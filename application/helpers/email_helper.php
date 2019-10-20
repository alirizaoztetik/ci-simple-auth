<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	function sendEmail($to = '', $subject  = '', $body = '', $attachment = '', $cc = '')
    {
		$controller =& get_instance();
        
		$controller->load->helper('path'); 
		$controller->config->load('auth');
		$controller->lang->load('auth');
		$auth_config = $controller->config->item('auth');
       	
       	// Configure email library
		
		$config = array();
        $config['protocol']             = $auth_config['protocol'];
        $config['smtp_host']            = $auth_config['smtp_host'];
        $config['smtp_port']            = $auth_config['smtp_port'];
		$config['smtp_timeout'] 		= $auth_config['smtp_timeout'];
		$config['smtp_user']    		= $auth_config['smtp_user'];
		$config['smtp_pass']    		= $auth_config['smtp_pass'];
        $config['mailtype'] 			= $auth_config['mailtype'];
        $config['charset']  			= $auth_config['charset'];
        $config['newline']  			= $auth_config['newline'];
        $config['wordwrap'] 			= $auth_config['wordwrap'];

        $controller->load->library('email');
        $controller->email->initialize($config);   
		$controller->email->from( $auth_config['smtp_user'] , $controller->lang->line('mail_message_different_device_mail_title') );
		$controller->email->to($to);
		$controller->email->subject($subject);
		$controller->email->message($body);
		
		if($cc != '') 
		{	
			$controller->email->cc($cc);
		}	
		
		if($attachment != '')
		{
			$controller->email->attach(base_url()."uploads/invoices/" .$attachment);
		 
		}
			
		if($controller->email->send()){
			return "success";
		}
		else
		{
			return "error";
		}
    }
?>