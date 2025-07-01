<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class watching extends CI_Controller {
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
		$this->load->library(array('dashboard_function' , 'share_url_encryption')); 
		$this->load->helper(array('button')); 
		if(!isset($_SESSION['website_mode'])){  $this->audition_functions->manage_my_web_mode_session(''); }
		
		$this->uid = is_login();
	}
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
    public function getChannelPostCommentCount($post_id=''){
		return $this->DatabaseModel->aggregate_data('channel_post_comment use INDEX(post_id,parent_com_id)','com_id','COUNT',array('post_id'=>$post_id,'parent_com_id'=>0));

	}
    

    function index(){
		$this->video();
	}

    public function video($post_key){
		$shareData = explode('|' , $queryString = $this->share_url_encryption->share_single_page_link_creator($post_key , 'decode'));
		if(count($shareData) == 2){
			if($shareData[0] == 1){

			}else{
				
				$data['list'] 		= $this->getlist($shareData[1]);
				$data['post_id'] 	= $shareData[1];
				$data['page_info'] 	= array('page'=>'single_video','title'=>'Single Video');
				
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/new_single_video',$data);
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);		
			}
		}
 
		 		
	}

	function load_player_playlist(){
		$resp=array();
		if (isset($_POST['pid']) && !empty($_POST['pid'])) {
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.description,channel_post_video.post_id,channel_post_video.uploaded_video,channel_post_video.iva_id,channel_post_video.created_at,channel_post_video.description,channel_post_video.is_video_processed,channel_post_video.mode,channel_post_video.is_stream_live,channel_post_video.video_type,channel_post_video.video_duration,users.user_level,mode_of_genre.genre_name';
				
			$join = array('multiple' , array(
										array(	'channel_post_thumb',
												'channel_post_thumb.post_id = channel_post_video.post_id',
												'left'),
										array(	'users', 
												'users.user_id 				= channel_post_video.user_id', 
												'inner'),
										array('mode_of_genre','mode_of_genre.genre_id 	= channel_post_video.genre','left'),
								));
			
			$globalcond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1 ';
			
			$start = (isset($_POST['start']))?$_POST['start'] :0;
			$limit = (isset($_POST['limit']))?$_POST['limit']:10;
			$limit = array($limit,$start);	
			if(isset($_POST['playlist_id']) && !empty($_POST['playlist_id'])){
				
				$playlists 	= $this->DatabaseModel->select_data('video_ids','channel_video_playlist',['playlist_id'=>$_POST['playlist_id']]);
				
				if(isset($playlists[0]['video_ids']) && !empty($playlists[0]['video_ids'])){
					$playlists 		= implode(',',explode('|',$playlists[0]['video_ids']));
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
				
				/*if(isset($_SESSION['Last_post_ids'])){
					
					$pids = $pids.','.$_SESSION['Last_post_ids'];
					$pids =  implode(',',array_unique(explode(',',$pids)));
					$cookie= array(
						'name'   => 'watched_ids',
						'value'  => $pids,                            
						'expire' => '3000',                                                                                   
						'secure' => TRUE
					);
					
					$this->input->set_cookie($cookie);
					
				}else{
					$watched_ids = $this->input->cookie('watched_ids',true);
					
					if(!empty($watched_ids)){
						$pids = $pids.','.$watched_ids;
					}
					
				}
				$_SESSION['Last_post_ids'] = $pids;*/

				/*if(isset($_POST['last_pids']) && !empty($_POST['last_pids'])){
					$lastPids = json_decode($_POST['last_pids']);
					if(!empty($lastPids) && !in_array($pids,$lastPids)){
						$lastPids = implode(',',$lastPids);
						$pids     = $pids.','.$lastPids;
					}
				}*/
				
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
				$post_ids = array_column($next_video, 'post_id');
				$post_ids = implode(',',$post_ids);
				$next_video = $this->getlist($post_ids,$start=0,$limit=10);
				$resp = array('status'=>1,'data'=>$next_video);	
			}else{
				$resp = array('status'=>0,'data'=>'');	
			}
			
		}
		echo json_encode($resp);
	}

	public function getlist($post_ids,$start=0,$limit=1){
		$post_ids =$post_ids; // preg_replace("/[^0-9]/", "", (slugify($post_ids)));
		
		if(!empty($post_ids)){
			
			$data = [];
			
			$data['single_video'] 	= [];
			
			$where 					= 'channel_post_video.post_id IN('.$post_ids.')';
		
			$field 					= 'channel_post_video.user_id,channel_post_video.post_id,channel_post_video.is_video_processed,channel_post_video.post_key,channel_post_video.created_at,channel_post_video.genre,channel_post_video.sub_genre,channel_post_video.title,channel_post_video.description,channel_post_video.age_restr,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.mode,channel_post_video.category,channel_post_video.language,channel_post_video.tag,channel_post_video.privacy_status,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.iva_id,channel_post_video.video_type,channel_post_video.is_stream_live,users.user_name,users.user_uname,users_content.uc_pic,users_content.uc_type,users.user_level,channel_post_video.active_status,channel_post_video.complete_status,mode_of_genre.genre_name,channel_post_video.video_duration'; 

			$join 					= array('multiple' , array(
										array(	'channel_post_thumb',
												'channel_post_thumb.post_id = channel_post_video.post_id',
												'left'),
										array(	'mode_of_genre',
												'channel_post_video.genre = mode_of_genre.genre_id',
												'left'),
										array(	'users',
												'users.user_id = channel_post_video.user_id',
												'left'),
										array(	'users_content', 
												'users.user_id 	= users_content.uc_userid', 
												'left')
									));
			
			$privacy_status = !empty($this->uid)? $this->common->GobalPrivacyCond($this->uid) : 'AND channel_post_video.privacy_status IN(7)';
			
			$where .= ' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0  AND users.user_status = 1 AND users.is_deleted = 0 '.$privacy_status;
			
			// if($p_uid != $this->uid){
			// 	$where .= ' AND channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1';
			// }
			$main_array = [];
			$single_videos = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join);
			$levellist 	= $this->valuelist->level();
			$modelist 	= $this->valuelist->mode();
			$ages 		= $this->audition_functions->age();
			$this->load->helper('button');
			foreach($single_videos as $single_video){
				$post_id				= isset($single_video['post_id'])?$single_video['post_id']:0;
				$data['post_id'] 		= $post_id;
				$data['single_video'] 	= $single_video;
				$p_uid 					= isset($single_video['user_id'])?$single_video['user_id']:0;
				$iva_id 				= isset($single_video['iva_id'])?$single_video['iva_id']:0;
				$image_name 			= isset($single_video['image_name'])?$single_video['image_name']:'';
				$uploaded_video	 		= isset($single_video['uploaded_video'])?$single_video['uploaded_video']:'';
				$is_video_processed 	= isset($single_video['is_video_processed'])?$single_video['is_video_processed']:0;
				$mode 					= isset($single_video['mode'])?$single_video['mode']:0;
				$age_restr 				= isset($single_video['age_restr'])?$single_video['age_restr']:'';

				$data['single_video']['user_pic'] = (isset($single_video['uc_pic']) && !empty($single_video['uc_pic']))?create_upic($p_uid,$single_video['uc_pic']) : '';
				$data['single_video']['user_default_image'] = user_default_image();
				$data['single_video']['created_at'] = time_elapsed_string($this->common->manageTimezone($data['single_video']['created_at']) ,false);
				$data['single_video']['user_level'] = isset($levellist[$single_video['user_level']])?$levellist[$single_video['user_level']]:'';
				$data['single_video']['count_comments'] = $this->getChannelPostCommentCount($post_id);
				$data['single_video']['web_mode'] 		= isset($modelist[$mode])?$modelist[$mode]:'';
				$data['single_video']['age_restr'] 		= isset($ages[$age_restr])?$ages[$age_restr] : $age_restr;
				$data['errimg'] 						= thumb_default_image();


				$FilterData=$this->share_url_encryption->FilterIva($p_uid,$iva_id,$image_name,trim($uploaded_video),true,'.m3u8',$is_video_processed);
				$videoFile 			= isset($FilterData['video'])?$FilterData['video']:'';
				
				$data['poster'] = isset($FilterData['webp'])?$FilterData['webp']:'';
				$data['sources'] = [ ['src' => $videoFile , 'type' => $this->share_url_encryption->mime_type($videoFile)]  ];

				if($data['single_video']['is_stream_live'] == 1){
					$ivs_info = $this->DatabaseModel->select_data('ivs_info,schedule_time,is_scheduled,is_chat,is_live','users_ivs_info',['user_id' => $p_uid],1);
					
					if(isset($ivs_info[0]['ivs_info']) && isset($ivs_info[0]['is_live']) && $ivs_info[0]['is_live'] == 1){
						$data['single_video']['ivs_info'] 		= $ivs_info[0]['ivs_info'];
						$data['single_video']['schedule_time'] 	= $ivs_info[0]['schedule_time'];
						$data['single_video']['is_scheduled'] 	= $ivs_info[0]['is_scheduled'];
						$data['single_video']['is_chat'] 		= $ivs_info[0]['is_chat'];
					}
					$media_info = $this->DatabaseModel->select_data('media_info,schedule_time,is_scheduled,is_chat,is_live','users_medialive_info',['user_id' => $p_uid],1);
					
					if(isset($media_info[0]['media_info']) && isset($media_info[0]['is_live']) && $media_info[0]['is_live'] == 1){
						$data['single_video']['media_info'] 	= $media_info[0]['media_info'];
						$data['single_video']['schedule_time'] 	= $media_info[0]['schedule_time'];
						$data['single_video']['is_scheduled'] 	= $media_info[0]['is_scheduled'];
						$data['single_video']['is_chat'] 		= $media_info[0]['is_chat'];
					} 
				}
				
				$data['single_video']['isvoted']		=	0;
				$data['single_video']['isMyFavorite']	=	0;
				$data['user_login_id']	=	'';
				
				$data['single_video']['become_a_fan']	= base64_encode( FanButton($p_uid)['new'] );
				
				if(!empty($this->uid)){
					$data['user_login_id'] = $this->uid;
					$post_user 	= array('user_id'=>$data['user_login_id'],'post_id'=>$post_id);	
					$isvoted 	= $this->DatabaseModel->select_data('vote_id','channel_video_vote use INDEX(user_id,post_id)',$post_user,1);
					if(!empty($isvoted)){
						$data['single_video']['isvoted']=1;
					}
					$post_user 	= array('user_id'=>$data['user_login_id'],'channel_post_id'=>$post_id);	
					$isMyFavorite = $this->DatabaseModel->select_data('fav_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
					if(!empty($isMyFavorite)){
						$data['single_video']['isMyFavorite']=1;
					}
				}
			
				$data['metaData'] = array(
										'title' 		=> $data['single_video']['title'], 
										//'description' 	=> $data['single_video']['description'] , 
										'image' 		=> getChnlImg($p_uid,isset($data['single_video']['image_name'])?$data['single_video']['image_name']:''),
										'embed'			=> base_url('embedcv/'.$post_id)
									);
							
							
				$main_array[] = $data;
			}
			
		}
		return $main_array;
	}

	
	
	
	
	
	
	

	

	
}

