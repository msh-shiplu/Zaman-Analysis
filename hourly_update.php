<?php
include_once('simple_html_dom.php');
include_once('helper_functions.php');
error_reporting(E_ALL & ~E_WARNING);
date_default_timezone_set('Asia/Dhaka');
//$slots = array(1030,1100,1130,1200,1230,1300,1330,1400,1430,1500,1530,1600);
$slots = array(930,1000,1030,1100,1130,1200,1230,1300,1330,1400,1430,1500);
$col = array('at_930','at_1000','at_1030','at_1100','at_1130','at_1200','at_1230','at_1300','at_1330','at_1400','at_1430','at_1500');
$idcode = array('dsex1','dsex2','total_value','issue_advanced','issue_declined','issue_unchanged','dses1','dses2','dse301','dse302');
function do_your_job($idx){
//	echo $idx."\n";
	$con=mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	if(mysql_error()){
		echo mysql_error();
		return;
	}
	global $col;
	$html = file_get_html('/var/www/html/dsebd/company_list.xml');
	foreach($html->find('company') as $company){
		//echo $company->code."\n";
		$table = $company->code.'_table';
		$today = date('Y-m-d');
		$query = 'select * from '.$table.' where trade_date =\''.$today.'\'';
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		//var_dump($row);
		$query = 'select * from hourly_ltp where company_code=\''.$company->code.'\'';
		$result = mysql_query($query);
		if(mysql_fetch_array($result)){
			$query = 'update hourly_ltp set '.$col[$idx].'='.$row['LTP'].' where company_code=\''.$company->code.'\'';
			mysql_query($query);
			if(mysql_error()){
				echo $query.' '.mysql_error()."\n";
				$company->clear();
				unset($company);
				$html->clear();
				unset($html);
				return;
			}
			$query = 'update hourly_volume set '.$col[$idx].'='.$row['volume'].' where company_code=\''.$company->code.'\'';
			mysql_query($query);
			if(mysql_error()){
				$company->clear();
				unset($company);
				$html->clear();
				unset($html);
				echo $query.' '.mysql_error()."\n";
				return;
			}
		}
		else{
			$query = 'insert into hourly_ltp (company_code,'.$col[$idx].') values(\''.$company->code.'\','.$row['LTP'].');';
			mysql_query($query);
			if(mysql_error()){
				$company->clear();
				unset($company);
				$html->clear();
				unset($html);
				echo $query.' '.mysql_error()."\n";
				return;
			}
			$query = 'insert into hourly_volume (company_code,'.$col[$idx].') values(\''.$company->code.'\','.$row['volume'].');';
			mysql_query($query);
			if(mysql_error()){
				$company->clear();
				unset($company);
				$html->clear();
				unset($html);
				echo $query.' '.mysql_error()."\n";
				return;
			}
		}
		$company->clear();
		unset($company);
		if(mysql_error()){
			echo mysql_error()."\n";
		}
		
	}
	$today = date('Y-m-d');
	global $idcode;
	foreach($idcode as $index_code){
		$query = 'select * from dse_index_info where trade_date =\''.$today.'\'';
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$query = 'select * from hourly_index where index_code=\''.$index_code.'\'';
		$result = mysql_query($query);
		if(mysql_fetch_array($result)){
			$query = 'update hourly_index set '.$col[$idx].'='.$row[$index_code].' where index_code=\''.$index_code.'\'';
			mysql_query($query);
			if(mysql_error()){
				echo $query.' '.mysql_error()."\n";
				break;
				
			}
		}
		else{
			$query = 'insert into hourly_index (index_code,'.$col[$idx].') values(\''.$index_code.'\','.$row[$index_code].');';
			mysql_query($query);
			if(mysql_error()){
				
				echo $query.' '.mysql_error()."\n";
				break;
			}
		}
		if(mysql_error()){
			echo mysql_error()."\n";
		}
	}
	$html->clear();
	unset($html);
}
function delete_all(){
	$con=mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	if(mysql_error()){
		echo mysql_error();
		return;
	}
	$query = 'delete from hourly_ltp where 1';
	mysql_query($query);
	$query = 'delete from hourly_volume where 1';
	mysql_query($query);
	$query = 'delete from hourly_index where 1';
	mysql_query($query);
	mysql_close($con);
}
$length = count($col);
if(market_status() == 0) 
	exit;
sleep(150);
$cur_time = localtime();
if($cur_time[1]+4>60)
	$cur_time[2] ++;
$cur_time[1] += 4;
$cur_time[1]%=60;
$ct = $cur_time[2]*100 + $cur_time[1];
for($i = 1;$i<$length;$i++){
	if($ct>=$slots[$i-1] && $ct<$slots[$i]){
		do_your_job($i-1);
		break;
	}
}	
/*
$last_stat = market_status();
while(1){
	$cur_time = localtime();
	$ct = $cur_time[2]*100 + $cur_time[1];
	if($ct<900 || $ct>1500){
		sleep(30*60);
		continue;
	}
	if($ct>=900 && $ct<930){
		if($cur_time[6]<5)
			delete_all();
		sleep(10*60);
		continue;
	}
	$status = market_status();
	if($ct>=930 && $ct<=1000){
		delete_all();
		sleep(30*60);
		continue;
	}
	else if($last_stat == 0 && $status == 0){
	
	if($status == 0) {
		sleep(5*60);
		continue;
	}
	for($i = 1;$i<$length;$i++){
		if($ct>=$slots[$i-1] && $ct<$slots[$i]){
			do_your_job($i-1);
			sleep(30*60);
			break;
		}
	}
	$last_stat = $status;
	
}
*/
?>
