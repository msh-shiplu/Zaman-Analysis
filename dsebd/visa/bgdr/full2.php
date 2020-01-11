
<?php
date_default_timezone_set('Asia/Dhaka');
declare(ticks = 1);
function sig_handler($signo){
	echo "Exiting...";
	exit;
}
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

$curl = array();
$pia = array();
$fileno = array();
$birthdate = array();
$passport_no = array();
$applname = array();
$cookie = array();
$fp = fopen("data.txt","r");
$app_date = trim(fgets($fp));
$i = 0;
while($line = fgets($fp)){
	sscanf($line,"%s %s %s %s %s",$pia[],$fileno[],$birthdate[],$passport_no[],$applname[]);
	$curl[$i] = curl_init('http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp');
	curl_setopt($curl[$i],CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl[$i],CURLOPT_HTTPHEADER,array('User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0','Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','Accept-Language: en-US,en;q=0.5','Accept-Encoding: gzip, deflate','Connection: keep-alive'));
	curl_setopt($curl[$i],CURLOPT_REFERER,'http://indianvisa-bangladesh.nic.in/visa/');
	curl_setopt($curl[$i],CURLOPT_HEADER,1);
	curl_setopt($curl[$i],CURLOPT_TIMEOUT,5);
	$i++;
}
for($j = 0;$j<$i;$j++){
	$resp = curl_exec($curl[$j]);
	if($resp && strlen($resp)>0){
		if(strpos($resp,'Please try after')){
			echo ".";
			$j--;
		}
		else
			$cookie[] = findCookie($resp);
	}
	else{
		echo "#";
		$j--;
	}

}
echo "\nEnter captcha's\n";
for($j = 0;$j<$i;$j++){
	curl_setopt($curl[$j],CURLOPT_URL,'http://indianvisa-bangladesh.nic.in/visa/Rimage.jsp');
	curl_setopt($curl[$j],CURLOPT_COOKIE,$cookie[$j]);
	curl_setopt($curl[$j],CURLOPT_TIMEOUT,5);
	curl_setopt($curl[$j],CURLOPT_HEADER,0);
	curl_setopt($curl[$j],CURLOPT_REFERER,'http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp');
	$resp = curl_exec($curl[$j]);
	file_put_contents('Captcha_'.$j.'.jpg',$resp);
}

$cap = array();
for($j = 0;$j<$i;$j++){
	echo 'Captcha '.$j.': ';
	$cap[] = trim(fgets(STDIN));
	curl_setopt($curl[$j],CURLOPT_URL,'http://indianvisa-bangladesh.nic.in/visa/ajaxCap.jsp?iNum='.$cap[$j]);
	$resp = curl_exec($curl[$j]);
	while($resp == "n"){
		echo 'Incorrect Code, Enter Correct Code of Captcha '.$j.': ';
		$cap[$j] = trim(fgets(STDIN));
		curl_setopt($curl,CURLOPT_URL,'http://indianvisa-bangladesh.nic.in/visa/ajaxCap.jsp?iNum='.$cap[$j]);
		$resp = curl_exec($curl[$j]);
	}
}



//curl_setopt($curl,CURLOPT_HTTPHEADER,array('User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0','Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','Accept-Language: en-US,en;q=0.5','Accept-Encoding: gzip, deflate','Connection: keep-alive'));
//curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
//curl_setopt($curl,CURLOPT_TIMEOUT,5);
//curl_setopt($curl,CURLOPT_REFERER,'http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp');
//curl_setopt($curl,CURLOPT_COOKIE,$str);

//curl_setopt($curl,CURLOPT_HEADER,1);

//$str = findCookie($resp);

//fprintf($stdout,"%s",trim($resp));
//fclose($stdout);
echo "submitting first form...\n";
for($j =0; $j<$i;$j++){
	$param = array(
		'pia' => $pia[$j].'1',
		'fileno' => $fileno[$j],
		'birthdate' => $birthdate[$j],
		'passport_no' => $passport_no[$j],
		'ImgNum' => $cap[$j],
		'Date' => date('d/m/Y'));

	curl_setopt_array($curl[$j],array(
		CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
		CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/Reprint.jsp',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => preparePostFields($param)
	));
	$resp = curl_exec($curl[$j]);
	if($resp && strlen($resp)>1){
		if(strpos($resp,$fileno[$j])){
			curl_setopt($curl[$j],CURLOPT_POST,0);
			continue;
		}
		else $j--;
	}
	else 
		$j--;
}
echo "Starting reloading captacha...\n";
pcntl_signal(SIGTERM,"sig_handler");
$pid = array();
for($j = 0;$j<$i;$j++){
	$pid[$j] = pcntl_fork();
	if($pid[$j] == 0)
		break;
}
if($j<$i){
	curl_setopt($curl[$j],CURLOPT_URL,'http://indianvisa-bangladesh.nic.in/visa/Rimage.jsp');
	curl_setopt($curl[$j],POST,0);
	while(true){
		$resp = curl_exec($curl[$j]);
		sleep(3);
	}
	exit;
}
echo 'Enter to stop reloading...';
fgets(STDIN);
for($j = 0;$j<$i;$j++){
	posix_kill($pid[$j],SIGTERM);
}

$sub = array();
for($j = 0;$j<$i;$j++){
	$param = array(
			'fileno' => $fileno[$j],
			'APPLNAME' => $applname[$j],
			'PIA' => $pia[$j],
			'next_page' => 'visa_print_Form2.jsp',
			'DATE' => $app_date);
	curl_setopt_array($curl[$j],array(
		CURLOPT_URL => 'http://indianvisa-bangladesh.nic.in/visa/allotmentsave.jsp',
		CURLOPT_REFERER => 'http://indianvisa-bangladesh.nic.in/visa/ReprintAppt.jsp',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => preparePostFields($param)
		));
	//$sub[] = new Submitter($curl[$j],$fileno[$j]);
}

echo 'Enter to start submitting';
fgets(STDIN);


for($j = 0;$j<$i;$j++){
	//$sub[$j]->start();
	$pid[$j] = pcntl_fork();
	if($pid[$j] ==0) break;
}
if($j<$i){
	curl_setopt($curl[$j],CURLOPT_HEADER,1);
	while(true){			
		$resp = curl_exec($curl[$j]);
		if($resp && strlen($resp)>0){
			curl_setopt($curl[$j],CURLOPT_URL,'http://indianvisa-bangladesh.nic.in/visa/Visa_print_Form2.jsp?number='.$fileno[$j]);
			curl_setopt($curl[$j],CURLOPT_POST,0);
			curl_exec($curl[$j]);
			break;
		}
	}
	exit;
}
for($j = 0;$j<$i;$j++){
	pcntl_waitpid($pid[$j],$status);
	curl_close($curl[$j]);
}
?>
