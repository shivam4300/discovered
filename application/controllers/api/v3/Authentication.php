<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {


	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	public  $deviceType = '';

	public function __construct()
	{
		parent::__construct();
		
		//$this->load->library('creator_jwt');
		//$this->creator_jwt->isAuthorized();
		$this->load->library(array('Audition_functions','query_builder','share_url_encryption','form_validation','common')); 
		$this->load->helper(['playfab', 'iter']);
		$this->load->model('UserModel');
		$this->deviceType = $this->input->get_request_header('device');
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
		return isset($errors[0])?$errors[0]:'';
	}
	
	/**************** Register Email STARTS **********************/
	function register_email($registered_by=''){
		
		$this->form_validation->set_rules('u_em', 'email', 'trim|required|is_unique['.USERS.'.user_email]');
		$this->form_validation->set_rules('u_pwd', 'password', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {

			if(!is_disposable_email($_POST['u_em'])){
			
				$user_name 			= 	isset($_POST['user_name'])? $_POST['user_name'] : '';
				$sigup_acc_type 	= 	(isset($_POST['sigup_acc_type']) && $_POST['sigup_acc_type'] !='')? $_POST['sigup_acc_type'] : 'standard' ;
						
				$randomstr	= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
				
				if(isset($_POST['registered_by'])){
					$registered_by = $_POST['registered_by'];
				}
				
				$param = array(	'user_key'		=>	md5($randomstr),
								'user_email'	=>	$_POST['u_em'],
								'user_name'		=>	$user_name,
								'user_password'	=>	md5($_POST['u_pwd']),
								'referral_by'	=>	isset($_POST['referral_by'])?$_POST['referral_by']:'',
								'referral_from'	=>	isset($_POST['referral_from'])?$_POST['referral_from']:''	,
								'register_by' 	=>	!empty($registered_by)? $registered_by :'ANDROID',
								'sigup_acc_type'=>  $sigup_acc_type,
								'user_location' =>  $this->common->getlocationbyip($this->common->get_client_ip())
								);
									
				if($sigup_acc_type == 'express'){
					$param['user_uname'] = str_replace(" ","_",strtolower($user_name)).'_'.$this->common->generateRandomString($length = 3,$onlyNum=true);
				}					
									
				$userid = $this->DatabaseModel->access_database(USERS,'insert',$param ,'');
				if($sigup_acc_type == 'express' && !empty($userid) ){
					$this->DatabaseModel->access_database('users_content','insert',['uc_userid'=>$userid]);
				}
					
				$email = $_POST['u_em'];
				$link = base_url().'home/verify_email/'.md5($randomstr);
				
				$subject = 'Welcome to '. PROJECT;
				$body ="<p> Hi, <br/> Thank you very much for creating an account with us. Please click on the link to verify your email. <br/><a href='".$link."'> Yes, this is my email</a></p> <p style='text-align:center;'>OR</p><p>Copy this link and paste it in your browser<br/>".$link."</p><br/><br/> Thanks</p> ";
				
				$greeting = 'Thanks for creating an account with us.';
				$action   = 'You have entered <b>'.$email.'</b> as the email address for your account. <br/>To complete your sign up process, simply click the button below so we know this account belongs to you.';

				//$action = 'Please click on the link to verify your email. OR Copy this link and paste it in your browser. ';
				$button = 'Activate Your Account';
				$fullname='';
				$to = '{	
						"email":"'.$_POST['u_em'].'",
						"name":"'.$fullname.'",
						"type":"to"
						}' ;
				//$this->audition_functions->MailByMandrillforLink($to,$subject,$greeting,$action,$button,$link);
				
				$this->load->helper('aws_ses_action');
				send_smtp([
					'greeting'=>$greeting,
					'action'=>$action,
					'email'=>NULL,
					'receiver_email'=>$email,
					'password'=>NULL,
					'button'=>$button,
					'link'=>$link,
					'subject'=>$subject,
				]);

				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Register successfully.';
				
				if($registered_by=='TIZEN'){
					$this->respMessage = 'An activation link has been sent to '.$email.' Please click the link in your email to activate your account. if you cannot see the email from '.PROJECT.' in your inbox. make sure to check your SPAM folder.';
				}
			}else{
				$this->respMessage = 'This email address looks fake or invalid, please enter a real email address.';
			}
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
					
					$this->respMessage = 'You account is blocked. Please contact support via email at support@discovered.tv !';
				}
				else if( $userDetails[0]['user_status'] == '4' ) {
					$this->DatabaseModel->access_database(USERS,'update',array('user_key'=>''),array('user_id'=>$userid));
					
					$this->respMessage = 'icon inactive.';
				}
				else {
					
					$this->respMessage = 'Something went wrong please try again. OR Please contact support via email at support@discovered.tv !';
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
	
	function reset_password(){
		if( isset($_POST['key']) && !empty($_POST['key'])) {
			 
			$this->form_validation->set_rules('pwd', 'Password', 'trim|required');
			if($this->form_validation->run() == TRUE) {
				$key = $_POST['key'];
				$res = $this->DatabaseModel->select_data('user_id',USERS,array('user_key'=>$key),1); 
				if(!empty($res)) {
				
					$this->DatabaseModel->access_database(USERS,'update',array('user_password'=>md5($_POST['pwd']),'user_key'=>''),array('user_key'=>$key));
				
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Password reset successfully.';
					
				}else{
					$this->respMessage = 'User account not found.';
				}
			}else {
				$this->respMessage = $this->single_error_msg();
			}
		}
		else {
			
			$this->form_validation->set_rules('forgot_email', 'email', 'trim|required');
			if($this->form_validation->run() == TRUE) {	
			
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
						$fullname='';
					}


					$email 		= $_POST['forgot_email'];
					$link 		= base_url().'home/reset_password/'.md5($randomstr);
				
					$subject 	= PROJECT . ' - Reset your password';
					
					$greeting 	= 'This email was sent automatically by '.PROJECT.' in response to your request to recover your password. This is done for your protection, only you, the recipient of this email can take the next step in the password recover process.';
					
					$action 	= 'To reset your password and access your account either click on the Orange button on the bottom or copy and paste the following link into the address bar of your browser:';
					$button 	= 'Reset Your Password';
					
					$to 		= '{	
										"email":"'.$email.'",
										"name":"'.$fullname.'",
										"type":"to"
									}' ;
					//$this->audition_functions->MailByMandrillforLink($to,$subject,$greeting,$action,$button,$link);

					$this->load->helper('aws_ses_action');
					send_smtp([
						'greeting'=>$greeting,
						'action'=>$action,
						'email'=>NULL,
						'receiver_email'=>$email,
						'password'=>NULL,
						'button'=>$button,
						'link'=>$link,
						'subject'=>$subject,
					]);
					
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Please check your email for password reset link.';
					 
				}
				else {
					$this->respMessage = 'User account not found.';
				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}
			 
		}
		$this->show_my_response();
	}
	
	/************************* Forgot Password ENDS **********************/
	
	/**************** LOGIN STARTS **********************/
	function allow_login_access(){
		header('Access-Control-Allow-Origin: *');
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
					$this->respMessage = 'You account is blocked. Please contact support via email at support@discovered.tv !';
				}else 
				if( $userDetails[0]['user_status'] == '2' ){
					//$resp = array('status'=>402);  // Inactive
					$this->respMessage = 'Your account is inactive. Please contact us at support@discovered.tv for assistance. !';
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
				$this->respMessage = 'Invalid login credentials. Please try again.';
			}
		}else{
			//$resp = array('status'=>404);
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	function tizen_app_login_access(){
		$resp=array();
		
		$this->form_validation->set_rules('u_em', 'email', 'trim|required');
		$this->form_validation->set_rules('u_pwd', 'password', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
			
			$userDetails = $this->DatabaseModel->select_data('user_id',USERS,array('user_email'=>$_POST['u_em']),1);
			if(!empty($userDetails)){
		
				$userDetails = $this->DatabaseModel->select_data('user_id,user_name,user_status,user_uname,user_level,user_cate',USERS,array('user_email'=>$_POST['u_em'],'user_password'=>md5($_POST['u_pwd'])),1); 
			
				if( isset($userDetails[0]['user_id']) && !empty($userDetails[0]['user_id']) ) {
				
					if( $userDetails[0]['user_status'] == '4' ){
						//$resp = array('status'=>407);// Icon Blocked
						$this->respMessage = 'icon inactive.';
					}else 
					if( $userDetails[0]['user_status'] == '3' ){
						//$resp = array('status'=>403);// Blocked
						$this->respMessage = 'You account is blocked. Please contact support via email at support@discovered.tv !';
					}else 
					if( $userDetails[0]['user_status'] == '2' ){
						//$resp = array('status'=>402);  // Inactive
						$this->respMessage = 'Your account is inactive. Please contact us at support@discovered.tv for assistance.';
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
					$this->respMessage = 'Invalid login credentials. Please try again.';
				}
				
			}else{
				$this->register_email('TIZEN'); // New User Register from tizen
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
        $userDetails 	= $this->DatabaseModel->select_data('user_name,user_uname,user_status,user_level,user_cate,user_email,user_firebase_token,sigup_acc_type,playfab_id',USERS,array('user_id'=>$userid),1); 
		$userContent 	= $this->DatabaseModel->select_data('uc_type,is_iva,uc_country,uc_pic',USERS_CONTENT,array('uc_userid'=>$userid),1); 
       
		$user_type 		= '0';
		$is_iva 		= '0';
		$uc_country 	= ' ';
		$uc_pic         = base_url('repo/images/user/user.png');
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
			if(!empty($userContent[0]['uc_pic'])){
				$upic 	= explode('.', $userContent[0]['uc_pic']);
				if(!empty($upic)){
					$uc_pic = AMAZON_URL."aud_{$userid}/images/{$upic[0]}_thumb.{$upic[1]}";
				}
			}
		}
		
		$firstname 	= '';
		
		$old_firebase_token =array();
		if(!empty($userDetails[0]['user_firebase_token'])){
			$old_firebase_token = json_decode($userDetails[0]['user_firebase_token'],true);
		}
		
        if( !empty($userDetails[0]['user_name']) ) {
            $fullname 	= $userDetails[0]['user_name'];
            $f_arr  	= explode(' ',$fullname);
            $firstname 	= ucfirst(isset($f_arr[0]) ? $f_arr[0] : '' );
		}

        $user_session_array	= array(
            'user_login_id'		=> $userid,
            'user_name'			=> $firstname,
            'user_uname'		=> ($userDetails[0]['user_uname'] !=null)? $userDetails[0]['user_uname']:'',
            'user_login'		=> true,
            'user_status'		=> $userDetails[0]['user_status'],
            'user_accesslevel'	=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
            'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
            'is_iva'			=> $is_iva,
            'user_primaryType'	=> (string) $user_type,
            'uc_country'		=> $uc_country,
            'user_email'		=> $userDetails[0]['user_email'],
			'iat'				=> time(),
			'user_pic'			=> $uc_pic,
			'sigup_acc_type'	=> $userDetails[0]['sigup_acc_type'],
			'playfab_id'		=> $userDetails[0]['playfab_id'],
			'sub'				=> $userid,
		);
		
		$this->load->library('creator_jwt');
		
		$jwtToken 						= 	$this->creator_jwt->GenerateToken($user_session_array);
		$user_session_array['token']	=	$jwtToken;
        
		//$this->session->set_userdata($user_session_array);

		if(!empty($jwtToken)){  // token update for mobile app iframe web login 
			$this->DatabaseModel->access_database(USERS,'update',array('last_login'=>date('Y-m-d H:i:s'),'user_login_token'=>$jwtToken),array('user_id'=>$userid));
		}

		if(isset($_POST['firebase_token']) && !empty($_POST['firebase_token'])){
			
			if(!empty($old_firebase_token)){
				if(isset($_POST['login_from']) && $_POST['login_from']=='IOS'){
					$old_firebase_token['ios'] 		= trim($_POST['firebase_token']);
				}else{
					$old_firebase_token['android'] 	= trim($_POST['firebase_token']);
				}
				$new_firebase_token 				= json_encode($old_firebase_token);
				$update_arr 						= array('user_firebase_token'=>$new_firebase_token);
				
			}else{
				
				$new_firebase_token['web']			= '';
				if(isset($_POST['login_from']) && $_POST['login_from']=='IOS'){
					$new_firebase_token['ios']		= trim($_POST['firebase_token']);
					$new_firebase_token['android']	= '';
				}else{
					$new_firebase_token['android']	= trim($_POST['firebase_token']);
					$new_firebase_token['ios']		= '';
				} 
				$new_firebase_token 				= json_encode($new_firebase_token);
				$update_arr							= array('user_firebase_token'=>$new_firebase_token);
				
			}
			$this->DatabaseModel->access_database(USERS,'update',$update_arr,array('user_id'=>$userid));
		}


		$email = $userDetails[0]['user_email'];
        if( $type == 'new' && !empty($email) && (!strpos($email,"@privaterelay.appleid.com") && !strpos($email,"@icloud.com"))) {
            
            $randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
            $this->DatabaseModel->access_database(USERS,'update',array('user_password'=>md5($randomstr)),array('user_id'=>$userid));
				
            $subject = 'Welcome to '. PROJECT;
            $body ="<p> Hi ".$firstname.", <br/> Thank you very much for creating an account with us. You can login to the ".PROJECT." using your Facebook or Google or even you can use these credentials , <br/> </p> <p> Login Email : ".$email."</p> <p> Password : ".$randomstr."<br/><br/> Thanks.</p> ";

			$greeting = 'Thanks for creating an account with us.';
			$action = 'Now you can login to '. PROJECT .' by using your personal email and password or you can login with your Facebook or Google accounts.';
			
			//$this->audition_functions->MailByMandrillForRegstr($email,$firstname,$subject,$greeting,$action,$email,$randomstr); 

			$this->load->helper('aws_ses_action');
			send_smtp([
				'greeting'=>$greeting,
				'action'=>$action,
				'email'=>$email,
				'receiver_email'=>$email,
				'password'=>$randomstr,
				'button'=>NULL,
				'link'=>NULL,
				'subject'=>$subject,
			]);

        }
		return $user_session_array;
    }
    /*************** Create Token and Send Email ENDS **********************/

	
	/**************** Check User Unique Name Exists STARTS **********************/
	

	function isUniequeNameExists(){
		$resp= array();
		$this->form_validation->set_rules('user_uname', 'unique name', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			
			$uniqNameArr=json_decode($_POST['user_uname']);
			//print_R($uniqNameArr);die;
			if(is_array($uniqNameArr)){
				$uniqNameArr = str_replace("'", '_', $uniqNameArr);
				//print_R($uniqNameArr);die;
				$uniqNameStr = "'" . implode( "','",$uniqNameArr) . "'";
				$cond = 'users.user_uname IN('.$uniqNameStr.')';
				$allUserUniuqeName = $this->DatabaseModel->select_data('UPPER(user_uname)','users',$cond); 
				$allUserUniuqeName = array_map('current', $allUserUniuqeName);
				//print_R($allUserUniuqeName);die;
				if(!empty($allUserUniuqeName)){
					
					foreach($uniqNameArr as $key=>$uname){
						
						if (in_array(strtoupper($uname), $allUserUniuqeName))
						{
							unset($uniqNameArr[$key]); 
							
						}
					}
					
					$resp['uniqueName']	= array_values($uniqNameArr);
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Available unique name.';
				}else{
					$resp['uniqueName']	= $uniqNameArr;
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Available unique name.';
				}
				
			}else{
				$userDetails 	= $this->DatabaseModel->select_data('user_id','users',array('user_uname'=>$_POST['user_uname']),1); 
			
				if(empty($userDetails)) {
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Unique name not exists.';
				}else{
					$this->respMessage = 'Unique name is already taken.';
				}
			}
			
		}else{
			$this->respMessage =$this->single_error_msg();
		}
		
		$this->show_my_response($resp);
	}
	
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
				if($this->DatabaseModel->access_database(USERS,'update',array('user_uname'=>$_POST['uniq_name']),array('user_id'=>$user_id))){
					
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Unique name save successfully.';
				}else{
					$this->respMessage = 'Something went wrong please try again. OR Please contact support via email at support@discovered.tv !';
				}
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
	
	public function set_artist_info_old() {
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
				//$data_array['uc_addr1'] 	= isset($_POST['address_line1']) ? $_POST['address_line1'] : '' ;
				//$data_array['uc_addr2'] 	= isset($_POST['address_line2']) ? $_POST['address_line2'] : '' ;
				$data_array['uc_phone'] 	= isset($_POST['phone']) ? $_POST['phone'] : '' ;
				$data_array['uc_email'] 	= isset($_POST['email']) ? $_POST['email'] : '' ;
				$data_array['uc_name'] 		= isset($_POST['cont_name']) ? $_POST['cont_name'] : '' ;
				$data_array['uc_website'] 	= isset($_POST['website']) ? $_POST['website'] : '' ;
				 
				// Update Level
				$this->DatabaseModel->access_database('users','update',array('user_address'=>$_POST['user_address'],'user_phone'=>$_POST['user_phone'],'user_email'=>$_POST['user_email'],'user_name'=>$_POST['user_name'],'user_status'=>1,'user_level'=>$cate,'user_cate'=>$user_cate),array('user_id'=>$userid));
				syncPlayFabPlayerDisplayName(first($this->UserModel->get($userid)));

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

				/*$user_array	= array(
					'user_login_id'		=> $userid,
					'user_name'			=> $firstname,
					'user_uname'		=> $userDetails[0]['user_uname'],
					'user_login'		=> true,
					'user_status'		=> $userDetails[0]['user_status'],*/
					//'user_accesslevel'	=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
					//'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
					/*'is_iva'			=> $is_iva,
					'user_primaryType'	=> (string) $user_type,
					'uc_country'		=> $uc_country,
					'user_email'		=> $userDetails[0]['user_email'],
					'iat'				=> time(),
				);*/
				
				$userData =  $this->create_token($userid,'old');
				
				/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO START */
				$noti_array = array('noti_status'=>1,'noti_type'=>5,'from_user'=>1,'to_user'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
				$this->DatabaseModel->access_database('notifications','insert',$noti_array); 
				/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO END */
				
				/* INSERT NOTIFICATION FOR UPLOAD OFFICIAL VIDEO START */
				
				if($cate != 4){
					$noti_array['noti_status'] = 2;
					$this->DatabaseModel->access_database('notifications','insert',$noti_array);
				}
				/* INSERT NOTIFICATION FOR UPLOAD OFFICIAL VIDEO END */
				
				//redirect(base_url('profile?user='.$user_uname));
				$resp =array('userData'=>$userData);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Information saved successfully.';
				
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
			$this->form_validation->set_rules('uniq_name', 'unique name', 'trim|required|alpha_dash|is_unique['.USERS.'.user_uname]');
			$this->form_validation->set_rules('user_phone', 'user phone', 'trim|required');
			$this->form_validation->set_rules('user_address', 'user addrss', 'trim|required');
			$this->form_validation->set_rules('country', 'country', 'trim|required');
			$this->form_validation->set_rules('state', 'state', 'trim|required');
			$this->form_validation->set_rules('city', 'city', 'trim|required');
			$this->form_validation->set_rules('user_accesslevel', 'user level', 'trim');
			$this->form_validation->set_rules('user_category', 'user category', 'trim|required');
			
			if ($this->form_validation->run() == TRUE) {
				
				$cate 						= (isset($_POST['user_accesslevel']) && !empty($_POST['user_accesslevel'])) ? $_POST['user_accesslevel'] : 4;		
				$user_category 				= json_decode($_POST['user_category']);
				$user_cate 	   				= implode(',' , $user_category);
				$user_primaryType 			= isset($_POST['user_primaryType'])?json_decode($_POST['user_primaryType']) : ['0'] ;
				$uc_primarytype 			= implode(',',$user_primaryType);
				$where_arr['uc_userid'] 	= $userid;			
				$data_array['uc_type'] 		= $uc_primarytype;
				$sigup_acc_type 			= isset($_POST['sigup_acc_type'])? $_POST['sigup_acc_type'] : 'standard' ;
				$data_array['uc_country'] 	= isset($_POST['country']) ? $_POST['country'] : '' ;
				$data_array['uc_state'] 	= isset($_POST['state']) ? $_POST['state'] : '' ;
				$data_array['uc_city'] 		= isset($_POST['city']) ? $_POST['city'] : '' ;
				$data_array['uc_gender'] 	= isset($_POST['uc_gender']) ? $_POST['uc_gender'] : '' ;
				$data_array['uc_dob'] 		= isset($_POST['uc_dob']) ? $_POST['uc_dob'] : '' ; //$_POST['y'].'-'.$_POST['m'].'-'.$_POST['d'] ;
				//$data_array['uc_addr1'] 	= isset($_POST['address_line1']) ? $_POST['address_line1'] : '' ;
				//$data_array['uc_addr2'] 	= isset($_POST['address_line2']) ? $_POST['address_line2'] : '' ;
				$data_array['uc_phone'] 	= isset($_POST['phone']) ? $_POST['phone'] : '' ;
				$data_array['uc_email'] 	= isset($_POST['email']) ? $_POST['email'] : '' ;
				$data_array['uc_name'] 		= isset($_POST['cont_name']) ? $_POST['cont_name'] : '' ;
				$data_array['uc_website'] 	= isset($_POST['website']) ? $_POST['website'] : '' ;
				$data_array['interest'] 	= isset($_POST['interest']) ? $_POST['interest'] : 0 ;

				// Update Level
				$this->DatabaseModel->access_database('users','update',array('user_uname'=>$_POST['uniq_name'],'user_address'=>$_POST['user_address'],'user_phone'=>$_POST['user_phone'],'user_email'=>$_POST['user_email'],'user_name'=>$_POST['user_name'],'user_status'=>1,'user_level'=>$cate,'user_cate'=>$user_cate,'sigup_acc_type'=>$sigup_acc_type),array('user_id'=>$userid));
				syncPlayFabPlayerDisplayName(first($this->UserModel->get($userid)));
			
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

				/*$user_array	= array(
					'user_login_id'		=> $userid,
					'user_name'			=> $firstname,
					'user_uname'		=> $userDetails[0]['user_uname'],
					'user_login'		=> true,
					'user_status'		=> $userDetails[0]['user_status'],*/
					//'user_accesslevel'	=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
					//'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
					/*'is_iva'			=> $is_iva,
					'user_primaryType'	=> (string) $user_type,
					'uc_country'		=> $uc_country,
					'user_email'		=> $userDetails[0]['user_email'],
					'iat'				=> time(),
				);*/
				
				$userData =  $this->create_token($userid,'old');
				
				/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO START */
				$noti_array = array('noti_status'=>1,'noti_type'=>5,'from_user'=>1,'to_user'=>$userid,'created_at'=>date('Y-m-d H:i:s'));
				$this->DatabaseModel->access_database('notifications','insert',$noti_array); 
				/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO END */
				
				/* INSERT NOTIFICATION FOR UPLOAD OFFICIAL VIDEO START */
				
				if($cate != 4 && $sigup_acc_type =='standard'){
					$noti_array['noti_status'] = 2;
					$this->DatabaseModel->access_database('notifications','insert',$noti_array);
					
					/*  Send notification for joined new Icon,Emerging AND Brand  */
					//$this->audition_functions->sendNotiOnJoinedNewIcon($userid,$cate);
				}
				/* INSERT NOTIFICATION FOR UPLOAD OFFICIAL VIDEO END */
				
				
				
				//redirect(base_url('profile?user='.$user_uname));
				$resp =array('userData'=>$userData);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Information saved successfully.';
				
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
		$order 		= array('country_name','ASC');
		$countryDetaills = $this->DatabaseModel->select_data('*', COUNTRY,'','','',$order);
		//$countryDetaills =  $this->DatabaseModel->select_data('CONCAT(UPPER(SUBSTRING(country_name,1,1)),LOWER(SUBSTRING(country_name,2))) AS Name',COUNTRY); 
		//$countryDetaills =  $this->DatabaseModel->select_data('CONCAT(UCASE(LEFT(country_name,1)),LCASE(SUBSTRING(country_name,2))) AS Name',COUNTRY); 
			$gender_list=array();
			$gender = $this->audition_functions->genders();
			foreach($gender as $key=>$gen){
				$gender_list[] =array('id'=>$key,'type'=>$gen); 
			}
			
		if(!empty($countryDetaills)) {
			foreach($countryDetaills as $key=>$con){
				$countryDetaills[$key]['country_name'] =ucwords(strtolower($con['country_name'])); 
			}
			$resp = array('countryData'=>$countryDetaills,'gender_list'=>$gender_list);
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
			$order 		= array('name','ASC');
			$stateDetails 	= $this->DatabaseModel->select_data('id,name',STATE,array('country_id'=>$_POST['country']),'','',$order); 
			 
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
	
	/**************** Social Login START *****************/
	public function social_login(){
		$resp=array();
		try{
			/*$this->form_validation->set_rules('user_email', 'Email', 'trim|required');*/
			$this->form_validation->set_rules('user_social', 'Social id', 'trim|required');
			$this->form_validation->set_rules('user_name', 'User name', 'trim');
			if ($this->form_validation->run() == TRUE){	
			
				$email 		= (isset($_POST['user_email']) && !empty($_POST['user_email']) && $_POST['user_email'] != 'undefined')? $_POST['user_email'] : '';
				$socialId 	= $_POST['user_social'];
				$user_name 	= !empty($_POST['user_name']) ? $_POST['user_name'] : 'NA';
				$cond 		= "user_social = '{$socialId}'";
				$sigup_acc_type 	= (isset($_POST['sigup_acc_type']) && $_POST['sigup_acc_type'] !='')? $_POST['sigup_acc_type'] : 'standard' ;
				
				if(isset($_POST['registered_by']) && $_POST['registered_by'] !='ANDROID' && empty($sigup_acc_type)){
					$sigup_acc_type 	= 'standard';	
				}
				
				
				if(!empty($email)){
					$cond .= " OR user_email = '{$email}'";
				}
				
				/*$cond 		= "user_email = '{$email}' OR user_social = '{$socialId}'";*/
			
				$checkUser 	= $this->DatabaseModel->select_data('user_id,user_name,user_status,user_uname,user_level,user_cate','users',$cond,1);
		
				if(empty($checkUser) && empty($sigup_acc_type)) {
					
					$this->statusCode = 2;
					$this->statusType = 'Success';
					$this->respMessage= 'Please select account type.';
					$resp = array('userData'=>array('user_email'=>$email,'user_social'=>$socialId, 'user_name'=>$user_name));
					
				}else if(empty($checkUser) && !empty($sigup_acc_type)){
					
						$insert_array = array(
							'user_name'   	=>  $user_name,
							'user_email'   	=>  $email,
							'user_status'   =>  1,
							'user_social'   =>  $socialId,
							'referral_by'	=>	isset($_POST['referral_by'])?$_POST['referral_by']:''	,
							'referral_from'	=>	isset($_POST['referral_from'])?$_POST['referral_from']:'' ,	
							'register_by'	=>  isset($_POST['registered_by'])?$_POST['registered_by']:'ANDROID',
							'sigup_acc_type'=>  $sigup_acc_type,
							'user_location' =>  $this->common->getlocationbyip($this->common->get_client_ip())
						);
						if($sigup_acc_type == 'express'){
							$insert_array['user_uname'] = str_replace(" ","_",strtolower($user_name)).'_'.$this->common->generateRandomString($length = 3,$onlyNum=true);
						}

						$userId = $this->DatabaseModel->access_database('users','insert',$insert_array,'');
						syncPlayFabPlayerDisplayName(first($this->UserModel->get($userId)));
						
						if($sigup_acc_type == 'express' && !empty($userid) ){
							$this->DatabaseModel->access_database('users_content','insert',['uc_userid'=>$userid]);
						} 
						
						$userData =$this->create_token($userId,'new');
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Login successfully.';
						$resp = array('userData'=>$userData);
				}else {
					
					$this->DatabaseModel->access_database('users','update',array('user_social'=>$socialId),array('user_id' => $checkUser[0]['user_id']));
					syncPlayFabPlayerDisplayName(first($this->UserModel->get($checkUser[0]['user_id'])));

					if( $checkUser[0]['user_status'] == '4' ){
							//$resp = array('status'=>407);// Icon Blocked
							$this->respMessage = 'icon inactive.';
					}else 
					if( $checkUser[0]['user_status'] == '3' ){
						//$resp = array('status'=>403);// Blocked
						$this->respMessage = 'You account is blocked. Please contact support via email at support@discovered.tv !';
					}else 
					if( $checkUser[0]['user_status'] == '2' ){
						//$resp = array('status'=>402);  // Inactive
						$this->respMessage = 'Your account is inactive. Please contact us at support@discovered.tv for assistance.';
					}else 
					if( $checkUser[0]['user_status'] == '1' ){
						//$this->SaveLoginCookie();
						$userData =  $this->create_token($checkUser[0]['user_id'],'old');
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Login successfully.';
						$resp = array('userData'=>$userData);
					}
				}
			
			}else{
				$this->respMessage =$this->single_error_msg();
			}

		} catch (Exception $e) {
			$resp['data'] = $e;
			$this->respMessage = $e->getMessage();
		}
		$this->show_my_response($resp);
	}
	
	/**************** Social Login ENDS *****************/
	
	
	
	/**************** Update Users Sstting STARTS **********************/
	
	function update_user_setting(){
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){  
		
			$user_id = $TokenResponce['userid'];
			
			$this->form_validation->set_rules('noti_status', 'Notification status', 'trim|required');
			//$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
			
			if ($this->form_validation->run() == TRUE) {
				//$user_id= $_POST['user_id'];
				$statusValue = $_POST['noti_status'];
				$checkUser 	= $this->DatabaseModel->select_data('user_setting','users_content',array('uc_userid'=>$user_id),1); 
				
				if(!empty($checkUser[0]['user_setting'])){
					
					$previousSetting  =json_decode($checkUser[0]['user_setting'],true);
					
					if(isset($previousSetting['notireceive_status'])){
						$previousSetting['notireceive_status'] =$statusValue;
						$updateSetting =array('user_setting'=>json_encode($previousSetting));
					}else{
						$updateSetting  =array('user_setting'=>json_encode(array('notireceive_status'=>$statusValue)));
						
					}
				}else{
					$updateSetting  =array('user_setting'=>json_encode(array('notireceive_status'=>$statusValue)));
				}
				
				$this->DatabaseModel->access_database(USERS_CONTENT,'update',$updateSetting,array('uc_userid'=>$user_id));
				
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Setting updated successfully.';
					
			}else {
				$this->respMessage =$this->single_error_msg();
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		} 
		$this->show_my_response();
	}

	/**************************** Update Users Sstting ENDS **********************/
	
	
	/**************** Delete user account STARTS **********************/
	
	function delete_my_account(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){  
		
			$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
			
			if ($this->form_validation->run() == TRUE) {
				$user_id = $TokenResponce['userid'];
			
				$updatetatus = array('user_status' =>2,
									 'is_deleted' =>1,
									 'date_of_delete_or_reactivate'=>date('Y-m-d'),
									 'reason_of_delete'=>'Deleted from '.$this->deviceType.' mobile APP' 
									);
			
				$this->DatabaseModel->access_database(USERS,'update',$updatetatus,array('user_id'=>$user_id));
			
				$this->statusCode  = 1;
				$this->statusType  = 'Success';
				$this->respMessage = 'Your account deleted successfully. if you want to Re-active your account please contact support.';
			}else {
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		} 
		$this->show_my_response();
	}

	/**************************** Delete user account ENDS **********************/
	
	
	
	/******************** Google Login STARTS ***************************/

	public function googlelogin(){
		
        include_once("google_login/autoload.php");
        $client_id = '718070016203-lv4528j176lsgl932cgsoguf4g3r92ea.apps.googleusercontent.com';
        $client_secret = 'mlKOQZP-T0AAWfhqS7cSnRoz';
        $redirect_uri = base_url().'home/googlelogin';

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");


        $service = new Google_Service_Oauth2($client);
       
	   if (isset($_GET['code'])) {
          $client->authenticate($_GET['code']);
          $_SESSION['access_token'] = $client->getAccessToken();
        }else {
          $authUrl = $client->createAuthUrl();
          redirect($authUrl);
        }

        /************************************************
          If we have an access token, we can make
          requests, else we generate an authentication URL.
         ************************************************/
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		
            $client->setAccessToken($_SESSION['access_token']);
            $user = $service->userinfo->get();

            $email 		= $user['email'];
            $socialId 	= $user['id'];
			
			$cond = "user_email = '{$email}' || user_social = '{$socialId}'";
          
			$checkUser 	= $this->DatabaseModel->select_data('user_id','users',$cond,1); 
			
            if(empty($checkUser)) {
					
                $name = $user['givenName'].' '.$user['familyName'];

                $insert_array = array(
                    'user_name'   	=>  $name,
                    'user_email'   	=>  $user['email'],
                    'user_status'   =>  1,
                    'user_social'   =>  $socialId,
					'referral_by'	=>	isset($_SESSION['referral_by'])?$_SESSION['referral_by']:'',
					'referral_from'	=>	isset($_SESSION['referral_from'])?$_SESSION['referral_from']:''	
                );
                $userId = $this->DatabaseModel->access_database('users','insert',$insert_array,'');
                $this->create_session($userId,'new');
            }
            else {
				
                $this->create_session($checkUser[0]['user_id'],'old');
            }
			 $redirectUrl = base_url();	
			  if(isset($_SESSION['user_uname'])){
				   $redirectUrl  =  base_url('profile?user='.strtolower($_SESSION['user_uname']));
			  }
            echo '<script type="text/javascript">window.close(); 
					 window.opener.location.href = '.'"'. $redirectUrl.'"'.';
				  </script>';

        } else {
			
            $authUrl = $client->createAuthUrl();
            redirect($authUrl);
        }

	}
	
	
	public function sendRequestToLiveStream(){
	
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
			
			if($TokenResponce['status'] == 1){
				
				$uid = $TokenResponce['userid'];
				
				$this->form_validation->set_rules('livestream_user_input', 'stream data', 'trim|required');
			//	$this->form_validation->set_rules('average_stream_duration','average stream duration', 'trim|required');
				//$this->form_validation->set_rules('streams_per_month', 'stream per month', 'trim');
				//$this->form_validation->set_rules('total_number_of_stream', 'total number of stream', 'trim|required');
				
				if ($this->form_validation->run() == TRUE){
					
					$userInput = json_decode($_POST['livestream_user_input'],true);
					
					$fields = array('number_of_viewers','average_stream_duration','streams_per_month','total_number_of_stream');
					
					$postData = array_combine($fields, $userInput);
					
					//print_r($postData);die;
					
					$ivs_info = $this->DatabaseModel->select_data('status','users_ivs_info',array('user_id' => $uid ),1);
					
					if(empty($ivs_info)){
						$this->load->helper('aws_ivs_action');
						
						$optionArr = array(
							'latencyMode' 	=> 'LOW',
							'name' 			=> 'channel-'.$uid,
							'type' 			=> 'STANDARD',
						);
						$r = createChannel($optionArr); 
						
						if($r['status'] == 1){
							
							$data['channel'] 	= $r['data']['channel'];
							$data['streamKey'] 	= $r['data']['streamKey'];
							
							$optionArr = array(
								'user_id' 		=> 	$uid,
								'status' 		=> 	2,
								'channel_arn' 	=> 	$data['channel']['arn'],
								'ivs_info'		=>	json_encode($data),
								'stream_info'	=>	json_encode($postData)
							);
							
							$iv = $this->DatabaseModel->access_database('users_ivs_info','insert',$optionArr);
							if($iv){
								$this->statusCode 	= 1;
								$this->statusType = 'Success';
								$this->respMessage = 'We have received your request for live streaming, you will get a notification and an email when it is approved.';
							}else{
								$this->statusCode 	= 1;
								$this->statusType = 'Success';
								$this->respMessage = 'Already requested ! Please try again. ';
							}
						}else{
							$this->statusCode 	= 1;
							$this->statusType = 'Success';
							$this->respMessage = $r['message'];
						}
						
					}else{
						if($this->DatabaseModel->access_database('users_ivs_info','update',array('status'=>2),array('user_id'=>$uid))){
							$this->statusCode 	= 1;
							$this->statusType = 'Success';
							$this->respMessage = 'We have received your request for live streaming, you will get a notification and an email when it is approved.';
						}else{
							$this->statusCode 	= 1;
							$this->statusType = 'Success';
							$this->respMessage = 'You have already requested to for live stream';
						}
					}
				}else{
					
					$this->respMessage  =  $this->single_error_msg();
				}
			}else{
				$this->respMessage = $TokenResponce['message'];
			}
		
		$this->show_my_response($resp=[]);
	}
	
	
	public function signupFeaturesList(){
		
		$resp['featuresList']  = array(
		
						array('features'=>'Typical For Fans',	'visitor'=>true, 'express'=>true, 'standard'=>false),
						array('features'=>'Typical For Icons/Creators/Brands',	'visitor'=>false, 'express'=>false, 'standard'=>true),
						array('features'=>'Watch Video, Stream’s',	'visitor'=>true, 'express'=>true, 'standard'=>true),
						array('features'=>'Share',					'visitor'=>true, 'express'=>true, 'standard'=>true),
						array('features'=>'Search, HELP',			'visitor'=>true, 'express'=>true, 'standard'=>true),
						array('features'=>'Pairing/ Casting On TV', 'visitor'=>true, 'express'=>true, 'standard'=>true),
						array('features'=>'Vote',					'visitor'=>false,'express'=>true, 'standard'=>true),
						array('features'=>'Invite Only Shows',		'visitor'=>false,'express'=>true, 'standard'=>true),
						array('features'=>'Ticketed/ VOD Events',   'visitor'=>false,'express'=>true, 'standard'=>true),
						array('features'=>'Social, Chat, News Feed','visitor'=>false,'express'=>true, 'standard'=>true),
						array('features'=>'Playlists',				'visitor'=>false,'express'=>false,'standard'=>true),
						array('features'=>'Branded Channel Page',	'visitor'=>false,'express'=>false,'standard'=>true),
						array('features'=>'Monetize Videos',		'visitor'=>false,'express'=>false,'standard'=>true),
						array('features'=>'Store',					'visitor'=>false,'express'=>false,'standard'=>true),
						array('features'=>'Gaming, Streaming',		'visitor'=>false,'express'=>false,'standard'=>true),
						array('features'=>'Get PAID',				'visitor'=>false,'express'=>false,'standard'=>true),
						array('features'=>'Dashboards, Analytics',	'visitor'=>false,'express'=>false,'standard'=>true)
	
					);
		foreach ($resp['featuresList'] as $key => $answer) {
			unset($resp['featuresList'][$key]['express']);
		}
		$this->statusCode  	= 1;
		$this->statusType   = 'Success';
		$this->respMessage  = 'Signup features list.';
		$this->show_my_response($resp);
	}


	public function getIconVerifyText(){

		$data['heading']  			= 'Verify Icon Status';

		$data['title']    			= '* You must SELECT/VERIFY at least one of the three questions below for Verified Icon Status';

		$data['iconCheckBox'] 		= array(	'Had or Currently have distribution by a Major or Independent entertainment company',
												'Toured internationally or domestically for audiences of 5,000 or more',
												'Have been or currently are a member of SAG, Academy of Motion Picture Arts and Sciences, of the Recording Academy'
										);
		$data['emergingCheckBox'] 	= array(	'If you cannot verify any of these, you must check this box and sign up as an Emerging Creator.',
												'Falsely verifying your status will result in removal as an Icon.'
										);
		$resp['textList'] 	= $data;
		$this->statusCode  	= 1;
		$this->statusType   = 'Success';
		$this->respMessage  = 'Icon Verify text list.';
		$this->show_my_response($resp);
	}
	
	
	function updateJson($jsonData, $addNewData,$keyExist){
		$data=[];
		if(!empty($jsonData)){
			$data = json_decode($jsonData);
			if(!empty($data)){
				foreach($data as $key=>$val){
					if($key == $keyExist){
						$data[$key] =  $addNewData;
					}
				}
			}			
		}
		return json_encode($data);	
	}
	
	function insertJson($jsonData, $addNewData){
		$data =[];
		if(!empty($jsonData)){
			$data = json_decode($jsonData);
			$data[] =$addNewData;
		}
		return json_encode($data);
	}
	
	
}