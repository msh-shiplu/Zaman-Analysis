<?php
	include_once('simple_html_dom.php');
	$con = mysql_connect('localhost','root','Allahisalmighty');
	mysql_select_db('dsebd',$con);
	
	$xml = file_get_html('/var/www/html/dsebd/company_list.xml');
	foreach($xml->find('industry') as $industry){
		foreach($industry->find('company') as $company){
			$table = $company->code.'_table';
			mysql_query('delete from '.$table.' where trade_date=\'2014-10-17\'');	
		}
	}

?>