<?php
include_once('simple_html_dom.php');
include_once('helper_functions.php');
gc_disable();
date_default_timezone_set('Asia/Dhaka');
//error_reporting(E_ALL & ~E_WARNING);
function parse_company_detail($company_code){
	echo $company_code."\n";
	$html = file_get_html('https://dsebd.org/displayCompany.php?name='.$company_code);
	$last = '';
	$agm_flag = true;
	$percantage = true;
	$pecount1 = 0;
	foreach($html->find("#company") as $a){
		if ($agm_flag){
				$st = strstr($a->plaintext, 'Last AGM held on:');
				if ($st!==FALSE){
						// if (strlen($st)>)
						$st = trim(substr($st,18, 10));
						if(strlen($st)==0)
			$last_agm = 'n/a';
		else
			$last_agm=$st;
						$agm_flag = false;
						// print("AGM: ".$last_agm."\n");
				}
		}
		foreach($a->find('tr td') as $x){
			if (strstr($last, "Day's Range")!==FALSE){
					$days_range = trim($x->plaintext);
					// print("Days Range: ".$days_range."\n");
			}
			else if (strstr($last, "52 Weeks' Moving Range")!==FALSE){
					$week_range = trim($x->plaintext);
					// print("Week Range: ".$week_range."\n");
			}
			else if (strstr($last, "Total No. of Outstanding Securities")!==FALSE){
					$total = intval(str_replace(',','',$x->plaintext));
					// print("Total: ".$total."\n");
			}
			else if($last == 'Market Lot'){
					sscanf($x->plaintext,"%d",$market_lot);
					// print("market lot: ".$market_lot."\n");
			}
			else if (strstr($last, "Year End")!==FALSE){
					$year_end = trim($x->plaintext);
					// print("Year end: ".$year_end."\n");
			}
			else if(strstr($last,'Listing Year')){
					sscanf($x->plaintext,"%d",$listing_year);
					// print("listing year: ".$listing_year."\n");
			}
			else if(strstr($last,'Market Category')){
					$category = trim($x->plaintext);
					// print("Category: ".$category."\n");
			}
			else if(strstr($last, 'Current P/E Ratio using Basic EPS')){
					$st = trim($x->parent()->last_child()->plaintext);
					if ($st == '-')
							$val = 0;
					else
							$val = (double)$st;
					$pe1[] = $val;
			}
			else if(strstr($last, 'Current P/E ratio using Diluted EPS')){
					$st = trim($x->parent()->last_child()->plaintext);
					if ($st == '-')
							$val = 0;
					else
							$val = (double)$st;
					$pe2[] = $val;
			}
			else if($percantage && strstr($last,'Share Holding Percentage')){
					foreach($x->find('table tr td') as $y){
							$st = trim($y->plaintext);
							if (strstr($st, 'Sponsor')){
									// print("Sponsor: ".trim(substr($st, 18))."\n");
									sscanf(substr($st, 18), "%f", $sponsor);
							}
							else if(strstr($st, 'Govt')){
									// print("Govt: ".trim(substr($st, 6))."\n");
									sscanf(substr($st, 6), "%f", $govt);
							}
							else if(strstr($st, "Institute")){
									// print("Inst: ".trim(substr($st, 10))."\n");
									sscanf(substr($st, 10), "%f", $institute);
									$institute = (int)($institute * $total / 100);
							}
							else if(strstr($st, "Foreign")){
									// print("Foreign: ".trim(substr($st, 8))."\n");
									sscanf(substr($st, 8), "%f", $foreign);
							}
							else if(strstr($st, "Public")){
									// print("Public: ".trim(substr($st, 8))."\n");
									sscanf(substr($st, 8), "%f", $public);
									$public = (int)($public * $total / 100);
							}
					}
					// print($total." ".$public." ".$institute." ".$sponsor." ".$foreign." ".$govt."\n");
					$percantage = false;
			}
			
			$last = $x->plaintext;
		}
	}
	foreach($html->find("#company") as $a){
		foreach($a->find('tr th') as $x){
			$st = trim($x->plaintext);
				if (strstr($st, 'Company Name:'))
					$company_name = trim(substr($st, 14));
		}
	}
	$html->clear();
	unset($html);
	unset($last);
	$con = mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	if(mysql_error()){
		echo "Fail to connect in $company_code \n";
		return;
	}
	//$company_code1 = str_replace(array("(",")","&","-","\\","/"),'',$company_code);
	$query = "select * from stock_data_detail where company_code='$company_code';";
	//echo $query."\n";
	$result = mysql_query($query);
	if(mysql_fetch_array($result)){
		$query = "update stock_data_detail set total=$total, public=$public, category='$category', year_end='$year_end', days_range='$days_range', week_range='$week_range', institute=$institute, govt=$govt, sponsor=$sponsor, forgn=$foreign, market_lot=$market_lot, last_agm='$last_agm', listing_year=$listing_year where company_code='$company_code';";
		mysql_query($query);
	//	echo $query."\n";
	}
	else{
		$query = "insert into stock_data_detail values('$company_code',$total, $public, '$category', '$year_end','$days_range', '$week_range', $institute, $govt, $sponsor, $foreign, $market_lot, '$last_agm', $listing_year );";
		mysql_query($query);
	}
	if(mysql_error()){
		echo $query.' '.mysql_error()."\n";
	}

	$query = "select * from stock_data where company_code='$company_code';";
	//echo $query."\n";
	$result = mysql_query($query);
	if(mysql_fetch_array($result)){
		$query = "update stock_data set PE1=$pe1[0], PE2=$pe2[0], PE3=$pe1[1], PE4=$pe2[1] where company_code='$company_code';";
		mysql_query($query);
	//	echo $query."\n";
	}
	else{
		$query = "insert into stock_data values('$company_code','$company_name', $pe1[0], $pe2[0],$pe1[1], $pe2[1]);";
		mysql_query($query);
	}
	if(mysql_error()){
		echo $query.' '.mysql_error()."\n";
	}

	mysql_close($con);
	unset($query);
	unset($result);
	unset($con);
}

