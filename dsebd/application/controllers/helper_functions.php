
<?php
include_once('/var/www/html/simple_html_dom.php');
function get_update_time(){
	$html = file_get_html('http://dsebd.org');
	foreach($html->find('table table div center table tr td b font') as $a){
		$ret = trim($a->plaintext);
		$html->clear();
		unset($html);
		$a->clear();
		unset($a);
		return $ret;
	}
	return '';
}
function market_status(){
	$html = file_get_html('http://www.dsebd.org');
	foreach($html->find('div table table tr td b font') as $a){
		//echo $a->plaintext.'<br/>';
		if(strstr($a->plaintext,'Market Status')){
			$ret = str_replace('&nbsp;',' ',$a->plaintext);
			sscanf($ret,"Market Status: %s",$ret);
			$a->clear();
			unset($a);
			break;
		}
		$a->clear();
		unset($a);
	}
	$html->clear();
	unset($html);
	if($ret == "Open")
		return 1;
	return 0;
}

?>

