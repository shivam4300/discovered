<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Streaming extends CI_Controller {
	public $uid 		= '';
	
	public $statusCode 	= '';
	public $statusType 	= '';
	public $respMessage = '';
	
	
	public function __construct(){
		parent::__construct(); 
		
		if(isset($_SESSION['user_accesslevel']) && ($_SESSION['user_accesslevel'] == 4 || !is_login())){  
			redirect(base_url());  /*IF CATEGORY EQUAL TO FAN*/
		}
		
		$this->load->library(array('manage_session','form_validation','share_url_encryption')); 
		$this->load->helper(array('url','api_validation'));
		$this->uid = is_login();
	}
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message']= $this->respMessage;
		
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	} 

	function index(){
		$data = [];
		$data['uid'] 		= $this->uid;
		$data['p_uid'] 		= $this->uid;
		
		if(isset($_SESSION['account_type'])  && isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard'   ){
			
			$account_type 			= $_SESSION['account_type'];
			$uc_type 				= $_SESSION['primary_type'];
			
			$category_id 			= $account_type;
			$othercat 				= ( ($category_id == 1) ? 149 : (  ($category_id == 2 ) ? 150 :   151 ) ) ;  
			// $othercat = ( ($category_id == 1) ? 156 : (  ($category_id == 2 ) ? 157 :   158 ) ) ;
			/* THIS ARE ALL THE OTHER CATEGOROY OPTION BY DEFAULT*/
			$uc_type 				= $uc_type.','.$othercat;
			$cond 					= "category_id IN({$uc_type}) AND status = 1";
			$data['website_mode'] 	= $this->DatabaseModel->select_data('*','website_mode',['channel_status'=>1]);
			
			$order 					= ['category_name','ASC'];
			$data['catDetail'] 		= $this->DatabaseModel->select_data('category_name,category_id','artist_category',$cond,'','',$order);
			
			$data['language_list']	= $this->DatabaseModel->select_data('*','language_list',['status'=>1],'','',['value','ASC']);
			
			$data['page_info'] 		= ['page'=>'streaming','title'=>'Streaming Info']; 
			$data['ivs_info'] 		= $this->DatabaseModel->select_data('*','users_ivs_info',['user_id' => $this->uid],1);
			
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/streaming/streaming_info',$data); 
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer');
		
		}else{
			redirect(base_url());
		}
	}
	
	function streaming_info(){
		
		$data = [];
		$data['uid'] 		= $this->uid;
		$data['p_uid'] 		= $this->uid;
		
		if(isset($_SESSION['account_type']) && isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard'  ){
			
			$account_type 	= $_SESSION['account_type'];
			$uc_type 		= $_SESSION['primary_type'];
			
			$category_id 	= $account_type;
			$othercat 		= ( ($category_id == 1) ? 149 : (  ($category_id == 2 ) ? 150 :   151 ) ) ;  
			// $othercat = ( ($category_id == 1) ? 156 : (  ($category_id == 2 ) ? 157 :   158 ) ) ;
			/* THIS ARE ALL THE OTHER CATEGOROY OPTION BY DEFAULT*/
			
			$uc_type 		= $uc_type.','.$othercat;
			
			$cond 			= "category_id IN({$uc_type}) AND status = 1";
			
			$data['website_mode'] = $this->DatabaseModel->select_data('*','website_mode',['channel_status'=>1]);
			
			$data['catDetail'] 	= $this->DatabaseModel->select_data('category_name,category_id','artist_category',$cond,'','',['category_name','ASC']);
			
			$data['language_list']= $this->DatabaseModel->select_data('*','language_list',['status'=>1],'','',['value','ASC']);
				
			$data['page_info'] = ['page'=>'streaming','title'=>'Streaming']; 
			
			$data['ivs_info'] 	= $this->DatabaseModel->select_data('*','users_ivs_info',['user_id' => $this->uid],1);
			
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/streaming/streaming',$data); 
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer');
		}else{
			redirect(base_url());
		}
	}
	
	
	
	function requestToLiveStream(){
		if($this->input->is_ajax_request()){
			$this->load->library('creator_jwt');
			$TokenResponce = $this->creator_jwt->MatchToken();
			
			if($TokenResponce['status'] == 1){
				
				$this->form_validation->set_rules('number_of_viewers', 'number of viewers', 'trim|required');
				$this->form_validation->set_rules('average_stream_duration','average stream duration', 'trim|required');
				$this->form_validation->set_rules('streams_per_month', 'stream per month', 'trim');
				$this->form_validation->set_rules('total_number_of_stream', 'total number of stream', 'trim|required');
				
				if ($this->form_validation->run() == TRUE){
					$ivs_info = $this->DatabaseModel->select_data('status','users_ivs_info',array('user_id' => $this->uid ),1);
					
					if(empty($ivs_info)){
						$this->load->helper('aws_ivs_action');
						
						$optionArr = array(
							'latencyMode' 	=> 'LOW',
							'name' 			=> 'channel-'.$this->uid,
							'type' 			=> 'STANDARD',
						);
						$r = createChannel($optionArr); 
						
						if($r['status'] == 1){
							
							$data['channel'] 	= $r['data']['channel'];
							$data['streamKey'] 	= $r['data']['streamKey'];
							
							$optionArr = array(
								'user_id' 		=> 	$this->uid,
								'status' 		=> 	2,
								'channel_arn' 	=> 	$data['channel']['arn'],
								'ivs_info'		=>	json_encode($data),
								'stream_info'	=>	json_encode($this->input->post())
							);
							
							$iv = $this->DatabaseModel->access_database('users_ivs_info','insert',$optionArr);
							if($iv){
								
								$this->respMessage = 'We have received your request for live streaming, you will get a notification and an email when it is approved.';
								$this->statusCode 	= 1;
							}else{
								$this->respMessage = 'Already requested ! Please try again. ';
							}
						}else{
							$this->respMessage = $r['message'];
						}
						
					}else{
						if($this->DatabaseModel->access_database('users_ivs_info','update',array('status'=>2),array('user_id'=>$this->uid))){
							$this->respMessage = 'We have received your request for live streaming, you will get a notification and an email when it is approved.';
							$this->statusCode 	= 1;
						}else{
							$this->respMessage = 'You have already requested to for live stream';
							$this->statusCode 	= 1;
						}
					}
				}else{
					$errors = array_values($this->form_validation->error_array());
					$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				}
			}else{
				$this->respMessage = $TokenResponce['message'];
			}
		}else{
			$this->respMessage = 'Something Went Wrong ! Please try again-3. ';
		}
		$this->show_my_response($resp=[]);
	}
	
	function roundUpToAny($scheduled_time,$step,$offset) {
		$scheduled_time = date('Y-m-d H:i',strtotime($scheduled_time));
		$sc 			= explode(' ',$scheduled_time);
		$date 			= $sc[0];
		$time 			= $sc[1];
		$time 			= explode(':',$time);
		$hours 			= (int) $time[0];
		$minute 		= (int) $time[1];
		$minute 		= (ceil($minute)%$step === 0) ? ceil($minute) : round(($minute+$step/2)/$step) * $step;
		
		if($minute == 60){
			$minute = 0;
			$hours 	= $hours+1;
			if($hours == 24){
				$hours = 0; 
			}
		}
		$scheduled_time = $date . ' ' . $hours.':'.$minute; 
		return $scheduled_time = $this->common->scheduleTimezone($scheduled_time,$clock="H",$offset);
	}
	 
	public function submitStream(){
		// $this->statusCode = 1;
		// $this->respMessage = '';
		// $resp['post_id'] = 50;
		// $resp['pub_url'] = 'dfsdfsfs';
		// return $this->show_my_response($resp);die;
		$resp	=	[];
		$uid 	= 	$this->uid;
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
			$this->form_validation->set_rules('genre','Genre', 'trim|required');
			$this->form_validation->set_rules('category', 'Category', 'trim');
			$this->form_validation->set_rules('language', 'Language', 'trim|required');
			$this->form_validation->set_rules('age_restr', 'Age', 'trim|required');
			$this->form_validation->set_rules('privacy_status', 'Privacy', 'trim|required');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[100]');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			if(isset($_POST['schedule']) && $_POST['schedule'] == 'on')
			$this->form_validation->set_rules('scheduled_time', 'Scheduled time', 'trim|required');
			
			if ($this->form_validation->run() == false){
				$errors = array_values($this->form_validation->error_array());
				
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}else{
				$this->load->helper('aws_s3_action');
			
				$ivs_info = $this->DatabaseModel->select_data('*','users_ivs_info',array('user_id' => $uid ),1);
				// print_r($ivs_info);die;
				if(isset($ivs_info[0]['is_live']) && $ivs_info[0]['is_live'] == 0){
					
					$mode 			= $this->input->post('mode');
					$genre 			= $this->input->post('genre');
					$sub_genre 		= $this->input->post('sub_genre');
					$category 		= $this->input->post('category');
					$language 		= $this->input->post('language');
					$age_restr 		= $this->input->post('age_restr');
					$title 			= $this->input->post('title');
					$description 	= $this->input->post('description');
					$privacy_status = $this->input->post('privacy_status');
					$scheduled_time = $this->input->post('scheduled_time');
					$schedule 		= $this->input->post('schedule');
					$is_chat 		= $this->input->post('is_chat');
					$offset 		= $this->input->post('offset');
					$channel_info 	= json_decode($ivs_info[0]['ivs_info'],true);
					
					if($schedule == 'on')
					$scheduled_time	= $this->roundUpToAny($scheduled_time,$step=5,$offset); 
					
					if(isset($channel_info['channel']['playbackUrl'])){
					
						$channel_array = array(	
							'video_type'		=> 	2,
							'uploaded_video'	=> 	stripslashes($channel_info['channel']['playbackUrl']),
							'user_id' 			=>	$uid,
							'created_at'		=>	date('Y-m-d H:i:s'),
							'complete_status'	=>	1,
							'active_status'		=>	1,
							'privacy_status'	=>	$privacy_status,
							'description'		=>	json_encode($description),
							'mode'				=>	$mode,
							'genre'				=>	$genre,
							'sub_genre'			=>	$sub_genre,
							'category'			=>	$category,
							'language'			=>	$language,
							'age_restr'			=>	$age_restr,
							'title'				=>	$title,
							'slug'				=>	slugify(strtolower(str_replace(" ","-",$title))),
							'is_stream_live'	=> 	1
						);
						
						$post_id = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
						
						if($post_id){
							if($schedule == 'on')
							$this->audition_functions->sendNotiOnLiveStreaming($uid,$post_id,$title,$status = 2);
							
							$post_keys = $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
							$this->DatabaseModel->access_database('channel_post_video','update',['post_key'=>$post_keys[0]],['post_id'=>$post_id]);
							
							$ivs_info = array(	
											'is_live'		=>	1,
											'live_pid'		=>	$post_id,
											'is_scheduled'	=>	($schedule == 'on')?1:0,
											'is_chat'		=>	($is_chat == 'on')?1:0,
											'schedule_time'	=>	$scheduled_time
										);
							
							$this->DatabaseModel->access_database('users_ivs_info','update',$ivs_info,['user_id'=>$uid]);
							
							$pathToImage = user_abs_path($uid);
							
							$u = $this->audition_functions->upload_file($pathToImage,'jpg|png|gif|jpeg','userfile',true);
							
							$name = '';
							if($u != 0 ){
								$name 	= $u['file_name'];
								$path 	= $pathToImage.$name ; 
								
								$r = $this->audition_functions->resizeImage('1080','608',$path,'',false,false);
								if($r != 0 ){
									$this->load->library('convert_image_webp');
									
									if(file_exists($path))
									$this->convert_image_webp->convertIntoWebp($path);
									
								    $r=$this->audition_functions->resizeImage('315','217',$pathToImage.$name,'',false,TRUE);	
									if($r != 0 ){
										
										$img = explode('.',$name);
										$path =	$pathToImage.$img[0].'_thumb.'.$img[1];	
										
										if(file_exists($path))
										$this->convert_image_webp->convertIntoWebp($path);
										
										$thumb_array = array(
											'post_id' 		=> $post_id ,
											'user_id' 		=> $uid,
											'image_name' 	=> $name,
											'active_thumb' 	=> 1,
										); 
										$this->DatabaseModel->access_database('channel_post_thumb','insert',$thumb_array);
										
										$pub_url 	= base_url('watch?p='.$post_keys[0]);
										$pub_content= $pub_url .' <br> '.$title.' <br> '.$description;
										$publish_data = array(	
												'pub_uid'		=>	$uid,
												'pub_content'	=>	$pub_content,
												'pub_status'	=>	$privacy_status,
												'pub_date'		=>	date('Y-m-d H:i:s')
												);
												
										$this->DatabaseModel->access_database('publish_data','insert',$publish_data);
										
										upload_all_images($uid);
									
										$this->statusCode  	=  1;
										$this->respMessage  =  'A new stream has created successfully.';
										$resp['post_id'] 	= $post_id;
										$resp['pub_url'] 	= $pub_url;
									}
								}
							}else{
								$thumb_array = array(
									'post_id' 		=> $post_id ,
									'user_id' 		=> $uid,
									'image_name' 	=> $name,
									'active_thumb' 	=> 1,
								); 
								$this->DatabaseModel->access_database('channel_post_thumb','insert',$thumb_array);
							}	
						$this->query_builder->changeVideoCount($uid,'increase');
						}else{
							$this->respMessage  =  'Faild to insert details.';
						}
					}else{
						$this->respMessage  =  'You don\'t have channel details.';
					}
				}else{
					$this->respMessage  =  'You are already streaming live';	
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	function GetCurrentStreamKey(){
		$resp	=	[];
		$uid 	= 	$this->uid;
		
		if($this->input->is_ajax_request()){
			
			$this->load->library('creator_jwt');
			$TokenResponce = $this->creator_jwt->MatchToken();
			
			if($TokenResponce['status'] == 1){
				$arn=$this->DatabaseModel->select_data('channel_arn,ivs_info','users_ivs_info',['user_id'=>$uid ]);	
				
				if(isset($arn[0]['channel_arn']) && !empty($arn[0]['channel_arn'])){
					
					$this->load->helper('aws_ivs_action');
					$StreamInfo = getStreamInfo($arn[0]['channel_arn']);
					
					if($StreamInfo['status'] == 1){
						$ivs_info		=	json_decode($arn[0]['ivs_info'],true);
						$resp['data'] 	= $ivs_info['streamKey'] = 	$StreamInfo['data'];
						
						if(isset($StreamInfo['data']['value']) && !empty($StreamInfo['data']['value'])){
							$update 	= [	'ivs_info'	=>	json_encode($ivs_info)];
							$is_update 	= $this->DatabaseModel->access_database('users_ivs_info','update',$update,['user_id'=>$uid ]);
							if($is_update){
								
								$this->statusCode  	=  1;
								$this->respMessage = 'New Stream key has updated';
							}else{
								$this->statusCode  	=  1;
								$this->respMessage = 'There is no change in the stream Key.';
							}
						}else{
							$this->respMessage = 'There is no key in your stream channel.';
						}
					}else{
						$this->respMessage = $StreamInfo['message'];
					}
				}else{
					$this->respMessage = 'Something went wrong !';
				}
			}else{
				$this->respMessage = $TokenResponce['message'];
			}
		}else{
			$this->respMessage = 'unauthorized access';
		}
		$this->show_my_response($resp);
	}
	
	
}
	