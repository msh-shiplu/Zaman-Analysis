<?php
include_once('/var/www/html/simple_html_dom.php');
function parse_company_detail($company_code){
	//echo $company_code."\n";
	$html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
	$last = '';
        $agm_flag = true;
	foreach($html->find("#company") as $a){
                if ($agm_flag){
                        $st = strstr($a->plaintext, 'Last AGM held on:');
                        if ($st!==FALSE){
                                print(substr($st,0, 50));
                                $agm_flag = false;
                        }
                }
		foreach($a->find('tr td') as $x){
                        if (strstr($last, "Day's Range")!==FALSE){
                                print(trim($x->plaintext));
                        }
                        $last = $x->plaintext;
                }
	}
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
parse_company_detail('ABBANK');
?>

