<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
    public function __construct()
    {
        parent::__construct();
		
        if (isset($this->session->userdata['admin'])) {
            if (isset($_POST) && !empty($_POST)) {
                if (!isset($_SERVER['HTTP_REFERER'])) {
                    die('Direct Access Not Allowed!!');
                }
            }
			   
        } 
		$this->load->helpers('api_validation');
		
    }
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message'] = $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
    public function index()
    {
		// print_r($_POST);die;
        if (!empty($_POST)) {
		$checkValidation = check_api_validation($_POST , array('email|require|email' , 'password|require'));
			if($checkValidation['status'] == 1){
				
				$checkUser = $this->DatabaseModel->select_data('user_role,user_name' , 'users' , array('user_email' => trim($_POST['email']) , 'user_password' => md5(trim($_POST['password'])) ) , 1);
				if(!(empty($checkUser))){
					if($checkUser[0]['user_role'] == 'admin'){
						$sessData = array(
							'admin' => 1,
							// 'user_name' => $checkUser[0]['user_name'],
							'user_role' => $checkUser[0]['user_role'],
						);
						$this->session->set_userdata($sessData);
						
						if(isset($_POST['remember_me']) && $_POST['remember_me'] == 'on'){
								setcookie('email',trim( $_POST['email']),  time()+3600 * 24 * 14,'/'); // 86400 = 1 day
								setcookie('pass', trim( $_POST['password']),  time()+3600 * 24 * 14,'/'); // 86400 = 1 day
						}else{
							setcookie('email', '',  time()-3600 * 24 * 365,'/'); // 86400 = 1 day
							setcookie('pass', '',  time()-3600 * 24 * 365,'/'); // 86400 = 1 day
						}
							
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'login successfully.';
						$resp = array('userData' => $sessData);	
					}else{
						$this->respMessage = 'Sorry, you are not an admin.';
					}
					
				}else{
					$this->respMessage = 'Invalid credentials, please check email or password.';
				}
				
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response();
			
        } else {
            $this->load->view('admin/login/login');
        }
    }
	
	public function logout(){
		setcookie("AuthTkn", "", 0, '/');
       	$this->session->sess_destroy();
        redirect(base_url('auth'));
	}
    
}

// $this->load->helper('string');
// $first = str_rot13(base64_encode('123465789'));
// print_r(base64_decode(str_rot13($first)));
// die;
