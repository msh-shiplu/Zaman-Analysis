<h2><u>Industry Name: <?php echo $industry_name; ?></u></h2>

<!--<div class="datagrid">-->
<table border="1" class="pure-table">

<?php
	date_default_timezone_set('Asia/Dhaka');
	echo "<thead><tr><th>Name of Stock</th>";
	foreach($company_list as $comp){
		$daywise = $comp['daywise'];
		foreach($daywise as $cur){
			$date = date_create($cur['trade_date']);
			echo "<th>".date_format($date,'d-m-Y')."</th>";
		}
		break;
	}
	echo "</tr></thead>";
	$i = 0;
	foreach($company_list as $comp){
		echo "<tr";
		if($i % 2 == 1)
			echo " class='pure-table-odd'";
		$i++;
		$pe1 = $pe2 = '';
		if(isset($comp['PE1'], $comp['PE2']) ){
			$pe1 = $comp['PE1'];
			$pe2 = $comp['PE2'];
		}
		echo "><td><sub>".$pe1.'/'.$pe2.'</sub>'.anchor('company/index/'.$comp['code'],$comp['name']);
		if(isset($comp['category']) && $comp['category']!='n/a')
			echo '<sub>'.$comp['category'].'</sub>';
		echo "</td>";
		$daywise = $comp['daywise'];
		foreach($daywise as $cur){
			echo "<td>".number_format($cur['volume'])."<br/>".$cur['ltp']."</td>";
		}
		echo "</tr>";
	}
?>

</table>
<!--</div>-->