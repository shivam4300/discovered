<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
// ;
class Media_stream extends CI_Controller {
	
	public $responses	=	'';
	public $uid 		= 	'';
	public $statusCode 	= 	0;
	public $statusType 	= 	'';
	public $respMessage = 	'';
	
	public function __construct(){
		parent::__construct(); 
		
		$this->load->library(array('Audition_functions','query_builder','manage_session','form_validation','share_url_encryption')); 
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
			$data['medialive'] 		= $this->DatabaseModel->select_data('*','users_medialive_info',['user_id' => $this->uid],1);
			
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/streaming/medialive_streaming_info',$data); 
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer');
		
		}else{
			redirect(base_url());
		}
	}
	
	function mstream(){
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
			
			$data['medialive'] 		= $this->DatabaseModel->select_data('*','users_medialive_info',['user_id' => $this->uid],1);
			
			
			$data['page_info'] 		= ['page'=>'streaming','title'=>'Streaming Info']; 
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/streaming/youtubestream',$data); 
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer'); 
		
		}else{
			redirect(base_url());
		}
	}
	
	function createMediaInput(){
		$r = GetInputGroup();
		
		if(isset($r['data']['InputSecurityGroups'][0]['Id'])){
			$inputGroupId = $r['data']['InputSecurityGroups'][0]['Id'];
			$optionArr = array('InputSecurityGroups' => $inputGroupId , 'uid' => $this->uid);
			$r = createMediaInput($optionArr);
			return $r;
		}else{
			return array('status'=>0,'message'=>'No input security group available.');
		}
	}
	
	function createDestinationParam($r){
		$optionArr = array();
		foreach($r['IngestEndpoints'] as $k => $v){
			if($k == 0){
				$cp = createParamter(array('name'=>$v['Username'],'value'=>$v['Password']));
				$optionArr[] = isset($cp['data']['@metadata']['statusCode']) && $cp['data']['@metadata']['statusCode'] == 200 ? '/medialive/'.$v['Username'] : '';
			}
		}
		return $optionArr;
	}
	
	function createMediaChannel($data){
		$Param = $this->createDestinationParam($data['package']['HlsIngest']);
		$Settings = [];
		
		foreach($data['package']['HlsIngest']['IngestEndpoints'] as $key => $val){
			if($key == 0)
			$Settings[] = array(
							'Url' 			=> $val['Url'],
							'Username' 		=> $val['Username'],
							'PasswordParam' => $Param[$key],
						);
		}
		
		$optionArr = array(	'channel_id' 	=> 'medialive-'.$this->uid  , 
							'Settings' 		=> $Settings , 
							'input_id' 		=> $data['input']['Id'] ,
							'input_name' 	=> $data['input']['Name'],
							'uid' 			=> $this->uid
						);
		return  createMediaChannel($optionArr);
	}
	
	function requestToLiveStream(){
		
		if($this->input->is_ajax_request()){
			$this->load->library('creator_jwt');
			$this->load->helper('media_stream');
			$TokenResponce = $this->creator_jwt->MatchToken();
			
			if($TokenResponce['status'] == 1){
				
				$this->form_validation->set_rules('number_of_viewers', 'number of viewers', 'trim|required');
				$this->form_validation->set_rules('average_stream_duration','average stream duration', 'trim|required');
				$this->form_validation->set_rules('streams_per_month', 'stream per month', 'trim');
				$this->form_validation->set_rules('total_number_of_stream', 'total number of stream', 'trim|required');
				
				if ($this->form_validation->run() == TRUE){
					$media_info = $this->DatabaseModel->select_data('status','users_medialive_info',array('user_id' => $this->uid ),1);
					
					if(empty($media_info)){

						$optionArr = array(
							'user_id' 		=> 	$this->uid,
							'status' 		=> 	2,
							'stream_info'	=>	json_encode($this->input->post()),
							'is_harvested'	=> 1
						);

						$r = $this->DatabaseModel->access_database('users_medialive_info','insert',$optionArr);
					
						if($r){

							$to_email = 'niket.verma@pixelnx.com,contact@discovered.tv';
							$subject  = 'New Request For Live Streaming';
							$message  = 'A new user has requested to live stream on Discovered. <br/>Visit "Admin Dashboard > Manage Users > Media Request For Live" to approve or reject the request.'; 

							//Load email library
							$data['ticket_id']		= '';
							$data['subject']		= $subject;
							$data['message']		= $message;
							$data['department_name']= 'Live Request';
							$data['ins']			= '';
							$data['user_name']		= $this->session->userdata('user_name');
							$data['receiver_email'] = $to_email;
							$data['mail_subject']   = 'New Request For Live Streaming';

							$this->load->helper('aws_ses_action');
							send_smtp_support_mail($data);

							$this->respMessage = 'We have received your request for live streaming, you will get a notification and an email when it is approved.';
							$this->statusCode 	= 1;
						}else{
							$this->respMessage = 'Already requested ! Please try again. ';
						}
						
					}else{
						if($this->DatabaseModel->access_database('users_medialive_info','update',array('status'=>2),array('user_id'=>$this->uid))){
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

	function CreateStremingChannel(){
		$data = [];
		if($this->input->is_ajax_request()){
			$this->load->library('creator_jwt');
			$this->load->helper('media_stream');
			$TokenResponce = $this->creator_jwt->MatchToken();
			
			if($TokenResponce['status'] == 1){
				
				$media_info = $this->DatabaseModel->select_data('media_info,live_pid','users_medialive_info',array('user_id' => $this->uid ),1);
					
				if(isset($media_info[0]['media_info']) && empty($media_info[0]['media_info'])){
				
					$r = $this->createMediaInput();
					if($r['status'] == 1){
						
						$data['input']['Arn'] 			= $r['data']['Input']['Arn'];
						$data['input']['Destinations'] 	= $r['data']['Input']['Destinations'];
						$data['input']['Id'] 			= $r['data']['Input']['Id'];
						$data['input']['Name'] 			= $r['data']['Input']['Name'];
						
						$r = createMediaPackageChannel(array('uid'=>$this->uid));
						
						if($r['status'] == 1){
							$data['package']['Arn'] 		= $r['data']['Arn'];
							$data['package']['HlsIngest'] 	= $r['data']['HlsIngest'];
							$data['package']['Id'] 			= $r['data']['Id'];
						
							$r = CreateEndPoint(array('channel_id' => $data['package']['Id'] , 'uid'=>$this->uid ));	
						
							if($r['status'] == 1){
								$data['endpoint']['Arn'] 	= $r['data']['Arn'];
								$data['endpoint']['Url'] 	= $r['data']['Url'];
								$data['endpoint']['Id'] 	= $r['data']['Id'];
								
								$r = createMediaChannel(array('input_id' => $data['input']['Id'] ,'input_name' => $data['input']['Name'],'uid' => $this->uid)); 
								if($r['status']  == 1){
									$data['Channel']['Arn'] 			= $r['data']['Channel']['Arn'];
									$data['Channel']['Destinations'] 	= $r['data']['Channel']['Destinations'];
									$data['Channel']['Id'] 				= $r['data']['Channel']['Id'];
									$data['Channel']['Name'] 			= $r['data']['Channel']['Name'];
									
									$r = putPlaybackConfiguration(array('EndPoint'=> $data['endpoint']['Url'] ,'uid' => $this->uid )); 
									if($r['status']  == 1){
										
										$data['tailor']['Name'] 				= $r['data']['Name'];
										$data['tailor']['HlsConfiguration'] 	= $r['data']['HlsConfiguration']['ManifestEndpointPrefix'].'index.m3u8';;
										
										$optionArr = array(
											'user_id' 		=> 	$this->uid,
											'status' 		=> 	1,
											'media_info'	=>	json_encode($data),
											// 'stream_info'	=>	json_encode($this->input->post())
										);
										
										$r = $this->DatabaseModel->access_database('users_medialive_info','update',$optionArr,['user_id'=>$this->uid]);
									
										if($r){

											if(isset($data['endpoint']['Url'])){
												$channel_array = array(	
													'uploaded_video'	=> 	stripslashes($data['tailor']['HlsConfiguration']),
												);
												$this->DatabaseModel->access_database('channel_post_video','update',$channel_array,['user_id'=>$this->uid,'post_id'=>$media_info[0]['live_pid']]);
											}

											$this->respMessage = 'We have received your request for live streaming.';
											$this->statusCode 	= 1;
										}else{
											$this->respMessage = 'Already requested ! Please try again. ';
										}
									}else{
										$this->respMessage = $r['message'];
									}
								}else{
									$this->respMessage = $r['message'];
								}
							}else{
								$this->respMessage = $r['message'];
							}
						}else{
							$this->respMessage = $r['message'];
						}
					}else{
						$this->respMessage = $r['message'];
					}
					
				}else{
					$this->respMessage = 'Channel is created already';
				}
			}else{
				$this->respMessage = $TokenResponce['message'];
			}
		}else{
			$this->respMessage = 'Something Went Wrong ! Please try again-3. ';
		}
		$this->show_my_response($data);
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
				//$this->db->trans_start();

				$this->load->helper('aws_s3_action');
			
				$medialive = $this->DatabaseModel->select_data('*','users_medialive_info',array('user_id' => $uid ),1);
				
				if(isset($medialive[0]['is_recorded']) && $medialive[0]['is_recorded'] != 1){       //it means , recording under process 
					if(isset($medialive[0]['is_live']) && $medialive[0]['is_live'] == 0){
						
						$r = 	$this->common->CallCurl('POST',['uid' => $uid ], base_url('cron/MediaLiveSns/listMediaChannels'),[]);
						$r = json_decode($r,true);
						
						if($r['status'] == 1){
							$s = 	$this->common->CallCurl('POST',['channelId' => $r['channel_id'] ], base_url('cron/MediaLiveSns/deleteMediaChannel'),[]);
							$t = 	$this->common->CallCurl('POST',['inputId' => $r['input_id'] ], base_url('cron/MediaLiveSns/deleteMediaInput'),[]);
						}
						
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
						// $channel_info 	= json_decode($medialive[0]['media_info'],true);
						
						if($schedule == 'on')
						$scheduled_time	= $this->roundUpToAny($scheduled_time,$step=5,$offset); 
						
						// if(isset($channel_info['endpoint']['Url'])){
						
							$channel_array = array(	
								'video_type'		=> 	2,
								// 'uploaded_video'	=> 	stripslashes($channel_info['tailor']['HlsConfiguration']),
								'active_status'		=>	1,
								'user_id' 			=>	$uid,
								'created_at'		=>	date('Y-m-d H:i:s'),
								'complete_status'	=>	1,
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
								
								$media_info = array(	
												'is_live'		=>	1,
												'live_pid'		=>	$post_id,
												'is_scheduled'	=>	($schedule == 'on')?1:0,
												'is_chat'		=>	($is_chat == 'on')?1:0,
												'schedule_time'	=>	$scheduled_time,
												'FST' 			=> 	'',
												'FET' 			=> 	'',
												'is_harvested' 	=> 	0,
												'is_recorded' 	=> 	0,
											);
								
								$this->DatabaseModel->access_database('users_medialive_info','update',$media_info,['user_id'=>$uid]);
								
								$pathToImage = user_abs_path($uid);
								$name = '';

								$u = $this->audition_functions->upload_file($pathToImage,'jpg|png|jpeg','userfile',true);
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
										}
									}
								}
								
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
							
								$this->query_builder->changeVideoCount($uid,'increase');
							
							}else{
								$this->respMessage  =  'Faild to insert details.';
							}
						// }else{
						// 	$this->respMessage  =  'You don\'t have channel details.';
						// }
					}else{
						$this->respMessage  =  'You are already streaming live';	
					}
				}else{
					$this->respMessage  =  'Please wait ! Your last live stream is yet recording as a video';	
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
	
	public function StartStremingChannel(){
		$resp	=	[];
		$uid 	= 	$this->uid;
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('channel_id', 'Channel id', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_rules('tz_offset', 'Offset', 'trim|required');
			
			if ($this->form_validation->run() == false){
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}else{
				$channel_id = $this->input->post('channel_id');
				$state 		= $this->input->post('state');
				$tz_offset 	= $this->input->post('tz_offset');
				
				$this->load->helper('media_stream');
				if($state == 'start'){
					$r = StartMediaChannel($channel_id); 
					
					$this->DatabaseModel->access_database('users_medialive_info','update',['CST'=>gmdate("Y-m-d H:i:s"),'tz_offset'=>$tz_offset,'is_harvested'=>0, 'is_stream_nf_status'=> 0],['user_id'=>$uid]);
				}else{
					$r = StopMediaChannel($channel_id); 
					
					if($r['status'] == 1){
						$MDLV 	= $this->DatabaseModel->select_data('FST','users_medialive_info',['user_id'=>$uid],1);
						if(!empty($MDLV) && $MDLV[0]['FST'] != NULL){
							$this->DatabaseModel->access_database('users_medialive_info','update',['FET'=> gmdate("Y-m-d H:i:s") ],['user_id'=>$uid]);
							$this->microt();
							$resp['harvesting'] = CreateHarvesting($uid);
						}
					}
				}
				
				if($r['status'] == 1){
					$state = isset($r['data']['State'])?$r['data']['State']:'';
					$this->statusCode  	=  	1;
					$this->respMessage 	= 	$state;
				}else{
					$this->respMessage = $r['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}

	function microt(){
		$time_start = '$time_start ' . microtime(true);
		$times=0;               // This couldn't be tough
		while($times<3000000000)
		{
			
			$times++;
		}

		$time_end = '$time_end ' .microtime(true);
		return true;
	}
	
	public function getStremingChannelDetail(){
		$resp	=	[];
		$uid 	= 	$this->uid;
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('channel_id', 'Channel id', 'trim|required');
			
			if ($this->form_validation->run() == false){
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}else{
				$channel_id = $this->input->post('channel_id');
				$this->load->helper('media_stream');
				$r 	= getChannelDetail($channel_id);
					
				if($r['status'] == 1){
					$state = isset($r['data']['State'])?$r['data']['State']:'';
					
					$this->statusCode  	=  1;
					$this->respMessage 	= $state;
				}else{
					$this->respMessage = $r['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	public function GetChannelMatrix(){
		$resp	=	[];
		$uid 	= 	['user_id'=> $this->uid ];
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('channel_id', 'Channel id', 'trim|required');
			
			if ($this->form_validation->run() == false){
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}else{
				$Pipeline = 0;
				$this->load->helper('media_stream');
				$r 	= getMetricStatistics(['ChannelId'=> $this->input->post('channel_id') ,'Pipeline'=>$Pipeline,'MetricName'=>'InputVideoFrameRate']);
				// print_r($r);die;
				if($r['status'] == 1){
					
					$DP 	= $r['data']['Datapoints'];
					
					$SOFDP 	= sizeof($DP);
					
					$MDLV 	= $this->DatabaseModel->select_data('FST,FET,is_harvested','users_medialive_info',$uid,1);
					
					$message = 'STREAM-WAITING';
					if(!empty($MDLV) && $SOFDP > 0){
						$message = 'STREAM-RUNNING';
						
						usort($DP, function($a, $b) {
							return $a['Timestamp'] <=> $b['Timestamp'];
						});
						
						$Timestamp = (array) $DP[0]['Timestamp'];
						
						if(round($DP[0]['Average']) > 0 &&  $MDLV[0]['FST'] == NULL && $MDLV[0]['is_harvested'] == 0 ){
							//'FST'=> $Timestamp['date']
							$this->DatabaseModel->access_database('users_medialive_info','update',['FST'=> gmdate("Y-m-d H:i:s") , 'is_stream_nf_status'=> 0 ],$uid);
							$message = 'STREAM-START';

							$this->audition_functions->sendNotiOnLiveStreaming( $this->uid  , $this->input->post('post_id') , $this->input->post('title') ,$status = 3);
						}
						
						$DP = array_reverse($DP);
						$Timestamp = (array) $DP[0]['Timestamp'];
						
						if(round($DP[0]['Average']) == 0 ){  
							//'FET'=>  $Timestamp['date']
							if($MDLV[0]['FST'] != NULL && $MDLV[0]['FET'] == NULL && $MDLV[0]['is_harvested'] == 0 ){
								$this->DatabaseModel->access_database('users_medialive_info','update',['FET'=> gmdate("Y-m-d H:i:s") ],$uid);
								$this->microt();
								$resp['harvesting'] = CreateHarvesting($uid);
							} 
							$message = 'STREAM-END';
						}
					}
					$resp['data'] 		= $DP;
					$this->statusCode  	=  1;
					$this->respMessage 	=  $message;
				}else{
					$this->respMessage = $r['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	
	
	public function resetMediaInputKey(){
		$resp	=	[];
		$uid 	= 	$this->uid;
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('input_id', 'Input id', 'trim|required');
			
			if ($this->form_validation->run() == false){
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}else{
				$input_id = $this->input->post('input_id');
				$this->load->helper('media_stream');
				$optionArr = array('uid' =>$uid ,'InputId'=> $input_id  );
				$r = updateMediaInput($optionArr);	
				if($r['status'] == 1){
					
					$media_info 	= $this->DatabaseModel->select_data('*','users_medialive_info',['user_id' =>(int) $uid],1);
					
					if(!empty($media_info) && isset($media_info[0]['media_info'])){
					
						$media_info 	= json_decode($media_info[0]['media_info'],true);
						$Destinations 	= isset($r['data']['Input']['Destinations'])?$r['data']['Input']['Destinations']:[];
						$media_info['input']['Destinations'] = $Destinations;
						
						$rtmp_key = explode( 'discovered_live_'.$uid.'/',stripslashes($Destinations[0]['Url']) );
						
						$u = $this->DatabaseModel->access_database('users_medialive_info','update',['media_info'=>json_encode($media_info)],['user_id'=>$uid]);
						if($u){
							$resp['data'] 		= $rtmp_key[1];
							$this->statusCode  	=  1;
							$this->respMessage 	=  'Stream Key updated successfully.';
						}else{
							$this->respMessage 	= 'Something went wrong. Update operation Faild.';
						}
					}else{
						$this->respMessage = 'Media info not available.';
					}
				}else{
					$this->respMessage = $r['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	function putPlaybackConfiguration(){
		$this->load->helper('media_stream');
		$EndPoint = 'https://82934cf9c8696bd2.mediapackage.us-east-1.amazonaws.com/out/v1/3cbc0a4caaea4b5ebf2b3ff955266b6a/index.m3u8';
		$optionArr = array('uid' => '215','EndPoint'=> $EndPoint );
		$r = putPlaybackConfiguration($optionArr); 
		echo '<pre>';
		print_r($r);die;
	}
	function deleteMediaTailorConfig(){
		$this->load->helper('media_stream');
		$optionArr = array('ChannelName' => 'dsf');
		$r = deleteMediaTailorConfig($optionArr); 
		echo '<pre>';
		print_r($r);die;
	}
	function CreateHarvesting(){
		$this->load->helper('media_stream');
		$r = CreateHarvesting(215);
		echo '<pre>';
		print_r($r);die;
		// harvest();
	}

	function insertscteMarker(){ 
		if(isset($_POST['channel_id'])){
			$this->load->helper('media_stream');
			$r = UpdateScteSchedule(array('channel_id' => $_POST['channel_id'] ));
			echo $r['status'];
		}
	}
	
	
}

