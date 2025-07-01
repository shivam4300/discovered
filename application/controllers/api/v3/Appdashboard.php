<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//use App\ThirdParty\subscriber\Mailchimp\MailChimp;

class Appdashboard extends CI_Controller {


	private $uid;
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	public $search_data = [];
	public  $deviceType = ''; 
	
	public function __construct(){
		
		parent::__construct();
		//$this->load->library('creator_jwt');
		//$this->creator_jwt->isAuthorized();
		$this->load->library(array('image_lib','audition_functions','dashboard_function','form_validation','query_builder','share_url_encryption','API_common_function','PlayFab')); 
		$this->load->helper(array('aws_s3_action','button','playfab', 'iter')); 
		$this->load->model('UserModel');
		$this->deviceType = $this->input->get_request_header('device');
	}
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		//$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	function single_error_msg(){
		$errors = array_values($this->form_validation->error_array());
		return isset($errors[0])?$errors[0]:'';
	}  
	
	/******* User Profile STARTS ********/
	public function profile(){
		$data =array();
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
			
		if($this->form_validation->run() == TRUE){ 
			$uid = $_POST['user_id'];
			$_POST['pub_uid'] = $_POST['user_id'];

			/* Check users content blocked or not */
			if($this->checkBlockedContent($uid,$related_with = 1)){
				$this->statusCode = 2;  // Blocked
				$this->respMessage = 'User blocked by you.';
				$this->show_my_response($data);
				return false;
			}


			if(isset($_POST['get_more_post']) && !empty($_POST['get_more_post'])){
				
				$data['publish_post'] = $this->GetPublishPost()['post'];
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'more post.';
			}else{
			
				$accessParam = array(
						'field' => 'users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_type,users_content.uc_video,users_content.aws_s3_profile_video,users_content.uc_type,users_content.uc_pic,users_content.uc_city,users_content.name,users_content.uc_gender,users_content.uc_dob,users.user_address,country.country_name,users_content.uc_about,state.name,users.referral_by,users.user_phone',
						'where' => 'user_id='.$uid,
						);

						
							
				$userDetail	= $this->query_builder->user_list($accessParam);
			
				if(isset($userDetail['users']) && !empty($userDetail['users'])){
					
					$userDetail = $userDetail['users'];
					
					if(isset($userDetail[0]['uc_dob']) && !empty($userDetail[0]['uc_dob'])){
						unset($userDetail[0]['uc_dob']);
					}
					
					//Json decode about me 
					if(isset($userDetail[0]['uc_about']) && !empty($userDetail[0]['uc_about'])){
						
						$userDetail[0]['uc_about'] = json_decode($userDetail[0]['uc_about']);
						
						if(!empty($userDetail[0]['uc_about'])){
							$userDetail[0]['uc_about'] = htmlspecialchars_decode($userDetail[0]['uc_about'],ENT_QUOTES);
						}else{
							$userDetail[0]['uc_about'] ='';
						}
					}
					
					//get user pic 
					if(isset($userDetail[0]['uc_pic']) && !empty($userDetail[0]['uc_pic'])){
						
						//$userDetail[0]['uc_pic'] = get_user_image($uid);
						$uc_pic = $userDetail[0]['uc_pic'];
						$userDetail[0]['uc_pic']  = AMAZON_URL."aud_{$uid}/images/{$uc_pic}";
						
						/*$pathToimg = ABS_PATH."uploads/aud_{$uid}/images/{$uc_pic}";
			
						if(file_exists($pathToimg)){
							$userDetail[0]['uc_pic'] = base_url().'uploads/aud_'.$uid.'/images/'.$uc_pic.'?q='.date('his');
						}else{
							$userDetail[0]['uc_pic'] = base_url('repo/images/user/user.png');
						}*/
		
					}else{
						$userDetail[0]['uc_pic'] = base_url('repo/images/user/user.png');
					}
					
					//check is fan button
					if(!empty($this->check_FanButton($uid))){
						$button = $this->check_FanButton($uid);
						
						$userDetail[0]['isfan']=$button['is_fan'];
						$userDetail[0]['CanIMakeFan']=$button['CanIMakeFan'];
					}
					
					if(!empty($userDetail[0]['user_regdate'])){
						$userDetail[0]['user_regdate'] = date('F-d-Y',strtotime($userDetail[0]['user_regdate']));
					}
					
					//get gender name
					$uc_gender = $userDetail[0]['uc_gender'];
					$gender = $this->audition_functions->genders();
					$userDetail[0]['uc_gender'] = isset($gender[$uc_gender])? $gender[$uc_gender] : '';
					
					$data['userDetail'] = $userDetail;
					
					//Get referral name 
					if(isset($userDetail[0]['referral_by']) && !empty($userDetail[0]['referral_by'])){
						 
						 $referral_cond = array('user_uname'=>$userDetail[0]['referral_by']);
						 
						 $referral_name = $this->DatabaseModel->select_data('user_name',USERS,$referral_cond);
						 
						 if(isset($referral_name[0]['user_name']) && !empty($referral_name[0]['user_name'])){
							 $data['referral_name'] =	$referral_name[0]['user_name'];
							 $data['referral_by']  	=	$userDetail[0]['referral_by'];
						 }
					}
					
					//Make user directory 
					if( !empty($userDetail)){
						if( $userDetail[0]['user_dir'] == '0' ) {
						
							@mkdir ('./uploads/aud_'.$uid);
							@mkdir ('./uploads/aud_'.$uid.'/images');
							@mkdir ('./uploads/aud_'.$uid.'/videos');
							
							$update_arr = array('user_dir'	=>	1);
							$from_arr 	= array('user_id'	=>	$uid);
							$this->DatabaseModel->access_database(USERS,'update',$update_arr,$from_arr);
						}
					} 
					
					//Get category and sub category name
					$data['sub_catname'] = ''; 
					if(!empty($userDetail[0]['uc_type'])){
						$sub_cat = $this->DatabaseModel->select_data('category_name',ARTIST_CATEGORY,'category_id IN ('.$userDetail[0]['uc_type'].')');
						
						$size = (sizeof($sub_cat) <= 4)?sizeof($sub_cat):4;
						
						for($i=0;$i < $size; $i++ ){
							$data['sub_catname'] .=  $sub_cat[$i]['category_name'].',';
						}
						
						$data['sub_catname'] = rtrim($data['sub_catname'],", ");
					}
					
					
			
					$img_arr = $video_arr = array();
					
					$cond = "pub_uid = {$uid} AND pub_media LIKE '%image%'";
					$letest_image = $this->DatabaseModel->select_data('pub_id,pub_media','publish_data',$cond,4,'',array('pub_id','desc'));
					if(!empty($letest_image)) {
						foreach($letest_image as $solo_post) {
							if( $solo_post['pub_media'] != '' ) {
								$media_type = explode('|',$solo_post['pub_media']);
								if(isset($media_type[1])){
									if( $media_type[1] == 'image') {
										$imgData = explode('.',$media_type[0]);
										$thumb_name = AMAZON_URL .'aud_'.$uid.'/images/'.$imgData[0].'_thumb.'.$imgData[1];
										array_push($img_arr,$thumb_name.'|'.$solo_post['pub_id']);
									}
								}
							}
						}
					}
					$cond = "pub_uid = {$uid} AND pub_media LIKE '%video%'";
					$letest_video = $this->DatabaseModel->select_data('pub_id,pub_media','publish_data',$cond,4,'',array('pub_id','desc'));
					if(!empty($letest_video)) {
						foreach($letest_video as $solo_post) {
							if( $solo_post['pub_media'] != '' ) {
								$media_type = explode('|',$solo_post['pub_media']);
								if(isset($media_type[1])){
									if( $media_type[1] == 'video') {
										$videoData = explode('.',$media_type[0]);
										$vid_name = base_url('embed/').$solo_post['pub_id']; 
										$thumb100 = (isset($media_type[2]))? AMAZON_URL .'aud_'.$uid.'/images/'.$media_type[2] : '';
										array_push($video_arr,$thumb100.'|'.$solo_post['pub_id']);
									}
								}
							}
						}
					}
					
					$count_post = $this->DatabaseModel->aggregate_data('publish_data','pub_id','COUNT',array('pub_uid'=>$uid));
					
					$default_cover_image= base_url().'repo/images/default_profile_banner.jpg';
					$url ='';
					if(!empty($userDetail[0]['aws_s3_profile_video'])){
						$url = AMAZON_URL.$userDetail[0]['aws_s3_profile_video'];
					}
					$data['cover_video'] 			= array('url'=>$url,'cover_image'=>$default_cover_image);
					$data['publish_images'] 		= $img_arr;
					$data['publish_videos'] 		= $video_arr;
					$data['icon_fan']				= $this->api_common_function->WhoFollowMe($uid,1,6,0);
					$data['emerging_fan']			= $this->api_common_function->WhoFollowMe($uid,2,6,0);
					$data['brand_fan']				= $this->api_common_function->WhoFollowMe($uid,3,6,0);
					$data['fans']					= $this->api_common_function->WhoFollowMe($uid,4,6,0);
					$data['CreatorsYouEndorsing']	= $this->api_common_function->WhomIEndorse($uid,1,6,0);
					$data['BrandsYouEndorsing']		= $this->api_common_function->WhomIEndorse($uid,3,6,0);
					$data['CreatorsEndorsingYou']	= $this->api_common_function->WhoEndorseMe($uid,1,6,0);
					$data['BrandsEndorsingYou']		= $this->api_common_function->WhoEndorseMe($uid,3,6,0);
					
					$ivs_info 						= $this->DatabaseModel->select_data('*','users_ivs_info',['user_id'=>$uid],1);
					if(isset($ivs_info[0])){
						$data['ivs_data'] 				= $ivs_info[0];
					}
					
					$data['publish_post'] 			= $this->GetPublishPost()['post'];
					$data['post_count'] 			= $count_post;
					$data['notification_count']     = $this->get_notification_count();
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'User profile info.';
				}
				else {
					$this->respMessage = 'User info not found.';
				}
			}
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}		
		$this->show_my_response($data);
	}
	/******* User Profile ENDS ***************/
	

	public function getUserInfo(){
		$resp = array();
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){

			$uid = $TokenResponce['userid'];

			$cond = "users.user_id 	= '".$uid."'";
				
			$users_content_fields 	='users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_status,users_content.uc_type,users_content.aws_s3_profile_video,users_content.uc_type,users_content.uc_pic,users_content.uc_city,users_content.uc_gender,users_content.uc_dob,country.country_name,state.name,users.referral_by,users.user_phone,users_content.uc_state,users_content.uc_zipcode,users_content.interest,users_content.uc_country,users.user_level';
			
			$join = array('multiple' , array(
								array(	'users_content', 
										'users.user_id 				= users_content.uc_userid', 
										'left'),
								array(	'artist_category', 
										'users.user_level 			= artist_category.category_id', 
										'left'),		
								array(	'country', 
										'users_content.uc_country 	= country.country_id', 
										'left'),
								array(	'state',
										'users_content.uc_state 	= state.id',
										'left'),
								));
			
			$userDetail 	= $this->DatabaseModel->select_data($users_content_fields , 'users use INDEX(user_id)' , $cond, 1, $join);
			if(!empty($userDetail)){
				//get user pic 
				if(isset($userDetail[0]['uc_pic']) && !empty($userDetail[0]['uc_pic'])){
					$uc_pic = $userDetail[0]['uc_pic'];
					$userDetail[0]['uc_pic']  = AMAZON_URL."aud_{$uid}/images/{$uc_pic}";
				}else{
					$userDetail[0]['uc_pic'] = base_url('repo/images/user/user.png');
				}

				$resp['userDetails'] = $userDetail;
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'User profile information.';
			}else{
				$this->respMessage = 'User details not found.';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);	
	}

	
	function replace_array_key($user_Arr=array()){
		if(!empty($user_Arr)){
			foreach($user_Arr as $key=>$user){
				if(isset($user['endorsee_id'])){
					
					$user_Arr[$key]['following_id'] = $user['endorsee_id'];
					unset($user_Arr[$key]['endorsee_id']);
					
				}else if(isset($user['endorser_id'])){
					
					$user_Arr[$key]['following_id'] = $user['endorser_id'];
					unset($user_Arr[$key]['endorser_id']);
				}
			}
		}
		return $user_Arr;
	}

	/********* See all myfan ***********/
	public function see_all_myfan(){
		$data = array();
		$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
		$this->form_validation->set_rules('fan_type', 'Fan type', 'trim|required');
		if($this->form_validation->run() == TRUE){ 
			
			$uid = $_POST['user_id'];
			$fan_type = $_POST['fan_type'];
			$start 	=isset($_POST['start'])?$_POST['start']:0;
			$limit 	=isset($_POST['limit'])?$_POST['limit']:'';  
			
			if($fan_type =='icon_fan'){
				
				$data['icon_fan']				= $this->api_common_function->WhoFollowMe($uid,1,$limit,$start);
				$data['icon_fan'] 				= $this->get_user_details($data['icon_fan']);
				
			}elseif($fan_type =='emerging_fan'){
				
				$data['emerging_fan']			= $this->api_common_function->WhoFollowMe($uid,2,$limit,$start);
				$data['emerging_fan'] 			= $this->get_user_details($data['emerging_fan']);
				
			}elseif($fan_type =='brand_fan'){
				
				$data['brand_fan']				= $this->api_common_function->WhoFollowMe($uid,3,$limit,$start);
				$data['brand_fan'] 				= $this->get_user_details($data['brand_fan']);
				
			}elseif($fan_type =='fans'){
				
				$data['fans']					= $this->api_common_function->WhoFollowMe($uid,4,$limit,$start);
				$data['fans'] 					= $this->get_user_details($data['fans']);
				
			}elseif($fan_type =='CreatorsYouEndorsing'){
				
				$data['CreatorsYouEndorsing']	= $this->api_common_function->WhomIEndorse($uid,1,$limit,$start);
				$data['CreatorsYouEndorsing'] 	= $this->get_user_details($data['CreatorsYouEndorsing']);
				
			}elseif($fan_type =='BrandsYouEndorsing'){
				
				$data['BrandsYouEndorsing']		= $this->api_common_function->WhomIEndorse($uid,3,$limit,$start);
				$data['BrandsYouEndorsing'] 	= $this->get_user_details($data['BrandsYouEndorsing']);
				
			}elseif($fan_type =='CreatorsEndorsingYou'){
				
				$data['CreatorsEndorsingYou']	= $this->api_common_function->WhoEndorseMe($uid,1,6,$start);
				$data['CreatorsEndorsingYou'] 	= $this->get_user_details($data['CreatorsEndorsingYou']);
				
			}elseif($fan_type =='BrandsEndorsingYou'){
				
				$data['BrandsEndorsingYou']		= $this->api_common_function->WhoEndorseMe($uid,3,$limit,$start);
				$data['BrandsEndorsingYou'] 	= $this->get_user_details($data['BrandsEndorsingYou']);
				
			}
			
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'See all fans.';
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}		
		$this->show_my_response($data);
	}
	
	
	/*********** Get user full details ************/
	public function get_user_details($user_Arr=array()){
		$data=array();
		if(!empty($user_Arr)){
			foreach($user_Arr as $key=>$user){
				if(isset($user['following_id'])){
					
					$user_id = $user['following_id'];
					
				}else if(isset($user['endorsee_id'])){
					
					$user_id = $user['endorsee_id'];
					
				}else{
					
					$user_id = $user['endorser_id'];
				}
				$cond = "users.user_id 	= '".$user_id."'";
				
				
				$users_content_fields 	= array('users.user_regdate','users_content.uc_city','country.country_name','state.name','artist_category.category_name');
				
				$join = array('multiple' , array(
									array(	'users_content', 
											'users.user_id 				= users_content.uc_userid', 
											'left'),
									array(	'artist_category', 
											'users.user_level 			= artist_category.category_id', 
											'left'),		
									array(	'country', 
											'users_content.uc_country 	= country.country_id', 
											'left'),
									array(	'state',
											'users_content.uc_state 	= state.id',
											'left'),
									));
				
				$userContent 	= $this->DatabaseModel->select_data($users_content_fields , 'users' , $cond,'',$join);
				if(isset($userContent[0])){
					
					$userContent[0]['user_regdate']= date('F-d-Y',strtotime($userContent[0]['user_regdate']));
					//Check is fan button
					$button = $this->check_FanButton($user_id);
					
					$userContent[0]['isfan']=$button['is_fan'];
					$userContent[0]['CanIMakeFan']=$button['CanIMakeFan'];
					
					$data[] = array_merge($user_Arr[$key],$userContent[0]);
				}
			}
			
			return $data;
			
		}

	}
	
	
	
	/******* Get publish post common function STARTS******/
	public function GetPublishPost($publish_id=''){
		
		$strArr		= [];
		$social 	= isset($_POST['social'])?$_POST['social']:null;
		$uid 		= isset($_POST['pub_uid'])?$_POST['pub_uid']:'' ;
		$start 		= isset($_POST['start'])?$_POST['start']:0;
		$limit 		= isset($_POST['limit'])?$_POST['limit']:2;

		if($this->deviceType=='ANDROID' && $limit ==5){
			$limit = 10;
		}
		
		if($social == null){    /*WHEN CURRENT PAGE IS NOT SOCIAL PAGE*/
			$cond = array('pub_uid'=>$uid);
			if(!$this->is_token_uid($uid)){   /* FOR OTHER USER	*/
				$AmIFanOfHim = $this->AmIFollowingHim($uid);  
				if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
					$cond = 'publish_data.pub_status IN(6,7)  AND publish_data.pub_uid = '.$uid.'';/* PRIVATE,PUBLIC*/
				}else{
					$cond = array('publish_data.pub_status'=>7,'publish_data.pub_uid'=>$uid);	/* ONLY PUBLIC*/
				}
			}
			 
		}else{
			
			$following = $this->DatabaseModel->select_data('user_id',BECOME_A_FAN.' use INDEX (following_id)',array('following_id'=>$uid));
        	// $fids = $uid;
        	$fids = '';
			if(isset($following[0])){
				foreach($following as $fid){
					$fids .= ','.$fid['user_id']; 
				}
			}
			
			if(!empty($fids)){
				$cond = 'publish_data.pub_status IN(6,7)  AND publish_data.pub_uid IN('.trim($fids, ',').') ';
			}else{
				$resp['post'] =[];
				$resp['post_count']="0";
				return $resp;
			}	
		}
		
		if(isset($_POST['publish_id']) && $_POST['publish_id'] != 0 && empty($publish_id)){   /*IN CASE OF UPDATE*/
			$cond = array('publish_data.pub_id'=>$_POST['publish_id']);
		}else 
		if(!empty($publish_id)){
			$cond = array('publish_data.pub_id'=>$publish_id);     /*In case of shared post*/
		}
		
		$join = array('multiple' , array(
									array(	'users', 
											'users.user_id 	= publish_data.pub_uid',
											'left'),
									array(	'users_content', 
											'users_content.uc_userid= users.user_id ',
											'left'),
									));
		
		$publish_content = $this->DatabaseModel->select_data('publish_data.*,users.user_name,users.user_uname,users_content.uc_gender,users_content.uc_pic',PUBLISH_DATA.' use INDEX(pub_status,pub_uid)',$cond,array($limit ,$start),$join,($social == null)? array('publish_data.pub_id','desc') :'rand()');
		$i=0;
		if($i==0){ 
			$resp['post_count'] = $this->DatabaseModel->aggregate_data(PUBLISH_DATA,'publish_data.pub_id','COUNT',$cond,$join); 
			$i++;
		}
		
		if(isset($publish_content) && !empty($publish_content)){
			
			foreach($publish_content as $key=>$content){
				
				$pubId 		 = $content['pub_id'];
				$pub_uid 	 = $content['pub_uid'];
				$pub_media 	 = $content['pub_media'];
				$pub_content = $content['pub_content'];
				$pub_reason  = $content['pub_reason'];
				$user_uname  = $content['user_uname'];
				$user_name   = $content['user_name'];
				$uc_gender   = $content['uc_gender'];
				$share_pid   = $content['share_pid'];
				$share_uid   = $content['share_uid'];
				$is_video_processed   			   = $content['is_video_processed'];
				$user_pic 	 = $content['uc_pic'];
				$publish_content[$key]['user_pic'] = create_upic($pub_uid,$user_pic);
				$publish_content[$key]['pub_date'] = ''; //$this->time_elapsed_string($this->manageTimezone($content['pub_date']),false);
				
				$shared_data = [];
				$ShareMeNow  = $pubId;
				$is_deleted  = 0;
				if(!empty(trim($share_pid))){
					$shared_data = $this->GetPublishPost($share_pid)['post'];   /*In case of shared post*/
					$shared_data = !empty($shared_data)?$shared_data:'';
					
					if(empty($shared_data)){
						$shared_data = [];
						$is_deleted  = 1;
					}
					$ShareMeNow  = $share_pid;
				}
				
				$pub_format = '';
				$display_content='';
				if(!empty($pub_media)){
					$p_data = explode('|',$pub_media);
					$display_content = trim($p_data[0]);
					$pub_format = $p_data[1];
					
					$ThumbImage =  base_url('repo/images/thumbnail.jpg');
						if(sizeof($p_data) == 3){
							//$ThumbImage = base_url()."uploads/aud_".$pub_uid."/images/".$p_data[2];
							$ThumbImage = AMAZON_URL."aud_".$pub_uid."/images/".$p_data[2];
						}
					$publish_content[$key]['ThumbImage'] = $ThumbImage;	
				}
				
				if( $pub_format == 'video' ) {
					$this->load->library('share_url_encryption');
					$Filter = $this->share_url_encryption->FilterSocialVideo($pubId,$pub_uid,$display_content,$is_video_processed,'.mp4');
					
					$publish_content[$key]['display_content'] = $Filter['video'];
					
				}elseif( $pub_format == 'image' ) {
					
					//$publish_content[$key]['display_content'] = base_url().'uploads/aud_'.$pub_uid.'/images/'.$display_content;
					$publish_content[$key]['display_content'] = AMAZON_URL.'aud_'.$pub_uid.'/images/'.$display_content;
				}else{
					
					$publish_content[$key]['display_content'] = $display_content;
				}
				
					$publish_content[$key]['likes'] 		 = $this->like($pubId);
					$publish_content[$key]['isliked']		 = 0;
					if(!empty($this->get_token_uid())){
						$publish_content[$key]['isliked'] 		 = ($this->dashboard_function->get_total_likes($pubId,$this->get_token_uid()) =='yes') ? 1:0;
					}
					$publish_content[$key]['comments_count'] = $this->dashboard_function->get_main_commets_count($pubId);
					$publish_content[$key]['pub_format'] 	 = $pub_format;
					$publish_content[$key]['ShareMeNow'] 	 = $ShareMeNow;
					$publish_content[$key]['SharedPost'] 	 = $shared_data;
					$publish_content[$key]['is_deleted'] 	 = $is_deleted;
					$publish_content[$key]['publish_reason'] = $this->get_publish_reason($pub_reason,$uc_gender,$share_uid);
			}
		}
		$resp['post'] =$publish_content;
		return $resp;
	}
	
	/****************** Get publish post common function ENDS **************/
	
	
	/****************** Like / Delete  Post STARTS ***********************/
	 
	function action_on_post(){
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('pub_id', 'Publish id', 'trim|required');
			$this->form_validation->set_rules('action_type','Action type', 'trim|required');
			if ($this->form_validation->run() == TRUE){
			
				$uid = $TokenResponce['userid'];
				$pid = $_POST['pub_id'];
				if( $_POST['action_type'] == 'like' ) {
					
					$to_user = $this->getUserFromPost($pid);
					
					if(isset($_POST['dislike']) && !empty($_POST['dislike']) && $_POST['dislike']=='yes'){
						
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
						$this->statusType = 'Success';
						//$resp = array('data'=>$this->like($pid));
						
						
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
							$this->statusType = 'Success';
							$this->respMessage ='You liked it successfully.';
							//$resp = array('data'=>$this->like($pid));
							
						}
					}
				}else
				if( $_POST['action_type'] == 'delete' ) {
					$publish_data = $this->DatabaseModel->select_data('pub_media,pub_uid,channel_post_id','publish_data',array('pub_id'=>$pid),1);
					
					$channel_post_id 	= $publish_data[0]['channel_post_id'];
					$pub_media 			= $publish_data[0]['pub_media'];
					$pub_uid 			= $publish_data[0]['pub_uid'];
						
					$channel_post = $this->DatabaseModel->select_data('uploaded_video','channel_post_video use INDEX(post_id)',array('post_id'=>$channel_post_id),1);
					
					if(!empty($pub_media)){
						
						$publish = explode('|',$pub_media);
						
						if($publish[1] == 'image'){
							$folder = 'images';
						}else{
							$folder = 'videos';
						}
						
						$file = trim($publish[0]);
						
						$pathTo = ABS_PATH.'uploads/aud_'.$pub_uid.'/'.$folder.'/';
						if($publish[1] == 'image'){
							
							//@ unlink($pathTo.$file);
							
							s3_delete_object(array(trim('aud_'.$uid.'/images/'.$file.'')));
							
							$uc_pic = $this->DatabaseModel->select_data('uc_pic','users_content',array('uc_pic'=>$file),1);
							if(!isset($uc_pic[0]['uc_pic'])){
								$files = explode('.',$file);
								s3_delete_object(array('aud_'.$uid.'/images/'.$files[0].'_thumb.'.$files[1] ));
								
								//@ unlink($pathTo.$files[0].'_thumb.'.$files[1]);
							}
						}else{
							
							if(sizeof($publish) == 3){
								$Vidthumb = trim($publish[2]);
								s3_delete_object(array(trim('aud_'.$uid.'/images/'.$Vidthumb.'')));
								
								/* $VidthumbPath = ABS_PATH.'uploads/aud_'.$pub_uid.'/images/'.$Vidthumb;
								if (file_exists($VidthumbPath)){
									//@ unlink($VidthumbPath);
									
								} */
							}
							
							$old_key 	= trim('aud_'.$uid.'/videos/'.$file.'');
							$key		= explode('.',$old_key)[0];
							
							
							if(empty($channel_post_id)){
								
								s3_delete_object(array($old_key));
								s3_delete_matching_object(trim($key),TRAN_BUCKET);
								//s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
							}else{
								
								if(isset($channel_post[0]['uploaded_video'])){
									$this->DatabaseModel->access_database('channel_post_video','update', array('social'=>0) , array('post_id'=>$channel_post_id));
								}else{
									s3_delete_object(array($old_key));
									s3_delete_matching_object(trim($key),TRAN_BUCKET);
									//s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
								}
							}
						}
					}
					
					$this->DatabaseModel->access_database('publish_data','delete','', array('pub_id'=>$pid));
					$this->DatabaseModel->access_database('likes','delete','', array('like_pubid'=>$pid));
					$this->DatabaseModel->access_database('comments','delete','', array('com_pubid'=>$pid));
					$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$pid));
					
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage ='Post deleted successfully.';
				}
				elseif( $_POST['action_type'] == 'change_audience' ) {
					$this->DatabaseModel->access_database('publish_data','update', array('pub_status'=>$_POST['aud']) , array('pub_id'=>$pid));
					$this->statusCode = 1;
					$this->statusType = 'Success';
				}
			}else {
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
	
		$this->show_my_response($resp);
	}

	/******************** Like / Delete Post ENDS *******************/


	/*********** Comment / Reply/ Delete On Post STARTS *************/
	
	public function save_comment(){
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('pub_id', 'Publish id', 'trim|required');
			$this->form_validation->set_rules('com_text','Comment text', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$uid 		= $TokenResponce['userid'];
				$pub_id 	= $_POST['pub_id'];
				$com_text 	= validate_input($_POST['com_text']);
				$parent_id 	= $_POST['parent_id'];
				$data_array = array(
					'com_text'		=>	$com_text,
					'com_pubid'		=>	$pub_id,
					'com_parentid'	=>	$parent_id,
					'com_uid'		=>	$uid,
					'com_date'		=>	date('Y-m-d H:i:s')
				);
				$lst_com_id = $this->DatabaseModel->access_database(COMMENTS,'insert', $data_array , '');
				
				$to_user = $this->getUserFromPost($pub_id);
				
				/*Parent id zero means main comment*/
				$status = ($parent_id == 0)?1:2;    
				/*1= comment on post, 2 = reply on comment*/ 
				
				if($status == 2){
					$user = $this->DatabaseModel->select_data('com_uid',COMMENTS.' use INDEX(com_id)',array('com_id'=>$parent_id),1);
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
						
						$com_data = $this->get_commets();
						$resp = array('com_data'=>$com_data);
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Comments list.';
						
					}else{
						
						$com_data = $this->get_commets_reply();
						$resp = array('com_data'=>$com_data);
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Comments list.';
					}
				}
				
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	public function get_comment(){
		$resp=array(); 
		$this->form_validation->set_rules('pub_id', 'Publish id', 'trim|required');
		$this->form_validation->set_rules('start','Start', 'trim|required');
		if ($this->form_validation->run() == TRUE){
			if(isset($_POST['parent_id']) && $_POST['parent_id'] !=0){
				$com_data = $this->get_commets_reply();
				if(!empty($com_data)){
					$resp = array('com_data'=>$com_data);
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Comments list.';
				}else{
					$this->respMessage = 'Comments not found.';
				}
			}else{
				$com_data = $this->get_commets();
				if(!empty($com_data)){
					$resp = array('com_data'=>$com_data);
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Comments list.';
				}
				else{
					$this->respMessage = 'Comments not found.';
				}
			}
		}else{
			$this->respMessage =$this->single_error_msg();	
		}
		$this->show_my_response($resp);
	}
	
	
	function delete_comment(){
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$this->form_validation->set_rules('comment_id', 'Comment id', 'trim|required');
			if ($this->form_validation->run() == TRUE){
			 
				$uid = $TokenResponce['userid'];
				$cid = $_POST['comment_id'];
				
				$comments = $this->DatabaseModel->select_data('*',COMMENTS,array('com_parentid'=>$cid));
				$this->DatabaseModel->access_database(COMMENTS,'delete','', array('com_id'=>$cid));
				
				$this->DatabaseModel->access_database('notifications','delete','', array('reference_id'=>$cid,'noti_type'=>2));
				
				if(isset($comments[0])){
					$this->DatabaseModel->access_database(COMMENTS,'delete','', array('com_parentid'=>$cid));
					foreach($comments as $comm){
						$subComments = $this->DatabaseModel->select_data('*',COMMENTS,array('com_parentid'=>$comm['com_id']));	
						foreach($subComments as $subComm){
							$this->DatabaseModel->access_database('notifications','delete','', array('reference_id'=>$subComm['com_id'],'noti_type'=>2));
						}
						$this->DatabaseModel->access_database(COMMENTS,'delete','', array('com_parentid'=>$comm['com_id']));
					}
				}
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Comment deleted successfully.';
			}else{
				$this->respMessage =$this->single_error_msg();	
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	/**************** Comment / Reply / Delete On Post ENDS **************/
	
	
	/******* Upload Profile picture STARTS ***************/
	function upload_profile_image() {
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1) {	
		
			if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
				$uid = $TokenResponce['userid'];
				
				$pathToImages = ABS_PATH .'uploads/aud_'.$uid.'/images/';
				$image_name = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
				
				$img_ext = 'png';
				$imgNewname = $image_name.'.'.$img_ext;
	 			
				if($_FILES["userfile"]['type'] == 'image/png' || $_FILES["userfile"]['type'] == 'image/jpg' || $_FILES["userfile"]['type'] == 'image/jpeg'){
					
					if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $pathToImages.$imgNewname)){	
					
						// check EXIF and autorotate if needed
						$this->load->library('image_autorotate', array('filepath' =>$pathToImages.$imgNewname));
						
						list($width, $height, $type, $attr) = getimagesize($pathToImages.$imgNewname);
						$w = $width; $h = $height;  
						if(($width > 660 || $height > 660) ){
							$w = $h = 660; 
						}
						$this->audition_functions->resizeImage($w ,$h,$pathToImages.$imgNewname,'',true,false,95);
						
						$resize = $this->audition_functions->resizeImage('246','246',$pathToImages.$imgNewname,'',$maintain_ratio = false,$create_thumb= true);
					
						
						$this->DatabaseModel->access_database(USERS_CONTENT,'update',array('uc_pic'=>$imgNewname), array('uc_userid'=>$uid));
			
						$user_name = $this->DatabaseModel->select_data('user_name ',USERS,array('user_id'=>$uid),1);
						$user_name = isset($user_name[0]['user_name'])?$user_name[0]['user_name']:'';
						$data_array = array(
								'pub_uid'		=>	$uid,
								'pub_reason'	=>	1,
								'pub_media'		=>	$imgNewname.'|image',
								'pub_status'	=>	7,
								'pub_date'		=>	date('Y-m-d H:i:s')
								);
						$this->DatabaseModel->access_database(PUBLISH_DATA,'insert',$data_array, '');
						
						upload_all_images($uid);
						syncPlayFabPlayerProfilePicture(first($this->UserModel->get($uid)));

						//$resp = array('imageName'=>$imgNewname);
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Image uploaded successfully.';
						
					}else{
						$this->respMessage ='Something Went Wrong ! Please try again.';
					}
				}else{
					$this->respMessage = 'Please use right image format ! Please try again. ';
				}
			}else{
				$this->respMessage ='Please select an image.';
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	/******* Upload Profile picture ENDS ***************/

	/******* Remove Profile picture STARTS ***************/
	function remove_my_profile(){
		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('type', 'type', 'trim|required');
			
			if($this->form_validation->run() == TRUE){
				$uid =  $TokenResponce['userid'];	
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
					}
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Profile image removed successfully.';
					/*if($this->DatabaseModel->access_database(USERS_CONTENT,'update',array('uc_pic'=>''), array('uc_userid'=>$uid))){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Profile image removed successfully.';
					}else{
						$this->respMessage = 'Something went wrong please try again.';
					}*/
				}else if($_POST['type'] == 'video'){
					
					$previous_Details = $this->DatabaseModel->select_data('aws_s3_profile_video,uc_video',USERS_CONTENT,array('uc_userid'=>$uid),1);
					
					if(!empty($previous_Details)){
						
						if($previous_Details[0]['uc_video'] == 'direct'){
							$old_key = trim($previous_Details[0]['aws_s3_profile_video']);
								
							if($old_key !== ''){
								$key = explode('.',$old_key)[0];
								s3_delete_object(array($old_key));
								s3_delete_matching_object(trim($key),TRAN_BUCKET);
								//s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
							}
						}
						
						$this->DatabaseModel->access_database(USERS_CONTENT,'update',array('aws_s3_profile_video'=>'','is_video_processed'=>0), array('uc_userid'=>$uid));
						
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Profile video remove successfully.';
							
					}else{
						$this->respMessage = 'Something went wrong please try again.';
					}					
				}
					
			}else{
				$this->respMessage =$this->single_error_msg();
			}
					
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	/******* Remove Profile picture ENDS ***************/
	
	
	/******* Upload Profile video STARTS ***************/
	
	function upload_profile_video() {
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$uid =  $TokenResponce['userid'];
			$this->form_validation->set_rules('video_key', 'Video key', 'trim|required');
			
			if($this->form_validation->run() == TRUE){
				
				$previous_Details = $this->DatabaseModel->select_data('aws_s3_profile_video,uc_video',USERS_CONTENT,array('uc_userid'=>$uid),1);
				
				if( isset($previous_Details[0]['uc_video']) && $previous_Details[0]['uc_video'] == 'direct'){
					
					$old_key = trim($previous_Details[0]['aws_s3_profile_video']);
					if($old_key !== ''){
						$key = explode('.',$old_key)[0];
						s3_delete_object(array($old_key));
						s3_delete_matching_object(trim($key),TRAN_BUCKET);
					}
				}
					
				$this->DatabaseModel->access_database(USERS_CONTENT,'update',array('aws_s3_profile_video'=>$_POST['video_key'],'uc_video'=>'direct','is_video_processed'=>0), array('uc_userid'=>$uid));
				
				$resp['cover_video'] = AMAZON_URL.$_POST['video_key'];
				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				$this->respMessage 	= 'Profile video uploaded successfully.';
				
			}
			else {
				$this->respMessage =$this->single_error_msg();
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	
	
	function upload_profile_video_old() {
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$uid =  $TokenResponce['userid'];
			
			if(isset($_FILES['userfile']['name'])){
				
				$previous_Details = $this->DatabaseModel->select_data('aws_s3_profile_video,uc_video',USERS_CONTENT,array('uc_userid'=>$uid),1);
				
				if(!empty($previous_Details)){
					
					$file_type = $_FILES['userfile']['type'];
					if ( ($file_type == "video/mp4") || ($file_type == "video/quicktime") ){
						
						$rna = $this->common->generateRandomString(20);
						$amazon_path = "aud_{$uid}/videos/{$rna}.".pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
						$res = s3_upload_object_ad($_FILES['userfile']['tmp_name'],$amazon_path); 
						
						$this->DatabaseModel->access_database(USERS_CONTENT,'update',array('aws_s3_profile_video'=>$res['key'],'uc_video'=>'direct','is_video_processed'=>0), array('uc_userid'=>$uid));
						
						if( isset($previous_Details[0]['uc_video']) && $previous_Details[0]['uc_video'] == 'direct'){
							
							$old_key = trim($previous_Details[0]['aws_s3_profile_video']);
							if($old_key !== ''){
								$key = explode('.',$old_key)[0];
								s3_delete_object(array($old_key));
								s3_delete_matching_object(trim($key),TRAN_BUCKET);
								//s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
							}
						}
						
						//echo json_encode($resp);
						$resp['cover_video'] = $res['url'];
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';
						$this->respMessage 	= 'Profile video uploaded successfully.';
					
					}else{
						$this->respMessage = 'Please upload right video format ! Please Try Again';
					}
				
				}else{
					$this->respMessage ='Something went wrong, please try again.';
				}
			}
			else {
				$this->respMessage ='Please select an video.';
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	/******* Upload Profile video ENDS *********/
	
	/******* Give your vote STARTS ***************/
	
	function give_your_vote(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1) {
			
			$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
			
			if ($this->form_validation->run() == TRUE) {
				
				$user_id = $TokenResponce['userid'];
				$post_id = $_POST['post_id'];
				$post_user = array('user_id'=>trim($user_id),'post_id'=>trim($post_id));	
				
				$isvoted = $this->DatabaseModel->select_data('vote_id','channel_video_vote',$post_user,1);
				if(empty($isvoted)){
					$post_user['vote_date'] = date('Y-m-d h:i:s');
					if($this->DatabaseModel->access_database('channel_video_vote','insert',$post_user,'')){
						
						$this->db->set('count_votes', '`count_votes`+ 1', FALSE);
						$this->db->where('post_id', $post_id);
						$this->db->update('channel_post_video');
						
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'You voted successfully.';
					}
				}else{
					$this->respMessage = 'You have already voted.';
				}	
			}
			else {
				$this->respMessage =$this->single_error_msg();
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	/******* Give your vote STARTS ***************/
	
	/******* Become A Fan STARTS ***************/
	
	public function becomeFan(){
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1) {
			
			$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
		
			$following_id = $TokenResponce['userid'];
		
			if ($this->form_validation->run() == TRUE) {
		
				$fan_user = array('user_id'=>trim($_POST['user_id']),'following_id'=>$following_id);	
				$isfan_user = $this->DatabaseModel->select_data('follow_date',BECOME_A_FAN.' use INDEX(user_id,following_id)',$fan_user);
				
				if(empty($isfan_user)){
					$isfan_user['follow_date'] = date('Y-m-d h:i:s');
					if($this->DatabaseModel->access_database(BECOME_A_FAN,'insert',$fan_user, '')){
						
						$to_user = $fan_user;
						//$to_user = $_POST['user_id'];
						$this->audition_functions->sendNotiOnBecomeAfan($following_id, $to_user,$following_id,'',$status = 1);
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'You are fan.';
						$this->load->library('Gamification');
						$this->gamification->player_became_fan(
							first($this->UserModel->get($to_user)),
							first($this->UserModel->get($following_id))
						);
					}
				}else{
					if($this->DatabaseModel->access_database(BECOME_A_FAN,'delete','', $fan_user) > 0){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Become a fan.';
					}
				}	
			}
			else {
				$this->respMessage =$this->single_error_msg();
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response();
	}
	
	/******* Become A Fan STARTS ***************/

	/*************** Get user from post*****************/
	public function getUserFromPost($pub_id = null){
		$user = $this->DatabaseModel->select_data('pub_uid',PUBLISH_DATA.' use INDEX(pub_id)',array('pub_id'=>$pub_id),1);
		return $to_user = isset($user[0]['pub_uid'])?$user[0]['pub_uid']:'';
	}
	
	/************** Get search content list *****************/
	public function search_content(){
		$resp=array();
		$this->form_validation->set_rules('search', 'Search', 'trim|required');
		if ($this->form_validation->run() == TRUE){
			$list = '';
			$search_result=[];
			// $mode_id = (isset($_GET['mode_id']))?$_GET['mode_id']:$_SESSION['website_mode']['id'];  /*By default site mode*/
			
			$mode_id  = '';   /*IN CASE OF ALL*/
			if(isset($_POST['mode_id']) && !empty($_POST['mode_id'])){
				$mode_id  = validate_input($_POST['mode_id']); /*site mode selected by user in a serch filter page*/
			}
			
			// $searchKey = str_replace("'","\'",trim($_POST['search']));
			$searchKey = addslashes(validate_input($_POST['search']));
			$searchKey = str_replace("&amp;","&",$searchKey);
			
			$cond = "users.is_deleted = 0  AND users.user_status = 1 AND (users.user_uname LIKE '".$searchKey."%' OR  users.user_name LIKE '".$searchKey."%' )";
			$join = array('multiple' , array(
								array(	'users_content', 
										'users.user_id 	= users_content.uc_userid', 
										'right'),
								));
			$search_result = $this->DatabaseModel->select_data('users.user_name','users',$cond,10,$join);
			if(isset($search_result[0]))
				$this->AddSearcResult($search_result);
			
			
			$cond = "channel_post_video.privacy_status = 7 AND channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND users.user_status = 1 AND (users.user_uname LIKE '".$searchKey."%' OR  users.user_name LIKE '".$searchKey."%' OR channel_post_video.title LIKE '%".$searchKey."%' )" ;
				
				if(!empty($mode_id))
				$cond .= "AND mode = {$mode_id}";
			
				
				$join = array('multiple' , array(
												array('users', 
													  'users.user_id 		= channel_post_video.user_id', 
													  'left'),
												array('users_content', 
													  'users.user_id 		= users_content.uc_userid', 
													  'right')
											)
							);
			$search_result = $this->DatabaseModel->select_data('channel_post_video.title','channel_post_video use INDEX(post_id)',$cond,10,$join);
			if(isset($search_result[0]))
				$this->AddSearcResult($search_result);
			
			if(!empty($this->search_data)){
				$resp['searchData'] = array_values(array_unique($this->search_data));
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ="Data found.";
			}else{
				$resp['searchData']=[];
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ="No data found.";
			}
		}
		else {
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	public function AddSearcResult($search_result){
			$results = [];
			
			foreach($search_result as $result){
				if(isset($result['title']))
				array_push($results,$result['title']);
				
				if(isset($result['user_name']))
				array_push($results,$result['user_name']);
			}
			$data = array_values(array_unique(array_filter($results)));
			
			foreach($data as $result){
				array_push($this->search_data,strtolower($result));
			}
			return true;
	}
	
	/*************** Add to favorite ******************/
	function add_to_favorite(){
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1) {
			
			$this->form_validation->set_rules('post_id', 'post id', 'trim|required');
			if ($this->form_validation->run() == TRUE) {
					$uid = $TokenResponce['userid'];
					$favCond = array('user_id'=>$uid,'channel_post_id'=>$_POST['post_id']);	
					$isMyFavorite = $this->DatabaseModel->select_data('*','channel_favorite_video',$favCond,1);
					if(empty($isMyFavorite)){
						$favCond['created_at'] = date('Y-m-d H:i:s');
						$this->DatabaseModel->access_database('channel_favorite_video','insert',$favCond);
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Added to favorite list.'; 
					}else{
						$this->DatabaseModel->access_database('channel_favorite_video','delete','', $favCond);
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Removed from favorite list.'; 
					}
				
			}else {
				$this->respMessage =$this->single_error_msg();
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response();
	}
	
	/************** Get my favorite videos *****************/
	function get_my_favorite_video(){
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			$_POST['favorite'] =1;
			$myFav = $this->get_my_content('video');
			if(!empty($myFav['data'])){
				$resp=array('myfav_video'=>$myFav['data'],
							'total_count'=>$myFav['total_count']
							);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ='My favorite video list.';
			}
			else {
				$resp=array('myfav_video'=>[],
							'total_count'=>"0"
							);
				$this->respMessage ='video not found.';
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	/************** Get home videos *****************/
	public function homeVideo_old(){
		$data=array();
		$mode = isset($_POST['mode'])? $_POST['mode'] : 1 ;
		$home_video					=	$this->show_homepage_video();
		$data['cover_video'] 		=  	$this->get_cover_video('homepage','music');
		//$data 					= 	$this->filter_my_all_video('all' , $mode);
		//$data['featured'] 		= 	$this->featuredVideo($mode);
		//$data['rndmGenr'] 		= 	$this->RandoeGenreHomeVideo(2);
		if(!empty($home_video)){
			
			$data['homeVideos'] 	= $home_video['video'];
			$data['total_count'] 	= $home_video['total_count'];
			$this->statusCode 		= 1;
			$this->statusType 		= 'Success';
			$this->respMessage		= 'Video list.';
		}else{
			$this->respMessage ='Video Not found.';
		}
		//$data['cover_image'] 	=  	$this->audition_functions->get_cover_image();
		//$data['cover_video'] 	=  	$this->audition_functions->get_cover_video('homepage');
		$this->show_my_response($data);
		 
		// print_r($data['cover_video'] );die; 
		 
	}
	
	
	public function homeVideo(){  //This API Used by ANDROID & IOS App only
		
		$this->load->library('Valuelist');
		$web_mode = $this->valuelist->mode();
		$data=array();
		$data['homeVideos'][0]		=	[];
		
		$_POST['mode'] 				= 	isset($_POST['mode'])? $_POST['mode'] : 1 ;
		
		$mode 						= 	($web_mode[$_POST['mode']])? $web_mode[$_POST['mode']] : 'music';
		
		$home_video					=	$this->show_homepage_slider();
		
		if($this->deviceType=='ANDROID' || $this->deviceType=='IOS'){
			$data['cover_video'] 		=   $this->getAndroidIosCoverVideo('homepage',$mode);
		}else{
			$data['cover_video'] 		=  	$this->get_cover_video('homepage',$mode);
		}
		
		
		if($_POST['mode'] == 7 && (isset($_POST['start']) && $_POST['start'] ==0)){
			$sliderTopGames 			= 	$this->SubGenreSlider($_POST['mode']);
			if(!empty($sliderTopGames)){
				$data['homeVideos'][0]	=	array(array('slider_type'=>'subGenreSlider', 'type'=>'Top Games','slider'=>$sliderTopGames));
			}
		}

		$data['total_slider_count'] = "0";

		if(!empty($home_video['data'])){
			$data['homeVideos'] 		=	array_merge($data['homeVideos'][0], $home_video['data']);
			$data['total_slider_count'] =	$home_video['total_count'];
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage ='Video list.';
		}else{
			$this->respMessage ='Video Not found.';
		}
		
		for($i=1; $i<11; $i++){
			$data['topTenImages'][] = CDN_BASE_URL.'repo/images/top_ten/top_ten'.$i.'.png';
		}
		
		$this->show_my_response($data);
	}
	
	public function homeVideoSpotlight(){  //This API Used by TIZEN App only
		$data=array();
		$this->load->library('Valuelist');
		$web_mode = $this->valuelist->mode();

		$data['homeVideos'][0]=[];
		$_POST['mode'] = isset($_POST['mode'])? $_POST['mode'] : 1 ;
		$mode = ($web_mode[$_POST['mode']])? $web_mode[$_POST['mode']] : 'music';
		$home_video					=	$this->show_homepage_slider();



		$data['cover_video'][]  =  	$this->get_cover_video('homepage',$mode);
		
		/* More videos from this creator */
		$data['related_video']=[];
		foreach($data['cover_video'] as $coverVideo){
			if(!empty($coverVideo['user_id'])){
				$_POST['uid']  			= $coverVideo['user_id'];
				$_POST['data_return']   = 1;
				$related_vid = $this->get_related_video();
				if(!empty($related_vid)){
					$data['related_video'][]  = $related_vid;
				}
			}
		}
		
		if($_POST['mode'] == 7){
			$sliderTopGames 			= 	$this->SubGenreSlider($_POST['mode']);
			if(!empty($sliderTopGames)){
				$data['homeVideos'][0]	=	array(array('slider_type'=>'subGenreSlider', 'type'=>'Top Games','slider'=>$sliderTopGames));
			}
		}
		$data['total_slider_count'] = "0";
		if(!empty($home_video['data'])){
			$data['homeVideos'] 		=	array_merge($data['homeVideos'][0], $home_video['data']);
			$data['total_slider_count'] =	$home_video['total_count'];
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage ='Video list.';
		}else{
			$this->respMessage ='Video Not found.';
		}
		
		$this->show_my_response($data);
	}
	

	public function get_articles_home_sliders(){  //This API Used by ANDROID & IOS App only
		
		$this->load->library('Valuelist');
		$web_mode = $this->valuelist->mode();
		$data=array();
		$data['homeArticles']       =	[];
		
		$_POST['mode'] 				= 	isset($_POST['mode'])? $_POST['mode'] : 10 ;
		
		$mode 						= 	($web_mode[$_POST['mode']])? $web_mode[$_POST['mode']] : 'articles';
		
		$home_articles				=	$this->getArticlesHomeSliders();
		
		if(($this->deviceType=='ANDROID' || $this->deviceType=='IOS') && (isset($_POST['start']) && $_POST['start']==0)){
			$data['cover_video'] 		=   $this->getAndroidIosCoverVideo('homepage',$mode);
		}
		
		$data['total_slider_count'] = "0";

		if(!empty($home_articles['data'])){
			$data['homeArticles'][0] 	= [];
			$data['homeArticles'] 		=	array_merge($data['homeArticles'][0], $home_articles['data']);
			$data['total_slider_count'] =	$home_articles['total_count'];
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage ='Articles list.';
		}else{
			$this->respMessage ='Articles Not found.';
		}
		
		$this->show_my_response($data);
	}


	/*************** Load related video ****************/
	function load_player_next_video_old(){
		$resp=array();
		$this->form_validation->set_rules('puid', 'post user id', 'trim|required');
		$this->form_validation->set_rules('pid', 'post id', 'trim|required');
		$this->form_validation->set_rules('tag', 'tag', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			
			$start = (isset($_POST['start']))?$_POST['start'] :0;
			$limit = (isset($_POST['limit']))?$_POST['limit']:10;
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_id,channel_post_video.uploaded_video,channel_post_video.iva_id,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,users.user_level';
			
			$join = array('multiple' , array(
									array(	'channel_post_thumb',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
									array(	'users', 
											'users.user_id 				= channel_post_video.user_id', 
											'inner'),
						));
			
			$cond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND channel_post_video.age_restr = "Unrestricted" AND users.user_status = 1 AND channel_post_video.post_id != '.$_POST['pid'].'';
			
			$tag = explode(',',$_POST['tag']);
			$cond .= ' REPLACE (';
			for($i=0;$i < sizeof($tag); $i++){
				$searchTag = str_replace("'","\'",trim($tag[$i]));
				$cond .= "channel_post_video.tag LIKE '%".trim($searchTag)."%'";
				if(sizeof($tag) - $i != 1){
					$cond .= ' REPLACE ';
				}
			}
			$cond .= ')';
			$cond2 	=  $cond;
			$cond 	= str_replace("REPLACE","AND",$cond);
			
			$related_video1 = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,array($limit,$start) ,$join,'rand()');
			
			$cond 	= str_replace("REPLACE","OR",$cond2);
			$related_video2 = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,array($limit,$start) ,$join,'rand()');
			
			$related_video = array_merge_recursive($related_video1,$related_video2);
			// print_r($related_video1); print_r($related_video2);die;
			if(isset($related_video[0])){
				$resp['data']=$this->swiper_slider($related_video);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Video list.';						
			}else{
				$this->respMessage = 'Video not found.';
			}
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	
	function load_player_next_video(){
		$resp=array();
		$this->form_validation->set_rules('puid', 'post user id', 'trim|required');
		$this->form_validation->set_rules('pid', 'post id', 'trim|required');
		$this->form_validation->set_rules('tag', 'tag', 'trim');
		if ($this->form_validation->run() == TRUE) {
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_id,channel_post_video.uploaded_video,channel_post_video.iva_id,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_type,channel_post_video.video_duration,users.user_level,mode_of_genre.genre_name';
				
			$join = array('multiple' , array(
										array(	'channel_post_thumb use INDEX(post_id)',
												'channel_post_thumb.post_id = channel_post_video.post_id',
												'left'),
										array(	'users use INDEX(user_id)', 
												'users.user_id 				= channel_post_video.user_id', 
												'inner'),
										array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),
								));
			
			$globalcond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1 ';
			
			$start = (isset($_POST['start']))?$_POST['start'] :0;
			$limit = (isset($_POST['limit']))?$_POST['limit']:10;
				
			if(isset($_POST['playlist_id']) && !empty($_POST['playlist_id'])){
				
				$playlists 	= $this->DatabaseModel->select_data('video_ids','channel_video_playlist',['playlist_id'=>$_POST['playlist_id']]);
				
				if(isset($playlists[0]['video_ids']) && !empty($playlists[0]['video_ids'])){
					$pids 			= $_POST['pid'];
					$vid_ids 		= explode('|',$playlists[0]['video_ids']);
					$vid_ids 		= array_values(array_diff($vid_ids,[$pids]));  // remove pids from playlist
					$playlists 		= implode(',',$vid_ids);
					$video_items 	= trim($playlists,',');
					
					$cond = $globalcond .' AND channel_post_video.post_id IN('.$video_items.')';
							
					$order = 'FIELD(channel_post_video.post_id, '.$video_items.')';

					$next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$cond,PLAYLIST_VIDEO_LIMIT,$join, $order );
				}
				
			}else{
				
				$pids = $_POST['pid'];
				$field1 = "channel_post_video.mode,channel_post_video.genre,channel_post_video.category";
				$where = "channel_post_video.post_id IN($pids)";
				$currentVideoInfo = $this->DatabaseModel->select_data($field1,'channel_post_video use INDEX(post_id)',$where,1);
				
				if(isset($_POST['last_pids']) && !empty($_POST['last_pids'])){
					$lastPids = json_decode($_POST['last_pids']);
					if(!empty($lastPids) && !in_array($pids,$lastPids)){
						$lastPids = implode(',',$lastPids);
						$pids     = $pids.','.$lastPids;
					}
				}
				
				$Maincond =  $globalcond.'AND channel_post_video.post_id NOT IN('.$pids.')';
				
				$mod 		= 	isset($currentVideoInfo[0]['mode']) && !empty($currentVideoInfo[0]['mode'])?$currentVideoInfo[0]['mode']:'1';
				$cat 		= 	isset($currentVideoInfo[0]['category']) && !empty($currentVideoInfo[0]['category'])?$currentVideoInfo[0]['category']:'1';
				$gen 		= 	isset($currentVideoInfo[0]['genre']) && !empty($currentVideoInfo[0]['genre'])?$currentVideoInfo[0]['genre']:'0';
				$uid 		= 	isset($_POST['puid']) && !empty($_POST['puid'])?$_POST['puid']:'0';
				
				$cond2 		= ' AND channel_post_video.mode 	= '.$mod.' 
								AND channel_post_video.genre 	= '.$gen.' 
								AND channel_post_video.user_id 	= '.$uid.'';
				
				$cond3 		= ' AND channel_post_video.mode 	= '.$mod.' 
								AND channel_post_video.genre 	= '.$gen.'';
								
				$cond4 		= ' AND channel_post_video.mode 	= '.$mod.'';
				
				//$order 		= ['channel_post_video.post_id','ASC'];
				$order 		= 'rand()'; 
				
				$cond		= $Maincond . $cond3 ;
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$cond, $limit  ,$join, $order);
			
				if(empty($next_video)){
					$cond		= $Maincond.$cond3 ;
					$next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$cond,$limit ,$join, $order );
				}
				
				if(empty($next_video)){
					$cond		= $Maincond .$cond4 ;
					$next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$cond,$limit ,$join, $order );
				}
			}	
				
			if(empty($next_video)){
				$order 		= array('channel_post_video.post_id','DESC');
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$globalcond,$limit,$join, $order );
			}
				
			if(isset($next_video[0])){
				$resp['data']=$this->swiper_slider($next_video,true);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Video list.';						
			}else{
				$this->respMessage = 'Video not found.';
			}
			
		}else {
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	
	
	/* To get the particular user related videos */
	function get_related_video(){
		$resp=array();
		$this->form_validation->set_rules('uid', 'Post user id', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
		 
			$start = (isset($_POST['start']))?$_POST['start'] :0;
			$limit = (isset($_POST['limit']))?$_POST['limit']:10;
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.post_id,channel_post_video.iva_id, channel_post_video.uploaded_video,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,users.user_level,country.country_name,mode_of_genre.genre_name,channel_post_video.description,channel_post_video.video_type,channel_post_video.genre,';
			
			$join = array('multiple' , array(
									array(	'channel_post_thumb use INDEX(post_id)',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
									array(	'users use INDEX(user_id)', 
											'users.user_id 				= channel_post_video.user_id', 
											'inner'),
									array('users_content use INDEX(uc_userid)','users_content.uc_userid = users.user_id','left'),
									array('country','country.country_id = users_content.uc_country','left'),
									array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),

						));
			
			$cond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1  AND channel_post_video.user_id = '.$_POST['uid'].'';
			
			$related_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,array($limit,$start) ,$join,'rand()');
			
			if(isset($related_video[0])){
				$resp['related_video'] = array('slider_type'=>'more_from_this_creator', 'type'=>'More From This Creator', 'slider'=>$this->swiper_slider($related_video, true));				
			}
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Related Video list.';	
		}
		else {
			$this->respMessage = $this->single_error_msg();
		}
		if(isset($_POST['data_return'])){
			return isset($resp['related_video']) ? $resp['related_video'] :[];
			die;
		}
		$this->show_my_response($resp);	
	}
	
	/*************** My channel video ********************/
	public function my_channel(){
		$uid = $this->get_token_uid();
		$other_user = (isset($_POST['user_id'])?$_POST['user_id']:'');
		
		if(!empty($other_user)){
			$uid	=	$other_user;
		}
		$limit =(isset($_POST['limit']))?$_POST['limit']:1;  
		$musics 			= array();
		$movies 			= array();
		$televisions 		= array();
		$gamings 			= array();
		$incomplete_video 	= array();
		//$data['sub_catname'] 		= ''; 
		$data['channel']	= array();
		
		/*$accessParam = array(
						'field' => 'users.user_id,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users_content.uc_type,users.referral_by,users_content.is_video_processed',
						'where' => 'user_id='.$uid,
						);
						
		$userDetail	= $this->query_builder->user_list($accessParam);*/
		
		$join  = array(
			'multiple',
			array(
				array('users_content' , 'users.user_id = users_content.uc_userid',
					'left'),
		));

		$cond = "users.user_role = 'member' AND users.user_id = {$uid}";
		$userDetail['users'] = $this->DatabaseModel->select_data('users_content.aws_s3_profile_video,users_content.is_video_processed','users use INDEX(user_id)',$cond,1,$join);


		if(isset($userDetail['users']) && !empty($userDetail['users'])){
			$userDetail = $userDetail['users'];
		
			$is_vid_processed		=	$userDetail[0]['is_video_processed'];
			$cover 					= 	$userDetail[0]['aws_s3_profile_video'];
			$url					=	"";
			$preview				=	"";
			if(!(empty($cover))){
				$url 				= 	AMAZON_URL .$cover;
				$preview			=	$this->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4',$is_vid_processed);
				$preview			=	isset($preview['video'])?$preview['video']:'';
			}
	
			$data['cover_video'] 	= 	array('url'=>$url,'preview'=>$preview);
		
			$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.mode";
		
			$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 AND mode = 1 ';
		
			if(!$this->is_token_uid($uid)){
				//$where .='AND channel_post_video.age_restr = "Unrestricted" ';
			}
			$globalCond =  $this->GobalPrivacyCond($uid);
		
			$where .=  $globalCond;	  
		
			$join  = array(
						'multiple',
						array(
							array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
						)
					);
			$Order = array('channel_post_video.post_id','DESC');
			
			$playlist	= $this->DatabaseModel->select_data('playlist_id,first_video_id,mode','channel_video_playlist use INDEX(playlist_id)',['user_id'=>$uid,'playlist_type'=>1]);
			
			/*START OF USER MUSIC CHANNEL VIDEO*/
			$musics = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$Order);
			$musics = $this->swiper_slider($musics);
			if(!empty($musics)){
				$musics[0]['playlist_id'] = $this->getPlaylistId($playlist,'1');
				$musics[0]['mode_key']    = 'musics';
				$data['channel'][]=$musics[0];
			}
			/*END OF USER MUSIC CHANNEL VIDEO*/
			
			/*START OF USER MOVIE CHANNEL VIDEO*/
			$where = str_replace("mode = 1","mode = 2" ,$where);
			$movies = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$Order);
			$movies = $this->swiper_slider($movies);
			if(!empty($movies)){
				$movies[0]['playlist_id'] = $this->getPlaylistId($playlist,'2');
				$movies[0]['mode_key'] = 'movies';
				$data['channel'][] = $movies[0];
			}
			/*END OF LETEST MOVIE CHANNEL VIDEO*/
			
			/*START OF USER TV CHANNEL VIDEO*/
			$where = str_replace("mode = 2","mode = 3" ,$where);
			$televisions = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$Order);
			$televisions = $this->swiper_slider($televisions);
			if(!empty($televisions)){
				$televisions[0]['playlist_id'] = $this->getPlaylistId($playlist,'3');
				$televisions[0]['mode_key'] = 'televisions';
				$data['channel'][] = $televisions[0]; 
			}
			/*END OF USER TV CHANNEL VIDEO*/
			
			/*START OF USER GAMING MODE CHANNEL VIDEO*/
			$where = str_replace("mode = 3","mode = 7" ,$where);
			$gamings = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$Order);
			$gamings= $this->swiper_slider($gamings);
			if(!empty($gamings)){
				$gamings[0]['playlist_id'] = $this->getPlaylistId($playlist,'7');
				$gamings[0]['mode_key']    = 'gamings';
				$data['channel'][]         = $gamings[0];
			}
			/*END OF USER GAMING MODE CHANNEL VIDEO*/
			
			/*START OF LETEST USER CHANNEL VIDEO*/
			
			
			
			$where 		= str_replace("AND mode = 7","" ,$where);
			
			array_push($join[1],array('website_mode', 'website_mode.mode_id 	= channel_post_video.mode','left'));
			$where_cond = ' channel_post_video.featured_by_user = 1 AND ' . $where ; 
			
			$field = "channel_post_video.user_id,channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.tag,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.created_at,channel_post_video.is_video_processed";
			
			$channel_video  = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where_cond,1,$join);
			
			if(!isset($channel_video[0]['post_id'])){
				$channel_video  = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,1,$join,$Order);
			}
			
			if(isset($channel_video[0]['post_id']) && !empty($channel_video[0]['post_id'])){
				$vCount   				= 	sizeof($channel_video);
				$index 					= 	0;
				$user_id                = 	$channel_video[$index]['user_id'];
				$post_key 				= 	$channel_video[$index]['post_key'];
				$iva_id   				= 	$channel_video[$index]['iva_id'];
				$up_video 				= 	$channel_video[$index]['uploaded_video'];
				$img_name			  	=	$channel_video[$index]['image_name'];
				$is_vid_processed		= 	$channel_video[$index]['is_video_processed'];
				//$data['single_video'] 	= 	base_url().$this->common->generate_single_content_url_param($post_key , 2);
				
				$FilterData 			= 	$this->share_url_encryption->FilterIva($uid, $iva_id,$img_name,$up_video,false,'.m3u8',$is_vid_processed);
				$data['feature_video']	=	isset($FilterData['video'])?$FilterData['video']:'';
				$data['feature_thumb']	=	isset($FilterData['thumb'])?$FilterData['thumb']:'';
				//$data['mime_type'] 		=	$this->share_url_encryption->mime_type($data['feature_video']);
				$data['feature_uid']  	= 	$user_id;
				$data['feature_pid']  	= 	$channel_video[$index]['post_id'];
				$data['feature_username']  	= 	get_user_fullname($user_id);
				$data['feature_title']  = 	$channel_video[$index]['title'];
				$data['feature_desc']  = 	$channel_video[$index]['description'];
				$data['feature_tag']  = 	$channel_video[$index]['tag'];
				$data['feature_uc_pic']  = 	get_user_image($user_id);
				$data['feature_created_at']  = 	$this->time_elapsed_string($this->manageTimezone($channel_video[$index]['created_at']) ,false);
				$data['featured_isMyFavorite'] =0;
				$data['featured_isVoted'] =0;
				
				if($this->get_token_uid()){
								
					$post_user = array('user_id'=>$this->get_token_uid(),'channel_post_id'=>$channel_video[$index]['post_id']);	
					$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
					if(!empty($isMyFavorite_check)){
						$data['featured_isMyFavorite']=1;
					}
				
					$post_user = array('user_id'=>$this->get_token_uid(),'post_id'=>$channel_video[$index]['post_id']);	
					$isvoted = $this->DatabaseModel->select_data('vote_id','channel_video_vote use INDEX(user_id,post_id)',$post_user,1);
					if(!empty($isvoted)){
						$data['featured_isVoted']=1;
					}
				}
			}
			
			/*END OF LETEST USER CHANNEL VIDEO*/
			
			/*START OF INCOMEPLETE CHANNEL VIDEO*/
			if($this->is_token_uid($uid)){
				$where ='channel_post_video.user_id='.$uid.' AND channel_post_video.complete_status=0 AND channel_post_video.active_status=0 AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1';
				
				$incomplete_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$Order);
				$incomplete_video = $this->swiper_slider($incomplete_video);
				if(!empty($incomplete_video)){
					$incomplete_video[0]['mode'] = 'Incomplete Video';
					$incomplete_video[0]['mode_key'] = 'incomplete_video';
					$data['channel'][] = $incomplete_video[0];
				}
			}
			/*END OF INCOMEPLETE CHANNEL VIDEO*/
			
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'User channel Info.';
		}else{
			 $this->respMessage = 'User channel Info not found.';
		}  						
			
		$this->show_my_response($data);
	}
	
	function getPlaylistId($playlist=[],$mode_id=''){
		$playlist_id = '';
		if(!empty($playlist)){
			$list = $this->audition_functions->searchForId($mode_id,'mode',$playlist);
			if(!empty($list)){
				$playlist_id = $list['playlist_id'];
			}
		}
		return $playlist_id;
	}
	
	/************ My channel video for only tizen app *********/
	public function my_channel_video(){
		$uid = $this->get_token_uid();
		$other_user = (isset($_POST['user_id'])?$_POST['user_id']:'');
		
		if(!empty($other_user)){
			$uid	=	$other_user;
		}
		$limit =(isset($_POST['limit']))?$_POST['limit']:1;
		$data['mychannel'] =array();
		$data_channel['music'] 			= array();
		$data_channel['movies'] 			= array();
		$data_channel['television'] 		= array();
		$data_channel['gaming'] 			= array();
		//$data_channel['incomplete_video'] 	= array();
		//$data['sub_catname'] 		= ''; 
		
		
		$accessParam = array(
						'field' => 'users.user_id,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users_content.uc_type,users.referral_by,users_content.is_video_processed',
						'where' => 'user_id='.$uid,
						);
						
		$userDetail	= $this->query_builder->user_list($accessParam);
		
		if(isset($userDetail['users']) && !empty($userDetail['users'])){
			$userDetail = $userDetail['users'];
			
			//Json decode about me 
			if(isset($userDetail[0]['uc_about']) && !empty($userDetail[0]['uc_about'])){
				
				$userDetail[0]['uc_about'] = json_decode($userDetail[0]['uc_about']);
				
				if(!empty($userDetail[0]['uc_about'])){
					$userDetail[0]['uc_about'] = $this->parseHtml($userDetail[0]['uc_about']);
				}else{
					$userDetail[0]['uc_about'] ='';
				}
			}
					
			/*if(isset($userDetail[0]['referral_by']) && !empty($userDetail[0]['referral_by'])){
				 $referral_name = $this->DatabaseModel->select_data('user_name','users',array('user_uname'=>$userDetail[0]['referral_by']));
				 if(isset($referral_name[0]['user_name']) && !empty($referral_name[0]['user_name'])){
					 $data['referral_name'] =	$referral_name[0]['user_name'];
					 $data['referral_by']  	=	$userDetail[0]['referral_by'];
				 }
			}*/
		
			//get user pic 
			if(isset($userDetail[0]['uc_pic']) && !empty($userDetail[0]['uc_pic'])){
				$uc_pic = $userDetail[0]['uc_pic'];
				$userDetail[0]['uc_pic']  = AMAZON_URL."aud_{$uid}/images/{$uc_pic}";
				
			}else{
				$userDetail[0]['uc_pic'] = base_url('repo/images/user/user.png');
			}
			

			//check is fan button
			if(!empty($this->check_FanButton($uid))){
				$button = $this->check_FanButton($uid);
				
				$userDetail[0]['isfan']=$button['is_fan'];
				//$userDetail[0]['CanIMakeFan']=$button['CanIMakeFan'];
			}
			
			$sub_catname='';
			if(!empty($userDetail[0]['uc_type'])){
				$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category','category_id IN ('.$userDetail[0]['uc_type'].')');
				
				$size = (sizeof($sub_cat) <= 4)?sizeof($sub_cat):4;
				for($i=0;$i < $size; $i++ ){
					$sub_catname .=  $sub_cat[$i]['category_name'].',';
				}
				$userDetail[0]['sub_catname'] = rtrim($sub_catname ,", ");
			}
			
			if(!empty($userDetail[0]['user_regdate'])){
				$userDetail[0]['user_regdate'] = date('F-d-Y',strtotime($userDetail[0]['user_regdate']));
			}
			
			if( !empty($userDetail) ){ 
				$data['userDetail'] = $userDetail;
			}
			 
			$is_vid_processed		=	$userDetail[0]['is_video_processed'];
			$cover 					= 	$userDetail[0]['aws_s3_profile_video'];
			$url					=	"";
			$preview				=	"";
			if(!(empty($cover))){
				$url 				= 	AMAZON_URL .$cover;
				$preview			=	$this->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4',$is_vid_processed);
				$preview			=	isset($preview['video'])?$preview['video']:'';
			}
	
			$data['cover_video'] 	= 	array('url'=>$url,'preview'=>$preview);
		
			$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,mode_of_genre.genre_name,channel_post_video.video_duration,channel_post_video.video_type,channel_post_video.genre,users.user_level,country.country_name";
		
			$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 AND mode = 1 ';
		
			if(!$this->is_token_uid($uid)){
				//$where .='AND channel_post_video.age_restr = "Unrestricted" ';
			}
			
			$globalCond =  $this->GobalPrivacyCond($uid);
		
			$where .=  $globalCond;	  
			
			
			if(isset($_POST['search_keyword']) && !empty($_POST['search_keyword'])){
				$search_keyword = $_POST['search_keyword'];
				$search_keyword = addslashes(validate_input($search_keyword));
				$where .=" AND (channel_post_video.title LIKE '%".$search_keyword."%' OR channel_post_video.description LIKE '%".$search_keyword."%' OR channel_post_video.created_at LIKE '%".$search_keyword."%'  OR channel_post_video.tag LIKE '%".$search_keyword."%')";
			}
		
			$join  = array(
						'multiple',
						array(
							array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
							array('users_content use INDEX(uc_userid)','users_content.uc_userid = users.user_id','left'),
							array('country','country.country_id = users_content.uc_country','left'),
							array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),
						)
					);
		
			/*START OF USER MUSIC CHANNEL VIDEO*/
			$data_channel['music'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,'rand()');
			$data_channel['music'] = $this->swiper_slider($data_channel['music'], true); 
			/*END OF USER MUSIC CHANNEL VIDEO*/
			
			/*START OF USER MOVIE CHANNEL VIDEO*/
			$where = str_replace("mode = 1","mode = 2" ,$where);
			$data_channel['movies'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,'rand()');
			$data_channel['movies'] = $this->swiper_slider($data_channel['movies'], true);
			/*END OF LETEST MOVIE CHANNEL VIDEO*/
			
			/*START OF USER TV CHANNEL VIDEO*/
			$where = str_replace("mode = 2","mode = 3" ,$where);
			$data_channel['television'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,'rand()');
			$data_channel['television'] = $this->swiper_slider($data_channel['television'], true);
			/*END OF USER TV CHANNEL VIDEO*/
			
			/*START OF USER GAMING MODE CHANNEL VIDEO*/
			$where = str_replace("mode = 3","mode = 7" ,$where);
			$data_channel['gaming'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,'rand()');
			$data_channel['gaming'] = $this->swiper_slider($data_channel['gaming'], true);
			/*END OF USER GAMING MODE CHANNEL VIDEO*/
			
			/*START OF LETEST USER CHANNEL VIDEO*/
			
			/*START OF INCOMEPLETE CHANNEL VIDEO*/
			/*if($this->is_token_uid($uid)){
				$where ='channel_post_video.user_id='.$uid.' AND channel_post_video.complete_status=0 AND channel_post_video.active_status=0 AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1';
				
				$data_channel['incomplete_video'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,'rand()');
				$data_channel['incomplete_video'] = $this->swiper_slider($data_channel['incomplete_video'], true);
			}*/
			/*END OF INCOMEPLETE CHANNEL VIDEO*/
			$mychannel = [];
			foreach($data_channel as $key=>$value){
				if(!empty($value)){
					$mychannel[]=array('title'=>$key , 'data' =>$value);
				}
			}
			$data['mychannel'] =$mychannel;
			

			$where 		= str_replace("AND mode = 7","" ,$where);
			
			array_push($join[1],array(	'website_mode', 
												'website_mode.mode_id 	= channel_post_video.mode', 
												'left'));
			$where_cond = ' channel_post_video.featured_by_user = 1 AND ' . $where ; 
			
			$channel_video  = $this->DatabaseModel->select_data('channel_post_video.user_id,channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.tag,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.created_at,channel_post_video.is_video_processed','channel_post_video use INDEX(post_id)',$where_cond,1,$join);
			
			if(!isset($channel_video[0]['post_id'])){
				$channel_video  = $this->DatabaseModel->select_data('channel_post_video.user_id,channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.tag,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.created_at,channel_post_video.is_video_processed','channel_post_video use INDEX(post_id)',$where,1,$join,array('channel_post_video.post_id','DESC'));
			}
			
			
			if(isset($channel_video[0]['post_id']) && !empty($channel_video[0]['post_id'])){
				$vCount   				= 	sizeof($channel_video);
				$index 					= 	0;
				$user_id                = 	$channel_video[$index]['user_id'];
				$post_key 				= 	$channel_video[$index]['post_key'];
				$iva_id   				= 	$channel_video[$index]['iva_id'];
				$up_video 				= 	$channel_video[$index]['uploaded_video'];
				$img_name			  	=	$channel_video[$index]['image_name'];
				$is_vid_processed		= 	$channel_video[$index]['is_video_processed'];
				//$data['single_video'] 	= 	base_url().$this->common->generate_single_content_url_param($post_key , 2);
				
				$FilterData 			= 	$this->share_url_encryption->FilterIva($uid, $iva_id,$img_name,$up_video,false,'.m3u8',$is_vid_processed);
				$data['feature_video']	=	isset($FilterData['video'])?$FilterData['video']:'';
				$data['feature_thumb']	=	isset($FilterData['thumb'])?$FilterData['thumb']:'';
				//$data['mime_type'] 		=	$this->share_url_encryption->mime_type($data['feature_video']);
				$data['feature_uid']  	= 	$user_id;
				$data['feature_pid']  	= 	$channel_video[$index]['post_id'];
				$data['feature_username']  	= 	get_user_fullname($user_id);
				$data['feature_title']  = 	$channel_video[$index]['title'];
				$data['feature_desc']  = 	$channel_video[$index]['description'];
				$data['feature_tag']  = 	$channel_video[$index]['tag'];
				$data['feature_uc_pic']  = 	get_user_image($user_id);
				$data['feature_created_at']  = 	$this->time_elapsed_string($this->manageTimezone($channel_video[$index]['created_at']) ,false);
				$data['featured_isMyFavorite'] =0;
				$data['featured_isVoted'] =0;
				
				if($this->get_token_uid()){
								
					$post_user = array('user_id'=>$this->get_token_uid(),'channel_post_id'=>$channel_video[$index]['post_id']);	
					$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
					if(!empty($isMyFavorite_check)){
						$data['featured_isMyFavorite']=1;
					}
				
					$post_user = array('user_id'=>$this->get_token_uid(),'post_id'=>$channel_video[$index]['post_id']);	
					$isvoted = $this->DatabaseModel->select_data('vote_id','channel_video_vote use INDEX(user_id,post_id)',$post_user,1);
					if(!empty($isvoted)){
						$data['featured_isVoted']=1;
					}
				}
				
				
					
			}
			
			/*END OF LETEST USER CHANNEL VIDEO*/
			
			
			
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'User channel Info.';
		}else{
			 $this->respMessage = 'User channel Info not found.';
		}  						
			
		$this->show_my_response($data);
	}
	
	
	public function my_channel_video_new(){    // currently not used on any platform
		$uid = $this->get_token_uid();
		
		$other_user = (isset($_POST['user_id'])?$_POST['user_id']:'');
		
		if(!empty($other_user)){
			$uid	=	$other_user;
		}
		$limit =(isset($_POST['limit']))?$_POST['limit']:1;
		$data['mychannel'] =array();
		$data_channel['musics'] 			= array();
		$data_channel['movies'] 			= array();
		$data_channel['televisions'] 		= array();
		$data_channel['gamings'] 			= array();
		$data_channel['incomplete_video'] 	= array();
		//$data['sub_catname'] 		= ''; 
		
		
		$accessParam = array(
						'field' => 'users.user_id,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users_content.uc_type,users.referral_by,users_content.is_video_processed',
						'where' => 'user_id='.$uid,
						);
						
		$userDetail	= $this->query_builder->user_list($accessParam);
		
		if(isset($userDetail['users']) && !empty($userDetail['users'])){
			$userDetail = $userDetail['users'];
		
			/*if(isset($userDetail[0]['referral_by']) && !empty($userDetail[0]['referral_by'])){
				 $referral_name = $this->DatabaseModel->select_data('user_name','users',array('user_uname'=>$userDetail[0]['referral_by']));
				 if(isset($referral_name[0]['user_name']) && !empty($referral_name[0]['user_name'])){
					 $data['referral_name'] =	$referral_name[0]['user_name'];
					 $data['referral_by']  	=	$userDetail[0]['referral_by'];
				 }
			}*/
		
			//get user pic 
			if(isset($userDetail[0]['uc_pic']) && !empty($userDetail[0]['uc_pic'])){
				$uc_pic = $userDetail[0]['uc_pic'];
				$userDetail[0]['uc_pic']  = AMAZON_URL."aud_{$uid}/images/{$uc_pic}";
				
			}else{
				$userDetail[0]['uc_pic'] = base_url('repo/images/user/user.png');
			}
			

			//check is fan button
			if(!empty($this->check_FanButton($uid))){
				$button = $this->check_FanButton($uid);
				
				$userDetail[0]['isfan']=$button['is_fan'];
				//$userDetail[0]['CanIMakeFan']=$button['CanIMakeFan'];
			}
			
			$sub_catname='';
			if(!empty($userDetail[0]['uc_type'])){
				$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category','category_id IN ('.$userDetail[0]['uc_type'].')');
				
				$size = (sizeof($sub_cat) <= 4)?sizeof($sub_cat):4;
				for($i=0;$i < $size; $i++ ){
					$sub_catname .=  $sub_cat[$i]['category_name'].',';
				}
				$userDetail[0]['sub_catname'] = rtrim($sub_catname ,", ");
			}
			
			if(!empty($userDetail[0]['user_regdate'])){
				$userDetail[0]['user_regdate'] = date('F-d-Y',strtotime($userDetail[0]['user_regdate']));
			}
			
			if( !empty($userDetail) ){ 
				$data['userDetail'] = $userDetail;
			}
			 
			$is_vid_processed		=	$userDetail[0]['is_video_processed'];
			$cover 					= 	$userDetail[0]['aws_s3_profile_video'];
			$url					=	"";
			$preview				=	"";
			if(!(empty($cover))){
				$url 				= 	AMAZON_URL .$cover;
				$preview			=	$this->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4',$is_vid_processed);
				$preview			=	isset($preview['video'])?$preview['video']:'';
			}
	
			$data['cover_video'] 	= 	array('url'=>$url,'preview'=>$preview);
		
			$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,mode_of_genre.genre_name,channel_post_video.mode";
		
			$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0';
		
			$globalCond =  $this->GobalPrivacyCond($uid);
		
			$where .=  $globalCond;	  
			
			
			if(isset($_POST['search_keyword']) && !empty($_POST['search_keyword'])){
				$search_keyword = $_POST['search_keyword'];
				$search_keyword = addslashes(validate_input($search_keyword));
				$where .=" AND (channel_post_video.title LIKE '%".$search_keyword."%' OR channel_post_video.description LIKE '%".$search_keyword."%' OR channel_post_video.created_at LIKE '%".$search_keyword."%'  OR channel_post_video.tag LIKE '%".$search_keyword."%')";
			}
		
			$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
							array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),
						)
					);
					
					
			$Order = array('channel_post_video.post_id','DESC');
			
			$all  = $this->DatabaseModel->select_data($field,'channel_post_video',$where,'',$join,$Order);
			$alll = $this->swiper_slider($all);
			$data_channel['musics'] 			= $this->filterMode($alll,1);
			$data_channel['movies'] 			= $this->filterMode($alll,2);
			$data_channel['televisions'] 		= $this->filterMode($alll,3);
			$data_channel['gamings'] 			= $this->filterMode($alll,7);
			$data_channel['incomplete_video'] 	= $this->filterMode($alll,'');	
			
			foreach($data_channel as $key=>$value){
				
				$mychannel[]=array('title'=>$key , 'data' =>$value);
			}
			$data['mychannel'] =$mychannel;
			

			$where 		= str_replace("AND mode = 7","" ,$where);
			
			array_push($join[1],array(	'website_mode', 
												'website_mode.mode_id 	= channel_post_video.mode', 
												'left'));
			$where_cond = ' channel_post_video.featured_by_user = 1 AND ' . $where ; 
			
			$channel_video  = $this->DatabaseModel->select_data('channel_post_video.user_id,channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.tag,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.created_at,channel_post_video.is_video_processed','channel_post_video use INDEX(post_id)',$where_cond,1,$join);
			
			if(!isset($channel_video[0]['post_id'])){
				$channel_video  = $this->DatabaseModel->select_data('channel_post_video.user_id,channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.tag,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.created_at,channel_post_video.is_video_processed','channel_post_video use INDEX(post_id)',$where,1,$join,array('channel_post_video.post_id','DESC'));
			}
			
			
			if(isset($channel_video[0]['post_id']) && !empty($channel_video[0]['post_id'])){
				$vCount   				= 	sizeof($channel_video);
				$index 					= 	0;
				$user_id                = 	$channel_video[$index]['user_id'];
				$post_key 				= 	$channel_video[$index]['post_key'];
				$iva_id   				= 	$channel_video[$index]['iva_id'];
				$up_video 				= 	$channel_video[$index]['uploaded_video'];
				$img_name			  	=	$channel_video[$index]['image_name'];
				$is_vid_processed		= 	$channel_video[$index]['is_video_processed'];
				//$data['single_video'] 	= 	base_url().$this->common->generate_single_content_url_param($post_key , 2);
				
				$FilterData 			= 	$this->share_url_encryption->FilterIva($uid, $iva_id,$img_name,$up_video,false,'.m3u8',$is_vid_processed);
				$data['feature_video']	=	isset($FilterData['video'])?$FilterData['video']:'';
				$data['feature_thumb']	=	isset($FilterData['thumb'])?$FilterData['thumb']:'';
				//$data['mime_type'] 		=	$this->share_url_encryption->mime_type($data['feature_video']);
				$data['feature_uid']  	= 	$user_id;
				$data['feature_pid']  	= 	$channel_video[$index]['post_id'];
				$data['feature_username']  	= 	get_user_fullname($user_id);
				$data['feature_title']  = 	$channel_video[$index]['title'];
				$data['feature_desc']  = 	$channel_video[$index]['description'];
				$data['feature_tag']  = 	$channel_video[$index]['tag'];
				$data['feature_uc_pic']  = 	get_user_image($user_id);
				$data['feature_created_at']  = 	$this->time_elapsed_string($this->manageTimezone($channel_video[$index]['created_at']) ,false);
				$data['featured_isMyFavorite'] =0;
				$data['featured_isVoted'] =0;
				
				if($this->get_token_uid()){
								
					$post_user = array('user_id'=>$this->get_token_uid(),'channel_post_id'=>$channel_video[$index]['post_id']);	
					$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
					if(!empty($isMyFavorite_check)){
						$data['featured_isMyFavorite']=1;
					}
				
					$post_user = array('user_id'=>$this->get_token_uid(),'post_id'=>$channel_video[$index]['post_id']);	
					$isvoted = $this->DatabaseModel->select_data('vote_id','channel_video_vote use INDEX(user_id,post_id)',$post_user,1);
					if(!empty($isvoted)){
						$data['featured_isVoted']=1;
					}
				}
			}
			
			/*END OF LETEST USER CHANNEL VIDEO*/
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'User channel Info.';
		}else{
			 $this->respMessage = 'User channel Info not found.';
		}  						
			
		$this->show_my_response($data);
	}
	
	function filterMode($array,$mode){
		$result = array_filter($array, function ($value) use ($mode) {
				return ($value["mode_id"] == $mode);
		});
		return array_values($result);
	}
	/*************** Get Genre list *****************/
	
	public function get_genre_list(){
		header('Access-Control-Allow-Origin: *');
		$resp=array();
		$this->load->library('Valuelist');
		$web_mode = $this->valuelist->mode();
		
		$mode_id = ( isset($_POST['mode_id']) && !is_null($_POST['mode_id']) )? $_POST['mode_id'] : 1 ;
		$limit	 = (isset($_POST['limit']))?$_POST['limit']:20;
		$start   = (isset($_POST['start']))?$_POST['start']:0;
		
		$where ="mode_of_genre.mode_id ={$mode_id} AND mode_of_genre.status=1 AND ";
		
		$where .= "channel_post_video.mode = {$mode_id} AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND  users.user_status = '1'";
				
		//$where .=" AND channel_post_video.genre = {$genre_id}";
		
		$join  = array(
						'multiple',
						array(
							array('channel_post_video','channel_post_video.genre= mode_of_genre.genre_id','left'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
		
		$genre_list =  $this->DatabaseModel->select_data('mode_of_genre.genre_name,mode_of_genre.genre_slug,mode_of_genre.genre_id,mode_of_genre.mode_id,mode_of_genre.image','mode_of_genre use INDEX(genre_id)',$where,array($limit ,$start),$join,array('mode_of_genre.browse_order','ASC'),'','mode_of_genre.genre_id');
		
		
		if(!empty($genre_list)){
			foreach($genre_list as $key=>$value){
				if(!empty($value['image'])){
					$pathToImages = ABS_PATH.'repo_admin/images/genre/';
					if(file_exists($pathToImages.$value['image'])){
						$genre_list[$key]['image']= CDN_BASE_URL.'repo_admin/images/genre/'.$value['image'];
					}else{
						$genre_list[$key]['image']= base_url().'repo/images/thumbnail.jpg';	
					}
				}else{
					$genre_list[$key]['image']= base_url().'repo/images/thumbnail.jpg';	
				}
				
				//Get particular genre latest video only for TIZEN & ANDROIDTV, ROKU 
				if($this->deviceType == 'TIZEN' || $this->deviceType=='ANDROIDTV' || $this->deviceType == 'ROKU'){
					$video = $this->get_single_genre_video($value['genre_id']);
					if(!empty($video[0])){
						$genre_list[$key] = array_merge($genre_list[$key],$video[0]);
					}
				}
			}
			
			$mode = ($web_mode[$mode_id])? $web_mode[$mode_id] : 'music';
			$resp =array('genre_title'=>'Browse '.ucfirst($mode).' Videos By Genres' ,'genre_list'=>$genre_list);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Genre list.';
		}else{
			$this->respMessage = 'Genre not found.';
		}
		$this->show_my_response($resp);
	}
	
	
	/*************** Get Particular Genre single video *****************/
	public function get_single_genre_video($genre_id, $genre_type='main_genre'){
		$genre_video=[];
		if(!empty($genre_id)){
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.post_id,channel_post_video.iva_id, channel_post_video.uploaded_video,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,users.user_level,country.country_name,mode_of_genre.genre_name,channel_post_video.video_type,channel_post_video.description,channel_post_video.genre';
			
			
			$join = array('multiple' , array(
									array(	'channel_post_thumb',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
									array(	'users', 
											'users.user_id 				= channel_post_video.user_id', 
											'inner'),
									array('users_content','users_content.uc_userid = users.user_id','left'),
									array('country','country.country_id = users_content.uc_country','left'),
									
						));
						
			
						
			
			$cond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1';
			
			if($genre_type=='sub_genre'){
				$field .=',channel_post_video.sub_genre';
				$join[1][]= array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.sub_genre','left');
				$cond .=' AND channel_post_video.sub_genre = '.$genre_id.''; 
			}else{
				$join[1][]= array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left');
				$cond .=' AND channel_post_video.genre = '.$genre_id.'';
			}
		
			$order_by = array('channel_post_video.post_id','DESC');
			
			$genre_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,1,$join,$order_by);
			
			if(isset($genre_video[0])){
				$genre_video = $this->swiper_slider($genre_video, true);				
			}
		}
		return $genre_video;
	}
	
	
	
	/*************** Get Sub Genre list *****************/
	
	public function SubGenreSlider($mode_id){
		$where 	= 'mode_id='.$mode_id .' AND status = 1 AND level = 2 AND is_in_slider = 1';
		$subGenre = $this->DatabaseModel->select_data('*,image as ThumbImage,genre_name as title','mode_of_genre use INDEX(genre_id)',$where,8,'','rand()');
		if(!empty($subGenre)){
			foreach($subGenre as $key=>$subGen){
				if(!empty($subGen['ThumbImage'])){
					$pathToImages = ABS_PATH.'repo_admin/images/genre/';
					if(file_exists($pathToImages.$subGen['ThumbImage'])){
						$subGenre[$key]['ThumbImage']=base_url().'repo_admin/images/genre/'.$subGen['ThumbImage'];				
					}else{
						$subGenre[$key]['ThumbImage']= base_url().'repo/images/thumbnail.jpg';	
					}
				}else{
					$subGenre[$key]['ThumbImage']= base_url().'repo/images/thumbnail.jpg';	
				}
				
				//Get particular genre latest video 
				$video = $this->get_single_genre_video($subGen['genre_id'],'sub_genre');
				if(!empty($video[0])){
					unset($video[0]['ThumbImage'],$video[0]['title']);
					$subGenre[$key] = array_merge($subGenre[$key],$video[0]);
				}else{
					unset($subGenre[$key]);
				}
			}
		}
		return array_values($subGenre);
	}
	
	
	
	public function get_subgenre_list(){
		header('Access-Control-Allow-Origin: *');
		$resp=array();
		$this->form_validation->set_rules('parentgenre_id', 'Parentgenre id', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$parentgenre_id = $_POST['parentgenre_id'];
			$subGenre = $this->getSubGenreList($parentgenre_id);
			if(!empty($subGenre)){
				$resp['subgenreList']  = $subGenre;
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Subgenre list.';
			}else{
				$this->respMessage = 'Subgenre not found.';
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response($resp);
	}
	
	
	public function getSubGenreList($parentgenre_id){
		$subGenre=[];
		if(!empty($parentgenre_id)){
			$where 	= 'parent_id='.$parentgenre_id .' AND status = 1 AND level = 2';
			$subGenre = $this->DatabaseModel->select_data('genre_id,genre_name','mode_of_genre use INDEX(genre_id)',$where);
		}
		return $subGenre;
	}
	
	
	public function get_genre_list_old(){
		$resp=array();
		$mode_id = (isset($_POST['mode_id']))? $_POST['mode_id'] : 1 ;
		$genre_list =  $this->DatabaseModel->select_data('mode_of_genre.genre_name,mode_of_genre.genre_slug,mode_of_genre.genre_id,mode_of_genre.mode_id,mode_of_genre.image','mode_of_genre use INDEX(genre_id)',array('mode_of_genre.mode_id'=>$mode_id),'',array('website_mode','website_mode.mode_id = mode_of_genre.mode_id','left'),array('mode_of_genre.browse_order','ASC'));
		
		if(!empty($genre_list)){
			//echo sizeof($genre_list);
			//echo "<br>";
			foreach($genre_list as $key=>$value){
				
				$genre_list[$key]['image']=base_url().'repo_admin/images/genre/'.$value['image'];
				
				$genre_id = $value['genre_id'];	
				
				$where = "channel_post_video.mode = {$mode_id} AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
				
				$where .=" AND channel_post_video.genre = {$genre_id}";
				
				$join  = array(
								'multiple',
								array(
									array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
							);
		
		
			
				$video_result_count = $this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$where,$join);
				 
				if($video_result_count >0){
					$resp[]= $genre_list[$key];
				}
				 
				$where ='';
			}
			//echo sizeof($resp);
			$resp =array('genre_list'=>$resp);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Genre list.';
		}else{
			$this->respMessage = 'Genre not found.';
		}
		$this->show_my_response($resp);
	}
	
	/*************** Set as featured video ********************/
	public function make_feature_video(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
			if ($this->form_validation->run() == TRUE){	
			
				$user_id = $TokenResponce['userid'];
				$table 	= 'channel_post_video ';
				$coloum = 'featured_by_user ';
				
				$cond 	= array('user_id'=>$user_id);
				$this->DatabaseModel->access_database($table,'update',array($coloum=>0),$cond);
				
				$cond 	= array('post_id'=>$_POST['post_id'],'user_id'=>$user_id);
				if($this->DatabaseModel->access_database($table,'update',array($coloum=>1),$cond) > 0){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'You have successfully updated the feature video.';
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	/**************** Update channel video privacy status ****************/
	public function update_status(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
		
			$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');
			$this->form_validation->set_rules('type', 'Type', 'trim|required');
			
			if ($this->form_validation->run() == TRUE){	
				$type = $_POST['type'];
				
				if($type =='channel' ){
					$table 	  = 'channel_post_video';
					$table_id = "post_id";
					$coloum   = "privacy_status";
					
				}else if($type =='socialpost'){
					
					$table 	  = 'publish_data';
					$table_id = "pub_id";
					$coloum   = "pub_status";
				}
				if($this->DatabaseModel->access_database($table,'update',array($coloum=>$_POST['status']),array($table_id=>$_POST['post_id'])) > 0){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Status Updated Successfully.';
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	/*************** Update social post privacy status ***************/
	public function update_social_post_status(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
		
			$this->form_validation->set_rules('pub_id', 'Publish id', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');
			if ($this->form_validation->run() == TRUE){	
				$table 	  = 'publish_data';
				$table_id = "pub_id";
				$coloum   = "pub_status";
					 
				if($this->DatabaseModel->access_database($table,'update',array($coloum=>$_POST['status']),array($table_id=>$_POST['pub_id'])) > 0){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Status Updated Successfully.';
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	/**************** See all channel video ****************/
	public function see_all_mychannel_video(){
		$data=array();
		$this->form_validation->set_rules('mode_id', 'Mode id', 'trim|required');
		if ($this->form_validation->run() == TRUE){	
			$mode_id =$_POST['mode_id'];
			
			$uid = $this->get_token_uid();
			
			$other_user = (isset($_POST['user_id'])?$_POST['user_id']:'');
			
			if(!empty($other_user)){
				$uid	=	$other_user;
			}
			
			$start = (isset($_POST['start']))?$_POST['start'] :0;
			$limit = (isset($_POST['limit']))?$_POST['limit']:20;
			
			$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.genre,channel_post_video.video_duration,channel_post_video.video_type,users.user_level,country.country_name,mode_of_genre.genre_name";
			
			
			$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
							array('users_content','users_content.uc_userid = users.user_id','left'),
							array('country','country.country_id = users_content.uc_country','left'),
							array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id = channel_post_video.genre','left'),
						)
					);
			
			
			if($mode_id=='incomplete_video'){
				
				/*START OF INCOMEPLETE CHANNEL VIDEO*/
				$where ='channel_post_video.user_id='.$uid.' AND channel_post_video.complete_status=0 AND channel_post_video.active_status=0 AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1';
			
				$all_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,'rand()');
				//$data['incomplete_video'] = $this->swiper_slider($data['incomplete_video']);
				/*END OF INCOMEPLETE CHANNEL VIDEO*/
				
			}else{
				
				$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND 	channel_post_video.delete_status = 0 AND mode ='.$mode_id.'';
		
				$globalCond =  $this->GobalPrivacyCond($uid);
		
				$where .=  $globalCond;	  
			
				$all_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,'rand()');
			}
			
			if(!empty($all_video)){
				$data['all_video'] = $this->swiper_slider($all_video, true); 
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'See all channel video.';
			}else{
				$this->respMessage ='Video not found.';
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response($data);
	}
	
	/*************** Get search query content *****************/
	public function get_mysearch_content(){
		$resp=array();
		$this->form_validation->set_rules('search_query', 'Search query', 'trim|required');
		$this->form_validation->set_rules('search_type', 'Search type', 'trim|required');
		if ($this->form_validation->run() == TRUE){	
			$search_type=$_POST['search_type'];
			$search_data = $this->get_my_content($search_type);
			if(!empty($search_data['data'])){
				$resp=array('search_data'=>$search_data['data'],
							'total_count'=>$search_data['total_count']
							);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ='Search content.';
			}
			else {
				$this->respMessage ='Search content not found.';
			}
		
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response($resp);
	}
	
	
	public function get_mysearch_content_new(){
		$resp=array();
		$this->form_validation->set_rules('search_query', 'Search query', 'trim|required');
		//$this->form_validation->set_rules('search_type', 'Search type', 'trim|required');
		if ($this->form_validation->run() == TRUE){	
			 
			$search_video = $this->get_my_content('video');
			$search_people = $this->get_my_content('people');
			
			//if(!empty($search_data['data'])){
				$resp['video']=array('search_data'=>$search_video['data'],
									 'total_count'=>$search_video['total_count']
									);
				$resp['people']=array('search_data'=>$search_people['data'],
									  'total_count'=>$search_people['total_count']
									);			
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ='Search content.';
			/*}
			else {
				$this->respMessage ='Search content not found.';
			}*/
		
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response($resp);
	}

	/******************************* Save / Get Published Content Data STARTS *************************/
	
	function create_social_post() {
		$resp	=	array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('publish_content', 'Publish content', 'trim');
			$this->form_validation->set_rules('publish_id', 'publish id', 'trim|required'); 
			if ($this->form_validation->run() == TRUE){
			
				$uid 				= $TokenResponce['userid'];
				$pub_media 			= ''; 
				$publish_content 	= isset($_POST['publish_content'])? $_POST['publish_content']:''; 
				$publish_input 		= $this->security->xss_clean(validate_input($publish_content));
				$pathToData 		= ABS_PATH.'uploads/aud_'.$uid.'/images/';
				$notiImage 			= ''; 
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
						
						/* if(!empty($name)){
							$notiImage  = AMAZON_URL .'aud_'.$uid.'/images/'.$name;
						} */
						
					}else{
						$this->respMessage = 'Please use right image format.';
						return $this->show_my_response($resp);die;
					}
					
				}else
				if(isset($_POST['video_key']) && !empty($_POST['video_key'])){
					
					$pth 			= "./uploads/aud_".$uid."/images/";
						
					$thumb 			= $this->audition_functions->createThumb(AMAZON_URL.$_POST['video_key'],'690x388',$pth,'jpg');
					
					/*if(!empty($thumb)){
						$vid 	= explode('/',$_POST['video_key'])[2];
						$pub_media 	= $vid.'|video|'.$thumb;
						upload_all_images($uid); 
					}else{
						$this->respMessage = 'Something went wrong ! Please try again.123';
						return $this->show_my_response($resp);die;
					} */
					$vid 	= explode('/',$_POST['video_key'])[2];
					$pub_media 	= $vid.'|video|'.$thumb;
					
					upload_all_images($uid); 
					
					//$notiImage = (isset($thumb))? AMAZON_URL .'aud_'.$uid.'/images/'.$thumb : '';
				}

				if( $_POST['publish_id'] == '0' ) 
				{
					$data_array = array(
						'pub_uid'		=>	$uid,
						'pub_content'	=>	$publish_input,
						'pub_media'		=>	$pub_media,
						'pub_date'		=>	date('Y-m-d H:i:s') 
					);
					
					$pubId = $this->DatabaseModel->access_database('publish_data','insert',$data_array, '');
					
					
					$this->audition_functions->sendNotiOnCreateSocialPost($uid,$pubId,$publish_input,$notiImage,$status= 1);
			
				}else
				{
					$previous_data = $this->DatabaseModel->select_data('pub_media,channel_post_id','publish_data',array('pub_id'=>$_POST['publish_id']),1);
				
					$data_array = array(
						'pub_uid'		=>	$uid,
						'pub_content'	=>	$publish_input
					);
				
				
					if(isset($_POST['remove_media_post']) && $_POST['remove_media_post'] =='yes') {
						
						if( !empty($previous_data) ){
							
							$pre_pub_media 		= $previous_data[0]['pub_media'];
							$channel_post_id 	= $previous_data[0]['channel_post_id'];
							
							$pathToMedia = ABS_PATH.'/uploads/aud_'.$uid.'/images/';

							if( $pre_pub_media != '' ) {
							
								$publish 	= 	explode('|',$pre_pub_media);
								$file 		= 	trim($publish[0]);
							
								if($publish[1] == 'image'){
									s3_delete_object(array(trim('aud_'.$uid.'/images/'.$file)));
									$t = explode('.',$file);
									s3_delete_object(array('aud_'.$uid.'/images/'.$t[0].'_thumb.'.$t[1] ));
								}else{
								
									$old_key = trim('aud_'.$uid.'/videos/'.$file.'');
									$key = explode('.',$old_key)[0];
									
									if (isset($publish[2])) {
										s3_delete_object(array(trim('aud_'.$uid.'/images/'.trim($publish[2]))));
									}
									
									if(empty($channel_post_id)){
										s3_delete_object(array($old_key));
										s3_delete_matching_object(trim($key),TRAN_BUCKET);
									}else{
										
										$channel=$this->DatabaseModel->select_data('uploaded_video','channel_post_video use INDEX(post_id)',array('post_id'=>$channel_post_id),1);
										if(isset($channel[0]['uploaded_video'])){
											$da =$this->DatabaseModel->access_database('channel_post_video','update', array('social'=>0) , array('post_id'=>$channel_post_id));
										}else{
											s3_delete_object(array($old_key));
											s3_delete_matching_object(trim($key),TRAN_BUCKET);
										}
									}
								}
							}
						}
					}
				
					if( (isset($_POST['remove_media_post']) && $_POST['remove_media_post'] =='yes') && empty($pub_media)){
						$data_array['pub_media'] = '';
						$data_array['is_video_processed'] =0;
					}else if((!isset($_POST['remove_media_post']) || isset($_POST['remove_media_post'])) && !empty($pub_media)){
						$data_array['pub_media'] = $pub_media;
						$data_array['is_video_processed'] =0;
					}else{
						$data_array['pub_media'] = $previous_data[0]['pub_media'];
					}
					
					$pubId = $_POST['publish_id'];
					$this->DatabaseModel->access_database('publish_data','update',$data_array,array('pub_id'=>$pubId));
				
				}
				
				$this->statusCode 	= 1;
				$this->statusType = 'Success';
				$this->respMessage ="Social post created successfully.";
			}else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	
	function create_social_post_old() {
		$resp	=	array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('publish_content', 'Publish content', 'trim');
			$this->form_validation->set_rules('publish_id', 'publish id', 'trim|required'); 
			if ($this->form_validation->run() == TRUE){
			
				$uid 				= $TokenResponce['userid'];
				
				$pub_media_upload 	= $this->home_image_video_upload($uid);
				$pub_media 			= ($pub_media_upload !='0') ? $pub_media_upload : '' ;
				 
				$publish_content = isset($_POST['publish_content'])? $_POST['publish_content']:''; 
				$publish_input 	= $this->security->xss_clean(validate_input($publish_content));
			
				if( $_POST['publish_id'] == '0' ) 
				{
					$data_array = array(
						'pub_uid'		=>	$uid,
						'pub_content'	=>	$publish_input,
						'pub_media'		=>	$pub_media,
						'pub_date'		=>	date('Y-m-d H:i:s') 
					);
					$pubId = $this->DatabaseModel->access_database('publish_data','insert',$data_array, '');
			
				}else
				{
					$previous_data = $this->DatabaseModel->select_data('pub_media,channel_post_id','publish_data',array('pub_id'=>$_POST['publish_id']),1);
				
					$data_array = array(
						'pub_uid'		=>	$uid,
						'pub_content'	=>	$publish_input
					);
				
				
					if( isset($_POST['remove_media_post']) && $_POST['remove_media_post'] =='yes') {
						
						if( !empty($previous_data) ){
							
							$pre_pub_media 		= $previous_data[0]['pub_media'];
							$channel_post_id 	= $previous_data[0]['channel_post_id'];
							
							$pathToMedia = ABS_PATH.'/uploads/aud_'.$uid.'/images/';

							if( $pre_pub_media != '' ) {
							
								$publish 	= 	explode('|',$pre_pub_media);
								$file 		= 	trim($publish[0]);
							
								if($publish[1] == 'image'){
									
									s3_delete_object(array(trim('aud_'.$uid.'/images/'.$file)));
									$t = explode('.',$file);
									s3_delete_object(array('aud_'.$uid.'/images/'.$t[0].'_thumb.'.$t[1] ));
									
									/*if (file_exists($pathToMedia.trim($file))) {
										@ unlink($pathToMedia.trim($file));
										$t = explode('.',$file);
										@ unlink($pathToMedia.$t[0].'_thumb.'.$t[1]);
									}*/
								}else{
								
									$old_key = trim('aud_'.$uid.'/videos/'.$file.'');
									$key = explode('.',$old_key)[0];
									
									if (isset($publish[2])) {
										//@ unlink($pathToMedia.trim($publish[2]));
										s3_delete_object(array(trim('aud_'.$uid.'/images/'.trim($publish[2]))));
									}
									
									if(empty($channel_post_id)){
										s3_delete_object(array($old_key));
										s3_delete_matching_object(trim($key),TRAN_BUCKET);
										//s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
									}else{
										
										$channel=$this->DatabaseModel->select_data('uploaded_video','channel_post_video use INDEX(post_id)',array('post_id'=>$channel_post_id),1);
										
										if(isset($channel[0]['uploaded_video'])){
											$da =$this->DatabaseModel->access_database('channel_post_video','update', array('social'=>0) , array('post_id'=>$channel_post_id));
										}else{
											s3_delete_object(array($old_key));
											s3_delete_matching_object(trim($key),TRAN_BUCKET);
											//s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
										}
									}
								}
							}
						}
					}
				
					if( (isset($_POST['remove_media_post']) && $_POST['remove_media_post'] =='yes') && empty($pub_media)){
						$data_array['pub_media'] = '';
						$data_array['is_video_processed'] =0;
					}else if((!isset($_POST['remove_media_post']) || isset($_POST['remove_media_post'])) && !empty($pub_media)){
						$data_array['pub_media'] = $pub_media;
						$data_array['is_video_processed'] =0;
					}else{
						$data_array['pub_media'] = $previous_data[0]['pub_media'];
					}
					
					$pubId = $_POST['publish_id'];
					$this->DatabaseModel->access_database('publish_data','update',$data_array,array('pub_id'=>$pubId));
				
				}
				//$Publishcontent = $this->dashboard_function->get_publish_data();
			
				$this->statusCode 	= 1;
				$this->statusType = 'Success';
				$this->respMessage ="Social post created successfully.";
				//$resp['data'] 		= isset($Publishcontent[0]['post'])?$Publishcontent[0]['post']	:	'' ;
			}else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	
	/**************** Upload post media file image/video **********************/
	function home_image_video_upload($userid=''){
		
		if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
			 
			$uid 	= $userid;
			// if ($this->security->xss_clean($_FILES['userfile']['name'], TRUE) === TRUE)
			// {
			
				if($_FILES['userfile']['type'] == 'video/mp4' || $_FILES['userfile']['type'] == 'video/quicktime')
				{
					$type_format	= 'video';
					$r 				= 1;		 		
				
				}else
				{
					$pathToData 	= ABS_PATH.'uploads/aud_'.$uid.'/images/';
					$type_format 	= 'image';
					$r 	= $this->audition_functions->upload_file($pathToData,'jpg|png|gif|jpeg','userfile',true);
				}
			
				if($r != 0){
					$thumb = '';
					
					if( $type_format == 'image' ) {
						$name 	= $r['file_name'];
						
						$w = $r['w'] ; $h = $r['h'] ; 
						
						if($r['w'] > 660 || $r['h'] > 660){
							$w = 660; 
							$h = 660;
						}
						// check EXIF and autorotate if needed
						$this->load->library('image_autorotate', array('filepath' =>$pathToData.$name));
						
						$this->audition_functions->resizeImage($w ,$h,$pathToData.$name,'',true,false,90);
						$this->audition_functions->resizeImage('100','100',$pathToData.$name,'',false,true);
						
					}
					if( $type_format == 'video' ) {
						$rna 			= $this->common->generateRandomString(20);
						$ext  			= pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
						
						$name 			= $rna.'.'.$ext;
						
						$amazon_path 	= "aud_{$uid}/videos/{$name}";
						$res 			= s3_upload_object_ad($_FILES['userfile']['tmp_name'],$amazon_path);
						
						$pth 			= "./uploads/aud_".$uid."/images/";
						
						$thumb 			= $this->audition_functions->createThumb($res['url'],'690x388',$pth,'jpg');
						
					}
					return $name.'|'.$type_format.'|'.$thumb;
				
				}else
				{
					return '0';
				}
			
			// }else
			// {
				// echo '0';
			// }
			
		}else
		{
			return '0';
		}
	
	}	
	
	/******************************* Save / Get Published Content Data ENDS *************************/

	/**************** Update user profile info *****************/
	public function update_user_info(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
		
			$this->form_validation->set_rules('user_name', 'User name', 'trim|required');
			$this->form_validation->set_rules('user_phone', 'User phone', 'trim|required'); 
			$this->form_validation->set_rules('user_address', 'Address', 'trim|required'); 
			$this->form_validation->set_rules('uc_country', 'Country', 'trim|required'); 
			$this->form_validation->set_rules('uc_state', 'State', 'trim|required'); 
			$this->form_validation->set_rules('uc_city', 'City', 'trim|required'); 
			$this->form_validation->set_rules('uc_gender', 'Gender', 'trim|required'); 
			$this->form_validation->set_rules('uc_dob', 'DOB', 'trim|required'); 
			if ($this->form_validation->run() == TRUE){
				
				$uid = $TokenResponce['userid'];
				
				$this->DatabaseModel->access_database('users','update',array('user_name'=>$_POST['user_name'],'user_phone'=>$_POST['user_phone'],'user_address'=>$_POST['user_address']), array('user_id'=>$uid));
				$this->DatabaseModel->access_database('users_content','update',array('uc_country'=>$_POST['uc_country'],'uc_state'=>$_POST['uc_state'],'uc_city'=>$_POST['uc_city'],'uc_gender'=>$_POST['uc_gender'],'uc_dob'=>$_POST['uc_dob']), array('uc_userid'=>$uid));
					
				syncPlayFabPlayerDisplayName(first($this->UserModel->get($uid)));

				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ='Profile updated successfully.';
					
			}else{
				$this->respMessage =$this->single_error_msg();
			}
			
		}else{
			$this->respMessage = $TokenResponce['message'];
		}	
		$this->show_my_response();
	}
	
	
	/*************** Search Screen video ********************/
	public function search_screen_video(){
		
		$start 	=0; //isset($_POST['start'])?$_POST['start']:0;
		$limit 	=1; //isset($_POST['limit'])?$_POST['limit']:2;  
		$data['most_popular_videos'] 	= array();
		$data['top_videos_ofthe_month'] = array();
		$data['new_realeased_video'] 	= array();
		
		$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,users.user_level";
		
		$where = 'channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status IN(7)';
			  
		$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
		$order=array('channel_post_video.post_id','DESC');
		
		/*START OF NEW REALEASED CHANNEL VIDEO*/
		$data['new_realeased_video'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
		$data['new_realeased_video'] = $this->swiper_slider($data['new_realeased_video']); 
		/*END OF NEW REALEASED CHANNEL VIDEO*/
			
		
		/*START OF TOP VIDEO OF THE MONTH CHANNEL VIDEO*/
		 
		$where .=" AND channel_post_video.created_at LIKE '%".date('Y-m')."%'";
		$order=array('channel_post_video.count_views','DESC');
		$data['top_videos_ofthe_month'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
		$data['top_videos_ofthe_month'] = $this->swiper_slider($data['top_videos_ofthe_month']);
		/*END OF TOP VIDEO OF THE MONTH CHANNEL VIDEO*/
		
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = 'Search screen video.';
		  						
			
		$this->show_my_response($data);
	}
	
	/*************** See All Video Of Search Screen ****************/
	public function see_search_screen_video(){
		$data=array();
		
		$this->form_validation->set_rules('video_type', 'Video type', 'trim|required'); 
		if ($this->form_validation->run() == TRUE){
			$start 	=isset($_POST['start'])?$_POST['start']:0;
			$limit 	=isset($_POST['limit'])?$_POST['limit']:4; 
			
			$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,users.user_level";
		
			$where = 'channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status IN(7)';
			  
			$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
			
			$order='';
			if($_POST['video_type'] =='new_realeased'){
				$order=array('channel_post_video.post_id','DESC');
			}
			if($_POST['video_type'] =='top_of_the_month'){
				$where .=" AND channel_post_video.created_at LIKE '%".date('Y-m')."%'";
				$order=array('channel_post_video.count_views','DESC');
			}
			
			$data['all_video'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
			
			if(!empty($data['all_video'])){
				$data['all_video'] = $this->swiper_slider($data['all_video']); 
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Search screen video.';	
			}else{
				$this->respMessage = 'Video not found.';	
			}

		}else{
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($data);
	}
	
	/*********** Count notification ***********/
	function get_notification_count(){
		$notification_count ='0';
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$user = $TokenResponce['userid'];
			
			$cond = array('to_user'=>$user,'view_status'=>0);
		
			//$notifications = $this->DatabaseModel->select_data('users.user_id,users.user_uname,users.user_name,notifications.*','notifications',$cond ,'',array('users','users.user_id = notifications.from_user'),array('noti_id','DESC'));
			
			$notification_count  = $this->DatabaseModel->aggregate_data('notifications','notifications.noti_id','COUNT',$cond,array('users','users.user_id = notifications.from_user'));
		
		}
		return $notification_count;
	}

	
	/************** Get notification ***************/
	public function get_notification(){
		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$user = $TokenResponce['userid'];
			
			$start 	=isset($_POST['start'])?$_POST['start']:0;
			$limit 	=isset($_POST['limit'])?$_POST['limit']:10; 
			$type 	=isset($_POST['type'])?$_POST['type']:null;
			
			$cond = array('to_user'=>$user);
		
			if(!empty($type)){
				$cond['noti_type'] = $type;
			}
		
			$this->DatabaseModel->update_data('notifications',array('view_status'=>1),$cond);
						
			$notifications = $this->DatabaseModel->select_data('users.user_id,users.user_uname,users.user_name,notifications.*',' notifications',$cond ,array($limit,$start),array('users','users.user_id = notifications.from_user'),array('noti_id','DESC'));
		
			if(isset($notifications[0])){
				$noti_Arr=[];
				foreach($notifications as $notify){
					$post_id ='';
					$mess 	= $this->audition_functions->getNotiStatus($notify['noti_status'],$notify['noti_type']);
					$link 	= $this->audition_functions->getNotiLink($notify['noti_status'],$notify['noti_type'],$notify['reference_id']);
				
				
					$sharedProfileName 	= '';
					if(($notify['noti_status']  == 1 || $notify['noti_status']  == 3) && $notify['noti_type'] == 4){         /*for sharing prfile and channel*/
						$sharedProfileName 	= $this->audition_functions->getSharedProfileName($notify['noti_status'],$notify['noti_type'],$notify['reference_id']);
					}
				
					$channelTitleName 	= '';
					if($notify['noti_status']  == 4 && $notify['noti_type'] == 4){     /*for sharing official channel video*/
						$channelTitleName 	= $this->audition_functions->getChannelTitleName($notify['noti_status'],$notify['noti_type'],$notify['reference_id']);
					}
				
					$pro_words = !empty($sharedProfileName)? $sharedProfileName : $channelTitleName;
					
					
					$comment_text='';
					$ThumbImage ='';
					$post_id='';
					
					if($notify['noti_type'] == 2 ){
						
						$comments_Details = $this->DatabaseModel->select_data('com_pubid,com_text',COMMENTS,array('com_id'=>$notify['reference_id']),1);
						if(!empty($comments_Details[0])){
							$comment_text = $comments_Details[0]['com_text'];
							$post_id 	  = $comments_Details[0]['com_pubid'];
						}
					}
				
					if($notify['noti_type'] == 3 ){	
						$post_id = $notify['reference_id'];
					}
					
					if(!empty($post_id)){
					
						$cond = array('pub_id'=>$post_id);
						$publish_content = $this->DatabaseModel->select_data('publish_data.	pub_uid,publish_data.pub_media',PUBLISH_DATA.' use INDEX(pub_status,pub_uid)',$cond,1);
						
						if(!empty($publish_content) && !empty($publish_content[0]['pub_media'])){
							$pub_media = $publish_content[0]['pub_media'];
							$pub_uid = $publish_content[0]['pub_uid'];
							if(!empty($pub_media)){
								$p_data = explode('|',$pub_media);
								 
								$display_content = trim($p_data[0]);
								$pub_format = $p_data[1];
						
								$ThumbImage =  base_url('repo/images/thumbnail.jpg');
								
								if(sizeof($p_data) == 3){
									//$ThumbImage = base_url()."uploads/aud_".$pub_uid."/images/".$p_data[2];
									$ThumbImage = AMAZON_URL.'aud_'.$pub_uid.'/images/'.$p_data[2];
								}
									
								if($pub_format =='image'){
									
									//$firstPath = base_url().'uploads/aud_'.$pub_uid;
									
									$imgData = explode('.',$display_content);
									//$ThumbImage = $firstPath.'/images/'.trim($imgData[0]).'_thumb.'.$imgData[1];
									$ThumbImage = AMAZON_URL.'aud_'.$pub_uid.'/images/'.trim($imgData[0]).'_thumb.'.$imgData[1];

								}
							}
						}
					}else{
						$post_id = $notify['reference_id'];
					}
					
					if(!empty($link)){
						$regex = '/https?\:\/\/[^\",]+/i';
						preg_match_all($regex, $link, $linkArr);
					}
					
					
					$notifications= array(	'user_id'	=>$notify['user_id'],
											'post_id'   =>$post_id,	
											'noti_id'   =>$notify['noti_id'],	
											'user_name'	=>$notify['user_name'],
											'user_uname'=>$notify['user_uname'],
											'user_pic'	=>get_user_image($notify['from_user']),
											'link'		=>isset($linkArr[0][0]) ? $linkArr[0][0] : '',
											'message'	=>$mess,
											'created_at'=>$this->time_elapsed_string($this->manageTimezone($notify['created_at']),false),
											'pro_words'=>$pro_words,
											'com_text'=>$comment_text,
											'thumbImage' =>$ThumbImage,
											'noti_status'=>$notify['noti_status'],
											'noti_type'=>$notify['noti_type']
										);
				
					array_push($noti_Arr, $notifications);
				
				}
			
				$resp=array('notifications'=>$noti_Arr);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ='Notification list.';
				
			}else{
				$this->respMessage ='No notification available.';
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		}	
		$this->show_my_response($resp);
	}
	
	/************** Clear all notification ****************/
	public function clear_notification(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$user = $TokenResponce['userid'];
			$this->DatabaseModel->access_database('notifications','delete','', array('to_user'=>$user));
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage ='Notification clear.';
		}else{
			$this->respMessage = $TokenResponce['message'];
		}	
		$this->show_my_response();
	}
	
	/************* Get my fan list **************/
	function get_my_fanlist(){
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			
			$uid = $TokenResponce['userid'];
			
			$field = 'users.user_name,users.user_level,become_a_fan.user_id,users_content.uc_pic';
			
			$join = array('multiple' , array(
								array(	'users_content', 
										'users_content.uc_userid = become_a_fan.user_id', 
										'left'),
								array(	'users',
										'users.user_id 			= become_a_fan.user_id',
										'left'),
								));
			$data = $this->DatabaseModel->select_data($field,'become_a_fan use INDEX (following_id)',array('become_a_fan.following_id'=>$uid,'users.user_name !='=>''),50,$join);
			
			if(!empty($data)){  
				$this->load->library('Valuelist');
				$user_level = $this->valuelist->level();
				foreach($data as $key=>$user){
					$data[$key]['uc_pic'] = create_upic($user['user_id'],$user['uc_pic']);
					
					if(isset($user['user_level'])){
						//$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category', array('category_id'=>$user['user_level']));
						//$user_cate=isset($sub_cat[0]['category_name'])?$sub_cat[0]['category_name'] :'';
						
						$data[$key]['category']= isset($user_level[$user['user_level']])? $user_level[$user['user_level']] : '';
					}
				}
				$resp =array('data'=>$data);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage ='Fan List.';
			}else{
				$this->respMessage ='Fan not found.';
			}
			
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	/************** Get Social Post Only *****************/ 
	public function get_social_post(){
		$data =array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('pub_uid', 'Publish user id', 'trim|required');
			//$this->form_validation->set_rules('type', 'Type', 'trim|required');
			
			if($this->form_validation->run() == TRUE){ 
				$_POST['social']=1;
				$data['cover_video']  = $this->getAndroidIosCoverVideo('homepage','social');
				$data['cover_video_multiple'] = $data['cover_video'];
				if($this->deviceType =='ANDROID'){
					$data['cover_video']  = $data['cover_video'][0];
				}

				$publish_post 		  = $this->GetPublishPost();
				$data['publish_post'] = $publish_post['post'];
				$data['post_count']   = $publish_post['post_count'];
				/*$postDetails = $this->get_social_post_content();
				$data['publish_post'] 	= $postDetails['publish_post'];
				$data['total_post'] 	= $postDetails['total_post']; */
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Social post.';
				 
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($data);
	}
	
	
	
	public function see_all_mypublish_content(){
		$data =array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('pub_uid', 'Publish user id', 'trim|required');
			$this->form_validation->set_rules('type', 'Type', 'trim|required');
			if($this->form_validation->run() == TRUE){ 
				$postDetails 		  = $this->get_social_post_content();
				$data['publish_post'] = $postDetails['publish_post'];
				$data['total_post']   = $postDetails['total_post'];
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($data);
	}
	
	
	/**************** Get Social Post ***************/
	public function get_social_post_content(){
		
		$strArr		= [];
		$social 	= isset($_POST['social'])?$_POST['social']:null;
		$uid 		= isset($_POST['pub_uid'])?$_POST['pub_uid']:'' ;
		$start 		= isset($_POST['start'])?$_POST['start']:0;
		$limit 		= isset($_POST['limit'])?$_POST['limit']:2;
		$type 		= isset($_POST['type'])?$_POST['type']:'video';
		
		if($social ==1){
			
			$following = $this->DatabaseModel->select_data('user_id',BECOME_A_FAN.' use INDEX (following_id)',array('following_id'=>$uid));
        	// $fids = $uid;
        	$fids = '';
			if(isset($following[0])){
				foreach($following as $fid){
					$fids .= ','.$fid['user_id']; 
				}
			}
			
			if(!empty($fids)){
				$cond = 'publish_data.pub_status IN(6,7) AND publish_data.pub_uid IN('.trim($fids, ',').') ';
			}else{
				return '';
			}	
		}else{
			$cond = "publish_data.pub_status IN(6,7) AND publish_data.pub_uid = {$uid}";
		}
		
		if($type =='video' || $type =='image'){
			$cond .=" AND publish_data.pub_media LIKE '%{$type}%'";
		}else if($type=='blog'){
			$cond .=" AND publish_data.pub_media =''";
		}
		
		
		//if(isset($_POST['publish_id']) && $_POST['publish_id'] != 0 && empty($publish_id)){   /*IN CASE OF UPDATE*/
		//	$cond = array('publish_data.pub_id'=>$_POST['publish_id']);
		//}else 
		//if(!empty($publish_id)){
		//	$cond = array('publish_data.pub_id'=>$publish_id);     /*In case of shared post*/
		//}
		
		
		
		$join = array('multiple' , array(
									array(	'users', 
											'users.user_id 	= publish_data.pub_uid',
											'left'),
									array(	'users_content', 
											'users_content.uc_userid= users.user_id ',
											'left'),
									));
		
		$publish_content = $this->DatabaseModel->select_data('publish_data.*,users.user_name,users.user_uname,users_content.uc_gender,users_content.uc_pic',PUBLISH_DATA.' use INDEX(pub_status,pub_uid)',$cond,array($limit ,$start),$join,array('publish_data.pub_id','desc'));
		 
		//$total_publish_content = $this->DatabaseModel->select_data('publish_data.*,users.user_name,users.user_uname,users_content.uc_gender',PUBLISH_DATA.' use INDEX(pub_status,pub_uid)',$cond,'',$join,array('publish_data.pub_id','desc')); 
		
		//echo sizeof($total_publish_content);

		$total_publish_content = $this->DatabaseModel->aggregate_data('publish_data','publish_data.pub_id','COUNT',$cond,$join);
		
		
		if(isset($publish_content) && !empty($publish_content)){
			
			foreach($publish_content as $key=>$content){
				
				$pubId 		 = $content['pub_id'];
				$pub_uid 	 = $content['pub_uid'];
				$pub_media 	 = $content['pub_media'];
				$pub_content = $content['pub_content'];
				$pub_reason  = $content['pub_reason'];
				$user_uname  = $content['user_uname'];
				$user_name   = $content['user_name'];
				$uc_gender   = $content['uc_gender'];
				$share_pid   = $content['share_pid'];
				$share_uid   = $content['share_uid'];
				$user_pic    = $content['uc_pic'];
				
				$is_video_processed   			   = $content['is_video_processed'];
				$publish_content[$key]['user_pic'] = create_upic($pub_uid,$user_pic);
				$publish_content[$key]['pub_date'] = ''; //$this->time_elapsed_string($this->manageTimezone($content['pub_date']),false);
				
				$shared_data = [];
				$ShareMeNow  = $pubId;
				if(!empty(trim($share_pid))){
					/*$shared_data = $this->GetPublishPost($share_pid);*/   /*In case of shared post*/
					//$shared_data = !empty($shared_data)?$shared_data:'';
					
					/*if(empty($shared_data)){
						$shared_data = 'Sorry,this content isn\'t available right now.';
					}*/
					$ShareMeNow  = $share_pid;
				}
				
				$pub_format = '';
				$display_content='';
				if(!empty($pub_media)){
					$p_data = explode('|',$pub_media);
					$display_content = trim($p_data[0]);
					$pub_format = $p_data[1];
					
					$ThumbImage =  base_url('repo/images/thumbnail.jpg');
						if(sizeof($p_data) == 3){
							$ThumbImage = base_url()."uploads/aud_".$pub_uid."/images/".$p_data[2];
						}
					$publish_content[$key]['ThumbImage'] = $ThumbImage;	
				}
				
				if( $pub_format == 'video' ) {
					$this->load->library('share_url_encryption');
					$Filter = $this->share_url_encryption->FilterSocialVideo($pubId,$pub_uid,$display_content,$is_video_processed);
					
					$publish_content[$key]['display_content'] = $Filter['video'];
					$ext  = pathinfo($Filter['video'] , PATHINFO_EXTENSION);
					$previewFile=$Filter['video'];
					if( $ext == 'm3u8'){
						 $previewFile = str_replace($ext,'mp4',$Filter['video']);
					}
					$publish_content[$key]['previewFile'] = $previewFile;
				}elseif( $pub_format == 'image' ) {
					
					$publish_content[$key]['display_content'] = base_url().'uploads/aud_'.$pub_uid.'/images/'.$display_content;
					$publish_content[$key]['previewFile'] = '';
				}else{
					
					$publish_content[$key]['display_content'] = $display_content;
					$publish_content[$key]['previewFile'] = '';
				}
				
					$publish_content[$key]['likes'] 		 = $this->like($pubId);
					$publish_content[$key]['isliked']		 = 0;
					if(!empty($this->get_token_uid())){
						$publish_content[$key]['isliked'] 		 = ($this->dashboard_function->get_total_likes($pubId,$this->get_token_uid()) =='yes') ? 1:0;
					}
					$publish_content[$key]['comments_count'] = $this->dashboard_function->get_main_commets_count($pubId);
					$publish_content[$key]['pub_format'] 	 = $pub_format;
					$publish_content[$key]['ShareMeNow'] 	 = $ShareMeNow;
					$publish_content[$key]['SharedPost'] 	 = $shared_data;
					$publish_content[$key]['publish_reason'] = $this->get_publish_reason($pub_reason,$uc_gender,$share_uid);
			}
		}
		return array('publish_post'=>$publish_content,'total_post'=>$total_publish_content);
		
	}
	
	
	
	/************ Share Post To Me ************/
	public function share_post_to_me(){
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$this->form_validation->set_rules('share_pid', 'Share post id', 'trim|required');
			if($this->form_validation->run() == TRUE){ 

				$uid= $TokenResponce['userid'];

				$share_array = array(
									'pub_uid' 	=>  isset($_POST['share_uid'])?$_POST['share_uid'] : $uid,
									'pub_reason'=>	2,
									'pub_date'	=>	date('Y-m-d H:i:s'),
									'pub_status'=>  isset($_POST['share_uid'])? 5 :7,
									'share_pid'	=>  $_POST['share_pid'],
									'share_uid'	=>	$uid
 								);
				$this->DatabaseModel->access_database('publish_data','insert',$share_array);				
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Post share successfully.';
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	
	/*************** Share on discovered as notification ****************/
	public function shareon_discovered_asnoti(){
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
			$this->form_validation->set_rules('reference_id', 'Reference id', 'trim|required');
			$this->form_validation->set_rules('share_status', 'Share status', 'trim|required');
			
			if($this->form_validation->run() == TRUE){
				
					$from_uid 	  = $TokenResponce['userid'];
					$to_user 	  = $_POST['user_id'];
					$reference_id = $_POST['reference_id'];
					$share_status = $_POST['share_status'];
					$where_array = array(	'noti_type'		=>	4,
											'noti_status'	=>	$share_status,
											'from_user'		=>	$from_uid,
											'to_user'		=>	$to_user,
											'reference_id'	=>	$reference_id,
										);
					$this->audition_functions->deleteNoti($where_array);
					
					$where_array['created_at'] = date('Y-m-d H:i:s');
					$insert_array = $where_array;
					
					$this->audition_functions->insertNoti($insert_array);
					
					/* START send firebase notification*/
					$token = $this->audition_functions->getFirebaseToken($to_user);
					$link  = $this->audition_functions->getNotiLink($share_status,4,$reference_id,true);
					
					if(!empty($token)){
						$mess 		= $this->audition_functions->getNotiStatus($share_status,4);
						$fullname 	= $this->audition_functions->get_user_fullname($from_uid);
						$profil_of 	= $this->audition_functions->getSharedProfileName($share_status,4,$from_uid,$reference_id);
						$msg_array 	=  [
							'title'	=>	$fullname .' '. $mess .' '. $profil_of,
							'body'	=>	'',
							'icon'	=>	base_url('repo/images/firebase.png'),
							'click_action'=>$link
						];
						$this->audition_functions->sendNotification($token,$msg_array);
						
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'It\'s done.';
						
					}else{
						$this->respMessage = 'push notification failed.';
					}
					
			}else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	
	
	/************** Reset user password **************/
	public function update_user_password(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
		
			$this->form_validation->set_rules('old_password', 'Old password', 'trim|required');
			$this->form_validation->set_rules('new_password', 'New password', 'trim|required');
			$this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[new_password]');
			if($this->form_validation->run() == TRUE){
				$uid = $TokenResponce['userid'];
				if($_POST['new_password'] == $_POST['confirm_password']){
					
					$user_password = $this->DatabaseModel->select_data('user_password','users',array('user_id'=>$uid)); 
					if($user_password[0]['user_password'] == md5($_POST['old_password'])){
						$this->DatabaseModel->access_database('users','update',array('user_password'=>md5($_POST['new_password'])), array('user_id'=>$uid)) ;
						
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Password reset successfully.';
						
					}else{
						$this->respMessage = 'Old password entered is incorrect.';
					}
				}else{
					$this->respMessage = 'Confirm Password entered isn\'t match.';
				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}
			 
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	
	/************************************** API Common Functions STARTS ***************************************/
	
	public function is_token_uid($user_id=''){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1) {	
			$uid = $TokenResponce['userid'];
			if($uid == $user_id){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function get_token_uid(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1) {	
			$uid = $TokenResponce['userid'];
			return $uid;
		}else{
			return false;
		}
	}
	
	function get_mode_name($mode_id){
		$checkMode = $this->DatabaseModel->select_data('mode_id,mode','website_mode',array('mode_id'=>$mode_id , 'status' => 1),1);
		if(!empty($checkMode)){
			return $checkMode[0]['mode'];
		}
	}
	
	function check_FanButton($uid){
		
		if($this->get_token_uid()){
			if($this->check_CanIFollowHim($uid) == 'YES'){
				$u = ($this->get_token_uid())?$uid:''; 
				
				$AmIFanOfHim = $this->AmIFollowingHim($uid);
				
				$fan = (isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim))?1:0;
				
				$button = array('is_fan'=>$fan,'CanIMakeFan'=>1); //'<a href="javascript:;" class="dis_fanbtn dis_bgclr_orange becomeFan" data-uid="'.$u.'">'.$fan.'</a>';
			}else{
				$button =array('is_fan'=>0,'CanIMakeFan'=>0);
			}
		}else{
			$button = array('is_fan'=>0,'CanIMakeFan'=>0);
		}
		return $button;			
	}
	
	function check_CanIFollowHim($uid){
		
		if($uid !==$this->get_token_uid()){
			/* $other 	= WhoAmI($uid);
			$self 	= WhoAmI($this->get_token_uid());
			
			$case = (int) $self.$other;
			return follow_fan_access_level($case); */
			return 'YES';
		}
		return 'NO';
	}
	
	
	public function AmIFollowingHim($uid){
		return $AmIFanOfHim = $this->DatabaseModel->select_data('fan_id',BECOME_A_FAN.' use INDEX(user_id,following_id)',array('user_id'=>$uid,'following_id'=>$this->get_token_uid()));
	}

	public function like($pubId){
	
		$user_like 	= 	$this->dashboard_function->get_total_likes($pubId,$this->get_token_uid());
		$count_like = 	$this->dashboard_function->get_total_likes($pubId);
		$like_name 	=	$this->dashboard_function->like_name($pubId);
			
			if( $user_like == 'yes' ) {
					
				$wholike = $like_name; 
				
				if($count_like > 1) { 
					$wholike .= ' & '; 
				} 
				if($count_like > 1) { 
					$wholike .= ($count_like-1) .' '. 'others'; 
				}
					$wholike .= ' Love it';	
			}else{
					
				$wholike = '';
				
				if($count_like > 0) { 
					$wholike = $like_name; 
				}
				if($count_like > 1) { 
			
					$wholike .= ' & '. ($count_like -  1).' '. 'others'; 
				} 
					$wholike .= ' Love it';	
			}
			return $wholike;
	}
	
	public function get_commets(){
     	$limit 			= 5;
		$uid 			= isset($_POST['pub_uid'])?$_POST['pub_uid']:'';
		$pub_id 		= isset($_POST['pub_id'])?$_POST['pub_id']:'';
		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:$limit;
		
		$data=array();		
		
		$comments_Details = $this->DatabaseModel->select_data('*',COMMENTS.' use INDEX(com_pubid,com_parentid)',array('com_pubid'=>$pub_id ,'com_parentid'=> 0),array($limit+1,$start),'',array('com_id','DESC')); 
		
		if(!empty($comments_Details)) {
			$checkCount = 1;
			foreach($comments_Details as $solo_comments ) {
				
				if($checkCount++ <= $limit){
					$where = array('com_pubid'=>$pub_id,'com_parentid'=>$solo_comments['com_id']);
					$count = $this->DatabaseModel->aggregate_data(COMMENTS.' use INDEX(com_pubid,com_parentid)','com_id','COUNT', $where);
					$countReply =0;
					
					if($count>0){
						$countReply =$count.' reply';
					}
					
					$cuser = get_user($solo_comments['com_uid']);
					$solo_upic =(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))?create_upic($solo_comments['com_uid'],$cuser[0]['uc_pic']) : '';
					//$solo_uname =(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
					$solo_name =(isset($cuser[0]['user_name']) && !empty($cuser[0]['user_name']))? $cuser[0]['user_name'] : '';
					
					$comment_Data = array(	'com_id'		=>$solo_comments['com_id'],
											'com_pubid'		=>$solo_comments['com_pubid'],
											'com_parentid'	=>$solo_comments['com_parentid'],
											'com_uid'		=>$solo_comments['com_uid'],
											'user_pic'		=>$solo_upic, //get_user_image($solo_comments['com_uid']),
											'user_name'		=>$solo_name, //get_user_fullname($solo_comments['com_uid']),
											'comment_date'	=>$this->time_elapsed_string($this->manageTimezone($solo_comments['com_date']) ,false),
											'comment_text'	=>$solo_comments['com_text'],
											'countReply'	=>$countReply
										);
																			
					array_push($data,$comment_Data);
				}
			}
		}
		return $data;
	}
	
	public function get_commets_reply(){
     	$limit 			= 5;
		$uid 			= isset($_POST['pub_uid'])?$_POST['pub_uid']:'';
		$pub_id 		= isset($_POST['pub_id'])?$_POST['pub_id']:'';
		$parent_id 		= isset($_POST['parent_id'])?$_POST['parent_id']:'';
		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:$limit;
		
		$data=array();		
		
		$reply_details = $this->DatabaseModel->select_data('*',COMMENTS.' use INDEX(com_pubid,com_parentid)',array('com_pubid'=>$pub_id ,'com_parentid'=>$parent_id),array($limit+1,$start),'',array('com_id','DESC'));
		
		if(!empty($reply_details)){ 
			$checkCount = 1;
			foreach($reply_details as $solo_reply) {
				if($checkCount++ <= $limit){
					$uid 			= 	$solo_reply['com_uid'];
					$com_id 		= 	$solo_reply['com_id'];
					$com_parentid 	=	$solo_reply['com_parentid'];
					$com_text 		= 	$solo_reply['com_text'];
					$pub_id 		= 	$solo_reply['com_pubid'];
					$com_date 		=	$solo_reply['com_date'];
					
					$cuser = get_user($uid);
					$solo_upic =(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))?create_upic($uid,$cuser[0]['uc_pic']) : '';
					//$solo_uname =(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
					$solo_name =(isset($cuser[0]['user_name']) && !empty($cuser[0]['user_name']))? $cuser[0]['user_name'] : '';		
					
					
					$where = array('com_pubid'=>$pub_id,'com_parentid'=>$com_id);
					$count = $this->DatabaseModel->aggregate_data(COMMENTS.' use INDEX(com_pubid,com_parentid)','com_id','COUNT', $where);
					$countReply=0;
					
					if(isset($_POST['reply']) && $_POST['reply'] != 'no'){
						if($count>0){
							$countReply = $count.' reply';
						}
					}
					
					$comment_reply_Data = array('com_id'		=>$com_id,
												'com_pubid'		=>$pub_id,
												'com_parentid'	=>$com_parentid,
												'com_uid'		=>$uid,
												'user_pic'		=>$solo_upic, //get_user_image($uid),
												'user_name'		=>$solo_name, //get_user_fullname($uid),
												'comment_date'	=>$this->time_elapsed_string($this->manageTimezone($com_date) ,false),
												'comment_text'	=>$com_text,
												'countReply'	=>$countReply
												);
																			
					array_push($data,$comment_reply_Data);
				}
			}
		}			
		return $data;
	}
	
	
	
	/******** Get publish post reason *********/
	public function get_publish_reason($res_status,$gender_type,$share_uid){
		$message = '';
		if($res_status == 1){   
			$message = 'updated '. $this->dashboard_function->get_gender($gender_type)[1] . ' Profile picture.';
		}else 
		if($res_status == 2){ 
			$message = 'Shared by '.get_user_fullname($share_uid) .'.';
		}
		
		return $message;
	}
	
	
	function filter_my_all_video($type , $mode){
		$videoData = array();
		$resp =array();
		if($type == 'all'){
			$cond = '';
			$dataLimit = 3;
		}else{
			$cond = array('type' => $type);
			$dataLimit = 1;
		}
		$freshVideoData = $this->DatabaseModel->select_data('*' , 'site_main_data' , $cond , $dataLimit);

		$limit = 8;
		if(!empty($freshVideoData)){
			foreach($freshVideoData as $vData){
				$vidList 	= json_decode($vData['data'] , true)[$mode];
				$vidList 	= explode(',',$vidList);
				$limit	 	= (sizeof($vidList) <= $limit)? sizeof($vidList) : $limit; 
				$keys 		= array_rand($vidList ,$limit) ;
				$values=[];
				for($i=0;$i< sizeof($keys);$i++){
					array_push($values,$vidList[$keys[$i]]);
				}

				$vidList = implode(',',$values);
				
				$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed";
				$where = "channel_post_video.post_id IN($vidList) AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND users.user_status = '1'";
						   
				$join  = array(
								'multiple',
								array(
									array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
							);
				
				$caloum = ($vData['type'] == 'new_release_video')?'post_id':'count_views';			
				
				$order = array($caloum,'DESC');
				
				$videoData[$vData['type']] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
			}
			$resp['popular_video'] 		= $this->swiper_slider($videoData['popular_video']);
			$resp['month_top_video'] 	= $this->swiper_slider($videoData['month_top_video']);
			$resp['new_release_video'] 	= $this->swiper_slider($videoData['new_release_video']);
		}
		return $resp;
	}
	
	public function featuredVideo($mode_id){
		$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.bimage_name,channel_post_video.iva_id,users.user_name,users.user_uname,channel_post_video.uploaded_video,channel_post_video.created_at,channel_post_video.is_video_processed";
		
		$where = "channel_post_video.mode = {$mode_id} AND channel_post_video.featured_by_admin = 1 AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
					   
		$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
			
		$featured =  $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,8,$join,'');
		$data=[];
		foreach($featured as $feature){
			$creator = $feature['user_name'].'|'.$feature['user_uname'].'|'.$mode_id;
			$data[$creator][] = $feature;
		}
		
		foreach($data as $key=>$value){
			$data[$key]= $this->swiper_slider($value);
		}
		return $data;
	}
	
	public function RandoeGenreHomeVideo($mode_id){
			$popular_video= array();
			$accessParam = array(
							'field' => 'channel_post_video.post_id,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.slug,channel_post_video.genre,mode_of_genre.genre_name,mode_of_genre.genre_slug,channel_post_video.iva_id',
							'where' => 'mode='.$mode_id.',privacy_status=7,user_status=1',
							'order' => 'rand()',
							'limit' => 6,
							);
			$rand_video	= $this->query_builder->channel_video_list($accessParam);
			
			if(isset($rand_video['channel'])){
				$rand_video = $rand_video['channel'];
			}
			return $rand_video;
	}
	
	function swiper_slider($content,$single_video=false,$playlist_id=[]){ 
		$video_Arr = array();
		if(!empty($content)){
			$token_uid = $this->get_token_uid();
			$isMyFavorite_check=[];
			$isvoted_Arr	=[];
			if($token_uid){
				$postId_arr=[];
				
				$postId_arr = array_column($content, 'post_id');
				 
				$vidList 	= implode(',',$postId_arr);
				$post_user  = "user_id = $token_uid AND channel_post_id IN($vidList)";
				
				$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user);
				$isMyFavorite_check = array_column($isMyFavorite_check, 'channel_post_id');
				
				$post_user = "user_id = $token_uid AND post_id IN($vidList)";
				
				$isvoted_Arr = $this->DatabaseModel->select_data('vote_id,post_id','channel_video_vote use INDEX(user_id,post_id)',$post_user);
				$isvoted_Arr = array_column($isvoted_Arr, 'post_id');
			}
			
			$this->load->library('Valuelist');
			$web_mode = $this->valuelist->mode();
			$user_level = $this->valuelist->level();
			foreach($content  as $key=>$video ){
				
				$image_uid  = $video['user_id'];
				$image_name = $video['image_name'];

				if(isset($video['playlist_thumb']) && isset($video['playlist_user_id']) && $video['playlist_thumb'] !=''){
					$image_uid  = $video['playlist_user_id'];
					$image_name = $video['playlist_thumb']; 
				}

				$uploaded_video = 	$video['uploaded_video'];
				$user_id		=	$video['user_id'];
				$post_id		=	$video['post_id'];
				$iva_id			=	$video['iva_id'];
				$post_key		= 	$video['post_key'];
				//$slug			=	urlencode($video['slug']);
				$full_title		=	$video['title'];
				$tag  			=   $video['tag'];
				$title			=   (strlen($full_title)< 20)?$full_title:trim(substr($full_title,0,20),"‘")."...";
				$created_at     =	""; //$this->time_elapsed_string($this->manageTimezone($video['created_at']) ,false);
				$is_vid_processed = $video['is_video_processed'];
				$mode_id  		=	isset($video['mode'])?$video['mode']:'';
				$is_stream_live = 	isset($video['is_stream_live'])?$video['is_stream_live']:0;
				$video_duration = 	isset($video['video_duration'])?$video['video_duration']:1;
				$country_name   = 	isset($video['country_name'])?$video['country_name']:'';
				$genre_name   	= 	isset($video['genre_name'])?$video['genre_name']:'';
				$video_type   	= 	isset($video['video_type'])?$video['video_type']:'';
				$genre_id   	= 	isset($video['genre'])?$video['genre']:'';
				 
				
				if(isset($video['sub_genre']) && !empty($video['sub_genre'])){
					$genre_id = $video['sub_genre'];
				}
				$cuser = get_user($user_id);
				$solo_upic =(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))?create_upic($user_id,$cuser[0]['uc_pic']) : '';
				//$solo_uname =(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
				$solo_name =(isset($cuser[0]['user_name']) && !empty($cuser[0]['user_name']))? $cuser[0]['user_name'] : '';
				
				
				$FilterData = $this->share_url_encryption->FilterIva($image_uid,$iva_id,$image_name,trim($uploaded_video),true,'.m3u8',$is_vid_processed);
				
				$ThumbImage = $FilterData['thumb'];
				$ThumbImage = isset($FilterData['webp'])?$FilterData['webp']:$ThumbImage;
				
				$videoFile 	= $FilterData['video'];
				
				$ext  = pathinfo($videoFile , PATHINFO_EXTENSION);
				
				$previewFile=$videoFile;
				if( $ext == 'm3u8'){
					$pattern = "/simultv/i";
					if(preg_match($pattern, $previewFile)==0 && $video_type!= 2){
						$previewFile = str_replace($ext,'mp4', $videoFile);
					}
				}
				
				$homeVideos = array('user_id'		=>$user_id,
									'user_name'		=>$solo_name, //get_user_fullname($user_id),
									'post_id'		=>$post_id,
									'iva_id'		=>$iva_id,
									'title'			=>$full_title,
									'post_key'		=>$post_key,
									'ThumbImage'	=>$ThumbImage,
									'videoFile'		=>$videoFile,
									'previewFile'	=>$previewFile,
									'user_pic'		=>$solo_upic, //get_user_image($user_id),
									'tag'			=>$tag,
									'created_at'	=>$created_at,
									'date'			=>$this->manageTimezone($video['created_at']),
									'isMyFavorite'	=>0,
									'isVoted'		=>0,
									'mode_id'		=>$mode_id,
									'is_stream_live'=>$is_stream_live,
									'video_duration'=>$video_duration,
									'user_country'	=>$country_name,
									'genre_name'	=>$genre_name,
									'description'	=>'NA',
									'genre_id'		=>$genre_id,
									'video_type'    =>$video_type, 
									'video_category'=>isset($video['category'])?$video['category']:'',
									'language'		=>isset($video['language'])?$video['language']:'',
									'privacy_status'=>isset($video['privacy_status'])?$video['privacy_status']:'',
									'share_on_social'=>isset($video['social'])?$video['social']:'', 
									'age_restr' 	 =>isset($video['age_restr'])?$video['age_restr'] :'',
									'clientside_vasttag' => 'https://adnimation.googleima.com/?iu=/339474670,22019190093/CTV_ADN/Discovered_TV/Discovered_Android&description_url=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.discoveredtv&an=Discovered&msid=com.discoveredTV&ua=user_agent&ip=ip_address'
									);
				
				if(!empty($playlist_id)){
					$homeVideos['playlist_id'] = (sizeof($playlist_id) > 1) ? isset($playlist_id[$key])?$playlist_id[$key]:'' : $playlist_id[0];
				}

				if(isset($video['video_ids']) && !empty($video['video_ids'])){
					$homeVideos['video_ids_count'] = sizeof(explode('|',$video['video_ids'])) - 1;
				}


				if(isset($video['user_level'])){
					//$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category', array('category_id'=>$video['user_level']));
					//$user_cate=isset($sub_cat[0]['category_name'])?$sub_cat[0]['category_name'] :'';
					$user_cate = isset($user_level[$video['user_level']])? $user_level[$video['user_level']] : ''; 
					$homeVideos['user_cate']= $user_cate;
				}
				
				
				
				if($single_video){
					
					$vastTag 	= $videoFile;
					
					//$placementId = $this->getPlacementId($mode_id,$this->deviceType, $is_stream_live);
					$placementId = $this->newPlacementId($this->deviceType, $is_stream_live);
					
					$cate_id     = isset($video['user_level'])?$video['user_level'] : '';
					
					$page_url 	 = urlencode(base_url('watch/'.$post_key));

					$size 		 = urlencode('400x300|640x480');

					//$custom 	 = urlencode("category={$mode_id}&user_id={$user_id}&video_id={$post_id}&ifa=uuid&viewer_id=vieweruid");

					$custom 	 = "category={$mode_id}&user_id={$user_id}&video_id={$post_id}&ifa=uuid&viewer_id=vieweruid";

					$CACHEBUSTER = time();

					$url		 = urlencode(base_url());

					if($is_stream_live != 1 ){
						if($this->deviceType=='ANDROID'){
							
							$vastTag ="https://pubads.g.doubleclick.net/gampad/ads?iu=/22019190093/discovered.tv-android_video&url={$url}&description_url={$page_url}&tfcd=0&npa=0&sz={$size}&cust_params={$custom}&vid={$post_id}&cmsid=2528975&gdfp_req=1&output=vmap&unviewed_position_start=1&env=vp&impl=s&ad_rule=1&correlator={$CACHEBUSTER}";
		
			
						}else if($this->deviceType=='IOS'){
							
							$vastTag ="https://pubads.g.doubleclick.net/gampad/ads?iu=/22019190093/discovered.tv-iOS_video&url={$url}&description_url={$page_url}&tfcd=0&npa=0&sz={$size}&cust_params={$custom}&vid={$post_id}&cmsid=2528975&gdfp_req=1&output=vmap&unviewed_position_start=1&env=vp&impl=s&ad_rule=1&correlator={$CACHEBUSTER}";
							
						}else if($this->deviceType=='TIZEN' || $this->deviceType=='ROKU' || $this->deviceType=='ANDROIDTV' || $this->deviceType=='FIRETV' && ($video_type ==0 || $video_type ==4) && $ext == 'm3u8'){
							
							$file		= explode('.',$uploaded_video);
							$folder 	= explode('/',$file[0]);
							$file_name  = $file[0].'/'.$folder[2].'.mp4';
							$key 		= str_replace(['mp4' , 'MP4'] , $ext,$file_name);
							
						
							$pset_id   = $this->getPsetId($video_duration);
							$app_id    = $this->getAppId($this->deviceType);
							$ifs_type  = $this->getIfaType($this->deviceType);
							$user_type = isset($user_cate)? $user_cate : '';
							
							$bundle 	 = 	$this->getBundleID($this->deviceType);
							$bundleID 	 = isset($bundle['bundle_id']) ? $bundle['bundle_id'] : '';
							$storeUrl 	 = isset($bundle['store_url']) ? $bundle['store_url'] : '';
							$width 		 = 1920;
							$height 	 = 1080;

							$adsParam 	= "?ads.placementId={$placementId}&ads.w={$width}&ads.h={$height}&ads.video_id={$post_id}&ads.userid={$user_id}&ads.genreid={$genre_id}&ads.categoryid={$cate_id}&ads.app_id={$app_id}&ads.ifa_type={$ifs_type}&ads.devicetype={$this->deviceType}&ads.user_type={$user_type}&ads.video_duration={$video_duration}&ads.app_bundle={$bundleID}&ads.app_name=discovered&ads.app_store_url={$storeUrl}&ads.did=uuid&ads.us_privacy=1---&aws.logMode=DEBUG&ads.viewerid=vieweruid";
								
							//$vastTag ="https://tv.springserve.com/vast{$adsParam}";
							
							$vastTag = "https://1ac1cad4108b4f3c9b7dec8d54d0600c.mediatailor.us-east-1.amazonaws.com/v1/master/1677faf8e8c0e186077d1c89432d58d6082ee83a/ctv/{$key}{$adsParam}";

							//$vastTag ="https://55eee753c89c42d79c2bfddf86b97fe8.mediatailor.us-east-1.amazonaws.com/v1/master/1677faf8e8c0e186077d1c89432d58d6082ee83a/adnimation_android_testing_jaseem/{$key}?aws.logMode=DEBUG"; //this url is used now
							

							if($this->deviceType=='ANDROIDTV'){      
								//$homeVideos['videoFile'] = $vastTag;
							}
							
						}
					}else if($is_stream_live ==1){
						$bundle 	 = 	$this->getBundleID($this->deviceType);
						$bundleID 	 = isset($bundle['bundle_id']) ? $bundle['bundle_id'] : '';
						$storeUrl 	 = isset($bundle['store_url']) ? $bundle['store_url'] : '';
						$placementId = 813007;
						$width 		 = 1920;
						$height 	 = 1080;
						$adsParam 	 = "?ads.placementId={$placementId}&ads.w={$width}&ads.h={$height}&ads.viewerid=vieweruid&ads.video_id={$post_id}&ads.userid={$user_id}&ads.genreid={$genre_id}&ads.categoryid={$cate_id}&ads.devicetype={$this->deviceType}&ads.us_privacy=1---&ads.app_bundle={$bundleID}&ads.app_name=discovered&ads.app_store_url={$storeUrl}&ads.did=uuid";
						
						$vastTag = "{$uploaded_video}{$adsParam}&aws.logMode=DEBUG"; 

						if($this->deviceType=='ANDROID' || $this->deviceType=='IOS'){
							$homeVideos['videoFile'] = $vastTag;
							$vastTag = '';
						}
					}
					
					$homeVideos['vastTag'] = $vastTag;
				}
				
				if(isset($video['description']) && $video['description'] !=''){
					
					$homeVideos['description'] = $video['description'];
					//$homeVideos['description'] = $this->parseHtml(json_decode($video['description']));
					if($homeVideos['description'] ==''){
						$homeVideos['description']='NA';
					}
					/*if(strlen($video['description']) < 250){ 
						$description =  json_decode($video['description']) ;
					}else{ 
						$description = substr(json_decode($video['description']),0,250)."..." ;
					}*/
					//$homeVideos['description']= $description;
				}	
			
				$age_restr = isset($video['age_restr'])? $video['age_restr'] :'';
				$mode 	   = isset($video['mode'])? ucfirst($web_mode[$video['mode']]) :'';
				$homeVideos['mode'] = $mode;
				if(isset($video['count_views'])){
					
					$age_restr 	= !empty($age_restr)? ' '.$age_restr : '';
					$mode 		= !empty($mode)? ', '.$mode.' Mode' : '';
					//$homeVideos['count_views']= $video['count_views'].' Views'.$age_restr.$mode;
					$homeVideos['count_views']= $age_restr.$mode;
				}
				
				if(!empty($isMyFavorite_check) && in_array($post_id,$isMyFavorite_check)){
					$homeVideos['isMyFavorite']=1;
				}
				
				if(!empty($isvoted_Arr) && in_array($post_id,$isvoted_Arr)){
					$homeVideos['isVoted']=1;
				}
				
				array_push($video_Arr,$homeVideos);
							
			}
		}
		return $video_Arr;
	}
	
	function getBundleID($deviceType){

		$bundleID['ANDROID'] = array('bundle_id'=>'com.discoveredtv', 'store_url'=>'https://play.google.com/store/apps/details?id=com.discoveredTV'); 
		$bundleID['IOS'] 	 = array('bundle_id'=>'id1560271435', 'store_url'=>'https://apps.apple.com/in/app/discovered/id1560271435'); 
		$bundleID['TIZEN']   = array('bundle_id'=>'IyLBDFEJoK.Discovered', 'store_url'=>' https://www.samsung.com/us/appstore/app/G21243017810'); 
		$bundleID['ROKU']    = array('bundle_id'=>'DISCOVERED', 'store_url'=>'https://channelstore.roku.com/en-gb/details/7377f7dc4ea6220ccc03fe74e3ed34b1/discovered');  
		$bundleID['ANDROIDTV'] = array('bundle_id'=>'com.discoveredTV', 'store_url'=>'https://play.google.com/store/apps/details?id=com.discoveredTV');  

		return isset($bundleID[$deviceType])? $bundleID[$deviceType] : [];
	}

	function getIfaType($type=''){
		
		$ifaType = array(	'ANDROID'	=>'aaid',
							'IOS'		=>'idfa',
							'ROKU'		=>'rida',
							'TIZEN'		=>'tifa',
							'ANDROIDTV'	=>'aaid'
						);
			
		return isset($ifaType[$type])? $ifaType[$type] : '';
	}
	
	
	function getAppId($type=''){
		
		$appID = array(	'ANDROID'	=>'com.discoveredtv',
						'IOS'		=>'id1560271435',
						'ROKU'		=>'DISCOVERED',
						'TIZEN'		=>'IyLBDFEJoK.Discovered',
						'ANDROIDTV'	=>'com.discoveredTV'
					);
			
		return isset($appID[$type])? $appID[$type] : '';
	}
	
	function getPsetId($duration=''){
		
		switch ($duration) {   
			case ($duration < 480):
				return "207";
				break;
			case ($duration < 960):
				return "208";
				break;
			case ($duration < 1440):
				return "209";
				break;
			case ($duration < 1920):
				return "210";
				break;
			case ($duration < 2400):
				return "211";
				break; 
			case ($duration < 2880):
				return "212";
				break;
			case ($duration < 3360):
				return "213";
				break; 
			case ($duration < 3840):
				return "214";
				break; 
			case ($duration < 4320):
				return "215";
				break;
			case ($duration < 4800):
				return "216";
				break;
			case ($duration < 5280):
				return "217";
				break;
			case ($duration < 5760):
				return "218";
				break;	
			case ($duration < 6240):
				return "219";
				break;	
			case ($duration < 6300):
				return "237";
				break;		
			case ($duration < 7200):
				return "238";
				break;		
			case ($duration < 8100):
				return "239";
				break;	
			case ($duration < 9000):
				return "240";
				break;	
			case ($duration < 9900):
				return "241";
				break;	
			case ($duration < 10800):
				return "242";
				break;	
			case ($duration < 11700):
				return "243";
				break;
			/*case ($duration < 12600):
				return "228";
				break;	
			case ($duration < 13500):
				return "229";
				break;	
			case ($duration < 11520):
				return "230";
				break;	
			case ($duration < 12000):
				return "231";
				break;		
			case ($duration < 12480):
				return "232";
				break;
			case ($duration < 12960):
				return "233";
				break;	
			case ($duration < 13440):
				return "234";
				break;	
			case ($duration < 13920):
				return "235";
				break;	 */
			default:
				return "243";
		}
	}
	

	function newPlacementId($type,$is_live){
		if($is_live==1){
			$placementId = array('ANDROID'=>813007, 'IOS'=>813007, 'TIZEN'=>813007,'ROKU'=>813007, 'ANDROIDTV'=>813007,'FIRETV'=>813007,);  
		}else{
			$placementId = array('FIRETV'=>835161, 'TIZEN'=>835163,'ROKU'=>835160, 'ANDROIDTV'=>835162);
		}
		return isset($placementId[$type])? $placementId[$type] : '';
	}
	function getPlacementId($mode_id, $type , $is_live){
		
		//music = 1 , movies = 2, television = 3 , 4 = social , gaming = 7
	
		if($is_live==1){
			$placementId['ANDROID'] = array('1'=>24008839, '2'=>24008843, '3'=>24008840,'4'=>24008842, '7'=>24008841); 
			$placementId['IOS'] 	= array('1'=>24008829, '2'=>24008833, '3'=>24008830,'4'=>24008832, '7'=>24008831); 
			$placementId['TIZEN']   = array('1'=>24008799, '2'=>24008803, '3'=>24008800,'4'=>24008802, '7'=>24008801); 
			$placementId['ROKU']    = array('1'=>24008809, '2'=>24008813, '3'=>24008810,'4'=>24008812, '7'=>24008811); 
		}else{
			$placementId['ANDROID'] = array('1'=>24008824, '2'=>24008828, '3'=>24008825,'4'=>24008827, '7'=>24008826); 
			$placementId['IOS'] 	= array('1'=>24008834, '2'=>24008838, '3'=>24008835,'4'=>24008837, '7'=>24008836); 
			$placementId['TIZEN']   = array('1'=>24008804, '2'=>24008808, '3'=>24008805,'4'=>24008807, '7'=>24008806);
			$placementId['ROKU']    = array('1'=>24008814, '2'=>24008818, '3'=>24008815,'4'=>24008817, '7'=>24008816);			
		}
		
		return isset($placementId[$type][$mode_id])? $placementId[$type][$mode_id] : '';
	}
	
	
	function show_homepage_video(){
		$data=array();
		$start	=	(isset($_POST['start']))?$_POST['start']:0;	
		$limit	=	(isset($_POST['limit']))?$_POST['limit']:3;	
		$mode_id=	(isset($_POST['mode']))? $_POST['mode'] :1;
		
		$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.description,users.user_level,channel_post_video.created_at,channel_post_video.is_video_processed";
					
		$where = "channel_post_video.mode = {$mode_id} AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
		
		if(isset($_POST['genre_id']) && !empty($_POST['genre_id'])){
			$genre_id = $_POST['genre_id'];	
			$where .=" AND channel_post_video.genre = {$genre_id}";
		}
		
		$order_by = array( array('post_id' , 'DESC') , array('count_views' , 'DESC') );
		$keys 	  = array_rand($order_by ,1) ;	
		
		$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
		$order = $order_by[$keys];
		$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
		
		
		
		if(!empty($videoData)){
			
			$video_result_count = $this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$where,$join);
			
			$data['total_count'] = $video_result_count;
			$data['video'] = $this->swiper_slider($videoData);
		}
		return $data;		
	
	}
	
	function show_homepage_slider_old(){
		
		//if(isset($_POST['limit']) && !empty($_POST['limit'])){
			$start	=	isset($_POST['start'])?$_POST['start'] : 0 ;	
			$limit	=	isset($_POST['limit'])?$_POST['limit'] : 8 ;	
			$mode	=	($this->get_mode_name($_POST['mode']))? $this->get_mode_name($_POST['mode']) : 'music'; //get mode name
			//$mode	=	isset($_POST['mode'])? $_POST['mode'] : 'music';
			$fields = 	array('slider_title','type', $mode, $mode.'_slider_order',$mode.'_status','user','id');
		
			$cond	=	array($mode .'!=' => NULL ,$fields[4] => 1,'id !='=>4);	
			$order	=	array($fields[3] , 'ASC');	
		
			$result	= 	$this->DatabaseModel->select_data($fields,'site_main_data',$cond,array($limit,$start),'', $order);
			//print_r($result);die;
			$data	=	[];
			$resp = [];
			if(isset($result[0])){
				
				foreach($result as $list){
					
					$limit 		= 	8;
					$title		=	$list['slider_title'];
					$type		=	$list['type'];
					$user		=	$list['user'];
					$id 		=   $list['id'];
					/* if($id != 4){ */
						
						$vidList 	= 	explode(',',$list[$mode]);
						$limit	 	= 	(sizeof($vidList) <= $limit)? sizeof($vidList) : $limit;
						$keys 		= 	array_rand($vidList ,$limit) ;
						//$keys =array_keys($vidList);
						//$keys =array_slice($keys,0,$limit);
						// print_r($keys);die;
						$values		=	[];
						if(is_array($keys)){
							for($j=0;$j< sizeof($keys);$j++){
								array_push($values,$vidList[$keys[$j]]);
							}
							$vidList = implode(',',$values);
						}else{
							$vidList = $vidList[$keys];
						}
						
						$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,users.user_level,channel_post_video.is_stream_live,channel_post_video.video_duration,channel_post_video.video_type,channel_post_video.genre,country.country_name,mode_of_genre.genre_name";
						
						$where = "channel_post_video.post_id IN($vidList) AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND channel_post_video.age_restr = 'Unrestricted' AND  users.user_status = '1'";
								   
						$join  = array(
										'multiple',
										array(
											array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
											array('users' , 'users.user_id = channel_post_video.user_id'),
											array('users_content','users_content.uc_userid = users.user_id','left'),
											array('country','country.country_id = users_content.uc_country','left'),
											array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id = channel_post_video.genre','left'),
										)
									);
						// $caloum = ($vData['type'] == 'new_release_video')?'post_id':'count_views';			
						// $order = array($caloum,'DESC');
						$order = ''; //rand()';
						$order ="FIELD(channel_post_video.post_id,$vidList)";
						if($title == 'NEW RELEASES'){
							$order = array('post_id','DESC');
						}else 
						if($title == 'TOP VIDEOS OF THE MONTH' || $title == 'MOST POPULAR VIDEOS'){
							$order = array('count_views','DESC');
						}
						
						$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
								
						if(!empty($videoData)){
							$data[] = array('slider_type'=>$type,'type'=>ucwords(strtolower($title)),'slider'=>$this->swiper_slider($videoData));
							//$data[$title] = $this->swiper_slider($videoData);
						}
						//$resp = $data;
					/*}else{
							array_push($data,'EXPLORE VIDEOS BY GENRES');
					}*/
				}
				//$resp = array('status'=>1,'data'=>$data);
			}else{
				//$resp = array('status'=>0,'data'=>$data);
			}
			//echo json_encode($resp);
			return $data;
		//}	
	}
	
	
	function show_homepage_slider(){
			$start	=	isset($_POST['start'])?$_POST['start'] : 0 ;	
			$limit	=	isset($_POST['limit'])?$_POST['limit'] : 8 ;
			
			$mode	=	isset($_POST['mode'])? $_POST['mode'] : '1';
			$data	=	[];
			$resp 	= 	[];

			$live_mode_slider_disable = false;

			if($mode == 9 && $start==0 && $live_mode_slider_disable){
				$liveModeSliders = array( 'is_live'=> 'Live Now',
										  'recently_ended'=> 'Recently Ended Streams',
										  'scheduled_live_streams' =>'Scheduled Live Streams',
										);
				foreach($liveModeSliders as $type=>$value){
					$liveData = $this->getLiveData($type);
					if(!empty($liveData)){
						array_push($data, $liveData);
					}
				}
				
				$this->load->library('Valuelist');
				$web_mode = array_reverse($this->valuelist->mode(), true);
				// print_r($web_mode);die;
				foreach ($web_mode as $key => $value) {
					if(in_array($key ,[1,2,3,7])){
						$category = $this->getLiveData('mode', $key ,$value);
						if(!empty($category)){
							array_push($data , $category);
						}
					}
				}
					
					
			}
				
			$fields = 	array(	'slider_title',
								'type', 
								'mode', 
								'data',
								'status',
								'slider_order',
								'user_id',
								'slider_type'
							);
		
			$cond	=	array($fields[1] .' !=' =>'explore_videos_by_genres', $fields[2] => $mode ,$fields[4] => 1);	
			$order	=	array($fields[5] , 'ASC');	
		
			$result	= 	$this->DatabaseModel->select_data($fields,'homepage_sliders use INDEX(mode)',$cond,array($limit,$start),'', $order);
			//echo $this->db->last_query();die;
			//print_r($result);die;
			
			$slider_total_count = "0";
			
			if(isset($result[0])){
				$slider_total_count = $this->DatabaseModel->aggregate_data('homepage_sliders','homepage_sliders.id','COUNT',$cond);

				/*if($mode ==7){
					$slider_total_count += 1;
				}*/

				$limit 	= 	8;
				if($this->deviceType=='ANDROID' || $this->deviceType=='IOS'){
					$limit 	= 	3;
				}
				foreach($result as $list){
					
					$title		=	$list['slider_title'];
					$type		=	$list['type'];
					$user		=	$list['user_id'];
					$slider_type =	$list['slider_type'];

					if($slider_type == 'playlist'){
						$htmlarray = $this->getPlaylistSlider($list,$start,$limit);

						if(!empty($htmlarray)){
							array_push($data,$htmlarray);
						}
									
					}else{
						if($title != 'EXPLORE VIDEOS BY GENRES'){
							
							$vidList 	= 	explode(',',$list['data']);
							$limit_key	= 	(sizeof($vidList) <= $limit)? sizeof($vidList) : $limit;

							if($type == 'global_top_ten'){
								$keys 	=	array_slice(array_keys($vidList),0,$limit_key); //without random keys in global top ten
							}else{
								$keys 	= 	array_rand($vidList ,$limit_key) ;
							}

							//$keys =array_keys($vidList);
							//$keys =array_slice($keys,0,$limit);
							// print_r($keys);die;
							$values		=	[];
							if(is_array($keys)){
								for($j=0;$j< sizeof($keys);$j++){
									array_push($values,$vidList[$keys[$j]]);
								}
								$vidList = implode(',',$values);
							}else{
								$vidList = $vidList[$keys];
							}
							
							$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,users.user_level,channel_post_video.is_stream_live,channel_post_video.video_duration,channel_post_video.genre,channel_post_video.video_type,country.country_name,mode_of_genre.genre_name";
							
							$where = "channel_post_video.post_id IN($vidList) AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
							/*channel_post_video.age_restr = 'Unrestricted' AND*/
							$join  = array(
											'multiple',
											array(
												array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
												array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
												array('users_content use INDEX(uc_userid)','users_content.uc_userid = users.user_id','left'),
												array('country','country.country_id = users_content.uc_country','left'),
												array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id = channel_post_video.genre','left'),
											)
										);
							// $caloum = ($vData['type'] == 'new_release_video')?'post_id':'count_views';			
							// $order = array($caloum,'DESC');
							//$order = ''; //rand()';
							$order ="FIELD(channel_post_video.post_id,$vidList)";
							if($title == 'NEW RELEASES'){
								$order = array('post_id','DESC');
							}else 
							if($title == 'TOP VIDEOS OF THE MONTH' || $title == 'MOST POPULAR VIDEOS'){
								$order = array('count_views','DESC');
							}
							
							$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
								
							if(!empty($videoData)){
								$data[] = array('slider_type'=>$type,'type'=>$title,'slider'=>$this->swiper_slider($videoData, true));
								//$data[$title] = $this->swiper_slider($videoData);
							}
							//$resp = $data;
						}else{
							//array_push($data,'EXPLORE VIDEOS BY GENRES');
						}
					}
				}
				//$resp = array('status'=>1,'data'=>$data);
			}else{
				//$resp = array('status'=>0,'data'=>$data);
			}
		
		//echo json_encode($resp);
		return  array('data'=>$data, 'total_count'=>$slider_total_count);
		//}	
	}
	
	

	public function getPlaylistSlider($list=[],$start=0,$limit=10)
	{	$data = [];
		if(!empty($list)){
			$mode		=  	mode();
			$title		=	$list['slider_title'];
			$type		=   $slug =	$list['type'];
			$user		=	$list['user_id'];
			$slider_type=	$list['slider_type'];
			$vidList 	= 	explode(',',$list['data']);
			$limit	 	= 	(sizeof($vidList) <= $limit)? sizeof($vidList) : $limit;
			$keys 		= 	array_rand($vidList ,$limit);
			
			$values		=	[];
			if(is_array($keys)){
				for($i=0;$i< sizeof($keys);$i++){
					array_push($values,$vidList[$keys[$i]]);
				}
				$vidList = implode(',',$values);
			}else{
				$vidList = $vidList[$keys];
			}
			
			$field = "channel_video_playlist.user_id as playlist_user_id,channel_video_playlist.video_ids,channel_post_video.post_id,channel_video_playlist.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.user_id,channel_video_playlist.playlist_thumb,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.mode,users.user_level,channel_post_video.is_stream_live,channel_post_video.genre,channel_post_video.video_type,country.country_name,mode_of_genre.genre_name";
			
			$where = "channel_video_playlist.privacy_status = 7 AND channel_video_playlist.playlist_id IN($vidList) AND " . $this->common->channelGlobalCond(); 
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			
			$join  = array(
						'multiple',
						array(
							array('channel_video_playlist use INDEX(first_video_id)' , 'channel_video_playlist.first_video_id = channel_post_video.post_id'),
							array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
							array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
							array('users_content use INDEX(uc_userid)','users_content.uc_userid = users.user_id','left'),
							array('country','country.country_id = users_content.uc_country','left'),
							array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id = channel_post_video.genre','left')
						),
					);
			
			$order = "FIELD(channel_video_playlist.playlist_id,$vidList)";
							
			
			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
			$pl 		= 	explode(',',$vidList);
			if(!empty($videoData)){				
				$data = array('slider_type'=>$type,'type'=>$title,'slider'=>$this->swiper_slider($videoData, true,$pl));
			}	
		}
		return $data;
	}


	function getLiveData($type, $mode_id='', $mode='', $seeAll=false, $start = 0, $limit = 10){
		
		$joinCond = array('users_medialive_info' , 'users_medialive_info.user_id = users.user_id','left');

		if($type ==	'is_live'){
			$condition =" AND channel_post_video.is_stream_live = 1 AND ( users_medialive_info.is_scheduled != 1  || users_medialive_info.schedule_time < '". gmdate("Y-m-d H:i:s") ."' )";
			// $condition =" AND channel_post_video.is_stream_live = 1 ";
			$title = 'Live Now';
		}
		
		if($type == 'recently_ended'){
			$condition =" AND channel_post_video.created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
			$condition =" AND  channel_post_video.is_stream_live = 0 AND users_medialive_info.is_scheduled = 0 ";
			$title = 'Recently Ended Streams';
		}
		
		if($type =='scheduled_live_streams'){ 
			$condition =" AND users_medialive_info.schedule_time > '". gmdate("Y-m-d H:i:s") ."' AND users_medialive_info.is_scheduled = 1 ";
			// $condition =" AND users_medialive_info.is_scheduled = 1 ";
			$title = 'Scheduled Live Streams';

			$joinCond = array('users_medialive_info' , 'users_medialive_info.live_pid = channel_post_video.post_id');
		}
		
		if($type =="mode"){
			$type = $type.'_'.$mode;
			$condition =" AND channel_post_video.mode = {$mode_id}";
			$title = $mode;
		}
		
		$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,,channel_post_video.description,channel_post_video.mode,channel_post_video.is_video_processed,channel_post_video.genre,channel_post_video.video_type,channel_post_video.is_stream_live,users_medialive_info.schedule_time,users.user_level,country.country_name,mode_of_genre.genre_name";

		$where = "channel_post_video.video_type = 2 AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1' {$condition}";
			   
		$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
							$joinCond ,
							array('users_content','users_content.uc_userid = users.user_id','left'),
							array('country','country.country_id = users_content.uc_country','left'),
							array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id = channel_post_video.genre','left'),
						)
					);
		
		$order = array('channel_post_video.post_id','DESC');
								
		$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,$order);
		
		if(!empty($videoData)){
			if($seeAll){
				return $videoData;
			}else{
				return array('slider_type'=>$type,'type'=>ucwords(strtolower($title)),'slider'=>$this->swiper_slider($videoData, true));
			}
		}else{
			return array();
		}
	}

	/**************** Articles Mode Start *****************/

	function getArticlesHomeSliders(){
		$start	=	isset($_POST['start'])?$_POST['start'] : 0 ;	
		$limit	=	isset($_POST['limit'])?$_POST['limit'] : 8 ;
		
		$mode	=	isset($_POST['mode'])? $_POST['mode'] : '10';
		$data	=	[];
		$resp 	= 	[];

		$fields = 	array(	'slider_title',
							'type', 
							'mode', 
							'data',
							'status',
							'slider_order',
							'user_id',
							'is_sidebar_slider',
						);

		$cond	=	array($fields[1] .' !=' =>'top_in_category', $fields[2] => $mode ,$fields[4] => 1,$fields[7] =>0);	
		$order	=	array($fields[5] , 'ASC');	

		$result	= 	$this->DatabaseModel->select_data($fields,'homepage_sliders use INDEX(mode)',$cond,array($limit,$start),'', $order);
		//echo $this->db->last_query();die;
		//print_r($result);die;

		$slider_total_count = "0";

		if(isset($result[0])){
			$slider_total_count = $this->DatabaseModel->aggregate_data('homepage_sliders','homepage_sliders.id','COUNT',$cond);

			$limit 	= 	8;
			if($this->deviceType=='ANDROID' || $this->deviceType=='IOS'){
				$limit 	= 	5;
			}
			
			foreach($result as $list){
					
				$title		=	$list['slider_title'];
				$type		=	$list['type'];
				$user		=	$list['user_id'];
				 
				$vidList 	= 	explode(',',$list['data']);
				$limit_key	= 	(sizeof($vidList) <= $limit)? sizeof($vidList) : $limit;
				$keys 		= 	array_rand($vidList ,$limit_key);
				$values		=	[];
				if(is_array($keys)){
					for($j=0;$j< sizeof($keys);$j++){
						array_push($values,$vidList[$keys[$j]]);
					}
					$vidList = implode(',',$values);
				}else{
					$vidList = $vidList[$keys];
				}
				$cond = '';
				$globalanCond ="complete_status = 1 AND delete_status = 0 AND privacy_status = 7";

				$order = array('articles.article_id', 'desc');

				if($title=='LATEST ARTICLES'){

					$order = array('articles.article_id', 'desc');

				}else if($title=='MOST POPULAR ARTICLES'){

					$order=array('articles.views desc', 'articles.article_id desc');

				}else if($title=='RECOMMENDED FOR YOU'){

					$order = array('articles.views', 'desc');

				}else if($title=='GLOBAL TOP TEN'){

					$res 	  =  $this->DatabaseModel->select_data('page_setting.cover_video','page_setting',array('website_mode' => 10));
					$data_arr =  isset($res['0']['cover_video'])?$res['0']['cover_video'] : '';
					if($data_arr !=''){
						$cond = " AND article_id IN($data_arr)";
					}

				}else{

					$cond = " AND article_id IN($vidList)"; 
					
				}

				$cond = $globalanCond.''.$cond;
				
				$field 	= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_tag,articles.ar_category_id,articles.ar_author_name,articles.ar_date_created,articles.ar_uid,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name,users_content.uc_pic';

				$join  			= array(
										'multiple',
											array(
												array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
												array('users use INDEX(user_id)' , 'users.user_id = articles.ar_uid','left'),
												array('users_content use INDEX(uc_userid)', 'users.user_id 	= users_content.uc_userid','left'),	
												array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
											)
										);
				$table 			= '(SELECT * FROM `articles` use INDEX(article_id) WHERE '.$cond.' ORDER BY `article_id` DESC LIMIT 0 , 5) as `articles`';
				$articlesData	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`order_` IN (0 ,1)','',$join,$order);
				//echo $this->db->last_query();die;
				
				if(!empty($articlesData)){
					$formated   = $this->formatArticlesArray($this->getArticlesDetails($articlesData));
					//print_R($formated);die;
					$data[] = array('slider_type'=>$type,'type'=>ucwords(strtolower($title)),'slider'=>$formated);
				}
			}
		}
		return  array('data'=>$data, 'total_count'=>$slider_total_count);
	}

	
	function getArticlesDetails($articles=[]){
		if(!empty($articles)){
			foreach($articles as $key=>$list){
				if($list['content_type'] =='image'){
					$imgData = explode('.',$list['content']);
					$articles[$key]['content'] 	  = AMAZON_URL.$list['content'];
					//$articles[$key]['ThumbImage'] = AMAZON_URL.trim($imgData[0]).'_thumb.'.$imgData[1];
					$articles[$key]['ThumbImage'] = AMAZON_URL.$list['content'];
					
				}else{
					$articles[$key]['ThumbImage'] =base_url().'repo/images/blog_pp.png';
				}
				if(isset($list['uc_pic']) && isset($list['ar_uid'])){
					$articles[$key]['uc_pic'] 	  = create_upic($list['ar_uid'],$list['uc_pic']);
				}
				$articles[$key]['ar_encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($list['article_id'] , 'encode');
			}
		}
		return $articles;
	}

	public function get_single_article(){
		
		$resp=array();
		$this->form_validation->set_rules('article_id', 'Article id', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{

			$article_id = $_POST['article_id'];

 			$cond ="article_id = $article_id AND complete_status = 1 AND delete_status = 0 AND privacy_status = 7";

			$field 	= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_tag,articles.ar_category_id,articles.ar_author_name,articles.ar_date_created,articles.ar_uid,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name,users_content.uc_pic,articles.privacy_status';
			
			$join  			= array(
									'multiple',
										array(
											array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
											array('users use INDEX(user_id)' , 'users.user_id = articles.ar_uid','left'),
											array('users_content use INDEX(uc_userid)', 'users.user_id 	= users_content.uc_userid','left'),	
											array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
										)
									);
			$table 			= '(SELECT * FROM `articles` use INDEX(article_id) WHERE '.$cond.' ORDER BY `article_id` DESC LIMIT 0 , 5) as `articles`';
			$where = ''; //'`articles_content`.`order_` IN (0 ,1)';
			$articlesData	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order=['articles_content.order_', 'asc']);
			//print_r($articlesData);die;

			if(!empty($articlesData)){
				$resp = array('single_articles'=>$this->getArticlesDetails($articlesData));
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Single article.';
			}else{
				$this->respMessage = 'Article not found.';
			}
		}else{
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
	}

	function get_related_articles(){
		$resp = [];
		$uid = (isset($_POST['article_uid']))?$_POST['article_uid'] : $this->get_token_uid();

		//if(isset($_POST['article_id']) || isset($_POST['article_tag'])){
			
			$limit	= (isset($_POST['limit']) && !empty($_POST['limit']))?$_POST['limit']:8;
			$start  = (isset($_POST['start']) && !empty($_POST['start']))?$_POST['start']:0;	

			$article_id   = isset($_POST['article_id']) ? $_POST['article_id'] : '';
			$article_tag  = isset($_POST['article_tag']) ? $_POST['article_tag'] : '';
			$article_cate = isset($_POST['article_cate']) ? $_POST['article_cate'] : '';

			$cond ="articles.complete_status = 1 AND articles.delete_status = 0"; 

			$privacyCond =" AND articles.privacy_status = 7 ";

			if(!$this->is_token_uid($uid)){   /* FOR OTHER USER	*/
				$AmIFanOfHim = $this->AmIFollowingHim($uid);  
				if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
					$privacyCond = ' AND articles.privacy_status IN(6,7) ';/* PRIVATE,PUBLIC*/
				}else{
					$privacyCond = ' AND articles.privacy_status IN(7) ';	/* ONLY PUBLIC*/
				}
			}

			if(!empty($article_id)){
				$cond .=" AND articles.article_id != $article_id";
			}

			if(!empty($article_cate)){
				$cond .=" AND articles.ar_category_id = $article_cate";
			}

			if(!empty($article_tag)){
				$cond .=" AND articles.ar_tag LIKE '%".$article_tag."%'";
			}
			$cond .= $privacyCond;

 			$field 	= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_tag,articles.ar_category_id,articles.ar_author_name,articles.ar_date_created,articles.ar_uid,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name,users_content.uc_pic';

			$join  	= array(
							'multiple',
								array(
									array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
									array('users' , 'users.user_id = articles.ar_uid','left'),
									array('users_content', 'users.user_id 	= users_content.uc_userid','left'),	
									array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
								)
							);
			$table = '(SELECT * FROM `articles` use INDEX(article_id) WHERE '.$cond.' ORDER BY `article_id` DESC LIMIT '.$start.' ,'.$limit.') as `articles`';
			
			$where = '`articles_content`.`order_` IN (0 ,1)';

			$order = array('articles.article_id', 'desc');

			$articlesData	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);
			if(!empty($articlesData)){
				$formated   = $this->formatArticlesArray($this->getArticlesDetails($articlesData));
				$resp = array('relatedArticles'=>$formated);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Related articles.';
			}else{
				$this->respMessage = 'Related articles.';
			}
		//}else{
			//$this->respMessage = 'Article ID OR Article Tag field required.';
		//}

		$this->show_my_response($resp);
	}

	private function formatArticlesArrayold($array){
		$c = 0;
		$formated = array();
		foreach($array as $k => $article){
			if ($k == 0) {
				$last_ID = $article['article_id'];
			}

			if ($last_ID != $article['article_id']) {
				$last_ID = $article['article_id'];
				$c += 1;
			}

			if ($last_ID == $article['article_id']) {
				if(empty($formated[$c])){
					$formated[$c][0] = $article;
					$formated[$c][0]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($last_ID , 'encode');
				}else{
					$formated[$c][1] = $article;
					$formated[$c][1]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($last_ID , 'encode');
				}
				$keys = array_column($formated[$c], 'order_');
				array_multisort($keys, SORT_ASC, $formated[$c]);
			}
		}
		return $formated;
	}
	
	private function formatArticlesArray($array){
		$formated = array();
		//$arr_keys = array_keys($array);
		//$last_key = end($arr_keys);
		$i = 0;
		$arti_ids= array_column($array,'article_id');
		foreach($array as $k => $article){
			if ($k == 0) {
				$last_ID = $article['article_id'];
				$key = $k;
				$i = 0;
			}

			if ($last_ID != $article['article_id']) {
				$last_ID = $article['article_id'];
				$key = $k;
				$i = 0;
			}

			if ($last_ID == $article['article_id']) {
				$i++;
				if($array[$key]['content_type']=='image' && $article['content_type'] == 'ckeditor'){

					$array[$key]['text_content'] = !empty($article['plain_content']) ? html_entity_decode($article['plain_content'],ENT_QUOTES) : '';
					$formated[] 				 = $array[$key];

				}else if($array[$key]['content_type']=='ckeditor' && $article['content_type'] == 'image'){

					$array[$k]['text_content'] 	= !empty($array[$key]['plain_content']) ? html_entity_decode($array[$key]['plain_content'],ENT_QUOTES) : '';
					$array[$k]['content'] 		= $article['content'];
					$formated[] 				= $array[$k];

				}else if(count(array_keys($arti_ids, $article['article_id'])) < 2){
					$array[$k]['text_content'] 	= !empty($array[$key]['plain_content']) ? html_entity_decode($array[$key]['plain_content'],ENT_QUOTES) : '';
					$array[$k]['content'] 		= $article['content'];
					$formated[] 				= $array[$k];

				}else if($i==2 && $array[$key]['content_type']=='image' && $article['content_type'] == 'image'){

					$array[$key]['text_content'] 	= !empty($array[$key]['plain_content']) ? html_entity_decode($array[$key]['plain_content'],ENT_QUOTES) : '';
					$array[$key]['content'] 		= $article['content'];
					$formated[] 					= $array[$key];

				}else if($i==2 && $array[$key]['content_type']=='ckeditor' && $article['content_type'] == 'ckeditor'){

					$array[$key]['text_content'] 	= !empty($array[$key]['plain_content']) ? html_entity_decode($array[$key]['plain_content'],ENT_QUOTES) : '';
					$array[$key]['content'] 		= $article['content'];
					$formated[] 					= $array[$key];
				}

				/*else if($last_key == $k){
					if(fmod(sizeof($array),2) > 0){
						$array[$k]['text_content'] = html_entity_decode($array[$key]['plain_content'],ENT_QUOTES);
						$array[$k]['content'] 	   = $article['content'];
						$formated[] = $array[$k];
					}
				}*/

			}
		}
		return $formated;
	}

	public function see_all_slider_aticles(){
		$data=array();
		$resp=array();
		$this->form_validation->set_rules('article_type', 'Article type', 'trim|required');
		$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
		
		if ($this->form_validation->run() == TRUE){
			
			$limit	= (isset($_POST['limit']))?$_POST['limit']:8;
			$start  = (isset($_POST['start']))?$_POST['start']:0;

			$articleData = $this->DatabaseModel->select_data('data,slider_title', 'homepage_sliders use INDEX(mode)' , array('type' => $_POST['article_type'],'mode'=>$_POST['mode']) ,1);
				
			if(isset($articleData[0]) && !empty($articleData[0])){
				
				$artiList = $articleData[0]['data'] ;  
				$title 	  = $articleData[0]['slider_title'] ; 
				
				$cond = '';
				$globalanCond ="complete_status = 1 AND delete_status = 0 AND privacy_status = 7";

				$order = array('articles.article_id', 'desc');

				if($title=='LATEST ARTICLES'){

					$order = array('articles.article_id', 'desc');

				}else if($title=='MOST POPULAR ARTICLES'){
					
					$order=array('articles.views desc', 'articles.article_id desc');

				}else if($title=='RECOMMENDED FOR YOU'){

					$order = array('articles.views', 'desc');

				}else if($title=='GLOBAL TOP TEN'){

					$res 	  =  $this->DatabaseModel->select_data('page_setting.cover_video','page_setting',array('website_mode' => 10));
					$data_arr =  isset($res['0']['cover_video'])?$res['0']['cover_video'] : '';
					if($data_arr !=''){
						$cond = " AND article_id IN($data_arr)";
					}

				}else{
					$cond = " AND article_id IN($artiList)"; 
				}

				$cond = $globalanCond.''.$cond;
				
				$field 	= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_tag,articles.ar_category_id,articles.ar_author_name,articles.ar_date_created,articles.ar_uid,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name,users_content.uc_pic';

				$join  			= array(
										'multiple',
											array(
												array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
												array('users' , 'users.user_id = articles.ar_uid','left'),
												array('users_content', 'users.user_id 	= users_content.uc_userid','left'),	
												array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
											)
										);
				$table 			= '(SELECT * FROM `articles` use INDEX(article_id) WHERE '.$cond.' ORDER BY `article_id` DESC LIMIT '.$start.' ,'.$limit.') as `articles`';
				$articlesData	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`order_` IN (0 ,1)','',$join,$order);

				if(isset($articlesData[0])){
					$data['all_articles'] = $this->formatArticlesArray($this->getArticlesDetails($articlesData));
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'All articles list.';
				}else{
					$this->respMessage = 'Article not found.';
				}						
				
			}else{
				$this->respMessage = 'Article not found.';
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($data);
	}

	public  function get_articles_category(){
		$resp = [];

		$limit	 = (isset($_POST['limit']))?$_POST['limit']:10;
		$start   = (isset($_POST['start']))?$_POST['start']:0;

		if(isset($_POST['type']) && $_POST['type']=='all'){
			$cond ="article_categories.status = 1";
		}else{
			$cond ="(SELECT COUNT(article_id) AS total FROM articles where ar_category_id = article_categories.id AND articles.complete_status = 1 AND articles.delete_status = 0 AND articles.privacy_status = 7) > 0 AND article_categories.status = 1";
		}
		
		$order = ['article_categories.category_order' , 'asc'];

		$cate = $this->DatabaseModel->select_data('id,cat_name,cat_img,cat_slug,status,category_order','article_categories',$cond,'','',$order);
		
		if(!empty($cate)){
			foreach($cate as $key=>$value){
				if(!empty($value['cat_img'])){
					$pathToImages = ABS_PATH.'repo_admin/images/blog_cate/';
					if(file_exists($pathToImages.$value['cat_img'])){
						$cate[$key]['cat_img']= base_url().'repo_admin/images/blog_cate/'.$value['cat_img'];
					}else{
						$cate[$key]['cat_img']= base_url().'repo/images/thumbnail.jpg';	
					}
				}else{
					$cate[$key]['cat_img']= base_url().'repo/images/thumbnail.jpg';	
				}
			}

			$resp =array('category_title'=>'Categories' ,'articleCategory'=>$cate);
			$this->respMessage 	= 'Articles category';
			$this->statusType   = 'Success';
			$this->statusCode  	=  1;

		}else{
			$this->respMessage = 'Articles category not found';
		}
		$this->show_my_response($resp);
	}
	
	public function delete_article_post(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$rule = array(
				array( 'field' => 'article_id', 'label' => 'article id', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rule);
			if($this->form_validation->run()){
				
				$article_id = $this->input->post('article_id');
				
				$where['article_id'] = $article_id;
				$where['content_type'] = 'image';
				
				$field = 'articles_content.content';
				$article_content	= $this->DatabaseModel->select_data($field,'articles_content',$where);
					
				if(!empty($article_content)){
					$keys = []; 
					foreach($article_content as $arti){
						$key = $arti['content'];
						if ($key != 0 || !empty($key)) {
							$key_ar = explode('/', $key);
							if( $key_ar[0] != 'embedcv' ){
								
								$pathinfo = pathinfo($key);
								$keys[]  = $key;
								$keys[]  = $key.'.webp';
								$keys[]  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'];
								$keys[]  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'].'.webp';
							}
						}
					}
					$s3_status  = s3_delete_object($keys);
				}
				
				unset($where['content_type']);
				$delete_status  = $this->DatabaseModel->access_database('articles_content', 'delete', '', $where);
				$delete_status1 = $this->DatabaseModel->access_database('articles', 'delete', '', $where);
				$this->deleteArticleFromHomesliders($article_id);
				$this->deleteArticleFromCoverVideo($article_id);
			
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Article deleted successfully';
			}else{
				$this->respMessage = $this->single_error_msg();
			}
			
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);	
	}

	private function deleteArticleFromHomesliders($article_id=''){
		if(!empty($article_id)){
			$sliders= $this->DatabaseModel->select_data('id,data','homepage_sliders',['mode'=> 10]);
			if(!empty($sliders)){
				$updateArray = [];
				foreach($sliders as $s){
					$article_ids =  $s['data'];
					$id = $s['id'];
					if(!empty($article_ids)){
						$aids =  explode(',' , $article_ids);
						$key = array_search($article_id, $aids);
						if($key !==FALSE){
							
							unset($aids[$key]);
							
							$v = array_values($aids);

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
	
	private function deleteArticleFromCoverVideo($article_id=''){
		if(!empty($article_id)){
			$sliders= $this->DatabaseModel->select_data('id,cover_video','page_setting',['website_mode'=> 10]);

			if(!empty($sliders)){
				$updateArray = [];
				foreach($sliders as $s){
					$article_ids =  $s['cover_video'];
					$id = $s['id'];
					if(!empty($article_ids)){
						$aids =  explode(',' , $article_ids);
						$key = array_search($article_id, $aids);
						if($key !==FALSE){
							
							unset($aids[$key]);
							
							$v = array_values($aids);

							$updateArray[] = array(
													'id'	=>$id,
													'cover_video'	=>	implode(',',$v)
												);	
						}
					}
				}
				if(!empty($updateArray)){
					$this->db->update_batch('page_setting',$updateArray, 'id');
					//print_r($updateArray);die;
				}
			}
		}
	}
	
	public function delete_article_content(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			$rule = array(
				array( 'field' => 'article_content_id', 'label' => 'content id', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rule);
			if($this->form_validation->run()){

				$where['id'] 			= $this->input->post('article_content_id');
				$where['content_type'] 	= 'image';
				$article_content		= $this->DatabaseModel->select_data('articles_content.content','articles_content',$where);
					
				if(!empty($article_content) && !empty($article_content[0]['content'])){
					$key = $article_content[0]['content'];
					if ($key != 0 || !empty($key)) {
						$key_ar = explode('/', $key);
						if( $key_ar[0] != 'embedcv' ){
							
							$pathinfo = pathinfo($key);
							$key1  = $key;
							$key2  = $key.'.webp';
							$key3  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'];
							$key4  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'].'.webp';
		
							$s3_status = s3_delete_object( array( $key1, $key2, $key3, $key4 ) );
						}
					}
				}

				unset($where['content_type']);
				$delete_status = $this->DatabaseModel->access_database('articles_content', 'delete', '', $where);
				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				$this->respMessage 	= 'Element Deleted Successfully';
				
			}else{
				$this->respMessage = $this->single_error_msg();
			}
			
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}

	public function create_article(){
		
		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$rule = array(
				array( 'field' => 'art_title', 			'label' => 'Title', 	'rules' => 'trim|required'),
				array( 'field' => 'art_category', 		'label' => 'Category', 	'rules' => 'trim|required'),
				array( 'field' => 'art_auth_name', 		'label' => 'Blog author name', 'rules' => 'trim|required'),
				array( 'field' => 'art_tags', 			'label' => 'Tags', 		'rules' => 'trim|required'),
				array( 'field' => 'art_privacy_status', 'label' => 'Privacy', 	'rules' => 'trim|required'),
			);
	
			$this->form_validation->set_rules($rule);
	
			if($this->form_validation->run() == TRUE){
				$uid = $TokenResponce['userid'];			
				$post = array(	'ar_title' 			=> $this->input->post('art_title'),
								'ar_slug' 			=> slugify($this->input->post('art_title')), 
								'ar_tag' 			=> $this->input->post('art_tags'), 
								'ar_category_id' 	=> $this->input->post('art_category'), 
								'ar_author_name' 	=> $this->input->post('art_auth_name'),
								'privacy_status' 	=> $this->input->post('art_privacy_status'), 
								'complete_status' 	=> 0,
								'ar_uid' 			=> $uid
							);
				
				$post_id['article_id'] = $this->input->post('article_id');
				
				if ($post_id['article_id'] == 0) {
					$post['ar_date_created']  	= date('Y-m-d H:i:s');
					$resp['article_id'] = $this->DatabaseModel->access_database('articles', 'insert', $post);
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Article created successfully.';
				}
				else{
					$res = $this->DatabaseModel->access_database('articles', 'update', $post, $post_id);
					$resp['article_id'] = $post_id['article_id'];
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Article updated successfully.';
				}
			}
			else{
				$this->respMessage = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}

	public function upload_article_content(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$rule = array(
				array( 'field' => 'article_id',   'label' => 'article id', 'rules' => 'trim|required'),
				array( 'field' => 'content_type', 'label' => 'content type', 'rules' => 'trim|required')
			);
			$this->form_validation->set_rules($rule);
			if($this->form_validation->run() == TRUE){
				$uid = $TokenResponce['userid'];

				$content_type 	= $this->input->post('content_type');
				$article_id 	= $this->input->post('article_id');
				if($content_type == 'ckeditor'){
					$content = $this->input->post('text_content');

					if(!empty($content)){
						$post = [	'article_id' 	=> $this->input->post('article_id'),
									'user_id' 		=> $uid, 
									'content_type' 	=> 'ckeditor', 
									'content'		=> $content,
									'plain_content' => htmlToPlainText($content)
								];
				
						if ($this->input->post('article_content_id') == 0) {
							$post['order_'] = $this->input->post('order');
							$db_id = $this->DatabaseModel->access_database('articles_content', 'insert', $post);
						}else{
							$data['id'] = $this->input->post('article_content_id');
							$status = $this->DatabaseModel->access_database('articles_content', 'update', $post, $data);
							
							if ($status === 1) {
								$db_id = $data['id'];
							}else{
								$db_id = $status;
							}
						}

						if(isset($_POST['publish_status']) && $_POST['publish_status'] ==1){
							$this->publish_article($article_id);
						}

						$resp = ['input_id' => $this->input->post('input_id'), 'counter' => $this->input->post('counter'), 'content_length' => $this->input->post('length'), 'last_insert_id' => $db_id ];
				
						$this->statusCode = 1;
						if ($db_id >= 1) {
							$this->statusType = 'Success';
							$this->respMessage = 'Data Inserted/Updated';
						}else{
							$this->respMessage = 'Data not Inserted/Updated';
						}
					}else{
						$this->respMessage = 'The text content field is required.';
					}
				}else if($content_type == 'image'){
					return $this->uploadArticleImages();

				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}

		$this->show_my_response($resp);
	}

	public function uploadArticleImages(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$rule = array(
				array( 'field' => 'author', 	'label' => 'image author', 	 'rules' => 'trim'),
				array( 'field' => 'publisher', 	'label' => 'image publisher','rules' => 'trim'),
				array( 'field' => 'license_id', 'label' => 'image license',  'rules' => 'trim')
			);
			$this->form_validation->set_rules($rule);
			if($this->form_validation->run() == TRUE){

				$article_id = $this->input->post('article_id');
				$uid = $TokenResponce['userid'];
				$upload_path = './uploads/aud_'.$uid.'/images/';
				$data  = ['db_status' => '', 'aws_status' => '' ];
				$filename = '';
				if(isset($_FILES['article_image']['name']) && !empty($_FILES['article_image']['name'])){

					$config = array(
						'upload_path' 	=> $upload_path,
						'allowed_types' => 'jpg|jpeg|png|gif',
						'max_size' 		=> '11000',
						'remove_spaces' => TRUE,
						'encrypt_name' 	=> TRUE,
					);
					$this->load->library('upload',$config); 
					$this->load->library('image_lib');
					if(!$this->upload->do_upload('article_image')){
						$this->respMessage = strip_tags($this->upload->display_errors()) ;
						return $this->show_my_response($resp);
					}else{
						$uploadData 	= $this->upload->data();
						$filename 		= $uploadData['file_name'];
						$this->audition_functions->resizeImage('1060','0',$upload_path.$filename,'',$maintain_ratio = TRUE,$create_thumb= FALSE,55);
					}

				}else if(isset($_POST['pixabay_src']) && !empty($_POST['pixabay_src'])){

					$url 		= $this->input->post('pixabay_src');
					$ext 		= pathinfo($url, PATHINFO_EXTENSION);
					$filename 	= rand().'.'.$ext;
					$file 		= file_put_contents($upload_path.$filename, file_get_contents($url));
				}else{
					$this->respMessage 	= 'Image file required.';
					return $this->show_my_response($resp);
				}
				//echo $filename;die;

				if($filename !=''){
					// converting webp and thumbnail start //
					$this->load->library('convert_image_webp');
						
					if(file_exists($upload_path.$filename))
					$this->convert_image_webp->convertIntoWebp($upload_path.$filename);
					
					//'294','217',
					$this->audition_functions->resizeImage('417','417',$upload_path.$filename,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
					
					$img = explode('.',$filename);
					
					$path = $upload_path.$img[0].'_thumb.'.$img[1];

					if(file_exists($path))
					$this->convert_image_webp->convertIntoWebp($path);
					// converting web and thumbnail end //

					upload_all_images($uid);

					$json_data  = ['author' => $this->input->post('author'), 'publisher' => $this->input->post('publisher'), 'license_id' => $this->input->post('license_id') ];
		
					$post 	    = [	'article_id' 	=> $this->input->post('article_id'),
									'user_id' 		=> $uid, 
									'content_type' 	=> 'image', 
									'content' 		=> 'aud_'.$uid.'/images/'.$filename,
									'plain_content' => 0,
									'image_data' 	=> json_encode($json_data),
									'order_' 		=> $this->input->post('order') 
								];
		
					$data['lastinsert_id'] 	= $this->DatabaseModel->access_database('articles_content', 'insert', $post);	
					$where['id'] 			= $data['lastinsert_id'];
					
					if(isset($_POST['publish_status']) && $_POST['publish_status'] ==1){
						$this->publish_article($article_id);
					}

					$img = $this->DatabaseModel->access_database('articles_content', 'select', '', $where);
					
					$resp = ['last_insert_id' => $data['lastinsert_id'], 'img_src' => $img[0]['content'], 'input_id' => $this->input->post('input_id') ];
					
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Article Uploaded Successfully.';		
				}else{
					$this->respMessage 	= 'Article not uploaded try again.';		
				}
				
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}

	public function publish_article($article_id =''){

		if(isset($article_id) && !empty($article_id)){

			$where['article_id'] 		= $article_id;
			$data['complete_status'] 	= 1;

			$content	= $this->DatabaseModel->select_data('plain_content,content_type','articles_content',$where);
			$reading_time = 0;
			$total_seconds = 0;

			foreach ($content as $key => $value) {
				if ($value['content_type'] == 'ckeditor') {
					$ret_arr = estimateReadingTime($value['plain_content']);
					$total_seconds += $ret_arr['total_seconds'];
				}else{
					$ret_arr = estimateReadingTime($value['plain_content'], 0.10);
					$total_seconds += $ret_arr['total_seconds'];
				}
			}
			$reading_time = round($total_seconds / 60);
			
			$data['ar_read_time'] = ($reading_time==0)?1:$reading_time;

			$this->DatabaseModel->access_database('articles', 'update', $data, $where);
		}
	}

	public function article_view_count(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			$rule = array(
						array( 'field' => 'article_id', 'label' => 'Article Id', 'rules' => 'trim|required'),
					);
			$this->form_validation->set_rules($rule);
		 	if ($this->form_validation->run() == TRUE){
				$where['article_id'] 		= $this->input->post('article_id');
				$where['complete_status'] 	= 1;
				$where['delete_status'] 	= 0;
				
				$row =  $this->DatabaseModel->select_data('views','articles',$where,1);
				if (!empty($row)) {
					$data['views'] = $row[0]['views'] + 1;
					$this->DatabaseModel->access_database('articles','update', $data, $where);
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Article add view count Successfully.';				
				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}

	/**************** Articles Mode End *****************/
	
	/************** See all slider video **************/
	public function see_all_slider_video(){
		$data=array();
		$resp=array();
		$this->form_validation->set_rules('video_types', 'Video types', 'trim|required');
		$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
		
		if ($this->form_validation->run() == TRUE){	
		 
			$limit	= (isset($_POST['limit']))?$_POST['limit']:8;
			$start  = (isset($_POST['start']))?$_POST['start']:0;			
			$video_types = $_POST['video_types'];
			
			
			if($_POST['mode'] ==9){   //See all videos in live mode
				$videoArr = explode('_' , $video_types);
				if(isset($videoArr[0]) && $videoArr[0] =='mode'){
					$this->load->library('valuelist');
					$mode_id = $this->valuelist->website_mode($videoArr[1]);
					$liveData = $this->getLiveData($videoArr[0], $mode_id, $videoArr[1], $seeAll = true, $start, $limit);
				}else{
					$liveData = $this->getLiveData($video_types ,'', '', $seeAll = true, $start, $limit);
				}
				
				if(isset($liveData[0])){
					$data['all_video'] = $this->swiper_slider($liveData, true);
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'All video list.';
				}else{
					$this->respMessage = 'Video not found.';
				}
				
			}else if(isset($_POST['uid']) && $_POST['uid']){
				$_POST['data_return']   = 1;
				$more_from_this_creator = $this->get_related_video(); //see all more from this creator
				if(!empty($more_from_this_creator)){
					$data['all_video'] = $more_from_this_creator['slider'];
				}
				
			}else if(isset($_POST['genre_id']) && $_POST['genre_id']==0)
			{	
				//$mode	=($this->get_mode_name($_POST['mode']))? $this->get_mode_name($_POST['mode']) : 'music'; //get mode name
				 
				$freshVideoData = $this->DatabaseModel->select_data('data,slider_title,slider_type', 'homepage_sliders use INDEX(mode)' , array('type' => $_POST['video_types'],'mode'=>$_POST['mode']) ,1);
				 //print_r($freshVideoData);die;
				if(isset($freshVideoData[0]) && !empty($freshVideoData[0])){
					
					$vidList = $freshVideoData[0]['data'] ;  /*mode id from info helper*/
					$title 	 = $freshVideoData[0]['slider_title'] ;  /*mode id from info helper*/
					$slider_type =	$freshVideoData[0]['slider_type'];
					$where = '';
					$pl = [];
					if($slider_type == 'playlist'){

						$field = "channel_video_playlist.user_id as playlist_user_id,channel_video_playlist.video_ids,channel_post_video.post_id,channel_video_playlist.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.user_id,channel_video_playlist.playlist_thumb,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.mode,users.user_level,channel_post_video.is_stream_live,channel_post_video.genre,channel_post_video.video_type,mode_of_genre.genre_name"; //country.country_name
			
						$where = "channel_video_playlist.privacy_status = 7 AND channel_video_playlist.playlist_id IN($vidList) AND " . $this->common->channelGlobalCond(); 
						/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
						
						$join  = array(
									'multiple',
									array(
										array('channel_video_playlist use INDEX(first_video_id)' , 'channel_video_playlist.first_video_id = channel_post_video.post_id'),
										array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
										array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
										//array('users_content use INDEX(uc_userid)','users_content.uc_userid = users.user_id','left'),
										//array('country','country.country_id = users_content.uc_country','left'),
										array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id = channel_post_video.genre','left')
									),
								);
						
						$order = "FIELD(channel_video_playlist.playlist_id,$vidList)";
						$pl 		= 	explode(',',$vidList);		
					}else{
					
						if(!empty($vidList)){
							$where  .= "channel_post_video.post_id IN($vidList) AND ";
						}
						
						$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,channel_post_video.genre,channel_post_video.video_type,users.user_level,mode_of_genre.genre_name";
						
						$where .= "channel_post_video.complete_status = '1' AND channel_post_video.active_status = '1' AND channel_post_video.privacy_status = '7' AND channel_post_thumb.active_thumb = 1 AND users.user_status = '1'";
									
						$join  = array('multiple',
										array(
											array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
											array('users' , 'users.user_id = channel_post_video.user_id'),
											array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),

										)
									);
						
						$order 	= 'rand()';
						if($title == 'NEW RELEASES'){
							$order = array('post_id','DESC');
						}else 
						if($title == 'TOP VIDEOS OF THE MONTH' || $title == 'MOST POPULAR VIDEOS'){
							$order = array('count_views','DESC');
						}		
					
					}
					
					$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,$order);
				
					if(isset($videoData[0])){
						$data['all_video'] = $this->swiper_slider($videoData, true,$pl);
						//$resp   = array('all_video'=>$data);		
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'All video list.';
					}else{
						$this->respMessage = 'Video not found.';
					}
				}else{
					$this->respMessage = 'Video not found.';
				}
			
			}else{
				 
				$genre_id = isset($_POST['genre_id'])?$_POST['genre_id']:0;
				$level ='genre';
				if(isset($_POST['genre_level']) && $_POST['genre_level']==2){
					$level='sub_genre';
				}					
				$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,channel_post_video.genre,channel_post_video.video_type,users.user_level,mode_of_genre.genre_name";
			
			
				$where ="channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status IN(7) AND channel_post_video.$level = {$genre_id} AND users.user_status = 1";
				  
				$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
									  'left'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
								array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),
							)
						);
						
				$order=array('channel_post_video.created_at','DESC');
				if($video_types =='new_realeased_video'){
					
					$order=array('channel_post_video.created_at','DESC');
					
				}else if($video_types =='most_popular_videos'){
					
					$order=array('channel_post_video.count_views','DESC');
					
				}else if($video_types =='top_of_the_month'){
					
					$where .=" AND channel_post_video.created_at LIKE '%".date('Y-m')."%'";
					$order=array('channel_post_video.count_views','DESC');
					
				}
				
				$see_all_genre_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
				
				if(isset($see_all_genre_video[0])){
					$data['all_video'] = $this->swiper_slider($see_all_genre_video, true);
					//$resp   = array('all_video'=>$data);		
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'All video list.';
				}else{
					$this->respMessage = 'Video not found.';
				}
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($data);
	}
	
	/************** See all slider video **************/
	public function see_all_slider_video_old(){
		$data=array();
		$resp=array();
		$this->form_validation->set_rules('video_types', 'Video types', 'trim|required');
		$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
		
		if ($this->form_validation->run() == TRUE){	
		 
			$limit	= (isset($_POST['limit']))?$_POST['limit']:8;
			$start  = (isset($_POST['start']))?$_POST['start']:0;			
			
			$mode	=($this->get_mode_name($_POST['mode']))? $this->get_mode_name($_POST['mode']) : 'music'; //get mode name
			 
			$freshVideoData = $this->DatabaseModel->select_data($mode.',slider_title', 'site_main_data' , array('type' => $_POST['video_types']) ,1);
			// print_r($freshVideoData);die;
			if(isset($freshVideoData[0]) && !empty($freshVideoData[0])){
				
				$vidList = $freshVideoData[0][$mode] ;  /*mode id from info helper*/
				$title = $freshVideoData[0]['slider_title'] ;  /*mode id from info helper*/
				
				$where = '';
				if(!empty($vidList)){
					$where  .= "channel_post_video.post_id IN($vidList) AND ";
				}
				
				$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,users.user_level";
				
				$where .= "channel_post_video.complete_status = '1' AND channel_post_video.active_status = '1' AND channel_post_video.privacy_status = '7' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.age_restr = 'Unrestricted' AND users.user_status = '1'";
						   
				$join  = array('multiple',
								array(
									array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
							);
				
				$order 	= 'rand()';
				if($title == 'NEW RELEASES'){
					$order = array('post_id','DESC');
				}else 
				if($title == 'TOP VIDEOS OF THE MONTH' || $title == 'MOST POPULAR VIDEOS'){
					$order = array('count_views','DESC');
				}						
				
				$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,$order);
			
				if(isset($videoData[0])){
					$data['all_video'] = $this->swiper_slider($videoData);
					//$resp   = array('all_video'=>$data);		
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'All video list.';
				}else{
					$this->respMessage = 'Video not found.';
				}
			}else{
				$this->respMessage = 'Video not found.';
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($data);
	}

	/**************** Get homepage cover image ****************/
	public function get_cover_image($mode=null){       /*GET COVER IMAGE FOR HOMAPAGES*/
		$cover_image = $this->audition_functions->get_website_info('cover_image',$mode);
		if(!empty($cover_image)){
			return  base_url('repo_admin/images/homepage/').$cover_image;
		}
	}
	
	/**************** Get homepage cover video for android & ios app ****************/
	function getAndroidIosCoverVideo($page=null,$mode=null){
		$this->load->library('valuelist');
		$mode_id = $this->valuelist->website_mode($mode);
		
		$detail = $this->DatabaseModel->select_data('cover_video,cover_video_link','page_setting',['website_mode'=> $mode_id ],1);
		
		$cover_video_id 	= isset($detail['0']['cover_video'])?$detail['0']['cover_video']:[];
		$cover_video_link 	= isset($detail['0']['cover_video_link'])? json_decode($detail['0']['cover_video_link'],true):[];

		if($mode_id == 10){
			if(!empty($cover_video_id)){
				$cond ="article_id IN($cover_video_id) AND complete_status = 1 AND delete_status = 0 AND privacy_status = 7";

				$field 	= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_tag,articles.ar_category_id,articles.ar_author_name,articles.ar_date_created,articles.ar_uid,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name';

				$join  	= array(
								'multiple',
									array(
										array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
										array('users' , 'users.user_id = articles.ar_uid','left'),
										array('users_content', 'users.user_id 	= users_content.uc_userid','left'),	
										array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
									)
								);
				$table 			= '(SELECT * FROM `articles` use INDEX(article_id) WHERE '.$cond.' ORDER BY `article_id` DESC) as `articles`';
				$group_by ='`articles`.`article_id`';
				$top_mid_slider	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`content_type` IN ("image")','',$join,$order=['articles.article_id', 'desc'],'',$group_by);
					
				if(isset($top_mid_slider[0])){
					$top_mid_slider = $this->getArticlesDetails($top_mid_slider);
					return sizeof($top_mid_slider) == 1 ? $top_mid_slider : $top_mid_slider;
				}else{
					return array();
				}
			}else{
				return array();
			}


		}else{

			if(!empty($cover_video_id)){
				
				$where = "channel_post_video.post_id IN($cover_video_id) AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 "; //AND channel_post_video.age_restr = 'Unrestricted'
				
				$join  = '';
							
				$order ="FIELD(channel_post_video.post_id,$cover_video_id)";

				$field = "channel_post_video.is_video_processed,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video,channel_post_video.description,channel_post_video.tag,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.count_views,channel_post_video.age_restr,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,";
				
				$data =  $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,'',$join,$order);
				
				$gloable_video = []; 
				
				if(isset($data[0])){
					
					$token_uid 			= $this->get_token_uid();
					foreach($data as $d){
						$uploaded_video = $d['uploaded_video'];
						
						$vid_name 	= AMAZON_URL .$uploaded_video;
						$key 		= explode('.',$uploaded_video);
						$folder 	= explode('/',$key[0]);
						$file_name  = $folder[2].'.mp4';
							
						$video_preview 	= ($d['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$file_name.'?q='.time() : $vid_name  ;
						
						$description='NA';
						if(isset($d['description']) && $d['description'] !=''){
						
							$description = $d['description'];
							//$description = $this->parseHtml(json_decode($d['description']));
						
							if($description ==''){
								$description='NA';
							}
						}
						$coverVideo =   array(	'user_id'        =>$d['user_id'],
												'user_name'      =>'',
												'post_id'        =>$d['post_id'],
												'url'            =>$vid_name,
												'title'          =>$d['title'],
												'preview'        =>$video_preview,
												'mode'           =>$mode,
												'description'    =>$description,
												'user_cate'      =>'',
												'genre_name'     =>'',
												'isMyFavorite'   =>0,
												'isVoted'	     =>0,
												'created_at'     =>'',
												'date'           =>'',
												'video_duration' =>'',
												'user_country' 	 =>'',
												'tag' 			 =>'',
												'user_pic'       =>'',
												'adsBtn'		 =>(object)[]  //isset($cover_video_link[$d['post_id']])?$cover_video_link[$d['post_id']]: (object)[]
											); 
						
						$gloable_video[] = $coverVideo;
						
					}
					if($this->deviceType=='ANDROID' || $this->deviceType=='ANDROIDTV' || $this->deviceType=='IOS'){

						return sizeof($gloable_video) == 1 ? $gloable_video : $gloable_video;	

					}else{

						return isset($gloable_video[0]) ? $gloable_video[0] : $gloable_video;	

					}
					
				}else{
					return array();
				}
				
			}else{
				return array();
			}
		}
	}
	
	public function get_cover_video($page=null,$mode=null){
		
		$this->load->library('valuelist');
		$mode_id = $this->valuelist->website_mode($mode);
		$detail = $this->DatabaseModel->select_data('cover_video,cover_video_link','page_setting',['website_mode'=> $mode_id ],1);
		
		$cover_video_id 	= isset($detail['0']['cover_video'])?$detail['0']['cover_video']:[];
		$cover_video_link 	= isset($detail['0']['cover_video_link'])? json_decode($detail['0']['cover_video_link'],true):[];
		
		if(!empty($cover_video_id)){
			
			$where = "channel_post_video.post_id IN($cover_video_id) AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0";
			
			$join  = array(
							'multiple',
							array(
								//array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id','left'),
								array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id','left'),
								array('users_content use INDEX(uc_userid)','users_content.uc_userid = users.user_id','left'),
								//array('artist_category','artist_category.category_id= users.user_level','left'),
								array('country','country.country_id = users_content.uc_country','left'),
								array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),
							)
						);
						
			$order ="FIELD(channel_post_video.post_id,$cover_video_id)";

			$field = "channel_post_video.is_video_processed,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video,channel_post_video.description,channel_post_video.tag,channel_post_video.created_at,channel_post_video.is_video_processed,channel_post_video.count_views,channel_post_video.age_restr,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,users.user_level,users.user_name,users_content.uc_pic,mode_of_genre.genre_name,country.country_name";
			
			$data =  $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,'',$join,$order);
			
			$gloable_video = [];
			
			if(isset($data[0])){
				
				$isMyFavorite_check	= [];
				$isvoted_Arr		= [];
				$token_uid 			= $this->get_token_uid();
				
				if($token_uid){

					$postId_arr=[];
					$postId_arr = array_column($data, 'post_id');
					$vidList 	= implode(',',$postId_arr);
					
					$post_user  = "user_id = $token_uid AND channel_post_id IN($vidList)";
					$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user);
					$isMyFavorite_check = array_column($isMyFavorite_check, 'channel_post_id');
					
					$post_user = "user_id = $token_uid AND post_id IN($vidList)";
					$isvoted_Arr = $this->DatabaseModel->select_data('post_id','channel_video_vote use INDEX(user_id,post_id)',$post_user);
					$isvoted_Arr = array_column($isvoted_Arr, 'post_id');
				}

				$web_mode   = $this->valuelist->mode();
				$user_level = $this->valuelist->level();  
				foreach($data as $d){
					$uploaded_video = $d['uploaded_video'];
					
					$vid_name 	= AMAZON_URL .$uploaded_video;
					$key 		= explode('.',$uploaded_video);
					$folder 	= explode('/',$key[0]);
					$file_name  = $folder[2].'.mp4';
						
					$video_preview 	= ($d['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$file_name.'?q='.time() : $vid_name  ;
					
					$description='NA';
					if(isset($d['description']) && $d['description'] !=''){
					
						$description = $d['description'];
						//$description = $this->parseHtml(json_decode($d['description']));
					
						if($description ==''){
							$description='NA';
						}
					}	
					
					$solo_upic =(isset($d['uc_pic']) && !empty($d['uc_pic']))?create_upic($d['user_id'],$d['uc_pic']) : base_url('repo/images/user/user.png');
					
					$coverVideo =   array(	'user_id'        =>$d['user_id'],
											'user_name'      =>$d['user_name'],
											'post_id'        =>$d['post_id'],
											'url'            =>$vid_name,
											'title'          =>$d['title'],
											'preview'        =>$video_preview,
											'mode'           =>$mode,
											'description'    =>$description,
											'user_cate'      =>isset($user_level[$d['user_level']])? $user_level[$d['user_level']] : '',
											'genre_name'     =>$d['genre_name'],
											'isMyFavorite'   =>0,
											'isVoted'	     =>0,
											'created_at'     =>$this->time_elapsed_string($this->manageTimezone($d['created_at']) ,false),
											'date'           =>$this->manageTimezone($d['created_at']),
											'video_duration' =>$d['video_duration'],
											'user_country' 	 =>$d['country_name'],
											'tag' 			 =>$d['tag'],
											'user_pic'       =>$solo_upic,
											'adsBtn'		 =>isset($cover_video_link[$d['post_id']])?$cover_video_link[$d['post_id']]: (object)[]
										); 
										
					$coverVideo['age_restr']	= isset($d['age_restr'])? $d['age_restr'] :'';
					$coverVideo['mode'] 		= isset($d['mode'])? ucfirst($web_mode[$d['mode']]) :'';
					
					
					if(!empty($isMyFavorite_check) && in_array($d['post_id'],$isMyFavorite_check)){
						$coverVideo['isMyFavorite']=1;;
					}
					
					if(!empty($isvoted_Arr) && in_array($d['post_id'],$isvoted_Arr)){
						$coverVideo['isVoted']=1;
					}
					$gloable_video[] = $coverVideo;
					// return $coverVideo;
				
				}
				if($this->deviceType=='ANDROID' || $this->deviceType=='ANDROIDTV' || $this->deviceType=='IOS'){

					return sizeof($gloable_video) == 1 ? $gloable_video : $gloable_video;	
					
				}else{

					return isset($gloable_video[0]) ? $gloable_video[0] : $gloable_video;

				}
			}else{
				return array();
			}
		}else{
			return array();
		}
		
	}
	
	function getVideoPreview($uploaded_video='',$is_video_processed=0){
		if(!empty($uploaded_video)){
			$vid_name 	=  AMAZON_URL .$uploaded_video;
			$key 		= explode('.',$uploaded_video);
			$folder 	= explode('/',$key[0]);
			$file_name  = $folder[2].'.mp4';
			
			return $video_preview 	= ($is_video_processed == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$file_name.'?q='.time() : $vid_name;
		}
	}
	
	/************** Get search query content or my favorite video *************/
	public function get_my_content($searchType){
		
		$searchKey = (isset($_POST['search_query']))?$_POST['search_query']:'';
		$searchKey = addslashes(validate_input($searchKey));
		$searchKey = str_replace("&amp;","&",$searchKey);
		
		$mode_id  = '';   /*IN CASE OF ALL*/
		if(isset($_POST['mode_id']) && !empty($_POST['mode_id'])){
			$mode_id  = validate_input($_POST['mode_id']); /*site mode selected by user in a serch filter page*/
		}
		
		$token_uid = $this->get_token_uid();
		
		$limitData 	 = (isset($_POST['limit']))?$_POST['limit']:8;
		$startOffset = (isset($_POST['start']))?$_POST['start']:0;
		
		$total_count="0";
		$result = array();
		if(!empty($searchType)){
			if($searchType == 'video'){ 
				
				$field = 'channel_post_video.post_id,channel_post_video.user_id,channel_post_video.created_at,channel_post_video.title,channel_post_video.description,channel_post_video.age_restr,channel_post_thumb.image_name,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.post_key,users.user_name,users.user_uname,users.user_level,country.country_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.is_video_processed,users_content.uc_pic,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,channel_post_video.genre,channel_post_video.video_type,mode_of_genre.genre_name';	
					
				$cond ="(users.user_name LIKE '%".$searchKey."%' OR users.user_uname LIKE '%".$searchKey."%' OR  channel_post_video.title LIKE '%".$searchKey."%')";
				
				$cond .=" AND channel_post_video.privacy_status = 7 AND users.user_status = 1 AND users.is_deleted = 0";  	
				
				if(!empty($mode_id)){ 
					$cond .=" AND channel_post_video.mode=".$mode_id."";
				}
				
				$cond .= " AND channel_post_video.active_status = 1  AND  channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0" ;
				
				$order = array('channel_post_video.post_id','DESC');
				
				$limit = $limitData+1;
				$start = $startOffset;
				
				$join = array('multiple' , 
						array(
							array(	'users', 
									'users.user_id 		= channel_post_video.user_id', 
									'left')
						)
				);

				$search_result = $this->DatabaseModel->select_data('channel_post_video.post_id','channel_post_video use INDEX(title)',$cond,array($limitData+1,$startOffset),$join,$order);
				$search_post_id = array_column($search_result, 'post_id');
				$search_post_id = implode(',',$search_post_id);
				$search_post_id = !empty($search_post_id) ? $search_post_id : 0 ;
				
				$join = array('multiple' , array(
									array(	'users', 
											'users.user_id 				= channel_post_video.user_id', 
											'left'),
									array(	'users_content', 
											'users.user_id 				= users_content.uc_userid', 
											'left'),		
									/*array(	'website_mode', 
											'website_mode.mode_id 		= channel_post_video.mode', 
											'left'),*/		
									array(	'mode_of_genre use INDEX(genre_id)', 
											'mode_of_genre.genre_id 	= channel_post_video.genre', 
											'left'),
									/*array(	'artist_category b',
											'b.category_id= users.user_level',
											'left'),*/
									array(	'channel_post_thumb',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
									array(	'country',
											'country.country_id = users_content.uc_country',
											'left'),
									));
									
				$cond = "channel_post_video.post_id IN($search_post_id) AND channel_post_thumb.active_thumb = 1";
				
				if(isset($_POST['favorite'])){
					
					$order = array('channel_favorite_video.fav_id','DESC');
					array_push($join[1],array('channel_favorite_video',
													   'channel_favorite_video.channel_post_id 	= channel_post_video.post_id',
													   'left'));
					$cond ="channel_post_thumb.active_thumb = 1 AND channel_favorite_video.user_id =".$token_uid."";
					
					if(isset($_POST['search_keyword']) && !empty($_POST['search_keyword'])){
						$search_keyword = $_POST['search_keyword'];
						$search_keyword = addslashes(validate_input($search_keyword));
						$cond .=" AND (channel_post_video.title LIKE '%".$search_keyword."%' OR channel_post_video.description LIKE '%".$search_keyword."%' OR channel_post_video.created_at LIKE '%".$search_keyword."%'  OR channel_post_video.tag LIKE '%".$search_keyword."%')";
					}
				}
				
				$video_result	= $this->DatabaseModel->select_data($field ,'channel_post_video use INDEX(post_id)' , $cond , array($limit,$start), $join);				
				
				if(isset($video_result[0])){
					
					$video_result_count = $this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$cond,$join);
					$total_count = $video_result_count;
					
					$checkChanelCount = 1;
					
					$isMyFavorite_check=[];
					$isvoted_Arr	=[];
					
					if($token_uid){
						$postId_arr=[];
						$postId_arr = array_column($video_result, 'post_id');
						$vidList 	= implode(',',$postId_arr);
						
						$post_user  = "user_id = $token_uid AND channel_post_id IN($vidList)";
						$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user);
						$isMyFavorite_check = array_column($isMyFavorite_check, 'channel_post_id');
						
						$post_user = "user_id = $token_uid AND post_id IN($vidList)";
						$isvoted_Arr = $this->DatabaseModel->select_data('post_id','channel_video_vote use INDEX(user_id,post_id)',$post_user);
						$isvoted_Arr = array_column($isvoted_Arr, 'post_id');
					}

					$this->load->library('Valuelist');
					$web_mode = $this->valuelist->mode();
					$user_level = $this->valuelist->level();
					
					foreach($video_result as $channel){
						$isMyFavorite=0;
						$isVoted=0;
						if($checkChanelCount++ <= $limitData){
							$isMyFavorite=0;
							$description = $channel['description'];
							//$description = $this->parseHtml(json_decode($channel['description']));
							if($description ==''){
								$description='NA';
							}
							/*if(strlen($channel['description']) < 250){ 
								$description =  json_decode($channel['description']) ;
							}else{ 
								$description = substr(json_decode($channel['description']),0,250)."..." ;
							}*/
							
							if(!empty($isMyFavorite_check) && in_array($channel['post_id'],$isMyFavorite_check)){
								$isMyFavorite=1;
							}
							
							if(!empty($isvoted_Arr) && in_array($channel['post_id'],$isvoted_Arr)){
								$isVoted=1;
							}
							
							$is_vid_processed = $channel['is_video_processed'];
							$FilterData = $this->share_url_encryption->FilterIva($channel['user_id'],$channel['iva_id'],$channel['image_name'],$channel['uploaded_video'],false,'.m3u8',$is_vid_processed);
							
							$ThumbImage = isset($FilterData['webp'])?$FilterData['webp']:$FilterData['thumb'];
							
							$videoFile 	= $FilterData['video'];
							
							$ext  = pathinfo($videoFile , PATHINFO_EXTENSION);
							$previewFile=$videoFile;
							if( $ext == 'm3u8'){
								$pattern = "/simultv/i";
								if(preg_match($pattern, $previewFile)==0){
									$previewFile = str_replace($ext,'mp4', $videoFile);
								}
							}
							
							$my_fav= array(	'user_pic'=>create_upic($channel['user_id'],$channel['uc_pic']),
											'user_id'=>$channel['user_id'],
											'user_name'=>$channel['user_name'],
											'user_cate'=>isset($user_level[$channel['user_level']])? $user_level[$channel['user_level']] : '',
											'country_name'=>ucfirst(strtolower($channel['country_name'])),
											'created_at'=> "", //$this->time_elapsed_string($this->manageTimezone($channel['created_at']) ,false),
											'web_mode'=>$web_mode[$channel['mode']],
											'age_restr'=>$channel['age_restr'],
											'post_id'=>$channel['post_id'],
											'post_key'=>$channel['post_key'],
											'count_views'=>'', //$channel['count_views'],
											'count_votes'=>$channel['count_votes'],
											'title'=>$channel['title'],
											'description'=>$description,
											'isMyFavorite'=>$isMyFavorite,
											'isVoted'=>$isVoted,
											'ThumbImage'=>$ThumbImage,
											'video_path'=>$videoFile,
											'previewFile'=>$previewFile,
											'tag'=>$channel['tag'],
											'mode_id'=>$channel['mode'],
											'is_stream_live'=>$channel['is_stream_live'],
											'video_duration'=>$channel['video_duration'],
											'user_country'=>$channel['country_name'],
											'genre_name'=>$channel['genre_name'],
											'mode'=>$web_mode[$channel['mode']],
											'video_type'=>$channel['video_type'],
											'clientside_vasttag' => 'https://adnimation.googleima.com/?iu=/339474670,22019190093/CTV_ADN/Discovered_TV/Discovered_Android&description_url=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.discoveredtv&an=Discovered&msid=com.discoveredTV&ua=user_agent&ip=ip_address'
							);
							
							$genre_id 		= $channel['genre'];
							$user_id 		= $channel['user_id'];
							$post_id    	= $channel['post_id'];
							$post_key    	= $channel['post_key'];
							$video_type 	= $channel['video_type'];
							$is_stream_live = $channel['is_stream_live'];
							$mode 			= $channel['mode'];
							$vastTag 		= $videoFile;
							$video_duration = $channel['video_duration'];
							$user_cate 		= isset($user_level[$channel['user_level']])? $user_level[$channel['user_level']] : '';
							
							//$placementId 	= $this->getPlacementId($mode,$this->deviceType, $is_stream_live);
							$placementId = $this->newPlacementId($this->deviceType, $is_stream_live);
							$cate_id     	= isset($channel['user_level'])?$channel['user_level'] : '';
							
							$page_url 		= urlencode(base_url('watch/'.$post_key));

							$size 		 	= urlencode('400x300|640x480');

							//$custom 	 	= urlencode("category={$mode}&user_id={$user_id}&video_id={$post_id}&ifa=uuid&viewer_id=vieweruid");

							$custom 	 	= "category={$mode}&user_id={$user_id}&video_id={$post_id}&ifa=uuid&viewer_id=vieweruid";

							$CACHEBUSTER   	= time();

							$url		 = urlencode(base_url());
					
							if($is_stream_live != 1 ){
								if($this->deviceType=='ANDROID'){
									
									$vastTag ="https://pubads.g.doubleclick.net/gampad/ads?iu=/22019190093/discovered.tv-android_video&url={$url}&description_url={$page_url}&tfcd=0&npa=0&sz={$size}&cust_params={$custom}&vid={$post_id}&cmsid=2528975&gdfp_req=1&output=vmap&unviewed_position_start=1&env=vp&impl=s&ad_rule=1&correlator={$CACHEBUSTER}";
		
							
									/* $vastTag ="https://secure.adnxs.com/ptv?id={$placementId}&appid=com.discoveredtv&ifa_type=aaid&vwidth=1920&vheight=1080&skippable=1&mf_aspect_ratio=16x9&vmaxduration=60&kw_video_id={$post_id}&kw_utm_source=discovered&kw_user_id={$user_id}&kw_source=discovered&kw_icon=1&kw_genre={$genre_id}&kw_category={$cate_id}&us_privacy=1---&ifa=uuid&kw_viewer_id=vieweruid"; */
									
									
								}else if($this->deviceType=='IOS'){
									
									$vastTag ="https://pubads.g.doubleclick.net/gampad/ads?iu=/22019190093/discovered.tv-iOS_video&url={$url}&description_url={$page_url}&tfcd=0&npa=0&sz={$size}&cust_params={$custom}&vid={$post_id}&cmsid=2528975&gdfp_req=1&output=vmap&unviewed_position_start=1&env=vp&impl=s&ad_rule=1&correlator={$CACHEBUSTER}";
							
									/* $vastTag ="https://secure.adnxs.com/ptv?id={$placementId}&appid=id1560271435&ifa_type=idfa&vwidth=1920&vheight=1080&skippable=1&mf_aspect_ratio=16x9&vmaxduration=60&kw_video_id={$post_id}&kw_utm_source=discovered&kw_user_id={$user_id}&kw_source=discovered&kw_icon=1&kw_genre={$genre_id}&kw_category={$cate_id}&us_privacy=1---&ifa=uuid&kw_viewer_id=vieweruid"; */
									
								}else if($this->deviceType=='TIZEN' || $this->deviceType=='ROKU' || $this->deviceType=='ANDROIDTV' || $this->deviceType=='FIRETV' && ($video_type ==0 || $video_type ==4) && $ext == 'm3u8'){
									
									$file		= explode('.',$channel['uploaded_video']);
									$folder 	= explode('/',$file[0]);
									$file_name  = $file[0].'/'.$folder[2].'.mp4';
									$key 		= str_replace(['mp4' , 'MP4'] , $ext,$file_name);
									
									//$adsParam 	= "?ads.placementId={$placementId}&ads.video_id={$post_id}&ads.userid={$user_id}&ads.genreid={$genre_id}&ads.categoryid={$cate_id}";
									
									//$vastTag ="https://6beeff115b8e469290debecb37b7fa76.mediatailor.us-east-1.amazonaws.com/v1/master/fc00e2fab47eb0541ea28244378f39111fd2e11a/xandr/{$key}{$adsParam}&aws.logMode=DEBUG&ads.viewerid=vieweruid&ads.ifa=uuid";
									
									//$vastTag ="https://a9c3b2db24144085a4d9c22bbb6dc532.mediatailor.us-east-1.amazonaws.com/v1/master/fc00e2fab47eb0541ea28244378f39111fd2e11a/tizen_ctv/{$key}{$adsParam}&aws.logMode=DEBUG&ads.viewerid=vieweruid&ads.ifa=uuid"; 
									
									$pset_id   = $this->getPsetId($video_duration);
									$app_id    = $this->getAppId($this->deviceType);
									$ifs_type  = $this->getIfaType($this->deviceType);
									$user_type = isset($user_cate)? $user_cate : '';
									
									$bundle 	 = 	$this->getBundleID($this->deviceType);
									$bundleID 	 = isset($bundle['bundle_id']) ? $bundle['bundle_id'] : '';
									$storeUrl 	 = isset($bundle['store_url']) ? $bundle['store_url'] : '';
									$width 		 = 1920;
									$height 	 = 1080;

									$adsParam 	= "?ads.placementId={$placementId}&ads.w={$width}&ads.h={$height}&ads.video_id={$post_id}&ads.userid={$user_id}&ads.genreid={$genre_id}&ads.categoryid={$cate_id}&ads.app_id={$app_id}&ads.ifa_type={$ifs_type}&ads.devicetype={$this->deviceType}&ads.user_type={$user_type}&ads.video_duration={$video_duration}&ads.app_bundle={$bundleID}&ads.app_name=discovered&ads.app_store_url={$storeUrl}&ads.did=uuid&ads.us_privacy=1---&aws.logMode=DEBUG&ads.viewerid=vieweruid";
										
									//$vastTag ="https://tv.springserve.com/vast{$adsParam}";

									$vastTag = "https://1ac1cad4108b4f3c9b7dec8d54d0600c.mediatailor.us-east-1.amazonaws.com/v1/master/1677faf8e8c0e186077d1c89432d58d6082ee83a/ctv/{$key}{$adsParam}";
										
									//$vastTag ="https://6beeff115b8e469290debecb37b7fa76.mediatailor.us-east-1.amazonaws.com/v1/master/fc00e2fab47eb0541ea28244378f39111fd2e11a/xandr/{$key}{$adsParam}&aws.logMode=DEBUG&ads.viewerid=vieweruid&ads.ifa=uuid"; 

									//$vastTag ="https://8b2c391cb1d44172b859c1e7794c2fe3.mediatailor.us-east-1.amazonaws.com/v1/master/a6e2d3f84fb3f31abfbb3f01b0cb02166d2558ff/xandr/{$key}{$adsParam}&aws.logMode=DEBUG&ads.viewerid=vieweruid&ads.ifa=uuid"; //this url is used now
									

									//$vastTag ="https://55eee753c89c42d79c2bfddf86b97fe8.mediatailor.us-east-1.amazonaws.com/v1/master/1677faf8e8c0e186077d1c89432d58d6082ee83a/adnimation_android_testing_jaseem/{$key}?aws.logMode=DEBUG"; //this url is used now
									

									if($this->deviceType=='ANDROIDTV'){      
										//$homeVideos['videoFile'] = $vastTag;
									}
									
								}
							}else if($is_stream_live ==1){
								$bundle 	 = 	$this->getBundleID($this->deviceType);
								$bundleID 	 = isset($bundle['bundle_id']) ? $bundle['bundle_id'] : '';
								$storeUrl 	 = isset($bundle['store_url']) ? $bundle['store_url'] : '';
								$placementId = 813007;
								$width 		 = 1920;
								$height 	 = 1080;
								$adsParam 	 = "?ads.placementId={$placementId}&ads.w={$width}&ads.h={$height}&ads.viewerid=vieweruid&ads.video_id={$post_id}&ads.userid={$user_id}&ads.genreid={$genre_id}&ads.categoryid={$cate_id}&ads.devicetype={$this->deviceType}&ads.us_privacy=1---&ads.app_bundle={$bundleID}&ads.app_name=discovered&ads.app_store_url={$storeUrl}&ads.did=uuid";
								
								$vastTag = "{$channel['uploaded_video']}{$adsParam}&aws.logMode=DEBUG"; 
								
								if($this->deviceType=='ANDROID' || $this->deviceType=='IOS'){
									$my_fav['video_path'] = $vastTag;
									$vastTag = '';
								}
							}
							
							$my_fav['vastTag'] = $vastTag;
						
							array_push($result,$my_fav);
						
						}
						
					}
					
				}
			
			}else if($searchType == 'people'){
				//$limitData = 6;
				$accessParam = array(
							'field' => '*',
							'where' => 'keyword='.$searchKey.',not_user_uname=1,user_status=1,is_deleted=0',
							'order' => 'users.user_id,DESC',
							'limit' => $limitData+1 .','.$startOffset,
							'user_content_table_join_type'=>'right'
							);
				
				
				
				if(isset($_GET['referral_by'])){
					$accessParam['where'] = 'referral_by='.validate_input($_GET['referral_by']);
				}
				
				$profile_result = $this->query_builder->user_list($accessParam);
				
				if(isset($profile_result['users'][0])){
					$checkChanelCount = 1;
					
					unset($accessParam['limit']);
					$profile_result = $this->query_builder->user_list($accessParam);
					$total_count    = (string) sizeof($profile_result['users']);
					
					foreach($profile_result['users'] as $users){
						if($checkChanelCount++ <= $limitData){
							
							if(!empty($this->check_FanButton($users['user_id']))){
								$button = $this->check_FanButton($users['user_id']);
							}else{
								$button = '<a href="'.base_url('profile?user='.$users['user_uname']).'" class="dis_btn">Visit Profile</a>';
							}
							
							$category_name 	= !empty($users['category_name'])?$users['category_name'] :'';
							$uc_city 		= !empty($users['uc_city'])?$users['uc_city'] . ', ':'';
							$name 			= !empty($users['name'])?$users['name'] . ', ':'';/*state name*/
							$country_name 	= !empty($users['country_name'])?$users['country_name'] . ', ' :'';
							$referral_from 	= !empty($users['referral_from'])?$users['referral_from'] :'';
							
							
							$search_people=array(	'user_id'=>$users['user_id'],
													'user_uname'=>($users['user_uname']!=null)? $users['user_uname'] : 'NA',
													'user_name'=>($users['user_name']!=null)? $users['user_name'] : 'NA',
													'user_pic'=>create_upic($users['user_id'],$users['uc_pic']),
													'category_name'=>$category_name,
													'uc_city'=>$uc_city,
													'name'=>$name,
													'country_name'=>$country_name,
													'user_regdate'=>date('F-d-Y',strtotime($users['user_regdate'])),
													'isfan'=>$button['is_fan'],
													'CanIMakeFan'=>$button['CanIMakeFan']
													//''$this->setSvgIcon($referral_from)
							);
							
							if($this->deviceType =='TIZEN'){
								unset($search_people['user_uname']);
							}
							array_push($result,$search_people);
							
						}
							
					}
					
				} 		
			}	
		}
		return array('data'=>$result,'total_count'=>$total_count);
	}
	
	public function get_my_content123($searchType){
		
		$searchKey = (isset($_POST['search_query']))?$_POST['search_query']:'';
		
		// $searchKey = str_replace("'","\'",$searchKey);
		$searchKey = addslashes(validate_input($searchKey));
		// $mode_id = isset($_SESSION['website_mode']['id'])?$_SESSION['website_mode']['id']:'';  /*By default site mode*/
		$mode_id  = '';   /*IN CASE OF ALL*/
		if(isset($_POST['mode_id']) && !empty($_POST['mode_id'])){
			$mode_id  = validate_input($_POST['mode_id']); /*site mode selected by user in a serch filter page*/
		}
		
		$limitData 	= (isset($_POST['limit']))?$_POST['limit']:8;
		$startOffset = (isset($_POST['start']))?$_POST['start']:0;
		// echo $startOffset; exit;
		$total_count=0;
		$result = array();
		if(!empty($searchType)){
			if($searchType == 'video'){
				
				$field = 'channel_post_video.post_id,channel_post_video.user_id,channel_post_video.created_at,channel_post_video.title,channel_post_video.description,website_mode.mode AS web_mode,channel_post_video.age_restr,channel_post_thumb.image_name,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.post_key,users.user_name,users.user_uname,users.user_level,country.country_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.is_video_processed';	
					
				$accessParam = array(
							'field' => $field,
							'order' => 'channel_post_video.post_id,ASC',
							'where' => 'keyword='.$searchKey.',privacy_status=7,user_status=1',
							'limit' => $limitData+1 .','.$startOffset,
							);
				if(!empty($mode_id)){ 
					$accessParam['where']	.= ',mode='.$mode_id;
				}
				$count_where='';
				$join='';
				if(isset($_POST['favorite'])){               
					$accessParam = array(
						'field' 			=> 	$field,
						'JoinTableAndType'	=>	'channel_favorite_video|left',
						'where'				=> 	'favorite_user_id='.$this->get_token_uid(),
						'limit' 			=> 	$limitData+1 .','.$startOffset,
						'order' 			=> 'channel_favorite_video.fav_id,DESC',
						);
					
				}
				
				$video_result	= $this->query_builder->channel_video_list($accessParam);
								
				if(isset($video_result['channel'][0])){
					
					unset($accessParam['limit']);
					$video_result_count	= $this->query_builder->channel_video_list($accessParam);
					$total_count = sizeof($video_result_count['channel']);
					
					$checkChanelCount = 1;
					$isMyFavorite=0;
					foreach($video_result['channel'] as $channel){
						if($checkChanelCount++ <= $limitData){
							$isMyFavorite=0;
							$description = $channel['description'];
							//$description = json_decode($channel['description']) ;
							/*if(strlen($channel['description']) < 250){ 
								$description =  json_decode($channel['description']) ;
							}else{ 
								$description = substr(json_decode($channel['description']),0,250)."..." ;
							}*/
							
							$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category', array('category_id'=>$channel['user_level']));
							$user_cate=isset($sub_cat[0]['category_name'])?$sub_cat[0]['category_name'] :'';
							
							
							if($this->get_token_uid()){
								
								$post_user = array('user_id'=>$this->get_token_uid(),'channel_post_id'=>$channel['post_id']);	
								$isMyFavorite_check = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
								if(!empty($isMyFavorite_check)){
									$isMyFavorite=1;
								}
							}
							
							//$FavoriteActive = 	($isMyFavorite == 1)?'active':'';
							//$isMyFavoriteText = ($isMyFavorite == 1)?'Added To favorites':'Add To favorites';
							
							//$FilterData = $this->share_url_encryption->FilterIva($user_id,$iva_id,$image_name,trim($uploaded_video),false);
			
						
							$FilterData = $this->share_url_encryption->FilterIva($channel['user_id'],$channel['iva_id'],$channel['image_name'],$channel['uploaded_video'],false,'.m3u8',$channel['is_video_processed']);
							$ThumbImage = $FilterData['thumb'];
							
							if(!empty($channel['image_name']) && !empty($FilterData['webp'])){
				
								//$img = explode('.',$channel['image_name']);
								$pathToimg = ABS_PATH.'uploads/aud_'.$channel['user_id'].'/images/'.$channel['image_name'];
								$file_ext  = pathinfo($pathToimg , PATHINFO_EXTENSION);
								$file = str_replace($file_ext,$file_ext.'.webp',$pathToimg);
							
								if (file_exists($file)) {
									$ThumbImage = $FilterData['webp'];
								}
							}
							
							$videoFile 	= $FilterData['video'];
						
						
							$my_fav= array(	'user_pic'=>get_user_image($channel['user_id']),
											'user_id'=>$channel['user_id'],
											'user_name'=>$channel['user_name'],
											'user_cate'=>$user_cate,
											'country_name'=>ucfirst(strtolower($channel['country_name'])),
											'created_at'=>$this->time_elapsed_string($this->manageTimezone($channel['created_at']) ,false),
											'web_mode'=>$channel['web_mode'],
											'age_restr'=>$channel['age_restr'],
											'post_id'=>$channel['post_id'],
											'post_key'=>$channel['post_key'],
											'count_views'=>$channel['count_views'],
											'count_votes'=>$channel['count_votes'],
											'title'=>$channel['title'],
											'description'=>$description,
											'isMyFavorite'=>$isMyFavorite,
											'ThumbImage'=>$ThumbImage,
											'video_path'=>$videoFile,
											'tag'=>$channel['tag']
							);
						
							array_push($result,$my_fav);
						
						}
						
					}
					
				}
			
			}else if($searchType == 'people'){
				//$limitData = 6;
				$accessParam = array(
							'field' => '*',
							'where' => 'keyword='.$searchKey.',not_user_uname=1',
							'order' => 'users.user_id,DESC',
							'limit' => $limitData+1 .','.$startOffset,
							'user_content_table_join_type'=>'right'
							);
				
				
				
				if(isset($_GET['referral_by'])){
					$accessParam['where'] = 'referral_by='.validate_input($_GET['referral_by']);
				}
				
				$profile_result = $this->query_builder->user_list($accessParam);
				
				if(isset($profile_result['users'][0])){
					$checkChanelCount = 1;
					
					/*unset($accessParam['limit']);
					$profile_result = $this->query_builder->user_list($accessParam);
					$total_count = sizeof($profile_result['users']);*/

					
					$join = array('multiple' , array(
								array(	'users_content','users.user_id 	= users_content.uc_userid', 
										'right'),
								array(	'artist_category','category_id = users.user_level',
										'left'),
							));

					$cond = "users.user_role = 'member' AND (users.user_uname LIKE '".$searchKey."%' OR users.user_name LIKE '".$searchKey."%' OR users.user_email LIKE '".$searchKey."%' OR artist_category.category_name LIKE '%".$searchKey."%') AND users.user_uname != '' AND users.user_status = '1' AND users.is_deleted = '0'";

					$total_count = (string) $this->DatabaseModel->aggregate_data('users','users.user_id','COUNT',$cond,$join);
					
					foreach($profile_result['users'] as $users){
						if($checkChanelCount++ <= $limitData){
							
							if(!empty($this->check_FanButton($users['user_id']))){
								$button = $this->check_FanButton($users['user_id']);
							}else{
								$button = '<a href="'.base_url('profile?user='.$users['user_uname']).'" class="dis_btn">Visit Profile</a>';
							}
							
							$category_name 	= !empty($users['category_name'])?$users['category_name'] :'';
							$uc_city 		= !empty($users['uc_city'])?$users['uc_city'] . ', ':'';
							$name 			= !empty($users['name'])?$users['name'] . ', ':'';/*state name*/
							$country_name 	= !empty($users['country_name'])?$users['country_name'] . ', ' :'';
							$referral_from 	= !empty($users['referral_from'])?$users['referral_from'] :'';
							
							
							$search_people=array(	'user_id'=>$users['user_id'],
													'user_uname'=>$users['user_uname'],
													'user_name'=>$users['user_name'],
													'user_pic'=>create_upic($users['user_id'],$users['uc_pic']),
													'category_name'=>$category_name,
													'uc_city'=>$uc_city,
													'name'=>$name,
													'country_name'=>$country_name,
													'user_regdate'=>date('F-d-Y',strtotime($users['user_regdate'])),
													'isfan'=>$button['is_fan'],
													'CanIMakeFan'=>$button['CanIMakeFan']
													//''$this->setSvgIcon($referral_from)
							);
							array_push($result,$search_people);
							
						}
							
					}
					
				} 		
			}	
		}
		return array('data'=>$result,'total_count'=>$total_count);
	}
	
	function GobalPrivacyCond($uid){
		$cond = '';
		if(!$this->is_token_uid($uid)){   /* FOR OTHER USER	*/
			$AmIFanOfHim = $this->AmIFollowingHim($uid);  
			if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
				$cond = ' AND channel_post_video.privacy_status IN(6,7) ';/* PRIVATE,PUBLIC*/
			}else{
				$cond = ' AND channel_post_video.privacy_status IN(7) ';	/* ONLY PUBLIC*/
			}
		}
		return $cond;
	}
	
	/************* Manage time zone *************/
	function manageTimezone($time,$clock="H"){ 	 
		if(isset($_POST['TimeZoneOffset'])){
			$TimeZoneOffset = $_POST['TimeZoneOffset'];
			if($TimeZoneOffset < 0){
				$TimeZoneOffset= abs($TimeZoneOffset);
				$time = new DateTime($time);
				$time->add(new DateInterval('PT' . $TimeZoneOffset . 'M'));
				return   $time->format('Y-m-d '.$clock.':i:s');	
			}else{
				$TimeZoneOffset= abs($TimeZoneOffset);
				$time = new DateTime($time);
				$time->sub(new DateInterval('PT' . $TimeZoneOffset . 'M'));
				return   $time->format('Y-m-d '.$clock.':i:s');
			}
		}
	}
	
	function time_elapsed_string($timestamp,$full = false){
   
	  $time_ago        = strtotime($timestamp);
	  $current_time    = strtotime($this->manageTimezone(date('Y-m-d H:i:s')));
	  $time_difference = $current_time - $time_ago;
	  $seconds         = $time_difference;
	  
	  $minutes = round($seconds / 60); // value 60 is seconds  
	  $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
	  $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
	  $weeks   = round($seconds / 604800); // 7*24*60*60;  
	  $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
	  $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
					
		if ($seconds <= 60){
			return "Just Now";
		}else 
		if ($minutes <= 60){
			if ($minutes == 1){
				return "one min ago";
			}else{
				return "$minutes min ago";
			}
		} else if ($hours <= 24){
			if ($hours == 1){
				return "an hour ago";
			} else {
			return "$hours hrs ago";
			}
		} else if ($days <= 7){
			if ($days == 1){
				return "yesterday";
			} else {
				return "$days days ago";
			}
		} else if ($weeks <= 4.3){
			if ($weeks == 1){
				return "a week ago";
			} else {
				return "$weeks weeks ago";
			}
		} else if ($months <= 12){
			if ($months == 1){
				return "a month ago";
			} else {
				return "$months months ago";
			}
		} else {
			if ($years == 1){
				return "one year ago";
			} else {
				return "$years years ago";
			}
		}
	}
	

	// Convert seconds into months, days, hours, minutes, and seconds.
	private function secondsToTime($ss) {
		$s = $ss%60;
		$m = floor(($ss%3600)/60);
		$h = floor(($ss%86400)/3600);
		//$d = floor(($ss%2592000)/86400);
		//$M = floor($ss/2592000);

		// Ensure all values are 2 digits, prepending zero if necessary.
		$s = $s < 10 ? '0' . $s : $s;
		$m = $m < 10 ? '0' . $m : $m;
		$h = $h < 10 ? '0' . $h : $h;
		//$d = $d < 10 ? '0' . $d : $d;
		//$M = $M < 10 ? '0' . $M : $M;

		//return "$M:$d:$h:$m:$s";
		return $h > 0 ? "$h:$m:$s" : "$m:$s";
		
	}

	/************************************** API Common Functions ENDS ***************************************/
	
	function add_view_count(){
		
		$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
		$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
		
		if ($this->form_validation->run() == TRUE){	
			
			$post_id = $_POST['post_id'];
			$user_id = $_POST['user_id'];
			
			$table1  = 'channel_video_view_count_by_date'; 			$index1 = ' use INDEX(video_id,video_userid,view_date)';
			
			$this->db->set('count_views','`count_views`+ 1',FALSE);
			$this->db->where('post_id',$post_id);
			$this->db->update('channel_post_video');
		
			$cond_array = array('video_id'		=>	$post_id,
								'video_userid'	=>	$user_id,
								'view_date'		=>	date('Y-m-d')
								);
			
			$data_array = array('video_id'		=>	$post_id,
								'video_userid'	=>	$user_id,
								'view_date'		=>	date('Y-m-d'),
								'view_count'	=>	1
								);					
			 
			$check = $this->DatabaseModel->select_data('vid',$table1.$index1,$cond_array,1);
			if(empty($check)){
				$this->DatabaseModel->access_database($table1,'insert',$data_array);
			}else{
				$this->db->set('view_count','`view_count`+ 1',FALSE);
				$this->db->where($cond_array);
				$this->db->update($table1.$index1);
			}
			
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'It\'s done.';
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response();
	}
	
	
	 
	public function generate_share_link(){
		$resp=array();
		$this->form_validation->set_rules('target', 'Target', 'trim|required');
		if ($this->form_validation->run() == TRUE){	
			$shareData = explode('|' , $_POST['target']);
			if(count($shareData) >= 2){
				$link = $this->share_url_encryption->share_single_page_link_creator($_POST['target'] , 'encode');
				$link = str_replace('watch/', 'watch?p=' , $link);
				
				
				$sharetext = '';
				$description = '';
				$media = '';
				if($shareData[0] == 2){ //check video post
					$checkVideoData = $this->DatabaseModel->select_data('title,description','channel_post_video use INDEX(post_id)',array('post_id' => $shareData[1]) , 1);
					if(!empty($checkVideoData)){
						$sharetext = urlencode($checkVideoData[0]['title']);
						$description = urlencode($checkVideoData[0]['description']);
						//$description = urlencode(json_decode($checkVideoData[0]['description']));
					}
					$thumb = $this->DatabaseModel->select_data('user_id,image_name','channel_post_thumb',array('post_id' => $shareData[1]) , 1);
					if(isset($thumb[0]['user_id'])){
						$media = $this->share_url_encryption->FilterIva($thumb[0]['user_id'],'',$thumb[0]['image_name'],'',false)['thumb'];
					}
				}
				$link = urlencode($link);
				$resp = array('link' =>  array(
								'main' 		=> urldecode($link),	
								'facebook' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$link,
								'twitter' 	=> 'https://twitter.com/share?url='.$link.'&text='.$sharetext.'&via=Discovered.TV&hashtags=Discovered.TV',
								'pinterest' => 'https://pinterest.com/pin/create/button/?url='.$link.'&media='.urlencode($media).'&description=Discovered.TV',
								'linkedin' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$link.'&title='.$sharetext.'&summary='.$description.'&source=Discovered.TV',
							) 
						);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Share links.';		
			}else{
				$this->respMessage ='Something went wrong please try again.';
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	
	
	/*************** Get genre slider video ********************/
	public function get_genre_video(){
		
		$data=array();
		$resp=array();
		$this->form_validation->set_rules('genre_id', 'Genre id', 'trim|required');
		if ($this->form_validation->run() == TRUE){
			$genre_id= $_POST['genre_id'];	
			$start 	=isset($_POST['start'])?$_POST['start']:0;
			$limit 	=isset($_POST['limit'])?$_POST['limit']:5;
			$level = 'genre';
			if(isset($_POST['genre_level']) && $_POST['genre_level']==2){ 
				$level ='sub_genre';
			}				
			$data['most_popular_videos'] 	= array();
			$data['top_videos_ofthe_month'] = array();
			$data['new_realeased_video'] 	= array();
			
			$genreDetail = $this->DatabaseModel->select_data('genre_id,genre_name','mode_of_genre use INDEX(genre_id)',array('genre_id'=>$genre_id),1);
			$genre_name = '';
			if(!empty($genreDetail)){
				$genre_name=$genreDetail[0]['genre_name'];
			}
			
			$field ="channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_duration,channel_post_video.genre,channel_post_video.video_type,users.user_level,mode_of_genre.genre_name";
			
			$where ="channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status IN(7) AND channel_post_video.$level = {$genre_id} AND users.user_status = 1";
				  
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
									  'left'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
								array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),

							)
						);
						
			$order=array('channel_post_video.created_at','DESC');
			/*START OF NEW REALEASED CHANNEL VIDEO*/
			$new_realeased_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
			$data['new_realeased_video'] = array('slider_type'=>'new_realeased_video',
												 'type'=>ucwords(strtolower('NEW RELEASED '.$genre_name.' VIDEOS')),
												 'slider'=>$this->swiper_slider($new_realeased_video, true)
												);
			
			 
			/*END OF NEW REALEASED CHANNEL VIDEO*/
			
			
			$order=array('channel_post_video.count_views','DESC');
			
			/*START OF MOST POPULAR CHANNEL VIDEO*/
			$most_popular_videos = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
			$data['most_popular_videos'] = array('slider_type'=>'most_popular_videos',
												 'type'=>ucwords(strtolower('MOST POPULAR '.$genre_name.' VIDEOS')),
												 'slider'=>$this->swiper_slider($most_popular_videos, true)
												);
												    
			/*END OF MOST POPULAR CHANNEL VIDEO*/	
			
			/*START OF TOP VIDEO OF THE MONTH CHANNEL VIDEO*/
			$where .=" AND channel_post_video.created_at LIKE '%".date('Y-m')."%'";
			$top_videos_ofthe_month = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit ,$start),$join,$order);
			$data['top_videos_ofthe_month'] =array('slider_type'=>'top_of_the_month',
												   'type'=>ucwords(strtolower('TOP '.$genre_name.' VIDEOS OF THE MONTH')),
												   'slider'=>$this->swiper_slider($top_videos_ofthe_month, true)
												   );
			/*END OF TOP VIDEO OF THE MONTH CHANNEL VIDEO*/
			$resp['homeVideos']=[];
			if(!empty($most_popular_videos)){
				$resp['homeVideos'][]=$data['most_popular_videos'];
			}
			if(!empty($top_videos_ofthe_month)){
				$resp['homeVideos'][]=$data['top_videos_ofthe_month'];
			}
			if(!empty($new_realeased_video)){
				$resp['homeVideos'][]=$data['new_realeased_video'];
			}
			$web_mode = $this->valuelist->mode();
			if(isset($_POST['mode_id']) && !empty($_POST['mode_id'])){
				$mode = ($web_mode[$_POST['mode_id']])? $web_mode[$_POST['mode_id']] : 'music';
				$resp['cover_video'] 	=  	$this->get_cover_video('homepage',$mode);
			}
			
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Genre video.';
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		   						
		$this->show_my_response($resp);
	}
	
	
	/************* Upload channel video ************/
	
	public function upload_channel_video(){
		$resp=array();
		$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
		$this->form_validation->set_rules('video_url', 'Video Url', 'trim|required');
		$this->form_validation->set_rules('video_key', 'Video Key', 'trim|required');
		if ($this->form_validation->run() == TRUE){
			
			$uid =$_POST['user_id'];
			
			try {
				
				/* START OF CREATING MULTIPLE THUMB FOR A VIDEO*/ 
				$ThumbPath = "./uploads/aud_{$uid}/images/";
				$imgArr = $this->audition_functions->createChannelThumb(AMAZON_URL.$_POST['video_key'],'1080',$ThumbPath,'jpg'); //1080*608
				
				$channel_array = array(	
										'uploaded_video'=>$_POST['video_key'],
										'user_id' =>$uid,
										'created_at'=>date('Y-m-d H:i:s')
									  );
				
				if(isset($_SESSION['video_duration'])){
					$channel_array['video_duration'] = $_SESSION['video_duration'];
					unset($_SESSION['video_duration']);
				}
				
				$pubId = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
				
				$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$pubId,'encode','id');
				$this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$check[0]), array('post_id'=>$pubId));
				
				$this->query_builder->changeVideoCount($uid,'increase'); 
				
				$thumArray = [];
				$this->load->library('convert_image_webp');
				for($i=0;$i<sizeof($imgArr);$i++){
					
					if(file_exists($ThumbPath.$imgArr[$i]))
					$this->convert_image_webp->convertIntoWebp($ThumbPath.$imgArr[$i]);
					//'294','217',
					
					if(file_exists($ThumbPath.$imgArr[$i]))
					$this->audition_functions->resizeImage('417','417',$ThumbPath.$imgArr[$i],'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
					
					$img = explode('.',$imgArr[$i]);
					
					$path = $ThumbPath.$img[0].'_thumb.'.$img[1];
					if(file_exists($path))
					$this->convert_image_webp->convertIntoWebp($path);
					
					$insertArr = array('post_id'=> trim($pubId),'image_name' =>$imgArr[$i],'user_id'=>$uid);
					
					$insertArr['active_thumb'] = ($i==0)? 1 : 0 ;  
					
					$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',$insertArr, '');
					
					$thumArray[$i] = array('thumb_id'=>$thumb_id,'name'=>$img[0].'_thumb.jpg');
				}
				upload_all_images($uid);  
				/* END OF CREATING MULTIPLE THUMBS FOR A VIDEO*/
				//$resp = array('pubId'=>$pubId,'thumbs'=>$thumArray);
				$resp =array('pubId'=>$pubId,'thumbs'=>$this->getChanneThumbsCommon($pubId));
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Video uploaded successfully.';
				
			}catch(Exception $e){
				$this->respMessage = $e->getMessage();
			}
				
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response($resp);
	}
	
	
	public function upload_channel_video_old(){
		$resp=array();
		$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
		if ($this->form_validation->run() == TRUE){
			
			$uid =$_POST['user_id'];
			if(isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != ''){
				
				try {
					/*START OF UPLOADING VIDEO ON aws SERVER*/
					$rns = $this->common->generateRandomString(20);
					$ext  = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);

					$name = $rns.'.'.$ext;

					$amazon_path = "aud_{$uid}/videos/{$name}";
					// $res = s3_upload_object_ad($_FILES['userfile']['tmp_name'],$amazon_path);
					$res = multipartUploader_ad($_FILES['userfile']['tmp_name'],$amazon_path);
					// print_r($res);die;
					/*END OF UPLOADING VIDEO ON aws SERVER*/
					if(isset($res['url'])){
						/* START OF CREATING MULTIPLE THUMB FOR A VIDEO*/ 
						$ThumbPath = "./uploads/aud_{$uid}/images/";
						$imgArr = $this->audition_functions->createChannelThumb($res['url'],'1080',$ThumbPath,'jpg'); //1080*608
						
						$channel_array = array(	
												'uploaded_video'=>$res['key'],
												'user_id' =>$uid,
												'created_at'=>date('Y-m-d H:i:s')
											  );
						
						if(isset($_SESSION['video_duration'])){
							$channel_array['video_duration'] = $_SESSION['video_duration'];
							unset($_SESSION['video_duration']);
						}
						
						$pubId = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
						
						$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$pubId,'encode','id');
						$this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$check[0]), array('post_id'=>$pubId));
						
						$thumArray = [];
						$this->load->library('convert_image_webp');
						for($i=0;$i<sizeof($imgArr);$i++){
							
							if(file_exists($ThumbPath.$imgArr[$i]))
							$this->convert_image_webp->convertIntoWebp($ThumbPath.$imgArr[$i]);
							//'294','217',
							$this->audition_functions->resizeImage('417','417',
							$ThumbPath.$imgArr[$i],'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
							
							$img = explode('.',$imgArr[$i]);
							
							$path = $ThumbPath.$img[0].'_thumb.'.$img[1];
							if(file_exists($path))
							$this->convert_image_webp->convertIntoWebp($path);
							
							$insertArr = array('post_id'=> trim($pubId),'image_name' =>$imgArr[$i],'user_id'=>$uid);
							
							$insertArr['active_thumb'] = ($i==0)? 1 : 0 ;  
							
							$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',$insertArr, '');
							
							$thumArray[$i] = array('thumb_id'=>$thumb_id,'name'=>$img[0].'_thumb.jpg');
						}
						/* END OF CREATING MULTIPLE THUMBS FOR A VIDEO*/
						//$resp = array('pubId'=>$pubId,'thumbs'=>$thumArray);
						$resp =array('pubId'=>$pubId,'thumbs'=>$this->getChanneThumbsCommon($pubId));
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Video uploaded successfully.';
					}else{
						$this->respMessage ='Something went wrong please try again.';
					}
				}catch(Exception $e){
					$this->respMessage = $e->getMessage();
				}
				
			}else{
				$this->respMessage ='Please select an video file.';
			}
		}else{
			$this->respMessage = $this->single_error_msg();
		}
		
		$this->show_my_response($resp);
	}
	
	
	/************* Submit channel video form ************/
	public function submit_channel_form(){
			$resp =array();
			$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
			$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
			$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
			$this->form_validation->set_rules('genre','Genre', 'trim|required');
			$this->form_validation->set_rules('category', 'Category', 'trim|required');
			$this->form_validation->set_rules('language', 'Language', 'trim|required');
			$this->form_validation->set_rules('age_restr', 'Age', 'trim|required');
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			$this->form_validation->set_rules('tag', 'Tag', 'trim|required');
			if ($this->form_validation->run() == TRUE)
			{
			
				$post_id 					= 	trim($_POST['post_id']);
				unset($_POST['post_id']);
				$_POST['description'] 		= 	$_POST['description'];
				//$_POST['description'] 		= 	json_encode($_POST['description']);
				$_POST['complete_status'] 	= 	$_POST['active_status'] =	1;
				$_POST['slug'] 				= 	slugify(strtolower(str_replace(" ","-",$_POST['title'])));
				$_POST['sub_genre'] 		=   isset($_POST['subgenre_id'])?$_POST['subgenre_id']:'';
				unset($_POST['subgenre_id']);
				$tag = json_decode($_POST['tag']);
				$_POST['tag'] = implode(',' ,$tag);
				
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
				
				$user_id = $_POST['user_id'];
				unset($_POST['user_id']);

				if($this->DatabaseModel->access_database('channel_post_video','update',$_POST, array('post_id'=>$post_id)) > 0){
					
					if( (isset($_POST['social'])  && $_POST['social'] == 1) || ($social_cover_video == 1) ){
							$uid 	= 	$user_id;
							$video 	= 	$this->DatabaseModel->select_data('uploaded_video','channel_post_video use INDEX(post_id)',array('post_id'=>$post_id),1);
							
							$uploaded_video =	$video[0]['uploaded_video'];
							
					}
					
					if(isset($_POST['social'])  && $_POST['social'] == 1){
							$video 	= 	explode('videos/',$uploaded_video);
							
							$image 	= $this->DatabaseModel->select_data('image_name','channel_post_thumb',array('post_id'=>$post_id,'active_thumb' => 1));
							$image = (isset($image[0]) && !empty($image[0]))? $image[0]['image_name'] : '';
							
							
							$publish_data = array(	'pub_uid'		=>	$uid,
													'pub_content'	=>	$_POST['title'],
													'pub_media'		=>	$video[1].'|video|'.$image,
													'pub_status'	=>	$_POST['privacy_status'],
													'channel_post_id'=> $post_id,
													'pub_date'		=>	date('Y-m-d H:i:s'));
													
							$this->DatabaseModel->access_database('publish_data','insert',$publish_data);
						
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
						
						
						$url ='';
						if(!empty($uploaded_video)){
							$url = AMAZON_URL.$uploaded_video;
						}
						$resp['cover_video'] = $url;
					}
					
					if(isset($_POST['privacy_status']) && $_POST['privacy_status'] !=5){ //Only me = 7
						$this->audition_functions->sendNotiOnMonetizeVideo($user_id,$post_id,$_POST['title']);
					}
					/*if($updatFrmMyChnl == 0){
						echo 1;
					}else{
						$_POST['post_id']  		= $post_id;
						$_POST['update_form']  	= $update_form;
						return 1;
					}*/
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Data updated successfully.';
				}else{
					/*if($updatFrmMyChnl == 0){
						echo 0;
					}else{
						$_POST['post_id']  		= $post_id;
						$_POST['update_form']  	= $update_form;
						return 0;
					}*/
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'No data to save.';
					
				}
			}
			else
			{
				$this->respMessage =$this->single_error_msg();
			}
			
		$this->show_my_response($resp);	
	}
	
	/************* Get channel thumb image ************/
	public function get_channel_thumbs(){
		$resp =array();
		$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{
			$thumArray = $this->getChanneThumbsCommon($_POST['post_id']);
			if(!empty($thumArray)){
				
				$resp = array('thumbs'=>$thumArray);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Thumb images list.';
				
			}else{
				$this->respMessage = 'Thumb images not found.';
			}
		}else
		{
			$this->respMessage =$this->single_error_msg();
		}
		
		$this->show_my_response($resp);	
	}
	
	/************* Get channel thumb image common function ************/
	function getChanneThumbsCommon($pubid=''){
		$thumArray = [];
		if ($pubid !='')
		{
			$join  = array(
						'multiple',
						array(
							array('channel_post_video' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
						
						)
					);
			$field = 'channel_post_thumb.thumb_id,channel_post_thumb.active_thumb,channel_post_thumb.image_name,channel_post_thumb.user_id,channel_post_video.iva_id';
			$imgArr = $this->DatabaseModel->select_data($field,'channel_post_thumb',array('channel_post_thumb.post_id'=>$pubid),'',$join);
			
			
			if(isset($imgArr[0])){
				for($i=0;$i<sizeof($imgArr);$i++){
					
					$FilterData = $this->share_url_encryption->FilterIva($imgArr[$i]['user_id'],$imgArr[$i]['iva_id'],$imgArr[$i]['image_name'],'',true);
					
					$thumArray[$i] = array('thumb_id'=>$imgArr[$i]['thumb_id'],'name'=>$FilterData['thumb'],'active_thumb'=>$imgArr[$i]['active_thumb']);
				}
				return $thumArray;
				 
			}else{
				return  $thumArray;
			}
		}else{
			return  $thumArray;
		}		
	}
	
	
	/************* Upload channel thumb image ************/
	function upload_channel_thumb(){
		$resp=array();
		$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
		$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{
			if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
				$uid =$_POST['user_id'];
				$config['upload_path'] = './uploads/aud_'.$uid.'/images/';
				$config['encrypt_name'] = true;
				$config['allowed_types'] = 'jpg|png|gif|jpeg';
				//$config['max_size']      = 8192 ; // 8192 bytes is equal to 8 KB
				$config['max_size']      = 10485760; // 10 MB in bytes
				$config['min_width']     = 640 ;
				$config['min_height']    = 474 ;

				try{	
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					
					if ($this->upload->do_upload('image'))
					{
						$uploaddata=$this->upload->data();
						$name = $uploaddata['raw_name'];
						$img_ext = $uploaddata['file_ext'];
						$imageName = $name.$img_ext;
						
						$path = $config['upload_path'].$imageName ; 
						$resize = $this->audition_functions->resizeImage('1080','608',$path,'',$maintain_ratio = false,$create_thumb= false);
						
						if($resize != 0 ){
							
							$this->load->library('convert_image_webp');
							
							if(file_exists($path))
							$this->convert_image_webp->convertIntoWebp($path);
							
							// $resize = $this->audition_functions->resizeImage('294','217',$config['upload_path'].$imageName,'',$maintain_ratio = false,$create_thumb= TRUE);

							if(file_exists($path))
							$resize =$this->audition_functions->resizeImage('294','217',$config['upload_path'].$imageName,'',false,TRUE,95);	
							
							if($resize != 0 ){
								
								$img = explode('.',$imageName);
								$path =$config['upload_path'].$img[0].'_thumb.'.$img[1];	
								
								if(file_exists($path))
								$this->convert_image_webp->convertIntoWebp($path);
						
								$this->DatabaseModel->access_database('channel_post_thumb','update',array('active_thumb'=>0), array('post_id'=>trim($_POST['post_id'])));
								if(!isset($_POST['update_form'])){
									$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',array('post_id'=> trim($_POST['post_id']),'image_name' =>$imageName,'user_id'=>$uid,'active_thumb'=>1), '');
									
									upload_all_images($uid);
									$thumArray = array('thumb_id'=>$thumb_id,'name'=>$name.'_thumb'.$img_ext);
									$resp = array('thumbs'=>$thumArray);
									$this->statusCode = 1;
									$this->statusType = 'Success';
									$this->respMessage = 'Image uploaded successfully.';

								}else{
									
									$video = $this->DatabaseModel->select_data('post_id','channel_post_thumb',array('post_id'=>trim($_POST['post_id'])));
									
									if(empty($video)){
										$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',array('post_id'=> trim($_POST['post_id']),'image_name' =>$imageName,'user_id'=>$uid,'active_thumb'=>1), '');
										return 1;
									}else{
										if($this->DatabaseModel->access_database('channel_post_thumb','update',array('image_name'=>$imageName,'active_thumb'=>1), array('post_id'=>trim($_POST['post_id']))) > 0){
											return 1;
										}
									}
									
								}
							}else{
								$this->respMessage ='Something went wrong please try again.';
							}
						}else{
							$this->respMessage ='Something went wrong please try again.';
						}
					}
					else {
						$image_info = getimagesize($_FILES['image']['tmp_name']);
						$error = strip_tags($this->upload->display_errors());
						$imgInfo = '';
						if(isset($image_info[0]) && isset($image_info[1])){
							$imgInfo =', but your image dimensions are '.$image_info[0].'x'.$image_info[1];
						}
						$this->respMessage =$error.' The required minimum dimensions of the image are '.$config['min_width'].'x'.$config['min_height'].$imgInfo;
					}
				}catch(Exception $e){
					$this->respMessage = $e->getMessage();
				}
			}
			else {
				$this->respMessage ='Please select an image file.';
			}
		}else
		{
			$this->respMessage =$this->single_error_msg();
		}	
		$this->show_my_response($resp);	
	}
	
	/************* Update channel thumb image status ************/
	function update_thumb_status(){
		$resp=array();
		$this->form_validation->set_rules('thumb_id', 'Thumb id', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{
			$Thumbs = $this->DatabaseModel->select_data('post_id ','channel_post_thumb',array('thumb_id'=>$_POST['thumb_id']),1);
			
			if(!empty($Thumbs)){
				$post_id = $Thumbs[0]['post_id'];
				
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
						 
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Thumb selected successfully.';
				}else{
					$this->respMessage ='Something went wrong please try again.';
				}
				
			}else{
				$this->respMessage ='Something went wrong please try again.';
			}
		}else
		{
			$this->respMessage =$this->single_error_msg();
		}	
		$this->show_my_response($resp);
	}
	
	
	public function getAllModes(){
		
		$condd = "status = 1";
		/* if($this->deviceType =='IOS'){
			$condd = "mode_id NOT IN(9) AND status = 1";
		//} */
		$condd = "mode_id NOT IN(6) AND status = 1";
		$website_mode	= $this->DatabaseModel->select_data('mode_id,mode,status,default_mode_status as default_mode,icon','website_mode',$condd);
		if(!empty($website_mode)){
			
			foreach($website_mode as $key=>$value){
				$icon = explode('.',$value['icon']);
			    $iconThumb = $icon[0].'_thumb.'.$icon[1];
				$website_mode[$key]['icon']=CDN_BASE_URL.'repo/images/mode_icon/'.$iconThumb;
				$website_mode[$key]['svg_icon']=$this->api_common_function->getSvgImage($icon[0]); 
				//$website_mode[$key]['svg_icon']=file_get_contents( base_url().'repo/images/mode_icon/'.$icon[0].'.svg' ); //base_url().'repo/images/mode_icon/'.$icon[0].'.svg';
			}
			 
			/* for($i=1; $i<10; $i++){
				
				$website_mode[] = array(
				"mode_id"=> $i,
				"mode"=> "Test Mode".$i,
				"status"=> "1",
				"default_mode"=> "0",
				"icon"=> "https://test.discovered.tv/repo/images/mode_icon/social_thumb.png",
				"svg_icon"=> "https://test.discovered.tv/repo/images/mode_icon/social.svg",
				);
				 
				
			} */
		}
		
		$resp['website_mode'] =$website_mode;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = 'Modes list.';
		$this->show_my_response($resp); 
		
	}
	
	/**************** Get language STARTS **********************/
	function get_language_list(){
		$resp=array();
		
		
		if(isset($_POST['uc_type']) && !empty($_POST['uc_type'])){
			$uc_type = $_POST['uc_type'];

			if(isset($_POST['user_level']) && !empty($_POST['user_level'])){
				$account_type = $_POST['user_level'];
				$category_id  = $account_type;
				$othercat 	  = ( ($category_id == 1) ? 149 : (  ($category_id == 2 ) ? 150 :   151 ) ) ;  /* THIS ARE ALL THE OTHER CATEGOROY OPTION BY DEFAULT*/
				$uc_type = $uc_type.','.$othercat;
			}

			$cond = "category_id IN({$uc_type}) AND status = 1";
				
			$resp['catDetail'] 		= $this->DatabaseModel->select_data('category_name,category_id','artist_category',$cond,'','',array('category_name','ASC'));
		}
		
		
		/*$website_mode	= $this->DatabaseModel->select_data('mode_id,mode,status,default_mode_status as default_mode,icon','website_mode',array('mode_id !='=>8, 'status'=>1));*/
		
		$condd = "mode_id NOT IN(4,6,9,10) AND status = 1";
		$website_mode	= $this->DatabaseModel->select_data('mode_id,mode,status,default_mode_status as default_mode,icon','website_mode',$condd);
		
		if(!empty($website_mode)){
			
			foreach($website_mode as $key=>$value){
				$icon = explode('.',$value['icon']);
			    $iconThumb = $icon[0].'_thumb.'.$icon[1];
				$website_mode[$key]['icon']=base_url().'repo/images/mode_icon/'.$iconThumb;
				$website_mode[$key]['svg_icon']=$this->api_common_function->getSvgImage($icon[0]); 
				//$website_mode[$key]['svg_icon']=file_get_contents( base_url().'repo/images/mode_icon/'.$icon[0].'.svg' ); //base_url().'repo/images/mode_icon/'.$icon[0].'.svg';
				if($value['mode_id'] == 3){
					$website_mode[$key]['default_mode'] = 1;
				}
			}

			if($this->deviceType=='TIZEN' || $this->deviceType=='ROKU'){
				$keys = array_column($website_mode, 'default_mode');
				array_multisort($keys, SORT_DESC, $website_mode);
			}
			
			// for($i=1; $i<10; $i++){
				
				// $website_mode[] = array(
				// "mode_id"=> $i,
				// "mode"=> "Test Mode".$i,
				// "status"=> "1",
				// "default_mode"=> "0",
				// "icon"=> "https://test.discovered.tv/repo/images/mode_icon/social_thumb.png",
				// "svg_icon"=> "https://test.discovered.tv/repo/images/mode_icon/social.svg",
				// );
			// }
		}
		
		$resp['website_mode'] =$website_mode;
		
		
		if(isset($_POST['parentgenre_id']) && !empty($_POST['parentgenre_id'])){
			$resp['subgenreList'] = $this->getSubGenreList($_POST['parentgenre_id']);
		}
		
		
		//$resp['ages'] = array('15+','18+','Unrestricted','PG','R');
		$resp['ages'] = array('13+','17+');
		
		if($this->deviceType=='IOS' || $this->deviceType=='ANDROID'){
			$resp['ages'] = $this->audition_functions->age();
		}
		
		$privacy_list=array();
		$privacy = $this->audition_functions->post_status();
		foreach($privacy as $key=>$p){
			$privacy_list[] =array('id'=>$key,'status'=>$p); 
		}
		
		$resp['privacy_status'] =$privacy_list; 
		
		$resp['pixabay_key'] 	= PIXABAY_KEY;

		$language_list 	= $this->DatabaseModel->select_data('*','language_list',array('status'=>1),'','',array('value','ASC'));	 
		
		if(!empty($language_list)) {
			$resp['language_list'] = $language_list;
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Language list.'; 
		}
		else {
			$this->respMessage = 'Language list not found.';
		}
		$this->show_my_response($resp); 
	}
	
	/******** Get video tag list *******/
	public function get_tag_list(){
		$tag_array1=[];	
		$tag_array=[];	
		$resp=array();
		$this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{
			$tag_list = $this->DatabaseModel->select_data('tag','channel_post_video','',5,'','',array('tag',$_POST['keyword']));
			if(!empty($tag_list)){
				foreach($tag_list  as $list){
					
					$tags = explode(',',$list['tag'] );
					foreach($tags as $tag){
						//$tag_array[]= $tag;
						//if (strpos($tag, $_POST['keyword']) !== FALSE){
						//	$tag_array[]= $tag;
						//}
						 
						if (strpos($tag, $_POST['keyword']) !== false && strpos($tag, $_POST['keyword'])==0) {
							array_unshift($tag_array1, $tag);             // add to beginning
						} else if (strpos($tag, $_POST['keyword']) !== false && strpos($tag, $_POST['keyword'])>0) {
							array_unshift($tag_array, $tag);              // add to beginning
						}else{
							$tag_array[] = $tag;                         // add to end
						}
					}
				}
				$tag_array = array_merge($tag_array1,$tag_array);
				$resp['tag_list'] = array_values(array_unique($tag_array));
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'tag list.';
			}else{
				$this->respMessage = 'tag not found.';
			}
		}else{
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
		
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
	
	
	public function get_single_video(){
		 
		$resp=array();
		$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{
			$limit =1;
			$vidList = $_POST['post_id'];
			$where = "channel_post_video.post_id IN($vidList) AND ";
			
			if (!is_numeric($vidList)) {
				$where ="channel_post_video.post_key = '".$vidList."' AND "; 
			}
			
			$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.count_views,channel_post_video.age_restr,channel_post_video.mode,channel_post_video.video_duration,channel_post_video.is_stream_live,channel_post_video.genre,channel_post_video.video_type,users.user_level,mode_of_genre.genre_name,channel_post_video.category,channel_post_video.language,channel_post_video.privacy_status,channel_post_video.social";
							
			$where .="channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
									   
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
								array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
								array('mode_of_genre use INDEX(genre_id)','mode_of_genre.genre_id 	= channel_post_video.genre','left'),

							)
						);
			
			
			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join);
					
			if(!empty($videoData)){
				$video = $this->swiper_slider($videoData,true);
				$resp = array('single_video'=>isset($video[0])?$video[0]:'');
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Single video.';
			}else{
				$this->respMessage = 'Video not found.';
			}
		}else{
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
		
	}
	
	/************** Delete channel video ***************/
	
	public function delete_channel_Video(){
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
			if ($this->form_validation->run() == TRUE)
			{	
				$uid = $TokenResponce['userid'];
				$post_id = $_POST['post_id'];
				$where_array 	= array('post_id'=>$post_id,'user_id'=>$uid);
				$publish_data 	= $this->DatabaseModel->select_data('uploaded_video,social','channel_post_video',$where_array,1);
		
				if(!empty($publish_data[0]['uploaded_video']))
				{
					$old_key 		= trim($publish_data[0]['uploaded_video']);
				
					$isIvaVideo  	= (count(explode('://' , $old_key)) > 1)?1:0;
				
					if($isIvaVideo  == 0){
				
						$key 		= explode('.',$old_key)[0];
					
						if($publish_data[0]['social'] == 0){
							s3_delete_object(array($old_key));
							s3_delete_matching_object(trim($key),TRAN_BUCKET);
						}
					}
				
					$actCond 		= array('active_thumb'=>0);
					
					$merge_cond 	= array_merge($where_array,$actCond);

					$kpath = 'aud_'.$uid.'/images/';
					
					$previous_thumb = $this->DatabaseModel->select_data('image_name','channel_post_thumb',$where_array);
					if(!empty($previous_thumb)){
						foreach($previous_thumb as $thumb){
							$image_name = trim($thumb['image_name']);
							if(!empty($image_name)) {
								if(!empty($image_name)) {
									$img = explode('.',$image_name);
									$img = $img[0].'_thumb.'.$img[1];
									s3_delete_object(array($kpath.$image_name,$kpath.$image_name.'.webp',$kpath.$img,$kpath.$img.'.webp' ));
								}
							}
						}
						
						$this->DatabaseModel->access_database('channel_post_thumb','delete','', $merge_cond);
					}
			
					$previous_cast = $this->DatabaseModel->select_data('image_name','channel_cast_images',$where_array );
					if(!empty($previous_cast)){
						$castAry = [];
						foreach($previous_cast as $cast){
							if($cast['image_name'] != '' ) {
								array_push($castAry, $kpath.$cast['image_name']);
							}
						}
						s3_delete_object($castAry);
						$this->DatabaseModel->access_database('channel_cast_images','delete','', $where_array );
					}
					
					$this->DatabaseModel->access_database('channel_post_video','update',array('delete_status'=>1),$where_array );
					$this->query_builder->changeVideoCount($uid,'decrease');
					
					$this->deleteVideoFromPlaylist($post_id, $uid);

					$this->deleteVideoFromHomesliders($post_id);

					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Channel video deleted successfully.';
				}else{
					$this->respMessage ="Selected video not found.";
				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}				
			 
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
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
	
	
	public function delete_channel_Video_old(){
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$this->form_validation->set_rules('post_id', 'Post id', 'trim|required');
			if ($this->form_validation->run() == TRUE)
			{	
				$uid = $TokenResponce['userid'];
				//if(isset($_POST['post_id']) && !empty($uid) ){
			
				$post_id = $_POST['post_id'];
				$where_array 	= array('post_id'=>$post_id,'user_id'=>$uid);
				$publish_data 	= $this->DatabaseModel->select_data('uploaded_video,social','channel_post_video',$where_array,1);
		
				if(!empty($publish_data[0]['uploaded_video']))
				{
					$old_key 		= trim($publish_data[0]['uploaded_video']);
				
					$isIvaVideo  	= (count(explode('://' , $old_key)) > 1)?1:0;
				
					if($isIvaVideo  == 0){
				
						$key 		= explode('.',$old_key)[0];
					
						if($publish_data[0]['social'] == 0){
							
							s3_delete_object(array($old_key));
							s3_delete_matching_object(trim($key),TRAN_BUCKET);
							// s3_delete_matching_object(trim($key),'discovered.tv.thumbs');
							
						}
					}
				
					$actCond 		= array('active_thumb'=>0);
					$merge_cond 	= array_merge($where_array,$actCond);

					$pathToImages = ABS_PATH .'uploads/aud_'.$uid.'/images/';
					
					$previous_thumb = $this->DatabaseModel->select_data('image_name','channel_post_thumb',$merge_cond);
					if(!empty($previous_thumb)){
						foreach($previous_thumb as $thumb){
							$image_name = trim($thumb['image_name']);
							if(!empty($image_name)) {
								if(file_exists($pathToImages.$image_name)){
									unlink($pathToImages.$image_name);
								}
								if(file_exists($pathToImages.$image_name.'.webp')){
									unlink($pathToImages.$image_name.'.webp');
								}
								
								$img = explode('.',$image_name);
								$img = $img[0].'_thumb.'.$img[1];
								
								if(file_exists($pathToImages.$img)){
									unlink($pathToImages.$img);
								}
								if(file_exists($pathToImages.$img.'.webp')){
									unlink($pathToImages.$img.'.webp');
								}
							}
						}
						
						$this->DatabaseModel->access_database('channel_post_thumb','delete','', $merge_cond);
					}
			
					$previous_cast = $this->DatabaseModel->select_data('image_name','channel_cast_images',$where_array );
					if(!empty($previous_cast)){
						foreach($previous_cast as $cast){
							if($cast['image_name'] != '' &&  file_exists($pathToImages.$cast['image_name'])) {
								unlink($pathToImages.$cast['image_name']);
							}
						}
						$this->DatabaseModel->access_database('channel_cast_images','delete','', $where_array );
					}
					
					//$this->DatabaseModel->access_database('channel_video_ads_count','delete','', array('post_view_id'=>$post_id) );
					//$this->DatabaseModel->access_database('channel_video_view_count','delete','', array('post_view_id'=>$post_id) );
					
					// $this->DatabaseModel->access_database('channel_post_video','delete','', $where_array);
					$this->DatabaseModel->access_database('channel_post_video','update',array('delete_status'=>1),$where_array );
	
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Channel video deleted successfully.';
				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}				
			 
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	
	
	public function dashboard(){
		$data=array();
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$uid = $TokenResponce['userid'];
			
			$data['advertising'] 		= $this->DatabaseModel->aggregate_data('channel_video_view_count_by_date','creator_share_amount','SUM',array('video_userid'=>$uid));
		
			$data['videouploaded'] 		= $this->DatabaseModel->aggregate_data('channel_post_video','post_id','COUNT',array('user_id'=>$uid));
		
			$data['viewCount'] 			= $this->DatabaseModel->aggregate_data('channel_video_view_count_by_date','ads_count','SUM',array('video_userid'=>$uid));
			
			$data['AverageEarningPerVideo'] = ($data['videouploaded'] != 0)?$data['advertising'] / $data['videouploaded']:0;
			
			$data['AverageEarningPerVideo'] = number_format((float)$data['AverageEarningPerVideo'], 4, '.', '');
			
			
			$resp = array('data'=>$data);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Dashboard data.';
			
		}else{
			
			$this->respMessage = $TokenResponce['message'];
			
		}
		$this->show_my_response($resp);
		
	}
	
	
	public function getMyPlayList(){
		$resp=array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$uid = $TokenResponce['userid'];
		
			$joinsPlaylist  = array
						(
							'multiple',
							array(
								array('channel_post_video','channel_post_video.post_id = channel_video_playlist.first_video_id','left'),
								array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id',
								'left'),
							)
						);
						
			$wherePlaylist = 'channel_video_playlist.user_id = '.$uid . ' AND channel_post_thumb.active_thumb = 1 '. $this->common->GobalPrivacyCond($uid,'channel_video_playlist'); 
				 
			$OrderPlaylist = array('channel_video_playlist.playlist_id','DESC');
				
			$resp['playlist'] = $this->DatabaseModel->select_data("channel_video_playlist.first_video_id,channel_post_video.user_id,channel_video_playlist.playlist_id,channel_video_playlist.title,channel_video_playlist.video_ids,channel_post_thumb.image_name",'channel_video_playlist',$wherePlaylist,10,$joinsPlaylist,$OrderPlaylist);
			
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	
	
	
	
	
	public function check_firebasenoti($to_user){
		
		//$resp =  $this->audition_functions->sendNotiOnCreateSocialPost($to_user, 2843,'xzczxc c z','https://s3-cdn.discovered.tv/aud_215/images/a59aba94392d00ad851696589343bab7.jpg' , $status = 1 );
		
		//$resp =  $this->audition_functions->sendNotiOnBecomeAfan($from_uid=270, $to_user=215,'',$video_title='',$status = 1 );
		
		//$resp =  $this->audition_functions->sendNotiOnLiveStreaming($to_user,$post_id="2464",$video_title='BLOOD BROTHERS#1_OFFICIAL TRAILER',$status = 2 );
		
		//$resp = $this->audition_functions->sendNotiOnMonetizeVideo($to_user,$post_id='1370', $video_title='teting by nitesh');
		
		//print_R($resp);die;
		//die;
		//$resp = $this->audition_functions->sendNotiOnJoinedNewIcon($to_user,1);
		
		//print_R($resp);
		
		//die;
		$token = $this->audition_functions->getFirebaseToken($to_user);
		//$token = $this->audition_functions->getFireBaseTokenOfALLFans();
		//$chunk['web'] 		= array_chunk($token['android'],10);
		//$chunk['android'] 	= array_chunk($token['web'],10);
		 
		if(!empty($token)){
			$mess 		= 'testing notification';
			$fullname 	= 'ajaydeep parmar'; //$this->audition_functions->get_user_fullname($uid
			$com_text   = 'Shubham modi like your post.';
			$link = base_url();
			$msg_array 	=  [
				'title'	=>	$fullname .' '. $mess,
				'body'	=>	$com_text,
				'icon'	=>	base_url('repo/images/firebase.png'),
				'image' =>	'https://s3.amazonaws.com/discovered.tv.new/aud_4271/images/1467505763_thumb.jpg.webp',
				'click_action'=>$link,
				'extra_data'=>array('id'=>'215','intent'=>'user_profile','videoThumb'=>'https://s3.amazonaws.com/discovered.tv.new/aud_4271/images/1467505763_thumb.jpg.webp')
			];
			$resp = $this->audition_functions->sendNotification($token,$msg_array);
			print_R($resp);die;
			echo "Notification send successfully.";
		}else{
			echo "Token not found.";
		}
	}
	
	public function get_id_tokens(){
		global $providedIdentity;
		$resp=array();
		//$this->load->library('manage_session');
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			$this->form_validation->set_rules('user_uname', 'User unique name', 'trim|required');
			if ($this->form_validation->run() == TRUE)
			{	
				$user_uname = $_POST['user_uname'];
				$this->load->helper('aws_cognito_action');
					
				$resp = getIdToken($user_uname);
				$resp['providedIdentity'] = $providedIdentity; //'us-west-2:1004c46f-a2bd-42d2-b8fd-41e34ea0fb7f';
				$resp['MAIN_BUCKET']	  = MAIN_BUCKET;
				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				$this->respMessage 	= 'Token generated successfully.';
			
			}else{
				$this->respMessage =$this->single_error_msg();
			}	
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	
	public function get_ivs_info(){
		header('Access-Control-Allow-Origin: *');
		$resp=[];
		$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{	
			$uid = $_POST['user_id'];
			$ivs_info = $this->DatabaseModel->select_data('*','users_ivs_info',['user_id'=>$uid],1);
			if(!empty($ivs_info)){
				$resp['ivs_data'] = isset($ivs_info[0])?$ivs_info[0]:[];
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Ivs info.';
			}else{
				$this->respMessage = 'Ivs info not found.';
			}
		}else{
			$this->respMessage =$this->single_error_msg();
		}
		$this->show_my_response($resp);
	}
	
	
	public function chek_thumb(){
		$uid =215;
		$url ='https://s3-trans-cdn.discovered.tv/aud_215/videos/1601708741352/1601708741352.mp4';
		
		$ThumbPath = "./uploads/aud_{$uid}/";
		
		$imgArr = $this->audition_functions->createChannelThumb($url,'1080',$ThumbPath,'jpg'); //1080*608
		
		print_R($imgArr);
		if(isset($_SESSION['video_duration'])){
			print_R($_SESSION['video_duration']);
			unset($_SESSION['video_duration']);
		}
	}
	
	
	function upload_video_onlocal() {
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$uid =  $TokenResponce['userid'];
			
			if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
					
				$config['upload_path'] = './uploads/ios_video';
				$config['encrypt_name'] = true;
				$config['allowed_types'] = '*';
				//$config['max_size']      = 8192 ;
				//$config['min_width']     = 640 ;
				//$config['min_height']    = 474 ;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				
				if ($this->upload->do_upload('userfile'))
				{
					$uploaddata=$this->upload->data();
					$name = $uploaddata['raw_name'];
					$video_ext = $uploaddata['file_ext'];
					$resp['video_url'] = $videoName = base_url().'uploads/ios_video/'.$name.$video_ext;
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage ='Video uploaded successfully.';
				}else{
					
					$this->respMessage ='Something went wrong please try again.';
				}
			}
			else {
				$this->respMessage ='Please select an video.';
			}
		}
		else {
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	function parseHtml($description=''){
		
		$description = 	htmlentities(htmlspecialchars_decode(html_entity_decode(strip_tags( $description), ENT_QUOTES)), null, 'utf-8');
		$description = 	str_replace("&nbsp;", "", $description);
		return $description = 	str_replace("&amp;", "", $description);
		//return $description = 	preg_replace( "/\r|\n/", "", $description );
	
	}
	
	public function privacy_policy(){
		 
		$data['privacy_policy']	= $this->load->view('common/privacy_policy','',TRUE);
		$data['terms_privacy']	= $this->load->view('common/terms_privacy','',TRUE);
		$resp['compliance_pages'] = $data;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage ='Page contents.';
		$this->show_my_response($resp);
		 
	}
	
	
	public function help_page_content(){
		
		$resp=[];
		$limitData = 5;
		$startOffset = (isset($_POST['start']))?$_POST['start']:0;
		
		$filed 		= ['icon_image','title','subject','description','faq_id'];
		$searchType = 1;
		$cond  = 'type ='.$searchType. ' AND status = 1' ;
		
		$help_page = $this->DatabaseModel->select_data($filed,'help_faq', $cond ,array($limitData+1,$startOffset));
		
		if(!empty($help_page)){
			$resp['help_page_content']=$help_page;
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage ='Help Page contents.';
		}else{
			$resp['help_page_content']=[];
			$this->respMessage ='Help Page contents not found.';
		}
		
		$this->show_my_response($resp);
		
	}
	
	
	function check_agent(){
		$this->load->library('user_agent');
		
		$vcb = $this->load->library('user_agent');
		print_R($this->agent);die;
		if ($this->agent->is_browser())
		{
				$agent = $this->agent->browser().' '.$this->agent->version();
		}
		elseif ($this->agent->is_robot())
		{
				$agent = $this->agent->robot();
		}
		elseif ($this->agent->is_mobile())
		{
				$agent = $this->agent->mobile();
		}
		else
		{
				$agent = 'Unidentified User Agent';
		}

		echo $agent;

		echo $this->agent->platform();die; // Platform info (Windows, Linux, Mac, etc.)
		echo $_SERVER['HTTP_USER_AGENT'];die;
		$browser = get_browser();
		print_r($browser);
	}
	
	
	public function submitLiveStreamForm(){
		header("Access-Control-Allow-Headers: Authorization");
		header('Access-Control-Allow-Origin: *');
		 
		// $this->statusCode = 1;
		// $this->respMessage = '';
		// $resp['post_id'] = 50;
		// $resp['pub_url'] = 'dfsdfsfs';
		// return $this->show_my_response($resp);die;
		$resp	=	[];
		//$uid 	= 	$this->uid;
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();;
		
		if($TokenResponce['status'] == 1){
			
			$uid = $TokenResponce['userid'];
			
			/* print_R($_FILES);
			print_R($_GET);die;
			print_R($_REQUEST);die; */
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
				
				$this->respMessage  =  $this->single_error_msg();
				
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
					
					if($schedule == 1)
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
							'description'		=>	$description,
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
							//if($schedule == 1)
							//$this->audition_functions->sendNotiOnLiveStreaming($uid,$post_id,$title,$status = 2);
							
							$post_keys = $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
							$this->DatabaseModel->access_database('channel_post_video','update',['post_key'=>$post_keys[0]],['post_id'=>$post_id]);
							
							$ivs_info = array(	
											'is_live'		=>	1,
											'live_pid'		=>	$post_id,
											'is_scheduled'	=>	($schedule == 1)?1:0,
											'is_chat'		=>	($is_chat == '1')?1:0,
											'schedule_time'	=>	$scheduled_time
										);
							
							$this->DatabaseModel->access_database('users_ivs_info','update',$ivs_info,['user_id'=>$uid]);
							
							$pathToImage = user_abs_path($uid);
							
							$u = $this->audition_functions->upload_file($pathToImage,'jpg|png|gif|jpeg','userfile',true);
							
							if($u != 0 ){
								$name 	= $u['file_name'];
								$path 	= $pathToImage.$name ; 
								
								$r = $this->audition_functions->resizeImage('1080','608',$path,'',false,false);
								if($r != 0 ){
									$this->load->library('convert_image_webp');
									
									if(file_exists($path))
									$this->convert_image_webp->convertIntoWebp($path);
									
									if(file_exists($path))
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
										
										
										if($schedule == 1)
										$this->audition_functions->sendNotiOnLiveStreaming($uid,$post_id,$title,$status = 2);
										
										
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
										$this->statusType = 'Success';
										$this->respMessage  =  'A new stream has created successfully.';
										$resp['post_id'] 	= $post_id;
										$resp['pub_url'] 	= $pub_url;
									}
								}
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
	
	
	
	function requestForLiveStreaming(){
		$resp=[];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$uid = $TokenResponce['userid'];	
			$ivs_info 		= $this->DatabaseModel->select_data('*','users_ivs_info',['user_id' => $uid],1);
		
			$resp['ivs_data']['id ']			= isset($ivs_info[0]['id'])?$ivs_info[0]['id']:'';
			$resp['ivs_data']['user_id'] 		= isset($ivs_info[0]['user_id'])?$ivs_info[0]['user_id']:'';
			$resp['ivs_data']['live_pid'] 		= isset($ivs_info[0]['live_pid'])?$ivs_info[0]['live_pid']: '';
			//$resp['ivs_data']['channel_arn'] 	= isset($ivs_info[0]['ivs_info'])?json_decode($ivs_info[0]['ivs_info'],true):[];
			$resp['ivs_data']['status']			= (int) isset($ivs_info[0]['status'])?$ivs_info[0]['status']: 3 ;
			$resp['ivs_data']['is_live'] 		= isset($ivs_info[0]['is_live'])?$ivs_info[0]['is_live']: 0 ;
			
		
			$page1Text = array(
							'You need a one time approval for streaming.',
							'Upon approval you can stream immediately or schedule it for a later date.',
							'For every stream you would need to provide basic video/stream details.',
							'Once the streaming ends, the video is automatically uploaded in your channel and will continue to be monetized.',
							'You can choose not to monetize by setting the video to offline/private mode from dashboard.'
						);
					
			$resp['page1'] = array(
										'header'=>'Discovered Live Streaming',
										'title'=>'Here are the steps & information needed for streaming through  discovered',
										'text'=>$page1Text,
										'footer'=>'Prerequisites',
										'footertext'=>'You need to have a high speed internet connection and a high resolution camera attached to your computer.'	
									);
			$page2Text = array(
							'Average number of viewers per stream',
							'Average Duration per stream in minutes',
							'Number of Streams per month are you likely to broadcast',
							'Total number of streams you plan to broadcast',
							
						);						
									
			$resp['page2'] = array(
									'header'=>'Please fill these basic details to request live streaming.',
									'text'=>$page2Text,
								);
								
			$this->statusCode  	=  1;
			$this->statusType = 'Success';
			$this->respMessage  =  'A new stream has created successfully.';
			
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}
	
	public function update_about_me(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		if($TokenResponce['status'] == 1){
			$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
			$this->form_validation->set_rules('uc_about', 'about me', 'trim|required');
				
			if ($this->form_validation->run() == false){
				
				$this->respMessage  =  $this->single_error_msg();
				
			}else{
				$uid = $_POST['user_id'];
				$this->DatabaseModel->access_database('users_content','update', array('uc_about'=>json_encode($_POST['uc_about'])) , array('uc_userid'=>$uid));
				$this->statusCode  	= 1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Updated successfully.';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	
	public  function get_violations_category(){
		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$uid =  $TokenResponce['userid'];
			
			if( !empty($uid)) {
				$rules = array(
					array( 'field' => 'parent_id', 'label' => 'Parent_id', 'rules' => 'trim|required'),
					array( 'field' => 'type', 'label' => 'Type', 'rules' => 'trim|required'),
				);
				$this->form_validation->set_rules($rules);
				
				if($this->form_validation->run()){
					
					$parent_id = $this->input->post('parent_id'); 
					
					$type = $this->input->post('type'); 
					
					$cond ="parent_id = {$parent_id} AND status = 1 AND type = '{$type}'";
					
					if($parent_id == 0){
						$cond .=" AND (SELECT COUNT(viol_id) AS viol_id from violations_category c2 where c2.parent_id = c1.viol_id) > 0";
					}
					
					$resp['cate'] = $this->DatabaseModel->select_data('viol_id,violations_title,parent_id','violations_category c1',$cond);
					
					$this->respMessage = 'Violations category';
					$this->statusType   = 'Success';
					$this->statusCode  =  1;
				
				}else{
					$this->respMessage  =  $this->single_error_msg();
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}

	public function submit_violations_history(){
		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$uid =  $TokenResponce['userid'];
			
			if( !empty($uid)) {
				$rules = array(
					array( 'field' => 'viol_cate', 'label' => 'Cateogory', 'rules' => 'trim|required'),
					array( 'field' => 'viol_subcate', 'label' => 'Sub Cateogory', 'rules' => 'trim|required'),
					array( 'field' => 'viol_msg', 'label' => 'Message', 'rules' => 'trim|max_length[250]'),
					array( 'field' => 'related_with', 'label' => 'Related With', 'rules' => 'trim|required'),
					array( 'field' => 'related_id', 'label' => 'Related ID', 'rules' => 'trim|required'),
				);
				$this->form_validation->set_rules($rules);
				
				if($this->form_validation->run()){
					$related_with 	 = $this->input->post('related_with'); 
					$related_id 	 = $this->input->post('related_id'); 
					$related_user_id = $uid; 
					$viol_cate 	 	 = $this->input->post('viol_cate'); 
					$viol_subcate 	 = $this->input->post('viol_subcate'); 
					$viol_msg 	     = $this->input->post('viol_msg'); 

					$resp = $this->DatabaseModel->select_data('viol_his_id','violations_history',array(
						'related_with' 		=> 	$related_with,
						'related_id'		=>	$related_id,
						'related_user_id' 	=> 	$related_user_id,
						'status' 			=> 	0,
					));

					if(isset($resp[0]) && !empty($resp[0])){
						$where = array(
							'related_with' 		=> 	$related_with,
							'related_id'		=>	$related_id,
							'related_user_id' 	=> 	$related_user_id,
						);
						$data = array(
							'viol_cate'			=>	$viol_cate,
							'viol_subcate' 		=> 	$viol_subcate,
							'viol_msg' 			=> 	$viol_msg,
						);
						$id = $this->DatabaseModel->access_database('violations_history','update',$data,$where);
						if($id){
							$this->respMessage = 'Thanks for your feedback.';
							$this->statusCode  =  1;
							$this->statusType  = 'Success';
						}else{
							$this->respMessage = 	'No updates to save.';
						}
						
					}else{
						$data = array(
							'viol_cate'			=>	$viol_cate,
							'viol_subcate' 		=> 	$viol_subcate,
							'viol_msg' 			=> 	$viol_msg,
							'related_with' 		=> 	$related_with,
							'related_id'		=>	$related_id,
							'related_user_id' 	=> 	$related_user_id,
							'status' 			=> 	0,
							'created_at'		=> 	date('Y-m-d H:i:s')
						);
						$id = $this->DatabaseModel->access_database('violations_history','insert',$data);
						if($id){
							$this->respMessage = 'Thanks for your feedback.';
							$this->statusCode  =  1;
							$this->statusType  = 'Success';
						}else{
							$this->respMessage = 	'No data to save.';
						}
					}
				}else{
					$this->respMessage  = $this->single_error_msg();
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	

	public function block_user_generated_content(){
		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$uid =  $TokenResponce['userid'];

			$rules = array(
				array( 'field' => 'block_by_uid', 'label' => 'Block By', 'rules' => 'trim|required'),
				array( 'field' => 'block_content_id', 'label' => 'Block Content Id', 'rules' => 'trim|required'),
				array( 'field' => 'related_with', 'label' => 'Related With', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){

				$blocked_by  	  = $this->input->post('block_by_uid');
				$block_content_id = $this->input->post('block_content_id'); 
				$related_with 	  = $this->input->post('related_with');
				
				$resp = $this->DatabaseModel->select_data('blocked_id,blocked_content','users_blocked_content',array('blocked_by'=>	$blocked_by,'related_with' =>$related_with));
				if(isset($resp[0]) && !empty($resp[0])){
					$where = array( 
							'blocked_by'	=> $blocked_by,
							'related_with' 	=> $related_with
							);

					$blocked_content_arr = [];

					if(!empty($resp[0]['blocked_content'])){
						$blocked_content_arr = json_decode($resp[0]['blocked_content']);
					}		

					array_push($blocked_content_arr,$block_content_id);

					$data = array('blocked_content' => json_encode(array_unique($blocked_content_arr)), 'updated_at' => date('Y-m-d H:i:s'));
					$id = $this->DatabaseModel->access_database('users_blocked_content','update',$data,$where);
					if($id){
						$this->respMessage = 'Thanks for your feedback.';
						$this->statusCode  =  1;
						$this->statusType  = 'Success';
					}else{
						$this->respMessage = 	'No updates to save.';
					}

				}else{
					$data = array(
						'blocked_by'			=>	$blocked_by,
						'blocked_content' 		=> 	json_encode([$block_content_id]),
						'related_with' 			=> 	$related_with,
						'created_at'			=> 	date('Y-m-d H:i:s')
					);
					$id = $this->DatabaseModel->access_database('users_blocked_content','insert',$data);
					if($id){
						$this->respMessage = 'Thanks for your feedback.';
						$this->statusCode  =  1;
						$this->statusType  = 'Success';
					}else{
						$this->respMessage = 	'No data to save.';
					}
				}
			}else{
				$this->respMessage  = $this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		} 
		$this->show_my_response();
	}

	public function checkBlockedContent($uid='',$related_with = ''){
		if($this->get_token_uid() && !empty($uid) && !empty($related_with)){
			$resp = $this->DatabaseModel->select_data('blocked_content','users_blocked_content',array('blocked_by'=>$this->get_token_uid(),'related_with' =>$related_with));
			if(isset($resp[0]) && !empty($resp[0])){
				$blocked_content_arr = [];

				if(!empty($resp[0]['blocked_content'])){
					$blocked_content_arr = json_decode($resp[0]['blocked_content']);
				}
				
				if(!empty($blocked_content_arr) &&  in_array($uid,$blocked_content_arr)){
					return  true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	

	/*public function testMail(){
		//  $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
  
		$to = 'nitesh.modi@pixelnx.com';
		$subject = 'An error has occured';
		$headers = 'From: Example Name <no-reply@example.com>' . "\r\n";
		$headers .= "Organization: Sender Organization\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= 'Content-type: text/plain; charset=utf-8\r\n';
		$headers .= "X-Priority: 3\r\n";
		$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
		
		$message ='Test mail by nitesh';
		mail($to, $subject, $message, $headers); 
	}*/
	
	
	public function deletePlaylist(){
		$this->form_validation->set_rules('playlist_id', 'playlist id', 'trim|required');
		if ($this->form_validation->run() == false){
			
			$this->respMessage  =  $this->single_error_msg();
			
		}else{
			$this->DatabaseModel->access_database('channel_video_playlist','delete','', array('playlist_id '=>$_POST['playlist_id']));
			$this->statusCode  	= 1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Playlist deleted successfully.';
		}
		$this->show_my_response();
	}
	
	public function actionOnPlaylist(){
		$this->form_validation->set_rules('playlist_id', 'playlist id', 'trim|required');
		$this->form_validation->set_rules('action_type', 'action type', 'trim|required');
		if ($this->form_validation->run() == false){
			
			$this->respMessage  =  $this->single_error_msg();
			
		}else{
			
			$updateList=[];
			$action_type 	= $this->input->post('action_type');
			$playlist_id 	= $this->input->post('playlist_id');
			$video_id 		= $this->input->post('video_id');
			
			$playlist= $this->DatabaseModel->select_data('*','channel_video_playlist',array('playlist_id'=>$playlist_id),1);
			if(!empty($playlist)){
				
				if($action_type =='addToPlaylist') {
					
					$video_ids = explode('|' , $playlist[0]['video_ids']);
					$video_items = array_unique(array_merge($video_ids, [$video_id] ));
					$updateList['video_ids'] = implode('|',$video_items);
					$this->respMessage  = 'Video added to playlist successfully.';
					
				}else if($action_type =='remove') {
					
					$video_ids = $playlist[0]['video_ids'];
					if(!empty($video_ids)){
						$vids =  explode('|' , $video_ids);
						if($key = array_search($video_id, $vids)){
							unset($vids[$key]);
							$v = array_values($vids);
							$updateList['video_ids'] = implode('|',$v);
							
							$this->respMessage  = 'Video removed successfully.';
						}else{
							$this->respMessage  = 'This video not found in playlist.';
						}
					}else{
						$this->respMessage  = 'playlist is empty.';
					}
					
				}else if($action_type =='re-ordered'){
					
					array_unshift($_POST['reorder_list'], '');
					$updateList['video_ids'] = implode('|',$_POST['reorder_list']);
					$this->respMessage  = 'Playlist Re-ordered successfully.';	
					
				}
			
				if(!empty($updateList)){
					$this->DatabaseModel->access_database('channel_video_playlist','update',$updateList,['playlist_id'=>$playlist_id]);
					$this->statusCode  	= 1;
					$this->statusType   = 'Success';
				}
				
			}else{
				$this->respMessage  = 'Playlist not found.';		
			}
		}
		$this->show_my_response();
	}

	public function getCoverVideo(){
		$resp=[];
		$this->form_validation->set_rules('mode_id', 'mode id', 'trim|required');
		if ($this->form_validation->run() == false){
			
			$this->respMessage  =  $this->single_error_msg();
			
		}else{	
			$mode = ($this->get_mode_name($_POST['mode_id']))? $this->get_mode_name($_POST['mode_id']) : 'music';
			if($mode=='spotlight'){
				$resp['cover_video'][]  =   $this->get_cover_video('homepage','music');
				$resp['cover_video'][]  =   $this->get_cover_video('homepage','movies');
				$resp['cover_video'][]  =   $this->get_cover_video('homepage','television');
				$resp['cover_video'][]  =   $this->get_cover_video('homepage','gaming');
			}else{
				$resp['cover_video'][] 	=  	$this->get_cover_video('homepage',$mode);
			}
			$this->statusCode  	= 1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Cover video.';
		}
		$this->show_my_response($resp);
	}
	
	
	public function getUserTicket(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			
			$uid = $TokenResponce['userid'];	
			
			$this->load->library('custom_pagination');
			
			$start 			= isset($_POST['start'])?$_POST['start']:0;
			$limit 			= isset($_POST['limit'])?$_POST['limit']:10;
			$no_of_records 	= isset($_POST['no_of_records'])?$_POST['no_of_records']:10;
			$ticket_status 	= isset($_POST['ticket_status'])?$_POST['ticket_status']:'';
			$user_type 		= isset($_POST['user_type'])?$_POST['user_type']:'';
			$date_range 	= isset($_POST['date_range'])?$_POST['date_range']:'';
			
			$fields = "support_ticket.user_id,support_ticket.id,support_ticket.subject,message,support_ticket.status, DATE_FORMAT(support_ticket.created_at, '%d %M %Y') as created_at , DATE_FORMAT(support_ticket.updated_at, '%d %M %Y') as updated_at ,users.user_name,users.user_uname,users_content.uc_pic,artist_category.category_name";
			
			$cond = "support_ticket.user_id =$uid AND support_ticket.ticket_id= 0";
			
			$order_by = array('support_ticket.id','DESC');
			
			$join = array('multiple' , array(
										array(	'users', 
												'users.user_id 				 = support_ticket.user_id', 
												'left'),
										array(	'users_content', 
												'users_content.uc_userid	 = users.user_id ', 
												'left'),		
										array(	'artist_category', 
												'artist_category.category_id = users.user_level', 
												'left'),		
										));
										
			if(!empty($no_of_records)){
				$limit = $no_of_records;
			}
			
			if($ticket_status !=''){
				$cond .=" AND support_ticket.status = $ticket_status";
			}
			
			if(!empty($user_type)){
				$cond .=" AND artist_category.category_id = $user_type";
			}
			
			if(!empty($date_range)){
				$date = explode(' - ' , $date_range);
				$date1 		= "'".date('Y-m-d' , strtotime($date[0]))."'";
				$date2 		= "'".date('Y-m-d' , strtotime($date[1]))."'";
				$cond .=" AND DATE(support_ticket.created_at) BETWEEN $date1 AND $date2";
			}
													
			$ticketData   = $this->DatabaseModel->select_data($fields,'support_ticket',$cond,array($limit ,$start),$join,$order_by);
			
			if(!empty($ticketData)){
				foreach($ticketData as $key=>$t){
					$ticketData[$key]['uc_pic'] = !empty($t['uc_pic']) ? create_upic($t['user_id'], $t['uc_pic']) : user_default_image() ;
					$ticketData[$key]['href']   = base_url('profile?user='.$t['user_uname']);
				}	
			}
			
			$ticketCount  = $this->DatabaseModel->aggregate_data('support_ticket','id','COUNT',$cond,'');
			
			$pagination   = $this->custom_pagination->pagination($ticketCount,$start,$limit);
			
			$resp 		  = array('status'=>1 , 'data'=>array('ticketData'=>$ticketData, 'pagination'=>$pagination));
		
			$this->statusCode  	= 1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'User tickets';
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
		
	}
	
	
	public function updatePlaylist(){
		$this->form_validation->set_rules('playlist_id', 'playlist id', 'trim|required');
		$this->form_validation->set_rules('playlist_title', 'playlist title', 'trim|required');
			
		if ($this->form_validation->run() == false){
			
			$this->respMessage  =  $this->single_error_msg();
			
		}else{
		
			$playlist_id = $_POST['playlist_id'];
			$updateList = array('title'=>$_POST['playlist_title']);
			$this->DatabaseModel->access_database('channel_video_playlist','update',$updateList,['playlist_id'=>$playlist_id]);
			$this->statusCode  	= 1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Updated successfully.';
		}
		$this->show_my_response();
	}
	
	/*function insert_old_slider_to_new($mode_id=''){
		
			$mode = $this->get_mode_name($mode_id);
			$fields = 	array(	'slider_title',
								'type', 
								$mode, 
								$mode.'_slider_order',
								$mode.'_status',
								'user',
								'cate',
							);
		
			$cond	=	array($mode.'!=' => '');	
			$order	=	''; //array($fields[3] , 'ASC');	
		
			$result	= 	$this->DatabaseModel->select_data($fields,'site_main_data',$cond,'','', $order);
			 
			$data	=	array();
			if(isset($result[0])){
				
				foreach($result as $list){
					 
					$title		=	$list['slider_title'];
					$type		=	$list['type'];
					$user_id	=	$list['user'];
					$slider_order =	$list[$mode.'_slider_order'];
					$slder_status =	$list[$mode.'_status'];
					$cate 		  =	$list['cate'];
					$data 		  =	$list[$mode];
					
					$data_array = array(
						'slider_title'	=>	$title,
						'type'			=>	$type,
						'data'			=>	$data ,
						'mode'			=>	$mode_id,
						'status'		=>	$slder_status ,
						'user_id'		=>	$user_id ,
						'category_id'	=>	$cate ,
						'slider_order'  =>  $slider_order
					);
					
					//$this->DatabaseModel->access_database('homepage_sliders','insert',$data_array, '');
				}
			}
		
	}*/
	
	
	/*public function website_info_insert_to_page_setting($mode_id=''){
		$mode 	= $this->get_mode_name($mode_id);
		$cond	= array('website_mode' => $mode);
		$array  = [];
		$result	= 	$this->DatabaseModel->select_data('*','website_info',$cond);
		if(!empty($result)){
			foreach($result as $key=>$val){
							
				if($val['field']=='cover_image'){
					$array['cover_image']=$val['title'];
				}
					
				if($val['field']=='cover_image_title'){
					$array['cover_image_title']=$val['title'];
				}
				
				if($val['field']=='cover_image_subtitle'){
					$array['cover_image_subtitle']=$val['title'];
				}
				
				if($val['field']=='cover_over_image'){
					$array['cover_over_image']=$val['title'];
				}
				if($val['field']=='cover_video'){
					$array['cover_video']=$val['title'];
				}
				if($val['field']=='cover_image_status'){
					$array['cover_image_status']=$val['title'];
				}
				
				if($val['field']=='icon_menu'){
					$array['icon_menu']=$val['title'];
				}
				
				$array['website_mode']=$mode_id;
			}
			//$this->DatabaseModel->access_database('page_setting','insert',$array);			
		}
				
	}*/
	
	public function getVideoThumbs($video_ids)
	{
		$vid_ids = implode(',', $video_ids);
		$cond = "channel_post_thumb.post_id IN($vid_ids) AND channel_post_thumb.active_thumb=1";
		return $result	= 	$this->DatabaseModel->select_data('post_id,image_name','channel_post_thumb',$cond);
	}

	function p($data=[]){
		echo "<pre>";
		print_r($data);
		die;
	}
	
	function testDataPrint(){
		
		if(!empty($data)){
			echo $data;
		}else{
			echo "empty";
		}
		die;

		$res = $this->common->getlocationbyip('49.36.26.166');
		print_R($res);die;
		
		print_R(getallheaders());
		echo "<br>";
		print_R($this->input->request_headers());
		echo "<br>";
		print_R($_POST); 
		echo "<br>";
		print_R($_GET);
		die;
		
	}
	
	
	/*function updateVideoTitle(){
		$cond = "title_new LIKE '%&#39%' ";
		$videos	= 	$this->DatabaseModel->select_data('post_id,title_new','channel_post_video',$cond);
		foreach($videos as $v){
			$updateList = array('title_new'=> str_replace("&#39","'",$v['title_new']));
			print_R($updateList);
			$this->DatabaseModel->access_database('channel_post_video','update',$updateList,['post_id'=>$v['post_id']]);
		}
	}*/
	
	
	
	
	function createAutoPlaylistByUser(){
		/* $mode_id = 7;
		$users	= 	$this->DatabaseModel->select_data('user_id','users',array('user_role'=>'member'));
		
		if(!empty($users[0])){
			
			$this->load->library('Valuelist');
			$web_mode = $this->valuelist->mode();
			$user_chunk = array_chunk($users,100);
			
			foreach($user_chunk as $user){
				$list =[];
				foreach($user as $u){
					$uid = $u['user_id'];
					$videos = $this->DatabaseModel->select_data('post_id','channel_post_video',array('user_id'=>$uid,'mode'=>$mode_id,'active_status' => 1,'delete_status'=>0));
					if(!empty($videos)){
						$postId_arr = array_column($videos, 'post_id');
						$postId_arr = array_unique(array_merge([''] ,$postId_arr ));
						
						 
						 $list[] = ['user_id' 			=> $uid,
								 'title'   			=> ucfirst($web_mode[$mode_id]).' Playlist',
								 'privacy_status'   => 7,
								 'first_video_id'   => isset($postId_arr[1])? $postId_arr[1] : 0,
								 'video_ids'        => implode('|',$postId_arr),
								 'mode'				=> $mode_id,
								 'playlist_type' 	=> 1,   					//Auto created playlist
								 'created_at'   	=> date('Y-m-d H:i:s')
								];
						
					}
				}
				if(!empty($list)){
					$this->db->insert_batch('channel_video_playlist', $list); 
					print_R($list);
				}
			}
			
		} */
		
		
	}
	
	
	
	/* public function getWebsiteInfo(){
		
		$resp['webinfo'] = array(	'PROJECT'			 => PROJECT,
									'MAIN_BUCKET'		 => MAIN_BUCKET,
									'TRAN_BUCKET'		 => TRAN_BUCKET,
									'STREAM_BUCKET'		 => STREAM_BUCKET,
									'BUCKET_REGION'		 => BUCKET_REGION,
									'BUCKET_KEY'		 => BUCKET_KEY,
									'BUCKET_SECRET'		 => BUCKET_SECRET,
									'MC_END_POINT'		 => MC_END_POINT,
									'CDN_BASE_URL'		 => CDN_BASE_URL,
									'AMAZON_URL'		 => AMAZON_URL,
									'AMAZON_TRANCODE_URL'=> AMAZON_TRANCODE_URL,
									'AMAZON_STREAM_URL'	 => AMAZON_STREAM_URL,
								);
		$this->statusCode  	= 1;
		$this->statusType   = 'Success';
		$this->respMessage  = 'Website info.';
		$this->show_my_response($resp);
	} */

	

	
	/*public function test_mail_send(){

		$to_email = 'nitesh.modi@pixelnx.com';
		$subject  = 'New Request For Live Streaming';
		$message  = 'A new user has requested to live stream on Discovered. <br/>Visit "Admin Dashboard > Manage Users > Media Request For Live" to approve or reject the request.'; 
		//Load email library
		$data['ticket_id']='';
		$data['subject']=$subject;
		$data['message']=$message;
		$data['department_name']='Live Request';
		$data['ins']='';
		$data['user_name']='Nitesh Modi';
		$data['receiver_email'] = $to_email;
		$data['mail_subject'] = 'New Request For Live Streaming';

		$this->load->helper('aws_ses_action');
		send_smtp_support_mail($data);
		echo "mail sent.";
		die;

		$email = "fide@mailinator.com";
		$link = base_url().'home/verify_email/bcvbcbcvbcvbc';
		
		$subject = 'Welcome to '. PROJECT;
		$greeting = 'Thanks for creating an account with us.';
		$action   = 'You have entered <b>'.$email.'</b> as the email address for your account. <br/>To complete your sign up process, simply click the button below so we know this account belongs to you.';

		$button = 'Activate Your Account';
		
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
		echo "mail sent.";
	}*/



	function cleanText(){
		$description =  $_POST['title'];

		$description = htmlspecialchars_decode(html_entity_decode(strip_tags($description), ENT_QUOTES));
		$description = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $description);
		$description = str_replace("–","-",$description); // lowercase 
		$description = preg_replace('/[^A-Za-z0-9 !@#$_%^&*()–]/u','', strip_tags($description));
		$description = filter_var($description, FILTER_SANITIZE_STRING);
		$description = str_replace("&nbsp;", "", $description);
		$description = str_replace("&amp;", "", $description);
		$description = preg_replace( "/\r|\n/", "", $description );
		echo $description = $this->string_cleaner($description);
	}

	function string_cleaner($result)
	{
		$result = strip_tags($result);
		$result = preg_replace('/[^\da-z]/i', ' ', $result);
		$result = preg_replace('/&.+?;/', '', $result); 
		$result = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', ' ', $result);
		$result = preg_replace('|-+|', ' ', $result);
		$result = preg_replace('/_+/', ' ', $result);
		$result = preg_replace('/&#?[a-z0-9]+;/i','',$result);
		$result = preg_replace('/[^%A-Za-z0-9 _-]/', ' ', $result);
		$result = preg_replace('/^\W+|\W+$/', '', $result);
		$result = preg_replace('/\s+/', ' ', $result);
		$result = trim($result, ' ');
		return $result;
	}





	public function updateVideoSize(){
		$videos = $this->DatabaseModel->select_data('post_id,uploaded_video','channel_post_video',array('video_type !='=>2));
		if(!empty($videos)){
			$sizes =  [];
			foreach($videos as $v){
				try{
					$fileSize = checkmeta($v['uploaded_video']);
					if($fileSize['statusCode']==200){
						$sizes [] =  array('post_id'=>$v['post_id'], 'video_size'=>$fileSize['headers']['content-length']);
					}
				}catch(Exception $e){
					continue;
				}
			}

			if(!empty($sizes)){
				$this->db->update_batch('channel_post_video',$sizes, 'post_id');
			}
			print_R($sizes);
		}
	}

	function get_mb($size) {
		return sprintf("%4.2f MB", $size/1048576);
	}

	function gbToBytes($gb=0){
		return ($gb*pow(1024,3));
	}

	function converttoUnit($size,$unit) 
	{
		if($unit == "KB")
		{
			return $fileSize = round($size / 1024,2) . 'KB';	
		}
		if($unit == "MB")
		{
			return $fileSize = round($size / 1024 / 1024,2) . 'MB';	
		}
		if($unit == "GB")
		{
			return $fileSize = round($size / 1024 / 1024 / 1024, 4); // . 'GB';	
		}
	}


	function filesize_formatted($size)
	{
		//$size = filesize($path);
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}


	function convertToReadableSize($size){
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = 3; //floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}

	function formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824)
		{
			$bytes = number_format($bytes / 1073741824, 4) . ' GB';
		}
		elseif ($bytes >= 1048576)
		{
			$bytes = number_format($bytes / 1048576, 4) . ' MB';
		}
		elseif ($bytes >= 1024)
		{
			$bytes = number_format($bytes / 1024, 4) . ' KB';
		}
		elseif ($bytes > 1)
		{
			$bytes = $bytes . ' bytes';
		}
		elseif ($bytes == 1)
		{
			$bytes = $bytes . ' byte';
		}
		else
		{
			$bytes = '0 bytes';
		}

		return $bytes;
  	}

	function getSignedUrl(){
		$resp = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->is_allow_access();
		
		if($TokenResponce['status'] == 1){
			$rule = array(
				array( 'field' => 'file_name', 'label' => 'File name', 	 'rules' => 'trim|required'),
				//array( 'field' => 'content_type', 	'label' => 'Content Type', 	 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rule);
			if($this->form_validation->run() == TRUE){
				
				$uid = $TokenResponce['userid'];

				$fileName 	 = $_POST['file_name'];
				//$contentType = $_POST['content_type'];
				$contentType = $this->getFileContentType($fileName);
				// Check the file type and move the file to the appropriate folder
				if (strpos($contentType, 'image/') === 0) {
					$folder = 'images';
				} elseif (strpos($contentType, 'video/') === 0) {
					$folder = 'videos';
				} else {
					$this->respMessage 		= 'Unsupported file type.';
					$this->show_my_response($resp);return;
				}
				
				$extension = pathinfo($fileName, PATHINFO_EXTENSION);
				$key = 'aud_'.$uid.'/'.$folder.'/'.uniqid().'.'. $extension;

				$result = createSignedUrl($key);
				if($result['status']){
					$resp['presignedUrl'] 	= $result['presignedUrl'];
					$resp['filename'] 		= $key;
					$this->statusCode 		= 1;
					$this->statusType 		= 'Success';
					$this->respMessage 		= 'URL generated successfully.';
				}else{
					$this->respMessage 	= $result['message'];
				}
			}else{
				$this->respMessage =$this->single_error_msg();
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}

	function getFileContentType($filename) {
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
	
		$mimeTypes = [
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
			'mp4' => 'video/mp4',
			'avi' => 'video/avi'
			// Add more file extensions and corresponding content types as needed
		];
	
		// Convert the extension to lowercase for case-insensitive comparison
		$extension = strtolower($extension);
	
		// Return the corresponding MIME type if it exists, or a default value otherwise
		return isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
	}


	/*public function manuallySubsCribe(){
		$resp =[];
		try{
			$cond = "`user_uname` !='' AND `user_status` = 1 AND `user_regdate` > '2025-01-22' AND `registration_source` = 'gamepass'";
			$gamepass_users = $this->DatabaseModel->select_data('user_name,user_email',USERS,$cond);
			if(!empty($gamepass_users)){
				foreach($gamepass_users as $user){
					$_POST['user_name'] = $user['user_name'];
					$_POST['u_em'] 		= $user['user_email'];
					if(isset($_POST['u_em']) && !empty($_POST['u_em'])){
						$emailExist = $this->DatabaseModel->select_data('id','gamepass_coupon_codes',array('user_email'=>$_POST['u_em']));
						if(empty($emailExist)){

							$coupon = $this->DatabaseModel->select_data('id,coupon_code','gamepass_coupon_codes',array('status'=>0),1); 
							$holdCoupon = array('status'=>2, 'user_email'=>$_POST['u_em']);
							$holdUpdate = $this->DatabaseModel->access_database('gamepass_coupon_codes','update',$holdCoupon,array('id' => $coupon[0]['id'])); // coupon on hold
							
							if(!empty($coupon) && !empty($coupon[0]['coupon_code'])) {

								include_once APPPATH . 'third_party/MailChimp.php';
							
								$MailChimp = new MailChimp(MAILCHIMP_KEY);
								$listID ='75e5618d5d';
								
								if (isset($_POST['user_name'])  && !empty($_POST['user_name'])){
									$args = array('FNAME' => $_POST['user_name'],'COUPON'=>$coupon[0]['coupon_code']);
								}else{
									$args = array();
								}
							
								if(!empty($listID)){
									$mdata = $MailChimp->post("lists/$listID/members", [
										'email_address' => $_POST['u_em'],
										'full_name'		=> isset($_POST['user_name']) ? $_POST['user_name'] : '',
										'status'        => 'subscribed',
									]);
									
								}
								$detail = isset($mdata['detail']) ? $mdata['detail'] : '';
								if ($MailChimp->success()) {
									if(!empty($args)){
										$subscriber_hash = $MailChimp->subscriberHash( $_POST['u_em'] );
										$MailChimp->patch("lists/$listID/members/$subscriber_hash", [
											'merge_fields' => $args
										]);
									}

									$updateData = array('status'=>1, 'user_email'=>$_POST['u_em']);
									$update = $this->DatabaseModel->access_database('gamepass_coupon_codes','update',$updateData,array('id' => $coupon[0]['id']));
									
									$result = array('success'=>'You have successfully registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.');
								}elseif ($mdata['status'] == 400){
									
									$errorMsg = $detail;
									if($mdata['title'] == 'Member Exists'){
										$errorMsg = 'You have already registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.';
									}

									$result = array( 'error' => $errorMsg,'status'=>'12' );
								}else{
									$result = array('error'=>$MailChimp->getLastError());
								}				
							}else{
								$result = array('error'=>'Coupon code not found.');
							}
							
						}else{
							$result = array('error'=>'You have already registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.');
						}
					}else{
						$result = array('error'=>'Email id is required.');
					}

					$resp[$_POST['u_em']] =  $result;
				}

			}else{
				$resp = array('error'=>'Gamepass user not found.');
			}
		}catch(Exception $e){
			$resp = $resp = array('error'=>$e->getMessage()); 
		}
		$this->show_my_response($resp);
	}*/

	function getArticlesData() {

		$articleData = $this->DatabaseModel->select_data('article_id, ar_title, ar_slug, ar_author_name, ar_date_created', 'articles' , array('complete_status' => 1,'privacy_status'=>7, 'active_status'=>1, 'delete_status'=>0) );
		
		if(!empty($articleData)){
			
			$fileName = "articles_data.xls";
		
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			
			$output = fopen("php://output", "w");
			
			$headers = ['Article Name','Author Name', 'URL', 'Created Date'];
			fputcsv($output, $headers, "\t");
		
			foreach ($articleData as $art) {
				$article_id = $this->share_url_encryption->share_single_article_link_creator($art['article_id'] , 'encode');
				$url = base_url().'article/'.$article_id.'/'.$art['ar_slug'];
				$row = [
					$art['ar_title'],
					$art['ar_author_name'],
					$url,
					$art['ar_date_created']
				];
				fputcsv($output, $row, "\t");
			}
		
			fclose($output);
			exit();
		}else{
			echo "Data not found.";die;
		}
	}

}
?>






