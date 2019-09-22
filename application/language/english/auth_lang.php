<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Error messages */
$lang['auth_error_no_user'] = 'No registered users found.';
$lang['auth_error_account_not_verified'] = 'Your account has not yet been verified. Please check your email address and verify your account.';
$lang['auth_error_license_end_date'] = 'Your license has expired.';
$lang['auth_error_demo_end_date'] = 'Your demo period has expired.';
$lang['auth_error_device_input_limit'] = 'You have tried to login from too many devices. Please terminate the session from any previous device.';
$lang['auth_error_different_device'] = 'You have tried to log in from a different device. Please enter your confirmation code.';
$lang['auth_error_login_attemps'] = 'Your user information is incorrect. Your remaining trial right;';
$lang['auth_error_login_attemps_time_front'] = 'Wrong entry times. Please';
$lang['auth_error_login_attemps_time_end'] = 'minute wait.';


/* Info messages */
$lang['auth_info_is_logged_in'] = 'Successfully logged in.';


/* Email Messages */
$lang['mail_message_different_device_mail_title'] = 'Codeman Adam Auth System Different Device Register Code';
$lang['mail_message_different_device_mail_subject'] = 'Permission Required to Login from a Different Device.';