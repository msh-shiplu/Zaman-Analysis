<?php
//include_once('simple_html_dom.php');
class Collection_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function get_data(){
		$xml = file_get_html('/var/www/html/dsebd/company_list.xml');
		$industry_list = array();
		foreach($xml->find('industry') as $industry){
			$company_list = array();
			foreach($industry->find('company') as $company){
				$comp = array();
				$table = $company->code.'_table';
				if(!$this->db->table_exists($table))
					continue;
				$query = $this->db->query('select count(*) as numrow from '.$table);
				$nrow = $query->row()->numrow;
				$nrow = $nrow - 10;
				if($nrow<0)
					$nrow = 0;
				$query = $this->db->query('select * from '.$table.' order by trade_date desc limit 10');
				$daywise = array();
				foreach($query->result() as $row){
					$info = array();
					$info['trade_date'] = $row->trade_date;
					$info['ltp'] = $row->LTP;
					$info['volume'] = $row->volume;
					$daywise[] = $info;
				}
			//	$daywise = array_reverse($daywise);
				$comp['daywise'] = $daywise;
				$comp['name']=$company->plaintext;
				$comp['code']=$company->code;
				$query = $this->db->query('select PE1, PE2 from stock_data where company_code=\''.$company->code.'\'');
				foreach($query->result() as $row ){
					$comp['PE1'] = $row->PE1;
					$comp['PE2'] = $row->PE2;
				}
				$query = $this->db->query('select category from stock_data_detail where company_code=\''.$company->plaintext.'\'');
				foreach($query->result() as $row){
					$comp['category'] = $row->category;
					break;
				}
				$company_list[] = $comp;
				$company->clear();
				unset($company);
			}
			$industry_list[$industry->name] = $company_list;
			$industry->clear();
			unset($industry);
		}
		$xml->clear();
		unset($xml);
		return $industry_list;
	}
	function get_detail_data(){
		$xml = file_get_html('/var/www/html/dsebd/company_list.xml');
		$industry_list = array();
		foreach($xml->find('industry') as $industry){
			$company_list = array();
			foreach($industry->find('company') as $company){
				$comp = array();
				
				$comp['name']=$company->plaintext;
				$query = $this->db->query('select * from stock_data where company_code=\''.$company->code.'\'');
				foreach($query->result() as $row ){
					$comp['PE1'] = $row->PE1;
					$comp['PE2'] = $row->PE2;
					$comp['PE3'] = $row->PE3;
					$comp['PE4'] = $row->PE4;
					$comp['PE5'] = $row->PE5;
					$comp['PE6'] = $row->PE6;
				}
				$query = $this->db->query('select * from stock_data_detail where company_code=\''.$company->plaintext.'\'');
				foreach($query->result() as $row){
					$comp['total'] = $row->total;
					$comp['public'] = $row->public;
					$comp['category'] = $row->category;
					$comp['year_end'] = $row->year_end;
					$comp['week_range'] = $row->week_range;
					$comp['institute'] = $row->institute;
					$comp['govt'] = $row->govt;
					$comp['sponsor'] = $row->sponsor;
					$comp['foreign'] = $row->forgn;
					$comp['market_lot'] = $row->market_lot;
					$comp['last_agm'] = $row->last_agm;
					$comp['listing_year'] = $row->listing_year;
				}
				$company_list[] = $comp;
				$company->clear();
				unset($company);
			}
			$industry_list[$industry->name] = $company_list;
			$industry->clear();
			unset($industry);
		}
		$xml->clear();
		unset($xml);
		return $industry_list;
	}
	function get_ltp_data($company_code){
		$company_code1 = $company_code;
		$company_code = str_replace(array("(",")","&","-","\\","/"),'',$company_code);
		$table = $company_code.'_table';
		$query = $this->db->query('select * from '.$table);
		$daywise = array();
		foreach($query->result() as $row){
			$info = array();
			$info['trade_date'] = $row->trade_date;
			$info['ltp'] = $row->LTP;
			$info['volume'] = $row->volume;
			$daywise[] = $info;
		}
		$daywise = array_reverse($daywise);
		$comp['daywise'] = $daywise;
		$query = $this->db->query('select company_name from stock_data where company_code=\''.$company_code.'\'');
		foreach($query->result() as $row){
			$comp['name'] = $row->company_name;
		}
				
		$query = $this->db->query('select * from stock_data where company_code=\''.$company_code.'\'');
		foreach($query->result() as $row ){
			$comp['PE1'] = $row->PE1;
			$comp['PE2'] = $row->PE2;
			$comp['PE3'] = $row->PE3;
			$comp['PE4'] = $row->PE4;
			$comp['PE5'] = $row->PE5;
			$comp['PE6'] = $row->PE6;
		}
		$query = $this->db->query('select * from stock_data_detail where company_code=\''.$company_code1.'\'');
		foreach($query->result() as $row){
			$comp['total'] = $row->total;
			$comp['public'] = $row->public;
			$comp['category'] = $row->category;
			$comp['year_end'] = $row->year_end;
			$comp['week_range'] = $row->week_range;
			$comp['institute'] = $row->institute;
			$comp['govt'] = $row->govt;
			$comp['sponsor'] = $row->sponsor;
			$comp['foreign'] = $row->forgn;
			$comp['market_lot'] = $row->market_lot;
			$comp['last_agm'] = $row->last_agm;
			$comp['listing_year'] = $row->listing_year;
		}
		return $comp;
	}
	function get_index_data(){
		$query = $this->db->query('select count(*) as numrow from dse_index_info');
		$nrow = $query->row()->numrow;
		$nrow = $nrow - 10;
		if($nrow<0)
			$nrow = 0;
		$query = $this->db->query('select * from dse_index_info limit '.$nrow.', 10');
		$ret = array();
		foreach($query->result() as $row){
			$info = array();
			$info['trade_date'] = $row->trade_date;
			$info['dsex1']= $row->dsex1;
			$info['dsex2']= $row->dsex2;
			$info['total_value']= $row->total_value;
			$info['issue_advanced']= $row->issue_advanced;
			$info['issue_declined']= $row->issue_declined;
			$info['issue_unchanged']= $row->issue_unchanged;
			$info['dses1']= $row->dses1;
			$info['dses2']= $row->dses2;
			$info['dse301']= $row->dse301;
			$info['dse302']= $row->dse302;
			$ret[] = $info;
		}
		$ret = array_reverse($ret);
		return $ret;
	}
	function get_index_data_individual($index_code){
		$query = $this->db->query("select trade_date,$index_code from dse_index_info where 1");
		$ret = array();
		foreach($query->result_array() as $row){
			$ret[] = $row;
		}
		$ret = array_reverse($ret);
		return $ret;
	}
	function get_hourly_data(){
		$xml = file_get_html('/var/www/html/dsebd/company_list.xml');
		$industry_list = array();
		foreach($xml->find('industry') as $industry){
			$company_list = array();
			foreach($industry->find('company') as $company){
				$comp = array();
				$comp['code']=$company->code;
				$comp['name']=$company->plaintext;
				$query = $this->db->query('select PE1, PE2 from stock_data where company_code=\''.$company->code.'\'');
				foreach($query->result() as $row ){
					$comp['PE1'] = $row->PE1;
					$comp['PE2'] = $row->PE2;
				}
				$query = $this->db->query('select * from hourly_ltp where company_code=\''.$company->code.'\'');
				foreach($query->result() as $row){
					$info = array();
					$flag = 0;
					foreach($row as $a){
						if($flag==0){
							$flag = 1;
							continue;
						}
						$info[] = $a;
					}
					$comp['ltp'] = $info;
				}
				$query = $this->db->query('select * from hourly_volume where company_code=\''.$company->code.'\'');
				foreach($query->result() as $row){
					$info = array();
					$flag = 0;
					foreach($row as $a){
						if($flag==0){
							$flag = 1;
							continue;
						}
						$info[] = $a;
					}
					$comp['volume'] = $info;
				}
				$query = $this->db->query('select category from stock_data_detail where company_code=\''.$company->plaintext.'\'');
				foreach($query->result() as $row){
					$comp['category'] = $row->category;
					break;
				}
				$company_list[] = $comp;
				$company->clear();
				unset($company);
			}
			$industry_list[$industry->name] = $company_list;
			$industry->clear();
			unset($industry);
		}
		$xml->clear();
		unset($xml);
		return $industry_list;
	}
	function get_hourly_index_data(){
		$ret = array();
		$query = $this->db->query('select * from hourly_index where 1');
		foreach($query->result_array() as $row){
			$ret[] = $row;
		}
		return $ret;
	}
}
?>
