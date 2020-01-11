<?php
error_reporting(E_ALL & ~E_WARNING);
$con=mysql_connect('localhost','root','Allahisalmighty');
mysql_select_db('dsebd',$con);
if(mysql_error()){
	echo mysql_error();
	exit;
}
$query = 'delete from hourly_ltp where 1';
mysql_query($query);
if(mysql_error()){
	echo mysql_error();
	exit;
}
$query = 'delete from hourly_volume where 1';
mysql_query($query);
if(mysql_error()){
	echo mysql_error();
	exit;
}
$query = 'delete from hourly_index where 1';
mysql_query($query);
if(mysql_error()){
	echo mysql_error();
	exit;
}
mysql_close($con);

?>
