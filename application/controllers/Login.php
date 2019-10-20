<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('auth_model', 'auth');
		$this->auth_config = $this->config->item('auth');
	}
	
	public function index(){
		if(get_cookie('timer')){
			$value = explode('|', get_cookie('timer'));
			$ip_address = $this->input->ip_address();
			echo $ip_address;
			if($ip_address == $value[0]){
				redirect('login/block');
			}
		} else {
			$data['title'] = 'Giriş Yap';
			$this->load->view('login', $data);
		}
	}

	public function code(){

		if(get_cookie('timer')){
			$value = explode('|', get_cookie('timer'));
			$ip_address = $this->input->ip_address();
			echo $ip_address;
			if($ip_address == $value[0]){
				redirect('login/block');
			}
		} else {
			if(get_cookie('coder')){
				$value = explode('|', get_cookie('coder'));
	
				$now_time = new DateTime(date('H:i:s'));
				$cookie_time= new DateTime($value[1]);
				$calc = $now_time->diff($cookie_time);
	
				if($calc->invert > 0){
					$data = array(
						'user_ip_code' => null,
						'user_updated_at' => date('Y-m-d H:i:s')
					);
	
					if($this->auth->user_update($value[2], $data)){
						delete_cookie('coder');
						redirect('login');
					}
				}
				
				$data['title'] = 'Güvenlik Kodu';
				$data['timer'] = $calc;
				$data['maxlength'] = $this->auth_config['random_code_limit'];

				$this->load->view('code', $data);	
			} else {
				redirect('login');
			}
		}
	}

	public function input_code(){

		$this->form_validation->set_rules(
			'user_code', 
			'Güvenlik Kodu', 
			'trim|required|numeric',
			array(
				'required' => 'Please enter the security code.',
				'numeric' => 'Please check your security code.',
			)
		);

		if ($this->form_validation->run() !== TRUE) {
			$array['error'] = validation_errors();
			echo json_encode($array);
		}
		else {
			$user_code = $this->input->post('user_code');
			$result = $this->auth->is_code($user_code);

			if($result){
				$array[$result['type']] = $result['message'];
				echo json_encode($array);
			}
		}
	}

	public function block(){
		if(get_cookie('timer')){
			$value = explode('|', get_cookie('timer'));

			$now_time = new DateTime(date('H:i:s'));
			$cookie_time= new DateTime($value[1]);
			$calc = $now_time->diff($cookie_time);

			if($calc->i === 0 && $calc->s === 0){
				$data = array(
					'user_attempts' => null,
					'user_updated_at' => date('Y-m-d H:i:s')
				);

				if($this->auth->user_update($value[2], $data)){
					delete_cookie('timer');
					redirect('login');
				}
			}
			
			$data['title'] = 'Bekle !';
			$data['timer'] = $calc;
			$this->load->view('cokie', $data);
		} else {
			redirect('login');
		}
	}

	public function logout(){
		delete_cookie('timer');
		delete_cookie('coder');
		$this->session->sess_destroy();
		redirect('login');
	}

	public function auth(){
		$this->form_validation->set_rules(
			'user_email', 
			'E-Mail Address', 
			'trim|required|valid_email',
			array(
				'required' => 'Please enter your e-mail address.',
				'valid_email' => 'Please enter a valid e-mail address.',
			)
		);

		$this->form_validation->set_rules(
			'user_password', 
			'User Password', 
			'trim|required',
			array(
				'required' => 'Please enter the your password.',
			)
		);
		if ($this->form_validation->run() !== TRUE) {
			$array['error'] = validation_errors();
			echo json_encode($array);
		}
		else {
			$user_email = $this->input->post('user_email');
			$user_password = md5(base64_encode($this->input->post('user_password')));

			$data = array(
				'user_email' => $user_email,
				'user_password' => $user_password,
			);

			$this->security->xss_clean($data);

			$result = $this->auth->auth($data);
			if($result){
				$array[$result['type']] = $result['message'];
				echo json_encode($array);
			}
		}
	}
}
