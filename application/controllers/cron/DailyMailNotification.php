<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DailyMailNotification extends CI_Controller {

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
		$this->DatabaseModel->access_database('cron_test','insert',array('cron_name'=>'DailyMailNotification','date'=>date('Y-m-d H:i:s')));
		$this->mailAndNotiForProfileCompletion();
		$this->mailAndNotiForUploadCoverVideo();
		$this->mailAndNotiForUploadChannelVideo();
		
	}
	
	function mailAndNotiForProfileCompletion(){
		
	}
	
	function mailAndNotiForUploadCoverVideo(){
		
		$YESTRDAY 	= 	date('Y-m-d',strtotime("-1 days"));
		
		$cond		= 	"(users.user_regdate LIKE '".$YESTRDAY."%') AND users_content.aws_s3_profile_video = ''";
		
		$join 		= 	array('multiple' , array(
							array(	'users_content', 
									'users.user_id 	= users_content.uc_userid', 
									'inner')
							)
						);
						
		$search_result = $this->DatabaseModel->select_data('users_content.aws_s3_profile_video,users.user_uname,users.user_name,users.user_email,users.user_firebase_token','users',$cond,10,$join);
		
		$subject 	= 'We\'ve noticed you have not uploaded your cover video';
	
		$greeting 	= 'Your '.PROJECT .' cover video is one of the first things people will see when they visit your '. PROJECT .' environment, and that is exactly why it is so important to make the best first impression possible and for the cover video to convey exactly what it is you are aiming for.';
		
		$action 	= 'Here are the steps you should follow: <br><br>1. Go to your '. PROJECT .' Profile page <br>2. Click on the Add Cover Video button<br>3. Drag and Drop a video or Click to browse and select video to upload <br><br>Enjoy your new Cover Video, you can change and update as often as you like. <br><br>So let\'s get started, click this button below to upload your own cover video';
		
		$button 	= 'My Profile';
		
		$this->load->helper('aws_ses_action');
		foreach($search_result as $list){
			$email 	= $list['user_email'];
			if(!strpos($email,"@privaterelay.appleid.com") && !strpos($email,"@icloud.com")){
				$pname 	= $list['user_name'];
				$uname 	= $list['user_uname'];
				$ftoken = $list['user_firebase_token'];
				
				$link 	= base_url('profile?user='.$uname) ; 
				
				$token_array 	= 	[$ftoken];
				$mess 			= 	$this->audition_functions->getNotiStatus(1,5);
				$msg_array 		=  	[
						'title'	=>	'Hello '.$pname .':',
						'body'	=>	$mess,
						'icon'	=>	base_url('repo/images/firebase.png'),
						'click_action'=>$link
					];
				$this->audition_functions->sendNotification($token_array,$msg_array);
				
				send_smtp([
					'greeting'=>$greeting,
					'action'=>$action,
					'receiver_email'=>$email,
					'email'=>'',
					'password'=>'',
					'button'=>$button,
					'link'=>$link,
					'subject'=>$subject,
				]);
			}
		}
	}
	
	function mailAndNotiForUploadChannelVideo(){
		$YESTRDAY 	= 	date('Y-m-d',strtotime("-1 days"));
		$cond		= 	"(users.user_regdate LIKE '".$YESTRDAY."%') AND users.user_level != 4";
		
		$join 		= 	array('multiple' , array(
							array(	'users_content', 
									'users.user_id 	= users_content.uc_userid', 
									'inner'),
							array(	'channel_post_video', 
									'channel_post_video.user_id = users.user_id', 
									'right'),
							)
						);
						
		$search_result = $this->DatabaseModel->select_data('channel_post_video.post_id,users_content.aws_s3_profile_video,users.user_uname,users.user_name,users.user_email,users.
		user_firebase_token','users',$cond,10,$join,'','','channel_post_video.user_id');
		
		$subject 	= 'DISCOVERED WANTS YOU TO BE FOUND';
	
		$greeting 	= 'Want to monetize your videos? <br> Simply upload your original video by clicking the MONETIZE VIDEO Icon in your Profile Feed next to CREATE SOCIAL POST Icon. Your videos will appear in your Channel and on the '. PROJECT .' Music, Movies or Television homepages.';
		
		$action 	= 'To reach at your profile and access your account either click on the Orange button below or copy and paste the following link into the address bar of your browser.';
		
		$button 	= 'My Profile';
		
		$this->load->helper('aws_ses_action');

		foreach($search_result as $list){
			if(empty($list['post_id'])){
				$email 	= $list['user_email'];
				if(!strpos($email,"@privaterelay.appleid.com") && !strpos($email,"@icloud.com")){
					$pname 	= $list['user_name'];
					$uname 	= $list['user_uname'];
					$ftoken = $list['user_firebase_token'];
					
					$link 	= base_url('profile?user='.$uname) ; 
					
					$token_array 	= 	[$ftoken];
					$mess 			= 	$this->audition_functions->getNotiStatus(1,5);
					$msg_array 		=  	[
							'title'	=>	'Hello '.$pname .':',
							'body'	=>	$mess,
							'icon'	=>	base_url('repo/images/firebase.png'),
							'click_action'=>$link
						];
					$this->audition_functions->sendNotification($token_array,$msg_array);

					send_smtp([
						'greeting'=>$greeting,
						'action'=>$action,
						'receiver_email'=>$email,
						'email'=>'',
						'password'=>'',
						'button'=>$button,
						'link'=>$link,
						'subject'=>$subject,
					]);
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
}
