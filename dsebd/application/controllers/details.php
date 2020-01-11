<?php
	include_once('simple_html_dom.php');
	class Details extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->check_isvalidated();
		}
		public function index(){
			$this->load->model('Collection_model');
			$data = $this->Collection_model->get_detail_data();
			$tl['title'] = 'Detail Analysis';
			$tl['page_no'] = 2;
			$this->load->view('header',$tl);
			foreach($data as $a => $b){
				$pass['industry_name'] = $a;
				$pass['company_list'] = $b;
				$this->load->view('detail_view',$pass);
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