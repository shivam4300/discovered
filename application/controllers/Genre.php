<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Genre extends CI_Controller {
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('share_url_encryption')); 
	}	

	function index(){
		if(isset($_GET['g']) && $_GET['g'] != 'all'){
			
			$data['most_popular_videos'] 	= [];	
			$data['top_videos_ofthe_month'] = [];
			$data['new_realeased_video'] 	= [];
			$data['other_video'] 			= [];
			
			$genre_slug = $_GET['g'];
			$level = isset($_GET['l'])? 'sub_genre' : 'genre';
			$list = $this->DatabaseModel->select_data('genre_id,genre_name','mode_of_genre',array('genre_slug'=>$genre_slug),1);
			
			$genre_name ='';
			
			if(!empty($list)){
				$_SESSION['genre_id'] = $genre_id = $list[0]['genre_id'];
				
				$genre_name = $data['genre_name'] = $list[0]['genre_name'];
			
				$field = 'channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.slug,channel_post_video.genre,mode_of_genre.genre_name,mode_of_genre.genre_slug,channel_post_video.iva_id,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration';
			
				$where = $this->common->channelGlobalCond() . " AND mode_of_genre.genre_id = ". $genre_id ."";
				
				

				$join = array('multiple',
							array(
							   array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id'),
							   array('users','users.user_id = channel_post_video.user_id'),
							   array('mode_of_genre','mode_of_genre.genre_id = channel_post_video.'.$level, 'left')
							)
						);
						
				$order 	= array('channel_post_video.count_views','DESC');
				$data['most_popular_videos'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,8,$join,$order);
				
				$order 	= array('channel_post_video.created_at','DESC');
				
				$where .= " AND channel_post_video.created_at LIKE '%".date('Y-m')."%' ";
				$data['new_realeased_video'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,8,$join,$order);
				
				if($level == 'genre'){
					$join  = array(
						'multiple',
						array(
							array('channel_post_video' , 'mode_of_genre.genre_id = channel_post_video.sub_genre'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
					
					$where = $this->common->channelGlobalCond([1,1,7,0,NULL,1,0]) . " AND mode_of_genre.mode_id =  ".mode()." AND mode_of_genre.parent_id = $genre_id AND mode_of_genre.status = 1";
					
					$data['sub_genres'] = $this->DatabaseModel->select_data('genre_name,genre_id,image,genre_slug','mode_of_genre',$where,'',$join,'','','channel_post_video.sub_genre');
				}
			}
			
			$data['page_info'] 		=  array('page'=>'single_genre','title'=>$genre_name);
			$data['cover_video'] 	=  $this->audition_functions->get_cover_video();
			
			if(!empty($data['most_popular_videos'])){
				$video 			= $this->audition_functions->isLiveStreamingVideo($data['most_popular_videos']);
				if(!empty($video)){
					$vid  		= 	$video['uploaded_video'] ;
					$url 		= 	AMAZON_URL .$vid;
					$key 		= 	explode('.',$vid);
					$folder 	= 	explode('/',$key[0]);
					$preview 	= 	($video['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$folder[2].'.mp4?q='.time()    :    $url  ;
					$post_key	=	base_url().$this->common->generate_single_content_url_param($video['post_key'] , 2);
					$data['cover_video'] =  [ array('user_id'=>$video['user_id'],'post_id'=>$video['post_id'],'url'=>$url,'title'=>$video['title'],'preview'=>$preview,'post_key'=>$post_key) ];
				}				
			}
			
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/genre/single_genre',$data);
			$this->load->view('home/inc/footer',$data);	
			
		}else{
			$join  = array(
						'multiple',
						array(
							array('channel_post_video' , 'mode_of_genre.genre_id = channel_post_video.genre'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
			
			$where = $this->common->channelGlobalCond([1,1,7,0,NULL,1,0]) . " AND mode_of_genre.mode_id =  ".mode()." AND mode_of_genre.status = 1";
					

			$data['mode_of_genre'] = $this->DatabaseModel->select_data('genre_name,genre_id,image,genre_slug','mode_of_genre',$where,'',$join,'','','channel_post_video.genre');
			
			$data['cover_video']	=  $this->audition_functions->get_cover_video(); 
			
			$data['page_info'] 		= array('page'=>'all_genre','title'=>'All '.' '.mode('name'));
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/genre/all_genre',$data);
			$this->load->view('home/inc/footer',$data);
		}
		
		
	}
	
	
	public function loadMoreGenre($mode_id = NULL){ 
		$genre = isset($_SESSION['genre_id'])?$_SESSION['genre_id']:'';
		
		if(isset($_POST['start_other'])){
			
			$where = $this->common->channelGlobalCond() . " AND channel_post_video.genre = ".$genre;
			
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id','right'),
								array('users' , 'users.user_id = channel_post_video.user_id')
							)
						);
			
			$order 	= ['channel_post_video.post_id','DESC'];
			
			$videoData = $this->DatabaseModel->select_data('channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.slug,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.post_id,channel_post_video.title,channel_post_thumb.image_name,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration','channel_post_video',$where,array($_POST['start_limit'],$_POST['start_other']),$join,$order,'','channel_post_video.post_id');			
			
			if(isset($videoData[0])){
				echo $this->common_html->loadModeVideo($videoData);					
			}
		}
	}
	
	function GetGenreSlider(){
		
		if(isset($_POST)){
		 $mode_id = mode();
			
			$condition = $this->common->channelGlobalCond([1,1,7,0,NULL,1,0]) . " AND mode_of_genre.mode_id = $mode_id AND mode_of_genre.status = 1";

			$join = array('multiple',
								array(
									array('channel_post_video','mode_of_genre.genre_id = channel_post_video.genre','RIGHT'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
			);
			$genres = $this->DatabaseModel->select_data('mode_id,genre_name,genre,genre_slug','mode_of_genre',$condition,array(1,$_POST['start']),$join,'','','channel_post_video.genre');
				// print_r($genres);die;
					$data	=	array();
					if(!empty($genres)){
						foreach ($genres as $genre){
							$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.slug,channel_post_video.uploaded_video,channel_post_video.video_duration,channel_post_video.is_video_processed,channel_post_video.video_type";
						
							$where = "genre = ".$genre['genre']." AND  channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
									   
							$join  = array(
											'multiple',
											array(
												array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
												array('users' , 'users.user_id = channel_post_video.user_id'),
											)
										);
							
							$order 	= array('channel_post_video.count_views','DESC'); 
													
							$limit = 10;
							
							$genreVideo = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
							
							if(isset($genreVideo[0])){
								
								$color = ($_POST['start']%2 == 0)? "bg-white" : "";
								
								$autoplay=array("2000","2500","3000");
								$random_keys=array_rand($autoplay,1);
								$auto = $autoplay[$random_keys];
								
								$href = '<a href="'.base_url("genre?g=".$genre["genre_slug"]).'" class="dis_sh_btn muli_font">See all
								<span class="dis_sh_btnicon">
									<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
									<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
									</svg>
								</span>
								</a>';
								
								$htmlarray = array(	'color'=>$color,
													'title'=>$genre["genre_name"],
													'href' =>$href,
													'auto' =>$auto, 
													'videoData'=>$this->common_html->swiper_slider_without_html($genreVideo)
											);
								
								array_push($data,$htmlarray);
							}else{
								$resp = array('status'=>0,'data'=>$data);
							}
						}
						$resp = array('status'=>1,'data'=>$data);
					}else{
						$resp = array('status'=>0,'data'=>$data);	
					}
				echo json_encode($resp);
		}	
		
	}
	
	
	
	
	
	
	

	
	
}
?>