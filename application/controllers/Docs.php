<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docs extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index(){
		$data['title'] = 'Documents';
        $this->load->view('docs', $data);
	}
}
