<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Parse DSE Company</title>
</head>

<body>
<?php
include_once('simple_html_dom.php');
function parse_from_company($adrs){
	$html = file_get_html($adrs);
	$ret = array();
	//$ret = $html->find('font',0);
/*	foreach($html->find('table div center table div b font u') as $a){
		//echo $a->plaintext.'<br/>';
		$ret['company'] = trim($a->plaintext);
	}*/
	$last = '';
	foreach($html->find('font') as $a){
	//$last = trim($last);
	//echo $last.'<br/>';

	 if($last == 'Last Trade:'){
		//echo 'LTP: '.$a->plaintext.'<br/>';
		sscanf($a->plaintext,"%f",$ltp);
		$ret['ltp'] = $ltp;
	 }
/*	 else if($last == 'Close Price'){
		//echo 'CP: '.$a->plaintext.'<br/>';
		sscanf($a->plaintext,"%f",$cp);
		$ret['cp'] = $cp;
	 }*/
	 else if($last == 'Volume'){
		$volume = intval(str_replace(',','',$a->plaintext));
		//echo 'Volume: '.$volume.'<br/>';
		$ret['volume'] = $volume;
		break;
	 }
	/* else if(strstr($a->plaintext,'Last AGM Held:') && strlen($a->plaintext)>16){
		sscanf($a->plaintext,"%s",$agm);
	//	echo 'Last AGM Held: '.substr($a->plaintext,15,10).'<br/>';
		$ret['agm'] = $agm;
	 }
	 else if($last == 'Year End&nbsp;'){
		//echo 'Year End: '.$a->plaintext.'<br/>';
		sscanf($a->plaintext,"%s",$yearend);
		$ret['yearend'] = $yearend;
	 }
	 else if($last == 'Total no. of Securities'){
		$total = intval(str_replace(',','',$a->plaintext));
		//echo 'Total Securities: '.$total.'<br/>';
		$ret['total'] = $total;
	 }
	 else if(strstr($a->plaintext,'Public')){
		sscanf($a->plaintext,"Public %f",$public);
		//echo 'Public Portion: '.(int)($total*$public / 100.0).'<br/>';
		$ret['public'] = (int)($total*$public / 100.0);
	 }
	 else if(strstr($a->plaintext,'Institute')){
		sscanf($a->plaintext,"Institute %f",$institute);
		//echo 'Institute Portion: '.(int)($total*$institute / 100.0).'<br/>';
		$ret['institute'] = (int)($total*$institute / 100.0);
	 }
	 else if(strstr($a->plaintext,'Govt.')){
		sscanf($a->plaintext,"Govt.%f",$govt);
		//echo 'Govt. Portion: '.(int)($total*$govt / 100.0).'<br/>';
		$ret['govt'] = (int)($total*$govt / 100.0);
	 }
	 else if(strstr($a->plaintext,'Sponsor')){
		sscanf($a->plaintext,"Sponsor/Director %f",$spons);
	//	echo 'Sponsor Portion: '.(int)($total*$spons / 100.0).'<br/>';
		$ret['sponsor']=(int)($total*$spons / 100.0);
	 }
	 else if(strstr($a->plaintext,'Foreign')){
		sscanf($a->plaintext,"Foreign %f",$foreign);
		//echo 'Foreign Portion: '.(int)($total*$foreign / 100.0).'<br/>';
		$ret['foreign']=(int)($total*$foreign / 100.0);
	 }
	 else if($last == 'Market Lot'){
		//echo 'Market Lot: '.$a->plaintext.'<br/>';
		sscanf($a->plaintext,"%d",$market_lot);
		$ret['market_lot'] = $market_lot;
	 }
	 else if($last == '52 Week\'s Range'){
		//echo '52 Week\'s Range: '.$a->plaintext.'<br/>';
		sscanf($a->plaintext,"%f - %f",$t1,$t2);
		$ret['range'] = array($t1,$t2);
	 }*/
	$last = $a->plaintext;
	}/*
	$last = '';
	$PE = array();
	$flag = 0;
	$idx = 0;
	foreach($html->find('table table td') as $a){
		if($flag == 0 && strstr($a->plaintext,'Current Price Earning Ratio')){
			$flag ++;
		}
		if($flag>10) break;
		if($flag==2 || $flag == 3 || $flag == 5 || $flag == 6 || $flag == 8 || $flag == 10){
			$PE[$idx++] = floatval(str_replace('&nbsp;','',$a->plaintext));
		}
		if($flag>0) $flag ++;

		$last = $a->plaintext;
	}
	$ret['PE'] = $PE;
/*	echo 'Price Earning Ratio:';
	foreach($PE as $a){
	echo ' '.$a;
	}
	echo '<br/>';*/
	return $ret;
}
function parse_from_more_info($adr){
	$con = mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	if(mysql_error()){
		echo 'Fail to connect in $adr <br/>';
		return;
	}
	
	echo $adr.'<br/>';
	$html = file_get_html($adr);
	$flag = 0;
	foreach($html->find('table table table tr') as $a){
		//echo $a->plaintext.'<br/>';
		if($flag==0 && strstr($a->plaintext,'#')){
			$flag = 1;
			continue;
		}
		if($flag==0) 
			continue;
		if($a->find('p'))
			break;
	//	if(strstr($a->children(0)->plaintext,'#'))
	//		continue;
		sscanf($a->children(1)->plaintext,"%s",$company_code);
		sscanf($a->children(2)->plaintext,"%d",$ltp);
		sscanf($a->children(1)->plaintext,"%d",$volume);
		$table = $company_code.'_table';
		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))==0){
			$query = 'create table '.$table.' (trade_date DATE, LTP DOUBLE(10,2),volume INT, PRIMARY KEY(trade_date))';
			mysql_query($query);
			if(mysql_error()){
				echo 'Fail to create table<br/>';
				return;
			}
		}
		$today = date('Y-m-d');
		$query = 'select * from '.$table.' where trade_date =\''.$today.'\'';
		$result = mysql_query($query);
		if(mysql_fetch_array($result)){
			$query = 'update '.$table.' set LTP='.$ltp.', volume='.$volume.' where trade_date=\''.$today.'\'';
			mysql_query($query);
		}
		else{
			$query = 'insert into '.$table.' values(\''.$today.'\','.$ltp.','.$volume.')';
			mysql_query($query);
		}
	//	echo $a->children(1)->plaintext.' LTP: '.$a->children(2)->plaintext.' Volume: '.$a->children(10)->plaintext.'<br/>';
	}
}
$html = file_get_html('http://dsebd.org/by_industrylisting1.php');
$data = array();
date_default_timezone_set('Asia/Dhaka');
foreach($html->find('table tr td table tr td table tr td center table tr td font table tr') as $a){
	$txt = $a->children(1)->plaintext;
	$txt = str_replace('$nbsp;','',$txt);
	//sscanf(,"%s",$industry);
	$industry = trim($txt);
	echo 'Industry='.$industry.'<br/>';
	if(strstr($industry,'Name of the Industry') || strstr($industry,'Treasury Bond') || strstr($industry,'Total Companies:')){
		continue;
	}
	//$adr = 'http://dsebd.org/'.$a->children(1)->children(0)->href;
	//echo $a->children(1)->plaintext.' '.$adr.'<br/>';
	$pid = pcntl_fork();
	if($pid==0){	
		parse_from_more_info('http://dsebd.org/'.$a->children(3)->children(0)->href);
		exit();
	}
	/*
	$comp = file_get_html($adr);
	$industry_list = array();
	foreach($comp->find('table table table table table table font a') as $b){
		sscanf($b->href,"displayCompany.php?name=%s",$company_code);
		echo $company_code.'<br/>';
		
	//	$industry_list[$company_code] = parse_from_company('http://dsebd.org/company_details_nav.php?name='.$company_code);
	//	echo $company_code.': LTP = '.$industry_list[$company_code]['ltp'].' Volume = '.$industry_list[$company_code]['volume'].'<br/>';
	}
	$data[$industry] = $industry_list;*/
}
pcntl_wait($status);
?>
</body>
</html>
