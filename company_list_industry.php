
<?php
include_once('simple_html_dom.php');
include_once('helper_functions.php');
//error_reporting(E_ALL & ~E_WARNING);
//ini_set('max_execution_time', '600');
$txt = '';
$pid = array();
$pc = 0;
function parse_from_company($company_code){
	$adrs = 'http://dsebd.org/company_details_nav.php?name='.$company_code;
	//echo $adrs."\n";
	$html = file_get_html($adrs);
	for ($i = 0;$i<5 && $html===FALSE;$i++)
		$html = file_get_html($adrs);
	if ($html === FALSE)
		return;
	$company_name = '';
	//$ret = $html->find('font',0);
	foreach($html->find('table div center table div b font u') as $a){
		//echo $a->plaintext.'<br/>';
		$company_name = trim($a->plaintext);
		$a->clear();
		unset($a);
		break;
	}
//	echo $company_name;
/*	$last = '';
	$PE = array();
	$flag = 0;
	$idx = 0;
	foreach($html->find('table table td') as $a){
		if($flag == 0 && strstr($a->plaintext,'Current Price Earning Ratio')){
			$flag ++;
		}
		if($flag>10){
			$a->clear();
			unset($a);
			break;
		}
		if($flag==2 || $flag == 3 || $flag == 5 || $flag == 6 || $flag == 8 || $flag == 10){
			$PE[$idx++] = floatval(str_replace('&nbsp;','',$a->plaintext));
		}
		if($flag>0) $flag ++;

		$last = $a->plaintext;
		$a->clear();
		unset($a);
	}
//	print_r($PE);
	$html->clear();
	unset($html);
	global $pc;
	global $txt;
	global $pid;*/
	global $txt;
	$company_code2= $company_code;
	$company_code = str_replace(array("(",")","&","-","\\","/"),'',$company_code);
	$txt = $txt."<company code='$company_code'>$company_code2</company>\n";
	echo $company_code."\n";
/*	while($pc>10){
		pcntl_wait($status);
		$pc--;
	}
	$pid[$pc] = pcntl_fork();
	if($pid[$pc++] == 0){
		$con = mysql_connect('localhost','root','Allahisalmighty');
		mysql_select_db('dsebd',$con);
		if(mysql_error()){
			echo "Fail to connect in $company_code \n";
			exit();
		}
		$query = "select * from stock_data where company_code='$company_code'";
		$result = mysql_query($query);
		if(mysql_error()){
			echo $query.' '.mysql_error()."\n";
			exit();
		}
		if(mysql_fetch_array($result)){
			$query = 'update stock_data set PE1='.$PE[0].',PE2='.$PE[1].',PE3='.$PE[2].',PE4='.$PE[3].',PE5='.$PE[4].',PE6='.$PE[5]." where company_code='$company_code'";
			mysql_query($query);
		}
		else{
			$query = "insert into stock_data values('$company_code','$company_name',".$PE[0].','.$PE[1].','.$PE[2].','.$PE[3].','.$PE[4].','.$PE[5].');';
			mysql_query($query);
		}
		if(mysql_error()){
			echo $query.' '.mysql_error()."\n";
			exit();
		}
		//unset($pid);
		exit();
	}
	*/

}
function do_your_job(){
	global $pc;
	global $pid;
	global $txt;
	$html = file_get_html('http://dsebd.org/by_industrylisting1.php');
	if ($html === FALSE)
		return;
	$startFlag = 0;
    //foreach($html->find('table tr td table tr td table tr td center table tr td font table tr') as $a){
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
		//echo $industry."\n";
		//$txt = $txt."<industry name='$industry'>\n";
		$adr = 'http://dsebd.org/'.$a->children(1)->children(0)->href;
	//	echo $industry.' '.$adr."\n";
		$comp = file_get_html($adr);
		for ($i = 0;$i<5 && $comp===FALSE;$i++)
                	$comp = file_get_html($adrs);
        	if ($comp === FALSE)
                	continue;
		$txt = $txt."<industry name='$industry'>\n";
		foreach($comp->find('table table table table table table font a') as $b){
			sscanf($b->href,"displayCompany.php?name=%s",$company_code);
			
			parse_from_company($company_code);
			$b->clear();
			unset($b);
		}
		$comp->clear();
		unset($comp);
		$a->clear();
		unset($a);
		$txt = $txt."</industry>\n";
	}
	$html->clear();
	unset($html);
	echo $txt;
	//for($i = 0;$i<$pc;$i++)
	//	pcntl_wait($status,WUNTRACED);
	$filename = "/var/www/html/dsebd/company_list.xml";
	$fp = fopen($filename,"r");
	while( $fp === FALSE){
		sleep(30);
		$fp = fopen($filename,"r");
	}
	$st = fread($fp,filesize($filename));
	fclose($fp);
	if($st != $txt){
		$fp = fopen($filename,"w");
		while( $fp === FALSE){
			sleep(30);
			$fp = fopen($filename,"w");
		}
		fwrite($fp,$txt);
		fclose($fp);
	}
	unset($st);
	
	//unset($pid);
}
do_your_job();
/*
$last_status = 0;
while(1){
	$status = market_status();
	if($status == 1 && $last_status==0){
		sleep(5*60);
		do_your_job();
	}
	else if($status == 0 && $last_status==1){
		sleep(2*60);
		do_your_job();
	}
	$last_status = $status;
	sleep(10*60);
}*/
?>
