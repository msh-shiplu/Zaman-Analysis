<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller{
    
    public function index(){
        // Load our view to be displayed
        // to the user
        $this->do_logout();
    }
 
	function do_logout(){
        $this->session->sess_destroy();
        redirect('login');
    }
}
?>