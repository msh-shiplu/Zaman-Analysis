<?php
error_reporting(E_ALL & ~E_WARNING);
include_once('simple_html_dom.php');
include_once('helper_functions.php');
function do_your_job(){
	$html = file_get_html('http://dsebd.org');
	if(!$html){
		unset($html);
		exit(-1);
	}
	$last = '';
	foreach($html->find('div div div div div div') as $a){
		if(!$a->children(0)){
			$a->clear();
			unset($a);
			continue;
		}
		if(trim($a->children(0)->plaintext) == "DSEX Index"){
			//echo "DSEX Index: ".$a->children(1)->plaintext." ".$a->children(2)->plaintext."<br/>";
			sscanf($a->children(1)->plaintext,"%lf",$dsex1);
			sscanf($a->children(2)->plaintext,"%lf",$dsex2);
		}
		else if(trim($a->children(0)->plaintext) == "DSES Index"){
			//echo "DSES Index: ".$a->children(1)->plaintext." ".$a->children(2)->plaintext."<br/>";
			sscanf($a->children(1)->plaintext,"%lf",$dses1);
			sscanf($a->children(2)->plaintext,"%lf",$dses2);
		}
		else if(trim($a->children(0)->plaintext) == "DS30 Index"){
			//echo "DS30 Index: ".$a->children(1)->plaintext." ".$a->children(2)->plaintext."<br/>";
			sscanf($a->children(1)->plaintext,"%lf",$dse301);
			sscanf($a->children(2)->plaintext,"%lf",$dse302);
		}
		else if($last == "Total Trade"){
			sscanf($a->children(2)->plaintext,"%d",$total_value);
		}
		else if($last == "Issues Advanced"){
			sscanf($a->children(0)->plaintext,"%d",$issue_advanced);
			sscanf($a->children(1)->plaintext,"%d",$issue_declined);
			sscanf($a->children(2)->plaintext,"%d",$issue_unchanged);
			$a->clear();
			unset($a);
			break;
		} 
		$last = trim($a->children(0)->plaintext);
		$a->clear();
		unset($a);
	}
	$html->clear();
	unset($html);
	unset($last);
	$con = mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	if(mysql_error()){
		echo "Fail to connect in $adr \n";
		exit(-1);
	}
	
	$today = date('Y-m-d');
	$query = 'select * from dse_index_info where trade_date =\''.$today.'\'';
	$result = mysql_query($query);
	if(mysql_error()){
		echo mysql_error();
		exit(-1);
	}
	if(mysql_fetch_array($result)){
		$query = 'update dse_index_info set dsex1='.$dsex1.', dsex2='.$dsex2.', total_value='.$total_value.', issue_advanced='.$issue_advanced.', issue_declined='.$issue_declined.', issue_unchanged='.$issue_unchanged.', dses1='.$dses1.', dses2='.$dses2.', dse301='.$dse301.', dse302='.$dse302.' where trade_date=\''.$today.'\'';
		mysql_query($query);
	}
	else{
		$query = 'insert into dse_index_info values(\''.$today.'\','.$dsex1.','.$dsex2.','.$total_value.','.$issue_advanced.','.$issue_declined.','.$issue_unchanged.','.$dses1.','.$dses2.','.$dse301.','.$dse302.')';
		mysql_query($query);
	}
	if(mysql_error()){
		echo $query.' '.mysql_error()."\n";
		exit(-1);
	}
	exit(0);
}
date_default_timezone_set('Asia/Dhaka');
$cur_update = get_update_time();
$fp = fopen("/var/www/html/dsebd/updated_time.txt","r");
if(!$fp)
	exit;
$st = fread($fp,100);
if($st == $cur_update)
	exit;
fclose($fp);
do_your_job();

?>
