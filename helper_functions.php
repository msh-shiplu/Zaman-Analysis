<?php
ini_set( 'default_socket_timeout', 40 );
include_once('/var/www/html/simple_html_dom.php');
function connect_for($site,$cnt){
	while($cnt > 0){
		$cnt--;
		$html = file_get_html($site);
		if($html != NULL)
			return $html;
	}
	return NULL;
}
//echo connect_for('http://www.dsebd.org',5);
function get_update_time(){
//        $html = file_get_html('http://www.dsebd.org');
	$html = connect_for('http://www.dsebd.org',5);
        foreach($html->find('div div div div div h2') as $a){
		$ret = str_replace('Last update on','',$a->plaintext);
                $ret = trim($ret);
               $html->clear();
               unset($html);
               $a->clear();
                unset($a);
                return $ret;
        }
        return '';
}
function market_status(){
//        $html = file_get_html('http://www.dsebd.org');
	$html =	connect_for('http://www.dsebd.org',5);
        foreach($html->find('div div div header div span') as $a){
                //echo $a->plaintext.'\n';
                if(strstr($a->plaintext,'Market Status')){
			$ret =trim($a->children(0)->plaintext);
                       // $ret = str_replace('&nbsp;',' ',$a->plaintext);
                        //sscanf($ret,"Market Status: %s",$ret);
                        $a->clear();
                        unset($a);
                        break;
                }
                $a->clear();
                unset($a);
        }
        $html->clear();
        unset($html);
        if($ret == "Closed")
                return 0;
	else  if($ret == "Open")
		return 1;
        return -1;
}
?>

