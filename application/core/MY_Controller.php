<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->sess = $this->session->userdata();

		if(get_cookie('timer')){
			$value = explode('|', get_cookie('timer'));
			$ip_address = $this->input->ip_address();
			echo $ip_address;
			if($ip_address == $value[0]){
				redirect('block');
			}
		} else {
			if($this->uri->segment(1) != "login" && $this->uri->segment(1) != "recovery"){
				if(!$this->session->has_userdata('user_id')) {
					redirect('login');
				}
			}
		}
	}
}