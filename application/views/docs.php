<!DOCTYPE html>
<html lang="tr">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $title; ?></title>

  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url('assets'); ?>/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="col-md-12">
        <div class="card mt-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-system-list" data-toggle="list" href="#list-system" role="tab" aria-controls="system"><i class="fas fa-project-diagram mr-2"></i>System</a>
                            <a class="list-group-item list-group-item-action" id="list-active-list" data-toggle="list" href="#list-active" role="tab" aria-controls="active"><i class="fas fa-project-diagram mr-2"></i>Is the account active</a>
                            <a class="list-group-item list-group-item-action" id="list-device-list" data-toggle="list" href="#list-device" role="tab" aria-controls="device"><i class="fas fa-project-diagram mr-2"></i>Device limit and entry from new device</a>
                            <a class="list-group-item list-group-item-action" id="list-limited-list" data-toggle="list" href="#list-limited" role="tab" aria-controls="limited"><i class="fas fa-project-diagram mr-2"></i>Limited entry attempt</a>
                            <a class="list-group-item list-group-item-action" id="list-license-list" data-toggle="list" href="#list-license" role="tab" aria-controls="license"><i class="fas fa-project-diagram mr-2"></i>License or Demo control</a>
                            <a class="list-group-item list-group-item-action" id="list-files-list" data-toggle="list" href="#list-files" role="tab" aria-controls="files"><i class="fas fa-project-diagram mr-2"></i>İmportant php files</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="list-system" role="tabpanel" aria-labelledby="list-system-list">
                                <h4>System Content And Purpose</h4>
                                <p>The system is a set of controls that a user should have when logging in and in addition</p>
                                <h5>In addition;</h5>
                                <ul>
                                    <li>Is the account active? is not it?</li>
                                    <li>Device limit for input and sending code by e-mail when input from different device.</li>
                                    <li>If the password is incorrect during the entry, limited trial step and blocking of the attempt to re-login by throwing ip block over certain minutes.</li>
                                    <li>License or Demo time control for user group</li>
                                </ul>
                                <h4>Requirements</h4>
                                <ul>
                                    <li>PHP 7.2 or greater</li>
                                    <li>CodeIgniter 3.1.10+</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="list-active" role="tabpanel" aria-labelledby="list-active-list">
                                <h4>Is the account active? is not it?</h4>
                                <p>This operation looks at the "user_token" field of a user to be created according to the database structure.
                                    If you create the required token and update this field and send the information by mail while the user is creating, this token field will remain stuck to this system without being cleaned.</p>
                                <h5>You can check this function in auth_model.php.</h5>
                                <hr>
                                <pre>
function is_active($data){
    $result = $data->row();

    if($this->auth_config['user_is_active'] != 1){
        return $this->is_license($result->user_id);
        exit;
    }

    if($result->user_token == NULL){
        if($result->user_level != 1){
            return $this->is_license($result->user_id);
        } else {
            return $this->is_change_ip_address($result);
        }
    } else {
        return $this->message('error', $this->lang->line('auth_error_account_not_verified'));
        exit;
    }
}
                                </pre>
                                <hr>
                            </div>
                            <div class="tab-pane fade" id="list-device" role="tabpanel" aria-labelledby="list-device-list">
                                <h4>Device limit for input and sending code by e-mail when input from different device.</h4>
                                <p>This system logs on to the user for the first time and has no device registrations. The first device that opens is saved in the automatic table "user_devices". Then it sends an e-mail for the new outgoing record before logging in from different ip and device. When the code sent by this e-mail is entered, the new device is registered and then it is allowed to log in again from the device.</p>
                                <h5>You can check this function in auth_model.php.</h5>
                                <hr>
                                <pre>
