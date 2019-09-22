<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	function sendEmail($to = '', $subject  = '', $body = '', $attachment = '', $cc = '')
    {
		$controller =& get_instance();
        
		$controller->load->helper('path'); 
		$controller->config->load('auth');
		$controller->lang->load('auth');
		$config_vars = $controller->config->item('auth');
       	
       	// Configure email library
		
		$config = array();
        $config['protocol']             = $config_vars['protocol'];
        $config['smtp_host']            = $config_vars['smtp_host'];
        $config['smtp_port']            = $config_vars['smtp_port'];
		$config['smtp_timeout'] 		= $config_vars['smtp_timeout'];
		$config['smtp_user']    		= $config_vars['smtp_user'];
		$config['smtp_pass']    		= $config_vars['smtp_pass'];
        $config['mailtype'] 			= $config_vars['mailtype'];
        $config['charset']  			= $config_vars['charset'];
        $config['newline']  			= $config_vars['newline'];
        $config['wordwrap'] 			= $config_vars['wordwrap'];

        $controller->load->library('email');
        $controller->email->initialize($config);   
		$controller->email->from( $config_vars['smtp_user'] , $controller->lang->line('mail_message_different_device_mail_title') );
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