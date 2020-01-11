<?php
	class Company extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->check_isvalidated();
		}
		public function index($company_code){
			$this->load->model('Collection_model');
			$data = $this->Collection_model->get_ltp_data($company_code);
			//$pass = array();
			$data['company_code'] = $company_code;
			$data['company_name'] = $data['name'];
			//$pass['daywise'] = $data['daywise'];
			$tl['title'] = $company_code.' - Volume & LTP/CP';
			$tl['page_no'] = -1; 
			$this->load->view('header',$tl);
			$this->load->view('ltp_view',$data);
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
