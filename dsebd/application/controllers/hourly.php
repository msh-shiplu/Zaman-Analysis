<?php
	//include_once('simple_html_dom.php');
	include_once('helper_functions.php');
	class Hourly extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->check_isvalidated();
		}
		public function index(){
			$this->load->model('Collection_model');
			$tl['title'] = 'Hourly Analysis';
			$tl['page_no'] = 1;
			$this->load->view('header',$tl);
			$pass2['data'] = $this->Collection_model->get_hourly_index_data();
			$this->load->view('hourly_dse_index_view',$pass2);
			$data = $this->Collection_model->get_hourly_data();
			$st = market_status();
			if($st == 1)
				$data1 = $this->Collection_model->get_data();
			$pass['market_status'] = $st;
			foreach($data as $a => $b){
				$pass['industry_name'] = $a;
				$pass['company_list'] = $b;
				if($st == 1)
					$pass['company_list_current'] = $data1[$a];
				else 
					$pass['company_list_current'] = NULL;
				$this->load->view('hourly_view',$pass);
			}
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