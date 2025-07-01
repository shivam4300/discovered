<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SendActivationEmail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		if(isset($_POST) && !empty($_POST)){
	        if(!isset($_SERVER['HTTP_REFERER'])){
                die('Direct Access Not Allowed!!');
	        }
	    }
	}
	
	function index(){
		$CURRENT_TIME = date('H:');
		
		$YESTRDAY 	  = date('Y-m-d',strtotime("-1 days")). ' ' . $CURRENT_TIME ;
		
		$DAY_BEFORE_YESTRDAY 	= 	date('Y-m-d',strtotime("-3 days")). ' ' . $CURRENT_TIME ;
		
		$cond		= 	"(users.user_regdate LIKE '%{$YESTRDAY}%' OR users.user_regdate LIKE '%{$DAY_BEFORE_YESTRDAY}%') AND users_content.uc_userid IS NULL " ;
		
		$join 		= 	array('multiple' , array(
							array(	'users_content', 
									'users.user_id 	= users_content.uc_userid', 
									'LEFT')
							)
						);
		$search_result = $this->DatabaseModel->select_data('users.user_id,users.user_regdate,users.user_uname,users.user_name,users.user_email','users',$cond,'',$join);
		
		$subject 	= 'We\'ve noticed you signed up on '. PROJECT .'!';
	
		$greeting 	=  'Your account is almost ready. PLEASE verify your account by clicking the link below. We highly recommend you fully complete your profile and start generating revenue from your original content.';
		
		$action 	= 'If you require any help in setting up your profile and videos, please visit our HELP page - '.base_url('help').'. or CONTACT us at help@discovered.tv';
		
		$button 	= 'Go To '.PROJECT;
		
		$this->load->helper('aws_ses_action');						
		foreach($search_result as $list){
			$email 	= $list['user_email'];
			if(!strpos($email,"@privaterelay.appleid.com") && !strpos($email,"@icloud.com")){
				$pname 	= $list['user_name'];
				$uname 	= $list['user_uname'];
				$link 	= base_url() ; 
			
				send_smtp([
					'greeting'=>'Hi '.$pname.',<br>'.$greeting,
					'action'=>$action,
					'email'=>$email,
					'receiver_email'=>$email,
					'password'=>'',
					'button'=>$button,
					'link'=>$link,
					'subject'=>$subject,
				]);
			}
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
