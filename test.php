<?php
include_once('/var/www/html/simple_html_dom.php');
function parse_company_detail($company_code){
	//echo $company_code."\n";
	$html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
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
                                print("AGM: ".$last_agm."\n");
                        }
                }
		foreach($a->find('tr td') as $x){
                        if (strstr($last, "Day's Range")!==FALSE){
                                $days_range = trim($x->plaintext);
                                print("Days Range: ".$days_range."\n");
                        }
                        else if (strstr($last, "52 Weeks' Moving Range")!==FALSE){
                                $week_range = trim($x->plaintext);
                                print("Week Range: ".$week_range."\n");
                        }
                        else if (strstr($last, "Total No. of Outstanding Securities")!==FALSE){
                                $total = intval(str_replace(',','',$x->plaintext));
                                print("Total: ".$total."\n");
                        }
                        else if($last == 'Market Lot'){
                                sscanf($x->plaintext,"%d",$market_lot);
                                print("market lot: ".$market_lot."\n");
                        }
                        else if (strstr($last, "Year End")!==FALSE){
                                $year_end = trim($x->plaintext);
                                print("Year end: ".$year_end."\n");
                        }
                        else if(strstr($last,'Listing Year')){
                                sscanf($x->plaintext,"%d",$listing_year);
                                print("listing year: ".$listing_year."\n");
                        }
                        else if(strstr($last,'Market Category')){
                                $category = trim($x->plaintext);
                                print("Category: ".$category."\n");
                        }
                        else if(strstr($last, 'Current P/E Ratio using Basic EPS')){
                                $st = trim($x->parent()->last_child()->plaintext);
                                if ($st == '-')
                                        $val = 0;
                                else
                                        $val = int($st);
                                $pe1[] = $val;
                        }
                        else if(strstr($last, 'Current P/E ratio using Diluted EPS')){
                                $st = trim($x->parent()->last_child()->plaintext);
                                if ($st == '-')
                                        $val = 0;
                                else
                                        $val = int($st);
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
                                print($total." ".$public." ".$institute." ".$sponsor." ".$foreign." ".$govt."\n");
                                $percantage = false;
                        }
                        
                        $last = $x->plaintext;
                }
        }
        print($pe1);
        print($pe2);
	// $last = '';
	// $html->clear();
	// $html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
	// foreach($html->find('table table table td') as $a){
	// 	if(strstr($last,'Market Category')){
	// 		sscanf($a->plaintext,"%s",$category);
	// 		$a->clear();
	// 		unset($a);
	// 		break;
	// 	}
	// 	$last = $a->plaintext;
	// 	$a->clear();
	// 	unset($a);
	// }
	// $html->clear();
	// unset($html);
	// unset($last);
	// $con = mysql_connect('localhost','root','Allahisalmighty');
	// mysql_select_db('dsebd',$con);
	// if(mysql_error()){
	// 	echo "Fail to connect in $company_code \n";
	// 	return;
	// }
	// //$company_code1 = str_replace(array("(",")","&","-","\\","/"),'',$company_code);
	// $query = "select * from stock_data_detail where company_code='$company_code';";
	// //echo $query."\n";
	// $result = mysql_query($query);
	// if(mysql_fetch_array($result)){
	// 	$query = "update stock_data_detail set total=$total, public=$public, category='$category', year_end=$year_end, week_range='$week_range', institute=$institute, govt=$govt, sponsor=$sponsor, forgn=$foreign, market_lot=$market_lot, last_agm='$last_agm', listing_year=$listing_year where company_code='$company_code';";
	// 	mysql_query($query);
	// //	echo $query."\n";
	// }
	// else{
	// 	$query = "insert into stock_data_detail values('$company_code',$total, $public, '$category', $year_end, '$week_range', $institute, $govt, $sponsor, $foreign, $market_lot, '$last_agm', $listing_year );";
	// 	mysql_query($query);
	// }
	// if(mysql_error()){
	// 	echo $query.' '.mysql_error()."\n";
	// }
	// mysql_close($con);
	// unset($query);
	// unset($result);
	// unset($con);
}
parse_company_detail('ABB1STMF');
?>

