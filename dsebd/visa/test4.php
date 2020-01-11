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

$curl = curl_init();
$param = array(
		'fileno' => 'BGDR33374915',
		'APPLNAME' => 'HULBUL',
		'PIA' => 'BGDR',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => '07/04/2015');
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_COOKIE => $str,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => preparePostFields($param)
));
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

$curl2 = curl_init();
$param = array(
		'fileno' => 'BGDR33374815',
		'APPLNAME' => 'MD MOTIUR',
		'PIA' => 'BGDR',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => '07/04/2015');
curl_setopt_array($curl2,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_COOKIE => $str,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => preparePostFields($param)
));
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//if(!($resp2 = curl_exec($curl2))){
//    die('Error: "' . curl_error($curl2) . '" - Code: ' . curl_errno($curl2));
//}
//$resAry = array();
//$resAry = json_decode($resp);
//echo "<pre>"; print_r($resAry); echo "</pre>";
$resp2 = curl_exec($curl2);
echo $resp2;
curl_close($curl2);

$curl1 = curl_init();
$param = array(
		'fileno' => 'BGDR33375415',
		'APPLNAME' => 'ARIFUL',
		'PIA' => 'BGDR',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => '07/04/2015');
curl_setopt_array($curl1,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_COOKIE => $str,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => preparePostFields($param)
));
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//if(!($resp1 = curl_exec($curl1))){
//    die('Error: "' . curl_error($curl1) . '" - Code: ' . curl_errno($curl1));
//}
//$resAry = array();
//$resAry = json_decode($resp);
//echo "<pre>"; print_r($resAry); echo "</pre>";
$resp1 = curl_exec($curl1);
echo $resp1;
curl_close($curl1);

?>
</body>
</html>