<?php
include_once('simple_html_dom.php');
include_once('helper_functions.php');

function parse_company_detail($company_code){
	//echo $company_code."\n";
	$html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
	$last = '';
	$agm_flag = true;
	$total=0;
	foreach($html->find('font') as $a){
		//$last = trim($last);
		//echo $last.'<br/>';
		 if(strstr($a->plaintext,'Last AGM Held:') && $agm_flag){
			if(strlen($a->plaintext)>16)
				$last_agm = trim(substr($a->plaintext,15,10));
			else 
				$last_agm='n/a';
			$agm_flag = false;
			echo "AGM: ".$last_agm."\n";
			
		 }
		 else if($last == 'Year End&nbsp;'){
			sscanf($a->plaintext,"%d",$year_end);
			echo "Year End: $year_end\n";
		 }
		 else if($last == 'Total no. of Securities'){
			$total = intval(str_replace(',','',$a->plaintext));
			echo "Total: $total\n";
		 }
		 else if(strstr($a->plaintext,'Public')){
			sscanf($a->plaintext,"Public %f",$public_f);
			$public = (int)($total*$public_f / 100.0);
			echo "Public: $public\n";
		 }
		 else if(strstr($a->plaintext,'Institute')){
			sscanf($a->plaintext,"Institute %f",$institute_f);
			$institute = (int)($total*$institute_f / 100.0);
			echo "Institute: $institute\n";
		 }
		 else if(strstr($a->plaintext,'Govt.')){
			sscanf($a->plaintext,"Govt.%f",$govt);
			echo "Govt: $govt\n";
		 }
		 else if(strstr($a->plaintext,'Sponsor')){
			sscanf($a->plaintext,"Sponsor/Director %f",$sponsor);
			echo "sponsor: $sponsor\n";
		 }
		 else if(strstr($a->plaintext,'Foreign')){
			sscanf($a->plaintext,"Foreign %f",$foreign);
			echo "Foreign: $foreign\n";
		 }
		 else if($last == 'Market Lot'){
			sscanf($a->plaintext,"%d",$market_lot);
			echo "Market Lot: $market_lot\n";
		 }
		 else if($last == '52 Week\'s Range'){
			$week_range = trim($a->plaintext);
			echo "Week Range: $week_range\n";
		 }
		 else if(strstr($last,'Listing Year')){
			sscanf($a->plaintext,"%d",$listing_year);
		}
		$last = $a->plaintext;
		$a->clear();
		
		unset($a);
		
	}
	$last = '';
	$html = file_get_html('http://dsebd.org/company_details_nav.php?name='.$company_code);
	foreach($html->find('table table table td') as $a){
		//echo $a->plaintext."\n";
		//if($a->children(0))
			//echo $a->children(0)->plaintext."\n";
		if(strstr($last,'Market Category')){
			sscanf($a->plaintext,"%s",$category);
			echo "Category: $category\n";
			$a->clear();
			unset($a);
			break;
		}
		if($a->children(0))
			$last = $a->children(0)->plaintext;
		$a->clear();
		unset($a);
	}
	$html->clear();
	unset($html);
	unset($last);
	
}
parse_company_detail('BDCOM');
?>