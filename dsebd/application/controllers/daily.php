<?php
	include_once('simple_html_dom.php');
	class Daily extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->check_isvalidated();
		}
		public function index(){
			$this->load->model('Collection_model');
			$tl['title'] = 'Daily Analysis';
			$tl['page_no'] = 0;
			$this->load->view('header',$tl);
			$data = $this->Collection_model->get_index_data();
			$pass2['data'] = $data;
			$this->load->view('dse_index_view',$pass2);
			$data = $this->Collection_model->get_data();
			
			foreach($data as $a => $b){
				$pass['industry_name'] = $a;
				$pass['company_list'] = $b;
				$this->load->view('daily_view',$pass);
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
