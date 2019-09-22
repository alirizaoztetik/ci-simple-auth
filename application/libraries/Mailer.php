<?php
class Mailer 
{
	function __construct()
	{
		$this->CI =& get_instance();
	}
	//=============================================================
	function change_device_send_code($email, $code){

		$tpl = '<h3>Hi, ' .$email.' is a test!</h3>
            <br>
            <br>		
            <p>Codeman Adam Auth System Different Device Register Code</p>
            <br>
            <br>			
			<p>This is a test. For new device registration mail</p>
			<p>Please enter this code for new device register</p>
            <p>Your is code: '.$code.'</p>

            <br>
            <br>
			<p>Codeman Adam Auth System Different Device Register Code</p>
    ';
		return $tpl;		
	}	
}
?>