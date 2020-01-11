<h1><u><?php echo $company_name.' ('.$company_code.')' ?></u></h1>
<table border='1' class="pure-table">
<?php
	echo "<thead><tr >";
	echo "<th >Total no. of securities</th>";
	echo "<th >public proportion (in quantity)</th>";
	echo "<th >PE Details</th>";
	echo "<th >Category</th>";
	echo "<th >Year End</th>";
	echo "<th >52 week range</th>";
	echo "<th >Market lot</th>";
	echo "<th >Last AGM</th>";
	echo "<th >Listing Year</th>";
	echo "<th >Institution proportion (in quantity)</th>";
	echo "<th >Govt. proportion (in quantity)</th>";
	echo "<th >Sponsor proportion (in quantity)</th>";
	echo "<th >Foreign proportion (in quantity)</th>";
	echo "</tr></thead>";
		echo "<tr >";
		echo "<td >".number_format($total)."</td>";
		echo "<td >".number_format($public)."</td>";
		echo "<td ><table><tr ><td >".$PE1."</td><td >".$PE2."</td></tr><tr class='pure-table-odd'><td >".$PE3."</td><td >".$PE4."</td></tr><tr ><td >".$PE5."</td><td >".$PE6."</td></tr></table></td>";
		echo "<td >".$category."</td>";
		echo "<td >".$year_end."</td>";
		echo "<td >".$week_range."</td>";
		echo "<td >".$market_lot."</td>";
		echo "<td >".$last_agm."</td>";
		echo "<td >".$listing_year."</td>";
		echo "<td >".number_format($institute)."</td>";
		echo "<td >".number_format(intval($total*$govt / 100.0))."</td>";
		echo "<td >".number_format(intval($total*$sponsor / 100.0))."</td>";
		echo "<td >".number_format(intval($total*$foreign / 100.0))."</td>";
		echo "</tr>";
?>
</table>
<br/>
<center>
<table border="1" class="pure-table">
<thead><tr >
	<th width="100">Date</th>
	<th width="100">Volume </th>
	<th width="100">CP/LTP</th>
</tr></thead>
<?php
	date_default_timezone_set('Asia/Dhaka');
	$odd = 0;
	foreach($daywise as $info){
		$date = date_create($info['trade_date']);
		echo "<tr";
		if($odd == 1)
			echo " class='pure-table-odd'";
		$odd = 1 - $odd;
		echo "><td >".date_format($date,'d-m-Y')."</td><td>".number_format($info['volume'])."</td><td>".$info['ltp']."</td></tr>";
	}
?>
</table>
</center>