<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('auth_model', 'auth');		
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
			$data['title'] = 'Güvenlik Kodu';

			$this->load->view('code', $data);	
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
		$this->session->unset_userdata($this->sess);
		redirect('login');
	}

	public function auth(){
		$this->form_validation->set_rules(
			'user_email', 
			'E-posta Adresi', 
			'trim|required|valid_email',
			array(
				'required' => 'Lütfen e-posta adresi giriniz.',
				'valid_email' => 'Lütfen geçerli bir e-posta adresi giriniz.',
			)
		);

		$this->form_validation->set_rules(
			'user_password', 
			'Kullanıcı Şifresi', 
			'trim|required',
			array(
				'required' => 'Lütfen kullanıcı şifresini giriniz.',
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
