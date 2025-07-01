<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Playlist extends CI_Controller {
	
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
		if(!is_login() && !is_admin()) {    
			redirect(base_url());
		}
		$this->load->library(array('audition_functions','dashboard_function','form_validation','query_builder','share_url_encryption')); 
		$this->load->helper(array('button','aws_s3_action')); 
		$this->uid = is_login();
	}
	
	public function index(){
		$data['page_info'] = array('page'=>'playlist','title'=>'Playlist');
		$this->load->view('backend/include/header',$data);
		$this->load->view('backend/playlist');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
	}
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	function single_error_msg(){
		$errors = array_values($this->form_validation->error_array());
		return isset($errors[0])?$errors[0]:'';
	}

	function show_playlist_details(){
		
		if(isset($_GET['length'])){
			$currency 	=  $this->common->currency;
			$data 		= array();
			$search 	= trim($_GET['search']);
			
			$colm = 1;
			$order = 'DESC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm = $_GET['order'][0]['column'];
				$order = $_GET['order'][0]['dir'];								
			}
			
			$start = $_GET['start'];
			
			$filed = array(null,'channel_post_thumb.image_name','channel_video_playlist.title','channel_video_playlist.created_at','channel_video_playlist.privacy_status','channel_video_playlist.first_video_id','channel_video_playlist.video_ids','channel_video_playlist.playlist_id','channel_post_video.user_id');
			
			$join  = array
						(
							'multiple',
							array(
								array('channel_post_video','channel_post_video.post_id = channel_video_playlist.first_video_id','left'),
								array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id AND channel_post_thumb.active_thumb =1',
								'left'),
							)
						);
						
			$cond = 'channel_video_playlist.user_id = '.$this->uid.' AND channel_video_playlist.playlist_type = 2'; 
			
			if(isset($_GET['privacy_status'])  && !empty($_GET['privacy_status'])){
				$cond .=" AND channel_video_playlist.privacy_status = " . $_GET['privacy_status'];
			}
		
			$cond .= ' AND (';
			for($i=0;$i < sizeof($filed); $i++){
				if($filed[$i] != ''){
					$cond .= "$filed[$i] LIKE '%".$search."%'";
					if(sizeof($filed) - $i != 1){
						$cond .= ' OR ';
					}	
				}
				
			}
			$cond .= ')';
			
			$resultData = $this->DatabaseModel->select_data($filed,'channel_video_playlist',$cond,array($_GET['length'],$start) ,$join,array($filed[$colm],$order));
			
			$leadsCount =	$this->DatabaseModel->aggregate_data('channel_video_playlist','playlist_id','COUNT',$cond,$join);
			
			$errimg	= 	thumb_default_image();
			
			foreach($resultData as $list){
				
					$start++;
					
					$FilterData 		= 	$this->share_url_encryption->FilterIva($list['user_id'],0,$list['image_name'],'',true);
					$img 				= 	$FilterData['thumb'];
					$webp 				= 	isset($FilterData['webp'])?$FilterData['webp']:$img;
					
					
					$select 			= 	'';
										
					$post_status 	= 	$this->audition_functions->post_status();
					
					$selected		=	$option  = '';
					foreach($post_status as $value => $key){
						$selected 	= 	( $value == $list['privacy_status']) ? 'selected' : '';
						$option    .=	'<option '.$selected.' value="'.$value.'">'.$key.'</option>';
					}
						
					$select 		= 	'<select class="dash_selectbox_without_search ChangeOption" 
											name="privacy_status" data-url="backend/advertising/updateCheckStatus/channel_video_playlist" 
												data-id="'.$list['playlist_id'].'">
											'.$option.'
										 </select>';
										 
					$href 	= 	$this->share_url_encryption->share_single_page_link_creator(2 .'|'.$list['first_video_id'],'encode','',array('list'=> $list['playlist_id']));
					$edithref = base_url('playlist/').$list['playlist_id'];
					$video_ids_count = sizeof(explode('|',$list['video_ids'])) - 1;
					
					$featured_option = '<li>
											<a class="dtvShareMe common_click"  data-share="2|'.$list['first_video_id'].'|'.$list['playlist_id'].'" data-share-embedlist="'.base_url('embedcv/'.$list['first_video_id'].'/'.$list['playlist_id']).'">
												<span class="drop_icon">
													<svg xmlns="http://www.w3.org/2000/svg" width="13px" height="15px">
													<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
													 d="M10.610,15.002 L2.375,15.002 C1.130,15.002 -0.005,13.988 -0.005,12.875 L-0.005,2.123 C-0.005,1.010 1.130,-0.004 2.375,-0.004 L8.232,-0.004 C8.747,-0.004 9.244,0.202 9.596,0.560 L12.485,3.503 C12.810,3.834 12.990,4.266 12.990,4.720 L12.990,12.875 C12.990,13.988 11.855,15.002 10.610,15.002 ZM9.147,1.620 L9.147,2.624 C9.147,3.070 9.616,3.898 10.061,3.898 L11.294,3.898 L9.147,1.620 ZM11.523,5.225 L10.061,5.225 C8.793,5.225 7.680,4.237 7.680,3.110 C7.680,3.110 7.673,2.357 7.679,1.323 L2.375,1.323 C1.906,1.323 1.462,1.712 1.462,2.123 L1.462,12.875 C1.462,13.287 1.906,13.675 2.375,13.675 L10.610,13.675 C11.079,13.675 11.523,13.287 11.523,12.875 L11.523,5.225 ZM8.962,11.276 L3.575,11.276 C3.171,11.276 2.842,10.979 2.842,10.614 C2.842,10.248 3.171,9.951 3.575,9.951 L8.962,9.951 C9.367,9.951 9.696,10.248 9.696,10.614 C9.696,10.979 9.367,11.276 8.962,11.276 ZM8.962,8.841 L3.575,8.841 C3.171,8.841 2.842,8.543 2.842,8.177 C2.842,7.812 3.171,7.515 3.575,7.515 L8.962,7.515 C9.367,7.515 9.696,7.812 9.696,8.177 C9.696,8.543 9.367,8.841 8.962,8.841 ZM6.916,6.374 L3.575,6.374 C3.171,6.374 2.842,6.077 2.842,5.712 C2.842,5.346 3.171,5.049 3.575,5.049 L6.916,5.049 C7.320,5.049 7.649,5.346 7.649,5.712 C7.649,6.077 7.320,6.374 6.916,6.374 Z"/>
													</svg>
													</span>
												Share
											</a>	
										</li>
										<li>
											<a onclick="window.location.href=\''.$edithref.'\'">
												<span class="drop_icon">
													<svg xmlns="http://www.w3.org/2000/svg" width="13px" height="15px">
													<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
													 d="M10.610,15.002 L2.375,15.002 C1.130,15.002 -0.005,13.988 -0.005,12.875 L-0.005,2.123 C-0.005,1.010 1.130,-0.004 2.375,-0.004 L8.232,-0.004 C8.747,-0.004 9.244,0.202 9.596,0.560 L12.485,3.503 C12.810,3.834 12.990,4.266 12.990,4.720 L12.990,12.875 C12.990,13.988 11.855,15.002 10.610,15.002 ZM9.147,1.620 L9.147,2.624 C9.147,3.070 9.616,3.898 10.061,3.898 L11.294,3.898 L9.147,1.620 ZM11.523,5.225 L10.061,5.225 C8.793,5.225 7.680,4.237 7.680,3.110 C7.680,3.110 7.673,2.357 7.679,1.323 L2.375,1.323 C1.906,1.323 1.462,1.712 1.462,2.123 L1.462,12.875 C1.462,13.287 1.906,13.675 2.375,13.675 L10.610,13.675 C11.079,13.675 11.523,13.287 11.523,12.875 L11.523,5.225 ZM8.962,11.276 L3.575,11.276 C3.171,11.276 2.842,10.979 2.842,10.614 C2.842,10.248 3.171,9.951 3.575,9.951 L8.962,9.951 C9.367,9.951 9.696,10.248 9.696,10.614 C9.696,10.979 9.367,11.276 8.962,11.276 ZM8.962,8.841 L3.575,8.841 C3.171,8.841 2.842,8.543 2.842,8.177 C2.842,7.812 3.171,7.515 3.575,7.515 L8.962,7.515 C9.367,7.515 9.696,7.812 9.696,8.177 C9.696,8.543 9.367,8.841 8.962,8.841 ZM6.916,6.374 L3.575,6.374 C3.171,6.374 2.842,6.077 2.842,5.712 C2.842,5.346 3.171,5.049 3.575,5.049 L6.916,5.049 C7.320,5.049 7.649,5.346 7.649,5.712 C7.649,6.077 7.320,6.374 6.916,6.374 Z"/>
													</svg>
													</span>
												Edit
											</a>	
										</li>
										';					 
											 
					
					
					$title = (strlen($list['title'])< 20)?$list['title']:substr($list['title'],0,20)."...";
					
					array_push($data , array(
											'<div class="tbl_serialno">
											'.$start.'.
											</div>
											<div class="tbl_checkbox" >
												<input type="checkbox" name="playlist_id" id="'.$list['playlist_id'].'" class="checked_video" value="'.$list['playlist_id'].'">
												<label for="'.$list['playlist_id'].'"></label>
											</div>',
											'<div class="table_preview">
												<img src="'.$webp.'" alt="preview" onError="ImageOnLoadError(this,\''.$img.'\',\''.$errimg.'\')">
												<a target="_blank" href="'.$href.'">
												<svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" viewBox="0 0 20 25">
												  <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAZCAMAAAAGyf7hAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAA21BMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8AAADeEzS0AAAAR3RSTlMAI6WcPzbyOgVDbn5KKoHpuRk74Z4dQiDKuhiQsiQ1WDTQvDj0+UwpFN6GX2rdof0x9axX1mIC8fsKSWWAkrG/RyFnjqSVb1myQygAAAABYktHRACIBR1IAAAACXBIWXMAAAsSAAALEgHS3X78AAAAtUlEQVQoz53R1xKCMBAF0CuiIogFexd77733/P8fObhEg8OT9+numcwkswGEeKR3vKJBZhQH+sj8f2LAiUpQ1bSQLqISjlhDNCagEachkfxiyn6diGnmghkXND7GsjmOeVsKxVK5wtGkUoWsoEa9jgaVJhhrtfnJDpUwuj30qQ8wpDIqA+MJ9Slm/O65ydsCyxX7yXoDbH9xZ+1j7zSJlqmKduAbrxw5nc7CR1yut7v0eOo0vQD8IVR/tKozOwAAAABJRU5ErkJggg==" width="20" height="25"/>
												</svg>
												</a>
											</div>',
											remove_special_char($title) ,
											$video_ids_count,
											date('F d,y',strtotime($list['created_at'])),
											$select,
											'<span data-toggle="dropdown" class="table_actionboxs" data-video="'.$list['playlist_id'].'" >
													<svg xmlns="https://www.w3.org/2000/svg" width="15px" height="4px" >
													<path fill-rule="evenodd"  fill="rgb(168, 170, 180)"
													d="M13.031,4.000 C11.944,4.000 11.062,3.104 11.062,2.000 C11.062,0.895 11.944,-0.000 13.031,-0.000 C14.119,-0.000 15.000,0.895 15.000,2.000 C15.000,3.104 14.119,4.000 13.031,4.000 ZM7.500,4.000 C6.413,4.000 5.531,3.104 5.531,2.000 C5.531,0.895 6.413,-0.000 7.500,-0.000 C8.587,-0.000 9.469,0.895 9.469,2.000 C9.469,3.104 8.587,4.000 7.500,4.000 ZM1.969,4.000 C0.881,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.881,-0.000 1.969,-0.000 C3.056,-0.000 3.937,0.895 3.937,2.000 C3.937,3.104 3.056,4.000 1.969,4.000 Z"/>
													</svg>
												<ul class="action_drop">
													'.$featured_option.'
												</ul>
											</span>'
									)); 
			
			}
			
			echo json_encode(array( 
				'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
				'recordsTotal' => $leadsCount,
				'recordsFiltered' => $leadsCount,
				'data' => $data, 
				));
				
			
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
			
			$data['playlist'] = $this->DatabaseModel->select_data("channel_video_playlist.first_video_id,channel_post_video.user_id,channel_video_playlist.playlist_id,channel_video_playlist.title,channel_video_playlist.video_ids,channel_video_playlist.created_at,channel_video_playlist.privacy_status,channel_post_thumb.image_name",'channel_video_playlist',$wherePlaylist,1,$joinsPlaylist,$OrderPlaylist);
			
			$data['page_info'] = array('page'=>'playlist','title'=>'My playlist');
			
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/playlist',$data); 
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer');
		}else{
			redirect(base_url('home/four_zero_four'));
		}
	}
	
	function getMyPlaylist(){
	
		if(is_admin()){
			
			$resp 	= array();
			
			$rules = array(
				array( 'field' => 'post_id', 'label' => 'Post', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$post_id 	= $this->input->post('post_id');

				$videoDetails 		= $this->DatabaseModel->select_data('user_id','channel_post_video use INDEX(post_id)',array('post_id'=>$post_id),1);
				$uid 				= isset($videoDetails[0]['user_id'])?$videoDetails[0]['user_id'] : '';

				$playlist_ids 		= $this->DatabaseModel->select_data('*','channel_video_playlist',['user_id'=>$uid,'playlist_type'=>2]);
				
				$playlist = [];
				if(!empty($playlist_ids)){
					foreach($playlist_ids as $list){
						$video_ids 	= explode('|',$list['video_ids']);
						$checked 	= (in_array($post_id ,$video_ids ))? 'checked' : '';
						$playlist[] = ['playlist_id'=>$list['playlist_id'],'title'=>$list['title'],'checked'=>$checked,'video_ids_count'=>(sizeof($video_ids)-1)];
						$this->statusCode  =  1;
					}
				}else{
					$this->statusCode  =  1;
					$this->respMessage  =  "No Playlist Available.";
				}			
				$resp['playlist']  = $playlist ;
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]	:	'';
			}
			

		}else{
			
			$this->load->library('manage_session');
			$this->load->library('creator_jwt');
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
					$playlist_ids 	= $this->DatabaseModel->select_data('*','channel_video_playlist',['user_id'=>$uid,'playlist_type'=>2]);
					
					$playlist = [];
					if(!empty($playlist_ids)){
						foreach($playlist_ids as $list){
							$video_ids 	= explode('|',$list['video_ids']);
							$checked 	= (in_array($post_id ,$video_ids ))? 'checked' : '';
							$playlist[] = ['playlist_id'=>$list['playlist_id'],'title'=>$list['title'],'checked'=>$checked,'video_ids_count'=>(sizeof($video_ids)-1)];
							$this->statusCode  =  1;
						}
					}else{
						$this->statusCode  =  1;
						$this->respMessage  =  "No Playlist Available.";
					}			
					$resp['playlist']  = $playlist ;
				}else{
					$errors = array_values($this->form_validation->error_array());
					$this->respMessage  =  isset($errors[0])?$errors[0]	:	'';
				}
			}else{
				$this->respMessage = $TokenResponce['message'];
			}
		}
		$this->show_my_response($resp);
	}
	
	function createNewPlaylist(){
		$this->load->library('manage_session');
		$this->load->library('creator_jwt');
		$uid 	= $this->uid;
		$resp 	= array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1){ 
			$rules = array(
				array( 'field' => 'playlistTitle', 'label' => 'Playlist Title', 'rules' => 'trim|required'),
				array( 'field' => 'PlayListStatus', 'label' => 'Playlist Status', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$title 	= $this->input->post('playlistTitle');
				$list = ['user_id' 			=> $uid,
							 'title'   			=> $title,
							 'privacy_status'   => $this->input->post('PlayListStatus'),
							 'created_at'   	=> date('Y-m-d H:i:s'), 
							 'playlist_type'    => 2    //User created playlist
							 ];
				$playlist_id = $this->DatabaseModel->access_database('channel_video_playlist','insert',$list);
				
				$playlist = [];
				if($playlist_id){
					$playlist[] = ['playlist_id'=> $playlist_id,'title'=> $title,'checked'=>'', 'video_ids_count'=>0];
					$this->statusCode  =  1;
				}else{
					$this->respMessage  =  'Something went wrong ! please try again.';
				}
				$resp['playlist']  = $playlist ;
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]	:	'';
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
			$privacy_status = $this->input->post('privacy_status');
			
			$playlist= $this->DatabaseModel->select_data('*','channel_video_playlist',array('playlist_id'=>$playlist_id),1);
			if(!empty($playlist)){
				
				if($action_type =='addToPlaylist') {
					
					$video_ids = explode('|' , $playlist[0]['video_ids']);
					if(sizeof($video_ids)<(PLAYLIST_VIDEO_LIMIT+1)){   // CONSTANT DEFINED
						if(!in_array($video_id, $video_ids)){
							
							$video_items = array_unique(array_merge($video_ids, [$video_id] ));
							
							$first_vid = array_values(array_filter($video_items));
									
							$updateList['first_video_id']  = isset($first_vid[0])? $first_vid[0] : 0;
									
							$updateList['video_ids'] = implode('|',$video_items);
							
							$this->respMessage  = 'Video added to playlist successfully.';
						}else{
							$this->respMessage  = 'Video already added to playlist.';
							$this->statusCode  	= 1;
							$this->statusType   = 'Success';
						}
					}else{
						$this->respMessage  = 'You have reached the maximum limit.';
						$this->statusCode  	= 0;
					}
				}else if($action_type =='removeVideo') {
					
					$video_ids = $playlist[0]['video_ids'];
					if(!empty($video_ids)){
						$vids =  explode('|' , $video_ids);
						if($key = array_search($video_id, $vids)){
							
							unset($vids[$key]);
							
							$v = array_values($vids);
							
							$first_vid = array_values(array_filter($v));
							
							$updateList['first_video_id']  = isset($first_vid[0])? $first_vid[0] : 0;
							
							$updateList['video_ids'] 	   = implode('|',$v);
							
							$this->respMessage  = 'Video removed successfully.';
							
						}else{
							$this->respMessage  = 'This video not found in playlist.';
						}
					}else{
						$this->respMessage  = 'playlist is empty.';
					}
					
				}else if($action_type =='re-ordered'){
					
					$reorder_list = json_decode($_POST['reorder_list'],true);
					$updateList['first_video_id'] =isset($reorder_list[0])? $reorder_list[0] : 0;
					array_unshift($reorder_list, '');
					$updateList['video_ids'] = implode('|',$reorder_list);
					$this->respMessage  = 'Playlist orders updated successfully.';	
					
				}else if($action_type =='statusChange'){
					
					$updateList['privacy_status'] = $privacy_status;
					$this->respMessage  = 'Privacy status updated successfully.';	
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
	
	public function deletePlaylist(){
		$resp=[];
		$this->form_validation->set_rules('playlist_id', 'playlist id', 'trim|required');
		if ($this->form_validation->run() == false){
			$this->respMessage  =  $this->single_error_msg();
		}else{
			$cond = "playlist_id IN({$_POST['playlist_id']})";
			
			$this->DeletePlaylistThumb($_POST['playlist_id']); //Delete previous thumb image
			
			$delete = $this->DatabaseModel->access_database('channel_video_playlist','delete','',$cond );
			
			if($delete){
				$usreName 			= isset($_SESSION['user_uname'])? $_SESSION['user_uname'] : '';
				$resp['redurl']		= base_url('channel?user='.$usreName);
				$this->statusCode  	= 1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Playlist deleted successfully.';
			}else{
				$this->respMessage  = 'Something went wrong.';
			}
		}
		$this->show_my_response($resp);
	}
	
	
	public function deleteVideoFromPlaylist($video_id='', $user_id=''){
		if(!empty($video_id) && !empty($user_id)){
			
			$playlist= $this->DatabaseModel->select_data('*','channel_video_playlist',array('user_id'=>$user_id));
			if(!empty($playlist)){
				
				foreach($playlist as $p){
				
					$video_ids =  $p['video_ids'];
					if(!empty($video_ids)){
						$vids =  explode('|' , $video_ids);
						if($key = array_search($video_id, $vids)){
							
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
		
			//$playlist_ids 	= $this->DatabaseModel->select_data('*','channel_video_playlist',['user_id'=>$uid]);
			
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
	
	function upload_playlist_thumb($type = NULL){
		$this->load->library('manage_session');
		if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
			$uid = $this->uid;
			
			$config['upload_path'] 	 = './uploads/aud_'.$uid.'/images/';
			$config['encrypt_name']  = true;
			$config['allowed_types'] = 'jpg|png|gif|jpeg';
			$config['max_size']      = 8192 ;
			// $config['min_width']     = 640 ;
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
					
						$playlist_id = trim($_POST['playlist_id']); 

						$this->DeletePlaylistThumb($playlist_id); //Delete previous thumb image
						
						$this->DatabaseModel->access_database('channel_video_playlist','update',['playlist_thumb' => $ImgNam], ['playlist_id' => $playlist_id ] );
						
						upload_all_images($uid);
						
						$thumbimg = AMAZON_URL.'aud_'.$uid.'/images/'.$name.'_thumb'.$img_ext;
						
						echo json_encode(array('playlist_id'=>$playlist_id,'name'=>$thumbimg));
						
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
	
	public function DeletePlaylistThumb($playlist_id){
		$this->load->library('manage_session');
		$uid = $this->uid;
		
		if(isset($playlist_id) && !empty($uid) ){
			
			$data_array = array('playlist_id'=>$playlist_id);
			$previous 	= $this->DatabaseModel->select_data('playlist_thumb','channel_video_playlist',$data_array);
		
			if( !empty($previous) ){
				if( $previous[0]['playlist_thumb'] != '' ){
					$kpath = 'aud_'.$uid.'/images/';
					$img = explode('.',$previous[0]['playlist_thumb']);
					$img = $img[0].'_thumb.'.$img[1];
					s3_delete_object(array($kpath.$previous[0]['playlist_thumb'],$kpath.$previous[0]['playlist_thumb'].'.webp',$kpath.$img,$kpath.$img.'.webp' ));
					return 1;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	
	
	Function removePlaylistThumb(){
		$resp=[];
		$this->form_validation->set_rules('playlist_id', 'playlist id', 'trim|required');
		if ($this->form_validation->run() == false){
			
			$this->respMessage  =  $this->single_error_msg();
			
		}else{
			$playlist_id = $_POST['playlist_id'];
			if($this->DeletePlaylistThumb($playlist_id) > 0){ //Delete previous thumb image.
				$this->DatabaseModel->access_database('channel_video_playlist','update',['playlist_thumb' =>''], ['playlist_id' => $playlist_id ] );
				$this->statusCode  	= 1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Image removed successfully.';
			}else{
				$this->respMessage  = 'Something went wrong please try again.';
			}
		}
		$this->show_my_response();
	}
	
	
}
?>