function is_change_ip_address($data){
    if($this->auth_config['user_is_change_ip_address'] != 1){
        return $this->login($data);
        exit;
    }

    $ip_address = $this->input->ip_address();
    $devices = $this->db->get_where('user_devices', array('user_device_user_id' => $data->user_id));
    $devices_log = $_SERVER['HTTP_USER_AGENT'];
                    
    if($devices->num_rows() > 0){
        if($data->user_last_ip != $ip_address){
            if($devices->num_rows() == $this->auth_config['device_input_limit']){
                return $this->message('error', $this->lang->line('auth_error_device_input_limit'));
                exit;
            } else {
                $search_devices = $this->db->get_where('user_devices', array('user_device_ip_address' => $ip_address));
                if($search_devices->num_rows() > 0){
                    return $this->login($data);
                } else {
                    $code = random_string('numeric', $this->auth_config['random_code_limit']);
            
                    $code_data = array(
                        'user_ip_code' => $code,
                        'user_updated_at' => date('Y-m-d H:i:s')
                    );
                    if($this->user_update($data->user_id, $code_data)){
                        $body = $this->mailer->change_device_send_code($data->user_email, $code);
                        $to = $data->user_email;
                        $subject = $this->lang->line('mail_message_different_device_mail_subject');
                        $message =  $body ;
        
                        if(sendEmail($to, $subject, $message, $file = '' , $cc = '')) {
                            $value = $data->user_last_ip.'|'.date('H:i:s', time() + ($this->auth_config['countdown_for_code_entry'] * 60)).'|'.$data->user_id;
                            $this->is_cookie('coder', $value);
                            return $this->message('warning', $this->lang->line('auth_error_different_device'), TRUE);
                            exit;
                        }	
                    }
                }
            }
        } else {
            return $this->login($data);
        }
    } else {
        $devices_data = array(
            'user_device_user_id' => $data->user_id,
            'user_device_ip_address' => $ip_address,
            'user_device_log' => $devices_log,
            'user_device_created_at' => date('Y-m-d H:i:s')
        );

        if($this->db->insert('user_devices', $devices_data)){
            return $this->is_change_ip_address($data);
        }
    }
}
                                </pre>
                                <hr>
                            </div>
                            <div class="tab-pane fade" id="list-limited" role="tabpanel" aria-labelledby="list-limited-list">
                                <h4>If the password is incorrect during the entry, limited trial step and blocking of the attempt to re-login by throwing ip block over certain minutes.</h4>
                                <p>This system is triggered if the user enters his / her e-mail address correctly but his / her password is incorrect and gives the right to try the number of steps specified.
                                    If he has exercised his full trial, the attempt to re-enter for the specified minute is blocked. When the time has elapsed, the re-entry attempt is activated.</p>
                                <h5>You can check this function in auth_model.php.</h5>
                                <pre>
function is_attempts($data){
    if($this->auth_config['user_is_attempts'] != 1){
        return $this->login($data);
        exit;
    }

    $attempts = $data->user_attempts;

    if(!empty($attempts)){
        if(is_numeric($attempts) && $attempts <= ($this->auth_config['faulty_trial_step'] - 1)){
            $attempts++;

            $attempt_data = array(
                'user_attempts' => $attempts,
                'user_updated_at' => date('Y-m-d H:i:s')
            );

            $this->user_update($data->user_id, $attempt_data);

            if($attempts == $this->auth_config['faulty_trial_step']){
                $attempt_date = array(
                    'user_attempt_time' => date('H:i:s', time() + ($this->auth_config['countdown_for_re_entry'] * 60)),
                    'user_updated_at' => date('Y-m-d H:i:s')
                );
                if($this->user_update($data->user_id, $attempt_date)){
                    $value = $data->user_last_ip.'|'.$attempt_date['user_attempt_time'].'|'.$data->user_id;
                    $this->is_cookie('timer', $value);

                    return $this->message('block', $this->auth_config['faulty_trial_step'].' '.$this->lang->line('auth_error_login_attemps_time_front').' '.$this->auth_config['countdown_for_re_entry'].' '.$this->lang->line('auth_error_login_attemps_time_end'));
                    exit;
                }
            } else {
                return $this->message('error', $this->lang->line('auth_error_login_attemps').' '.($this->auth_config['faulty_trial_step'] - $attempts));
                exit;
            }
        } else {
            $value = $data->user_last_ip.'|'.$data->user_attempt_time.'|'.$data->user_id;
            $this->is_cookie('timer', $value);
            return $this->message('block', 'Login engeli hala devam ediyor.');
            exit;
        }
    } else {
        $attempt_data = array(
            'user_attempts' => 1,
            'user_updated_at' => date('Y-m-d H:i:s')
        );
        if($this->user_update($data->user_id, $attempt_data)){
            return $this->message('error', $this->lang->line('auth_error_login_attemps').' '.($this->auth_config['faulty_trial_step'] - 1));
            exit;
        }
    }
}
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="list-license" role="tabpanel" aria-labelledby="list-license-list">
                                <h4>License or Demo time control</h4>
                                <p>This system triggers a license for the logged-in user role. If you have a license add-on to your system, you can trigger it here.</p>
                                <h5>You can check this function in auth_model.php.</h5>
                                <pre>
function is_license($data){
    $result = $this->db
                ->join('license', 'license.license_user_id = users.user_id')
                ->get_where('users', array('user_id' => $data))
                ->row();

    if($this->auth_config['user_is_license'] != 1){
        return $this->is_change_ip_address($result);
        exit;
    }

    $turkey = now("Turkey");
    $start_date= new DateTime(mdate("%Y-%m-%d",$turkey));
    $end_date= new DateTime($result->license_end_date);
    $interval= $start_date->diff($end_date);
    
    if($interval->invert > 0){
        if($result->license_type == 1){
            return $this->message('error', $this->lang->line('auth_error_license_end_date'));
            exit;
        } else {
            return $this->message('error', $this->lang->line('auth_error_demo_end_date'));
            exit;
        }
    } else {
        return $this->is_change_ip_address($result);
    }
}
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="list-files" role="tabpanel" aria-labelledby="list-files-list">
                                <h4>application/core/MY_Controller.php && MY_Model.php</h4>
                                <p>These 2 files are opened for the necessary initial checks and to trigger the necessary triggers. You can change it as you wish. System updates will continue through these files.</p>
                                <h5 class="font-weight-bold">MY_Controller.php</h5>
                                <pre>
