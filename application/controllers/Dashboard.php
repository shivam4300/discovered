<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	private $uid;
	public $statusCode 	= 	'';
	public $respMessage = 	'';
	
	
	public function __construct(){
		
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->helper(array('aws_s3_action','button','playfab', 'iter')); 
		$this->load->library(array('image_lib','audition_functions','dashboard_function','form_validation','query_builder','share_url_encryption','creator_jwt','PlayFab')); 
		$this->load->model('UserModel');
		$this->load->model('ChannelPostVideoModel');
		$this->uid = is_login();
	}
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	public function SetCurrentToken(){
		if(isset($_POST['uc_firebase_token']) && !empty($_POST['uc_firebase_token'])){
			$userDetails 		= $this->DatabaseModel->select_data('user_firebase_token,','users',array('user_id'=>$this->uid),1); 
			$old_firebase_token =  (!empty($userDetails[0]['user_firebase_token'])) ? json_decode($userDetails[0]['user_firebase_token'],true) : [] ;
			
			if(!empty($old_firebase_token)){
				$old_firebase_token['web'] 		= $_POST['uc_firebase_token'];
				$update_arr 					= array('user_firebase_token'=>json_encode($old_firebase_token) );
			}else{
				$new_firebase_token['web']		= $_POST['uc_firebase_token'];
				$new_firebase_token['android']	= '';
				$new_firebase_token['ios']		= '';
				$update_arr						= array('user_firebase_token'=>json_encode($new_firebase_token));
			}
			$this->DatabaseModel->access_database('users','update',$update_arr,array('user_id'=>$this->uid));
		}
	}

	public function profile(){
		$this->index();
	}

	/******* Profile page STARTS ***************/
	
	public function index($other_user = null){
		if(!empty($this->uid))
		$this->load->library('manage_session');
		
		if(isset($_GET['user']) && !empty($_GET['user'])){
			if(isset($_SESSION['myfids'])){
				unset($_SESSION['myfids']); /* This is added from dashboard_function.php library*/
			}
			$other_user = $_GET['user'];
    	
			$uid 		= $this->uid;
			
			$userInfo 	= $this->dashboard_function->getUserProfileInfo($other_user);
			
			$uid 		= $userInfo['uid'];
			
			if(!empty($userInfo['userDetail'])){ 
					
					$userDetail = $userInfo['userDetail'];
					
					if( !empty($userDetail))
					{
						if( $userDetail[0]['user_dir'] == '0' ) 
						{

							mkdir ('./uploads/aud_'.$uid);
							mkdir ('./uploads/aud_'.$uid.'/images');
							mkdir ('./uploads/aud_'.$uid.'/videos');
							
							$update_arr = array('user_dir'	=>	1);
							$from_arr 	= array('user_id'	=>	$uid);
							$this->DatabaseModel->access_database('users','update',$update_arr,$from_arr);
						}
					}
					
					$data 					= $userInfo;
					
					$data['noFooter'] 		= 1;
					$data['page_info'] 		= array('page'=>'dashboard','title'=>'Dashboard');
					$data['skelton'] 		= $this->load->view('common/skeletion_loader',[],true); 
					$data['common_header']  = $this->load->view('common/dashboard_header',$data,true);
					$data['user_introduction']  = $this->load->view('common/user_profile_info',$data,true);
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/dashboard/dashboard',$data); 
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
					
			}else{
				redirect(base_url());
			}
			
		}else{
			redirect(base_url('home/four_zero_four'));
		}
		
	}
	
	public function my_playlist(){ 
		$uid 		= $this->uid;
		$other_user = isset($_GET['user'])?$_GET['user']:'';
		
		$data = [ 	
			'uid'			=> $uid,
			'letest_post_id'=> '',
			'user_id'		=> '',
			'web_mode'		=> '',
			'sub_catname'	=> '',
			'musics'		=> [],
			'movies'		=> [],
			'televisions'	=> [],
			'incomplete_video'=> [],
		];
		
		$userInfo = $this->dashboard_function->getUserProfileInfo($other_user);	
		
		if(!empty($userInfo['userDetail'])){
			
			$data 				= array_merge($data,$userInfo);
			
			$featuredVideo 		= $this->dashboard_function->getUserFeturedVideo($userInfo['uid']);
			
			$data 				= array_merge($data,$featuredVideo);
			
			$data['page_info'] = array('page'=>'my_playlist','title'=>'My Playlist');
			
			$data['common_header']  		= $this->load->view('common/dashboard_header',$data,true);
			$data['user_introduction']  	= $this->load->view('common/user_profile_info',$data,true);
			$data['user_featured_video']  	= $this->load->view('common/user_featured_video',$data,true);
			
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/channel/my_playlist',$data); 
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);
		}else{
			redirect(base_url());
		}
	}
	
	
	
	
	public function playlist($playlist_id=''){
		
		if($playlist_id){
			$data = [];
			$data['p_uid'] = $uid = $this->uid;
			$data['page_info'] 	  = ['page'=>'playlist','title'=>'Playlist'];
			
			$joinsPlaylist  = array
						(
							'multiple',
							array(
								array('channel_post_video','channel_post_video.post_id = channel_video_playlist.first_video_id','left'),
								array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id AND channel_post_thumb.active_thumb =1','left'),
							)
						);
						
			$wherePlaylist = 'channel_video_playlist.playlist_id = '.$playlist_id.' AND channel_video_playlist.user_id = '.$uid . ' '. $this->common->GobalPrivacyCond($uid,'channel_video_playlist'); 
			 
			$OrderPlaylist = array('channel_video_playlist.playlist_id','DESC');
			
			$data['playlist'] = $this->DatabaseModel->select_data("channel_video_playlist.first_video_id,channel_post_video.user_id,channel_video_playlist.playlist_id,channel_video_playlist.title,channel_video_playlist.video_ids,channel_video_playlist.created_at,channel_video_playlist.privacy_status,channel_video_playlist.playlist_thumb,channel_post_thumb.image_name",'channel_video_playlist',$wherePlaylist,1,$joinsPlaylist,$OrderPlaylist);
			
			if(!empty($data['playlist'])){
				$data['page_info'] = array('page'=>'playlist','title'=>'My playlist');
			
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/playlist',$data); 
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer');
			}else{
				$uname = isset($_SESSION['user_uname'])? $_SESSION['user_uname'] : '';
				redirect(base_url('playlist?user=').$uname);
			}
		}else{
			redirect(base_url('home/four_zero_four'));
		}
	}
	

	/******* Gamifiication ***************/
	/******* Collection page STARTS ***************/
	public function collection($other_user = null){
		if(!empty($this->uid))
		$this->load->library('manage_session');
		
		if(isset($_GET['user']) && !empty($_GET['user'])){
			if(isset($_SESSION['myfids'])){
				unset($_SESSION['myfids']); /* This is added from dashboard_function.php library*/
			}
			$other_user = $_GET['user'];
    	
			$uid 		= $this->uid;
			
			$userInfo 	= $this->dashboard_function->getUserProfileInfo($other_user);
			
			$uid 		= $userInfo['uid'];
			
			if(!empty($userInfo['userDetail'])){ 
					$data['page_info'] 		= array('page'=>'collection','title'=>'Collection');
					$data 					= array_merge($data,$userInfo);
					$data['skelton'] 		= $this->load->view('common/skeletion_loader',[],true); 
					$data['common_header']  = $this->load->view('common/dashboard_header',$data,true);
					$data['user_introduction']  = $this->load->view('common/user_profile_info',$data,true);
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/dashboard/collection',$data); 
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
					
			}else{
				redirect(base_url());
			}
			
		}else{
			redirect(base_url('home/four_zero_four'));
		}
		
	}
	
	
	
	/******* About me page STARTS ***************/
	
	public function about_me($other_user = null){
		if(!empty($this->uid))
		$this->load->library('manage_session');
		
		if(isset($_GET['user']) && !empty($_GET['user'])){
			if(isset($_SESSION['myfids'])){
				unset($_SESSION['myfids']); /* This is added from dashboard_function.php library*/
			}
			$other_user = $_GET['user'];
    	
			$uid 		= $this->uid;
			
			$userInfo 	= $this->dashboard_function->getUserProfileInfo($other_user);
			
			$uid 		= $userInfo['uid'];
			
			if(!empty($userInfo['userDetail'])){ 
					$data['page_info'] 		= array('page'=>'about','title'=>'About');
					$data 					= array_merge($data,$userInfo);
					$data['skelton'] 		= $this->load->view('common/skeletion_loader',[],true); 
					$data['common_header']  = $this->load->view('common/dashboard_header',$data,true);
					$data['user_introduction']  = $this->load->view('common/user_profile_info',$data,true);
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/dashboard/about_me',$data); 
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
					
			}else{
				redirect(base_url());
			}
			
		}else{
			redirect(base_url('home/four_zero_four'));
		}
		
	}
	
	
	
	public function GetPublishPost(){
		if($this->input->is_ajax_request()){
			$Publishcontent = $this->dashboard_function->get_publish_data();
			echo json_encode(array('status'=>1,'data'=>$this->dashboard_function->get_publish_data()));	
		}else{
			echo json_encode(array('status'=>0,'data'=>'No direct Access allowed'));
		}
		
	}
	
/******* Profile page ENDS ***************/



