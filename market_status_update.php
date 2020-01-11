
<?php
include_once('helper_functions.php');
$st = market_status();
$fp = fopen("/var/www/html/dsebd/market_status.txt","r");
if(!$fp) exit;
fscanf($fp,"%d",$st1);
if($st1 == $st) exit;
fclose($fp);
$fp = fopen("/var/www/html/dsebd/market_status.txt","w");
if(!$fp) exit;
fprintf($fp,"%d",$st);
fclose($fp);
?>