class MY_Controller extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->sess = $this->session->userdata();
		$this->auth_config = $this->config->item('auth');

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
                                </pre>
                                <p>We have assigned a value to use the session created here in other controller files. We have assigned a value for the adjustment of the system, its use to a certain point, and we have carried out a check to prevent some degree of inaccurate step attempts.</p>
                                <h5 class="font-weight-bold">MY_Model.php</h5>
                                <pre>
class MY_Model extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->auth_config = $this->config->item('auth');
	}
}
                                </pre>
                                <p>Here we have assigned a value for the tuning components of the system on some required models.</p>
                                <hr>
                                <h4>application/config/auth.php</h4>
                                <p>This file was created to manage and change system settings.</p>
                                <h5 class="font-weight-bold">Email Settings</h5>
                                <p>Mail settings for sending mail are available in Auth_model.php. Default mail attachment <b>SMTP</b></p>
                                <pre>
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
                                </pre>

                                <h5 class="font-weight-bold">System Distribution On / Off</h5>
                                <p>System requirements are the settings used to make active / passive. You can turn the feature on and off. 1-0 (true / false)</p>
                                <pre>
$auth_config['user_is_license']           = 1;  // For License or Demo control
$auth_config['user_is_change_ip_address'] = 1;  // For Device limit and entry from new device
$auth_config['user_is_attempts']          = 1;  // For Limited entry attempt
$auth_config['user_is_active']            = 1;  // For Is the account active
                                </pre>

                                <h5 class="font-weight-bold">Other Settings</h5>
                                <p>Settings used for limits and restrictions. Number of experiment steps, the time from the format of minutes set for the cookie duration to occur. etc.</p>
                                <pre>
$auth_config['faulty_trial_step']           = 5;  // For Incorrect number of steps
$auth_config['countdown_for_re_entry']      = 1;  // For Waiting time after incorrect entry
$auth_config['countdown_for_code_entry']    = 2;  // For Time for entering the code sent to register for new device
$auth_config['random_code_limit']           = 10; // For Number of limits for random codes used within the system
$auth_config['device_input_limit']          = 2;  // For The maximum amount of devices a user can register
                                </pre>
                                <hr>

                                <h4>application/language/english/auth_lang.php</h4>
                                <p>Returning in the system includes positive or negative messages, headers and contents used in the mail theme. Classic language file</p>
                                <pre>
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
$lang['auth_error_bad_code'] = 'The code entered is incorrect, please check your email address.';


/* Info messages */
$lang['auth_info_is_logged_in'] = 'Successfully logged in.';


/* Email Messages */
$lang['mail_message_different_device_mail_title'] = 'Codeman Adam Auth System Different Device Register Code';
$lang['mail_message_different_device_mail_subject'] = 'Permission Required to Login from a Different Device.';
                                </pre>
                                <hr>

                                <h4>application/helpers/email_helper.php</h4>
                                <p>It is a ready-made library for sending emails. Triggered by including some necessary files used within the system.</p>
                                <pre>
function sendEmail($to = '', $subject  = '', $body = '', $attachment = '', $cc = '')
{
    $controller =& get_instance();
    
    $controller->load->helper('path'); 
    $controller->config->load('auth');
    $controller->lang->load('auth');
    $auth_config = $controller->config->item('auth');
    
    // Configure email library
    
    $config = array();
    $config['protocol']     = $auth_config['protocol'];
    $config['smtp_host']    = $auth_config['smtp_host'];
    $config['smtp_port']    = $auth_config['smtp_port'];
    $config['smtp_timeout'] = $auth_config['smtp_timeout'];
    $config['smtp_user']    = $auth_config['smtp_user'];
    $config['smtp_pass']    = $auth_config['smtp_pass'];
    $config['mailtype']     = $auth_config['mailtype'];
    $config['charset']      = $auth_config['charset'];
    $config['newline']      = $auth_config['newline'];
    $config['wordwrap']     = $auth_config['wordwrap'];

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
                                </pre>
                                <hr>

                                <h4>application/libraries/Mailer.php</h4>
                                <p>File containing html theme required for sending mail</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url('assets'); ?>/js/sb-admin-2.min.js"></script>
</body>

</html>
