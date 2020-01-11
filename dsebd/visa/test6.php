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

$curl = curl_init();
$param = array(
		'fileno' => 'BGDR33375615',
		'APPLNAME' => 'ROKON',
		'PIA' => 'BGDR',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => '07/04/2015');
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
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
?>
</body>
</html>