/******* Upload Profile picture STARTS ***************/
	function upload_profile_image() {
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		$resp = array();
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			if(isset($_FILES['userfile']['name']) && !empty($uid)){
				
				$image_name 	= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
				$imgNewname 	= $image_name.'.jpg';
				$img_path 		= user_abs_path($uid).$imgNewname;
				
				if($_FILES["userfile"]['type'] == 'image/png'){
					
					if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $img_path)){
						list($width, $height, $type, $attr) = getimagesize($img_path);
						$w = $width; $h = $height;  
						if(($width > 660 || $height > 660) ){
							$w = $h = 660; 
						}
						$this->audition_functions->resizeImage($w ,$h,$img_path,'',true,false,95);
						$this->audition_functions->resizeImage('246','246',$img_path,'',$maintain_ratio = false,$create_thumb= true);
					
						$this->DatabaseModel->access_database('users_content','update',array('uc_pic'=>$imgNewname), array('uc_userid'=>$uid));
						$data_array = array(
								'pub_uid'		=>	$uid,
								'pub_reason'	=>	1,
								'pub_media'		=>	$imgNewname.'|image',
								'pub_status'	=>	7,
								'pub_date'		=>	date('Y-m-d H:i:s')
								);
						$this->DatabaseModel->access_database('publish_data','insert',$data_array, '');
						upload_all_images($uid);
						syncPlayFabPlayerProfilePicture(first($this->UserModel->get($uid)));
						$this->statusCode 	= 1;
					}else{
						$this->respMessage = 'Something Went Wrong ! Please try again. ';
					}
				}else{
					$this->respMessage = 'Please use right image format ! Please try again. ';
				}
				
			}else{
				$this->respMessage = 'Something Went Wrong ! Please try again. ';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
/******* Upload Profile picture ENDS ***************/
/******* Remove Profile picture ENDS ***************/
	function RemoveMyProfile(){
		$this->load->library('manage_session');
		$resp = array();
		$uid = $this->uid;
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			if( !empty($uid) && $this->input->is_ajax_request()) {
				if(isset($_POST['type'])){
					if($_POST['type'] == 'picture'){
						$uc_pic = $this->DatabaseModel->select_data('uc_pic','users_content',array('uc_userid'=>$uid),1);
						if(isset($uc_pic[0]['uc_pic']) && !empty($uc_pic[0]['uc_pic'])){
							$uc_pic = $uc_pic[0]['uc_pic'];
							$cond = "pub_uid = {$uid} AND pub_media LIKE '%{$uc_pic}%'";
							$post = $this->DatabaseModel->select_data('pub_uid','publish_data',$cond,1);
							if(!isset($post[0]['pub_uid'])){
								$p = explode('.',$uc_pic );
								s3_delete_object(array('aud_'.$uid.'/images/'.$p[0].'_thumb.'.$p[1] ));
							}
							$this->DatabaseModel->access_database('users_content','update',array('uc_pic'=>''), array('uc_userid'=>$uid));
							syncPlayFabPlayerProfilePicture(first($this->UserModel->get($uid)));
						} 
						$this->statusCode = 1;
					}else if($_POST['type'] == 'video'){
						$previous_Details = $this->DatabaseModel->select_data('aws_s3_profile_video,uc_video','users_content',array('uc_userid'=>$uid),1);
						if( isset($previous_Details[0]['uc_video'])){
								if($previous_Details[0]['uc_video'] == 'direct'){
									$old_key = trim($previous_Details[0]['aws_s3_profile_video']);
									if(!empty($old_key)){
										$key = explode('.',$old_key)[0];
										s3_delete_object(array($old_key));
										s3_delete_matching_object(trim($key),TRAN_BUCKET);
									}
								}
								$this->DatabaseModel->access_database('users_content','update',array('aws_s3_profile_video'=>'','is_video_processed'=>0), array('uc_userid'=>$uid));
							$this->statusCode = 1;
						}
					}
				}else{
					$this->respMessage = 'Something Went Wrong ! Please Try Again';
				}
				
			}	
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
/******* Remove Profile picture ENDS ***************/


/******* Upload Profile video STARTS ***************/
	
	function upload_profile_video() {
		$this->load->library('manage_session');
		
		$resp = array();
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
				$uid = $this->uid;
				if ( isset($_POST['Key'])  && !empty($_POST['Key']) ){
					$previous_Details = $this->DatabaseModel->select_data('aws_s3_profile_video,uc_video','users_content',array('uc_userid'=>$uid),1);
					if( isset($previous_Details[0]['uc_video'])  &&  $previous_Details[0]['uc_video'] == 'direct'){
						$old_key = trim($previous_Details[0]['aws_s3_profile_video']);
						if($old_key !== ''){
							$key = explode('.',$old_key)[0];
							s3_delete_object(array($old_key));
							s3_delete_matching_object(trim($key),TRAN_BUCKET);
						}
					}
					$this->DatabaseModel->access_database('users_content','update',array('aws_s3_profile_video'=>$_POST['Key'],'uc_video'=>'direct','is_video_processed'=>0), array('uc_userid'=>$uid));
					$this->statusCode 	= 1;
				}else{
					$this->respMessage = 'Please upload right video format ! Please Try Again';
				}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	
/*********************** Upload Video and Image on Home ENDS ************************/

/******************************* Save / Get Published Content Data STARTS *************************/
	function front_user_publish_data() {
		$resp = array();
		
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('publish_input', 'Publish Input', 'trim');
			$this->form_validation->set_rules('publish_id', 'publish id', 'trim|required'); 
		 
			if ($this->form_validation->run() == FALSE){
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
				
			}else{
				$pub_media = '';
				$publish_input 	= $this->security->xss_clean(validate_input($_POST['publish_input']));
				$pathToData 	= user_abs_path($uid);
				$notiImage 		= ''; 
				if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
					
					$r 	= $this->audition_functions->upload_file($pathToData,'jpg|png|gif|jpeg','userfile',true);
					if($r != 0){
						$name 	= $r['file_name'];
						$w 		= $r['w'] ; $h = $r['h'] ; 
						if($r['w'] > 660 || $r['h'] > 660){
							$w = 660; 
							$h = 660;
						}
						$this->audition_functions->resizeImage($w ,$h,$pathToData.$name,'',true,false,90);
						$this->audition_functions->resizeImage('100','100',$pathToData.$name,'',false,true);
						$pub_media 	= $name.'|image';
						
						upload_all_images($uid); 
						
						
					}else{
						$this->respMessage = 'Please use right image format.';
						return $this->show_my_response($resp);die;
					}
					
				}else
				if( isset($_FILES['Thumb']['name']) && !empty($_FILES['Thumb']['name']) ){
					$ImgName = rand().'.jpg';
					if(move_uploaded_file($_FILES['Thumb']['tmp_name'],$pathToData.$ImgName)){
						
						if ($data = @getimagesize($pathToData.$ImgName)) {
							list($width, $height, $type, $attr) = $data;
							$this->audition_functions->resizeImage($width ,$height,$pathToData.$ImgName,'',true,false,75);
						}
						
						$vid 	= explode('/',$_POST['Key'])[2];
						$pub_media 	= $vid.'|video|'.$ImgName;
						
						upload_all_images($uid);
					
					}else{
						$this->respMessage = 'Something went wrong ! Please try again.';
						return $this->show_my_response($resp);die;
					}
				}
				
				if( $_POST['publish_id'] == '0' ){
					$data_array = array(
						'pub_uid'		=>	$uid,
						'pub_content'	=>	$publish_input,
						'pub_media'		=>	$pub_media,
						'pub_date'		=>	date('Y-m-d H:i:s') 
					);
					$pubId = $this->DatabaseModel->access_database('publish_data','insert',$data_array);
					
					$this->audition_functions->sendNotiOnCreateSocialPost($uid,$pubId,$publish_input,$notiImage,$status= 1);
					
				}else{
					$cond 			= array('pub_id'=>$_POST['publish_id']);
					$previous_data 	= $this->DatabaseModel->select_data('pub_media,channel_post_id','publish_data use INDEX(pub_id)', $cond ,1);
					
					$data_array = array(
						'pub_uid'		=>	$uid,
						'pub_content'	=>	$publish_input
					);
					
					if( isset($_POST['remove_media_post']) ) {
						if( !empty($previous_data) ){
							$pre_pub_media 		= $previous_data[0]['pub_media'];
							$channel_post_id 	= $previous_data[0]['channel_post_id'];
							
							if( $pre_pub_media != '' ) {
								$publish 	= 	explode('|',$pre_pub_media);
								$file 		= 	trim($publish[0]);
								
								if($publish[1] == 'image'){
									$t = explode('.',$file);
									s3_delete_object(array('aud_'.$uid.'/images/'.$file  , 'aud_'.$uid.'/images/'.$t[0].'_thumb.'.$t[1]  ));  
								}else{
									$old_key = trim('aud_'.$uid.'/videos/'.$file.'');
									$key = explode('.',$old_key);
									
									if (isset($publish[2])) {
										s3_delete_object(array(trim('aud_'.$uid.'/images/'.trim($publish[2]))));
									}
									if(empty($channel_post_id)){
										s3_delete_object(array($old_key));
										s3_delete_matching_object(trim($key[0]),TRAN_BUCKET);
									}else{
										$where 	 = array('post_id'=>$channel_post_id);
										$channel = $this->DatabaseModel->select_data('uploaded_video','channel_post_video use INDEX(post_id)',$where,1);
										if(isset($channel[0]['uploaded_video'])){
											$da =$this->DatabaseModel->access_database('channel_post_video use INDEX(post_id)','update',array('social'=>0),$where);
										}else{
											s3_delete_object(array($old_key));
											s3_delete_matching_object(trim($key[0]),TRAN_BUCKET);
										}
									}
								}
							}
						}
					}
					
					
					if( isset($_POST['remove_media_post']) && empty($pub_media)){
						$data_array['pub_media'] = '';
						$data_array['is_video_processed'] 	= 0;
					}else 
					if((!isset($_POST['remove_media_post']) || isset($_POST['remove_media_post'])) && !empty($pub_media)){
						$data_array['pub_media'] = $pub_media;
						$data_array['is_video_processed'] 	= 0;
					}else{
						$data_array['pub_media'] = $previous_data[0]['pub_media'];
					}
					$this->DatabaseModel->access_database('publish_data','update',$data_array,array('pub_id'=>$_POST['publish_id']));
					
				}
				$Publishcontent = $this->dashboard_function->get_publish_data();
				$this->statusCode 	= 1;
				$resp['data'] 		= isset($Publishcontent[0]['post'])?$Publishcontent[0]['post']	:	'' ;
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	
	
	/******************************* Save / Get Published Content Data ENDS *************************/

/******* Save profile text STARTS ***************/

	
	
	
	function save_about_me(){
		$this->load->library('manage_session');
		$uid = $this->uid;
		if(isset($_POST['uc_about'])){
			if($this->DatabaseModel->access_database('users_content','update', array('uc_about'=>$_POST['uc_about']) , array('uc_userid'=>$uid)) > 0){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}
	function SaveEditorImage(){
		$this->load->library('manage_session');
		if(isset($_GET['CKEditorFuncNum'])){
			$url 				= 'https://p.bigstockphoto.com/GeFvQkBbSLaMdpKXF1Zv_bigstock-Aerial-View-Of-Blue-Lakes-And--227291596.jpg';
			$message 			='';
			$function_number 	= $_GET['CKEditorFuncNum'];
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
		}
	}	
/******* Save profile text ENDS ***************/


	
	
	function getUserFromPost($pub_id = null){
		if(isset($pub_id) && $pub_id !== null){
			$user = $this->DatabaseModel->select_data('pub_uid','publish_data use INDEX(pub_id)',array('pub_id'=>$pub_id),1);
			return $to_user = isset($user[0]['pub_uid'])?$user[0]['pub_uid']:'';
		}
	}
	
/***************************** Like / Delete  Post STARTS ***********************************/
	 
	function action_on_post(){
		$this->load->library('manage_session');
		$resp = array();
		
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			if( isset($_POST['pid']) && !empty($this->uid)  && $this->input->is_ajax_request() ) {
				$uid = $this->uid;
				$pid = $_POST['pid'];
				if( $_POST['action_type'] == 'like' ) {
					
					$to_user = $this->getUserFromPost($pid);
					
					if(isset($_POST['dislike'])){
						
						$data_array = array(
							'like_pubid'	=>	$pid,
							'like_uid'		=>	$uid,
						);
						
						$this->DatabaseModel->access_database('likes','delete','', $data_array);
						
						$where_array = array(	'noti_type'		=>	3,  /* 3 = LIKE */
												'noti_status'	=>	1,	/* 1 = LIKE on post */
												'from_user'		=>	$uid,
												'to_user'		=>	$to_user,
												'reference_id'	=>	$pid,
												);
						$this->audition_functions->deleteNoti($where_array);
						
						$this->statusCode = 1;
						
						$resp = array('data'=>$this->dashboard_function->like($pid));
						
						
					}else{
						
						$data_array = array(
							'like_pubid'	=>	$pid,
							'like_uid'		=>	$uid,
						);
						
						$res = $this->DatabaseModel->select_data('likes_id','likes use INDEX(like_pubid,like_uid)', $data_array,1);
						
						if(empty($res)){
							
							if($uid != $to_user){
								/* START Insert notification*/
								$insert_array = array(	'noti_type'		=>	3,
														'noti_status'	=>	1,
														'from_user'		=>	$uid,
														'to_user'		=>	$to_user,
														'reference_id'	=>	$pid,
														'created_at'	=>	date('Y-m-d H:i:s')
														);
								$this->audition_functions->insertNoti($insert_array);
								/* END Insert notification*/

								/* START send firebase notification*/
								$token 	= $this->audition_functions->getFirebaseToken($to_user);
								$link 	= $this->share_url_encryption->share_single_page_link_creator(1 .'|'.$pid, 'encode');

								if(!empty($token)){
									$mess 			= 	$this->audition_functions->getNotiStatus(1,3);
									$fullname 		= 	$this->audition_functions->get_user_fullname($uid);
									$msg_array 		=  	[
											'title'	=>	$fullname .': '. $mess,
											'body'	=>	':)',
											'icon'	=>	base_url('repo/images/firebase.png'),
											'click_action'=>$link
										];
									$this->audition_functions->sendNotification($token,$msg_array);
								}
							}
							
							
							$this->DatabaseModel->access_database('likes','insert',$data_array, '');
							
							$this->statusCode = 1;
							
							$resp = array('data'=>$this->dashboard_function->like($pid));
							
						}
					}
				}else
				if( $_POST['action_type'] == 'delete' ) {
					$publish_data = $this->DatabaseModel->select_data('pub_media,pub_uid,channel_post_id','publish_data',array('pub_id'=>$pid, 'pub_uid' => $this->uid),1);
					if(isset($publish_data[0]['pub_uid'])){
						$channel_post_id 	= $publish_data[0]['channel_post_id'];
						$pub_media 			= $publish_data[0]['pub_media'];
						$pub_uid 			= $publish_data[0]['pub_uid'];
							
						$channel_post = $this->DatabaseModel->select_data('uploaded_video','channel_post_video',array('post_id'=>$channel_post_id),1);
						
						if(!empty($pub_media)){
							
							$publish = explode('|',$pub_media);
							$file = trim($publish[0]);
							if($publish[1] == 'image'){
								s3_delete_object(array(trim('aud_'.$uid.'/images/'.$file.'')));
							
								$uc_pic = $this->DatabaseModel->select_data('uc_pic','users_content',array('uc_pic'=>$file),1);
								if(!isset($uc_pic[0]['uc_pic'])){
									$files = explode('.',$file);
									s3_delete_object(array('aud_'.$uid.'/images/'.$files[0].'_thumb.'.$files[1] ));
								}
							}else{
								
								if(sizeof($publish) == 3){
									$Vidthumb = trim($publish[2]);
									s3_delete_object(array(trim('aud_'.$uid.'/images/'.$Vidthumb.'')));
								}
								
								$old_key 	= trim('aud_'.$uid.'/videos/'.$file.'');
								$key		= explode('.',$old_key)[0];
								
								if(empty($channel_post_id)){
									s3_delete_object(array($old_key));
									s3_delete_matching_object(trim($key),TRAN_BUCKET);
								}else{
									
									if(isset($channel_post[0]['uploaded_video'])){
										$this->DatabaseModel->access_database('channel_post_video','update', array('social'=>0) , array('post_id'=>$channel_post_id));
									}else{
										s3_delete_object(array($old_key));
										s3_delete_matching_object(trim($key),TRAN_BUCKET);
									}
								}
							}
						}
						
						$this->DatabaseModel->access_database('publish_data','delete','', array('pub_id'=>$pid));
						$this->DatabaseModel->access_database('likes','delete','', array('like_pubid'=>$pid));
						$this->DatabaseModel->access_database('comments','delete','', array('com_pubid'=>$pid));
						$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$pid));
						
						$this->statusCode = 1;
					}else{
						$this->respMessage ='Something Went Wrong. Please Try again.' ;
					}
					
				}
				elseif( $_POST['action_type'] == 'change_audience' ) {
					$id = $this->DatabaseModel->access_database('publish_data','update', array('pub_status'=>$_POST['aud']) , array('pub_id'=>$pid, 'pub_uid' => $this->uid));
					if($id){
						$this->statusCode = 1;
					}else{
						$this->respMessage ='Something Went Wrong. No Changes Found.' ;
					}
					
				}
				elseif( $_POST['action_type'] == 'stop_suggestions' ) {
					
					$id = $this->DatabaseModel->access_database('users_content','update', array('stop_suggestions'=>$_POST['type']) , array('uc_userid' => $this->uid));
					
					unset($_SESSION['suggested_fids']);
					unset($_SESSION['my_fids']);
					unset($_SESSION['fids']);
					$this->statusCode = 1;
					
				}
			}else {
				$this->respMessage ='Something Went Wrong.' ;
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
	
		$this->show_my_response($resp);
	}

/***************************** Like / Delete Post ENDS ***********************************/


/***************************** Comment / Reply/ Delete On Post STARTS ***********************************/
	
	
	function save_comment_db(){
		$this->load->library('manage_session');
		if( isset($_POST['pub_id']) && !empty($this->uid) && isset($_POST['com_text']) && !empty($_POST['com_text'])) {
				
				$uid 		= $this->uid;
				$pub_id 	= $_POST['pub_id'];
				// $com_text 	= validate_input($_POST['com_text']);
				$com_text 	= $this->security->xss_clean(validate_input($_POST['com_text']));
				$parent_id 	= $_POST['parent_id'];
				
				$data_array = array(
					'com_text'		=>	$com_text,
					'com_pubid'		=>	$pub_id,
					'com_parentid'	=>	$parent_id,
					'com_uid'		=>	$uid,
					'com_date'		=>	date('Y-m-d H:i:s')
				);
				$lst_com_id = $this->DatabaseModel->access_database('comments','insert', $data_array , '');
				
				$to_user = $this->getUserFromPost($pub_id);
				
				/*Parent id zero means main comment*/
				$status = ($parent_id == 0)?1:2;    
				/*1= comment on post, 2 = reply on comment*/ 
				
				if($status == 2){
					$user = $this->DatabaseModel->select_data('com_uid','comments use INDEX(com_id)',array('com_id'=>$parent_id),1);
					$to_user = $user[0]['com_uid'];
				}
				
				if($uid != $to_user){	
					
					/* START Insert notification*/
					$insert_array = array(	'noti_type'		=>	2,
											'noti_status'	=>	$status,
											'from_user'		=>	$uid,
											'to_user'		=>	$to_user,
											'reference_id'	=>	$lst_com_id,
											'created_at'	=>	date('Y-m-d H:i:s')
											);
					$this->audition_functions->insertNoti($insert_array);
					/* END Insert notification*/
					
					/* START send firebase notification*/
					$token = $this->audition_functions->getFirebaseToken($to_user);
					$link = $this->share_url_encryption->share_single_page_link_creator(1 .'|'.$pub_id, 'encode');
					
					if(!empty($token)){
						
						$mess 		= $this->audition_functions->getNotiStatus($status,2);
						$fullname 	= $this->audition_functions->get_user_fullname($uid);
						$msg_array 	=  [
							'title'	=>	$fullname .' '. $mess,
							'body'	=>	$com_text,
							'icon'	=>	base_url('repo/images/firebase.png'),
							'click_action'=>$link
						];
						$this->audition_functions->sendNotification($token,$msg_array);
					}
					/* END send firebase notification*/
				}
				
				$_POST['limit'] = 1;	/*for receive last single comment*/
				if(isset($lst_com_id) && !empty($lst_com_id)){
					if($parent_id == 0){   /*Parent id zero means MAIN comment*/
						echo $this->dashboard_function->get_commets();
					}else{
						echo $this->dashboard_function->get_commets_reply();
					}
				}
			else {
				echo '0';
			}
		}
		die();
	}
	function get_comment(){
			$this->form_validation->set_rules('pub_id', 'Publish id', 'trim|required');
			$this->form_validation->set_rules('start','Start', 'trim|required');
			if ($this->form_validation->run() == FALSE){
				echo 0;die;
			}else{
				if(isset($_POST['parent_id'])){
					echo $this->dashboard_function->get_commets_reply();
				}else{
					echo $this->dashboard_function->get_commets();
				}
				
			}
	}
	
	
	function delete_comment(){
		$this->load->library('manage_session');
		if(isset($_POST['comment_id']) && !empty($_POST['comment_id']) && !empty($this->uid)){
			$uid = $this->uid;
			$cid = $_POST['comment_id'];
			
			$comments = $this->DatabaseModel->select_data('*','comments',array('com_parentid'=>$cid));
			$this->DatabaseModel->access_database('comments','delete','', array('com_id'=>$cid));
			
			$this->DatabaseModel->access_database('notifications','delete','', array('reference_id'=>$cid,'noti_type'=>2));
			
			if(isset($comments[0])){
				$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$cid));
				foreach($comments as $comm){
					$subComments = $this->DatabaseModel->select_data('*','comments',array('com_parentid'=>$comm['com_id']));	
					foreach($subComments as $subComm){
						$this->DatabaseModel->access_database('notifications','delete','', array('reference_id'=>$subComm['com_id'],'noti_type'=>2));
					}
					$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$comm['com_id']));
				}
			}
			echo 1;
			
		}
	}

/***************************** Comment / Reply / Delete On Post ENDS ***********************************/

/***************************** Casting call  Start ***********************************/
    
	
	
	public function getPublishDataStatus(){
		
		if(isset($_POST['pubID'])){
			$publish_content = $this->DatabaseModel->access_database('publish_data','select','', array('pub_id'=>$_POST['pubID']));
			echo $publish_content[0]['pub_status'];
		}
		
	}
	function _mime_content_type($filename) {
		$result = new finfo();

		if (is_resource($result) === true) {
			return $result->file($filename, FILEINFO_MIME_TYPE);
		}

		return false;
	}
	
	function GetIdTokens(){
		$this->load->library('manage_session');
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1){
			$user_uname = isset($_SESSION['user_uname'])? $_SESSION['user_uname'] : '';
			if(!empty(trim($user_uname))){
				$this->load->helper('aws_cognito_action');
				echo json_encode(getIdToken($user_uname)) ;
			}else{
				echo json_encode(array('status'=>0 , 'message' => 'Something went wrong ! please try again .'  ));
			}
		}else{
			echo json_encode(array('status'=>0 , 'message' => $TokenResponce['message'] ));
		}
	}
	function create_channel_post(){
		$uid = $this->uid;
		$this->load->library('manage_session');
		$res = [];
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			$rules = array(
				array( 'field' => 'file_name', 'label' => 'File Name', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run()){
				
				$ext 		= pathinfo($this->input->post('file_name'), PATHINFO_EXTENSION); 
				$filename 	= pathinfo($this->input->post('file_name'), PATHINFO_FILENAME);
				
				$target = "aud_{$uid}/videos/".uniqid().'.'. $ext;
				
				$channel_array = array(	
					'uploaded_video'=> 	$target,
					'user_id' 		=>	$uid,
					'title' 		=> 	$filename,
					'created_at'	=>	date('Y-m-d H:i:s'),
				);

				if(isset($_POST['upload_source']) && !empty($_POST['upload_source'])){ // for getdiscovered uploader page
					$channel_array['upload_source_page'] =$this->input->post('upload_source');
				}
				$post_id = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
				
				$res = ['status'=>1,'post_id'=>$post_id,'target'=>$target] ;

			}else{
				$res = $this->common->form_validation_error()  ;
			}
		}else{
			$res = ['status'=>0 , 'message' => $TokenResponce['message']] ;
		}
		echo json_encode( $res );
	}
				
			
	function front_uploaded_video(){
		$uid = $this->uid;
		$this->load->library('manage_session');
		$this->load->library('creator_jwt');
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			$rules = array(
				array( 'field' => 'Key', 'label' => 'Key', 'rules' => 'trim|required'),
				array( 'field' => 'Location', 'label' => 'Location', 'rules' => 'trim|required'),
				array( 'field' => 'post_id', 'label' => 'Post', 'rules' => 'trim|required'),
				array( 'field' => 'video_duration', 'label' => 'Video Duration', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$post_id 		= $this->input->post('post_id');
				$video_duration = $this->input->post('video_duration');
				$is_safari 		= $this->input->post('is_safari');
				
				$check 			= $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
				
				$this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$check[0],'video_duration'=>$video_duration), array('post_id'=>$post_id));
				
				$this->query_builder->changeVideoCount($uid,'increase'); 
				
				$ThumbPath = user_abs_path($uid);
				$imgArr=[];
				
				if(isset($_FILES) && !empty($_FILES) && isset($_FILES[0]['tmp_name']) && !empty($_FILES[0]['tmp_name']) && isset($_FILES[0]['size']) && $_FILES[0]['size'] > 0 ){

					if($is_safari && sizeof($_FILES)==1){

						$imgArr = $this->audition_functions->createChannelThumb(AMAZON_URL.$_POST['Key'],'1080',$ThumbPath,'jpg'); //1080*608

					}else{

						for($i=0;$i< sizeof($_FILES);$i++){
							if(isset($_FILES[$i]['tmp_name'])){
								$image_name = rand().'.jpg';
								move_uploaded_file($_FILES[$i]['tmp_name'],$ThumbPath.$image_name);
								array_push($imgArr,$image_name);
							}
						}
					}

					$thumArray = [];
					$this->load->library('convert_image_webp');
					try {
						for($i=0;$i<sizeof($imgArr);$i++){
							
							if(file_exists($ThumbPath.$imgArr[$i])){
								$this->convert_image_webp->convertIntoWebp($ThumbPath.$imgArr[$i]);
								//'294','217',

								$this->audition_functions->resizeImage('315','217',
								$ThumbPath.$imgArr[$i],'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
							
							
								$img = explode('.',$imgArr[$i]);
								
								$path = $ThumbPath.$img[0].'_thumb.'.$img[1];
								
								if(file_exists($path)){
									$this->convert_image_webp->convertIntoWebp($path);
									
									$insertArr = array('post_id'=> $post_id ,'image_name' =>$imgArr[$i],'user_id'=>$uid);
									
									$insertArr['active_thumb'] = ($i==0)? 1 : 0 ;  
									
									$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',$insertArr, '');
									
									$thumArray[$i] = array('thumb_id'=>$thumb_id,'name'=>$img[0].'_thumb.jpg','active_thumb' => $insertArr['active_thumb'] );
								}
							}
						}
						
					}catch(Exception $e) {
						// echo 'Message: ' .$e->getMessage(); 
					}
					upload_all_images($uid);
					
				}else{
					$insertArr = array('post_id'=> $post_id , 'image_name' => '', 'user_id'=>$uid , 'active_thumb' => 1);
					$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',$insertArr);
					$thumArray[0] = array('thumb_id'=> $thumb_id,'name'=> '','active_thumb' => 1 );
				}
				
				
				echo json_encode(array('pubId'=>$post_id,'thumbs'=>$thumArray));
			
			}else{
				$errors = array_values($this->form_validation->error_array());
			
				echo json_encode(array('status'=>0 , 'message' =>isset($errors[0])?$errors[0]:'' ));
			}
		}else{
			echo json_encode(array('status'=>0 , 'message' => $TokenResponce['message'] ));
		}
	}
	
	
	function upload_channel_video($page=NULL,$viewload = 'false',$post_id = NULL){  /*$page = single or = bulk*/
		
		if(!is_admin()){
			$this->load->library('manage_session');
			$uid = $this->uid;
		}
		//$this->load->library('manage_session');
		//$uid = $this->uid;
		if( (isset($_SESSION['account_type']) && isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard')  || is_admin() ){
			
			$data = [];

			$join = array('multiple' , array(
				array(	'users',
						'users.user_id = channel_post_video.user_id',
						'left'),
				array(	'users_content',
						'users_content.uc_userid = users.user_id',
						'left'),
			
			));

			$data['uid'] 			= $uid;
			$data['p_uid'] 			= $uid;

			$puid = '';
			if(!empty($post_id)){
				$videoDetails 		= $this->DatabaseModel->select_data('users.user_id,users.user_level,users_content.uc_type','channel_post_video use INDEX(post_id)',array('post_id'=>$post_id),1,$join);
				$puid 				= isset($videoDetails[0]['user_id'])?$videoDetails[0]['user_id'] : '';
				
				$data['uid'] 			= $puid;
				$data['p_uid'] 			= $puid;

				$account_type   	= isset($videoDetails[0]['user_id'])?$videoDetails[0]['user_level'] : '';
				$uc_type   			= isset($videoDetails[0]['user_id'])?$videoDetails[0]['uc_type'] : '';

			}else{
				$account_type 		= $_SESSION['account_type'];
			 	$uc_type 			= $_SESSION['primary_type'];
			}
			
			if(($account_type == 4 || (!is_admin() && $puid != $uid && !empty($post_id)) )){
				redirect(base_url());
			}	

			$category_id = $account_type;
			$othercat = ( ($category_id == 1) ? 149 : (  ($category_id == 2 ) ? 150 :   151 ) ) ;  /* THIS ARE ALL THE OTHER CATEGOROY OPTION BY DEFAULT*/
			
			$uc_type = $uc_type.','.$othercat;
			
			$cond 					= "category_id IN({$uc_type}) AND status = 1";
			
			$data['catDetail'] 		= $this->DatabaseModel->select_data('category_name,category_id','artist_category',$cond,'','',array('category_name','ASC'));
			$data['website_mode'] 	= $this->DatabaseModel->access_database('website_mode','select','',array('channel_status'=>1)); 
			$data['language_list'] 	= $this->DatabaseModel->select_data('*','language_list',array('status'=>1),'','',array('value','ASC'));
			
			
			$data['page_info'] 		= array('page'=>'upload_official_video','title'=>'Upload Official Video ');
			$data['cover_video']	= array('url'=>AMAZON_URL . PROJECT. '+Video.mp4','post_id'=>'','title'=>PROJECT);
			
			if($page == 'bulk'){
				$website_mode = '';
				foreach($data['website_mode'] as $mode){
					$website_mode .= "<option value='{$mode['mode_id']}'>".ucfirst($mode['mode'])."</option>";
				}
				$data['website_mode'] = $website_mode;
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/channel/bulk_upload',$data);
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);
			}else{
				if($viewload == 'true'){
					echo $this->load->view('home/channel/upload_video',$data,true,$viewload); 
				}else{
					 
					if(!empty($post_id)){ 
						$join = array('multiple' , array(
							array(	'mode_of_genre',
									'channel_post_video.genre = mode_of_genre.genre_id',
									'left'),
						
						));
						$where 	= 'channel_post_video.post_id = '.$post_id.'';
						$single_video = $this->DatabaseModel->select_data('*','channel_post_video use INDEX(post_id)',$where,1,$join); 
						$data['single_video'] 		= 	isset($single_video[0])?$single_video[0]:[];
						$data['genre_list'] 		= $this->DatabaseModel->select_data('*',' mode_of_genre',array('mode_id'=>$data['single_video']['mode'],'level'=>1));

						$data['sub_genre_list'] 	= $this->DatabaseModel->select_data('*',' mode_of_genre',array('parent_id'=>$data['single_video']['genre']));
					}
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/channel/upload_video',$data);
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
				}
			}
		}else{
			redirect(base_url());
		} 
	}
	
	
	function AddHlSURL(){
		$this->load->library('manage_session');
		$uid = $this->uid;
		$this->form_validation->set_rules('uploaded_video', 'HLS URL', 'trim|required');
		if ($this->form_validation->run() == FALSE){
			echo json_encode(array('status'=>0,'message'=>'please enter URL.'));
		}else{
			$url = explode('.m3u8',$_POST['uploaded_video']);
			if(isset($url[1])){
				
				$pubId = $this->DatabaseModel->access_database('channel_post_video','insert',array('video_type'=>3,'uploaded_video'=>$_POST['uploaded_video'],'user_id' =>$uid,'created_at'=>date('Y-m-d H:i:s')));
				
				$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$pubId,'encode','id');
				$this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$check[0]), array('post_id'=>$pubId));
				
				$this->query_builder->changeVideoCount($uid,'increase');
				
				echo json_encode(array('status'=>1,'pubId'=>$pubId));
			}else{
				echo json_encode(array('status'=>0,'message'=>'please enter valid URL.'));
			}
		}
	}
	
	function upload_channel_thumb($type = NULL){
		if(is_admin()){
			if(!empty($_POST['post_id'])){
				$videoDetails 		= $this->DatabaseModel->select_data('user_id','channel_post_video use INDEX(post_id)',array('post_id'=>$_POST['post_id']),1);
				$uid 				= isset($videoDetails[0]['user_id'])?$videoDetails[0]['user_id'] : '';
			}
		}else{
			$this->load->library('manage_session');
			$uid = $this->uid;
		}
		//$this->load->library('manage_session');
		if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
			//$uid = $this->uid;
			
			$config['upload_path'] 	 = './uploads/aud_'.$uid.'/images/';
			$config['encrypt_name']  = true;
			$config['allowed_types'] = 'jpg|png|gif|jpeg';
			$config['max_size']      = 8192 ;
			$config['min_width']     = 640 ;
			// $config['min_height']    = 474 ;
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload('image')){
				
				$file=$this->upload->data();
				$name = $file['raw_name'];
				$img_ext = $file['file_ext'];
				$ImgNam = $name.$img_ext;
				
				$path = $config['upload_path'].$ImgNam ; 
				$resize = $this->audition_functions->resizeImage('1080','608',$path,'',$maintain_ratio = false,$create_thumb= false);
				
				if($resize != 0 ){
					
					$this->load->library('convert_image_webp');
					
					if(file_exists($path))
					$this->convert_image_webp->convertIntoWebp($path);
					
					$resize =$this->audition_functions->resizeImage('315','217',$config['upload_path'].$ImgNam,'',false,TRUE,95);	
					if($resize != 0 ){
						
						$img = explode('.',$ImgNam);
						$path =$config['upload_path'].$img[0].'_thumb.'.$img[1];	
						
						if(file_exists($path))
						$this->convert_image_webp->convertIntoWebp($path);
						
						$post_id = trim($_POST['post_id']) ; 
						$this->DatabaseModel->access_database('channel_post_thumb','update',['active_thumb' => 0], ['post_id' => $post_id ] );
						
						$tid = $this->DatabaseModel->access_database('channel_post_thumb','insert',['post_id'=>$post_id,'image_name'=>$ImgNam,'user_id'=>$uid,'active_thumb'=>1 ]);
						
						upload_all_images($uid);
						
						if($type == 'bulk'){
							$this->GetChannelThumbs();
						}else{
							echo json_encode(array('thumb_id'=>$tid,'name'=>$name.'_thumb'.$img_ext));
						}
					}else{
						echo 3;
					}
				}else{
					echo 2;
				}
			}
			else {
				echo 1;
			}
		}
		else {
			echo 0;
		}
	}
	function GetChannelThumbs(){  /*THIS FUNCTION IS ALSO USING FOR upload_channel_thumb AND FOR SHOWING THUMBS IN SINGLE PAGE*/
		if(!is_admin()){
			$this->load->library('manage_session');
		}
		//$this->load->library('manage_session');
		if(isset($_POST['post_id'])){
			$join  = array(
						'multiple',
						array(
							array('channel_post_video' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
						
						)
						
					);
			$field = 'channel_post_thumb.thumb_id,channel_post_thumb.active_thumb,channel_post_thumb.image_name,channel_post_thumb.user_id,channel_post_video.iva_id';
			$imgArr = $this->DatabaseModel->select_data($field,'channel_post_thumb',array('channel_post_thumb.post_id'=>$_POST['post_id']),'',$join);
			
			$thumArray = [];
			if(isset($imgArr[0])){
				for($i=0;$i<sizeof($imgArr);$i++){
					
					$FilterData = $this->share_url_encryption->FilterIva($imgArr[$i]['user_id'],$imgArr[$i]['iva_id'],$imgArr[$i]['image_name'],'',true);
					
					$thumArray[$i] = array('thumb_id'=>$imgArr[$i]['thumb_id'],'name'=>$FilterData['thumb'],'active_thumb'=>$imgArr[$i]['active_thumb']);
				}
				echo json_encode(array('status'=>1 ,'thumbs'=>$thumArray,'pubId'=>$_POST['post_id']));
			}else{
				echo json_encode(array('status'=>0));
			}
		}
	}
	function updateThumbStatus(){
		if(!is_admin()){
			$this->load->library('manage_session');
		}
		
		if(!empty($_POST['thumb_id'])){
			$Thumbs = $this->DatabaseModel->select_data('post_id ','channel_post_thumb',array('thumb_id'=>$_POST['thumb_id']),1);
			
			if(!empty($Thumbs)){
				$_POST['post_id'] = $post_id = $Thumbs[0]['post_id'];
				
				$this->DatabaseModel->access_database('channel_post_thumb','update',array('active_thumb' => 0), array( 'post_id'=> $post_id ) );
				
				if($this->DatabaseModel->access_database('channel_post_thumb','update',array('active_thumb' => 1), array('thumb_id'=>trim($_POST['thumb_id']))) > 0){
						
					$cond = array('channel_post_id'=>$post_id);
					$publish_data = $this->DatabaseModel->select_data('pub_media','publish_data',$cond,1);
						
					if(isset($publish_data[0]) && !empty($publish_data[0]['pub_media'])){
							
						$pub_media = explode('|',$publish_data[0]['pub_media']);
								
						if(isset($pub_media[2])){
								
							$activeThumb = $this->DatabaseModel->select_data('image_name','channel_post_thumb',array('active_thumb'=>1,'post_id'=> $post_id),1);
									
							if(isset($activeThumb[0]) && !empty($activeThumb[0]['image_name'])){
								$pub_media[2] = $activeThumb[0]['image_name'];
								$pub_media = implode('|',$pub_media);
									
								$this->DatabaseModel->access_database('publish_data','update',array('pub_media' => $pub_media), $cond);
							}
						}
					}
					$this->GetChannelThumbs();
				}
				
			}
		}
	}
	
	public function submitchannelform(){
		if(!is_admin()){
			$this->load->library('manage_session');
			//$this->form_validation->set_rules('post_uid', 'Post User Id', 'trim|required');
		}

		//$this->load->library('manage_session');
		//$uid 	= 	$this->uid;

		
		$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
		$this->form_validation->set_rules('genre','Genre', 'trim|required');
		$this->form_validation->set_rules('category', 'Category', 'trim');
		$this->form_validation->set_rules('language', 'Language', 'trim|required');
		$this->form_validation->set_rules('age_restr', 'Age', 'trim|required');
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('tag', 'Tag', 'trim');
		
		if(isset($_POST['content'])){
			unset($_POST['content']);      //removing it because it was coming from open ai popup
			unset($_POST['max_token']);
		}
		
		if ($this->form_validation->run() == FALSE){
			echo 0;
		}else{
			$uid  						= 	!empty($_POST['post_uid']) ? $_POST['post_uid'] : $this->uid;		
			$post_id 					= 	trim($_POST['post_id']);
			$playlist_ids 				= 	isset($_POST['playlist_ids'])?$_POST['playlist_ids']:[];
			
			unset($_POST['post_uid']);
			unset($_POST['post_id']);
			
			
			
			if(isset($_POST['playlist_ids']))
				unset($_POST['playlist_ids']);
			
			$_POST['complete_status'] 	= 	$_POST['active_status'] =	1;
			$_POST['slug'] 				= 	slugify(strtolower(str_replace(" ","-",$_POST['title'])));
			
			$social_cover_video 		=	0;
			if(isset($_POST['social_cover_video'])){
				$social_cover_video 	= 	$_POST['social_cover_video'];
				unset($_POST['social_cover_video']);
			}
			
			$updatFrmMyChnl 			= 	0;
			if(isset($_POST['update_form'])){
				$update_form			=	$_POST['update_form'];
				$updatFrmMyChnl 		= 	1;
				unset($_POST['update_form']);
			}
			
			/************************Start Playlist*****************************/
			$updateResponse = 0;
			$cond 		= "user_id = {$uid}";
			$playlists 	= $this->DatabaseModel->select_data('playlist_id,video_ids','channel_video_playlist',$cond);
			$Updatelist = [];
			
			foreach($playlists as $list){
				$video_ids 	= explode('|',$list['video_ids']);
				$video_items = [];
				if( in_array($list['playlist_id'] , $playlist_ids ) ){
					$video_items = array_unique(array_merge($video_ids,[$post_id]));
				}else{
					if (($key = array_search($post_id,$video_ids))){
						unset($video_ids[$key]);
						$video_items 	= array_values($video_ids);
					}else{
						$video_items = $video_ids;
					}
				}
				$Updatelist[] 	= [
					'playlist_id'	=> $list['playlist_id'],
					'video_ids'		=> implode('|',$video_items) ,
					'first_video_id'=> isset($video_items[1]) ? $video_items[1] : '' ,
				];
			}
			if(!empty($Updatelist)){
				$r = $this->db->update_batch('channel_video_playlist',$Updatelist,'playlist_id');
				if($r)
				$updateResponse = 1;
			}
			/************************End Playlist*****************************/
			$_POST['description'] = checkForeignChar($_POST['description']);
			$_POST['title'] = checkForeignChar($_POST['title']);
			// print_r($_POST);die;
			if($this->DatabaseModel->access_database('channel_post_video','update',$_POST, array('post_id'=>$post_id)) > 0){
				
				if( (isset($_POST['social'])  && $_POST['social'] == 1) || ($social_cover_video == 1) ){
						
						$video 	= 	$this->DatabaseModel->select_data('uploaded_video','channel_post_video use INDEX(post_id)',array('post_id'=>$post_id),1);
						
						$uploaded_video =	$video[0]['uploaded_video'];
						
				}
				
				if(isset($_POST['social'])  && $_POST['social'] == 1){
						$video 	= 	explode('videos/',$uploaded_video);
						
						$image 	= $this->DatabaseModel->select_data('image_name','channel_post_thumb',array('post_id'=>$post_id,'active_thumb' => 1));
						$image = (isset($image[0]) && !empty($image[0]))? $image[0]['image_name'] : '';
						
						if(isset($video[1]) && !empty($video[1])){
							$publish_data = array(	'pub_uid'		=>	$uid,
													'pub_content'	=>	$_POST['title'],
													'pub_media'		=>	$video[1].'|video|'.$image,
													'pub_status'	=>	$_POST['privacy_status'],
													'channel_post_id'=> $post_id,
													'pub_date'		=>	date('Y-m-d H:i:s'));
													
							$this->DatabaseModel->access_database('publish_data','insert',$publish_data);
						}
					
				}
				
				if($social_cover_video == 1 ){
					$previous_Details = $this->DatabaseModel->select_data('uc_video,aws_s3_profile_video','users_content',array('uc_userid'=>$uid),1);
					
					if( isset($previous_Details[0]['uc_video'])  &&  $previous_Details[0]['uc_video'] == 'direct'){
						$old_key = trim($previous_Details[0]['aws_s3_profile_video']);
						if(!empty($old_key)){
							$key = explode('.',$old_key)[0];
							s3_delete_object(array($old_key));
							s3_delete_matching_object(trim($key),TRAN_BUCKET);
							// s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
						}
					}
					
					$this->DatabaseModel->access_database('users_content','update',array('aws_s3_profile_video'=>$uploaded_video,'uc_video'=>'channel','is_video_processed'=>0), array('uc_userid'=>$uid));
				}

				$hasFeaturedVideo = $this->ChannelPostVideoModel->get(["user_id" => $uid, "featured_by_user" => 1, "active_status" => 1]);
				if (!$hasFeaturedVideo || !empty($hasFeaturedVideo)) {
					playfabUpdateWeeklyChallengeMissionFeaturedVideo($uid, $post_id);
				}
				
				//Auto create playlist
				$this->autoCreatePlaylist($post_id,$_POST['mode']);

				$this->load->library('Gamification');
				$this->load->model('UserModel');

				try {
					$this->gamification->player_published_video(
						first($this->UserModel->get($uid)),
						$post_id
					);
				}
				catch(Exception $e) {
					'Message: ' .$e->getMessage();
				}
				
				
				if($updatFrmMyChnl == 0){
					$this->audition_functions->sendNotiOnMonetizeVideo($uid,$post_id,$_POST['title']);
					echo 1;
				}else{
					$_POST['post_id']  		= $post_id;
					$_POST['update_form']  	= $update_form;
					
					return 1;
				}
			}else{
				
				if($updatFrmMyChnl == 0){
					echo $updateResponse;
				}else{
					$_POST['post_id']  		= $post_id;
					$_POST['update_form']  	= $update_form;
					return $updateResponse;
				}
			}
		}
	}
	
	public function Updatechannelform(){
		$this->load->library('manage_session');
		$_POST['update_form'] 	= 	1; 
		echo $this->submitchannelform();
	}
	
	function autoCreatePlaylist($video_id='',$mode_id=''){
		if(!empty($video_id) && !empty($mode_id)){
			$uid 	= $this->uid;
			$playlist= $this->DatabaseModel->select_data('*','channel_video_playlist',array('user_id'=>$uid,'mode'=>$mode_id),1);
			
			if(empty($playlist)){
				$this->load->library('Valuelist');
				$web_mode = $this->valuelist->mode();
				$title 	  = ucfirst($web_mode[$mode_id]).' Playlist';
				$list = ['user_id' 			=> $uid,
						 'title'   			=> $title,
						 'privacy_status'   => 7,
						 'first_video_id'   => $video_id,
						 'video_ids'        => implode('|',['',$video_id]),
						 'mode'				=> $mode_id,
						 'playlist_type' 	=> 1,   					//Auto created playlist
						 'created_at'   	=> date('Y-m-d H:i:s')
						];
				
				$playlist_id = $this->DatabaseModel->access_database('channel_video_playlist','insert',$list);
				
			}else{
				
				$video_ids = explode('|' , $playlist[0]['video_ids']);
				if(sizeof($video_ids)<(PLAYLIST_VIDEO_LIMIT+1)){   // CONSTANT DEFINED
					if(!in_array($video_id, $video_ids)){
						
						$video_items = array_unique(array_merge($video_ids, [$video_id] ));
						
						$first_vid   = array_values(array_filter($video_items));
								
						$updateList['first_video_id']  = isset($first_vid[0])? $first_vid[0] : 0;
								
						$updateList['video_ids']       = implode('|',$video_items);
						
						$this->DatabaseModel->access_database('channel_video_playlist','update',$updateList,['playlist_id'=>$playlist[0]['playlist_id']]);
					}
				}
			}
		}
	}
	
	
	public function DeleteThumb(){
		
		if(!is_admin() && isset($_POST['thumb_id'])){
			$this->load->library('manage_session');
			$uid = $this->uid;
		}else{
			$thumbDetails 		= $this->DatabaseModel->select_data('user_id','channel_post_thumb',array('thumb_id'=>$_POST['thumb_id']),1);
			$uid 				= isset($thumbDetails[0]['user_id'])?$thumbDetails[0]['user_id'] : '';
		}
		
		if(isset($_POST['thumb_id']) && !empty($uid) ){
			
			$data_array = array('user_id'=>$uid,'thumb_id'=>$_POST['thumb_id']);
			$previous 	= $this->DatabaseModel->select_data('image_name,post_id','channel_post_thumb',$data_array);
			
			$post_id 	= $previous[0]['post_id'];
			$thumbs 	= $this->DatabaseModel->aggregate_data('channel_post_thumb','thumb_id','COUNT',array('user_id'=>$uid,'post_id'=>$post_id));
		
			if($thumbs > 1){
				if( !empty($previous) ){
					if( $previous[0]['image_name'] != '' ){
						$kpath = 'aud_'.$uid.'/images/';
						$img = explode('.',$previous[0]['image_name']);
						$img = $img[0].'_thumb.'.$img[1];
						s3_delete_object(array($kpath.$previous[0]['image_name'],$kpath.$previous[0]['image_name'].'.webp',$kpath.$img,$kpath.$img.'.webp' ));
					}
					
					if($this->DatabaseModel->access_database('channel_post_thumb','delete','', $data_array) > 0){
						$this->DatabaseModel->update_data('channel_post_thumb',array('active_thumb'=>1),array('user_id'=>$uid,'post_id'=>$post_id),1);
						echo 1;
					}else{
						echo 0;
					}
					
				}else{
					echo 0;
				}
			}else{
				echo 2;
			}
		}
		
	}
	public function RotateImage(){
		$this->load->library('manage_session');
		$imgPath = isset($_POST['imgPath']) ? $_POST['imgPath'] : '';
		if(!empty($imgPath)){
			$path_arry = explode('uploads',$imgPath);
			if(isset($path_arry[1])){
				if($this->audition_functions->rotateImage('uploads'.$path_arry[1]) != 0){
					$main = str_replace("_thumb","",$path_arry[1]);
					if($this->audition_functions->rotateImage('uploads'.$main) != 0){
						echo 1;
					}else{
						echo 2;
					}
				}else{
					echo 3;
				}
			}else{
				echo 4;
			}
			
		}
	}
	
	public function DeleteChannelVideo(){
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		if(isset($_POST['post_id']) && !empty($uid) ){
			
			$post_id 		= $_POST['post_id'];
			$where 			= array('post_id'=>$post_id);
			$publish_data 	= $this->DatabaseModel->select_data('uploaded_video,social,video_type,is_stream_live,user_id','channel_post_video',$where,1);
			
			
			if(!empty($publish_data[0]['uploaded_video'])){
				// $uid = $publish_data[0]['user_id'];

				$video_type  	= $publish_data[0]['video_type'];
				$is_stream_live = $publish_data[0]['is_stream_live'];
				$old_key 		= trim($publish_data[0]['uploaded_video']);
				
				if($video_type  == 0 || $video_type  == 4 || $video_type  == 2 ||  $video_type  == 5 ){  /*****When video is uploaded as a channel video*****/
					if($publish_data[0]['social'] == 0){
						$r = s3_delete_object(array($old_key));
						
						$key = explode('.',$old_key)[0];
						$r = s3_delete_matching_object(trim($key),TRAN_BUCKET);
						
					}elseif($publish_data[0]['social'] == 1) {
						$this->DatabaseModel->access_database('publish_data','update',array('channel_post_id'=> NULL),array('channel_post_id'=>$post_id,'pub_uid'=>$uid));
					}
				}else
				if($video_type  == 2){ /* Live Video */
					$key 		= explode(AMAZON_STREAM_URL,$old_key);
					if( isset($key[1]) && !empty($key[1]) ){
						$recorded_path = explode('/media' , $key[1]);
						if(sizeof($recorded_path) == 2){
							s3_delete_matching_object($recorded_path[0],STREAM_BUCKET,'us-east-1');
						}else{
							$key = str_replace(basename($recorded_path[0]),"",$recorded_path[0]);
							s3_delete_matching_object($key,STREAM_BUCKET,'us-east-1');
						}
					}
				}
				
				$kpath = 'aud_'.$uid.'/images/';
				$previous_thumb = $this->DatabaseModel->select_data('image_name','channel_post_thumb',$where);
				
				if(!empty($previous_thumb)){
					foreach($previous_thumb as $thumb){
						$image_name = trim($thumb['image_name']);
						if(!empty($image_name)) {
							$img = explode('.',$image_name);
							$img = $img[0].'_thumb.'.$img[1];
							$r = s3_delete_object(array($kpath.$image_name,$kpath.$image_name.'.webp',$kpath.$img,$kpath.$img.'.webp' ));
						}
					}
					
					$actCond 		= array('active_thumb'=>0);
					$merge_cond 	= array_merge($where,$actCond);

					$this->DatabaseModel->access_database('channel_post_thumb','delete','', $merge_cond );
					
				}
			
				$previous_cast = $this->DatabaseModel->select_data('image_name','channel_cast_images',$where );
				
				if(!empty($previous_cast)){
					$castAry = [];
					foreach($previous_cast as $cast){
						if($cast['image_name'] != '') {
							array_push($castAry, $kpath.$cast['image_name']);
						}
					}
					if(!empty($castAry)){
						$r = s3_delete_object($castAry);
						$this->DatabaseModel->access_database('channel_cast_images','delete','', $where );
					}
				}
				
				
				$this->DatabaseModel->access_database('channel_post_video','update',array('delete_status'=>1,'is_stream_live'=>0),$where);
				
				if($is_stream_live == 1 && $video_type == 2){
					$where  = ['user_id' =>	$uid , 'live_pid' => $post_id ];
					$update = [	'is_live' => 0,'live_pid' => 0 ,'is_scheduled'=>0 ,'schedule_time'	=> 0,'is_chat'=> 0 ];
					$this->DatabaseModel->access_database('users_ivs_info','update',$update,$where);
					$update['FET'] = ''; $update['FST'] = '';
					$this->DatabaseModel->access_database('users_medialive_info','update',$update,$where);
					$this->common->CallCurl('POST',['field' => 'user_id', 'id' =>$uid  , 'deletetable' => 'no'  ], base_url('cron/MediaLiveSns/deleteRowContent/users_medialive_info'),[]);
				}
					
				$this->query_builder->changeVideoCount($uid,'decrease');
				
				$this->deleteVideoFromPlaylist($post_id, $uid);

				$this->deleteVideoFromHomesliders($post_id);
				
				echo '1';
			}else{
				echo '2';
			}
		}else{
			echo '3';
		}
	}
	
	public function DeleteBulkChannelVideo(){
		
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		if(isset($_POST['post_id'])  && !empty($_POST['post_id']) && !empty($uid) ){
			
			$post_ids 		= $_POST['post_id'];
			$cond 			= "post_id IN($post_ids) AND user_id=$uid";
			$channel_data 	= $this->DatabaseModel->select_data('post_id,uploaded_video,social,video_type,is_stream_live','channel_post_video',$cond);
		
			if(!empty($channel_data[0]['uploaded_video'])){
				$i=0;
				foreach($channel_data as $channel){
					$video_type  	= $channel['video_type'];
					$is_stream_live = $channel['is_stream_live'];
					$post_id 		= $channel['post_id']; 
					
					$where 			= array('post_id'=>$post_id,'user_id'=>$uid);
					
					$old_key 		= trim($channel['uploaded_video']);
					
					if(!empty($old_key)){
						if($video_type  == 0 || $video_type  == 4 || $video_type  == 2){  /*****When video is uploaded as a channel video*****/
					
							if($channel['social'] == 0){
								$r = s3_delete_object(array($old_key));
								
								$key = explode('.',$old_key)[0];
								$ro = s3_delete_matching_object(trim($key),TRAN_BUCKET);
							}
						}else
						if($video_type  == 2){ /* Live Video */
							$key 		= explode(AMAZON_STREAM_URL,$old_key);
							if( isset($key[1]) && !empty($key[1]) ){
								$recorded_path = explode('/media' , $key[1])[0];
								s3_delete_matching_object($recorded_path,STREAM_BUCKET,'us-east-1');
							}
						}
					}
					
					
					$kpath = 'aud_'.$uid.'/images/';
					$previous_thumb = $this->DatabaseModel->select_data('image_name','channel_post_thumb',$where);
					
					if(!empty($previous_thumb)){
						foreach($previous_thumb as $thumb){
							$image_name = trim($thumb['image_name']);
							if(!empty($image_name)) {
								$img = explode('.',$image_name);
								if(isset($img[1])){
									$img = $img[0].'_thumb.'.$img[1];
									s3_delete_object(array($kpath.$image_name,$kpath.$image_name.'.webp',$kpath.$img,$kpath.$img.'.webp' ));
								}
							}
						}
						$merge_cond 	= array_merge($where, ['active_thumb'=>0]);

						$this->DatabaseModel->access_database('channel_post_thumb','delete','', $merge_cond );
					}
					
					$previous_cast = $this->DatabaseModel->select_data('image_name','channel_cast_images',$where );
					if(!empty($previous_cast)){
						$castAry = [];
						foreach($previous_cast as $cast){
							if($cast['image_name'] != ''){
								array_push($castAry, $kpath.$cast['image_name']);
							}
						}
						if(!empty($castAry)){
							$r = s3_delete_object($castAry);
						}
						$this->DatabaseModel->access_database('channel_cast_images','delete','', $where );
					}
					
					$this->DatabaseModel->access_database('channel_post_video','update',array('delete_status'=>1,'is_stream_live'=>0),$where);
					
					if($is_stream_live == 1 && $video_type == 2){
						$where  = "user_id=	{$uid} and live_pid = {$post_id}";
						$update = [	'is_live' => 0,'live_pid' => 0 ,'is_scheduled'=>0 ,'schedule_time'	=> 0,'is_chat'=> 0 ];
						$this->DatabaseModel->access_database('users_ivs_info','update',$update,$where);
						$update['FET'] = ''; $update['FST'] = '';
						$this->DatabaseModel->access_database('users_medialive_info','update',$update,$where);
						$this->common->CallCurl('POST',['field' => 'user_id', 'id' =>$uid  , 'deletetable' => 'no'  ], base_url('cron/MediaLiveSns/deleteRowContent/users_medialive_info'),[]);
					}
						
					$this->query_builder->changeVideoCount($uid,'decrease');
					$this->deleteVideoFromPlaylist($post_id, $uid);
					$this->deleteVideoFromHomesliders($post_id);
					$i++;
				}
				if($i>0){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'You have successfully deleted the video.';
				}else{
					$this->respMessage = 'Something went wrong.';
				}
				
			}else{
				$this->respMessage = 'Video not found.';
			}
		}else{
			$this->respMessage = 'post id field required.';
		}
		$this->show_my_response();
	}

	
	
	
	public function getGenreList(){
		$this->load->library('manage_session');
		if(isset($_POST['id'])){
			$list = $this->DatabaseModel->access_database('mode_of_genre','select','',array('mode_id'=>$_POST['id'],'level'=>1));
				echo '<option value="">Select Genre</option>';
			foreach($list as $lst){
				echo '<option value="'.$lst["genre_id"].'">'.$lst["genre_name"].'</option>';
			}
		}
	}
	public function getSubGenreList(){
		$this->load->library('manage_session');
		if(isset($_POST['genre_id'])){
			$list = $this->DatabaseModel->access_database('mode_of_genre','select','',array('parent_id'=>$_POST['genre_id']));
				echo '<option value="">Select Sub Genre</option>';
			foreach($list as $lst){
				echo '<option value="'.$lst["genre_id"].'">'.$lst["genre_name"].'</option>';
			}
		}
	}
	
	public function getTaglist(){
		$this->load->library('manage_session');
		$tag_array1=[];	
		$tag_array=[];	
		
		if(!empty($_GET['query'])){
			$tag_list = $this->DatabaseModel->select_data('tag','channel_post_video','',5,'','',array('tag',$_GET['query']));
		
			foreach($tag_list  as $list){
				
				$tags = explode(',',$list['tag'] );
				foreach($tags as $tag){
					//$tag_array[]= $tag;
					if (strpos($tag, $_GET['query']) !== false && strpos($tag, $_GET['query'])==0) {
						array_unshift($tag_array1, $tag);             // add to beginning
					} else if (strpos($tag, $_GET['query']) !== false && strpos($tag, $_GET['query'])>0) {
						array_unshift($tag_array, $tag);              // add to beginning
					}else{
						$tag_array[] = $tag;                         // add to end
					}
				}
			}
			$tag_array = array_merge($tag_array1,$tag_array);
		}
	
		echo json_encode(array_values(array_unique($tag_array)));
	}
	
	
	
	
	
	public function AddCast(){
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		$this->form_validation->set_rules('cast_user_id', 'Creator', 'trim|required');
		$this->form_validation->set_rules('cast_script_name', 'Script Name', 'trim|required');
		
		if ($this->form_validation->run() == FALSE){
			echo 0;
		}else{
			
			$cast_array = array(
				'post_id'			=>	$_POST['post_id'],
				'user_id'			=>	$uid,
				'cast_script_name'	=>	$_POST['cast_script_name'],
			);

			if(is_numeric($_POST['cast_user_id'])){
				$cast_array['cast_user_id'] 	=	$_POST['cast_user_id'];
			}else{
				$cast_array['cast_real_name'] 	=	$_POST['cast_user_id'];
			}
			$uploaded = 0;
			if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
				$pathToImages = user_abs_path($uid);
			
				$uploaded = $this->audition_functions->upload_file($pathToImages,'jpg|png|gif|jpeg','userfile',true);
				
				$name 	= isset($uploaded['file_name'])?$uploaded['file_name']:'';
					
				$resize = ($uploaded != 0) ? $this->audition_functions->resizeImage('165','165',$pathToImages.$name,'',$maintain_ratio = false,$create_thumb= false) : 1 ;
					
				if($resize != 0 ){
					$cast_array['image_name']	=	$name;
				}
			}
			
			$cast_Id = $this->DatabaseModel->access_database('channel_cast_images','insert',$cast_array, '');

			if($uploaded != 0)
			upload_all_images($uid); 

			if(!empty($cast_Id)){
				$cast_array['cast_image_id'] = $cast_Id;

				$this->load->library('Gamification');
				$this->load->model('UserModel');

				try{
					$this->gamification->player_added_castcrew(
						first($this->UserModel->get($uid)),
						$_POST['post_id']
					);
					echo  json_encode(array('status'=>1,'data'=>$this->common_html->castAndCrew($_POST['post_id'] , $cast_Id),'cast_image_id'=>$cast_Id)) ;
				}catch(Exception $e){
					echo  json_encode(array('status'=>0, 'message' => $e->getMessage() )) ;
				}
			}else{
				echo  json_encode(array('status'=>0)) ;
			}
		}
	}
	public function UpdateCast(){
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		$this->form_validation->set_rules('cast_user_id', 'Creator', 'trim|required');
		$this->form_validation->set_rules('cast_script_name', 'Script Name', 'trim|required');
		
		if ($this->form_validation->run() == FALSE){
			echo 0;
		}else{
			
			$where_array = array(
				'post_id'		=>	$_POST['post_id'],
				'user_id'		=>	$uid,
				'cast_image_id'	=>	$_POST['cast_id'],
			);					
			
			$cast_array = array(
				'cast_script_name'	=>	$_POST['cast_script_name'],
			);
							
			if(is_numeric($_POST['cast_user_id'])){
				$cast_array['cast_user_id'] 	=	$_POST['cast_user_id'];
			}else{
				$cast_array['cast_real_name'] 	=	$_POST['cast_user_id'];
			}

			if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
				$previous_Details = $this->DatabaseModel->select_data('image_name','channel_cast_images',array('user_id'=>$uid,'cast_image_id'=>$_POST['cast_id'],'post_id'=>$_POST['post_id']));
				$pathToImages = user_abs_path($uid);
				if(!empty($_FILES['userfile']['name'])){
					$uploaded = $this->audition_functions->upload_file($pathToImages,'jpg|png|gif|jpeg','userfile',true,2048);
					if($uploaded != 0 ){
						$name = $uploaded['file_name'];
						$resize = $this->audition_functions->resizeImage('165','165',$pathToImages.$name,'',$maintain_ratio = false,$create_thumb= false);
						upload_all_images($uid); 
					}
					if( !empty($previous_Details[0]['image_name']) ) {
						s3_delete_object(['aud_'.$uid.'/images/'.$previous_Details[0]['image_name']]);
					}
				}else{
					$name = $previous_Details[0]['image_name'];
				}
				$cast_array['image_name']	=	$name;
			} 
			
			if($this->DatabaseModel->access_database('channel_cast_images','update',$cast_array,$where_array) >0){
				echo  json_encode(array('status'=>1,'data'=>$this->common_html->castAndCrew($_POST['post_id'],$_POST['cast_id']),'cast_image_id'=>$_POST['cast_id'])) ;
			}else{
				echo  json_encode(array('status'=>0)) ;
			}
		}
	}
	
	public function DeleteCast(){
		$this->load->library('manage_session');
		$uid = $this->uid;
		if(isset($_POST['post_id']) && isset($_POST['cast_id']) && !empty($uid) ){
			$pathToImages = user_abs_path($uid);
			$data_array = array('user_id'=>$uid,'cast_image_id'=>$_POST['cast_id'],'post_id'=>$_POST['post_id']);

			$previous_Details = $this->DatabaseModel->select_data('image_name','channel_cast_images',$data_array);
			if( !empty($previous_Details) ){
				if( $previous_Details[0]['image_name'] != '' ) {
					s3_delete_object(['aud_'.$uid.'/images/'.$previous_Details[0]['image_name']]);
				}
			}
			
			if($this->DatabaseModel->access_database('channel_cast_images','delete','', $data_array) > 0){
				echo 1;
			}else{
				echo 0;
			}
		}	
			
	}
	
	function GiveYourVote(){
		$this->load->library('manage_session');
		if(isset($_POST)){
			
			$post_id = isset($_POST['post_id'])? $_POST['post_id'] : '' ;
			
			if(!empty($post_id)){
				$post_user = array('user_id'=>$this->uid,'post_id'=>trim($post_id));	
				
				$isvoted = $this->DatabaseModel->select_data('vote_id','channel_video_vote',$post_user,1);
				
				if(empty($isvoted)){
					
					$post_user['vote_date'] = date('Y-m-d h:i:s');
					if($this->DatabaseModel->access_database('channel_video_vote','insert',$post_user,'')){
						$this->db->set('count_votes', '`count_votes`+ 1', FALSE);
						$this->db->where('post_id', $post_id);
						$this->db->update('channel_post_video');
						echo 1;
						$this->load->library('Gamification');
						$this->gamification->player_loved_video(
							first($this->UserModel->get($this->uid)),
							$post_id
						);
					}
				}else{
					echo 0;
				}	
			}
		}
	}
	
	function becomeFan(){
		$this->load->library('manage_session');
		if(isset($_POST)){
			if(!empty($_POST['user_id'])){
				$following_id = $this->uid;
				$fan_user = array('user_id'=>trim($_POST['user_id']),'following_id'=>$following_id);	
				
				$isfan_user = $this->DatabaseModel->select_data('follow_date','become_a_fan use INDEX(user_id,following_id)',$fan_user);
				
				if(empty($isfan_user)){
					$isfan_user['follow_date'] = date('Y-m-d h:i:s');
					if($this->DatabaseModel->access_database('become_a_fan','insert',$fan_user, '')){
						$to_user = $_POST['user_id'];
						$this->audition_functions->sendNotiOnBecomeAfan($following_id, $to_user,$following_id,'',$status = 1);
						echo 1;
						$this->load->library('Gamification');
						$this->gamification->player_became_fan(
							first($this->UserModel->get($to_user)),
							first($this->UserModel->get($following_id))
						);
					}
				}else{
					if($this->DatabaseModel->access_database('become_a_fan','delete','', $fan_user) > 0){
						echo 0;
					}
				}	
			}
		}
	}
	
	
	
	function getNotification(){
		$this->load->library('manage_session');
		if ($this->input->is_ajax_request() && !empty($this->uid)) {
			$data =  $this->common_html->notification_popup($this->uid);
			echo json_encode(array('data'=>$data));
		}
	}
	
	function clearNotification(){
		$this->load->library('manage_session');
		if ($this->input->is_ajax_request() && !empty($this->uid)) {
			$this->DatabaseModel->access_database('notifications','delete','', array('to_user'=>$this->uid));
			echo 1;
		}
	}
	
	function AddBulkUploadVideos(){
		$this->load->library('manage_session');
		
		
		$uid = $this->uid;
		$resp = array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){ 
			$rules = array(
				array( 'field' => 'post_id[]', 'label' => 'Post', 'rules' => 'trim|required'),
				array( 'field' => 'mode[]', 'label' => 'Mode', 'rules' => 'trim|required'),
				array( 'field' => 'genre[]', 'label' => 'Genre', 'rules' => 'trim|required'),
				array( 'field' => 'title[]', 'label' => 'Title', 'rules' => 'trim|required'),
				array( 'field' => 'description[]','label' => 'Description','rules' => 'trim'), 
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$post_id 	= $this->input->post('post_id');
				$mode 		= $this->input->post('mode');
				$genre 		= $this->input->post('genre');
				$title 		= $this->input->post('title');
				$tag 		= $this->input->post('tag');
				$desc 		= $this->input->post('description');
				
				$join  = array(
					'multiple',
					array(
						array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
					
					)
				);
				
				$cond = "channel_post_video.post_id IN(". implode(',',$post_id) .") AND active_thumb = 1";
				
				$videos = $this->DatabaseModel->select_data('channel_post_video.*,channel_post_thumb.image_name','channel_post_video use INDEX(post_id)',$cond,'',$join);
				
				$updateArray = array();
				$insertArray = array();
				
				for($x = 0; $x < sizeof($post_id); $x++){
					$Rarray = $this->audition_functions->searchForId($post_id[$x], 'post_id' , $videos);
					
					$age_restr 	= ( isset($Rarray['age_restr']) &&  trim($Rarray['age_restr']) != '') ? $Rarray['age_restr'] : 'Unrestricted' ;
					$privacy 	= ( isset($Rarray['privacy_status']) &&  trim($Rarray['privacy_status']) != 0) ? $Rarray['privacy_status'] : 7;
					$language 	= ( isset($Rarray['language']) &&  trim($Rarray['language']) != '') ? $Rarray['language'] : 'en_US';
					$social 	= 1;
					
					$updateArray[] = array(
						'post_id'		=>	$post_id[$x],
						'mode' 			=> 	$mode[$x],
						'genre' 		=> 	$genre[$x],
						'tag' 			=> 	$tag[$x],
						'title' 		=> 	checkForeignChar($title[$x]),
						'description' 	=> 	checkForeignChar($desc[$x]),
						'age_restr' 	=> 	$age_restr,
						'privacy_status'=> 	$privacy,
						'complete_status'=> 1,
						'active_status'	=>  1,
						'language'		=> 	$language,
						'social'		=> 	$social,
					);
					
					if($social == 1 && empty($Rarray['social'])){
						if(isset($Rarray['uploaded_video']) && trim($Rarray['uploaded_video']) != ""){
							$video 			= 	explode('videos/',$Rarray['uploaded_video']);
							$image 			= 	isset($Rarray['image_name']) && trim($Rarray['image_name']) ? $Rarray['image_name'] : '';
							
							$insertArray[]	= 	array(	
								'pub_uid'		=>	$uid,
								'pub_content'	=>	checkForeignChar($title[$x]),
								'pub_media'		=>	$video[1].'|video|'.$image,
								'pub_status'	=>	$privacy,
								'channel_post_id'=> $post_id[$x],
								'pub_date'		=>	date('Y-m-d H:i:s') 
							);
						}
					}
				}
				
				if(!empty($insertArray))
					$this->db->insert_batch('publish_data', $insertArray); 
				if(!empty($updateArray))
					$this->db->update_batch('channel_post_video',$updateArray, 'post_id');
				
				$this->load->library('Gamification');
				$this->load->model('UserModel');
				$user = first($this->UserModel->get($uid));
				foreach ($post_id as $id) {
					$this->gamification->player_published_video(
						$user,
						$id
					);
				}

				$resp['redurl']		= base_url('channel?user=').isset($_SESSION['user_uname'])? $_SESSION['user_uname'] : '';
				$this->statusCode 	= 1;
				$this->respMessage  =  "Information has been added successfully .";
				
			}else{
				$this->respMessage  =  $this->common->form_validation_error()['message'];
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}

	// function getFBTokenOfMyFans(){
	// 	$this->audition_functions->getFBTokenOfMyFans(215);
	// }
	
	function getMyPlaylist(){
		$this->load->library('manage_session');
		
		$uid 	= $this->uid;
		$resp 	= array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1){ 
			$rules = array(
				array( 'field' => 'post_id', 'label' => 'Post', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$post_id 	= $this->input->post('post_id');
				$playlist_ids 	= $this->DatabaseModel->select_data('*','channel_video_playlist',['user_id'=>$uid]);
				
				$playlist = [];
				if(!empty($playlist_ids)){
					foreach($playlist_ids as $list){
						$video_ids 	= explode('|',$list['video_ids']);
						$checked 	= (in_array($post_id ,$video_ids ))? 'checked' : '';
						$playlist[] = ['playlist_id'=>$list['playlist_id'],'title'=>$list['title'],'checked'=>$checked];
						$this->statusCode  =  1;
					}
				}else{
					$this->respMessage  =  "No Playlist Available.";
				}			
				$resp['playlist']  = $playlist ;
			}else{
				$this->respMessage  =  $this->common->form_validation_error()['message'];
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	function createNewPlaylist(){
		$this->load->library('manage_session');
		
		$uid 	= $this->uid;
		$resp 	= array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1){ 
			$rules = array(
				array( 'field' => 'playlistTitle', 'label' => 'Playlist Title', 'rules' => 'trim|required'),
				array( 'field' => 'PlayListStatus', 'label' => 'Playlist Stastu', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$title 	= $this->input->post('playlistTitle');
				$list = ['user_id' 			=> $uid,
							 'title'   			=> $title,
							 'privacy_status'   => $this->input->post('PlayListStatus'),
							 'created_at'   	=> date('Y-m-d H:i:s') ];
				$playlist_id = $this->DatabaseModel->access_database('channel_video_playlist','insert',$list);
				
				$playlist = [];
				if($playlist_id){
					$playlist[] = ['playlist_id'=> $playlist_id,'title'=> $title,'checked'=>''];
					$this->statusCode  =  1;
				}else{
					$this->respMessage  =  'Something went wrong ! please try again.';
				}
				$resp['playlist']  = $playlist ;
			}else{
				$this->respMessage  =  $this->common->form_validation_error()['message'];
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	public function getPublishVideos(){
		$login_userid 	= $this->uid;
		$uid 			= isset($_POST['uid'])?$_POST['uid']:$login_userid ;
		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:3;
					
		$statusCond = '';
		if(!is_session_uid($uid)){   /* FOR OTHER USER	*/
			$AmIFanOfHim = AmIFollowingHim($uid);  
			$statusCond = (isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)) ? 'publish_data.pub_status IN(6,7) AND ' : 'publish_data.pub_status IN(7) AND ' ;
		}
		
		$cond = $statusCond . "pub_uid = {$uid} AND pub_media LIKE '%video%'";
			
		$letest_video = $this->DatabaseModel->select_data('*','publish_data',$cond,array($limit,$start),'',array('pub_id','desc'));
			
		return $letest_video;
	}
	
	public function getPublishImages(){
		
		$login_userid 	= $this->uid;
		$uid 			= isset($_POST['uid'])?$_POST['uid']:$login_userid ;
		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:3;
		
		$statusCond = '';
		if(!is_session_uid($uid)){   /* FOR OTHER USER	*/
			$AmIFanOfHim = AmIFollowingHim($uid);  
			$statusCond = (isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)) ? 'publish_data.pub_status IN(6,7) AND ' : 'publish_data.pub_status IN(7) AND ' ;
		}
					
		$cond = $statusCond . "pub_uid = {$uid} AND pub_media LIKE '%image%'";
		$letest_image = $this->DatabaseModel->select_data('*','publish_data',$cond,array($limit,$start),'',array('pub_id','desc'));
		
		return $letest_image;
	}
	
	
	public function getPublishContent(){
		
		if ($this->input->is_ajax_request()) {
			$str='';
			$login_userid 	= $this->uid;
			$uid 			= isset($_POST['uid'])?$_POST['uid']:$login_userid ;
			$type 			= isset($_POST['type'])?$_POST['type']:'';
			$count 			= 0;
			if($uid !='undefined'){
				if($type=='video'){
					$letest_video = $this->getPublishVideos(); 
					$count = sizeof($letest_video);
					if(!empty($letest_video)) {
						foreach($letest_video as $solo_post) {
							if( $solo_post['pub_media'] != '' ) {
								$media_type = explode('|',$solo_post['pub_media']);
								if(isset($media_type[1])){
									if( $media_type[1] == 'video') {
										$videoData = explode('.',$media_type[0]);
										$vid_name = base_url('embed/').$solo_post['pub_id'].'?autoplay=false&loop=true'; 
										$thumb100 = (isset($media_type[2]))? AMAZON_URL .'aud_'.$uid.'/images/'.$media_type[2] : '';
										//array_push($video_arr,$vid_name.'|'.$solo_post['pub_id'].'|'.$thumb100);
										
										$str.='<li id="side_bar_post_'.$solo_post['pub_id'].'">
										   <img src="'.$thumb100.'" class="img-reponsive" alt="" onError="this.onerror=null;this.src=\''.thumb_default_image().'\'">										
										   <a href="'.$vid_name.'" class="play_video overlay">
										   <img src="'.base_url('repo/images/video_gallery_icon.png').'" ></a>
										   </li>';
									}
								}
							}
						}
					}
					
				}else{
					
					$letest_image = $this->getPublishImages(); 
					$count = sizeof($letest_image);
					if(!empty($letest_image)) {
						foreach($letest_image as $solo_post) {
							if( $solo_post['pub_media'] != '' ) {
								$media_type = explode('|',$solo_post['pub_media']);
								if(isset($media_type[1])){
									if( $media_type[1] == 'image') {
										$thumb_name = create_upic($uid, $media_type[0]);
										//array_push($img_arr,$thumb_name.'|'.$solo_post['pub_id']);
										
										$str .='<li id="side_bar_post_'.$solo_post['pub_id'].'">
											   <img src="'.$thumb_name.'" class="img-reponsive" alt=""  onError="this.onerror=null;this.src=\''.thumb_default_image().'\'">
											   <a href="'.str_replace('_thumb','',$thumb_name).'" class="sidebar_zoom overlay">
											   <img src="'.base_url('repo/images/photo_gallery_icon.png').'"></a>
											   </li>';
									}
								}
							}
						}
					} 
				}
			}
			echo json_encode(array('str'=>$str,'count'=>$count));
			
		}else{
			 exit('No direct script access allowed');
		}
	}
	
	
	public function getMyFanList(){
		$data = array();
		$this->form_validation->set_rules('uid', 'user id', 'trim|required');
		$this->form_validation->set_rules('type', 'Fan type', 'trim|required');
		if($this->form_validation->run()){
			
			$login_userid 	= 	$this->uid;
			$uid 			= 	isset($_POST['uid'])?$_POST['uid']:$login_userid ;
			$fan_type 		= 	$_POST['type'];
			$start 			=	isset($_POST['start'])?$_POST['start']:0;
			$limit 			=	isset($_POST['limit'])?$_POST['limit']:3;  
			$data['count'] = 0;
			if($fan_type =='icon_fan'){
				
				$data['str']	= $this->common_html->WhoFollowMe($uid,1,$limit,$start);
				
			}elseif($fan_type =='emerging_fan'){
				
				$data['str']	= $this->common_html->WhoFollowMe($uid,2,$limit,$start);
				
			}elseif($fan_type =='brand_fan'){
				
				$data['str']	= $this->common_html->WhoFollowMe($uid,3,$limit,$start);
				
			}elseif($fan_type =='fans'){
				
				$data['str']	= $this->common_html->WhoFollowMe($uid,4,$limit,$start);
				
			}
			/*
			elseif($fan_type =='CreatorsYouEndorsing'){
				
				$data['str']	= $this->common_html->WhomIEndorse($uid,1,$limit,$start);
				
			}elseif($fan_type =='BrandsYouEndorsing'){
				
				$data['str']	= $this->common_html->WhomIEndorse($uid,3,$limit,$start);
			
			}elseif($fan_type =='CreatorsEndorsingYou'){
				
				$data['str']	= $this->common_html->WhoEndorseMe($uid,1,$limit,$start);
				
			}elseif($fan_type =='BrandsEndorsingYou'){
				
				$data['str']	= $this->common_html->WhoEndorseMe($uid,3,$limit,$start);
			
			}
			*/
			else if($fan_type=='All'){
				$data['fanData']['icon_fan']	= $this->common_html->WhoFollowMe($uid,1,$limit,$start);
				$data['fanData']['emerging_fan']= $this->common_html->WhoFollowMe($uid,2,$limit,$start);
				$data['fanData']['brand_fan']	= $this->common_html->WhoFollowMe($uid,3,$limit,$start);
				$data['fanData']['fans']		= $this->common_html->WhoFollowMe($uid,4,$limit,$start);
				$data['fanData']['icon_fan_count']	= $this->query_builder->WhoFollowMeCount($uid,1,$limit,$start);
				$data['fanData']['emerging_fan_count']= $this->query_builder->WhoFollowMeCount($uid,2,$limit,$start);
				$data['fanData']['brand_fan_count']	= $this->query_builder->WhoFollowMeCount($uid,3,$limit,$start);
				$data['fanData']['fans_count']		= $this->query_builder->WhoFollowMeCount($uid,4,$limit,$start);

				
				// $data['fanData']['CreatorsYouEndorsing']=$this->common_html->WhomIEndorse($uid,1,$limit,$start);
				// $data['fanData']['BrandsYouEndorsing'] = $this->common_html->WhomIEndorse($uid,3,$limit,$start);
				// $data['fanData']['CreatorsEndorsingYou']=$this->common_html->WhoEndorseMe($uid,1,$limit,$start);
				// $data['fanData']['BrandsEndorsingYou']	=$this->common_html->WhoEndorseMe($uid,3,$limit,$start);

				// $data['fanData']['CreatorsYouEndorsingCount']=$this->query_builder->WhomIEndorseCount($uid,1,$limit,$start);
				// $data['fanData']['BrandsYouEndorsingCount'] = $this->query_builder->WhomIEndorseCount($uid,3,$limit,$start);
				// $data['fanData']['CreatorsEndorsingYouCount']=$this->query_builder->WhoEndorseMeCount($uid,1,$limit,$start);
				// $data['fanData']['BrandsEndorsingYouCount']	=$this->query_builder->WhoEndorseMeCount($uid,3,$limit,$start);
			}
			
			if(!empty($data['str'])){
				$data['count'] = $start+$limit;
			}
			echo json_encode($data);
		}
		else {
			exit('No direct script access allowed');
		}		
		
	}
	
	public function videoAddToPlaylist(){
		$this->load->library('manage_session');
		
		$this->form_validation->set_rules('post_id', 'post id', 'trim|required');
		if ($this->form_validation->run() == false){
			$this->respMessage  =  $this->common->form_validation_error()['message'];
		}else{
			$post_id 			= 	trim($_POST['post_id']);

			$post_video 		= 	$this->DatabaseModel->select_data('complete_status','channel_post_video use INDEX(post_id)',['post_id' => $post_id ]);

			if(isset($post_video[0]['complete_status']) && $post_video[0]['complete_status'] == 1 ){
				$playlist_ids 		= 	isset($_POST['playlist_ids'])?explode(',' , $_POST['playlist_ids']) :[];
				$r 					= 	$this->commonFunAddToPlaylist($post_id, $playlist_ids);
				if($r){
					$this->statusCode  	= 1;
					$this->statusType   = 'Success';
					$this->respMessage  = 'Playlist updated successfully.';
				}else{
					$this->respMessage  = 'No playlist selected.';	
				}
			}else{
				$this->respMessage  = 'Incomplete video can\'t include in playlist.';	
			}
			
		}
		$this->show_my_response();
	}	
	
	
	function commonFunAddToPlaylist($post_id=NULL , $playlist_ids=NULL){
		
		$updateResponse = 0;
		if(!empty($post_id)){
			$uid 			= 	$this->uid;
			$cond 		= "user_id = {$uid}";
			$playlists 	= $this->DatabaseModel->select_data('playlist_id,video_ids','channel_video_playlist',$cond);
			$Updatelist = [];
			if(!empty($playlists)) {
				foreach($playlists as $list){
					$video_ids 	= explode('|',$list['video_ids']);
					$video_items = [];
					if( in_array($list['playlist_id'] , $playlist_ids ) ){
						$video_items = array_unique(array_merge($video_ids,[$post_id]));
					}else{
						if (($key = array_search($post_id,$video_ids))){
							unset($video_ids[$key]);
							$video_items = array_values($video_ids);
						}else{
							$video_items = $video_ids;
						}
					}
					$Updatelist[] 	= [
						'playlist_id'	=> $list['playlist_id'],
						'video_ids'		=> implode('|',$video_items) ,
						'first_video_id'=> isset($video_items[1]) ? $video_items[1] : '' ,
					];
				}
				if(!empty($Updatelist)){
					$r = $this->db->update_batch('channel_video_playlist',$Updatelist,'playlist_id');
					if($r)
					$updateResponse = 1;
				}
			}	
		}
		return $updateResponse;
	}

	public function deleteVideoFromHomesliders($video_id=''){
		if(!empty($video_id)){
			$sliders= $this->DatabaseModel->select_data('id,data','homepage_sliders');
			if(!empty($sliders)){
				$updateArray = [];
				foreach($sliders as $s){
					$video_ids =  $s['data'];
					$id = $s['id'];
					if(!empty($video_ids)){
						$vids =  explode(',' , $video_ids);
						$key = array_search($video_id, $vids);
						if($key !==FALSE){
							
							unset($vids[$key]);
							
							$v = array_values($vids);

							$updateArray[] = array(
													'id'	=>$id,
													'data'	=>	implode(',',$v)
												);	
						}
					}
				}
				if(!empty($updateArray)){
					$this->db->update_batch('homepage_sliders',$updateArray, 'id');
					//print_r($updateArray);die;
				}
			}
		}
	}
	
	public function deleteVideoFromPlaylist($video_id='', $user_id=''){
		if(!empty($video_id) && !empty($user_id)){
			
			$playlist= $this->DatabaseModel->select_data('*','channel_video_playlist',array('user_id'=>$user_id));
			if(!empty($playlist)){
				$updateArray = [];
				foreach($playlist as $p){
				
					$video_ids =  $p['video_ids'];
					if(!empty($video_ids)){
						$vids =  explode('|' , $video_ids);
						$key  =  array_search($video_id, $vids);
						if($key !==FALSE){

							//$updateList['user_id'] 	   	   = $user_id;
							$updateList['playlist_id'] 	   = $p['playlist_id'];
							
							unset($vids[$key]);
							
							$v = array_values($vids);
							
							$first_vid = array_values(array_filter($v));
							
							$updateList['first_video_id']  = isset($first_vid[0])? $first_vid[0] : 0;
							
							$updateList['video_ids'] 	   = implode('|',$v);
							$updateArray[] = $updateList;
						}
					}
				}

				if(!empty($updateArray)){
					$this->db->update_batch('channel_video_playlist',$updateArray, 'playlist_id');
					//print_r($updateArray);die;
				}
			}
		}
	}

	public function deleteVideoFromPlaylist_old($video_id='', $user_id=''){
		if(!empty($video_id) && !empty($user_id)){
			
			$playlist= $this->DatabaseModel->select_data('*','channel_video_playlist',array('user_id'=>$user_id));
			if(!empty($playlist)){
				
				foreach($playlist as $p){
				
					$video_ids =  $p['video_ids'];
					if(!empty($video_ids)){
						$vids =  explode('|' , $video_ids);
						$key  =  array_search($video_id, $vids);
						if($key !==FALSE){
							
							unset($vids[$key]);
							
							$v = array_values($vids);
							
							$first_vid = array_values(array_filter($v));
							
							$updateList['first_video_id']  = isset($first_vid[0])? $first_vid[0] : 0;
							
							$updateList['video_ids'] 	   = implode('|',$v);
							
							$this->DatabaseModel->access_database('channel_video_playlist','update',$updateList,array('user_id'=>$user_id, 'playlist_id'=>$p['playlist_id']));
						}
					}
				}
			}
		}
	}


	function submit_howtodiscoveredus(){
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			$rules = array(
				array( 'field' => 'mySource', 'label' => 'Source', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run()){
				$mySource 		= $this->input->post('mySource');
				$myCustSource 	= $this->input->post('myCustSource');
				
				if($mySource == 'other' && trim($myCustSource) == ''){
					$resp = ['status'=>0 , 'message' => 'Please provide the detail of any other source'];
				}else{
					$this->DatabaseModel->access_database('users','update',array('HDYDU' => json_encode(array('mySource' => $mySource , 'myCustSource' => $myCustSource)) ),array('user_id'=>$this->uid));
					$resp = ['status'=>1,'message'=> 'Thanks for your valueable feeback'];
				}
			}else{
				$resp = $this->common->form_validation_error();
			}
		}else{
			$resp = ['status'=>0 , 'message' => $TokenResponce['message']];
		}
		echo json_encode($resp);
	}


	

	

	public function directory(){
		$data['page_info'] = array('page'=>'mutual_friends','title'=>'User Directory');

		$data['cate_info'] = $this->DatabaseModel->select_data('category_id,category_name','artist_category',['parent_id' => NULL,'category_id !='  => 130]);
		$data['country_info'] = $this->DatabaseModel->select_data('country_id,country_name','country',['status' =>1]);
		
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/dashboard/mutual_friends',$data);
        $this->load->view('home/inc/footer',$data);
	}

	public function Get_user_desc()
	{	
		$resp = array();
		$user_n = $this->input->post('user_uname');
		if (isset($user_n)) {
			$field = 'users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_type,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users.referral_by,users_content.is_video_processed,users_content.is_fc_member';
				
			$accessParam = array(
							'field' => $field,
							'where' => 'user_uname='.$user_n,
							);
							
			$userDetail	= $this->query_builder->user_list($accessParam);
			
			$data['sub_catname'] = ''; 
			if(!empty($userDetail['users'][0]['uc_type']))
			{
				$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category','category_id IN ('.$userDetail['users'][0]['uc_type'].')');
				
				$size = (sizeof($sub_cat) <= 5)?sizeof($sub_cat):5;
				
				for($i=0;$i < $size; $i++ ){
					$data['sub_catname'] .=  $sub_cat[$i]['category_name'].',';
				}
				
				$data['sub_catname'] = rtrim($data['sub_catname'],", ");
				
				
			}
			$userDetail['users'][0]['uc_interest'] = $data['sub_catname'];

			$resp['status'] = "success";
			$resp['info'] = $userDetail['users'][0];
		}else{
			$resp['status'] = "success";
			$resp['info'] = "Details not available";
		}
		echo json_encode($resp);
	}

	function deleteOrDeactivateMyAccount(){
		
		$this->load->library('manage_session');
		$resp = array();
		
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			$rules = array(
				array( 'field' => 'is_deleted_status', 'label' => 'Delete Button', 'rules' => 'trim|required'), //2 = Future Delete Date, 3 = Temporary Deactivate	
				array( 'field' => 'delete_or_reactivate_date', 'label' => 'Date', 'rules' => 'trim|required'), 
				array( 'field' => 'reason', 'label' => 'Reason', 'rules' => 'trim'), 
			);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run()){
				
				$delete_or_reactivate_date = date('Y-m-d',strtotime($this->input->post('delete_or_reactivate_date')));
				
				$delete_or_reactivate_date = $this->common->manageTimezone($delete_or_reactivate_date,$clock="H", $this->input->post('offset'));

				$is_deleted_status = $this->input->post('is_deleted_status');
				$reason_of_delete = $this->input->post('reason');
				

				$should_it_delete_now = 'no';
				if($is_deleted_status == 2){ //Future Delete Date
					if(date('Y-m-d') == $delete_or_reactivate_date){
						$should_it_delete_now = 'yes';
						$this->respMessage = 'Your account has been permanently deleted from Discovered successfully.';
					}else{
						$this->respMessage = 'The request to delete your account permanently has been successfully received.';
					}
				}else 
				if( $is_deleted_status == 3){ // Temporary Deactivate
					$should_it_delete_now = 'yes';
					$this->respMessage = 'The request to temporarily deactivate your account has been successfully received.';
				} 
				
				$update_arr = ['date_of_delete_or_reactivate' => $delete_or_reactivate_date , 'is_deleted' => $is_deleted_status ,'reason_of_delete' => $reason_of_delete ];
				
				if($should_it_delete_now == 'yes'){
					$update_arr['is_deleted'] 	= 1;
					$update_arr['user_status'] 	= 3;
				}

				$id = $this->DatabaseModel->access_database('users','update',$update_arr,array('user_id'=>$this->uid));
				
				if($id){
					$this->statusCode  	= 1;
					$this->statusType   = 'Success';
				}else{
					$this->respMessage = 'Something went wrong. Please Try again.';
				}
				
			}else{
				$this->respMessage = $this->common->form_validation_error()['message'];
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}


	// public function usersWhoFollowedMe(){
	// 	if(isset($_POST['search']) && !empty($_POST['search'])){
	// 		$search = $_POST['search'];

	// 		$cond  = "become_a_fan.user_id = $this->uid AND is_deleted = 0 AND user_status = 1 AND user_role = 'member' AND ";
	// 		$cond .= "user_name LIKE '%".$search."%'";  //OR user_uname LIKE '%".$search."%'

	// 		$field	= array('become_a_fan.following_id As id','users.user_name As name');
	// 		$data['list'] = [['id' => $this->uid,'name' => $_SESSION['user_name']]];
	// 		$list = $this->DatabaseModel->select_data($field,' become_a_fan',$cond,'',array('users','users.user_id = become_a_fan.following_id'),['become_a_fan.fan_id','DESC']);
			
	// 		$data['list'] = array_merge($data['list'] , $list);
	// 		echo json_encode(array('status'=>1,'data'=>$data));
	// 	}
	// } 

	public function usersWhoFollowedMe(){
		if(isset($_POST['search']) && !empty($_POST['search'])){
			$search = $_POST['search'];
			$field	=	array('users.user_id As id','users.user_name As name');
		
			$join = array('multiple' , array(
						array(	'users_content', 
								'users.user_id 	= users_content.uc_userid', 
								'left')
						)
					);
			$cond  = "is_deleted = 0 AND user_status = 1 AND user_role = 'member' AND ";
			$cond .= "user_name LIKE '%".$search."%' OR user_uname LIKE '%".$search."%'";

			$data['list']= $this->DatabaseModel->select_data($field,'users',$cond,'',$join,array('user_name','ASC'));
			echo json_encode(array('status'=>1,'data'=>$data));	 	
		}
	} 
	

	public function getMyPlaylistVideo(){
		$resp=[];
		$this->form_validation->set_rules('playlist_id', 'playlist id', 'trim|required');
		if ($this->form_validation->run() == false){
			
			$this->respMessage  =  $this->single_error_msg();
			
		}else{
			$uid = $this->uid;
			$playlist_id = $_POST['playlist_id'];
			$playlist= $this->DatabaseModel->select_data('video_ids,playlist_thumb','channel_video_playlist',array('playlist_id'=>$playlist_id));
			
			if(!empty($playlist)){
				
				$playlist_thumb='';
				if($playlist[0]['playlist_thumb'] !=''){
					$img = explode('.',$playlist[0]['playlist_thumb']);
					$img = $img[0].'_thumb.'.$img[1];
					$playlist_thumb  = AMAZON_URL.'aud_'.$uid.'/images/'.$img;
				}
					
				if(!empty($playlist[0]['video_ids'])){
					
					$vids 	 =  explode('|' , $playlist[0]['video_ids']);
					unset($vids[0]); 
					$vidList = implode(',' , $vids);
					 
					$start 	= isset($_POST['start'])?$_POST['start']:0;
					$limit 	= isset($_POST['limit'])?$_POST['limit']:50;
				
					$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.created_at,website_mode.mode";
									
					$where = "channel_post_video.post_id IN($vidList) AND " . $this->common->channelGlobalCond();
							   
					$join  = array(
									'multiple',
									array(
										array('website_mode','website_mode.mode_id 	= channel_post_video.mode','left'),
										array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
										array('users' , 'users.user_id = channel_post_video.user_id'),
									)
								);
				
					$order ="FIELD(channel_post_video.post_id,$vidList)";
					
					$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
					
					if(!empty($videoData)){
						$resp = array('status'=>1,'data'=>array('playlist_video'=>$this->common_html->swiper_slider_without_html($videoData,'',$playlist_id), 'playlist_thumb'=>$playlist_thumb));
					}else{
						$resp = array('status'=>0,'data'=>array('playlist_thumb'=>$playlist_thumb),"defalutImg"=>thumb_default_image());
					}
					
				}else{
					$resp = array('status'=>0,'data'=>array('playlist_thumb'=>$playlist_thumb),"defalutImg"=>thumb_default_image());
				}
			}else{
				$resp = array('status'=>0,'data'=>array(),"defalutImg"=>thumb_default_image());
			}
		}
		
		echo json_encode($resp);
	}
	
	
}
?>
