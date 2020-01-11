<h2><u>Industry Name: <?php echo $industry_name; ?></u></h2>

<table border="1" class="pure-table">

<?php
	error_reporting(E_ALL & ~E_WARNING);
	
	date_default_timezone_set('Asia/Dhaka');
	$slot = array('9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM');
	$col = array('at_930','at_1000','at_1030','at_1100','at_1130','at_1200','at_1230','at_1300','at_1330','at_1400','at_1430','at_1500');
	$length = count($col);
	echo "<thead><tr ><th>Name of Stock</th>";
	$flag = 1;
	$i = 0;
	foreach($company_list as $comp){
		if(!isset($comp['ltp'])){
			$flag = 0;
			break;
		}
		$ltp = $comp['ltp'];
		$f1 = 1;
		for($i  = 0;$i<$length;$i++){
			if($ltp[$i] !== NULL){
			//	if($f1==1){ $f1 = 0; continue; }
				
				echo "<th >".$slot[$i].'</th>';
			}				
		}
		break;
	}
	/*$fp = fopen("/var/www/html/dsebd/market_status.txt","r");
	$st = 0;
	if($fp!=NULL){
		fscanf($fp,"%d",$st);*/
		if($market_status == 1)
			echo "<th >".date('g:i A')."</th>";
	/*}*/
	echo "</tr></thead>";
	if($flag){
		$odd = 0;
		$cc = 0;
		foreach($company_list as $comp){
			echo "<tr";
			if($odd == 1)
				echo " class='pure-table-odd'";
			if(array_key_exists('PE1', $comp) && array_key_exists('PE2', $comp))
				echo "><td ><sub>".$comp['PE1'].'/'.$comp['PE2'].'</sub>'.anchor('company/index/'.$comp['code'],$comp['name']);
			else
				echo "><td>".anchor('company/index/'.$comp['code'],$comp['name']);
			if(isset($comp['category']) && $comp['category']!='n/a')
				echo '<sub>'.$comp['category'].'</sub>';
			echo "</td>";
			$odd = 1 - $odd;
			if (array_key_exists('ltp', $comp)==FALSE || array_key_exists('volume', $comp)==FALSE){
				echo '</tr>';
				$cc ++;
				continue;
			}
			$ltp = $comp['ltp'];
			$volume = $comp['volume'];
			$k = -1;
			$f1 = 1;
			for($i = 0;$i<$length;$i++){
				if($ltp[$i] === NULL){ 
					continue;
				}
			//	if($f1==1){ $f1 = 0; continue; }
				echo "<td >".number_format($volume[$i])."<br/>".$ltp[$i];
				if($k>-1){
					echo '<br/>'.number_format($volume[$i] - $volume[$k]);
				}
				echo "</td>";
				$k = $i;
			}
			if($market_status == 1){
				$daywise = $company_list_current[$cc]['daywise'];
				foreach($daywise as $cur){
					echo "<td>".number_format($cur['volume'])."<br/>".$cur['ltp'];
					if($k>-1){
						echo '<br/>'.number_format($cur['volume'] - $volume[$k])."</td>";
					}
					break;
				}
			}
			echo "</tr>";
			$cc++;
		}
	}
?>

</table>

