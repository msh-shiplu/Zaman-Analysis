<h1><u><?php echo $index_name; ?></u></h1>

<table border="1">
<tr height="50" align='center'>
	<th width="100">Date</th>
	<th width="100"> <?php echo $index_name;?> </th>
</tr>
<?php
	date_default_timezone_set('Asia/Dhaka');
	foreach($data as $info){
		$date = date_create($info['trade_date']);
		echo "<tr height='50' align='center'><td width='100'>".date_format($date,'d-m-Y')."</td><td width='100'>".$info[$index_code];
		if($index_code == 'total_value')
			echo " mn";
		echo "</td></tr>";
	}
?>
</table>