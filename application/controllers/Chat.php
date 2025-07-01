<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller{
	
	private $uid;
	public $statusCode = '';
	public $respMessage = '';
	
	public function __construct(){
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->library(array('manage_session','dashboard_function')); 
		$this->uid = is_login();
	}
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	public function send_message(){
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			if( isset($_POST['to_uid']) && isset($_POST['message']) && !empty($this->uid) && $this->input->is_ajax_request()) 
			{
				$to_user = trim($_POST['to_uid']);
				$message = trim($_POST['message']);
				
				$reply_id = (isset($_POST['reply_mess_id']) && !empty($_POST['reply_mess_id']))?$_POST['reply_mess_id']:'';
				
				$noti_status  = !empty($reply_id)?2:1;
				
				$reference_id = !empty($reply_id)?$this->uid:$to_user;
				
				$insert_array = array(	'noti_type'		=>	6,
										'noti_status'	=>	$noti_status,
										'from_user'		=>	$this->uid,
										'to_user'		=>	$to_user,
										'reference_id'	=>	$reference_id,
										'created_at'	=>	date('Y-m-d H:i:s'),
										
										);
				$this->audition_functions->insertNoti($insert_array);
				
				$token 	= $this->audition_functions->getFirebaseToken($to_user);
				$link 	= $this->audition_functions->getNotiLink($noti_status,6,$reference_id,true);

				if(!empty($token)){
					$token_array 	= 	[$token];
					$mess 			= 	$this->audition_functions->getNotiStatus(1,6);
					$fullname 		= 	$this->audition_functions->get_user_fullname($this->uid);
					
					$msg_array 		=  	[
							'title'	=>	$fullname .': '. $mess ,
							'body'	=>	$message ,
							'icon'	=>	base_url('repo/images/firebase.png'),
							'click_action'=>$link
						];
					
					$this->audition_functions->sendNotification($token_array,$msg_array);
				}
				
				$data_array = array(
									'from_user_id' 	=>  $this->uid,
									'to_user_id'	=>	$to_user,
									'message'		=>  $message,
									'created_at'	=>	date('Y-m-d H:i:s'),
									'reply_mess_id'	=>	$reply_id
								);
				$mess_id = $this->DatabaseModel->access_database('chat_messages','insert',$data_array);
				$resp = array('mess_id'=>$mess_id);
				$this->statusCode = 1;
				
			}else{
				$this->respMessage ='Something Went Wrong.' ;
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	function FeatchMessage($by_mess_id=""){
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			if(!empty($this->uid) && $this->input->is_ajax_request()) 
			{
				
				$post_uid = $_POST['to_uid'];
				
				$offset=1;
				if(isset($_POST['limit'])){
					$limit = $_POST['limit'];
					$start = $_POST['start'];
					$offset = array($limit ,$start);
				}
				 
				if($this->uid == $post_uid){
					$cond = array('chat_messages.to_user_id'=>$post_uid, 'reply_mess_id'=> 0);
				}else{
					$cond = array('chat_messages.from_user_id'=>$this->uid,'chat_messages.to_user_id'=>$post_uid, 'reply_mess_id'=> 0);
				}
				
				
				if(!empty($by_mess_id)){
					$cond = array('chat_messages.mess_id'=>$by_mess_id);
				}
				
				$join = array('multiple' , array(
										array(	'users', 
												'users.user_id 	= chat_messages.from_user_id',
												'left'),
										array(	'artist_category', 
												'users.user_level 	= artist_category.category_id', 
												'left'),	
										));
				
				
				$chat_messages = $this->DatabaseModel->select_data('chat_messages.*,artist_category.category_name,users.user_uname,users.user_name','chat_messages',$cond,$offset,$join,array('chat_messages.mess_id','desc'));
				
				$data = [];
				if(isset($chat_messages[0])){
					
					foreach($chat_messages as $chat){
						
						
						$from_uid 	= $chat['from_user_id'];
						$from_name 	= $chat['user_name'];
						$message 	= $chat['message'];
						$user_type 	= $chat['category_name'];
						$status 	= $chat['status'];
						
						$from_uname = base_url('profile?user='.$chat['user_uname']);
						$created_dt = $this->common->manageTimezone($chat['created_at']);
						
						$creat_date = date('d F Y',strtotime($created_dt));
						$creat_time = date('h:i A',strtotime($created_dt));
						
						$message_status = '';
						if( $status == 0){
							$message_status = '<div class="invited_icon new"><span>New</span></div>';
						}
						
						$mess_id = $chat['mess_id'];
						
						$reply 	 = '';
						
						$cond = array('chat_messages.reply_mess_id'=>$mess_id);
						
						$reply_messages = $this->DatabaseModel->select_data('chat_messages.*,artist_category.category_name,users.user_uname,users.user_name','chat_messages',$cond,1,$join);
							
							
							if(!empty($reply_messages[0])){
								$reply_messages = $reply_messages[0];
								
								$reply_uid 		= $reply_messages['from_user_id'];
								$reply_name 	= $reply_messages['user_name'];
								$reply_message 	= $reply_messages['message'];
								$reply_user_type= $reply_messages['category_name'];
								$reply_status 	= $reply_messages['status'];
								
								$reply_uname = base_url('profile?user='.$reply_messages['user_uname'].'#message' );
								$reply_created_dt = $this->common->manageTimezone($reply_messages['created_at']);
								
								$reply_creat_date = date('d F Y',strtotime($reply_created_dt));
								$reply_creat_time = date('h:i A',strtotime($reply_created_dt));
								
								$reply = '<div class="dis_user_post_data nmsg_subcontent" >
															
															<div class="dis_user_post_header">
																<div class="dis_user_img">														
																	<img src="'.get_user_image($reply_uid).'" alt="thumb_img" >
																</div>
																<div class="dis_user_detail">
																	<h3>
																		<a href="'.$reply_uname.'">'.$reply_name.'</a>, 
																		<span class="dis_pub_reason">'.$reply_user_type.'</span> 
																	</h3> 
																	<p class="published_date"> Sent On '.$reply_creat_date.' at '.$reply_creat_time.' </p>
																</div>
															</div>
															
															<div class="dis_user_post_content">
																<span class="contentText">
																'.nl2br($this->dashboard_function->partOfString($reply_message,0,500)).'
																</span>
															</div>
															
															<div class="invited_icon">
																<span>
																	<svg xmlns="http://www.w3.org/2000/svg" width="13px" height="16px">
																	<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
																	 d="M0.388,5.430 C1.171,3.414 3.287,2.406 6.735,2.406 L8.360,2.406 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.088,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.083 2.615,7.295 C2.378,7.508 2.184,7.761 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.556,9.945 1.556,10.451 C1.556,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.567,11.937 C1.525,11.986 1.468,12.011 1.396,12.011 C1.319,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.001,11.494 C0.964,11.409 0.939,11.349 0.925,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"/>
																	</svg>
																</span>
															</div>
															
														</div>';
								
									
							
							}else{
								if(is_session_uid($post_uid)){
									$reply = '<div class="nmsg_preply">
												<a class="OpenReplyBox" data-mess_id="'.$mess_id .'"><svg xmlns="http://www.w3.org/2000/svg" width="13px" height="12px" viewBox="0 0 13 12"><path fill-rule="evenodd" fill="rgb(235, 88, 31)" d="M0.388,5.430 C1.171,3.414 3.287,2.406 6.735,2.406 L8.360,2.406 L8.360,0.485 C8.360,0.355 8.406,0.242 8.498,0.147 C8.590,0.052 8.699,0.005 8.825,0.005 C8.950,0.005 9.059,0.052 9.151,0.147 L12.865,3.990 C12.957,4.085 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.570 12.865,4.665 L9.151,8.508 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.508 C8.406,8.413 8.360,8.300 8.360,8.170 L8.360,6.249 L6.735,6.249 C6.262,6.249 5.837,6.263 5.462,6.294 C5.088,6.323 4.715,6.377 4.345,6.455 C3.975,6.533 3.654,6.639 3.380,6.774 C3.107,6.909 2.852,7.083 2.615,7.295 C2.378,7.508 2.184,7.761 2.034,8.054 C1.885,8.346 1.767,8.692 1.683,9.093 C1.598,9.493 1.556,9.946 1.556,10.451 C1.556,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.551 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.825 1.608,11.887 1.567,11.937 C1.525,11.987 1.468,12.012 1.396,12.012 C1.319,12.012 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.099,11.719 C1.070,11.654 1.037,11.579 1.001,11.494 C0.964,11.409 0.939,11.350 0.925,11.314 C0.310,9.888 0.003,8.760 0.003,7.930 C0.003,6.934 0.131,6.101 0.388,5.430 Z"></path></svg> Reply</a>
											</div>
											
											<div class="nmsg_send_wraaper" id="ReplyBox'.$mess_id.'" style="display:none;">
												<span class="nm_user_thumn">
												   <img onerror="this.onerror=null;this.src=\''.base_url('repo/images/user/user.png').'\'" src="'.get_user_image($mess_id).'" alt="" class="img-responsive">
												</span>
												<div class="nm_textarea">
													<textarea class="" rows="4" placeholder="Enter Your Message..." 
													id="ReplyMessage'.$mess_id.'" data-length="5000" maxlength="5000"></textarea>
												</div> 
											</div>
										
											<div class="nmsg_btn" id="ReplyButton'.$mess_id.'" style="display:none;">
												<a data-to_uid = "'.$from_uid.'" data-reply_mess_id="'.$mess_id .'" class="dis_btn SentReplyMessage">reply</a>
											</div>';
								}
								
							}
						
						
						
						
						
						array_push($data,'<div class="dis_user_post_data nmsg_subcontent" id="parent_post_messsge_'.$mess_id.'">
												
												<div class="dis_user_post_header">
													<div class="dis_user_img">
													<img alt="thumb_img" src="'.get_user_image($from_uid).'">
													</div>
													<div class="dis_user_detail">
														<h3>
															<a href="'.$from_uname.'">'.$from_name.'</a>, 
															<span class="dis_pub_reason">'.$user_type.'</span> 
														</h3> 
													<p class="published_date"> Sent On '.$creat_date.' at '.$creat_time.' </p>
													</div>
												</div>
												
												<div class="dis_user_post_content">
													<span class="contentText" >
													'.nl2br($this->dashboard_function->partOfString($message,0,500)).'
													</span>
													
													'.$reply.'
												</div> 
												 
												'.$message_status.'		
	
											</div>');
					
							if($this->uid == $post_uid)
							$this->DatabaseModel->access_database('chat_messages','update',array('status'=>1),array('mess_id'=>$mess_id));
					}
					
					$this->statusCode = 1;
					$resp =  array('data' => $data);
				
				}else{
					$this->respMessage ='No More Message availble.' ;
				}
				
			}else{
				$this->respMessage ='Something Went Wrong.' ;
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	public function firebase_chat_demo(){
			$cond = "users.user_id =  " .$this->uid;
		$join = array('multiple' , array(
							array(	'users_content', 
									'users.user_id 	= users_content.uc_userid', 
									'right'),
							));
		$me = $this->DatabaseModel->select_data('users_content.uc_pic,users.user_id,users.user_uname,users.user_name,users.user_email','users',$cond,1,$join);
		
		$data['user_info']  = 	isset($me[0])? $me[0] : array();
		$data['page_info'] 	= 	array('page'=>'livechat','title'=>'Live Chat');
		$this->load->view('common/firebase_chat',$data);
	}
	function MyUsersList(){
		$cond = "to_user_id = {$this->uid}";
		
		$join = array('multiple' , array(
							array(	'users', 
									'users.user_id 	= firebase_user_connection.from_user_id', 
									'right'),
							array(	'users_content', 
									'users.user_id 	= users_content.uc_userid', 
									'right'),
							));
							
		$chat1 = $this->DatabaseModel->select_data('c_id,uc_pic,user_id,user_uname,user_name','firebase_user_connection',$cond,'',$join,array('c_id','desc'));
		$cond = "from_user_id = {$this->uid}";
		
		$join = array('multiple' , array(
							array(	'users', 
									'users.user_id 	= firebase_user_connection.to_user_id', 
									'right'),
							array(	'users_content', 
									'users.user_id 	= users_content.uc_userid', 
									'right'),
							));
							
		$chat2 = $this->DatabaseModel->select_data('c_id,uc_pic,user_id,user_uname,user_name','firebase_user_connection',$cond,'',$join,array('c_id','desc'));
		
		$chat  = $this->audition_functions->super_unique(array_merge($chat1,$chat2) , 'user_id');
		
		echo json_encode($chat);
		
		
	}
	
	function search_user(){ 
		if(isset($_POST['search'])){
			$searchKey = addslashes(validate_input($_POST['search']));
		
			$cond = "users.user_status = 1 AND (users.user_uname LIKE '".$searchKey."%' OR  users.user_name LIKE '".$searchKey."%' )";
			$join = array('multiple' , array(
								array(	'users_content', 
										'users.user_id 	= users_content.uc_userid', 
										'right'),
								));
			$search_result = $this->DatabaseModel->select_data('users_content.uc_pic,users.user_id,users.user_uname,users.user_name','users',$cond,10,$join);
			
			echo json_encode($search_result );
		}
	}
	function add_user_connection(){
		if(isset($_POST['to_user_id'])){
			$to_user_id = trim($_POST['to_user_id']);
			$cond = "from_user_id = {$this->uid} AND to_user_id = {$to_user_id} ";
			$chat = $this->DatabaseModel->select_data('c_id','firebase_user_connection',$cond,1);
			
			if(empty($chat)){
				$common_id = ($this->uid < $to_user_id)? $this->uid.$to_user_id : $to_user_id.$this->uid;
				$data_array = array(
										'from_user_id' 	=>  $this->uid,
										'to_user_id'	=>	$to_user_id,
										'common_id'		=>	$common_id,
										'status'		=>  1,
										'created_at'	=>	date('Y-m-d H:i:s'),
									);
				if($this->DatabaseModel->access_database('firebase_user_connection','insert',$data_array)){
					echo 1;
				}
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}
	
	function sendChatNotification(){
		$resp =[];
		if((isset($_POST['to_uid']) && !empty($_POST['to_uid'])) && (isset($_POST['message']) && !empty($_POST['message']))){
			$to_user = $_POST['to_uid'];
			$token 	 = $this->audition_functions->getFirebaseToken($to_user);
			$link 	 = $this->audition_functions->getNotiLink(1,6,$to_user,true);

			if(!empty($token)){
				$message 		= 	$_POST['message'];
				$mess 			= 	$this->audition_functions->getNotiStatus(1,6);
				$fullname 		= 	$this->audition_functions->get_user_fullname($this->uid);
				$msg_array 		=  	[
						'title'	=>	$fullname .': '. $mess,
						'body'	=>	$message,
						'icon'	=>	base_url('repo/images/firebase.png'),
						'click_action'=>$link,
						'noti_type'=>'chat_msg'
					];
				$result = $this->audition_functions->sendNotification($token,$msg_array);
				
				$resp = array('status'=>1, 'message'=>$result);
			}else{
				$resp =array('status'=>0, 'message'=>'Something went wrong.');
			}
		}else{
			$resp =array('status'=>0, 'message'=>'Something went wrong.');
		}
		echo json_encode($resp);
	}
	
	public function socket_chat($other_user = null){
		if(isset($_GET['user']) && !empty($_GET['user'])){
	
			$other_user = $_GET['user'];
    	
			$uid = $this->uid;
			
			if(!empty($other_user)){
				$otherUser = $this->DatabaseModel->select_data('user_id,sigup_acc_type','users',array('user_uname'=>$other_user),1);
				if(isset($otherUser[0]['user_id'])){
					$uid	=	$otherUser[0]['user_id'];
				}
			}
		
			$data['uid'] = $uid;
			
			$data['is_session_uid'] = (is_session_uid($uid)) ? 1 : 0 ;
			
			$field = 'users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_type,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users.referral_by,users_content.is_video_processed,users_content.is_fc_member';
			
			$accessParam = array(
							'field' => $field,
							'where' => 'user_id='.$uid, 
							);
							
			$userDetail	= $this->query_builder->user_list($accessParam);
			
			if(isset($userDetail['users']) && !empty($userDetail['users'])){
					$userDetail = $userDetail['users'];
					$data['userDetail'] = $userDetail;
				
					$data['page_info'] 		= 	array('page'=>'socketchat','title'=>'Messenger');
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('common/notofication_popup');
					$this->load->view('home/chat/socket_chat',$data);
					
					$this->load->view('home/inc/footer',$data);
					
			}else{
				redirect(base_url());
			} 
			
		}else{
			redirect(base_url('home/four_zero_four'));
		}
		
	}

	public function firebase_chat($other_user = null){

		if(isset($_GET['user']) && !empty($_GET['user'])){
	
			$other_user = $_GET['user'];
    	
			$uid = $this->uid;
			
			if(!empty($other_user)){
				$otherUser = $this->DatabaseModel->select_data('user_id,sigup_acc_type','users',array('user_uname'=>$other_user),1);
				if(isset($otherUser[0]['user_id'])){
					$uid	=	$otherUser[0]['user_id'];
				}
			}
		
			$data['uid'] = $uid;
			
			$data['is_session_uid'] = (is_session_uid($uid)) ? 1 : 0 ;
			
			$field = 'users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_type,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users.referral_by,users_content.is_video_processed,users_content.is_fc_member';
			
			$accessParam = array(
							'field' => $field,
							'where' => 'user_id='.$uid, 
							);
							
			$userDetail	= $this->query_builder->user_list($accessParam);
			
			if(isset($userDetail['users']) && !empty($userDetail['users'])){
					$userDetail = $userDetail['users'];
					$data['userDetail'] = $userDetail;
				
					$data['page_info'] 		= 	array('page'=>'firebase_chat','title'=>'Messenger');
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/inc/firebase_chat_page',$data);
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
					
			}else{
				redirect(base_url());
			}
			
		}else{
			redirect(base_url());
		}
		
	}
	
}

	
?>
