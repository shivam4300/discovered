<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends CI_Controller {
	public $uid;
	public $statusCode 	= 	'';
	public $respMessage = 	'';

	public function __construct()
	{
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->uid = is_login();
	}

	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}

	function AddViewcount(){
		if(isset($_POST['post_id']) && !empty($_POST['post_id']) && isset($_POST['user_id']) && !empty($_POST['user_id'])){
			
			$post_id = (int) base64_decode($_POST['post_id']);
			$user_id = (int) base64_decode($_POST['user_id']);
			
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
			echo json_encode(array('status'=>1));
		}
	}
	
	
	public function getRelatedVideo(){ 
		$this->load->library(array('share_url_encryption')); 
		$slid = '';
		if(isset($_POST['uid']) && !empty($_POST['uid'])){
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where = "channel_post_video.user_id = ".$_POST['uid']." AND " . $this->common->channelGlobalCond([1 , 1,  7 , 0 , 1]);
			// echo $where;die;
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id','right'),
								// array('users' , 'users.user_id = channel_post_video.user_id')
							)
						);
			
			// $order 	= ['channel_post_video.post_id','DESC'];
			$order 	= 'rand()';
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.post_id,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration';

			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(user_id)',$where,array($_POST['limit'],$_POST['start']),$join,$order);			
			// print_r($videoData );die;
			if(isset($videoData[0])){
				$slid = $this->common_html->loadModeVideo($videoData,$thumb = true,$routePlayer=false);					
			}	
		}
		echo $slid;
	} 
	
	public function getRelatedVideoNew(){ 
		$this->load->library(array('share_url_encryption')); 
		$data = [];
		if(isset($_POST['uid']) && !empty($_POST['uid'])){
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where = "channel_post_video.user_id = ".$_POST['uid']." AND " . $this->common->channelGlobalCond([1 , 1,  7 , 0 , 1]);
			
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id','right'),
								// array('users' , 'users.user_id = channel_post_video.user_id')
							)
						);
			
			// $order 	= ['channel_post_video.post_id','DESC'];
			$order 	= 'rand()';
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.post_id,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration';

			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(user_id)',$where,array($_POST['limit'],$_POST['start']),$join,$order);			
			
			$data = $this->common_html->loadModeVideoNew($videoData,$thumb = true);		
		}
		
		echo json_encode(array('status'=>1,'data'=>$data));
	} 
	
	function LoadPlayerNextVideo(){
		
		if(isset($_POST['uid']) && !empty($_POST['uid'])){
			
			$field = 'channel_post_video.tag,channel_post_thumb.image_name,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.title,channel_post_video.post_id,channel_post_video.genre';
			
			$join 	= array('multiple' , array(
									array(	'channel_post_thumb',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
									array(	'users', 
											'users.user_id 				= channel_post_video.user_id', 
											'inner'),
						));
			
			$globalcond = $this->common->channelGlobalCond();
								
			$limit	= [$_POST['limit'],$_POST['start']];
			
			if(isset($_POST['list']) && !empty($_POST['list'])){
				$this->load->library('Share_url_encryption');
				$id = $this->share_url_encryption->share_single_page_link_creator($_POST['list'],'decode');
				
				$playlists 	= $this->DatabaseModel->select_data('video_ids','channel_video_playlist',['playlist_id'=>$id]);
				
				if(isset($playlists[0]['video_ids']) && !empty($playlists[0]['video_ids'])){
					$playlists 		= implode(',',explode('|',$playlists[0]['video_ids']));
					$video_items 	= trim($playlists,',');
					
					$cond = $globalcond .' AND channel_post_video.post_id IN('.$video_items.')';
							
					$order = 'FIELD(channel_post_video.post_id, '.$video_items.')';
				}
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video',$cond,PLAYLIST_VIDEO_LIMIT,$join, $order );
			}else{
				
				$pids = $_POST['pid'];
				if(isset($_SESSION['Last_post_ids'])){
					
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
				$_SESSION['Last_post_ids'] = $pids;
				
				$Maincond 	= 	$globalcond .' AND channel_post_video.post_id NOT IN('.$pids.')';
				
				$mod 		= 	isset($_POST['mod']) && !empty($_POST['mod'])?$_POST['mod']:'1';
				$cat 		= 	isset($_POST['cat']) && !empty($_POST['cat'])?$_POST['cat']:'1';
				$gen 		= 	isset($_POST['gen']) && !empty($_POST['gen'])?$_POST['gen']:'0';
				$uid 		= 	isset($_POST['uid']) && !empty($_POST['uid'])?$_POST['uid']:'0';
				
				$cond2 		= ' AND  channel_post_video.mode 	= '.$mod.' 
								AND channel_post_video.genre 	= '.$gen.' 
								AND channel_post_video.user_id 	= '.$uid.'';
				
				$cond3 		= ' AND channel_post_video.mode 	= '.$mod.' 
								AND channel_post_video.genre 	= '.$gen.'';
								
				$cond4 		= ' AND channel_post_video.mode 	= '.$mod.'';
				
			
				$order 		= 'rand()'; 
				
				$cond		= $Maincond . $cond3 ;
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond, $limit  ,$join, $order);
				
				if(empty($next_video)){
					$cond		= $Maincond.$cond3 ;
					$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,$limit ,$join, $order );
				}
				
				if(empty($next_video)){
					$cond		= $Maincond .$cond4 ;
					$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,$limit ,$join, $order );
				}
			}
			
			if(empty($next_video)){
				$order 		= array('channel_post_video.post_id','DESC');
				$next_video = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$globalcond,$limit,$join, $order );
			}
			
			if(!empty($next_video)){
				echo json_encode(array('status'=>1,'data'=>$next_video,'AMAZON_URL' => AMAZON_URL));				
			}else{
				echo json_encode(array('status'=>0,'data'=>''));
			}
		}
	}
	public function getCastCrew(){
		if(isset($_POST['pid']) && !empty($_POST['pid'])){
			$resp = array('status'=>1,'data'=>$this->common_html->castAndCrew($_POST['pid']));
		}else{
			$resp = array('status'=>0);
		}
		echo json_encode($resp);
	}

	public function getStream(){
		if($this->input->is_ajax_request()){
			if(isset($_POST['uid']) && !empty($_POST['uid'])){           // For Media Live
				$field = 'count_views,FST';
				$join = array('multiple' , array(
								array(	'channel_post_video',
										'channel_post_video.post_id = users_medialive_info.live_pid',
										'left'),
							));
				$cond = 'users_medialive_info.is_live = 1 AND users_medialive_info.user_id = '.$_POST['uid'].'';
				$minfo = $this->DatabaseModel->select_data($field,'users_medialive_info',$cond,1,$join);
				
				$data = ['state'=>'Offline','health'=>'NA','viewerCount'=>0,'duration'=>0];
				if(!empty($minfo) && isset($minfo[0])){
					$start 	= $minfo[0]['FST'];
					$end 	= gmdate("Y-m-d H:i:s");
					
					if($start != NULL){
						$this->load->helper('my');
						$r 	= caculate_time_differece($end,$start);
						
						$data['state'] 			= 	'LIVE';
						$data['health'] 		= 	'GOOD';
						$data['viewerCount']	= 	$minfo[0]['count_views'];
						$data['duration'] 		=  	$r['hours'] .':'. $r['minutes'] .':'. $r['seconds']; 
					}
				}
				echo json_encode(array('status'=>1,'data'=>$data));
				
			}elseif(isset($_POST['arn']) && !empty($_POST['arn'])){              // For IVS
				$this->load->helper('aws_ivs_action');
				
				$cond = 'users_ivs_info.is_live = 1 AND users_ivs_info.user_id = '.$_POST['uid'].'';
				$ivsinfo = $this->DatabaseModel->select_data('channel_arn','users_ivs_info',$cond,1);
				if(!empty($ivsinfo) && isset($ivsinfo[0])){
					$stream = getStream($_POST['channel_arn']);
					echo json_encode($stream);
				}
				
			}
		}
	}
	public function getPostComments(){
		$data = [];
		if($this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('post_id', 'Video', 'trim|required');
			$this->form_validation->set_rules('parent_com_id', 'Parent', 'trim|required');
			$this->form_validation->set_rules('start', 'Start', 'trim|required'); 
			$this->form_validation->set_rules('limit', 'Limit', 'trim|required'); 
		 
			if ($this->form_validation->run()){
				$post_id 	= $_POST['post_id'];
				$parent_com_id 	= $_POST['parent_com_id'];
				$start 		= isset($_POST['start'])?$_POST['start']:0;
				$limit 		= isset($_POST['limit'])?$_POST['limit']:$limit;
				
				$com_id 	= isset($_POST['com_id'])?$_POST['com_id']:'';
				
				$join = array('multiple' , array(
					array(	'users_content',
							'users_content.uc_userid = channel_post_comment.user_id',
							'left'),
					array(	'users',
							'users_content.uc_userid = users.user_id',
							'left')
				));
				$where 	= 'post_id = '.$post_id.' AND parent_com_id = '.$parent_com_id;
				
				$limit  = [$limit+1,$start];
				
				if(!empty($com_id)){
					$where 	= ' com_id = ' . $com_id;
					$limit = 1;
				}
				
				$data['comments'] = $this->DatabaseModel->select_data('channel_post_comment.*,users_content.uc_pic,users.user_name,users.user_uname','channel_post_comment use INDEX(post_id)',$where,$limit,$join,['com_id','DESC']);  
				$this->respMessage 	=	'comments available.';
				$this->statusCode 	=	 1;
			}else{
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}
			$this->show_my_response($data);
		}
	}

	public function AddComment(){
		$this->load->library(['manage_session','creator_jwt']);

		$data = [];
		if($this->input->is_ajax_request()){

			$TokenResponce = $this->creator_jwt->MatchToken();
			if($TokenResponce['status'] == 1){ 
				$this->load->library('form_validation');
				$this->form_validation->set_rules('post_id', 'Video', 'trim|required');
				$this->form_validation->set_rules('parent_com_id', 'Parent', 'trim|required');
				$this->form_validation->set_rules('message', 'Comment', 'trim|required'); 
				
				if ($this->form_validation->run()){
					$data = array(
						'post_id' 		=> $this->input->post('post_id'),
						'user_id' 		=> $this->uid,
						'message' 		=> $this->input->post('message'),
						'parent_com_id' => $this->input->post('parent_com_id'),
						'com_date' 		=> date('Y-m-d H:i:s'),
					);
					$com_id = $this->DatabaseModel->access_database('channel_post_comment','insert',$data);

					if($data['parent_com_id'] != 0){
						$this->db->set('msg_count','`msg_count`+ 1',FALSE);
						$this->db->where('com_id',$data['parent_com_id']);
						$this->db->update('channel_post_comment');
					}
					$data['com_id']		= 	$com_id;
					$this->respMessage 	=	'Thanks for your comment :)';
					$this->statusCode 	=	 1;
				}else{
					$this->respMessage 	=	$this->common->form_validation_error()['message'];
				}
			}else{
				$this->respMessage = $TokenResponce['message'];
			}
			$this->show_my_response($data);
		}
	}
	
}


