<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class share extends CI_Controller {
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
		$this->load->library(array('share_url_encryption')); 
		$this->load->helper(array('playfab', 'iter')); 
		
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
	
	function GetPublishPost(){
		$this->load->library(array('dashboard_function')); 
		$Publishcontent = $this->dashboard_function->get_publish_data();
		echo isset($Publishcontent[0]['post'])?$Publishcontent[0]['post']:'';
	}

	function index(){
		
		$this->video();
	}

	function video($key = NULL,$list = NULL){
		
		$_GET['p'] = isset($_GET['p'])?$_GET['p']:$key;
		
		if(isset($_GET['p']) && !empty($_GET['p'])){
			$shareData = explode('|' , $queryString = $this->share_url_encryption->share_single_page_link_creator($_GET['p'] , 'decode'));
			
			if(count($shareData) == 2){
				
				if($shareData[0] == 1){
					
					$data 				= [];
					$data['post_id'] 	= $shareData[1];
					
					$post 				= $this->DatabaseModel->select_data('pub_media,pub_content,pub_uid','publish_data use INDEX(pub_id)',array('pub_id'=>$data['post_id']),1);
					
					$pub_media 	 		= isset($post[0]['pub_media'])?$post[0]['pub_media']:'';
					$pub_content 		= isset($post[0]['pub_content'])?$post[0]['pub_content']:'';
					$pub_uid 	 		= isset($post[0]['pub_uid'])?$post[0]['pub_uid']:'';
					$data['metaData'] 	= [];
					$image='';

					if(!empty($pub_media)){
						$p_data 			= explode('|',$pub_media);
						$display_content 	= $p_data[0];
						$pub_format 		= $p_data[1];
						
						if($pub_format == 'video'){
							$ThumbImage =  base_url('repo/images/thumbnail.jpg');	
							if(isset($p_data[2])){
								$ThumbImage = AMAZON_URL."aud_".$pub_uid ."/images/".trim($p_data[2]);
							}
							$image = $ThumbImage;
						}else{
							$image = AMAZON_URL.'aud_'.$pub_uid .'/images/'.trim($display_content);
						} 

						$data['metaData'] = array(
							'title' 		=> (!empty($pub_content))?substr($pub_content,0,80).'...':'', 
							'description' 	=> $pub_content, 
							'image' 		=> $image
						);
						
						if($pub_format == 'video'){
							$data['metaData']['embed'] = base_url('embed/'.$data['post_id']);
						}
					}
					
					$data['page_info'] 	= array('page'=>'single_publish_post','title'=>'Post Share');
					
					$this->load->view('home/inc/header',$data);
					$this->load->view('share/single_publish_post',$data);
					$this->load->view('home/inc/footer',$data);	
				}else{ 
					$this->single_video($shareData[1],$list);
				}
			}
		}
	}
	
	public function single_video($post_id,$list){
		if(isset($_SESSION['listLoadedonce'])){
			unset($_SESSION['listLoadedonce']);
		}
		

		$post_id 	= preg_replace( '/[^0-9]/', '', $post_id );
		//$plist 		= $this->getlist($post_id);

		$where 					= 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND  channel_post_video.post_id IN('.$post_id.')';
		
		$field 					= 'channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.description,channel_post_thumb.image_name'; 

		$join 					= 	array('multiple' , array(
										array(	'channel_post_thumb use INDEX(post_id)',
												'channel_post_thumb.post_id = channel_post_video.post_id',
												'left'),
										array(	'users use INDEX(user_id)',
												'users.user_id = channel_post_video.user_id',
												'left')
									));
			
		$privacy_status = !empty($this->uid)? $this->common->GobalPrivacyCond($this->uid) : 'AND channel_post_video.privacy_status IN(7)';
			
		$where .= ' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0  AND users.user_status = 1 AND users.is_deleted = 0 '.$privacy_status;
		
		$metaData 		= $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,1,$join);
		if(!empty($metaData)){
			$plist[0]['metaData'] = array(
				'title' 		=> $metaData[0]['title'], 
				'description' 	=> $metaData[0]['description'] , 
				'image' 		=> getChnlImg($metaData[0]['user_id'], isset($metaData[0]['image_name'])?$metaData[0]['image_name']:'',''),
				'embed'			=> base_url('embedcv/'.$post_id)
			);
		}
		if($plist){
			$data = [
				'list'      => [],
				'post_id'   => $post_id,
				'metaData'  => isset($plist[0]['metaData']) ? $plist[0]['metaData'] : [],
				'playlist'  => [
					'list_key' => $list,
					'list_id'  => $this->share_url_encryption->share_single_page_link_creator($list, 'decode')
				],
				'page_info' => [
					'page'  => 'single_video',
					'title' => 'Single Video'
				]
			];
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/new_single_video',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);	
		}else{
			redirect(base_url());
		}
	}
	
	function generate_link(){
		if(isset($_POST['taget'])){
			$shareData = explode('|' , $_POST['taget']);
			if(count($shareData) >= 2){
				$post_id = null;
				$list='';
				if(isset($shareData[2]) && !empty($shareData[2])){
					$_POST['taget'] = $shareData[0].'|'.$shareData[1];
					$list =  array('list'=>$shareData[2]);
				}
				$link = $this->share_url_encryption->share_single_page_link_creator($_POST['taget'] , 'encode','',$list);
				
				$sharetext = '';
				$description = '';
				$media = '';
				if($shareData[0] == 2){ //check video post
					$post_id = $shareData[1];
					$checkVideoData = $this->DatabaseModel->select_data('title,description','channel_post_video',array('post_id' => $shareData[1]) , 1);
					if(!empty($checkVideoData)){
						$sharetext = urlencode($checkVideoData[0]['title']);
						$description = urlencode(strip_tags($checkVideoData[0]['description']));
						//$description = urlencode(strip_tags(json_decode($checkVideoData[0]['description'])));
					}
					$thumb = $this->DatabaseModel->select_data('user_id,image_name','channel_post_thumb',array('post_id' => $shareData[1]) , 1);
					if(isset($thumb[0]['user_id'])){
						$media = $this->share_url_encryption->FilterIva($thumb[0]['user_id'],'',$thumb[0]['image_name'],'',false)['thumb'];
					}
				}
				$link = urlencode($link);
				$resp = array('status' => 1 , 'link' =>  array(
								'main' 		=> urldecode($link),	
								'facebook' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$link,
								'twitter' 	=> 'https://twitter.com/share?url='.$link.'&text='.$sharetext.'&via=Discovered.TV&hashtags=Discovered.TV',
								'pinterest' => 'https://pinterest.com/pin/create/button/?url='.$link.'&media='.urlencode($media).'&description=Discovered.TV',
								'linkedin' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$link.'&title='.$sharetext.'&summary='.$description.'&source=Discovered.TV',
								'livejournal'=>'https://www.livejournal.com/update.bml?subject='.$sharetext.'&event='.$link.'',
								'skype'		 =>'https://web.skype.com/share?url='.$link.'&text='.$sharetext.'',
								'tumblr'	 =>'https://www.tumblr.com/widgets/share/tool?canonicalUrl='.$link.'&title='.$sharetext.'&caption='.$description.'',
								'reddit'	 =>'https://reddit.com/submit?url='.$link.'&title='.$sharetext.'',
								'blogger'	 =>'https://www.blogger.com/blog-this.g?u='.$link.'&n='.$sharetext.'&t='.$description.'',
								'whatsapp'   =>'https://api.whatsapp.com/send?text='.$link.'',
							) 
						);
				
				$this->load->library('Gamification');
				$this->load->model('UserModel');

				$this->gamification->player_shared_video(
					first($this->UserModel->get($this->uid)),
					$post_id
				);
			}else{
				$resp = array('status' => 0);
			}
		}else{
			$resp = array('status' => 0);
		}
		echo json_encode($resp);
	}
	
	function add_to_favorite(){
		if(isset($_POST['post_id'])){
			if(isset($this->session->userdata['user_login_id'])){
				$uid = $this->session->userdata['user_login_id'];
				$favCond = array('user_id'=>$uid,'channel_post_id'=>$_POST['post_id']);	
				$isMyFavorite = $this->DatabaseModel->select_data('*','channel_favorite_video',$favCond,1);
				if(empty($isMyFavorite)){
					$favCond['created_at'] = date('Y-m-d H:i:s');
					$this->DatabaseModel->access_database('channel_favorite_video','insert',$favCond);
					echo 1;
				}else{
					$this->DatabaseModel->access_database('channel_favorite_video','delete','', $favCond);
					echo 0;
				}
			}
		}
	}
	
	function GetFanListForProShare(){
		$data=[];
		if ($this->input->is_ajax_request()) {
		   if(!empty($this->uid)){
				$field = 'users.user_name,become_a_fan.user_id,become_a_fan.following_id,users_content.uc_pic';
				$join = array('multiple' , array(
									array(	'users_content', 
											'users_content.uc_userid = become_a_fan.following_id', 
											'left'),
									array(	'users',
											'users.user_id 			= become_a_fan.following_id',
											'left'),
									));
				$data = $this->DatabaseModel->select_data($field,'become_a_fan use INDEX (following_id)',array('become_a_fan.user_id'=>$this->uid),50,$join);
				
				$resp 	= json_encode(array('status'=>1,'data'=>$data));
		   }else{
				$resp 	= json_encode(array('status'=>0,'data'=>$data)); 
		   }
		}else{
				$resp 	= json_encode(array('status'=>0,'data'=>$data));
		}
		echo $resp;
	}
	
	function shareOnDiscoveredAsNoti($share_status = null){
		
		if ($this->input->is_ajax_request()) {
			if(isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['reference_id']) && !empty($_POST['reference_id'])){
				if(!empty($this->uid)){
					$to_user 	  = $_POST['user_id'];
					$reference_id = $_POST['reference_id'];
					
					$where_array = array(	'noti_type'		=>	4,
											'noti_status'	=>	$share_status,
											'from_user'		=>	$this->uid,
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
						$fullname 	= $this->audition_functions->get_user_fullname($this->uid);
						$profil_of 	= $this->audition_functions->getSharedProfileName($share_status,4,$this->uid,$reference_id);
						$msg_array 	=  [
							'title'	=>	$fullname .' '. $mess .' '. $profil_of,
							'body'	=>	'',
							'icon'	=>	base_url('repo/images/firebase.png'),
							'click_action'=>$link
						];
						$this->audition_functions->sendNotification($token,$msg_array);
						$resp = json_encode(array('status'=>1,'message'=>'It\'s done.'));
					}else{
						$resp = json_encode(array('status'=>1,'message'=>'push notification failed.'));
					}
				}else{
					$resp = json_encode(array('status'=>0,'message'=>'user session expired.'));
				}	
			}else{
				$resp = json_encode(array('status'=>0,'message'=>'something went wrong.'));
			}
		}else{
			$resp = json_encode(array('status'=>0,'message'=>'direct access not allowed.'));
		}
		echo $resp;
	}
	
	function shareThisPostToMe(){
		$resp = array();
		
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
			if( isset($_POST['share_pid']) && !empty($this->uid)  && $this->input->is_ajax_request() ) {
				$share_array = array(
									'pub_uid' 	=>  isset($_POST['share_uid'])?$_POST['share_uid'] : $this->uid,
									'pub_reason'=>	2,
									'pub_date'	=>	date('Y-m-d H:i:s'),
									'pub_status'=>  isset($_POST['share_uid'])? 5 :7,
									'share_pid'	=>  $_POST['share_pid'],
									'share_uid'	=>	$this->uid
 								);
				$this->DatabaseModel->access_database('publish_data','insert',$share_array);				
				$this->statusCode = 1;				
			}else{
				$this->respMessage = 'Something Went Wrong,please try again';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	
	/* New player*/
	function load_player_playlist(){
		$resp=array();
		if (isset($_POST['uid']) && !empty($_POST['uid'])) {
			$field = 'channel_post_video.post_id';
				
			$join = array('multiple' , array(
				array(	'channel_post_thumb use INDEX(post_id)',
						'channel_post_thumb.post_id = channel_post_video.post_id',
						'left'),
				array(	'users use INDEX(user_id)', 
						'users.user_id 				= channel_post_video.user_id', 
						'inner'),
				array(	'mode_of_genre use INDEX(genre_id)',
						'mode_of_genre.genre_id 	= channel_post_video.genre',
						'left'),
			));
			
			$globalcond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1  AND users.is_deleted = 0 ';

			// $privacy_status = !empty($this->uid)? $this->common->GobalPrivacyCond($this->uid) : 'AND channel_post_video.privacy_status IN(7)';
			
			// $where .= ' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0  AND users.user_status = 1 AND users.is_deleted = 0 '.$privacy_status;
			
			$start = (isset($_POST['start']))?$_POST['start'] :0;
			$limit = (isset($_POST['limit']))?$_POST['limit']:10;
			
			$startlimit = array($limit,$start);	

			
			if(isset($_POST['list']) && !empty($_POST['list'])){
				$limit = PLAYLIST_VIDEO_LIMIT;

				$playlists 	= $this->DatabaseModel->select_data('video_ids','channel_video_playlist use INDEX(playlist_id)',['playlist_id'=>$_POST['list']]);
							
				if(isset($playlists[0]['video_ids']) && !empty($playlists[0]['video_ids'])){
					$playlists 		= implode(',',explode('|',$playlists[0]['video_ids']));
					$video_items 	= trim($playlists,',');
						
					$cond 			= $globalcond .' AND channel_post_video.post_id IN('.$video_items.') ' ;
					$order 			= 'FIELD(channel_post_video.post_id, '.$video_items.')';
					
					$next_video 	= $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,PLAYLIST_VIDEO_LIMIT,$join, $order );
					
				}else{
					if(empty($next_video))
					$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$globalcond,$startlimit,$join,['channel_post_video.post_id','DESC'] );
				}
			}else{
				
				if(isset($_SESSION['Last_post_ids'])){ 
					$pids = $_SESSION['Last_post_ids'];
					$pids =  implode(',',array_unique(explode(',',$pids)));
				}else{
					$pids = 0;
				}

				$Maincond 	=  $globalcond.'AND channel_post_video.post_id NOT IN('.$pids.')';
				
				$mod 		= 	isset($_POST['mod']) 		&& !empty($_POST['mod'])		?$_POST['mod']:'1';
				// $cat 		= 	isset($_POST['cat']) 		&& !empty($_POST['cat'])	?		$_POST['cat']:'1';
				$gen 		= 	isset($_POST['gen']) 		&& !empty($_POST['gen'])		?$_POST['gen']:'0';
				// $uid 		= 	isset($_POST['uid']) 		&& !empty($_POST['uid'])		?$_POST['uid']:'0';
				// $cond2 		= ' AND channel_post_video.mode 	= '.$mod.' AND channel_post_video.genre 	= '.$gen.' AND channel_post_video.user_id 	= '.$uid.'';
				
				$cond3 		= ' AND channel_post_video.mode 	= '.$mod.' AND channel_post_video.genre 	= '.$gen.'';
				$cond4 		= ' AND channel_post_video.mode 	= '.$mod.'';
				$order 		= 'rand()'; 
				
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)', $Maincond . $cond3 , $startlimit ,$join, $order);
				// if(empty($next_video))
				// $next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$Maincond.$cond3 ,$startlimit ,$join, $order );
				
				if(empty($next_video))
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$Maincond .$cond4 ,$startlimit ,$join, $order );

				if(empty($next_video))
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$globalcond,$startlimit,$join,['channel_post_video.post_id','DESC'] );
				
			}	
				
			if(isset($next_video[0])){
				$post_ids 	= array_column($next_video, 'post_id');

				if(empty(trim($_POST['list'])) &&  !isset($_SESSION['listLoadedonce']) ){
					$p 							= 	$_POST['pid'];
					$u 							= 	$_POST['uid'];
					$_SESSION['listLoadedonce'] = 	1;	
					
					$cond 						=   "user_id = $u AND (video_ids LIKE '%".$p."%')" ;
					$vdeo 						= 	$this->DatabaseModel->select_data('video_ids','channel_video_playlist',$cond,1 );
					$vdeo 						= 	isset($vdeo[0]['video_ids'])? explode('|', ltrim($vdeo[0]['video_ids'],'|') ) :[];
					
					$post_ids 					= 	array_merge($vdeo,$post_ids);
				}

				$post_ids 	= implode(',',$post_ids);
				
				$_SESSION['Last_post_ids'] = $post_ids;

				$next_video = $this->getlist($post_ids,$start=0,$limit);
				$resp 		= array('status'=>1,'data'=>$next_video);	
			}else{
				$resp = array('status'=>0,'data'=>[]);	
			}
			
		}
		echo json_encode($resp);
	}

	public function getlist($post_ids,$start=0,$limit=10){
		$post_ids =$post_ids; // preg_replace("/[^0-9]/", "", (slugify($post_ids)));
		
		if(!empty($post_ids)){
			
			$data = [];
			
			$data['single_video'] 	= [];
			
			$where 					= 'channel_post_video.active_status = 1 AND channel_post_video.post_id IN('.$post_ids.')';
		
			$field 					= 'channel_post_video.user_id,channel_post_video.post_id,channel_post_video.is_video_processed,channel_post_video.post_key,channel_post_video.created_at,channel_post_video.genre,channel_post_video.sub_genre,channel_post_video.title,channel_post_video.description,channel_post_video.age_restr,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.mode,channel_post_video.category,channel_post_video.language,channel_post_video.privacy_status,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.iva_id,channel_post_video.video_type,channel_post_video.is_stream_live,users.user_name,users.user_uname,users_content.uc_pic,users_content.uc_type,users.user_level,mode_of_genre.genre_name,channel_post_video.video_duration,users.playfab_id'; 

			$join 					= array('multiple' , array(
										array(	'channel_post_thumb use INDEX(post_id)',
												'channel_post_thumb.post_id = channel_post_video.post_id',
												'left'),
										array(	'mode_of_genre use INDEX(genre_id)',
												'channel_post_video.genre = mode_of_genre.genre_id',
												'left'),
										array(	'users use INDEX(user_id)',
												'users.user_id = channel_post_video.user_id',
												'left'),
										array(	'users_content use INDEX(uc_userid)', 
												'users.user_id 	= users_content.uc_userid', 
												'left')
									));
			
			$privacy_status = !empty($this->uid)? $this->common->GobalPrivacyCond($this->uid) : 'AND channel_post_video.privacy_status IN(7)';
			
			$where .= ' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0  AND users.user_status = 1 AND users.is_deleted = 0 '.$privacy_status;
			
			if($p_uid != $this->uid){
				$where .= ' AND channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1';
			}
			
			$order 			= 'FIELD(channel_post_video.post_id, '.$post_ids.')';

			$main_array 	= [];
			$single_videos 	= $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,$order);
			$levellist 		= $this->valuelist->level();
			$modelist 		= $this->valuelist->mode();
			$ages 			= $this->audition_functions->age();
			
			$this->load->helper('button');

			foreach($single_videos as $single_video){
				
				$post_id				= isset($single_video['post_id'])?$single_video['post_id']:0;
				$data['post_id'] 		= $post_id;
				$data['single_video'] 	= $single_video;
				
				$data['single_video']['title'] 			= checkForeignChar($single_video['title']);
				$data['single_video']['description'] 	= checkForeignChar($single_video['description']);
				$data['single_video']['user_name'] 		= checkForeignChar(addslashes($single_video['user_name']));

				$data['full_title'] = $data['single_video']['title'];
				$data['title'] 		= (strlen($data['single_video']['title'])< 20)?$data['single_video']['title']:substr($data['single_video']['title'],0,20)."...";


				$p_uid 					= isset($single_video['user_id'])?$single_video['user_id']:0;
				$iva_id 				= isset($single_video['iva_id'])?$single_video['iva_id']:0;
				$image_name 			= isset($single_video['image_name'])?$single_video['image_name']:'';
				$uploaded_video	 		= isset($single_video['uploaded_video'])?$single_video['uploaded_video']:'';
				$is_video_processed 	= isset($single_video['is_video_processed'])?$single_video['is_video_processed']:0;
				$mode 					= isset($single_video['mode'])?$single_video['mode']:0;
				$genre 					= isset($single_video['genre'])?$single_video['genre']:0;
				$category 				= isset($single_video['category'])?$single_video['category']:0;
				$age_restr 				= isset($single_video['age_restr'])?$single_video['age_restr']:'';

				$data['single_video']['user_pic'] 		= (isset($single_video['uc_pic']) && !empty($single_video['uc_pic']))?create_upic($p_uid,$single_video['uc_pic']) : '';
				$data['single_video']['user_default_image'] = user_default_image();
				$data['single_video']['created_at'] 	= time_elapsed_string($this->common->manageTimezone($data['single_video']['created_at']) ,false);
				$data['single_video']['user_level'] 	= isset($levellist[$single_video['user_level']])?$levellist[$single_video['user_level']]:'';
				$data['single_video']['count_comments'] = $this->getChannelPostCommentCount($post_id);
				$data['single_video']['web_mode'] 		= isset($modelist[$mode])?$modelist[$mode]:'';
				$data['single_video']['age_restr'] 		= isset($ages[$age_restr])?$ages[$age_restr] : $age_restr;
				$data['errimg'] 						= thumb_default_image();
				$data['single_video']['genre_name'] 	= (isset($single_video['genre_name']) && $single_video['genre_name'] !=null)? $single_video['genre_name'] : '';

				$FilterData			=	$this->share_url_encryption->FilterIva($p_uid,$iva_id,$image_name,trim($uploaded_video),true,'.m3u8',$is_video_processed);
				$videoFile 			= 	isset($FilterData['video'])?$FilterData['video']:'';
				
				$img 				= 	$FilterData['thumb'];
				$data['img'] 		= 	$img;
				$data['poster']		= 	isset($FilterData['webp'])?$FilterData['webp']:'';
				$data['sources'] 	= 	[ ['src' => $videoFile , 'type' => $this->share_url_encryption->mime_type($videoFile)]  ];

				$data['video_duration']	= isset($single_video['video_duration'])?$single_video['video_duration']:0;

				$data['href'] = base_url($this->common->generate_single_content_url_param($single_video['post_key'], 2));
				
				if($data['single_video']['is_stream_live'] == 1){
					$ivs_info = $this->DatabaseModel->select_data('ivs_info,schedule_time,is_scheduled,is_chat,is_live','users_ivs_info',['user_id' => $p_uid],1);
					
					if(isset($ivs_info[0]['ivs_info']) && isset($ivs_info[0]['is_live']) && $ivs_info[0]['is_live'] == 1){
						$data['single_video']['schedule_time'] 	= $ivs_info[0]['schedule_time'];
						$data['single_video']['is_scheduled'] 	= $ivs_info[0]['is_scheduled'];
						$data['single_video']['is_chat'] 		= $ivs_info[0]['is_chat'];
					}
					
					$media_info = $this->DatabaseModel->select_data('media_info,schedule_time,is_scheduled,is_chat,is_live','users_medialive_info',['user_id' => $p_uid],1);
					
					if(isset($media_info[0]['media_info']) && isset($media_info[0]['is_live']) && $media_info[0]['is_live'] == 1){
						$data['single_video']['schedule_time'] 	= $media_info[0]['schedule_time'];
						$data['single_video']['is_scheduled'] 	= $media_info[0]['is_scheduled'];
						$data['single_video']['is_chat'] 		= $media_info[0]['is_chat'];

						// $vastTags 		= [1 => '24008711', 2 => '24008715', 3 => '24008712', 7 => '24008713', 'social' => '24008714'];
						// $placementId 	= isset($vastTags[$single_video['mode']]) ? $vastTags[$single_video['mode']] : $vastTags[1];

						$placementId    = 	819760;
						$width 			=	640; 
						$height 		=	480; 
						$adsParam 		=  '?ads.w='.$width.'&ads.h='.$height.'&ads.placementId='.$placementId.'&ads.viewerid='.$this->uid.'&ads.video_id='.$post_id.'&ads.userid='.$p_uid.'&ads.genreid='.$genre.'&ads.categoryid='.$category.'&ads.devicetype=web&aws.logMode=DEBUG';

						$data['sources'] = [ ['src' =>  $uploaded_video ? $uploaded_video . $adsParam : 'nostream.mp4' , 'type' => $uploaded_video ? $data['sources'][0]['type'] : 'video/mp4' ]  ];
					} 
				}
				
				$data['single_video']['isvoted']		=	0;
				$data['single_video']['isMyFavorite']	=	0;
				$data['user_login_id']					=	'';
				$data['single_video']['become_a_fan']	= 	base64_encode( FanButton($p_uid)['new'] );
				
				$data['single_video']['captions'] 		= $this->DatabaseModel->select_data('caption_name,language,user_id','channel_post_caption use INDEX(post_id)',['post_id'=>$post_id]);

				if(!empty($this->uid)){
					$data['user_login_id'] = $this->uid;
					$post_user 	= array('user_id'=>$data['user_login_id'],'post_id'=>$post_id);	
					$isvoted 	= $this->DatabaseModel->select_data('vote_id','channel_video_vote use INDEX(user_id,post_id)',$post_user,1);
					if(!empty($isvoted)){
						$data['single_video']['isvoted']	=	1;
					}

					$post_user 	= array('user_id'=>$data['user_login_id'],'channel_post_id'=>$post_id);	
					$isMyFavorite = $this->DatabaseModel->select_data('fav_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
					if(!empty($isMyFavorite)){
						$data['single_video']['isMyFavorite']	=	1;
					}
				}
			
				$data['metaData'] = array(
										'title' 		=> $data['single_video']['title'], 
										'description' 	=> $data['single_video']['description'] , 
										'image' 		=> getChnlImg($p_uid,isset($data['single_video']['image_name'])?$data['single_video']['image_name']:'',''),
										'embed'			=> base_url('embedcv/'.$post_id)
									);
							
							
				$main_array[] = $data;
			}
			
		}
	
		return $main_array;
	}	

	public function getChannelPostCommentCount($post_id=''){
		return $this->DatabaseModel->aggregate_data('channel_post_comment use INDEX(post_id,parent_com_id)','com_id','COUNT',array('post_id'=>$post_id,'parent_com_id'=>0));

	}

	function getVideo(){
		$resp = array();
		
		if( isset($_POST['pid']) && $this->input->is_ajax_request() ) {
			$post_id 	= preg_replace( '/[^0-9]/', '', $_POST['pid'] );
			$video		= $this->getlist($post_id);	
			$resp 		= json_encode(array('status'=>1,'data'=>$video));		
		}
		echo $resp;
	}
}

