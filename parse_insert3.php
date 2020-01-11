<?php
include_once('simple_html_dom.php');
include_once('helper_functions.php');
gc_disable();
date_default_timezone_set('Asia/Dhaka');
//error_reporting(E_ALL & ~E_WARNING);
function parse_company_detail($company_code){
	//echo $company_code."\n";
	$html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
	$last = '';
	$agm_flag = true;
	foreach($html->find('font') as $a){
		//$last = trim($last);
		//echo $last.'<br/>';
		 if(strstr($a->plaintext,'Last AGM Held:') && $agm_flag){
			if(strlen($a->plaintext)>16)
				$last_agm = trim(substr($a->plaintext,15,10));
			else 
				$last_agm='n/a';
			$agm_flag = false;
			
		 }
		 else if($last == 'Year End&nbsp;'){
			sscanf($a->plaintext,"%d",$year_end);
		 }
		 else if($last == 'Total no. of Securities'){
			$total = intval(str_replace(',','',$a->plaintext));
		 }
		 else if(strstr($a->plaintext,'Public')){
			sscanf($a->plaintext,"Public %f",$public_f);
			$public = (int)($total*$public_f / 100.0);
		 }
		 else if(strstr($a->plaintext,'Institute')){
			sscanf($a->plaintext,"Institute %f",$institute_f);
			$institute = (int)($total*$institute_f / 100.0);
		 }
		 else if(strstr($a->plaintext,'Govt.')){
			sscanf($a->plaintext,"Govt.%f",$govt);
		 }
		 else if(strstr($a->plaintext,'Sponsor')){
			sscanf($a->plaintext,"Sponsor/Director %f",$sponsor);
		 }
		 else if(strstr($a->plaintext,'Foreign')){
			sscanf($a->plaintext,"Foreign %f",$foreign);
		 }
		 else if($last == 'Market Lot'){
			sscanf($a->plaintext,"%d",$market_lot);
		 }
		 else if($last == '52 Week\'s Range'){
			$week_range = trim($a->plaintext);
		 }
		 else if(strstr($last,'Listing Year')){
			sscanf($a->plaintext,"%d",$listing_year);
		}
		$last = $a->plaintext;
		$a->clear();
		unset($a);
		
	}
	$last = '';
	$html->clear();
	$html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
	foreach($html->find('table table table td') as $a){
		if(strstr($last,'Market Category')){
			sscanf($a->plaintext,"%s",$category);
			$a->clear();
			unset($a);
			break;
		}
		$last = $a->plaintext;
		$a->clear();
		unset($a);
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
		$query = "update stock_data_detail set total=$total, public=$public, category='$category', year_end=$year_end, week_range='$week_range', institute=$institute, govt=$govt, sponsor=$sponsor, forgn=$foreign, market_lot=$market_lot, last_agm='$last_agm', listing_year=$listing_year where company_code='$company_code';";
		mysql_query($query);
	//	echo $query."\n";
	}
	else{
		$query = "insert into stock_data_detail values('$company_code',$total, $public, '$category', $year_end, '$week_range', $institute, $govt, $sponsor, $foreign, $market_lot, '$last_agm', $listing_year );";
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
