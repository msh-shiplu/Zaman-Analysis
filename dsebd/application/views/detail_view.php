<h2><u>Industry Name: <?php echo $industry_name; ?></u></h2>

<table border="1">

<?php
	date_default_timezone_set('Asia/Dhaka');
	echo "<tr height='50' align='center'><th width = '130'>Name of Stock</th>";
	echo "<th width = '130'>Total no. of securities</th>";
	echo "<th width = '130'>public proportion (in quantity)</th>";
	echo "<th width = '130'>PE Details</th>";
	echo "<th width = '130'>Category</th>";
	echo "<th width = '130'>Year End</th>";
	echo "<th width = '130'>Day's Range</th>";
	echo "<th width = '130'>52 week range</th>";
	echo "<th width = '130'>Market lot</th>";
	echo "<th width = '130'>Last AGM</th>";
	echo "<th width = '130'>Listing Year</th>";
	echo "<th width = '130'>Institution proportion (in quantity)</th>";
	echo "<th width = '130'>Govt. proportion (in quantity)</th>";
	echo "<th width = '130'>Sponsor proportion (in quantity)</th>";
	echo "<th width = '130'>Foreign proportion (in quantity)</th>";
	echo "</tr>";
	foreach($company_list as $comp){
		echo "<tr height='50' align='center'><td width = '130'>".$comp['name']."</td>";
		echo "<td width = '130'>".number_format($comp['total'])."</td>";
		echo "<td width = '130'>".number_format($comp['public'])."</td>";
		echo "<td width = '130'><table><tr height='15' align='center'><td width='50'>".$comp['PE1']."</td><td width='50'>".$comp['PE2']."</td></tr><tr height='15' align='center'><td width='50'>".$comp['PE3']."</td><td width='50'>".$comp['PE4']."</td></tr></table></td>";
		echo "<td width = '130'>".$comp['category']."</td>";
		echo "<td width = '130'>".$comp['year_end']."</td>";
		echo "<td width = '130'>".$comp['days_range']."</td>";
		echo "<td width = '130'>".$comp['week_range']."</td>";
		echo "<td width = '130'>".$comp['market_lot']."</td>";
		echo "<td width = '130'>".$comp['last_agm']."</td>";
		echo "<td width = '130'>".$comp['listing_year']."</td>";
		echo "<td width = '130'>".number_format($comp['institute'])."</td>";
		echo "<td width = '130'>".number_format(intval($comp['total']*$comp['govt'] / 100.0))."</td>";
		echo "<td width = '130'>".number_format(intval($comp['total']*$comp['sponsor'] / 100.0))."</td>";
		echo "<td width = '130'>".number_format(intval($comp['total']*$comp['foreign'] / 100.0))."</td>";
		echo "</tr>";
	}
?>

</table>