function parse_from_industry($adrs){
	$html = file_get_html('http://dsebd.org/'.$adrs->children(0)->href);
	//echo $adrs->children(0)->href."\n";
	foreach($html->find('table table table table table table font a') as $a){
		sscanf($a->href,"displayCompany.php?name=%s",$company_code);
		parse_company_detail($company_code);
		$a->clear();
		unset($a);
	}
	$html->clear();
	unset($html);
}
function do_your_job(){
	$html = file_get_html('http://dsebd.org/by_industrylisting1.php');
	$pid = array();
	$pc = 0;
		$startFlag = 0;
	date_default_timezone_set('Asia/Dhaka');
	foreach($html->find('body table tr td table tr td table tr td table tr td table tr') as $a){
		if(!$a->children(1)) continue;
		$industry = str_replace('&nbsp;','',$a->children(1)->plaintext);
		//sscanf($a->children(1)->plaintext,"%s",$industry);
		$industry = trim($industry);
		
			if(strstr($industry,'Name of the Industry')){
				$startFlag = 1;
				continue;
			}
			if(!$startFlag) continue;
		if(strstr($industry,'Name of the Industry') || strstr($industry,'Treasury Bond') || strstr($industry,'Total Companies:')){
			$a->clear();
			unset($a);
			continue;
		}
		while($pc>10){
			pcntl_wait($status);
			$pc--;
		}
		$pid[$pc] = pcntl_fork();
		if($pid[$pc++]==0){	
			parse_from_industry($a->children(1));
			$a->clear();
			unset($a);
			unset($pid);
			$html->clear();
			unset($html);
			exit();
		}
		$a->clear();
		unset($a);
	}
	$html->clear();
	unset($html);
//	echo $pc."\n";
	for($i = 0;$i<$pc;$i++)
		pcntl_wait($status);
	unset($pid);
}

do_your_job();
/*
while(1){
	$cur_time = localtime();
	$ct = $cur_time[2]*100 + $cur_time[1];
	if($ct>=1700 && $ct<=1730){
		do_your_job();
		sleep(24*60*60);
	}
	else
		sleep(10*60);
}*/
?>
