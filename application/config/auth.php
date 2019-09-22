<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config_auth = array();

$config_auth["default"] = array(
    //Email Settings
    'protocol'      => "smtp",
    'smtp_host'     => "yourhost",
    'smtp_port'     => "587",
    'smtp_timeout'  => '30',
    'smtp_user'     => "your-email@address.com",
    'smtp_pass'     => "yourpassword",
    'mailtype'      => 'html',
    'charset'       => 'utf-8',
    'newline'       => "\r\n",
    'wordwrap'      => TRUE,
    //Email Settings End

    //System Distribution On / Off
    'user_is_license'           => 1,
    'user_is_change_ip_address' => 1,
    'user_is_attempts'          => 1,
    'user_is_active'            => 1,
    //System Distribution On / Off End

    //Other Settings
    'faulty_trial_step'         => 5,
    'countdown_for_re_entry'    => 0.3,
    'random_code_limit'         => 10,
    'device_input_limit'        => 2,
    //Other Settings End
);

$config['auth'] = $config_auth['default'];
