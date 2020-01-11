<html>
<body>
<?php
function preparePostFields($array1) {
  $params = array();

  foreach ($array1 as $key => $value) {
    $params[] = $key . '=' . urlencode($value);
  }

  return implode('&', $params);
}
/*
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
curl_close($ch);
*/
$curl = curl_init();
$param = array(
		'fileno' => 'BGDR33374915',
		'APPLNAME' => 'HULBUL',
		'PIA' => 'BGDR',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => '07/04/2015');
$fp = fopen('..\cookie.txt','r');
fscanf($fp,"%s",$str);
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_COOKIE => $str,
	CURLOPT_USERAGENT => 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2288.6 Safari/537.36',
	CURLOPT_ENCODING => 'gzip, deflate',
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => preparePostFields($param)
));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Accept-Language: en-US,en;q=0.8','Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'));
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//if(!($resp = curl_exec($curl))){
//    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
//}
//$resAry = array();
//$resAry = json_decode($resp);
//echo "<pre>"; print_r($resAry); echo "</pre>";
$resp = curl_exec($curl);
echo $resp;
curl_close($curl);

?>
</body>
</html>