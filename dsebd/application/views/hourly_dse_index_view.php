<h2><u>DSE Index Information</u></h2>

<table border="1" class="pure-table">

<?php
	date_default_timezone_set('Asia/Dhaka');
	$slot = array('9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM');
	$idcode = array('dsex2'=>'DSE X','total_value'=>'Total value in Taka','issue_advanced'=>'Issue Advanced','issue_declined'=>'Issue Declined','issue_unchanged'=>'Issue Unchanged','dses2'=>'DSE S','dse302'=>'DSE 30');
	$col = array('at_930','at_1000','at_1030','at_1100','at_1130','at_1200','at_1230','at_1300','at_1330','at_1400','at_1430','at_1500');
	echo "<thead><tr><th></th>";
	foreach($data as $info){
		$i = 0;
		$f1 = 1;
		foreach($info as $a=>$b){
			if($i == 0){
				$i++;
				continue;
			}
			if($b === NULL){
				$i++;
				continue;
			}
		//	if($f1==1){ $f1 = 0; $i++; continue; }
			echo "<th >".$slot[$i-1]."</th>";
			$i++;
 		}
		break;
	}
	echo "</tr></thead>";
	
	$prev = array();
	$odd = 0;
	foreach($data as $info){
		if(strstr($info['index_code'],'1')){
			$prev = $info;
			continue;
		}
		if($odd == 1)
			echo "<tr class='pure-table-odd'>";
		else 
			echo "<tr>";
		$odd = 1 - $odd;
		$len = count($info);
		echo "<td>".$idcode[$info['index_code']]."</td>";
		if(strstr($info['index_code'],'2')){
			$f1 = 1;
			for($i = 0;$i<$len-1;$i++){			
				if($info[$col[$i]] === NULL){
					continue;
				}
		//		if($f1==1){ $f1 = 0; continue; }
				echo "<td >";
				printf("%.2lf",$prev[$col[$i]]);
				echo "<br/>";
				if($info[$col[$i]]>0)
					echo "+";
				printf("%.2lf",$info[$col[$i]]);
				echo "</td>";
			}
		}
		else {
			$f1 = 1;
			for($i = 0;$i<$len-1;$i++){			
				if($info[$col[$i]] === NULL){
					continue;
				}
		//		if($f1==1){ $f1 = 0; continue; }
				echo "<td >".intval($info[$col[$i]])."</td>";
			}
		}
		
		echo "</tr>";
	}
?>

</table>

