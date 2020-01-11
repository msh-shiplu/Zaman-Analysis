
<?php
//gc_disable();
//ob_start();
include_once('simple_html_dom.php');
include_once('helper_functions.php');
error_reporting(E_ALL & ~E_WARNING);
$status = 0;
function parse_from_more_info($adr){
	$con = mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	//echo $adr."**\n";
	if(mysql_error()){
		echo "Fail to connect in $adr \n";
		return;
	}
	
//	echo $adr."\n";
//	$html = file_get_html($adr);
	$html =	connect_for($adr,5);
	if($html == NULL) return ;
//	echo $adr."\n";
	$flag = 0;
	global $status;
	foreach($html->find('table table table tr') as $a){
		//echo $a->plaintext.'<br/>';
		if($flag==0 && strstr($a->plaintext,'#')){
			$flag = 1;
			$a->clear();
			unset($a);
			continue;
		}
		if($flag==0){
			$a->clear();
			unset($a);
			continue;
		}
		if($a->find('p')){
			$a->clear();
			unset($a);
			break;
		}
	//	if(strstr($a->children(0)->plaintext,'#'))
	//		continue;
		sscanf($a->children(1)->plaintext,"%s",$company_code);
		if($status==1)
			sscanf($a->children(2)->plaintext,"%lf",$ltp);
		else
			sscanf($a->children(5)->plaintext,"%lf",$ltp);
	//	echo $company_code.' '.$ltp."\n";
		sscanf($a->children(10)->plaintext,"%d",$volume);
		$a->clear();
		unset($a);
		$company_code = str_replace(array("(",")","&","-","\\","/"),'',$company_code);
		//echo $company_code."\n";
		$table = $company_code.'_table';
		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))==0){
			$query = 'create table '.$table.' (trade_date DATE, LTP DOUBLE(10,2),volume INT, PRIMARY KEY(trade_date))';
			mysql_query($query);
			if(mysql_error()){
				echo $table.": Fail to create table\n";
				return;
			}
		}
		$today = date('Y-m-d');
//		$today = '2016-06-20';
		$query = 'select * from '.$table.' where trade_date =\''.$today.'\'';
		$result = mysql_query($query);
		if(mysql_fetch_array($result)){
			$query = 'update '.$table.' set LTP='.$ltp.', volume='.$volume.' where trade_date=\''.$today.'\'';
//			echo $query."\n";
			mysql_query($query);
		}
		else{
			$query = 'insert into '.$table.' values(\''.$today.'\','.$ltp.','.$volume.')';
			mysql_query($query);
		}
		if(mysql_error()){
			echo $table.' '.$query.' '.mysql_error()."\n";
		}
	//	echo $a->children(1)->plaintext.' LTP: '.$a->children(2)->plaintext.' Volume: '.$a->children(10)->plaintext.'<br/>';
	}
	$html->clear();
	unset($html);
}

function do_your_job(){
//	$html = file_get_html('http://dsebd.org/by_industrylisting1.php');
	$html =	connect_for('http://dsebd.org/by_industrylisting1.php',5);
	$pid = array();
        $vis = array();
	$pc = 0;
	$startFlag = 0;
	date_default_timezone_set('Asia/Dhaka');
	foreach($html->find('body table tr td table tr td table tr td table tr td table tr') as $a){
		if(!$a->children(1)) continue;
		$industry = str_replace('&nbsp;','',$a->children(1)->plaintext);
		//sscanf($a->children(1)->plaintext,"%s",$industry);
		$industry = trim($industry);
		//echo $industry."\n";
			if(strstr($industry,'Name of the Industry')){
				$startFlag = 1;
				continue;
			}
			if(!$startFlag) continue;
 		if(isset($vis[$industry])){
			continue;
		}
		$vis[$industry] = 1;
		if(strstr($industry,'Name of the Industry') || strstr($industry,'Treasury Bond') || strstr($industry,'Total Companies:')){
			$a->clear();
			unset($a);
			continue;
		}
		//$adr = 'http://dsebd.org/'.$a->children(3)->children(0)->href;
		//echo $adr."\n";
		//echo $a->children(1)->plaintext.' '.$adr.'<br/>';
	/*	while($pc>3){
			//echo "Will wait $pc and ".date("h:i:s a")."\n";
			pcntl_wait($status);
			$pc--;
		//	echo "Exiting wait $pc and ".date("h:i:s a")."\n";
		}*/
		//$pid[$pc] = pcntl_fork();
	//	if($pid[$pc++]==0){	
			parse_from_more_info('http://dsebd.org/'.$a->children(3)->children(0)->href);
			$a->clear();
			unset($a);
			//unset($pid);
		//	exit();
		//}
		//$a->clear();
		//unset($a);
	}
	$html->clear();
	unset($html);
//	echo $pc."\n";
//	for($i = 0;$i<$pc;$i++)
//		pcntl_wait($status);
	unset($pid);
}

$cur_update = get_update_time();
$fp = fopen("/var/www/html/dsebd/updated_time.txt","r");
if(!$fp)
	exit;
$st = fread($fp,100);
if($st == $cur_update)
	exit;
echo $st.' =>  '.$cur_update."\n";
fclose($fp);
$fp = fopen("/var/www/html/dsebd/updated_time.txt","w");
if(!$fp)
	exit;
fwrite($fp,$cur_update);
fclose($fp);
$status = market_status();
do_your_job();

?>
