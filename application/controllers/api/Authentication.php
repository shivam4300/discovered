<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {


	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';

	public function __construct()
	{
		parent::__construct();
		/*if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    } */
		//$this->load->helper(array('form', 'url'));
		$this->load->library(array('Audition_functions','query_builder','share_url_encryption','form_validation')); 
	}
	
	
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message'] = $this->respMessage;
		$this->output->set_content_type('application/json');
		//$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	function single_error_msg(){
		$errors = array_values($this->form_validation->error_array());
		return $errors[0];
	}
	
	/**************** Register Email STARTS **********************/
	function register_email(){
		
		$this->form_validation->set_rules('u_em', 'email', 'trim|required|is_unique['.USERS.'.user_email]');
		$this->form_validation->set_rules('u_pwd', 'password', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
					
			$randomstr	= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
				
			$param = array(	'user_key'		=>	md5($randomstr),
							'user_email'	=>	$_POST['u_em'],
							'user_password'	=>	md5($_POST['u_pwd']),
							'referral_by'	=>	isset($_SESSION['referral_by'])?$_SESSION['referral_by']:'',
							'referral_from'	=>	isset($_SESSION['referral_from'])?$_SESSION['referral_from']:''	
							);
								
			$userid = $this->DatabaseModel->access_database(USERS,'insert',$param ,'');
				
			$email = $_POST['u_em'];
			$link = base_url().'home/verify_email/'.md5($randomstr);
			
			$subject = 'Welcome to DiscoveredTv';
			$body ="<p> Hi, <br/> Thank you very much for creating an account with us. Please click on the link to verify your email. <br/><a href='".$link."'> Yes, this is my email</a></p> <p style='text-align:center;'>OR</p><p>Copy this link and paste it in your browser<br/>".$link."</p><br/><br/> Thanks</p> ";
			
			$greeting = 'Thanks for creating an account with us';
			$action = 'Please click on the link to verify your email. OR Copy this link and paste it in your browser. ';
			$button = 'Activate Your Account';
			$fullname='';
			$to = '{	
					"email":"'.$_POST['u_em'].'",
					"name":"'.$fullname.'",
					"type":"to"
					}' ;
			$this->audition_functions->MailByMandrillforLink($to,$subject,$greeting,$action,$button,$link);
			
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Register successfully.';
			
		}else {
			$this->respMessage = $this->single_error_msg();
		} 
		
		$this->show_my_response();
	}
	
	/**************************** Register Email ENDS **********************/
	
	
	/**************************** Verify Email STARTS **********************/
	
	function verify_email(){
		
		$this->form_validation->set_rules('verify_code', 'verification code', 'trim|required');
		
		if($this->form_validation->run() == TRUE) {
			  
			$userDetails = $this->DatabaseModel->select_data('user_id,user_name,user_status',USERS,array('user_key'=>$_POST['verify_code']),1); 
			// print_r($userDetails);die;
			if(!empty($userDetails)) {
				
				if( $userDetails[0]['user_name'] != '' ) {
					$fullname = $userDetails[0]['user_name'];
					$f_arr  = explode(' ',$fullname);

					$firstname = ucfirst($f_arr[0]);
				}
				else {
					$firstname = '';
				}
				$userid = $userDetails[0]['user_id'];
				
				if( $userDetails[0]['user_status'] == '2' ) {
					$this->DatabaseModel->access_database(USERS,'update',array('user_status'=>1,'user_key'=>''),array('user_id'=>$userid));
					
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Your account is verified successfully.';
				}
				else if( $userDetails[0]['user_status'] == '3' ) {
					$this->DatabaseModel->access_database(USERS,'update',array('user_key'=>''),array('user_id'=>$userid));
					
					$this->respMessage = 'You account is blocked. Please, contact support.';
				}
				else if( $userDetails[0]['user_status'] == '4' ) {
					$this->DatabaseModel->access_database(USERS,'update',array('user_key'=>''),array('user_id'=>$userid));
					
					$this->respMessage = 'icon inactive.';
				}
				else {
					
					$this->respMessage = 'Something went wrong please try again. Please, contact support.';
				}
			
			}
			else {
				
				$this->respMessage = 'You have already used this activation link.';
			}
		}
		else {
			$this->respMessage = $this->single_error_msg();
		} 
		$this->show_my_response();
	}
	
	/**************************** Verify Email ENDS **********************/
	
	
	
	/************************* Forgot Password STARTS **********************/
	
	function reset_password($key=''){
		if( $key != '' ) {
			$data['key'] = 1;
			if( isset($_POST['pwd']) ) {
				$this->DatabaseModel->access_database(USERS,'update',array('user_password'=>md5($_POST['pwd']),'user_key'=>''),array('user_key'=>$key));
				
				redirect(base_url().'home/messages/pwd_change_success');
			
			}
			else {
				$res 	= $this->DatabaseModel->select_data('user_id',USERS,array('user_key'=>$key),1); 
				if(empty($res)) {
					redirect(base_url().'home/messages/invalid_access');
				}
			}
		}
		else {
			if( isset($_POST['forgot_email']) ) {
				
				$res 	= $this->DatabaseModel->select_data('user_id,user_name',USERS,array('user_email'=>$_POST['forgot_email']),1); 
				if(!empty($res)) {
				
					$randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
					$this->DatabaseModel->access_database(USERS,'update',array('user_key'=>md5($randomstr)),array('user_id'=>$res[0]['user_id']));
				
					if( $res[0]['user_name'] != '' ) {
						$fullname = $res[0]['user_name'];
						$f_arr  = explode(' ',$fullname);

						$firstname = ucfirst($f_arr[0]);
					}
					else {
						$firstname = '';
					}


					$email 		= $_POST['forgot_email'];
					$link 		= base_url().'home/reset_password/'.md5($randomstr);
				
					$subject 	= 'DiscoveredTv - Reset your password';
					
					$greeting 	= 'This email was sent automatically by Discovered in response to your request to recover your password. This is done for your protection, only you, the recipient of this email can take the next step in the password recover process';
					
					$action 	= 'To reset your password and access your account either click on the Orange button on the bottom or copy and paste the following link into the address bar of your browser:';
					$button 	= 'Reset Your Password';
					
					$to 		= '{	
										"email":"'.$email.'",
										"name":"'.$fullname.'",
										"type":"to"
									}' ;
					$this->audition_functions->MailByMandrillforLink($to,$subject,$greeting,$action,$button,$link);
					redirect(base_url().'home/messages/reset_link_sent');
				}
				else {
					redirect(base_url().'home/messages/invalid_account');
				}
			}
			$data['key'] = 0;
		}
		
		$data['page_info'] 	= array('page'=>'forgot_pass','title'=>'Forgot Password');
		
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/forgot_password',$data);
		$this->load->view('home/inc/footer',$data);
	}
	
	/************************* Forgot Password ENDS **********************/
	
	/**************** LOGIN STARTS **********************/
	function allow_login_access(){
		$resp=array();
		$this->form_validation->set_rules('u_em', 'email', 'trim|required');
		$this->form_validation->set_rules('u_pwd', 'password', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
		
			$userDetails = $this->DatabaseModel->select_data('user_id,user_name,user_status,user_uname,user_level,user_cate',USERS,array('user_email'=>$_POST['u_em'],'user_password'=>md5($_POST['u_pwd'])),1); 
		
			if( isset($userDetails[0]['user_id']) && !empty($userDetails[0]['user_id']) ) {
			
				if( $userDetails[0]['user_status'] == '4' ){
					//$resp = array('status'=>407);// Icon Blocked
					$this->respMessage = 'icon inactive.';
				}else 
				if( $userDetails[0]['user_status'] == '3' ){
					//$resp = array('status'=>403);// Blocked
					$this->respMessage = 'You account is blocked. Please, contact support.';
				}else 
				if( $userDetails[0]['user_status'] == '2' ){
					//$resp = array('status'=>402);  // Inactive
					$this->respMessage = 'You account is inactive. Please, activate your account.';
				}else 
				if( $userDetails[0]['user_status'] == '1' ){
					//$this->SaveLoginCookie();
					$userData =  $this->create_token($userDetails[0]['user_id'],'old');
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Login successfully.';
					$resp = array('userData'=>$userData);
				}
			}else{
				//$resp = array('status'=>401);
				$this->respMessage = 'Invalid login credentials.Please,try again.';
			}
		}else{
			//$resp = array('status'=>404);
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	/**************************** Login ENDS **********************/

	/*************** Create Token and Send Email STARTS **********************/

    function create_token($userid,$type) {
        $userDetails 	= $this->DatabaseModel->select_data('user_name,user_uname,user_status,user_level,user_cate,user_email',USERS,array('user_id'=>$userid),1); 
		$userContent 	= $this->DatabaseModel->select_data('uc_type,is_iva,uc_country',USERS_CONTENT,array('uc_userid'=>$userid),1); 
       
		$user_type 		= '0';
		$is_iva 		= '0';
		$uc_country 	= ' ';
		
		if( isset($userContent[0]) ){
			if(!empty($userContent[0]['uc_type'])){
				$user_type 	= $userContent[0]['uc_type'];
			}
			if(!empty($userContent[0]['is_iva'])){
				$is_iva 	= $userContent[0]['is_iva'];
			}
			if(!empty($userContent[0]['uc_country'])){
				$uc_country = $userContent[0]['uc_country'];
			}
		}
		
		$firstname 	= '';
        if( !empty($userDetails[0]['user_name']) ) {
            $fullname 	= $userDetails[0]['user_name'];
            $f_arr  	= explode(' ',$fullname);
            $firstname 	= ucfirst(isset($f_arr[0]) ? $f_arr[0] : '' );
        }

        $user_session_array	= array(
            'user_login_id'		=> $userid,
            'user_name'			=> $firstname,
            'user_uname'		=> $userDetails[0]['user_uname'],
            'user_login'		=> true,
            'user_status'		=> $userDetails[0]['user_status'],
            'user_accesslevel'	=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
            'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
            'is_iva'			=> $is_iva,
            'user_primaryType'	=> (string) $user_type,
            'uc_country'		=> $uc_country,
            'user_email'		=> $userDetails[0]['user_email'],
			'iat'				=> time(),
			'sub'				=> $userid,
		);
		
		$this->load->library('creator_jwt');
		
		$jwtToken 						= 	$this->creator_jwt->GenerateToken($user_session_array);
		$user_session_array['token']	=	$jwtToken;
        
		//$this->session->set_userdata($user_session_array);

        if( $type == 'new' ) {
            $email = $userDetails[0]['user_email'];
            $randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
            $this->DatabaseModel->access_database(USERS,'update',array('user_password'=>md5($randomstr)),array('user_id'=>$userid));
				
            $subject = 'Welcome to Discovered';
            $body ="<p> Hi ".$firstname.", <br/> Thank you very much for creating an account with us. You can login to the DiscoveredTv using your Facebook or Google or even you can use these credentials , <br/> </p> <p> Login Email : ".$email."</p> <p> Password : ".$randomstr."<br/><br/> Thanks.</p> ";

			$greeting = 'Thanks for creating an account with us';
			$action = 'Now you can login to Discovered.tv by using your personal email and password or you can login with your Facebook or Google accounts.';
			
			$this->audition_functions->MailByMandrillForRegstr($email,$firstname,$subject,$greeting,$action,$email,$randomstr);
        }
		return $user_session_array;
    }
    /*************** Create Token and Send Email ENDS **********************/

	
	
	
	/**************** Set Username STARTS **********************/
	
	function set_unique_username(){
		
		/*$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){  
		
			$user_id = $TokenResponce['userid'];*/
			
			$this->form_validation->set_rules('uniq_name', 'unique name', 'trim|required|is_unique['.USERS.'.user_uname]');
			$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
			
			if ($this->form_validation->run() == TRUE) {
				$user_id= $_POST['user_id'];
				$this->DatabaseModel->access_database(USERS,'update',array('user_uname'=>$_POST['uniq_name']),array('user_id'=>$user_id));
				
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Unique name save successfully.';
					
			}else {
				$this->respMessage =$this->single_error_msg();
			}
		
		/* }else{
			$this->respMessage = $TokenResponce['message'];
		} */
		$this->show_my_response();
	}
	/**************************** Set Username ENDS **********************/
	
	
	/**************************** Get Account Types STARTS **********************/
	public function choose_level() {
		$resp=array();
		$artist_category = $this->DatabaseModel->access_database(ARTIST_CATEGORY,'select','',array('level'=>1,'status'=>1,'category_id !='=>130)); /*Means not official*/
		
		if(!empty($artist_category)){
			$resp = array('artist_category'=>$artist_category);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Choose account type.';
		}
		else {
			$this->respMessage = 'Account type not found.';
		}
		
		$this->show_my_response($resp);
	}
	
	/**************************** Get Account Types ENDS **********************/
	
	
	/**************************** Get Category STARTS **********************/
	public function choose_category(){
		$resp=array();
		$this->form_validation->set_rules('ac_type', 'account type', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
		 
			$cate = $_POST['ac_type'];
			$level_category = $this->DatabaseModel->access_database(LEVELS,'select','',array('cate_id'=>$cate,'level_status'=>1));
			
			if(!empty($level_category)) {
				$resp = array('level_category'=>$level_category);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Choose category.';
			}
			else {
				$this->respMessage = 'Category not found.';
			}
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($resp); 
	}
	
	/**************************** Get Category ENDS **********************/
	
	
	/**************************** Get Primary Type STARTS **********************/
	
	public function choose_primary_type(){
		$resp=array();
		$this->form_validation->set_rules('ac_type', 'account type', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
		 
			$cate = $_POST['ac_type'];
			$artist_category = $this->DatabaseModel->select_data('*',ARTIST_CATEGORY,array('level'=>2,'status'=>1,'parent_id'=>$cate),'','',array('category_name','ASC'));
			
			if(!empty($artist_category)) {
				$resp = array('primary_type'=>$artist_category);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Choose primary type.';
			}
			else {
				$this->respMessage = 'Primary type not found.';
			}
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	/**************************** Get Primary Type ENDS **********************/
	
	
	public function set_artist_info() {
		$resp =array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();

		if($TokenResponce['status'] == 1){  
			
			$userid = $TokenResponce['userid'];
			
			$this->form_validation->set_rules('user_name', 'user name', 'trim|required');
			$this->form_validation->set_rules('user_email', 'user email', 'trim|required');
			$this->form_validation->set_rules('uc_gender', 'user gender', 'trim|required');
			$this->form_validation->set_rules('uc_dob', 'date of birth', 'trim|required');
			$this->form_validation->set_rules('user_phone', 'user phone', 'trim|required');
			$this->form_validation->set_rules('user_address', 'user addrss', 'trim|required');
			$this->form_validation->set_rules('country', 'country', 'trim|required');
			$this->form_validation->set_rules('state', 'state', 'trim|required');
			$this->form_validation->set_rules('city', 'city', 'trim|required');
			$this->form_validation->set_rules('user_accesslevel', 'user level', 'trim|required');
			$this->form_validation->set_rules('user_category', 'user category', 'trim|required');
			
			if ($this->form_validation->run() == TRUE) {
				
				$cate 						= $_POST['user_accesslevel'];	
				$user_category 				= json_decode($_POST['user_category']);
				$user_cate 	   				= implode(',' , $user_category);
				$user_primaryType 			= isset($_POST['user_primaryType'])?json_decode($_POST['user_primaryType']) : ['0'] ;
				$uc_primarytype 			= implode(',',$user_primaryType);
				$where_arr['uc_userid'] 	= $userid;			
				$data_array['uc_type'] 		= $uc_primarytype;
				$data_array['uc_country'] 	= isset($_POST['country']) ? $_POST['country'] : '' ;
				$data_array['uc_state'] 	= isset($_POST['state']) ? $_POST['state'] : '' ;
				$data_array['uc_city'] 		= isset($_POST['city']) ? $_POST['city'] : '' ;
				$data_array['uc_gender'] 	= isset($_POST['uc_gender']) ? $_POST['uc_gender'] : '' ;
				$data_array['uc_dob'] 		= isset($_POST['uc_dob']) ? $_POST['uc_dob'] : '' ; //$_POST['y'].'-'.$_POST['m'].'-'.$_POST['d'] ;
				$data_array['uc_addr1'] 	= isset($_POST['address_line1']) ? $_POST['address_line1'] : '' ;
				$data_array['uc_addr2'] 	= isset($_POST['address_line2']) ? $_POST['address_line2'] : '' ;
				$data_array['uc_phone'] 	= isset($_POST['phone']) ? $_POST['phone'] : '' ;
				$data_array['uc_email'] 	= isset($_POST['email']) ? $_POST['email'] : '' ;
				$data_array['uc_name'] 		= isset($_POST['cont_name']) ? $_POST['cont_name'] : '' ;
				$data_array['uc_website'] 	= isset($_POST['website']) ? $_POST['website'] : '' ;
				 
				// Update Level
				$this->DatabaseModel->access_database('users','update',array('user_address'=>$_POST['user_address'],'user_phone'=>$_POST['user_phone'],'user_email'=>$_POST['user_email'],'user_name'=>$_POST['user_name'],'user_status'=>1,'user_level'=>$cate,'user_cate'=>$user_cate),array('user_id'=>$userid));
			
				// Update User Content
				
				$res = $this->DatabaseModel->select_data('uc_userid','users_content',$where_arr,1); 
				
				if(!empty($res)) {
					$this->DatabaseModel->access_database('users_content','update',$data_array,$where_arr);
				}
				else{
					$q = array_merge($where_arr,$data_array);
					$this->DatabaseModel->access_database('users_content','insert',$q,'');
				}
				

				if( $cate == '1' ) {
					//$_SESSION['user_status'] = 4;
				
					$this->DatabaseModel->access_database('users','update',array('user_status'=>4), array('user_id'=>$userid));
					//redirect(base_url().'home/messages/icon_inactive');
				}
				/*if( $_POST['user_name'] != '' ) {
					$fullname 	= $_POST['user_name'];
					$f_arr		= explode(' ',$fullname);

					$firstname 	= ucfirst($f_arr[0]);
				}else{
					$firstname 	= '';
				}  
				$_SESSION['user_name'] 	= $firstname;
				$user_uname 			= isset($_SESSION['user_uname'])? $_SESSION['user_uname']:'';*/
			
				
				$userDetails 	= $this->DatabaseModel->select_data('user_name,user_uname,user_status,user_level,user_cate,user_email',USERS,array('user_id'=>$userid),1); 
				$userContent 	= $this->DatabaseModel->select_data('uc_type,is_iva,uc_country',USERS_CONTENT,array('uc_userid'=>$userid),1); 
			   
				$user_type 		= '0';
				$is_iva 		= '0';
				$uc_country 	= ' ';
				
				if( isset($userContent[0]) ){
					if(!empty($userContent[0]['uc_type'])){
						$user_type 	= $userContent[0]['uc_type'];
					}
					if(!empty($userContent[0]['is_iva'])){
						$is_iva 	= $userContent[0]['is_iva'];
					}
					if(!empty($userContent[0]['uc_country'])){
						$uc_country = $userContent[0]['uc_country'];
					}
				}
				
				$firstname 	= '';
				if( !empty($userDetails[0]['user_name']) ) {
					$fullname 	= $userDetails[0]['user_name'];
					$f_arr  	= explode(' ',$fullname);
					$firstname 	= ucfirst(isset($f_arr[0]) ? $f_arr[0] : '' );
				}

				$user_array	= array(
					'user_login_id'		=> $userid,
					'user_name'			=> $firstname,
					'user_uname'		=> $userDetails[0]['user_uname'],
					'user_login'		=> true,
					'user_status'		=> $userDetails[0]['user_status'],
					'user_accesslevel'	=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
					'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
					'is_iva'			=> $is_iva,
					'user_primaryType'	=> (string) $user_type,
					'uc_country'		=> $uc_country,
					'user_email'		=> $userDetails[0]['user_email'],
					'iat'				=> time(),
				);
				
				
				/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO START */
				/*$noti_array = array('noti_status'=>1,'noti_type'=>5,'from_user'=>1,'to_user'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
				$this->DatabaseModel->access_database('notifications','insert',$noti_array); */
				/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO END */
				
				/* INSERT NOTIFICATION FOR UPLOAD OFFICIAL VIDEO START */
				
				/*if($cate != 4){
					$noti_array['noti_status'] = 2;
					$this->DatabaseModel->access_database('notifications','insert',$noti_array);
				}*/
				/* INSERT NOTIFICATION FOR UPLOAD OFFICIAL VIDEO END */
				
				//redirect(base_url('profile?user='.$user_uname));
				$resp =array('userData'=>$user_array);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Info save successfully.';
				
			}
			else {
				$this->respMessage = $this->single_error_msg();
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
		 
	}
	
	
	/**************** Get Country STARTS **********************/
	function getCountry(){
		$resp = array(); 
		$countryDetaills = $this->DatabaseModel->access_database(COUNTRY,'select','','');
			 
		if(!empty($countryDetaills)) {
			$resp = array('countryData'=>$countryDetaills,'gender_list'=>$this->audition_functions->genders());
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Choose country.'; 
		}
		else {
			$this->respMessage = 'Country not found.';
		}	
		$this->show_my_response($resp);
	}
	/**************************** Get Country ENDS **********************/
	
	
	/**************** Get States STARTS **********************/
	function getStates(){
		$resp=array();
		$this->form_validation->set_rules('country', 'country', 'trim|required');
			
		if ($this->form_validation->run() == TRUE) {
		 
			$stateDetails 	= $this->DatabaseModel->select_data('id,name',STATE,array('country_id'=>$_POST['country'])); 
			 
			if(!empty($stateDetails)) {
				$resp = array('stateData'=>$stateDetails);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Choose State.'; 
			}
			else {
				$this->respMessage = 'State not found.';
			}
			 
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($resp); 
	}
	/**************************** Get States ENDS **********************/
	
	
}