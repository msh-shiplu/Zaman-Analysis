<?php
	class Dseindex extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->check_isvalidated();
		}
		public function index($index_code){
			$code = array('dsex'=>'DSE X','dses' => 'DSE S', 'dse30'=>'DSE 30', 'total_value'=>'Total Value in Taka', 'issue_advanced'=>'Issue Advanced','issue_declined'=>'Issue Declined','issue_unchanged'=>'Issue Unchanged');
			$this->load->model('Collection_model');
			if(strstr($index_code,'dse')){
				$data1 = $this->Collection_model->get_index_data_individual($index_code.'1');
				$data2 = $this->Collection_model->get_index_data_individual($index_code.'2');
				$i = 0;
				foreach($data1 as $row){
					$info = array();
					$info['trade_date'] = $row['trade_date'];
					if($data2[$i][$index_code.'2']>0)
						$sign = '+';
					else 
						$sign = '';
					
					$info[$index_code] = number_format($row[$index_code.'1']).'<br/>'.$sign.sprintf("%.2lf",$data2[$i][$index_code.'2']);;
					$data[] = $info;
					$i++;
				}
			}
			else{
				$data = $this->Collection_model->get_index_data_individual($index_code);
			}
			$pass = array();
			$pass['index_code'] = $index_code;
			$pass['index_name'] = $code[$index_code];
			$pass['data'] = $data;
			
			//$pass['daywise'] = $data['daywise'];
			$tl['title'] = $code[$index_code];
			$tl['page_no'] = -1;
			$this->load->view('header',$tl);
			$this->load->view('individual_index_view',$pass);
			$this->load->view('footer');
		}
		private function check_isvalidated(){
			if(! $this->session->userdata('validated')){
				redirect('login');
			}
		}
		public function do_logout(){
			$this->session->sess_destroy();
			redirect('login');
		}
		
	}
?>
