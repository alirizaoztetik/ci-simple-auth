<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->auth_config = $this->config->item('auth');
	}
}