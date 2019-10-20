<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Email Settings
$auth_config['protocol']      = "smtp";
$auth_config['smtp_host']     = "yourhost";
$auth_config['smtp_port']     = "587";
$auth_config['smtp_timeout']  = '30';
$auth_config['smtp_user']     = "your-email@address.com";
$auth_config['smtp_pass']     = "yourpassword";
$auth_config['mailtype']      = 'html';
$auth_config['charset']       = 'utf-8';
$auth_config['newline']       = "\r\n";
$auth_config['wordwrap']      = TRUE;
//Email Settings End

//System Distribution On / Off
$auth_config['user_is_license']           = 1;
$auth_config['user_is_change_ip_address'] = 1;
$auth_config['user_is_attempts']          = 1;
$auth_config['user_is_active']            = 1;
//System Distribution On / Off End

//Other Settings
$auth_config['faulty_trial_step']           = 5;
$auth_config['countdown_for_re_entry']      = 1;
$auth_config['countdown_for_code_entry']    = 2;
$auth_config['random_code_limit']           = 10;
$auth_config['device_input_limit']          = 2;
//Other Settings End

$config['auth'] = $auth_config;
