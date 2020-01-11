<h2><u>DSE Index Information</u></h2>
<center>
<table border="1" class="pure-table">

<?php
	date_default_timezone_set('Asia/Dhaka');
	echo "<thead><tr ><th ></th>";
	foreach($data as $info){
		$date = date_create($info['trade_date']);
		echo "<th width = '130'>".date_format($date,'d-m-Y')."</th>";
	}
	echo "</tr></thead>";
	echo "<tr ><td >".anchor('dseindex/index/dsex','DSE X')."</td>";
	foreach($data as $info){
		echo "<td >";
		if($info['dsex2']>0)
			$sign = '+';
		else $sign='';
		printf("%.2lf",$info['dsex1']);
		echo '<br/>';
		printf("%s%.2lf",$sign,$info['dsex2']);
		echo "</td>";
		//echo "<td width = '130'>".$info['dsex1'].'<br/>'.$info['dsex2']."</th>";
	}
	echo "</tr>";
	echo "<tr class='pure-table-odd'><td >".anchor('dseindex/index/total_value','Total value in taka')."</td>";
	foreach($data as $info){
		echo "<td>".$info['total_value']." mn</td>";
	}
	echo "</tr>";
	
	echo "<tr ><td >".anchor('dseindex/index/issue_advanced','Issue Advanced')."</td>";
	foreach($data as $info){
		echo "<td >".$info['issue_advanced']."</td>";
	}
	echo "</tr>";
	
	echo "<tr class='pure-table-odd'><td>".anchor('dseindex/index/issue_declined','Issue Declined')."</td>";
	foreach($data as $info){
		echo "<td >".$info['issue_declined']."</td>";
	}
	echo "</tr>";
	
	echo "<tr><td>".anchor('dseindex/index/issue_unchanged','Issue Unchanged')."</td>";
	foreach($data as $info){
		echo "<td>".$info['issue_unchanged']."</td>";
	}
	echo "</tr>";
	echo "<tr class='pure-table-odd'><td>".anchor('dseindex/index/dses','DSE S')."</td>";;
	foreach($data as $info){
		echo "<td >";
		if($info['dses2']>0)
			$sign = '+';
		else $sign='';
		printf("%.2lf",$info['dses1']);
		echo '<br/>';
		printf("%s%.2lf",$sign,$info['dses2']);
		echo "</td>";
		//echo "<td width = '130'>".$info['dses1'].'<br/>'.$info['dses2']."</th>";
	}
	echo "</tr>";
	echo "<tr ><td >".anchor('dseindex/index/dse30','DSE 30')."</td>";
	foreach($data as $info){
		echo "<td >";
		if($info['dse302']>0)
			$sign = '+';
		else $sign='';
		printf("%.2lf",$info['dse301']);
		echo '<br/>';
		printf("%s%.2lf",$sign,$info['dse302']);
		echo "</td>";
		//echo "<td width = '130'>".$info['dse301'].'<br/>'.$info['dse302']."</th>";
	}
	echo "</tr>";
?>

</table>

</center>