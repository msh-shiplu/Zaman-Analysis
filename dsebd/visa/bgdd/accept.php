<?php 
function preparePostFields($array1) {
  $params = array();

  foreach ($array1 as $key => $value) {
    $params[] = $key . '=' . urlencode($value);
  }

  return implode('&', $params);
}
function findCookie($result){
	$ptr = strpos($result,'Set-Cookie:');
	$str='';
	for($i = $ptr + 12;;$i++){
		if($result[$i]==';')
			break;
		$str=$str.$result[$i];
	}
	return $str;
}
$str = $_POST['cookie'];
$cap = $_POST['cap'];

$param = array(
		'pia' => 'BGDD1',
		'fileno' => 'BGDDVFD32E15',
		'birthdate' => '02/02/1986',
		'passport_no' => 'be0784034',
		'ImgNum' => $cap,
		'Date' => '31/03/2015');

$curl = curl_init();
curl_setopt($curl,CURLOPT_HTTPHEADER,array('User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0','Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','Accept-Language: en-US,en;q=0.5','Accept-Encoding: gzip, deflate','Connection: keep-alive'));
curl_setopt($curl,CURLOPT_COOKIE,$str);
curl_setopt($curl,CURLOPT_TIMEOUT,5);
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp',
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => preparePostFields($param)
));
$resp = curl_exec($curl);

$param = array(
		'fileno' => 'BGDDVFD32E15',
		'APPLNAME' => 'ABBAS',
		'PIA' => 'BGDD',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => '08/04/2015');
for($i = 0;$i<2;$i++){
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_POSTFIELDS => preparePostFields($param)
));

	$resp = curl_exec($curl);

curl_setopt($curl,CURLOPT_URL,'http://indianvisa-bangladesh.nic.in/visa/Visa_print_Form2.jsp?number=BGDDVFD32E15');
curl_setopt($curl,CURLOPT_POST,0);
curl_setopt($curl,CURLOPT_HEADER,1);
//curl_setopt($curl,CURLOPT_HEADER,1);
$resp = curl_exec($curl);/*
header('Cache-Control: private'); 
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="new.pdf"');
header('Content-Length: '.strlen($resp));
echo $resp;*/
file_put_contents('new'+$i+'.pdf',$resp);
curl_setopt($curl,CURLOPT_POST,1);
curl_setopt($curl,CURLOPT_HEADER,0);
}
curl_close($curl);
?>
