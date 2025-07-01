<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	public $responses='';
	public $uid = '';
	function __Construct(){ 
		parent::__Construct();
		$this->load->library(array('Audition_functions','query_builder','share_url_encryption'));
		$this->load->helper(array('info'));
		$this->uid = is_login();
	}
	function testembedcode(){
		$this->load->view('test/testembed');
	}
	function garbagereplace() {
		echo checkForeignChar('J Keba x Mary&#39AAM  The Way (Official Video)');
	}
	function remove_special_char() {
		// Replace en dash with a regular dash (optional)
		$text = str_replace("–", "-", $text='https://www.youtube.com/@officialjkeba');
		
		// Remove HTML tags
		$text = strip_tags($text);
		
		// Replace unwanted special characters using preg_replace
		// This regex keeps only alphanumeric characters, spaces, line breaks, and selected special characters, including apostrophes, quotes, and periods
		echo preg_replace('/[^A-Za-z0-9 !@#$_%^&*()\-\'":\/\n\.]/u', '', $text);
	}
	function getresponse(){
		$listID = 'kqo7p' ;
		$customerEmail = 'test@gmail.com';
		$customerName= 'test';
		$args = array(
		'campaign' => array('campaignId'=>$listID),
		'email' => $customerEmail,
		'name'  =>  $customerName,
		'dayOfCycle'=>0,
		);                                                       
		$data_string = json_encode($args);                                                                                   
		$ch = curl_init('https://api.getresponse.com/v3/contacts');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
		'X-Auth-Token:api-key 7p55ps3l2j6rvww0e7xyi0ir94pzv6j5',                                                                      
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
		);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		echo $result;
	}
	function loadThreeJs(){
		$this->load->view('test/three');
	}

	function Performance_Analytics_API(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.rubiconproject.com/prebidanalytics/v1/prebid/wrapper/offline?account=publisher/9041');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n\t\"criteria\":{\n\t\t\"dimension\": \"pb_channel,pb_device_class,pb_wrapper_name\",\n    \"metric\": \"pb_ad_requests\",\n    \"limit\": 1000,\n    \"dateRange\": \"yesterday\",\n    \"start\": null,\n    \"end\": null,\n    \"timezone\": null,\n    \"currency\": \"USD\",\n    \"filters\": null,\n\t},\n}");
		curl_setopt($ch, CURLOPT_USERPWD, '5157f203526906f60c60db29fd67d3ea646ca18c:5c387a73cc6c713ce4737bf4a7e2dc46');

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		
		print_r($result);
	}

	function Historical_Wrapper_API(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.rubiconproject.com/analytics/v1/report/?account=publisher/9041&dateRange=yesterday&dimensions=date&metrics=impressions,paid_impression,revenue&filters=&format=json',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Basic NTE1N2YyMDM1MjY5MDZmNjBjNjBkYjI5ZmQ2N2QzZWE2NDZjYTE4Yzo1YzM4N2E3M2NjNmM3MTNjZTQ3MzdiZjRhN2UyZGM0Ng=='
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		print_r($response);
	}

	function Performance_Wrapper_API(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.rubiconproject.com/prebidanalytics/v1/prebid/wrapper-realtime?account=publisher/9041&metrics=pb_ad_requests&dateRange=yesterday&timezone=America/Los_Angeles&currency=USD&dimensions=date,pb_bidder_name',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Basic NTE1N2YyMDM1MjY5MDZmNjBjNjBkYjI5ZmQ2N2QzZWE2NDZjYTE4Yzo1YzM4N2E3M2NjNmM3MTNjZTQ3MzdiZjRhN2UyZGM0Ng=='
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		print_r($response);

	}
	public function new_player($post_key){
		$shareData = explode('|' , $queryString = $this->share_url_encryption->share_single_page_link_creator($post_key , 'decode'));
		if(count($shareData) == 2){
			if($shareData[0] == 1){

			}else{
				
				$data['list'] 		= $this->getlist($shareData[1]);
				$data['post_id'] 	= $shareData[1];
				$data['page_info'] 	= array('page'=>'single_video','title'=>'Single Video');
				//echo '<pre>';
				//print_r($data);die;
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/new_single_video',$data);
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);		
			}
		}
		
		 		
	}
	// public function getlist($post_id){
		
	// 	$post_id = preg_replace("/[^0-9]/", "", (slugify($post_id)));
		
	// 	if(!empty($post_id)){
			
	// 		$data = [];
	// 		$data['post_id'] 		= $post_id;
	// 		$data['single_video'] 	= [];
			
	// 		$where 					= 'channel_post_video.post_id = '.$post_id.'';
			
	// 		$field 					= 'channel_post_video.is_video_processed,channel_post_video.title,channel_post_video.description,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.iva_id,users.user_name,users.user_uname,users.user_level,users.user_id'; 
			
	// 		$join 					= array('multiple' , array(
	// 									array(	'channel_post_thumb',
	// 											'channel_post_thumb.post_id = channel_post_video.post_id',
	// 											'left'),
	// 									array(	'users',
	// 											'users.user_id = channel_post_video.user_id',
	// 											'left'),
	// 								));
			
	// 		$privacy_status = !empty($this->uid)? $this->common->GobalPrivacyCond($this->uid) : 'AND channel_post_video.privacy_status IN(7)';
			
	// 		$where .= ' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0  AND users.user_status = 1 AND users.is_deleted = 0 '.$privacy_status;
			
	// 		// if($p_uid != $this->uid){
	// 		// 	$where .= ' AND channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1';
	// 		// }
			
	// 		$single_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,1,$join);
			
	// 		if(isset($single_video[0])){
				
	// 			$data['single_video'] 	= $single_video[0];
	// 			$p_uid 					= isset($single_video[0]['user_id'])?$single_video[0]['user_id']:0;
	// 			$iva_id 				= isset($single_video[0]['iva_id'])?$single_video[0]['iva_id']:0;
	// 			$image_name 			= isset($single_video[0]['image_name'])?$single_video[0]['image_name']:'';
	// 			$uploaded_video	 		= isset($single_video[0]['uploaded_video'])?$single_video[0]['uploaded_video']:'';
	// 			$is_video_processed 	= isset($single_video[0]['is_video_processed'])?$single_video[0]['is_video_processed']:0;
				
	// 			$FilterData=$this->share_url_encryption->FilterIva($p_uid,$iva_id,$image_name,trim($uploaded_video),false,'.m3u8',$is_video_processed);
				
	// 			$data['ThumbImage'] = isset($FilterData['webp'])?$FilterData['webp']:'';
	// 			$data['videoFile'] 	= isset($FilterData['video'])?$FilterData['video']:'';
	// 			$data['mime_type']  = $this->share_url_encryption->mime_type($data['videoFile']);
			
	// 			$data['metaData'] = array(
	// 									'title' 		=> $data['single_video']['title'], 
	// 									'description' 	=> $data['single_video']['description'] , 
	// 									'image' 		=> getChnlImg($p_uid,isset($data['single_video']['image_name'])?$data['single_video']['image_name']:''),
	// 									'embed'			=> base_url('embedcv/'.$post_id)
	// 								);
							
	// 			return $data;					
	// 		}
	// 	}
	// }


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


	public function getChannelPostCommentCount($post_id=''){
		return $this->DatabaseModel->aggregate_data('channel_post_comment use INDEX(post_id,parent_com_id)','com_id','COUNT',array('post_id'=>$post_id,'parent_com_id'=>0));

	}

	// public function single_video($post_id,$viewload=false){
	// 	$post_id = preg_replace("/[^0-9]/", "", (slugify($post_id)));
		
	// 	if(!empty($post_id)){

	// 		$data = [];
	// 		$data['post_id'] 		= $post_id;
	// 		$data['single_video'] 	= [];
			
	// 		$where 					= 'channel_post_video.post_id = '.$post_id.'';
	// 		$getUid 				= $this->DatabaseModel->select_data('user_id','channel_post_video use INDEX(post_id)',$where,1);
	// 		$p_uid 					= isset($getUid[0]['user_id'])?$getUid[0]['user_id']:'';
			
	// 		$data['p_uid'] 			= $p_uid;
			
	// 		$field 					= 'channel_post_video.is_video_processed,channel_post_video.post_key,channel_post_video.created_at,channel_post_video.genre,channel_post_video.sub_genre,channel_post_video.title,channel_post_video.description,channel_post_video.age_restr,channel_post_video.uploaded_video,channel_post_thumb.image_name,channel_post_video.mode,channel_post_video.category,channel_post_video.language,channel_post_video.tag,channel_post_video.privacy_status,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.iva_id,channel_post_video.video_type,channel_post_video.is_stream_live,users.user_name,users.user_uname,users_content.uc_pic,users_content.uc_type,users.user_level'; 
			
	// 		$join 					= array('multiple' , array(
	// 									array(	'channel_post_thumb',
	// 											'channel_post_thumb.post_id = channel_post_video.post_id',
	// 											'left'),
	// 									array(	'users',
	// 											'users.user_id = channel_post_video.user_id',
	// 											'left'),
	// 									array(	'users_content', 
	// 											'users.user_id 	= users_content.uc_userid', 
	// 											'left')
	// 								));
			
	// 		$privacy_status = !empty($this->uid)? $this->common->GobalPrivacyCond($p_uid) : 'AND channel_post_video.privacy_status IN(7)';
			
	// 		$where .= ' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0  AND users.user_status = 1 AND users.is_deleted = 0 '.$privacy_status;
			
	// 		if($p_uid != $this->uid){
	// 			$where .= ' AND channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1';
	// 		}
			
	// 		$single_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,1,$join);
			
	// 		if(isset($single_video[0])){

	// 			$data['single_video'] 	= 	$single_video[0];
	// 			$data['post_key']		=	base_url().$this->common->generate_single_content_url_param($data['single_video']['post_key'] , 2);
	// 			$created_at 			= 	time_elapsed_string($this->common->manageTimezone($data['single_video']['created_at']) ,false);
						
	// 			$data['single_video']['created_at'] = $created_at;
				
	// 			if($data['single_video']['is_stream_live'] == 1){
	// 				$ivs_info = $this->DatabaseModel->select_data('ivs_info,schedule_time,is_scheduled,is_chat,is_live','users_ivs_info',['user_id' => $p_uid],1);
					
	// 				if(isset($ivs_info[0]['ivs_info']) && isset($ivs_info[0]['is_live']) && $ivs_info[0]['is_live'] == 1){
	// 					$data['single_video']['ivs_info'] = $ivs_info[0]['ivs_info'];
	// 					$data['single_video']['schedule_time'] = $ivs_info[0]['schedule_time'];
	// 					$data['single_video']['is_scheduled'] = $ivs_info[0]['is_scheduled'];
	// 					$data['single_video']['is_chat'] = $ivs_info[0]['is_chat'];
	// 				}
	// 				$media_info = $this->DatabaseModel->select_data('media_info,schedule_time,is_scheduled,is_chat,is_live','users_medialive_info',['user_id' => $p_uid],1);
					
	// 				if(isset($media_info[0]['media_info']) && isset($media_info[0]['is_live']) && $media_info[0]['is_live'] == 1){
	// 					$data['single_video']['media_info'] = $media_info[0]['media_info'];
	// 					$data['single_video']['schedule_time'] = $media_info[0]['schedule_time'];
	// 					$data['single_video']['is_scheduled'] = $media_info[0]['is_scheduled'];
	// 					$data['single_video']['is_chat'] = $media_info[0]['is_chat'];
	// 				} 
	// 			}
				
	// 			$data['isvoted']		=	0;
	// 			$data['isMyFavorite']	=	0;
	// 			$data['user_login_id']	=	'';
				
	// 			if(!empty($this->uid)){
	// 				$data['user_login_id'] = $this->uid;
	// 				$post_user 	= array('user_id'=>$data['user_login_id'],'post_id'=>$post_id);	
	// 				$isvoted 	= $this->DatabaseModel->select_data('vote_id','channel_video_vote use INDEX(user_id,post_id)',$post_user,1);
	// 				if(!empty($isvoted)){
	// 					$data['isvoted']=1;
	// 				}
	// 				$post_user 	= array('user_id'=>$data['user_login_id'],'channel_post_id'=>$post_id);	
	// 				$isMyFavorite = $this->DatabaseModel->select_data('fav_id','channel_favorite_video use INDEX(user_id,channel_post_id)',$post_user,1);
	// 				if(!empty($isMyFavorite)){
	// 					$data['isMyFavorite']=1;
	// 				}
	// 			}
			
	// 			$data['page_info'] = array('page'=>'single_video','title'=>'Single Video');
				
	// 			$data['metaData'] = array(
	// 									'title' 		=> $data['single_video']['title'], 
	// 									'description' 	=> $data['single_video']['description'] , 
	// 									'image' 		=> getChnlImg($p_uid,isset($data['single_video']['image_name'])?$data['single_video']['image_name']:''),
	// 									'embed'			=> base_url('embedcv/'.$post_id)
	// 								);
							
	// 			$this->load->view('home/inc/header',$data);
	// 			$this->load->view('home/new_single_video',$data);
	// 			$this->load->view('common/notofication_popup');
	// 			$this->load->view('home/inc/footer',$data);					
	// 		}else{
	// 			redirect(base_url());
	// 		}
	// 	}
	// }
	
	function twitter(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.twitter.com/oauth/request_token?oauth_callback=localhost:3000',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
			'Authorization: OAuth oauth_consumer_key="NTKp4KHoxfjrjHJlWzydc4BsT",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1674541824",oauth_nonce="Hmr5MMpQR75",oauth_version="1.0",oauth_signature="df0OzJauTdevmka9RF7ynVYGwBE%3D"',
			'Cookie: guest_id=v1%3A167454178752763150; guest_id_ads=v1%3A167454178752763150; guest_id_marketing=v1%3A167454178752763150; personalization_id="v1_QlTBqAIaa3Y3DV5NH1Gojg=="'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

		echo '<pre>';
		print_r($response );die;
	}
	function createHarvesting(){
		$this->load->helper('media_stream');
		$r = CreateHarvesting($uid = 215);
		echo '<pre>';
		print_r($r);		
	}
	function creatsha256(){
		date_default_timezone_set('Asia/Kolkata');
		$now = time();
		$ten_minutes = $now + (20 * 60);
		$startDate = date('m-d-Y H:i:s', $now);
		$endDate = date('m-d-Y H:i:s', $ten_minutes);
		echo $timestamp =  strtotime($endDate);
		echo '<br>';
		echo $h =  "84661ddbe7f7f-1e70-4ed8-9b5c92ceff96-dbe0-46ae".$timestamp."08432460-b8c3-4f33-b2a6-7161d71f5989";
		echo '<br>';
		echo hash('sha256', $h);
	}
	function uploadbunny(){
		
		$params = array(
			'storageZoneName' 	=> 'discoverme' , 
			'apiAccessKey' 		=> '920855cf-3d3c-47ff-ba2551dcfd13-97ca-4ba6',
			'storageZoneRegion' => 'ny'
		);

		$this->load->library('bunnycdn_storage', $params);
		
		$r = $this->bunnycdn_storage->uploadFile(ABS_PATH.'repo/images/1heighlight_popup01.jpg','/discoverme/1heighlight_popup01.jpg');
		print_r($r);
	}
	function singleVideo(){
		$data['page_info'] = array('page'=>'single_video','title'=>'Single Video');
		$data['p_uid'] = 1;
		$this->load->view('home/inc/header',$data);
		$this->load->view('HTML/new_single_video',$data);
        $this->load->view('home/inc/footer',$data);
	}

	function getDomainOnly(){
		$this->load->helper('my');
		$r = get_domain_only(base_url());
		echo '<pre>';
		print_r($r);die;
	}

	public function deleteMedia(){
		$this->load->helper('media_stream');
		$r = 	deleteMediaInput(7438395);
		echo '<pre>';
		print_r($r);die;

	}
	public function deletemediachannel(){
		$r = $this->common->CallCurl('POST',['inputId' => '4355494'], base_url('cron/MediaLiveSns/deleteMediaInput'),[]);
		print_r($r);die;
	}

	function phpmail(){
		// $this->load->library('audition_functions');
		// echo $this->audition_functions->HtmlMailByMandrill('satiety-many0r@icloud.com','ad','mandrill','hello');
		// die;
		// $this->load->library('parser');
		$this->load->helper('aws_ses_action');
		
		$email = 'ajaydeep.parmar@pixelnx.com';
		$randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
		$subject = 'Welcome to '.PROJECT;
		
		$greeting = 'Thanks for creating an account with us';
		$action = 'Now you can login to '. PROJECT .' by using your personal email and password or you can login with your Facebook or Google accounts.';

		$r = send_smtp([
			'greeting'=>$greeting,
			'action'=>$action,
			'email'=>$email,
			'receiver_email'=>$email,
			'password'=>$randomstr,
			'button'=>NULL,
			'link'=>NULL,
			'subject'=>$subject,
		]);

		echo $r;
	
	}

	function SantizeString(){
		$checkData = $this->DatabaseModel->select_data('title,post_id,description','channel_post_video');
		$t = [];
		foreach($checkData as $k => $item){
			
			// $description = checkForeignChar($item['description']); 
			// $description 		= 	str_replace(' rsquo ', "'s", $item['description']);
			// $title 		= 	str_replace(' rsquo ', "'s", $item['title']);
			$description 		= 	str_replace('Paul \'sRondo','Paul\'s Rondo', $item['description']);
			$title 		= 	str_replace('Paul \'sRondo', 'Paul\'s Rondo', $item['title']);
			if(!empty($description)){
				$update = [	'description'	=> $description,'title' => $title ];
				$where 	= [	'post_id'	=>	$item['post_id'] ];
				print_r($update);
				print_r($where);
				echo '</br>';
				$this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
			}
		}	 
	}

	function getThumbStatus(){
		$this->load->helper('aws_s3_action');
				
			$where = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = 7 AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1 AND users.is_deleted = 0 AND channel_post_video.post_id NOT IN ('. implode(',',$_SESSION['post_id']) .')' ;
		
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id','left'),
								array('users' , 'users.user_id = channel_post_video.user_id','left'),
							)
						);
			
			$videoData =  $this->DatabaseModel->select_data('channel_post_thumb.image_name,channel_post_video.user_id,channel_post_video.user_id,,channel_post_video.post_id,,channel_post_video.title','channel_post_video',$where,'',$join);		
			$data = [];
			$i = 0;
		
			
			$this->recurs($i,$videoData);
		
	}
 
	function recurs($i,$videoData){
		// $r = DoesObjectExist('aud_'.$videoData[$i]['user_id'] . '/images/' . $videoData[$i]['image_name'],MAIN_BUCKET);
		$r = checkmeta('aud_'.$videoData[$i]['user_id'] . '/images/' . $videoData[$i]['image_name']);

		if(isset($r['statusCode']) && $r['statusCode'] != 200 ){ 
			echo $videoData[$i]['post_id'] . ' ' . $videoData[$i]['title'] . '</br>';
		}else{
			echo 'hai'. '</br>';
		}
		
		$this->recurs($i++,$videoData);
		
	}

	function getfreshstring(){
		$text = "Assassin's Creed";
		echo nl2br( checkForeignChar($text));
		die;
	}
	function checkForeignChar(){
		$this->config->load('foreign_chars');
		$f = $this->config->item('foreign_characters');
		
		$checkData = $this->DatabaseModel->select_data('title,post_id','channel_post_video',array('user_id '=> 2718 ));
		$t = [];
		
		foreach($checkData as $k => $item){
			$t[$k]['oldtitle'] 	= $item['title'];
			$newtitle 			= $t[$k]['newtitle'] =  preg_replace(array_keys($f), array_values($f), $item['title']);
			$t[$k]['UTF'] 		= iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $item['title']);
			$slugify 			= slugify($newtitle);

			$update 			= [	'title'	=> $newtitle , 'slug' => $slugify ];
			$where 				= [	'post_id'	=>	$item['post_id'] ];
			print_r($update);
			print_r($where);
			echo '</br>';
			// $this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
		}
	}
	function xandrauth(){
		$post = file_get_contents(ABS_PATH .'auth.txt');
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.appnexus.com/report?advertiser_id=6001369');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		// $post = array(
		// 	'file' => $auth
		// );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		$headers 	= array();
		$headers[] 	= 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		print_r($result);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	}
	function redirectolive(){
		redirect('https://discovered.tv/watch/Zaj5Zmtm');
	}
	function encodepostkey(){
		 $check = $this->share_url_encryption->share_single_page_link_creator('2|8921','encode','id');
		 print_r($check);
	}
	function sendlocalmail(){
		$this->audition_functions->send_emails('ajaydeep.parmar@pixelnx.com','Testing',$body = '<h1>My Name Is Ajaydeep<h1>');
	}
	function set(){

		$cookie= array(
		   'name'   => 'remember_me',
		   'value'  => json_encode([1,2,3]),                            
		   'expire' => '300',                                                                                   
		   'secure' => TRUE
		);

		$this->input->set_cookie($cookie);

		echo "Congratulation Cookie Set";

	}

	function get(){

	   print_r( json_decode($this->input->cookie('remember_me',true)));

	}

	function getMetaData(){
		 $check = $this->share_url_encryption->share_single_page_link_creator('2|8090','encode','id');
		 print_r($check);
		 die;
		$this->load->helper('aws_s3_action');
		// $tags = get_meta_tags('https://s3.amazonaws.com/discovered.tv.thumbs.new/05hgxhMo6C8ei4XaLD9d.mp4');
		registerStreamWrapper();
		// https://s3-cdn.discovered.tv/aud_2793/videos/I03RhXFgNwXMc1DWQldY.mp4
		$tags = file_get_contents('s3://discovered.tv/aud_2793/videos/I03RhXFgNwXMc1DWQldY.mp4');
		echo '<pre>';
		print_r( get_meta_tags($tags) );die;
		
		$r = s3headObject('aud_215/videos/05hgxhMo6C8ei4XaLD9d.mp4' );
		echo '<pre>';
		print_r($r );
	}
	
	function fb(){
	
		$app_id = "924880001742691";
		$app_secret = "0eff4bb36c1bb9b5ce4787c3ac7c7c9c";
		$my_url = base_url('test/fb');
		$video_title = "TITLE FOR THE VIDEO";
		$video_desc = "DESCRIPTION FOR THE VIDEO";
		$page_id = "106074121876363"; // Set this to your APP_ID for Applications

		$code = isset($_REQUEST["code"])?$_REQUEST["code"]:'';
		$error_code = isset($_REQUEST["error_code"])?$_REQUEST["error_code"]:'';

		echo '<html><body>';
		if(!empty($error_code)){
			
			print_r($_REQUEST['error_message']);
			
			print_r($error_code);
		}else
		if(empty($code)) {
		  // Get permission from the user to publish to their page. 
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
			. $app_id . "&redirect_uri=" . urlencode($my_url)
			. "&scope=pages_read_engagement,public_profile,email";
			// . "&scope=publish_stream,manage_pages,public_profile,email";
			echo('<script>top.location.href="' . $dialog_url . '";</script>');
		} else {

		  // Get access token for the user, so we can GET /me/accounts
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
			. $app_id . "&redirect_uri=" . urlencode($my_url)
			. "&client_secret=" . $app_secret
			. "&code=" . $code;
			$access_token = json_decode(file_get_contents($token_url),true);
			
			$accounts_url = "https://graph.facebook.com/v12.0/me/accounts?access_token=".$access_token['access_token'];
			$response = file_get_contents($accounts_url);
			
			// Parse the return value and get the array of accounts we have
			// access to. This is returned in the data[] array. 
			$response = json_decode($response,true);
			echo '<pre>';
			print_r($response);die;
			$accounts = $response['data'];
		
		  // Find the access token for the page to which we want to post the video.
			if(!empty($accounts)){
				foreach($accounts as $account) {
					
					if($account['id'] == $page_id) {
						
						$access_token = $account['access_token'];
						break;
					}
				}

				// Using the page access token from above, create the POST action
				// that our form will use to upload the video.
				$post_url = "https://graph-video.facebook.com/" . $page_id . "/videos?"
				. "title=" . $video_title. "&description=" . $video_desc
				. "&access_token=". $access_token;

				// Create a simple form 
				echo '<form enctype="multipart/form-data" action=" '.$post_url.' "  
				method="POST">';
				echo 'Please choose a file:';
				echo '<input name="file" type="file">';
				echo '<input type="submit" value="Upload" />';
				echo '</form>';


				
			}else{
				echo 'empty account';
			}
			
		}
		echo '</body></html>';
	}
	function testcurl(){
		$curl = curl_init();

			curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://zupport.in/api/home/testApi',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		echo '<pre>';
		
		print_r($err);

	}
	function GetStreamKey(){
		$this->load->helper('aws_ivs_action');
		// $GetStreamKey = getStreamInfo("arn:aws:ivs:us-east-1:201068771454:channel/CrIvJLId96tO");	/*Live*/
		$GetStreamKey = getStreamInfo("arn:aws:ivs:us-east-1:201068771454:channel/mURWlMSTpmk3");	/*test*/
		echo '<pre>';
		print_r($GetStreamKey);
	}
	function updateIvaVideoType(){
		$checkData = $this->DatabaseModel->select_data('post_id,user_id,iva_id','channel_post_video',array('iva_id != '=> '' ));
		foreach($checkData as $item){
			$update = [	'video_type'	=>	1 ];
			$where = [	'post_id'	=>	$item['post_id'] ];
			$this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
			
		}	
	}
	function send_notifi(){
		$this->audition_functions->sendNotiOnLiveStreaming(215,2398,'Live',3 );
	}
	function downloadFile(){
		$data = file_get_contents('https://www.dropbox.com/s/iu8s7gz1lildmsw/page-not-found.png?raw=1');
		$path = user_abs_path(215,'videos/');
		file_put_contents($path.'m3u8tomp4.jpg' , $data);
		print_r($data );die;
	}
	function m3u8tomp4(){
		$this->load->helper('aws_s3_action');
		registerStreamWrapper();
		$data = file_get_contents('s3://discovered.tv.transcoder/aud_215/videos/zJ6EaZqWFaI4kCr25svq/zJ6EaZqWFaI4kCr25svq_Ott_Hls_Ts_Avc_Aac_16x9_1280x720p_30Hz_6500Kbps_00001.ts');
		
		$path = user_abs_path(215,'videos/');
		
		// file_put_contents($path.'m3u8tomp4.mp4' , $data);
		
		$ffmpeg = 'ffmpeg -i https://s3-us-west-1.amazonaws.com/discovered.tv.transcoder/aud_215/videos/zJ6EaZqWFaI4kCr25svq/zJ6EaZqWFaI4kCr25svq.m3u8 -bsf:a aac_adtstoasc -vcodec copy -c copy -crf 50 '. $path.'m3u8tomp41.mp4';
		$output = exec( $ffmpeg ,$responce );
		print_r($output);
	}
	function copyObject(){
		$this->load->helper('aws_s3_action');
		$sourceKeyname = 'ivs/v1/201068771454/VkyY8nKlIsfK/2021/5/1/12/29/uxNBbmdB3RYb/media/hls/480p/';
		// $sourceKeyname = 'ivs\/v1\/201068771454\/VkyY8nKlIsfK\/2021\/5\/1\/12\/29\/uxNBbmdB3RYb\/media\/hls\/480p/';
		$targetKeyname = 'aud_215/videos/';
		$t = 'ajaydeep';
		
		copyObject($sourceKeyname,$targetKeyname , $t, $t.'.m3u8','playlist.m3u8');
		copyinbatch($sourceKeyname,$targetKeyname ,'ajaydeep');
		
		
	}
	function callscurl(){
		$r = $this->common->CallCurl('POST' ,json_encode(array('arn'=>'arn:aws:ivs:us-east-1:201068771454:stream-key/ouN602ON5i5V','channelArn'=>'arn:aws:ivs:us-east-1:201068771454:channel/BjB0Ly0IkIii') ), 'https://test.discovered.tv/test/resetStream',array('Content-Type:application/json'));
		print_r($r);
	}
	function resetStream(){
		$record = json_decode(  file_get_contents('php://input') , true );
		$arn = stripcslashes($record['arn']);
		$channelArn = stripcslashes($record['channelArn']);
		
		$this->load->helper('aws_ivs_action');
		$r = resetStream($arn,$channelArn);
		echo json_encode($r); 
	}
	
	function downloadImgS3(){
		$this->load->helper('aws_s3_action');
		$keyname = 'ivs/v1/201068771454/DFmGAOsfUiVw/2021/5/5/5/22/GADZ3LU5HH4M/media/thumbnails/thumb0.jpg';
		$dobe  = DoesObjectExist($keyname,$bucket ='discovered-ivs-stream','us-east-1');
		if($dobe == 1){
			$k = putObjectAcl($keyname, 'discovered-ivs-stream','us-east-1');
			
			$name = rand().'.jpeg';
			$pathToImages = user_abs_path(215,'images').$name;
			$s3path = 'https://discovered-ivs-stream.s3.amazonaws.com/ivs/v1/201068771454/DFmGAOsfUiVw/2021/5/5/5/22/GADZ3LU5HH4M/media/thumbnails/thumb0.jpg';
			
			if(file_put_contents($pathToImages, file_get_contents($s3path))){
				$this->load->library('audition_functions');
				$this->audition_functions->resizeImage('315','217',$pathToImages,'',$maintain_ratio = false,$create_thumb= TRUE);
			};
		}
	}
	
	function stremingSns(){
		$record = json_decode(  file_get_contents('php://input') , true );
		
		if(!empty($record)){
			$resources = implode('', $record['resources']);
			
			$arn=$this->DatabaseModel->select_data('*','users_ivs_info',['channel_arn'=> stripslashes($resources)]);	
				
			if(!empty($arn)){
				
				$data = [
						 'channel_arn' 	=> 	$resources,
						 'user_id' 		=> 	$arn[0]['user_id'],
						 'message'		=>	json_encode( $record  ),
						 'status'		=> 	$record['detail']['recording_status'],
						];
				
				$this->DatabaseModel->access_database('aws_sns_ivs','insert',$data);
				
				if($record['detail']['recording_status'] == 'Recording End'){
					$recorded_path 	= stripslashes($record['detail']['recording_s3_key_prefix']);
					$stream_id 		= $record['detail']['stream_id'];
					
					$sourceKeyname 	= $recorded_path.'/media/hls/master.m3u8';
					$targetKeyname 	= 'aud_'.$data['user_id'].'/videos/';
					
					$stream_url 	= 'https://discovered-ivs-stream.s3.amazonaws.com/';
					
					$this->load->helper('aws_s3_action');
					$recorded_duration_s 	= $record['detail']['recording_duration_ms'] / 1000;
					
					$name		=	'';
					$keyname 	= 	$recorded_path.'/media/thumbnails/thumb0.jpg';
					$dobe  		= 	DoesObjectExist($sourceKeyname,$bucket ='discovered-ivs-stream','us-east-1');
					
					if($dobe == 1){
						// $k 				= putObjectAcl($sourceKeyname, 'discovered-ivs-stream','us-east-1');
						// $k 				= putObjectAcl($keyname, 'discovered-ivs-stream','us-east-1');
						$name 			= rand().'.jpeg';
						$pathToImages 	= user_abs_path($data['user_id'],'images').$name;
						$s3path 		= $stream_url.$keyname;
						
						if(file_put_contents($pathToImages, file_get_contents($s3path))){
							$this->load->library('audition_functions');
							$this->audition_functions->resizeImage('315','217',$pathToImages,'',FALSE,TRUE);
							upload_all_images($data['user_id']);
						};
					}
						
					// s3_delete_matching_object($recorded_path.'/media/thumbnails','discovered-ivs-stream','us-east-1');
						
					$ivs_info					=	json_decode($arn[0]['ivs_info'],true);
					$streamKeyArn 				=	stripslashes($ivs_info['streamKey']['arn']);
						
					$r = $this->common->CallCurl('POST' ,json_encode(array('arn'=>$streamKeyArn,'channelArn'=>$data['channel_arn'])), base_url('test/resetStream'),array('Content-Type:application/json'));
					
					$ivs_info['streamKey'] 		= 	json_decode($r['data']) ;
						
					$where  = ['user_id'		=>	$data['user_id']];
					
					$update = [	'ivs_info'		=>	json_encode($ivs_info),
								'is_live' 		=> 	0 , 
								'live_pid' 		=> 	'' , 
								'schedule_time'	=> 	'',
								'is_scheduled'	=> 	0
								];
					$this->DatabaseModel->access_database('users_ivs_info','update',$update,$where);
					
					$where['is_stream_live'] = 1;
								  
					$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
					
					if(isset($post[0]['post_id'])){
						$post_id = $post[0]['post_id'];
						
						$thumb_array = array(
								'post_id' 		=> $post_id ,
								'user_id' 		=> $data['user_id'],
								'image_name' 	=> $name,
								'active_thumb' 	=> 0,
							); 
						$this->DatabaseModel->access_database('channel_post_thumb','insert',$thumb_array);
						
						$update = 
								[ 'uploaded_video' 	=> 	$stream_url.$sourceKeyname,
								  'is_stream_live'	=>	0,
								  'video_duration'	=>	$recorded_duration_s
								];
						
						$this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
						echo $post_id;
					}
				
				}else{
					
					$user_id = $arn[0]['user_id'];
					$where  = ['user_id'	=>	$user_id , 'is_stream_live' => 1];
					$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
					if(isset($post[0]['post_id'])){
						$post_id = $post[0]['post_id'];
						$this->audition_functions->sendNotiOnLiveStreaming($user_id  ,$post_id,$post[0]['title'],$status = 3);
					}
				}
			}
		}
		echo 1;
	}
	function stremingSnsOld(){
		$record = json_decode(  file_get_contents('php://input') , true );
		
		if(!empty($record)){
			$resources = implode('', $record['resources']);
			
			$arn=$this->DatabaseModel->select_data('*','users_ivs_info',['channel_arn'=> stripslashes($resources)]);	
				
			if(!empty($arn)){
				
				$data = [
						 'channel_arn' 	=> 	$resources,
						 'user_id' 		=> 	$arn[0]['user_id'],
						 'message'		=>	json_encode( $record  ),
						 'status'		=> 	$record['detail']['recording_status'],
						];
				
				$this->DatabaseModel->access_database('aws_sns_ivs','insert',$data);
				
				if($record['detail']['recording_status'] == 'Recording End'){
					$recorded_path 	= stripslashes($record['detail']['recording_s3_key_prefix']);
					
					$sourceKeyname 	= $recorded_path.'/media/hls/480p/';
					
					$targetKeyname 	= 'aud_'.$data['user_id'].'/videos/';
					
					$stream_id 		= $record['detail']['stream_id'];
					
					$this->load->helper('aws_s3_action');
					$dobe  = DoesObjectExist($sourceKeyname.'playlist.m3u8',$bucket ='discovered-ivs-stream','us-east-1');
					
					$f = '480p';
					if($dobe == 0){
						$f = '480p60';
						$sourceKeyname 	= $recorded_path.'/media/hls/480p60/';
					}
					 
					$CO = copyObject($recorded_path.'/media/hls/' ,$targetKeyname , $stream_id, $stream_id.'.m3u8','master.m3u8');
					$CO = copyObject($sourceKeyname,$targetKeyname , $stream_id .'/'.$f, 'playlist.m3u8','playlist.m3u8');
					
					if($CO['status'] == 1){
						$recorded_duration_s = $record['detail']['recording_duration_ms'] / 1000;
						$file_count = round( $recorded_duration_s/12) + 5 ;
						copyinbatch($sourceKeyname,$targetKeyname , $stream_id .'/'.$f , $file_count);
						
						
						$name='';
						$keyname = $recorded_path.'/media/thumbnails/thumb0.jpg';
						$dobe  = DoesObjectExist($keyname,$bucket ='discovered-ivs-stream','us-east-1');
						if($dobe == 1){
							$k 				= putObjectAcl($keyname, 'discovered-ivs-stream','us-east-1');
							$name 			= rand().'.jpeg';
							$pathToImages 	= user_abs_path($data['user_id'],'images').$name;
							$s3path 		= 'https://discovered-ivs-stream.s3.amazonaws.com/'.$keyname;
							
							if(file_put_contents($pathToImages, file_get_contents($s3path))){
								$this->load->library('audition_functions');
								$this->audition_functions->resizeImage('315','217',$pathToImages,'',FALSE,TRUE);
								upload_all_images($data['user_id']);
							};
						}
						
						s3_delete_matching_object($recorded_path,'discovered-ivs-stream','us-east-1');
						
						$ivs_info				=	json_decode($arn[0]['ivs_info'],true);
						$streamKeyArn 			=	stripslashes($ivs_info['streamKey']['arn']);
						
						$r = $this->common->CallCurl('POST' ,json_encode(array('arn'=>$streamKeyArn,'channelArn'=>$data['channel_arn']) ), 'https://test.discovered.tv/test/resetStream',array('Content-Type:application/json'));
					
						$ivs_info['streamKey'] 	= 	json_decode($r['data']) ;
							
						$where  = ['user_id'	=>	$data['user_id']];
						$update = [	'ivs_info'		=>	json_encode($ivs_info),
									'is_live' 		=> 	0 , 
									'live_pid' 		=> 	'' , 
									'schedule_time'	=> 	'',
									'is_scheduled'	=> 	0
									];
						$this->DatabaseModel->access_database('users_ivs_info','update',$update,$where);
						
						$where['is_stream_live'] = 1;
								  
						$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
						if(isset($post[0]['post_id'])){
							$post_id = $post[0]['post_id'];
							
							$thumb_array = array(
									'post_id' 		=> $post_id ,
									'user_id' 		=> $data['user_id'],
									'image_name' 	=> $name,
									'active_thumb' 	=> 0,
								); 
							$this->DatabaseModel->access_database('channel_post_thumb','insert',$thumb_array);
							
							$update = [	'uploaded_video' => $targetKeyname.$stream_id.'.mp4','is_stream_live'=>0];
							
							$this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
							
							// $this->audition_functions->sendNotiOnLiveStreaming($data['user_id'],$post_id,$post[0]['title'],$status = 3);
							
							echo $post_id;
						}
						
					}else{
						echo $CO['message'];
					}
					
				}else{
					
					$user_id = $arn[0]['user_id'];
					$where  = ['user_id'	=>	$user_id , 'is_stream_live' => 1];
					$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
					if(isset($post[0]['post_id'])){
						$post_id = $post[0]['post_id'];
						$this->audition_functions->sendNotiOnLiveStreaming($user_id  ,$post_id,$post[0]['title'],$status = 3);
					}
				}
			}
		}
		echo 1;
	}
	
	function putMetadata(){
		$this->load->helper('aws_ivs_action');
		$r = putMetadata($channelArn="arn:aws:ivs:us-east-1:201068771454:channel/VkyY8nKlIsfK",$metadata="This is my matadata");
		echo '<pre>';
		print_r($r);
	}
	function createChannel(){
		$this->load->helper('aws_ivs_action');
		
		$data = [];
		$uid = 215;
		
		$optionArr = array(
			'latencyMode' 	=> 'LOW',
			'name' 			=> 'I-am-goint-to-live-now',
			'type' 			=> 'BASIC',
		);
		
		$r = createChannel($optionArr); 
		if($r['status'] == 1){
			$data['channel'] = $r['data']['channel'];
			$data['streamKey'] = $r['data']['streamKey'];
			
			$channel_array = array(	
				'video_type'		=> 	4,
				'uploaded_video'	=> 	$data['channel']['playbackUrl'],
				'user_id' 			=>	$uid,
				'created_at'		=>	date('Y-m-d H:i:s'),
				'complete_status'	=>	1,
				'active_status'		=>	1,
				'privacy_status'	=>	7,
				'is_video_processed'=>	1,
				'description'		=>	'',
				'mode'				=>	1,
				'genre'				=>	1,
				'sub_genre'			=>	0,
				'category'			=>	90,
				'language'			=>	'',
				'age_restr'			=>	'15+',
				'title'				=>	'I am Going To Live Now',
				'slug'				=>	'',
				'aws_ivs_detail'	=> json_encode($data) ,
				'is_stream_live'	=> 	1
			);
			
			$this->DatabaseModel->access_database('users_ivs_info','update',['channel_arn'=>$data['channel']['arn'],'ivs_info'=>json_encode($data)],['user_id'=>$uid]);
			
			
			$pubId = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
			  
			$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$pubId,'encode','id');
			
			$this->DatabaseModel->access_database('channel_post_video','update',['post_key'=>$check[0]],['post_id'=>$pubId]);
			
			$this->query_builder->changeVideoCount($uid,'increase'); 
			
			$Thumb = ['post_id'=> trim($pubId),'image_name'=>'','user_id'=>$uid,'active_thumb'=>1];
			
			$this->DatabaseModel->access_database('channel_post_thumb','insert',$Thumb);
			
			
			
			echo '<pre>';
			print_r(json_encode($data));
		}
	}
	function testajay(){
		
		$this->load->library('httpsocket');

		$sock = $this->httpsocket;
		
		$server_ip="104.255.220.32"; //IP that User is assigned to
		$server_login="admin";
		$server_pass="qCIOIKO^9g1eQSnkBt";
		$server_host="104.255.220.32"; //where the API connects to
		$server_ssl="N";
		$server_port="2222";

		// if (isset($_POST['action']) && $_POST['action'] == "add")
		// {

			$username='ajayd';
			$domain='ajay.com';
			$email='ajaydeep.parmar@pixelnx.com';
			$pass=123;
			$package='Basic_1G';


			// echo "Creating user $username on $server_ip.... <br>\n";
		 
			// $sock = new HTTPSocket;
			if ($server_ssl == 'Y')
			{
				$sock->connect("ssl://".$server_host, $server_port);
			}
			else
			{ 
				$sock->connect($server_host, $server_port);
			}
		 
			$sock->set_login($server_login,$server_pass);
		 
			$sock->query('/CMD_API_ACCOUNT_USER',
				array(
					'action' => 'create',
					'add' => 'Submit',
					'username' => $username,
					'email' => $email,
					'passwd' => $pass,
					'passwd2' => $pass,
					'domain' => $domain,
					'package' => $package,
					'ip' => $server_ip,
					'notify' => 'yes'
				));
		 
			$result = $sock->fetch_parsed_body();
			echo '<pre>';
			print_r($result);die;
			if ($result['error'] != "0")
			{
				echo "<b>Error Creating user $username on server $server_ip:<br>\n";
				echo $result['text']."<br>\n";
				echo $result['details']."<br></b>\n";
			}
			else
			{
				echo "User $username created on server $server_ip<br>\n";
			}

			exit(0);
		// }

		echo "Will connect to: ".($server_ssl == "Y" ? "https" : "http")."://".$server_host.":".$server_port."<br>\n";


	}
	
	public function createInvalidation(){
		$this->load->helper('aws_s3_action');
		$r = createCasheInvalidatin(['/aud_215/videos/8US6YPJxekVsOP4hUwew/8US6YPJxekVsOP4hUwew.mp4']);
		echo '<pre>';
		print_r($r['data']['Invalidation']['Status']);
	}
	public function twillio(){
		$this->load->helper('twillio');
		// twillio_msg();
		nexmo_msg();
	}
	public function getRefershToken(){
		$this->load->helper('aws_s3_action');
		MCendpoint();
	}
	public function analytics(){
		$this->load->library('session');
		$this->session->sess_destroy();

		echo '<pre>';
		print_r();die;
		$data= [];
		$this->load->view('test/analytics2',$data);
	}
	public function profanity(){
		$this->load->view('admin/channel/profanity_notice_template');
	
	}
	public function blogs($page = null){
		$data['page_info'] = array('page'=>'blogs','title'=>'Articles');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/blogs/'.$page,$data);
        $this->load->view('home/inc/footer',$data);
	
	}
	public function shop($page = null){
		$data['page_info'] = array('page'=>'shop','title'=>'My playlist');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/shop/'.$page,$data);
        $this->load->view('home/inc/footer',$data);
	
	}

	public function allusers($page = 'allusers'){
		$data['page_info'] = array('page'=>'allusers','title'=>'My users');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/'.$page,$data);
        $this->load->view('home/inc/footer',$data);
	
	}
	
	public function youtubeClient($outh=false){
		require APPPATH .'third_party/analytics/analytic/vendor/autoload.php';
		$config =  APPPATH .'third_party/analytics/client_secret_youtube.json';
		try {
			$client = new Google_Client();
			$client->setAuthConfig($config);
			$client->addScope([
				'https://www.googleapis.com/auth/userinfo.profile',
				'https://www.googleapis.com/auth/userinfo.email',
				'https://www.googleapis.com/auth/youtube',
				'https://www.googleapis.com/auth/yt-analytics-monetary.readonly',
				'https://www.googleapis.com/auth/youtube.force-ssl'
			]);
			$client->setAccessType('offline');
			$client->setIncludeGrantedScopes(true);
			
			if($outh){
				$client->setApprovalPrompt("force");
			}else{
				$client->setApprovalPrompt("consent");
				$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/getRefreshToken');
			}
			return $client;
		}catch (Google\Service\Exception $e) {
			return  (object) array( 'error' => $e->getMessage() );
		}
	}
	
	function checkAccessToken($client,$channel_id=NULL){
		if(!empty($channel_id)){
			$token= $this->DatabaseModel->select_data('youtube_token','youtube_channel_list',array('channel_id'=>$channel_id));
			if(isset($token[0]['youtube_token']) && !empty($token[0]['youtube_token'])){
				$youtube_token 				= json_decode($token[0]['youtube_token'],true);
				$_SESSION['access_token']  	= $youtube_token['access_token'];
				$_SESSION['refresh_token'] 	= $youtube_token['refresh_token'];
			}else{
				return (object) array('status'=>0,'error'=>'Token not available in database.');
			}
		}
		
		if ($client->isAccessTokenExpired()) {
			$refresh_token = isset($_SESSION['refresh_token'])? $_SESSION['refresh_token'] : '';
			if(isset($refresh_token) && !empty($refresh_token)){
				$_SESSION['access_token'] = $client->fetchAccessTokenWithRefreshToken($refresh_token);
				$client->setAccessToken($_SESSION['access_token']);
				return $client;
			}else{
				return (object) array('status'=>0,'error'=>'Token not available in database.');
			}
		}else if(isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			 $client->setAccessToken($_SESSION['access_token']);
			 return $client;
		}
	}
	
	function getRefreshToken(){
		$client = $this->youtubeClient(true);
		
		if(!isset($client->error)){
			if (!isset($_GET['code'])) {
				$auth_url = $client->createAuthUrl();
				redirect($auth_url);
			}else 
			{
				$client->authenticate($_GET['code']);

				$_SESSION['refresh_token'] = $client->getRefreshToken();
				$_SESSION['access_token'] = $client->getAccessToken();
				
				$redirect_uri = base_url('test/getChannel');
				redirect($redirect_uri);
			}
		}else{
			$redirect_uri = base_url('test/channel_page');
			redirect($redirect_uri.'?error='.$client->error);
		}
	}
	function getChannel(){
		// print_r($_SESSION);die;
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client);
			
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				try{
					$channelsResponse = $youtube->channels->listChannels('snippet,statistics,status,id', array('mine' => 'true'));
					
					if(isset($channelsResponse['items'][0]['id'])){
						$channel_id 		=  	$channelsResponse['items'][0]['id'];
						$title 				=  	$channelsResponse['items'][0]['snippet']['title'];
						$thumbnails 		=  	$channelsResponse['items'][0]['snippet']['thumbnails']['high']['url'];
						$publishedAt 		=  	$channelsResponse['items'][0]['snippet']['publishedAt'];
						$privacyStatus 		=  	$channelsResponse['items'][0]['status']['privacyStatus'];
						$subscriberCount 	=  	$channelsResponse['items'][0]['statistics']['subscriberCount'];
						$videoCount 		=  	$channelsResponse['items'][0]['statistics']['videoCount'];
						$viewCount 			=  	$channelsResponse['items'][0]['statistics']['viewCount'];
						$uid 				= 	is_login();
						
						$checkData = $this->DatabaseModel->select_data('*','youtube_channel_list',array('user_id'=>$uid,'channel_id'=>$channel_id));
						
						if(empty($checkData)){
							$insert = [	'user_id' 		=> $uid,
										'channel_id' 	=> $channel_id,
										'title' 		=> $title,
										'thumbnails' 	=> $thumbnails,
										'privacyStatus' => $privacyStatus,
										'subscriberCount' => $subscriberCount,
										'videoCount' 	=> $videoCount,
										'viewCount' 	=> $viewCount,
										'publish_at' 	=> date('Y-m-d H:i:s',strtotime($publishedAt)),
										'youtube_token'	=> json_encode(['refresh_token'=>$_SESSION['refresh_token'],'access_token'=>$_SESSION['access_token']]),
										];
							$result = $this->DatabaseModel->access_database('youtube_channel_list','insert',$insert);
								
						}else{
							$where  = [	'user_id' 		=> $uid,
										'channel_id' 	=> $channel_id];
										
							$update = [	'title' 		=> $title,
										'thumbnails' 	=> $thumbnails,
										'privacyStatus' => $privacyStatus,
										'subscriberCount'=> $subscriberCount,
										'videoCount' 	=> $videoCount,
										'viewCount' 	=> $viewCount,
										'youtube_token'	=> json_encode(['refresh_token'=>$_SESSION['refresh_token'],'access_token'=>$_SESSION['access_token']]),
									];
							$result = $this->DatabaseModel->access_database('youtube_channel_list','update',$update,$where);
						}
						if($result)
							$this->getVideosByChannel($channel_id);
							// $this->getPlaylist($channel_id); 
					}
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}

		}else{
			$redirect_uri = base_url('test/my_channels');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	function getPlaylist($channel_id){
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id);
			
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				try{
					$queryParams = [
						'channelId' => 	$channel_id,
						'maxResults'=>	50,
					];
					$PlaylistResponse = $youtube->playlists->listPlaylists('contentDetails,id,localizations,player,snippet,status', $queryParams);
					
					if(isset($PlaylistResponse['items'])){
						
						foreach($PlaylistResponse['items'] as $playlist){
							
							$playlist_id 		=  	$playlist['id'];
							$title 				=  	$playlist['snippet']['title'];
							$thumbnails 		=  	$playlist['snippet']['thumbnails']['high']['url'];
							$publishedAt 		=  	$playlist['snippet']['publishedAt'];
							$privacyStatus 		=  	$playlist['status']['privacyStatus'];
							$uid 				= 	is_login();
							
							$checkData = $this->DatabaseModel->select_data('*','youtube_playlist_list',array('user_id'=>$uid,'playlist_id'=>$playlist_id));
							
							if(empty($checkData)){
								$insert = [	'user_id' 		=> $uid,
											'channel_id' 	=> $channel_id,
											'playlist_id' 	=> $playlist_id,
											'title' 		=> $title,
											'thumbnails' 	=> $thumbnails,
											'privacyStatus' => $privacyStatus,
											'publish_at' 	=> date('Y-m-d H:i:s',strtotime($publishedAt)) ];
								$result = $this->DatabaseModel->access_database('youtube_playlist_list','insert',$insert);
									
							}else{
								$where  = [	'user_id' 		=> $uid,
											'playlist_id' 	=> $playlist_id];
											
								$update = [	'title' 		=> $title,
											'thumbnails' 	=> $thumbnails,
											'privacyStatus' => $privacyStatus];
								$result = $this->DatabaseModel->access_database('youtube_playlist_list','update',$update,$where);
							}
							if($result)
								$this->getPlaylistItem($channel_id,$playlist_id);
						}
						
					}
				
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}

		}else{
			$redirect_uri = base_url('test/my_channels');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	function getPlaylistItem($channel_id,$playlist_id){
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id);
			 
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				$video_items = [];
				$nextPageToken='';
				try{
					do {
						
						$queryParams = [
							'maxResults' => 50,
							'playlistId' => $playlist_id,
							'pageToken' => $nextPageToken
						];

						$listItemResponse = $youtube->playlistItems->listPlaylistItems('contentDetails,status', $queryParams);
						
						$nextPageToken = isset($listItemResponse['nextPageToken'])? $listItemResponse['nextPageToken']: '';
						
						if(isset($listItemResponse['items'])){
							
							foreach($listItemResponse['items'] as $item){
								// if($item['status']['privacyStatus'] == 'public')
								$video_items[] =  $item['contentDetails']['videoId'];
							}
						}
					}while($nextPageToken != '');
					
					$where  = [	'user_id' => is_login(),'channel_id' => $channel_id,'playlist_id' => $playlist_id];
										
					$update = [	'video_items' => implode(',',$video_items) ];
					
					$result = $this->DatabaseModel->access_database('youtube_playlist_list','update',$update,$where);
				
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}
		}else{
			$redirect_uri = base_url('test/playlist_page');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	function getVideosByChannel($channel_id){
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id);
			 
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				$video_items = [];
				$nextPageToken='';
				
				try{
					do {
						
						$queryParams = [
							'maxResults' => 50,
							'channelId' => $channel_id,
							'type' => 'video',
							'pageToken' => $nextPageToken,
						];
						
						$searchResponse = $youtube->search->listSearch('snippet', $queryParams);
						// echo '<pre>';
					// print_r($searchResponse);die;
						$nextPageToken = isset($searchResponse['nextPageToken'])? $searchResponse['nextPageToken']: '';
						
						if(isset($searchResponse['items'])){
							
							foreach($searchResponse['items'] as $item){
								// if($item['status']['privacyStatus'] == 'public')
								$video_items[] =  $item['id']['videoId'];
							}
						}
					}while($nextPageToken != '');
					
					$where  = [	'user_id' => is_login(),'channel_id' => $channel_id];
										
					$update = [	'video_items' => implode(',',$video_items) ];
					
					$result = $this->DatabaseModel->access_database('youtube_channel_list','update',$update,$where);
				
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
				
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}
		}else{
			$redirect_uri = base_url('test/playlist_page');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	public function youtube_upload() {
		$htmlBody='NA';
		$video="linkedin.mp4";
		$title="tvn rahul youtube api v3";
		$desc="tvn rahul youtube api v3 for php";
		$tags=["rahultvn","youtubeapi3"];
		$privacy_status="public";
		
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id = 'UCSJbZ4op_Yy_qgjy0JhHMLw');

		// Define an object that will be used to make all API requests.
			$youtube = new Google_Service_YouTube($client);
			
			try{
			// REPLACE this value with the path to the file you are uploading.
			
			$videoPath = ABS_PATH .'uploads/admin/video/783cfc5fd3727425dda63a35c7c48f77.mp4';
			//$videoPath = "videos/linkedin.mp4";

			// Create a snippet with title, description, tags and category ID
			// Create an asset resource and set its snippet metadata and type.
			// This example sets the video's title, description, keyword tags, and
			// video category.
			$snippet = new Google_Service_YouTube_VideoSnippet();
			$snippet->setTitle($title);
			$snippet->setDescription($desc);
			$snippet->setTags($tags);

			// Numeric video category. See
			// https://developers.google.com/youtube/v3/docs/videoCategories/list 
			$snippet->setCategoryId("22");

			// Set the video's status to "public". Valid statuses are "public",
			// "private" and "unlisted".
			$status = new Google_Service_YouTube_VideoStatus();
			$status->privacyStatus = $privacy_status;

			// Associate the snippet and status objects with a new video resource.
			$video = new Google_Service_YouTube_Video();
			$video->setSnippet($snippet);
			$video->setStatus($status);

			// Specify the size of each chunk of data, in bytes. Set a higher value for
			// reliable connection as fewer chunks lead to faster uploads. Set a lower
			// value for better recovery on less reliable connections.
			$chunkSizeBytes = 1 * 1024 * 1024;

			// Setting the defer flag to true tells the client to return a request which can be called
			// with ->execute(); instead of making the API call immediately.
			$client->setDefer(true);

			// Create a request for the API's videos.insert method to create and upload the video.
			$insertRequest = $youtube->videos->insert("status,snippet", $video);

			// Create a MediaFileUpload object for resumable uploads.
			$media = new Google_Http_MediaFileUpload(
			$client,
			$insertRequest,
			'video/*',
			null,
			true,
			$chunkSizeBytes
			);
			$media->setFileSize(filesize($videoPath));

			// Read the media file and upload it chunk by chunk.
			$status = false;
			$handle = fopen($videoPath, "rb");
			while (!$status && !feof($handle)) {
				$chunk = fread($handle, $chunkSizeBytes);
				$status = $media->nextChunk($chunk);
			}

			fclose($handle);

			// If you want to make other calls after the file upload, set setDefer back to false
			$client->setDefer(false);


			$htmlBody .= "<h3>Video Uploaded</h3><ul>";
			$htmlBody .= sprintf('<li>%s (%s)</li>',
			$status['snippet']['title'],
			$status['id']);

			$htmlBody .= '</ul>';
			$result['id']=$status['id'];
			$result['title']=$status['snippet']['title'];

			}catch (Google_Service_Exception $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}

			 
			
			$result['message']=$htmlBody;
			echo '<pre>';
			print_r($result); 
		}
	}
	
	
	
	
	
	public function gaouth(){
		
		require APPPATH .'third_party/analytics/analytic/vendor/autoload.php';
		$config =  APPPATH .'third_party/analytics/client_secret_314045516986-2f8ngc90jk9a02j2vgpla64a03vjjngu.apps.googleusercontent.com.json';
		
		
		// Create the client object and set the authorization configuration
		// from the client_secretes.json you downloaded from the developer console.
		try {
			$client = new Google_Client();
			$client->setAuthConfig($config);
			$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
			$client->setAccessType('offline');
			$client->setApprovalPrompt('force');
		
		// If the user has already authorized this app then get an access token
		// else redirect to ask the user to authorize access to Google Analytics.
		// unset($_SESSION['access_token']);die; 
		
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  // Set the access token on the client.
		  $client->setAccessToken($_SESSION['access_token']);
		  // Create an authorized analytics service object.
		  $analytics = new Google_Service_Analytics($client);
		  // Get the first view (profile) id for the authorized user.
		  $profile = $this->getFirstProfileId($analytics);

		  // Get the results from the Core Reporting API and print the results.
		  $results = $this->getResults($analytics, $profile);
		
		  $this->printResults($results);
		}
		else 
		{
			if ($client->isAccessTokenExpired()) {
				
				$refresh_token = '1//0d3i8MHqVQ9SXCgYIARAAGA0SNwF-L9Ir6I3qsp4hlD0xuE3mM2DZu3fri5airmX0_aLTbPjgrIp9ymZUQxyQhTaWkreYq7S3RcE';
				// if(isset($_SESSION['refresh_token']) && $_SESSION['access_token'] ){
				if($refresh_token){
					// $_SESSION['access_token'] = $client->fetchAccessTokenWithRefreshToken($_SESSION['refresh_token']);
					$_SESSION['access_token'] = $client->fetchAccessTokenWithRefreshToken('1//0d3i8MHqVQ9SXCgYIARAAGA0SNwF-L9Ir6I3qsp4hlD0xuE3mM2DZu3fri5airmX0_aLTbPjgrIp9ymZUQxyQhTaWkreYq7S3RcE');
					redirect(base_url('test/gaouth'));
				}else{
					$redirect_uri = base_url('test/oauth2callback');
					header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
				}
				
				
			}
		}
		
		}catch (Google\Service\Exception $e) {
			print_r( array( 'error' => $e->getMessage() ) );
		}
		
	}
	
	function getFirstProfileId($analytics) {
	  // Get the user's first view (profile) ID.

	  // Get the list of accounts for the authorized user.
	  $accounts = $analytics->management_accounts->listManagementAccounts();
		
	  if (count($accounts->getItems()) > 0) {
		$items = $accounts->getItems();
		
		$firstAccountId = $items[1]->getId(); /* For Discovered Account*/

		// Get the list of properties for the authorized user.
		$properties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

		if (count($properties->getItems()) > 0) {
		  $items = $properties->getItems();
		  $firstPropertyId = $items[0]->getId();

		  // Get the list of views (profiles) for the authorized user.
		  $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstPropertyId);
// echo '<pre>';
		// print_r($profiles);die;
		  if (count($profiles->getItems()) > 0) {
			$items = $profiles->getItems();

			// Return the first view (profile) ID.
			return $items[0]->getId();

		  } else {
			throw new Exception('No views (profiles) found for this user.');
		  }
		} else {
		  throw new Exception('No properties found for this user.');
		}
	  } else {
		throw new Exception('No accounts found for this user.');
	  }
	}

	function getResults($analytics, $profileId) {
	  // Calls the Core Reporting API and queries for the number of sessions
	  // for the last seven days.
	  // $segmentlist = $this->segmentlist($analytics);
	  // echo '<pre>';
	  // print_r($segmentlist);die;	
	  return $analytics->data_ga->get( 
		  'ga:'.$profileId,
		  '2021-01-29',
		  '2021-02-04',
		  // 'ga:users,ga:newUsers,ga:sessions'
		  'ga:users,ga:sessions'
		  // ,['dimensions'=>'ga:country',"segment" => "gaid::it1yeqyJS9-4vphn7hU0iQ"]
		  ,['dimensions'=>'ga:country',"segment" => "users::condition::ga:hostname=@apis.discovered.tv"]
		  // ,['dimensions'=>'ga:country',"segment" => "users::condition::ga:hostname=@discovered.tv"]
		  ); 
	   
		  
	}
	function segmentlist($analytics){
		try {
		  $segments = $analytics->management_segments->listManagementSegments();
		} catch (apiServiceException $e) {
		  print 'There was an Analytics API service error '
			  . $e->getCode() . ':' . $e->getMessage();

		} catch (apiException $e) {
		  print 'There was a general API error '
			  . $e->getCode() . ':' . $e->getMessage();
		}
		 $html = '';

		/*
		 * Example #2:
		 * The results of the list method are stored in the segments object.
		 * The following code shows how to iterate through them.
		 */
		foreach ($segments->getItems() as $segment) {
		  $html .= "HTML
					<pre>

					Segment ID = {$segment->getId()}
					Kind       = {$segment->getKind()}
					Self Link  = {$segment->getSelfLink()}
					Name       = {$segment->getName()}
					Definition = {$segment->getDefinition()}
					Created    = {$segment->getCreated()}
					Updated    = {$segment->getUpdated()}
					</pre>
					HTML";
		 
		}
		 print $html;
	}
	function printResults($results) {
	  // Parses the response from the Core Reporting API and prints
	  // the profile name and total sessions.
	 
	  if (count($results->getRows()) > 0) {

		// Get the profile name.
		$profileName = $results->getProfileInfo()->getProfileName();

		// Get the entry for the first entry in the first row.
		$rows['geoData'] = $results->getRows();
		// $sessions = $rows[0][0];
 
		// Print the results.
		// echo '<pre>';
		// print "<p>First view (profile) found: $profileName</p>";
		// print_r($rows);
		$this->load->view('test/analytics3',$rows);
	  } else {
		print "<p>No results found.</p>";
	  }
	}
	function oauth2callback(){
		require APPPATH .'third_party/analytics/analytic/vendor/autoload.php';
		$config =  APPPATH .'third_party/analytics/client_secret_314045516986-2f8ngc90jk9a02j2vgpla64a03vjjngu.apps.googleusercontent.com.json';
		
		// Create the client object and set the authorization configuration
		// from the client_secretes.json you downloaded from the developer console.
		
		
		$client = new Google_Client();
		$client->setAuthConfig($config);
		$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
		$client->setAccessType('offline');
		$client->setApprovalPrompt('force');


		// Handle authorization flow from the server.
		if (! isset($_GET['code'])) {
		  $auth_url = $client->createAuthUrl();
		  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		} else {
		  $client->authenticate($_GET['code']);
		 
		  $_SESSION['refresh_token'] = $client->getRefreshToken();
		   $_SESSION['access_token'] = $client->getAccessToken();
		  
		  // echo '<pre>';
		  // print_r($_SESSION);die;
		  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/test/gaouth';
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}

	}

	function getTotalVideo(){
		$userDetails = $this->DatabaseModel->select_data('user_id,user_name,','users'); 
		foreach($userDetails as $userDetail){
			$user_id = $userDetail['user_id']  ;
			$user_name = $userDetail['user_name']  ;
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$cond = $this->common->channelGlobalCond([1 ,1,NULL,0,1,NULL,0]);
			$cond .= ' AND channel_post_video.user_id = '.$user_id . '' ;
			$join = array('multiple' , array(
				array(	'users', 
						'users.user_id 				= channel_post_video.user_id', 
						'left'),
				array(	'website_mode', 
						'website_mode.mode_id 		= channel_post_video.mode', 
						'left'),		
				array(	'mode_of_genre', 
						'mode_of_genre.genre_id 	= channel_post_video.genre', 
						'left'),
				array(	'channel_post_thumb',
						'channel_post_thumb.post_id = channel_post_video.post_id',
						'left'),
			));
			$leadsCount =	$this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$cond,$join);
			$this->DatabaseModel->access_database('users_content','update',['total_channel_video'=>$leadsCount],array('uc_userid'=>$userDetail['user_id']));
		}
	}

	function downloadaudio(){
		$ch = curl_init('https://s3-trans-cdn.discovered.tv/aud_160/videos/3c92106ebc06a7e3860987db6c78b0d5/3c92106ebc06a7e3860987db6c78b0d5.mp4');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		$output = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($status == 200) {
			header("Content-type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename=m.mp4"); 
			echo $output;
		}
		// $this->load->helper('aws_s3_action');
		// s3_get_object('aud_218/videos/07PvlY1XoPdlFEaldLSA.mp4');
	}
	function deleteobjects3(){
		$this->load->helper('aws_s3_action');
		s3_delete_matching_object('ajaydeep','discovered.tv');
	}
	
	function tempcred(){
		$this->load->helper('aws_cognito_action');
		getTempCredential();
		
	}
	function getobject(){
		$this->load->helper('aws_s3_action');
		
		$keys  = getAllObjects();
		$keyOfArray = [];
		foreach ($keys['Contents'] as $key) {
			array_push($keyOfArray,$key['Key'] );
		}
		s3_delete_object( $keyOfArray);
	}
	function UpdateFtokens(){
		
			$userDetails 		= $this->DatabaseModel->select_data('user_id,user_firebase_token,','users'); 
			foreach($userDetails as $userDetail){
				$user_firebase_token = $userDetail['user_firebase_token']  ;
			
				if(!empty($user_firebase_token)){
					$old_firebase_token['web'] 		= $user_firebase_token;
					$old_firebase_token['android'] 	= '';
					$update_arr 					= array('user_firebase_token'=>json_encode($old_firebase_token) );
					$this->DatabaseModel->access_database('users','update',$update_arr,array('user_id'=>$userDetail['user_id']));
				}
				
			}
		
	}
	
	function upload_all_images($uid){
		$this->load->helper('aws_s3_action');
		$path = 'uploads/aud_'.$uid.'/images/'; 
		if (is_dir($path)){	
			if ($imgDir = opendir($path)){
				while (($file = readdir($imgDir)) !== false){
					if(!empty(trim($file))){
						$amazon_path = "aud_{$uid}/images/{$file}";
						$res = s3_upload_object_ad($path.$file,$amazon_path);
						if(!empty($res)){
							if(file_exists($path.$file)){
								@ unlink($path.$file);
							}
						}
					}
					
				}
				closedir($imgDir);
			}
		}
	}
	function delete_all_images(){
		$this->load->helper('aws_s3_action');
		$path = 'uploads'; 
		if (is_dir($path)){	
			if ($imgDir = opendir($path)){
				while (($file = readdir($imgDir)) !== false){
					if(!empty(trim($file))){
						$f= explode('aud_',$file);
						if(isset($f[1])){
								$path = "uploads/aud_{$f[1]}/images/";
								if (is_dir($path)){
								if ($vd = opendir($path)){
									while (($file = readdir($vd)) !== false){
										if(file_exists($path.$file)){
											echo $path.$file;
											echo '<br>';
											unlink($path.$file);
										}
										
									}

								
								}}
						
						
						}
					
					}
					
				}
				closedir($imgDir);
			}
		}
	}
	function getcontent(){
		$name = rand().'.jpeg';
		$filePath = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_215/images/m8qrivrkydrtZGHsUXX7.png'; 
		$pathToImages = ABS_PATH .'uploads/aud_15/images/'.$name ;
		file_put_contents($pathToImages, file_get_contents($filePath));
	}
	function query( $keyword){
		// $keyword = 'ycConQW';
		$query = "(SELECT uc_pic FROM users_content WHERE uc_pic LIKE '%" . 
           $keyword . "%' ) 
           UNION
           (SELECT image_name FROM channel_post_thumb WHERE image_name LIKE '%" . 
           $keyword . "%' ) 
           UNION
		   (SELECT image_name FROM channel_cast_images WHERE image_name LIKE '%" . 
           $keyword . "%' ) 
           UNION
           (SELECT pub_media FROM publish_data WHERE pub_media LIKE '%" . 
           $keyword . "%' )";
		   
		$data =  $this->db->query($query );  
		$result =  $data->result_array();
		// if(isset($result[0])){
			// return $result[0];
		// }
		if(isset( $result[0]  )){
			// echo '<pre>';
		// print_r($result[0]);
			return ($result[0]['uc_pic']);
			
		}
		return 'NOTAVAI';
		

	}
	public function readDirectory(){
		echo '<pre>';
		$dir = "uploads/";
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
			
				$path = 'uploads/'.$file.'/images/'; 
				
				if (is_dir($path)){
					
						// echo "path: <h2>" . $path . $file .  "</h2><br>";
					if ($vd = opendir($path)){
						while (($vdfile = readdir($vd)) !== false){
							if(!empty(trim($vdfile,'.'))){
						
								$pos = strpos($vdfile,"thumb");
								$pos1 = strpos($vdfile,"webp");
								if(!$pos && !$pos1 ){
									
									$posi = explode('.',$vdfile);
									
									
									$resu = $this->query( $posi[0] );
								
									if($resu == 'NOTAVAI'){
										
										if(file_exists($path.$vdfile)){
											unlink($path.$vdfile);
										}
										if(file_exists($path.$vdfile.'.webp')){
											unlink($path.$vdfile.'.webp');
										}
										
										if(file_exists( $path.$posi[0].'_thumb.'.$posi[1])){
											unlink( $path.$posi[0].'_thumb.'.$posi[1] );
										}
										
										if(file_exists( $path.$posi[0].'_thumb.'.$posi[1] .'.webp')){
											unlink( $path.$posi[0].'_thumb.'.$posi[1] .'.webp' );
										}
										
									}
									
									
									
								}
									
							}
							
						}
						closedir($vd);
					}
					
				}
				
			}
			closedir($dh);
		  }
		}
	}
	function createChannelThumb(){
		$ThumbPath = "./uploads/aud_215/videos/";
		$url = '';
		$this->audition_functions->createChannelThumb($url,'480',$ThumbPath,'jpg'); ;
	}
	function mail_template(){
		$this->load->view('common/mail_notificaiton_template');	
	}
	function metalinkjs(){
		$this->load->view('test/metalink');	
	}
	function metalink(){
		$received_url ='https://www.google.co.in/webhp?ie=UTF-8&rct=j';
		$url = htmlspecialchars(trim($received_url),ENT_QUOTES,'ISO-8859-1',TRUE);

		$host = '';

		if( !empty($url) )
		{
			$url_data = parse_url($url);
			$host = $url_data['host'];

			$file = fopen($url,'r');

			if(!$file)
			{
				exit();
			}
			else
			{
				$content = '';
				while(!feof($file))
				{
					$content .= fgets($file,1024);
				}

				$meta_tags = get_meta_tags($url);

				// Get the title
				$title = '';

				if( array_key_exists('og:title',$meta_tags) )
				{
					$title = $meta_tags['og:title'];
				}
				else if( array_key_exists('twitter:title',$meta_tags) )
				{
					$title = $meta_tags['twitter:title'];
				}
				else
				{
					$title_pattern = '/<title>(.+)<\/title>/i';
					preg_match_all($title_pattern,$content,$title,PREG_PATTERN_ORDER);

					if( !is_array($title[1]) )
						$title = $title[1];
					else
					{
						if( count($title[1]) > 0 )
							$title = $title[1][0];
						else
							$title = 'Title not found!';
					}
				}

				$title = ucfirst($title);

				// Get the description
				$desc = '';

				if( array_key_exists('description',$meta_tags) )
				{
					$desc = $meta_tags['description'];
				}
				else if( array_key_exists('og:description',$meta_tags) )
				{
					$desc = $meta_tags['og:description'];
				}
				else if( array_key_exists('twitter:description',$meta_tags) )
				{
					$desc = $meta_tags['twitter:description'];
				}
				else
				{
					$desc = 'Description not found!';
				}

				$desc = ucfirst($desc);

				// Get url of preview image
				$img_url = '';
				if( array_key_exists('og:image',$meta_tags) )
				{
					$img_url = $meta_tags['og:image'];
				}
				else if( array_key_exists('og:image:src',$meta_tags) )
				{
					$img_url = $meta_tags['og:image:src'];
				}
				else if( array_key_exists('twitter:image',$meta_tags) )
				{
					$img_url = $meta_tags['twitter:image'];
				}
				else if( array_key_exists('twitter:image:src',$meta_tags) )
				{
					$img_url = $meta_tags['twitter:image:src'];
				}
				else
				{
					// Image not found in meta tags so find it from content
					$img_pattern = '/<img[^>]*'.'src=[\"|\'](.*)[\"|\']/Ui';
					$images = '';
					preg_match_all($img_pattern,$content,$images,PREG_PATTERN_ORDER);

					$total_images = count($images[1]);
					if( $total_images > 0 )
						$images = $images[1];

					for($i=0; $i<$total_images; $i++)
					{
						if(getimagesize($images[$i]))
						{
							list($width,$height,$type,$attr) = getimagesize($images[$i]);

							if( $width > 600 ) // Select an image of width greater than 600px
							{
								$img_url = $images[$i];
								break;
							}
						}
					}
				}

				echo "<div>$title</div>";
				echo "<div><img src='$img_url' alt='Preview image'></div>";
				echo "<div>$desc</div>";
				echo "<div>$host</div>";
			}
		}
	}
	
	function GetIdTokens(){
		// header('Content-Type: application/json');
		
		$input = file_get_contents('php://input');
		$post = json_decode($input,true);
		if(isset($post['user'])){
			$this->load->helper('aws_cognito_action');
			echo json_encode(getIdToken($post['user'])) ;
		}else{
			echo json_encode(array('status'=>0));
		}
	}
	function cogntito($type="javascript"){
		$this->load->helper('aws_cognito_action');
		if($type == 'javascript'){
			$this->load->view('test/test');	
		}else if($type == 'getIdToken'){
		
			$r =getIdToken();
			echo '<pre>';
			print_r($r);
		}else if($type == 'getCredential'){
			
			$r = getCredential();
			echo '<pre>';
			print_r($r);
		}else if($type == 'setRules') {
			$r = setRules();
			echo '<pre>';
			print_r($r);
		}else if($type == 'WebIdentity'){
			$r = WebIdentity();
			echo '<pre>';
			print_r($r);
		}
		
		
	}
	function uploadTest(){
		if(isset($_FILES) && !empty($_FILES)){
			
			$this->load->library('common');
			$this->load->helper('aws_s3_action');
			$rna = $this->common->generateRandomString(20);
			$amaTar = "admin/{$rna}.mp4";
			// $amaTar = "admin/{$_FILES['userfile']['name']}";
			
			s3_upload_object_ad($_FILES['userfile']['tmp_name'],$amaTar);
			// echo mime_content_type($_FILES['userfile']['tmp_name']);die;
			// $res = multipartUploader_ad($_FILES['userfile']['tmp_name'], $amaTar);
			print_r($res);die;
			print_r($_FILES['userfile']['tmp_name']);die;
			print_r($rna );die;
			
		}else{
			
			$this->load->helper('form');
			$this->load->view('test/test');	
		}
		
		
	}	
	function csvtobase64(){
		 $csv = file_get_contents('Book1.csv'); 
		 print_r( $csv);
	}
	
	function createVid(){
	$txtpath = 'concat.txt';	
	$txtpath = 'concat.txt';	
	
		$c = 'ffmpeg -f lavfi -i color=size=320x240:duration=10:rate=25:color=blue -vf "drawtext=fontfile=/path/to/font.ttf:fontsize=30:fontcolor=white:x=(w-text_w)/2:y=(h-text_h)/2:text=Stack Overflow" output.mp4';
		$c = 'ffmpeg -f lavfi -i color=c=green:s=320x240:d=10 -vf "drawtext=fontfile=/path/to/font.ttf:fontsize=30:fontcolor=white:x=(w-text_w)/2:y=(h-text_h-text_h)/2:text=Stack,drawtext=fontfile=/path/to/font.ttf:fontsize=30:fontcolor=white:x=(w-text_w)/2:y=(h+text_h)/2:text=Overflow" output.mp4';
		
		$output = exec( $c,$responce );
		print_r($responce);
	}
	
	function getUpdateVideoDuration(){
		
		$checkData = $this->DatabaseModel->select_data('post_id,video_duration,user_id,iva_id,uploaded_video' , 'channel_post_video',array('video_duration'=>0));
		// echo '<pre>';
		// print_r($checkData);die;
		foreach($checkData as $item){
			if(!empty($item['uploaded_video'])){
				$Arr = explode('https://',$item['uploaded_video']);
				
				$isIvaVideo  	=  (!empty(trim($item['iva_id'])))?1:0;
				if($isIvaVideo || isset($Arr[1])){
					$video 	= $item['uploaded_video'] ;
				}else{
					$video =  AMAZON_URL .$item['uploaded_video'];
					
					// $c = "ffmpeg -i {$video} -vstats 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//";
					// $output = exec( $c );
					// $dt = new DateTime("1970-01-01 $output", new DateTimeZone('UTC'));
					// echo $seconds = (int)$dt->getTimestamp();
					echo $video;
					echo '<br>';
					// $this->DatabaseModel->update_data('channel_post_video' , array('video_duration' => $seconds) , array('post_id' => $item['post_id']) , 1);

				}
			}
			
			
		}
	}
	function xmltoarray(){
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>
<string xmlns="http://api.ach.com/">&lt;TransactionsAndReturns&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107661025&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121028399692&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;215&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-06-15T20:06:31&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-06-15T07:18:59&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;215&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Ajaydeep&lt;/IndividualName&gt;
    &lt;Amount&gt;0.13&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;123607990        &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;321177968&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0615B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162375594020210616&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107703544&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121028399693&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;1312&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-06-15T20:06:31&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-06-15T12:09:20&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;1312&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Dij Sparkx&lt;/IndividualName&gt;
    &lt;Amount&gt;0.17&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;1004151542       &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;011001276&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0615B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162377336220210616&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107703545&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121028399694&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;1330&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-06-15T20:06:31&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-06-15T12:09:20&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;1330&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Producer&lt;/IndividualName&gt;
    &lt;Amount&gt;0.42&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;671814145        &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;102001017&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0615B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162377336220210616&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107703567&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121028399695&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;1312&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-06-15T20:06:31&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-06-15T12:13:24&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;1312&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Dij Sparkx&lt;/IndividualName&gt;
    &lt;Amount&gt;73.90&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;1004151542       &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;011001276&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0615B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162377360420210616&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107703568&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121028399696&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;1330&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-06-15T20:06:31&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-06-15T12:13:24&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;1330&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Producer&lt;/IndividualName&gt;
    &lt;Amount&gt;56.44&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;671814145        &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;102001017&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0615B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162377360420210616&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107703600&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121028399697&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;182&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-06-15T20:06:31&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-06-15T12:15:36&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;182&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Hwy17&lt;/IndividualName&gt;
    &lt;Amount&gt;0.01&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;123607990        &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;321177968&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0615B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162377374120210616&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;108581695&lt;/TransactionID&gt;
    &lt;TraceNumber&gt;061121029454754&lt;/TraceNumber&gt;
    &lt;CustomerTraceNumber&gt;1917&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;4&lt;/TransactionStatusID&gt;
    &lt;ProcessingDate&gt;2021-07-14T20:03:54&lt;/ProcessingDate&gt;
    &lt;CreatedOn&gt;2021-07-14T02:05:47&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-07-15T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;1917&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Junior Coimbra&lt;/IndividualName&gt;
    &lt;Amount&gt;12.40&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;483028041477     &lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;026009593&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment   &lt;/TransactionDescription&gt;
    &lt;ParentName&gt;06112102&lt;/ParentName&gt;
    &lt;CustomerServiceNumber&gt;8314277530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;Discovered USA&lt;/CustomerName&gt;
    &lt;ParentID&gt;06112102&lt;/ParentID&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;ReleaseFileName&gt;AtlCapREL0714B.ach&lt;/ReleaseFileName&gt;
    &lt;CustomerFileName&gt;10162624274920210715&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
    &lt;Addenda xml:space="preserve"&gt;&lt;/Addenda&gt;
    &lt;Email&gt;&lt;/Email&gt;
    &lt;Return&gt;
      &lt;ReturnID&gt;64065557&lt;/ReturnID&gt;
      &lt;ReturnTraceNumber&gt;061121029454754&lt;/ReturnTraceNumber&gt;
      &lt;CreatedOn&gt;2021-07-19T07:50:08&lt;/CreatedOn&gt;
      &lt;ReturnCode&gt;R03&lt;/ReturnCode&gt;
      &lt;Status&gt;&lt;/Status&gt;
      &lt;TransactionID&gt;108581695&lt;/TransactionID&gt;
      &lt;Addenda xml:space="preserve"&gt;R03061121029454754      02600959                                            061121029454754&lt;/Addenda&gt;
      &lt;ReturnFileID&gt;489075&lt;/ReturnFileID&gt;
      &lt;FedReturnFileName&gt;\\ACHFAPPROD\Files\FED\in\Files\Logical\acb202107197_19_2021_74900_am_17_19_2021_75007_AM_1.str&lt;/FedReturnFileName&gt;
    &lt;/Return&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107649458&lt;/TransactionID&gt;
    &lt;CustomerTraceNumber&gt;215&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;5&lt;/TransactionStatusID&gt;
    &lt;CreatedOn&gt;2021-06-15T07:09:37&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;215&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Ajaydeep&lt;/IndividualName&gt;
    &lt;Amount&gt;0.13&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;123607990&lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;321177968&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment&lt;/TransactionDescription&gt;
    &lt;CustomerServiceNumber&gt;831-427-7530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;DISCOVERED USA Inc.&lt;/CustomerName&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;CustomerFileName&gt;101623755376&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107661007&lt;/TransactionID&gt;
    &lt;CustomerTraceNumber&gt;215&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;5&lt;/TransactionStatusID&gt;
    &lt;CreatedOn&gt;2021-06-15T07:16:38&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;215&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Ajaydeep&lt;/IndividualName&gt;
    &lt;Amount&gt;0.13&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;123607990&lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;321177968&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment&lt;/TransactionDescription&gt;
    &lt;CustomerServiceNumber&gt;831-427-7530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;DISCOVERED USA Inc.&lt;/CustomerName&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;CustomerFileName&gt;101623755799&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
  &lt;/Transaction&gt;
  &lt;Transaction&gt;
    &lt;TransactionID&gt;107661017&lt;/TransactionID&gt;
    &lt;CustomerTraceNumber&gt;215&lt;/CustomerTraceNumber&gt;
    &lt;TransactionStatusID&gt;5&lt;/TransactionStatusID&gt;
    &lt;CreatedOn&gt;2021-06-15T07:17:56&lt;/CreatedOn&gt;
    &lt;EffectiveEntryDate&gt;2021-06-16T00:00:00&lt;/EffectiveEntryDate&gt;
    &lt;IndividualID&gt;215&lt;/IndividualID&gt;
    &lt;IndividualName&gt;Ajaydeep&lt;/IndividualName&gt;
    &lt;Amount&gt;0.13&lt;/Amount&gt;
    &lt;RDFIAccountNumber&gt;123607990&lt;/RDFIAccountNumber&gt;
    &lt;RDFIRoutingNumber&gt;321177968&lt;/RDFIRoutingNumber&gt;
    &lt;TransactionDescription&gt;Payment&lt;/TransactionDescription&gt;
    &lt;CustomerServiceNumber&gt;831-427-7530&lt;/CustomerServiceNumber&gt;
    &lt;CustomerName&gt;DISCOVERED USA Inc.&lt;/CustomerName&gt;
    &lt;City&gt;Santa Cruz&lt;/City&gt;
    &lt;State&gt;CA&lt;/State&gt;
    &lt;SECCOde&gt;WEB&lt;/SECCOde&gt;
    &lt;EntryMethod&gt;Uploaded&lt;/EntryMethod&gt;
    &lt;TransactionCode&gt;22&lt;/TransactionCode&gt;
    &lt;CustomerFileName&gt;101623755880&lt;/CustomerFileName&gt;
    &lt;TransactionTypeDescription&gt;Credit&lt;/TransactionTypeDescription&gt;
    &lt;TransactionType&gt;Credit&lt;/TransactionType&gt;
    &lt;SpecialNote&gt;Note is not available&lt;/SpecialNote&gt;
  &lt;/Transaction&gt;
&lt;/TransactionsAndReturns&gt;</string>';  
				
				$response 	= simplexml_load_string(html_entity_decode($xml));
				
				$jsonfile 	= json_encode($response);
				$myarray 	= json_decode($jsonfile, true);
				echo '<pre>';
				print_r($myarray );
				
	}
	function encodeVidDesc(){
		$vids = $this->DatabaseModel->select_data("post_id,description" , 'channel_post_video');
		
		foreach($vids  as $vid ){
			if(!empty(trim($vid['description'])))
			$this->DatabaseModel->update_data('channel_post_video' , array('description' => json_encode($vid['description'])) , array('post_id' => $vid['post_id']) , 1);
		}
	}
	

	function varifyAch(){
		$this->load->library('common');
		$arrayData = array(	
							'token'   		=> $this->common->ach_token,
							'nachaid' 		=> $this->common->ach_nachaid,
							'routingnumber' => '053000196',
							'accountnumber' => '00066893341d2',
							'amount'		=>	1
							);
		$responce	=	$this->common->call_ach('POST', $arrayData ,'Verify');
		echo '<pre>';
		print_r($responce);
	}
	function substractDate(){
		print('Privious Date ' . date('Y-m-d', strtotime('-67 day', strtotime(date('Y-m-d')))));

	}
	function AchUploadFile(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.ach.com/webservice/V1/gateway.asmx/UploadFile",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "securityToken=fab20e2f-9ca2-450c-ab0d-c4f8f4b2108e&nachaId=9-00000386&fileTransactionCount=1&fileDebits=1&fileCredits=1&fileName=file.csv&fileContent=OS0wMDAwMDM4NixEaXNjb3ZlcmVkLCA5LTAwMDAwMzg2LERpc2NvdmVyZWQsV0VCLFBheW91dCwyMDA2MTUsMTIzNDU2Nzg5LEFKQVkgUmVjZWl2ZXIsMTExMDAwNjE0LDQ1MDg5Nzk0MiwyNywxLjAwLDEyMzQ1Njc0ODkwLFMK",
		  CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$new 		= simplexml_load_string(html_entity_decode($response));
			$jsonfile 	= json_encode($new);
			$myarray 	= json_decode($jsonfile, true);
			print_r($myarray);
		}
	}
	function parse_response() { 
	
	$path = "myxmlfile.xml";
	file_put_contents($path, html_entity_decode($_SESSION['parse']));
	 
	   $xmlfile = file_get_contents($path);
	   $new 	= simplexml_load_string($xmlfile);
	   $jsonfile = json_encode($new);
	   $myarray = json_decode($jsonfile, true);
	   print_r($myarray);
		// return $arr;
	}
	
	
	function imageSize(){
		$path = 'uploads/vivek/1875269584.jpg';
		
		list($width, $height, $type, $attr) = getimagesize($path);
		echo filesize($path) . '<br>' .$width.'<br>'.$height.'<br>'.$type.'<br>'.$attr;
	}
	function php(){
		echo CI_VERSION;
		echo phpinfo();
	}
	
	function resize(){
		$path = './uploads/vivek/sher.jpg';
		echo $this->audition_functions->resizeImage('294','217',$path,'',$maintain_ratio = false,$create_thumb= TRUE);	
	}
	function getImage(){
		// print_r($_FILES);
		// $this->load->library('Audition_functions');
		$pathToImages = ABS_PATH .'uploads/vivek/';
		
		$images=[];
		for($i=0;$i< sizeof($_FILES);$i++){
			$image_name = $pathToImages.rand().'.jpeg';
			  move_uploaded_file($_FILES[$i]['tmp_name'],$image_name);
			  array_push($images,$image_name);
			  
		}
		echo json_encode($images);
		// print_r($r);
	}
	function trims(){
		echo $text = '`1š›84`'; 
		echo '<br>';
		echo $res = preg_replace("/[^0-9]/", "", (slugify($text)) );
	}
	
	
	function browser(){
		echo '<pre>';
		echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

		$browser = get_browser(null, true);
		print_r($browser);
	}
	
	function convertImageToWebpFormat(){
		$this->load->library('convert_image_webp');
		$post_thumb = $this->DatabaseModel->select_data("*" , 'channel_post_thumb');
		foreach($post_thumb as $list){
			$path = ABS_PATH .'uploads/aud_'.$list['user_id'].'/images/'.$list['image_name'];
			if(file_exists($path))
			$this->convert_image_webp->convertIntoWebp($path);
			
			$img = explode('.',$list['image_name']);
			
			$path = ABS_PATH .'uploads/aud_'.$list['user_id'].'/images/'.$img[0].'_thumb.'.$img[1];
			
			if(file_exists($path))
			$this->convert_image_webp->convertIntoWebp($path);
			
		}
	}
	function cron(){
		$this->DatabaseModel->access_database('cron_test','insert',array('video_userid'=>215,'view_date'=>date('Y-m-d'),'view_count'=>1,'ads_count'=>1));
	}
	
	
	function gmt(){
		print gmdate("Y-m-d\TH:i:s\Z");
	}
	
	
	function create_job(){
		$this->load->helper('aws_s3_action');
		CreateJob('aud_2537/videos/PA0CvgFjn8u8Q0d3ofbf.mp4','aud_2537/videos/PA0CvgFjn8u8Q0d3ofbf/');
	}
	function  CreateElasticJob(){
		$this->load->helper('aws_s3_action');
		 ElasticTranscoder();
	}
	function img(){
		$img = 'https://d1argtojjc5x02.cloudfront.net/content/photos/12433/655331_067.jpg?&Expires=1579932396&Signature=NZ1vQtT5Y3iE96rv7Ep4L9CztpZKl7hH-gxcYJl6rookdvXYryEXPZlwX9E~6J2TozNmT5-HCL9LJ4CJ0uygFUy6RtHR-nIzqAYzvHEo4AitpIWaDZeJg~1LGHlANBXIQsYS8Sm2UcjXN07K9rqwdaS7-owzQec9srTUK6GlGNbK4irRc4GrSWPUEJwW35o3smA2Kv~2HMjKoAhaFUSUPhS7RiOICZJXShOUAIjaUahfMlQnA9XPaICz2VlLZwv7lx70O0piPl4BMdnlRzBQpMJ~B0GLsIRX75oJX7S7l9i~Ii1i3tla~UoJrydSevV3BgEHMY8foT-8Gf9T58lC2Q__&Key-Pair-Id=APKAIUDDGLY3RASDQSZQ';
		
		$name = rand();
		$filePath = ABS_PATH.'/iva/'.$name.'.jpeg'; 
		file_put_contents($filePath, file_get_contents($img));
	}
	function mime_type(){
		$type= '';
		
		$img = 'https://video.internetvideoarchive.net/video.m3u8?cmd=6&fmt=11&customerid=222333&publishedid=974449&rnd=29&e=1737441506&h=a87279ce9dff2799254f2b0872b9372b';
		$img = 'https://s3.us-west-1.amazonaws.com/discovered.tv/aud_299/videos/56HF0TQlIsok4q7glJId.mp4';
		$img = explode('?',$img);
		
		if(isset($img[0]) && !empty($img[0])){
			 $ext  = pathinfo($img[0] , PATHINFO_EXTENSION);
			 if( $ext == 'm3u8'){
				 $type = 'application/x-mpegURL';
			 }else if($ext == 'mp4'){
				  $type = 'video/mp4';
			 }
		}
		echo  $type;
		
	}
	function timezone(){
		$this->load->library('common');
		$utc = '2019-09-25 11:12:37';
		echo  $this->common->manageTimezone($utc);
		
	}
	
	function update_rec_data(){
		echo '<pre>';
		print_r($_SESSION);
		
		// echo DB()->query("update recommended_videos set video_category = CONCAT(video_category, ',78' ) where id = '1'");
		
		echo DB()->query("INSERT INTO asset 
(name, house, vehicle, current, savings, kwsp, haji, asb, stock, property, others, total) 
VALUES ('$name', '$ahouse', '$avehicle', '$acurrent', '$asaving', '$akwsp', '$ahaji', '$aasb', '$astock', '$aproperty', '$aothers', '$total')
ON DUPLICATE KEY 
UPDATE house = '$ahouse', vehicle = '$avehicle', current = '$acurrent', savings = '$asaving', kwsp = '$akwsp', haji = '$ahaji', asb = '$aasb', stock = '$astock', property = '$aproperty', others = '$aothers', total = 'assets' ");
		
		
		$sdfdsfdsdf = $this->DatabaseModel->select_data("SUBSTRING(video_category, 7, 3) AS category" , 'recommended_videos' ,  array('id' => 1));
		print_r($sdfdsfdsdf);
	
	}
	
	
	function check_uniq_id(){
		$this->load->library('share_url_encryption');
		$checkData = $this->DatabaseModel->select_data('post_id' , 'channel_post_video');
		foreach($checkData as $d){
			$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$d['post_id'] , 'encode' , 'id');
			$this->DatabaseModel->update_data('channel_post_video' , array('post_key' => $check[0]) , array('post_id' => $d['post_id']) , 1);
		}
		
	}
	function createVideoPriview(){
		$filePath = ABS_PATH.'uploads/admin/'; 
		$fileName = rand().'.mp4';
		
		$video = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_218/videos/8bbbdb2fd5dff0c96c75f8e0d76a3dd0.mp4';
		$cmd = "ffmpeg -i  {$video} -ss 00:00:00 -t 00:00:10 -acodec copy -vcodec copy {$filePath}{$fileName}"; 
		exec ($cmd,$output,$responce );
		 
		$cmd ="ffmpeg -i {$filePath}{$fileName} -b 400k {$filePath}"."outp.mp4";
		exec ($cmd,$output,$responce );
		unlink("{$filePath}{$fileName}");
	}
	
	function createThumb(){
		// $video = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_877/videos/g3OlXB86t68d2rNQK969.mov';
		$video = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_1103/videos/AsevIq3zXgeGCYVrxrmF.mp4';
	
		$filePath = ABS_PATH.'uploads/vivek/'; 
		
		$size = '690x388';
		
		$imgArr = [];
		$time = array('00:00:01','00:00:03','00:00:05');
		$fileName = rand().'.jpg';
		$cmd = "ffmpeg -i {$video}  -ss {$time[0]} -vframes 2 -s {$size} {$filePath}{$fileName}"; 
		$cmd = "ffmpeg -itsoffset -1 -i {$video} -ss {$time[2]} -vframes 1 -filter:v scale='608:-1'  {$filePath}{$fileName}"; 
		exec ($cmd,$output,$responce ) ; 
		echo '<pre>';
		print_r($responce);
		
	}
	function VideoDuration(){
		$path = 'https://s3.us-west-1.amazonaws.com/discovered.tv/aud_190/videos/1084554439a6a902e87393f81642192a.mp4';
		$c = "ffmpeg -i {$path} -vstats 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//";

		$output = exec( $c );
		$dt = new DateTime("1970-01-01 $output", new DateTimeZone('UTC'));
		$seconds = (int)$dt->getTimestamp();
		$seconds =  $seconds/3;
		// echo "<br>";
		$t1 = 0; 
		$t2=  $seconds *1;
		$t3 = $seconds *2;
		
		$t1 = round($t1);
		$t2 = round($t2);
		$t3 = round($t3);
		
		echo sprintf('%02d:%02d:%02d', ($t1/3600),($t1/60%60), $t1%60);
		echo "<br>";
		echo sprintf('%02d:%02d:%02d', ($t2/3600),($t2/60%60), $t2%60);
		echo "<br>";
		echo sprintf('%02d:%02d:%02d', ($t3/3600),($t3/60%60), $t3%60);
		// echo "<pre>";
		// print_r($output);
		
	}
	
	function mails(){
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'relay11.splitmx.com',
		'smtp_port' => 25,
		'smtp_user' => 'discovered-tv/discovered-tv',
		'smtp_pass' => 'YfArBhOZKOp0BYX5TYLCtgvf',
		'mailtype'  => 'html', 
		'charset'   => 'utf-8',	
		'smtp_timeout'   => 20,
		// 'smtp_crypto' => 'tsl'
		
	);
	$this->load->library('email', $config);
	// $this->email->set_newline("\r\n");

	
        $this->email->initialize($config);
        $this->email->from('help@discovered.tv', 'dc');
        $this->email->to('ajay.parmar@himanshusofttech.com');
        $this->email->subject('test');
        $this->email->message('The email send using codeigniter library');
		$this->email->send();
		$res = $this->email->print_debugger();
		print_r($res);
	}
	public function send_mail() {
        $from_email = "ajaydeepparmar@gmail.com";
       
        //Load email library
        $this->load->library('email');
        $this->email->from($from_email, 'Identification');
        $this->email->to('ajay.parmar@himanshusofttech.com');
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');
        $this->email->send();
		$res = $this->email->print_debugger();
		print_r($res);
  
    }
	function tests(){

		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'relay11.splitmx.com',
		'smtp_port' => 25,
		'smtp_user' => 'discovered-tv/discovered-tv',
		'smtp_pass' => 'YfArBhOZKOp0BYX5TYLCtgvf',
		'mailtype' => 'html', 
		'charset' => 'utf-8',	
		'smtp_timeout' => 20,


		);
		$this->load->library('email');
		$this->email->initialize($config);
		$this->email->from('help@discovered.tv', 'dc');
		$this->email->to('shahbaj.shah@himanshusofttech.com');
		$this->email->subject('test');
		$this->email->message('The email send using codeigniter library');
		$this->email->send();
		$res = $this->email->print_debugger();
		print_r($res);
	}
	function send_email(){
		$this->load->library('Audition_functions');
		$this->audition_functions->send_emails('ajaydeepparmar@gmail.com','testing','this is for testing purpose');
	}
	function  upload_video_html(){
		$data= [];
		$this->load->view('home/inc/header',$data);
        $this->load->view('home/upload_video_form_html',$data);
        $this->load->view('home/inc/footer',$data);
	}
	
	function iframe(){
		echo '<iframe src="https://discovered.tv/embed/554" width="560" height="315" frameborder="0" allow="autoplay"></iframe> ';
	}
	
	
	function html($page=null,$any=null){
		$data = array();
		  $data['basepath'] = base_url();
		if(!empty($page)){
			
			if(!empty($any)){
				// $this->load->view('backend/include/header',$data);
			}else{
				$this->load->view('home/inc/header',$data);
			}
			
			$this->load->view('HTML/'.$page.'',$data);
			
			if(!empty($any)){
				// $this->load->view('backend/include/footer',$data);
			}else{
				$this->load->view('home/inc/footer',$data);
			}
		
		}
	}
	
		
	
	
				
		
	function stream(){
		$file_path = 's3://discovered.tv/aud_215/videos/ToxSxiXKrsBPScQLAdmY.mp4';
		$file_path = array('file_path' => $file_path);
		$this->load->library('video_streaming',$file_path);
		$this->video_streaming->start();
	}

	function checkIfProfileComplete(){
			$artist_category = $this->DatabaseModel->select_data('*','artist_category');
		
			foreach($artist_category as $category){
					$category_slug = strtolower(str_replace(" ","-",$category['category_name']));
					$category_slug = slugify($category_slug);
					$this->DatabaseModel->update_data_limit('artist_category', array('category_slug'=>$category_slug) , array('category_id' => $category['category_id']) , 1);	
					
			}
		
	}
	function updatCate(){
			$artist_category = $this->DatabaseModel->select_data('*','artist_category');
		
			foreach($artist_category as $category){
					
					$this->DatabaseModel->update_data_limit('artist_category', array('category_order'=>$category['category_id']) , array('category_id' => $category['category_id']) , 1);	
					
			}
		
	}
	
	function rotateImage(){
		
		
		$pathToImages = ABS_PATH .'uploads/aud_215/images/ab0c779933840a8b2dfa975a5117b3a4.jpg.webp';
		if(file_exists($pathToImages)){
			echo 'Yes';
			
		}
		$config=array();
		$config['image_library']   	= 'gd2';
		$config['source_image'] 	= $pathToImages;
		$config['rotation_angle'] 	= '90';
		// $config['rotation_angle'] = 'hor';
		$this->load->library('image_lib',$config);
		$this->image_lib->initialize($config); // reinitialize it instead of reloading
		$this->image_lib->rotate();
		$this->image_lib->clear();
		echo $this->image_lib->display_errors();
		echo '<img src="'.base_url('uploads/aud_215/images/ab0c779933840a8b2dfa975a5117b3a4.jpg.webp?q='.date('his')) .'">';
	}
	function convertTom3u8(){
		$video = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_218/videos/8bbbdb2fd5dff0c96c75f8e0d76a3dd0.mp4';
		$filePath = ABS_PATH.'uploads/vivek/manifest.m3u8'; 
		// $cmd = "ffmpeg -i {$video} -profile:v baseline -level 3.0 -s 640x360 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls {$filePath}";
		$cmd = "ffmpeg -i {$video} -b:v 1M -g 60 -hls_time 2 -hls_list_size 0 -hls_segment_size 500000 {$filePath}";
		// $cmd = "ffmpeg -i {$video} -strict -2 -preset:v veryfast -profile:v baseline {$filePath}";
		// $cmd = "ffmpeg -re -f concat -i {$video} -c:v libx264 -vbsf h264_mp4toannexb -r 25 -g 75 -c:a libfdk_aac -hls_time 3  {$filePath}";
		// $cmd = "ffmpeg -i {$video} -c:v h264 -flags +cgop -g 30 -hls_time 1 {$filePath}";
		
		exec ($cmd,$output,$responce );
		echo '<pre>';
		print_r($output);
	}
	function creathlsplaylist(){
		$filePath = ABS_PATH.'uploads/vivek/manifest.m3u8'; 
		
		$video = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_218/videos/8bbbdb2fd5dff0c96c75f8e0d76a3dd0.mp4';
		
		$c = 'ffmpeg -hide_banner -re -i '.$video.' -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0
			  -c:v h264 -profile:v main -crf 20 -sc_threshold 0 -g 48 -keyint_min 48 -c:a aac -ar 48000
			  -filter:v:0 scale=w=640:h=-2  -maxrate:v:0 856k  -bufsize:v:0 1200k -b:a:0 96k  
			  -filter:v:1 scale=w=842:h=-2  -maxrate:v:1 1498k -bufsize:v:1 2100k -b:a:1 128k 
			  -filter:v:2 scale=w=1280:h=-2 -maxrate:v:2 2996k -bufsize:v:2 4200k -b:a:2 128k 
			  -filter:v:3 scale=w=1920:h=-2 -maxrate:v:3 5350k -bufsize:v:3 7500k -b:a:3 192k 
			  -var_stream_map "v:0,a:0 v:1,a:1 v:2,a:2 v:3,a:3" -hls_time 4 -master_pl_name master.m3u8 
			  -hls_segment_filename '.$filePath.'';
		
		exec ($c,$output,$responce );
		echo '<pre>';
		print_r($output);
	}
						
	
	public function search_bad_words() 
	{
		echo '<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}
		
		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}
		
		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		.highlight {
			background-color: yellow;
		  }
		</style>';

		$str = 'a55, a55hole, aeolus, ahole, anal, analprobe, anilingus, anus, areola, areole, arian, ass, assbang, assbanged, assbangs, asses, assfuck, assfucker, assh0le, asshat, assho1e, ass, ass hole, assholes, assmaster, assmunch, asswipe, asswipes, azazel, azz, b1tch, ballsack, badass, banger, barf, bastard, bastards, bawdy, beaner, beardedclam, beastiality, beatch, beater, beaver, beer, beeyotch, beotch, biatch, bigtits, big tits, bimbo, bitch, bitched, bitches, bitchy, blow job, blow, blowjob, blowjobs, bod, boink, bollock, bollocks, bollok, boned, boner, boners, bong, boob, boobies, boobs, booby, booger, bookie, bootee, bootie, booty, booze, boozer, boozy, bosom, bosomy, breast, breasts, bugger, bukkake, bullshit, bull shit, bullshits, bullshitted, bullturds, bung, busty, butt, butt fuck, buttfuck, buttfucker, buttfucker, buttplug, c.0.c.k, c.o.c.k., c.u.n.t, c0ck, c-0-c-k, caca, cahone, cameltoe, carpetmuncher, cawk, cervix, chinc, chincs, chink, chink, chode, chodes, cl1t, clit, clitoris, clitorus, clits, clitty, cocain, cocaine, cock, c-o-c-k, cockblock, cockholster, cockknocker, cocks, cocksmoker, cocksucker, cock sucker, coital, commie, coon, coons, corksucker, crabs, cracker, crackwhore, cum, cummin, cumming, cumshot, cumshots, cumslut, cumstain, cunilingus, cunnilingus, cunny, cunt, cunt, c-u-n-t, cuntface, cunthunter, cuntlick, cuntlicker, cunts, d0ng, d0uch3, d0uche, d1ck, d1ld0, d1ldo, dago, dagos, dawgie-style, dick, dickbag, dickdipper, dickface, dickflipper, dickhead, dickheads, dickish, dick-ish, dickripper, dicksipper, dickweed, dickwhipper, dickzipper, diddle, dike, dildo, dildos, diligaf, dillweed, dimwit, dingle, dipship, doggie-style, doggy-style, dong, doosh, dopey, douch3, douche, douchebag, douchebags, douchey, dumass, dumbass, dumbasses, dyke, dykes, ejaculate, erection, erotic, essohbee, extacy, f.u.c.k, fack, fag, fagg, fagged, faggit, faggot, fagot, fags, faig, faigt, fannybandit, fartknocker, felch, felcher, felching, fellate, fellatio, feltch, feltcher, fisted, fisting, fisty, floozy, foad, fondle, foobar, foreskin, freex, frigg, frigga, fubar, fuck, f-u-c-k, fuckass, fucked, fucked, fucker, fuckface, fuckin, fucking, fucknugget, fucknut, funk, fuckoff, fucks, fucktard, fuck-tard, fuckup, fuckwad, fuckwit, fudgepacker, fuk, fvck, fxck, gae, gai, gey, gfy, ghay, ghey, gigolo, glans, goatse, gonad, gonads, gook, gooks, gringo, gspot, g-spot, gtfo, guido, h0m0, h0mo, handjob, hard on, he11, hebe, heeb, hell, hemp,  herp, herpes, herpy, hiv, hobag, hom0, homey, homo, homoey, honky, hooch, hookah, hooker, hoor, hootch, hooter, hooters, horny, hump, humped, humping, hussy, hymen, inbred, incest, injun, j3rk0ff, jackass, jackhole, jackoff, jap, japs, jerk0ff, jerked, jerkoff, jism, jiz, jizm, jizz, jizzed, kike, kikes, kinky, kkk, klan, knobend, kooch, kooches, kootch, kraut, kyke, labia, lech, leper, lesbo, lesbos, lez, lezbian, lezbians, lezbo, lezbos, lezzie, lezzies, lezzy, lmao, lmfao, loin, loins, lube, lusty, mams, massa, masterbate, masterbating, masterbation, masturbate, masturbating, masturbation, maxi, menses, menstruate, menstruation, m-fucking, mofo, molest, moolie, motherfucka, motherfucker, motherfucking, mtherfucker, mthrfucker, mthrfucking, muffdiver, muthafuckaz, muthafucker, mutherfucker, mutherfucking, muthrfucking, nad, nads, napalm, nappy, negro, nigga, niggah, niggas, niggaz, nigger, nigger, niggers, niggle, niglet, nimrod, ninny, nooky, nympho, opiate, orgasm, orgasmic, orgies, orgy, ovary, ovum, ovums, p.u.s.s.y., paddy, paki, pantie, panties, panty, pastie, pasty, pcp, pecker, pedo, pedophile, pedophilia, pedophiliac, pee, peepee, penetrate, penetration, penial, penile, penis, perversion, peyote, phalli, phallic, phuck, pillowbiter, pimp, pinko, polack, pollock, poon, poontang, porn, porno, prick, prig, prude, pube, pubic, pubis, punkass, punky, puss, pussies, pussy, pussypounder, puto, queaf, queef, queef, queer, queero, queers, quicky, quim, racy, raunch, rectal, rectum, rectus, reefer, reetard, reich, retard, retarded, revue, rimjob, ritard, rtard, r-tard, rum, rump, rumprammer, ruski, s.h.i.t., s.o.b., s0b, scag, scantily, schizo, schlong, scrog, scrot, scrote, scrud, scum, seaman, seamen,  semen, sexy, sexuality, sexiest, sexual, sex, sexual, sh1t, s-h-1-t, shamedame, shit, s-h-i-t, shite, shiteater, shitface, shithead, shithole, shithouse, shits, shitt, shitted, shitter, shiz, sissy, skag, skank, slave, sleaze, sleazy, slut, slutdumper, slutkiss, sluts, smegma, smut, smutty, snatch, sniper, snuff, s-o-b, sodom, souse, soused, sperm, spic, spick, spik, spiks, spooge, spunk, steamy, stfu, stiffy, stoned, suck, sucked, sucking, sumofabiatch, t1t, tampon, tard, tawdry, teabagging, teat, terd, teste, testee, testes, testicle, testis, tinkle, tit, titfuck, titi, tits, tittiefucker, titties, titty, tittyfuck, tittyfucker, toke, toots, tramp, tubgirl, turd, tush, twat, twats, ugly, undies, unwed, urinal, urine, uterus, uzi, vag, vagina, vixen, vulgar, vulva, wad, wang, wank, wanker, wazoo, wedgie, weed, weenie, weewee, weiner, wench, wetback, wh0re, wh0reface, whitey, whoralicious, whorealicious, whored, whoreface, whorehopper, whorehouse, whores, whoring, wigger, wop, wtf, x-rated, xxx, yeasty, yobbo, zoophile';
		// $str = 'murder';

		$str_arr = explode (",", $str); 

		$without_comma_str = str_replace(',', '', $str_arr);
		// print_r($str_arr);die;
		$where = '';
		$arr = [];
		$total = count($str_arr);

		for ($i=0; $i < $total ; $i++) { 
			$param  = trim($str_arr[$i]);
			// $where .= "title LIKE '% {$param} %' OR description LIKE '% {$param} %' OR tag LIKE '% {$param} %' " ; 
			$where .= "title REGEXP '[[:<:]]{$param}[[:>:]]' OR description REGEXP '[[:<:]]{$param}[[:>:]]' OR tag REGEXP '[[:<:]]{$param}[[:>:]]' " ; 

			
			if($total - 1 !== $i){
				$where .= ' OR ' ;
			}
			
		}

		$sql = "SELECT post_id,mode,tag,description,post_key,title FROM `channel_post_video` WHERE $where";
		$query = $this->db->query($sql);
		
	
		$data =  $query->result_array(); 
		$ar = [];
		$update_query = "UPDATE `channel_post_video` SET `active_status` = 0 WHERE `post_id` = ";

		foreach ($data as $key => $value) {
			$ar[$key] = $update_query . $value['post_id'] ;
		}
		
		// $this->update_status($ar);
		// print_r($ar);
		echo $this->db->last_query();
		echo '<br>';
		echo '<input type="button" onclick="update_status()" id="update" value="Update" />';
		echo '<br>';

		echo '<table id="content">';
		echo '<tr>
				<th>S.No.</th>
				<th>Title</th>
				<th>Description</th>
				<th>Tag</th>
				
				<th>Video Link</th>
			</tr>';
		$count = 1;
		foreach ($data as $row){
			echo '
			<tr>
			  <th>'.$count.'</th>
			  <th>'.$row['title'].'</th> 
			  <th>'.$row['description'].'</th>
			  <th>'.$row['tag'].'</th>
			  <th><a target="_blank" href="'. base_url('watch/').$row['post_key']. '">'.$row['post_key'].'</a></th>
			</tr>';  
			$count += 1;
		}
		echo '</table>';
		echo '';
		// highlight(passedArray[index]);
		echo '<script src="'.base_url('repo/js/highlight.js').'"> </script>';
		echo '<script>
		var passedArray = '.json_encode($ar).';

		function update_status(){}


		var myHilitor = new Hilitor("content"); // id of the element to parse
		
		var words = "a55 a55hole aeolus ahole anal analprobe anilingus anus areola areole arian ass assbang assbanged assbangs asses assfuck assfucker assh0le asshat assho1e ass ass hole assholes assmaster assmunch asswipe asswipes azazel azz b1tch ballsack badass banger barf bastard bastards bawdy beaner beardedclam beastiality beatch beater beaver beer beeyotch beotch biatch bigtits big tits bimbo bitch bitched bitches bitchy blow job blow blowjob blowjobs bod boink bollock bollocks bollok boned boner boners bong boob boobies boobs booby booger bookie bootee bootie booty booze boozer boozy bosom bosomy breast breasts bugger bukkake bullshit bull shit bullshits bullshitted bullturds bung busty butt butt fuck buttfuck buttfucker buttfucker buttplug c.0.c.k c.o.c.k. c.u.n.t c0ck c-0-c-k caca cahone cameltoe carpetmuncher cawk cervix chinc chincs chink chink chode chodes cl1t clit clitoris clitorus clits clitty cocain cocaine cock c-o-c-k cockblock cockholster cockknocker cocks cocksmoker cocksucker cock sucker coital commie coon coons corksucker crabs cracker crackwhore cum cummin cumming cumshot cumshots cumslut cumstain cunilingus cunnilingus cunny cunt cunt c-u-n-t cuntface cunthunter cuntlick cuntlicker cunts d0ng d0uch3 d0uche d1ck d1ld0 d1ldo dago dagos dawgie-style dick dickbag dickdipper dickface dickflipper dickhead dickheads dickish dick-ish dickripper dicksipper dickweed dickwhipper dickzipper diddle dike dildo dildos diligaf dillweed dimwit dingle dipship doggie-style doggy-style dong doosh dopey douch3 douche douchebag douchebags douchey dumass dumbass dumbasses dyke dykes ejaculate erection erotic essohbee extacy f.u.c.k fack fag fagg fagged faggit faggot fagot fags faig faigt fannybandit fartknocker felch felcher felching fellate fellatio feltch feltcher fisted fisting fisty floozy foad fondle foobar foreskin freex frigg frigga fubar fuck f-u-c-k fuckass fucked fucked fucker fuckface fuckin fucking fucknugget fucknut funk fuckoff fucks fucktard fuck-tard fuckup fuckwad fuckwit fudgepacker fuk fvck fxck gae gai gey gfy ghay ghey gigolo glans goatse gonad gonads gook gooks gringo gspot g-spot gtfo guido h0m0 h0mo handjob hard on he11 hebe heeb hell hemp herp herpes herpy hiv hobag hom0 homey homo homoey honky hooch hookah hooker hoor hootch hooter hooters horny hump humped humping hussy hymen inbred incest injun j3rk0ff jackass jackhole jackoff jap japs jerk0ff jerked jerkoff jism jiz jizm jizz jizzed kike kikes kinky kkk klan knobend kooch kooches kootch kraut kyke labia lech leper lesbo lesbos lez lezbian lezbians lezbo lezbos lezzie lezzies lezzy lmao lmfao loin loins lube lusty mams massa masterbate masterbating masterbation masturbate masturbating masturbation maxi menses menstruate menstruation m-fucking mofo molest moolie motherfucka motherfucker motherfucking mtherfucker mthrfucker mthrfucking muffdiver muthafuckaz muthafucker mutherfucker mutherfucking muthrfucking nad nads napalm nappy negro nigga niggah niggas niggaz nigger nigger niggers niggle niglet nimrod ninny nooky nympho opiate orgasm orgasmic orgies orgy ovary ovum ovums p.u.s.s.y. paddy paki pantie panties panty pastie pasty pcp pecker pedo pedophile pedophilia pedophiliac pee peepee penetrate penetration penial penile penis perversion peyote phalli phallic phuck pillowbiter pimp pinko polack pollock poon poontang porn porno prick prig prude pube pubic pubis punkass punky puss pussies pussy pussypounder puto queaf queef queef queer queero queers quicky quim racy raunch rectal rectum rectus reefer reetard reich retard retarded revue rimjob ritard rtard r-tard rum rump rumprammer ruski s.h.i.t. s.o.b. s0b scag scantily schizo schlong scrog scrot scrote scrud scum seaman seamen semen sexy sexuality sexiest sexual sex sexual sh1t s-h-1-t shamedame shit s-h-i-t shite shiteater shitface shithead shithole shithouse shits shitt shitted shitter shiz sissy skag skank slave sleaze sleazy slut slutdumper slutkiss sluts smegma smut smutty snatch sniper snuff s-o-b sodom souse soused sperm spic spick spik spiks spooge spunk steamy stfu stiffy stoned suck sucked sucking sumofabiatch t1t tampon tard tawdry teabagging teat terd teste testee testes testicle testis tinkle tit titfuck titi tits tittiefucker titties titty tittyfuck tittyfucker toke toots tramp tubgirl turd tush twat twats ugly undies unwed urinal urine uterus uzi vag vagina vixen vulgar vulva wad wang wank wanker wazoo wedgie weed weenie weewee weiner wench wetback wh0re wh0reface whitey whoralicious whorealicious whored whoreface whorehopper whorehouse whores whoring wigger wop wtf x-rated xxx yeasty yobbo zoophile";
		myHilitor.apply(words);
		
		</script>';
		
	}

	private function update_status($arr)
	{
		foreach ($arr as $key => $value) {
			$this->db->query($value);
		}
	}
}

