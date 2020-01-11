<?php
$curl = curl_init();
curl_setopt_array($curl,array(
	CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
	CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => array(
		'fileno' => 'BGDDVE340015',
		'APPLNAME' => 'ANIMESH',
		'PIA' => 'BGDD',
		'next_page' => 'visa_print_Form2.jsp',
		'DATE' => urlencode('30/03/2015')
));
$resp = curl_exec($curl);
echo $resp;
curl_close($curl);
?>
