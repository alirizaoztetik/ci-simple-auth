<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends MY_Model{
    
    function message($type = '',$message = '', $return = FALSE){
        return $info = [
            'type' => $type,
            'message' => $message,
            'return' => $return
        ];
    }

    function user_update($user_id, $data){
        if(is_numeric($user_id) AND $user_id > 0){
            return $this->db->where('user_id', $user_id)->update('users', $data);
        }
    }

    function auth($data){
        $result = $this->db->get_where('users', array('user_email' => $data['user_email']));
     
        if($result->num_rows() > 0){
            $rows = $result->row();
            if($data['user_password'] === $rows->user_password){
                return $this->is_active($result);
            } else {
                return $this->is_attempts($rows);
            exit;
            }
        } else {
            return $this->message('error', $this->lang->line('auth_error_no_user'));
            exit;
        }
    }

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

    function is_code($data){
        $result = $this->db->get_where('users', array('user_ip_code' => $data));
        if($result->num_rows() > 0){
            $user = $result->row();
            $devices_log = $_SERVER['HTTP_USER_AGENT'];

            $devices_data = array(
                'user_device_user_id' => $user->user_id,
                'user_device_ip_address' => $this->input->ip_address(),
                'user_device_log' => $devices_log,
                'user_device_created_at' => date('Y-m-d H:i:s')
            );

            if($this->db->insert('user_devices', $devices_data)){
                return $this->login($user);
            }

        } else {
            return $this->message('error', $this->lang->line('auth_error_bad_code'));
            exit;
        }
    }

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

    function is_cookie($name, $value){
        if(get_cookie($name)){ delete_cookie($name); }

        $cookie = array(
            'name'	 => $name,
            'value'	 => $value,
            'expire' => 99*999*999,
            'path'	 => '/',
        );

        if($this->input->set_cookie($cookie)){ return TRUE; }
    }

    function login($data){
        if($data->user_level != 1){
            $column = array(
                'user_id', 
                'user_level', 
                'user_email', 
                'user_name', 
                'user_surname', 
                'license_start_date',
                'license_end_date',
                'license_type',
            );

            $result = $this->db
                        ->select($column)
                        ->join('license', 'license.license_user_id = users.user_id')
                        ->get_where('users', array('user_id' => $data->user_id))
                        ->row_array();
        } else {
            $result = $this->db
                    ->get_where('users', array('user_id' => $data->user_id))
                    ->row_array();
        }
        
        if($result){
            $data = array(
                'user_last_ip' => $this->input->ip_address(),
                'user_last_login' => date('Y-m-d H:i:s'),
                'user_updated_at' => date('Y-m-d H:i:s'),
                'user_attempts' => null,
                'user_ip_code' => null
            );
            if($this->user_update($result['user_id'], $data)){
                $this->session->set_userdata($result);
                return $this->message('success', $this->lang->line('auth_info_is_logged_in'));
            }
        }
    }

}