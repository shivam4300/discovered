<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel extends CI_Controller {
	
	private $uid;
	public function __construct(){
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->library(array('share_url_encryption','dashboard_function'));
		$this->load->helper('button');
		
		$this->uid = is_login();
		
	}
		
	public function index(){
		
		$uid = $this->uid;
		
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
		
		if(WhoAmI($userInfo['uid']) != 4 && isset($userInfo['sigup_acc_type']) && $userInfo['sigup_acc_type'] == 'standard'){	
			
			if(!empty($userInfo['userDetail'])){ 
			
				$data 				= array_merge($data,$userInfo);
				
				$featuredVideo 		= $this->dashboard_function->getUserFeturedVideo($userInfo['uid']); 
				
				$data 				= array_merge($data,$featuredVideo);
				
				$data['page_info'] 	= array('page'=>'my_channel','title'=>'My Channel');
			
				$data['common_header']  		= $this->load->view('common/dashboard_header',$data,true);
				$data['user_introduction']  	= $this->load->view('common/user_profile_info',$data,true);
				$data['user_featured_video']  	= $this->load->view('common/user_featured_video',$data,true);
				
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/channel/my_channel',$data); 
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);
			}else{
				redirect(base_url());
			}
		}else{
			redirect(base_url('profile?user='.$other_user));
		}
	}
	
	
	
	
	public function indexold(){
		
		$uid = $this->uid;
		
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
		
			
		$data['other_user'] = $other_user;
		
		$getUser = [];
		if(!empty($other_user)){
			$getUser  	= $this->DatabaseModel->select_data('user_id,sigup_acc_type','users use INDEX(user_id)',['user_uname'=>$other_user],1);
			$uid		= isset($getUser[0]['user_id'])? $getUser[0]['user_id'] : $uid ;
		}
		
		$data['sigup_acc_type'] = isset($getUser[0]['sigup_acc_type']) ? $getUser[0]['sigup_acc_type'] : '';
		
		if(WhoAmI($uid) != 4 && isset($getUser[0]['sigup_acc_type']) && $getUser[0]['sigup_acc_type'] == 'standard'){	
			$data['is_session_uid'] = (is_session_uid($uid))?1:0;
			$data['uid'] = $uid;
			
			$userDetail	= $this->query_builder->user_list(array(
							'field' =>'users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_type,users_content.aws_s3_profile_video,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users_content.uc_type,users.referral_by,users_content.is_fc_member',
							'where' => 'user_id='.$uid.',user_status=1,is_deleted=0',
						));
			
			if(isset($userDetail['users'])){
				$userDetail = $userDetail['users'];
				
				if(isset($userDetail[0]['referral_by']) && !empty($userDetail[0]['referral_by'])){
					$referral_by 	= $userDetail[0]['referral_by'];
					$referral_name 	= $this->DatabaseModel->select_data('user_name','users',array('user_uname'=>$referral_by));
					if(isset($referral_name[0]['user_name']) && !empty($referral_name[0]['user_name'])){
						$data['referral_name'] 	=	$referral_name[0]['user_name'];
						$data['referral_by']  	=	$referral_by;
					}
				}
					
				if(!empty($userDetail)) 
					$data['userDetail'] = $userDetail;
				else
					redirect(base_url());
				
				
				if(!empty($userDetail[0]['uc_type'])){
					$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category','category_id IN ('.$userDetail[0]['uc_type'].')');
					
					$size = (sizeof($sub_cat) <= 5)?sizeof($sub_cat):5;
					for($i=0;$i < $size; $i++ ){
						$data['sub_catname'] .=  $sub_cat[$i]['category_name'].',';
					}
					$data['sub_catname'] = rtrim($data['sub_catname'] ,", ");
				}
				
				
				$field ="channel_post_video.mode,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type";
				
				$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0';
					
				$globalCond =  $this->common->GobalPrivacyCond($uid);
				
				$where .=  $globalCond;	  
				
				$join  = array(
								'multiple',
								array(
									array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
										  'left'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
							);
				
				$Order = array('channel_post_video.post_id','DESC');
				
				array_push($join[1],array(	'website_mode','website_mode.mode_id = channel_post_video.mode','left'));
				
				$where_cond = ' channel_post_video.featured_by_user = 1 AND ' . $where ; 
				
				$field = 'channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video';
				
				$channel_video  = $this->DatabaseModel->select_data($field ,'channel_post_video',$where_cond,1,$join);
				
				
				if(!isset($channel_video[0]['post_id']))
				$channel_video  = $this->DatabaseModel->select_data($field,'channel_post_video',$where,1,$join,$Order);
				
				
				if(isset($channel_video[0]['post_id']) && !empty($channel_video[0]['post_id'])){
					$vCount   				= 	sizeof($channel_video);
					$index 					= 	0;
					$post_key 				= 	$channel_video[$index]['post_key'];
					$iva_id   				= 	$channel_video[$index]['iva_id'];
					$up_video 				= 	$channel_video[$index]['uploaded_video'];
					
					$data['single_video'] 	= 	base_url().$this->common->generate_single_content_url_param($post_key , 2);
					
					$data['feature_video'] 	= 	$this->share_url_encryption->FilterIva($uid, $iva_id,'',$up_video,'','.m3u8');
					$data['feature_video'] 	= 	isset($data['feature_video']['video'])?str_replace("m3u8","mp4",$data['feature_video']['video']):'';
					
					$data['feature_pid']  	= 	$channel_video[$index]['post_id'];
					$data['title']    		= 	$channel_video[$index]['title'];
						
				}
				
				$cover 					= 	$userDetail[0]['aws_s3_profile_video'];
				$url					=	"";
				$preview				=	"";
				if(!(empty($cover))){
					$url 				= 	AMAZON_URL .$cover;
					$preview			=	$this->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4');
					$preview			=	isset($preview['video'])?$preview['video']:'';
				}
				$data['cover_video'] 	= 	array('url'=>$url,'preview'=>$preview);
				
				$data['DP'] 			= 	!empty($userDetail[0]['uc_pic']) ? create_upic($uid, $userDetail[0]['uc_pic']) : user_default_image() ;
								
				$data['metaData'] 		= 	array(
						'title' 		=> 	'Channel@'. $userDetail[0]['user_name'], 
						'description' 	=> 	'', 
						'image' 		=> 	$data['DP']
				);
				
				$this->db->cache_on();
				$defaultVideo			=	$this->DatabaseModel->select_data('channel_post_video.uploaded_video','page_setting',array('website_mode'=>4),1,array('channel_post_video','channel_post_video.post_id = page_setting.default_profile_video' ));
				$data['defaultVideo'] 	=  isset($defaultVideo[0]['uploaded_video']) ?  $defaultVideo[0]['uploaded_video'] : '';
				$this->db->cache_off();
				
				$data['page_info'] = array('page'=>'my_channel','title'=>'My Channel');
				
				$data['common_header']  		= $this->load->view('common/dashboard_header',$data,true);
				$data['user_introduction']  	= $this->load->view('common/user_profile_info',$data,true);
				$data['user_featured_video']  	= $this->load->view('common/user_featured_video',$data,true);
				
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/channel/my_channel',$data); 
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);
			}else{
				redirect(base_url());
			}
	
		}else{
			redirect(base_url('profile?user='.$other_user));
		}
	}
	
	function filterMode($array,$mode){
		$result = array_filter($array, function ($value) use ($mode) {
				return ($value["mode"] == $mode);
		});
		return $result;
	}
	function getLimitDataArray($array,$limit){
		return array_slice($array, 0,$limit);
	}
	function show_channel_slider(){
		$uid 		= 	$this->uid;
		$other_user = 	isset($_POST['user'])?trim($_POST['user']):'';
		$mode_id	=	isset($_POST['mode_id'])?$_POST['mode_id']:'';
		
		$modeCondi	=	"";
		$user_name	=	"";

		if(!empty($other_user)){
			$getUser  	= $this->DatabaseModel->select_data('user_id,sigup_acc_type,user_name','users use INDEX(user_id)',['user_uname'=>$other_user],1);
			$check="dsadsa";
			$uid		= isset($getUser[0]['user_id'])? $getUser[0]['user_id'] : $uid ;
			$user_name		= isset($getUser[0]['user_name'])? $getUser[0]['user_name'] : '' ;
			$modeCondi= 'AND mode = '.$mode_id;
		}
		if($mode_id=="" && ($this->uid!=$uid)){
			$modeCondi="";
		}
		$data=array();
		if(!empty($uid)){
			$field ="channel_post_video.mode,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration";
			
			$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0 '.$modeCondi;
						
			$globalCond =  $this->common->GobalPrivacyCond($uid);
					
			$where .=  $globalCond;	  
				
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id','left'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
							)
						);
			
				$Order = array('channel_post_video.post_id','DESC');

				$autoplay=array("4000","4500","5000");
				$random_keys=array_rand($autoplay,1);
				$auto = $autoplay[$random_keys];

				$all = $this->DatabaseModel->select_data($field,'channel_post_video',$where,10,$join,$Order);
				//echo $this->db->last_query();
				
				$playlist	= $this->DatabaseModel->select_data('playlist_id,first_video_id,mode','channel_video_playlist',['user_id'=>$uid,'playlist_type'=>1]);
				
				if($mode_id==2){
					$htmlarray = array(	'color'=>"bg-white",
										'title'=>((is_session_uid($uid))?'My':'').' Movies',
										'href' =>'<a href="'.base_url('search?search_query='.$other_user.'&uid='.$uid.'&mode_id=2&hide=search|people&un='.$user_name).'" class="dis_sh_btn muli_font">See all
									<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">	<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>									</svg></span></a>',
										'auto' =>$auto, 
										'videoData'=>$this->common_html->swiper_slider_without_html($all),
										'play_all_url' => $this->getPlayAllUrl($playlist,$mode_id),
										'mode'=> 'Movies'
					);
					array_push($data,$htmlarray);
				}elseif($mode_id==3){
					$htmlarray1 = array(	'color'=>"bg-white",
										'title'=>((is_session_uid($uid))?'My':'').' TV Shows',
										'href' =>'<a href="'.base_url('search?search_query='.$other_user.'&uid='.$uid.'&mode_id=3&hide=search|people&un='.$user_name).'" class="dis_sh_btn muli_font">See all
									<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">	<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>									</svg></span></a>',
										'auto' =>$auto, 
										'videoData'=>$this->common_html->swiper_slider_without_html($all),
										'play_all_url' => $this->getPlayAllUrl($playlist,$mode_id),
										'mode'=> 'TV Shows'
					);
					array_push($data,$htmlarray1);

				}elseif($mode_id==1){
					$htmlarray = array(	'color'=>"bg-white",
										'title'=>((is_session_uid($uid))?'My':'').' Music Videos',
										'href' =>'<a href="'.base_url('search?search_query='.$other_user.'&uid='.$uid.'&mode_id=1&hide=search|people&un='.$user_name).'" class="dis_sh_btn muli_font">See all
									<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">	<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>									</svg></span></a>',
										'auto' =>$auto, 
										'videoData'=>$this->common_html->swiper_slider_without_html($all),	
										'play_all_url' => $this->getPlayAllUrl($playlist,$mode_id),
										'mode'=> 'Music'										
					);
					array_push($data,$htmlarray);
				}elseif($mode_id==7){
					$htmlarray1 = array(	'color'=>"bg-white",
										'title'=>((is_session_uid($uid))?'My':'').' Gaming Videos',
										'href' =>'<a href="'.base_url('search?search_query='.$other_user.'&uid='.$uid.'&mode_id=7&hide=search|people&un='.$user_name).'" class="dis_sh_btn muli_font">See all
									<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">	<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>									</svg></span></a>',
										'auto' =>$auto, 
										'videoData'=>$this->common_html->swiper_slider_without_html($all),
										'play_all_url' => $this->getPlayAllUrl($playlist,$mode_id),
										'mode'=> 'Gaming'				
					);
					array_push($data,$htmlarray1);

				}elseif(!empty($this->uid) && ($this->uid==$uid) && $mode_id==""){
					$htmlarray = array(	'color'=>"bg-white",
										'title'=>((is_session_uid($uid))?'My':'').' Incomplete Uploads',
										'href' =>'',
										'auto' =>$auto, 
										'videoData'=>$this->common_html->swiper_slider_without_html($all),
										
					);
					array_push($data,$htmlarray);

				}
			}
				$resp = array('status'=>1,'data'=>$data);
				echo json_encode($resp);
	}
	
	
	public function getPlayAllUrl($playlist=[],$mode_id=''){
		$url = '';
		if(!empty($playlist)){
			$list = $this->audition_functions->searchForId($mode_id,'mode',$playlist);
			if(!empty($list)){
				$url = $this->share_url_encryption->share_single_page_link_creator(2 .'|'.$list['first_video_id'],'encode','',array('list'=> $list['playlist_id']));
			}
		}
		return $url;
	}

	function getMyChannelPlaylist(){
		
		$uid 	= isset($_POST['uid'])?$_POST['uid']:$this->uid;
		$resp 	= array();
		$data   = array();
		
		if(!empty($uid)){
			
			$start 	=isset($_POST['start'])?$_POST['start']:0;
			$limit 	=isset($_POST['limit'])?$_POST['limit']:8;  
			
			$joinsPlaylist  = array
						(
							'multiple',
							array(
								array('channel_post_video','channel_post_video.post_id = channel_video_playlist.first_video_id','left'),
								array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id AND channel_post_thumb.active_thumb =1',
								'left'),
							)
						);
						
			$wherePlaylist = 'channel_video_playlist.playlist_type = 2 AND channel_video_playlist.user_id = '.$uid . ' '. $this->common->GobalPrivacyCond($uid,'channel_video_playlist'); 
			 
			$OrderPlaylist = array('channel_video_playlist.playlist_id','DESC');
			
			$playlist_ids = $this->DatabaseModel->select_data("channel_video_playlist.first_video_id,channel_post_video.user_id,channel_video_playlist.playlist_id,channel_video_playlist.title,channel_video_playlist.video_ids,channel_video_playlist.playlist_thumb,channel_post_thumb.image_name",'channel_video_playlist',$wherePlaylist,array($limit,$start),$joinsPlaylist,$OrderPlaylist);
		
			
			$playlist = [];
			if(!empty($playlist_ids)){
				foreach($playlist_ids as $list){
					$video_ids 	= explode('|',$list['video_ids']);
					$video_ids_count = sizeof(explode('|',$list['video_ids'])) - 1;
					
					if(is_session_uid($uid) || (!is_session_uid($uid) &&  $video_ids_count>0)){ 
						
						$image_uid  = $list['user_id'];
						$image_name = $list['image_name'];
						if($list['playlist_thumb'] !=''){
							$image_uid  = $uid;
							$image_name = $list['playlist_thumb'];
						}
						$FilterData = $this->share_url_encryption->FilterIva($image_uid,0,$image_name,'',true);
						$img 		= $FilterData['thumb'];
						$webp 		= isset($FilterData['webp'])?$FilterData['webp']:$img;
						$errimg		= thumb_default_image();
						$href 		= $this->share_url_encryption->share_single_page_link_creator(2 .'|'.$list['first_video_id'],'encode','',array('list'=> $list['playlist_id']));
						$edithref 	= base_url('playlist/').$list['playlist_id'];
						
						
						if(strlen($list['title']) < 20){ 
							$title =  $list['title'] ;
						}else{ 
							$title = substr($list['title'],0,20)."..." ;
						}
								
						$playlist[] = array('playlist_id'	 => $list['playlist_id'],
											'first_video_id' => $list['first_video_id'],
											'title'		 	 => strtolower($title),
											'webp' 		 	 => $webp,
											'img'        	 => $img,
											'edithref'   	 => $edithref,
											'href' 		 	 => $href,
											'errimg'     	 => $errimg,
											'video_ids_count'=> $video_ids_count
											);
					}
				}
				
				if(!empty($playlist)){
					$color 	= ($start%2 == 0)? "bg-white" : "";
					$autoplay=array("2000","2500","3000");
					$random_keys=array_rand($autoplay,1);
					$auto = $autoplay[$random_keys];
					
					$data = array(	'color'=>$color,
									'title'=>(isset($this->uid) && $this->uid != '')? 'My Playlists':' Playlists',
									'href' =>'', //view all link
									'auto' =>$auto, 
									'videoData'=>$playlist,
									);
				}
			}
			$this->statusType   = 'Success';
			$this->respMessage  = 'Playlist.';
			$this->statusCode   =  1;			
			$resp['data']	    = $data;
		}
		$this->show_my_response($resp);
	}
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
}
?>

