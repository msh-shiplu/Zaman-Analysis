<?php 
/*
$curl = curl_init();
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/',
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_HEADER => 1
));
$resp = curl_exec($curl);
//$resAry = array();
//$resAry = json_decode($resp);
//echo "<pre>"; print_r($resAry); echo "</pre>";
preg_match('/^Set-Cookie:\s*([^;]*)/mi', $result, $m);
parse_str($m[1], $cookies);
var_dump($cookies);*/
$ch = curl_init('http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp');
curl_setopt($ch, CURLOPT_REFERER, 'http://indianvisa-bangladesh.nic.in/visa/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// get headers too with this line
curl_setopt($ch, CURLOPT_HEADER, 1);
$result = curl_exec($ch);

$ptr = strpos($result,'Set-Cookie:');
$str='';
for($i = $ptr + 12;;$i++){
	if($result[$i]==';')
		break;
	$str=$str.$result[$i];
}
$fp = fopen('cookie.txt','w');
fprintf($fp,"%s",$str);
// get cookie
//preg_match('/^Set-Cookie:\s*([^;]*)/mi', $result, $m);

//parse_str($m[1], $cookies);
//var_dump($cookies);
//echo $result;
?>
