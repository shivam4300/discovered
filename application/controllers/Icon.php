<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Icon extends CI_Controller {
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('share_url_encryption')); 
	}	

	function index(){
		$mode_id = mode();
		if(isset($_GET['iv']) && $_GET['iv'] != 'all'){
			$category_slug = $_GET['iv'];
			
			$list 	= $this->DatabaseModel->select_data('category_id,category_name','artist_category',array('category_slug'=>$category_slug,'parent_id' => 1 ),1);
			$category_name ='';
			if(!empty($list)){
				$_SESSION['category_slug'] = $category_slug ;
				
				$_SESSION['category_id'] = $category_id = $list[0]['category_id'];
				$category_name =	$data['category_name'] = $list[0]['category_name'];
			
				$data['most_popular_videos'] 	= [];	
				$data['top_videos_ofthe_month'] = [];
				$data['new_realeased_video'] 	= [];
				$data['other_video'] 			= [];
				
				$accessParam = array(
					'field' => 'channel_post_video.slug,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.post_id,channel_post_video.title,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.user_id,channel_post_video.video_duration',
					'where' => 'category='.$category_id.',privacy_status=7,user_status=1',
					'order' => 'channel_post_video.count_views,DESC',
					'limit' => 20,
				);
						
				$CategoryVideo	= $this->query_builder->channel_video_list($accessParam);
				if(isset($CategoryVideo['channel'])){
					$data['most_popular_videos'] =$CategoryVideo['channel'];
				}
				
				$accessParam['order'] = "'channel_post_video.created_at','DESC'";
				$CategoryVideo	= $this->query_builder->channel_video_list($accessParam);
				if(!empty($CategoryVideo['channel'])){
					$data['new_realeased_video'] = $CategoryVideo['channel'];
				}
				
				
				$accessParam['limit'] = 4;
				// $CategoryVideo	= $this->query_builder->channel_video_list($accessParam);
				// if(!empty($CategoryVideo['channel'])){
				// 	$data['other_video'] = $CategoryVideo['channel'];
				// }
				
				$accessParam['where'] = 'category='.$category_slug.',privacy_status=7,user_status=1';
				$accessParam['limit'] = 20;
				$CategoryVideo	= $this->query_builder->channel_video_list($accessParam);
				if(!empty($CategoryVideo['channel'])){
					$data['top_videos_ofthe_month'] =$CategoryVideo['channel'];
				}
			}
			$data['page_info'] 		= array('page'=>'single_category','title'=>$category_name);
			$coverVideo = array('music','movies','television','gaming');
			$coverRand  = array_rand($coverVideo);
			$data['cover_video'] =  $this->audition_functions->get_cover_video();
			
			if(!empty($data['most_popular_videos'])){
				$video 			= $this->audition_functions->isLiveStreamingVideo($data['most_popular_videos']);
				
				if(!empty($video)){
					$uid = $video['user_id'];
					$file = explode('.',$video['uploaded_video'])[0];
					$file = explode('aud_'.$uid.'/videos/',$file);
					
					$path = trim('aud_'.$uid.'/videos/'.$file[1].'/'.$file[1].'.mp4');
					//$this->load->helper(array('aws_s3_action')); 
					
					//if(DoesObjectExist($path,'discovered.tv.transcoder.new','us-east-1') == 1){
						$vid  		= 	$video['uploaded_video'];
						$video_type = 	$video['video_type'] ;
						$url 		= 	AMAZON_URL .$vid;
						$key 		= 	explode('.',$vid);
						$folder 	= 	explode('/',$key[0]);
						$preview 	= 	($video['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$folder[2].'.mp4?q='.time()    :    $url  ;
						$post_key	=	base_url().$this->common->generate_single_content_url_param($video['post_key'] , 2);
						$data['cover_video'] =  [ array('user_id'=>$video['user_id'],'post_id'=>$video['post_id'],'url'=>$url,'title'=>$video['title'],'preview'=>$preview,'post_key'=>$post_key) ]; 
					//}
					
				}
			}
			 
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/icon/single_icon',$data);
			$this->load->view('home/inc/footer',$data);	
		}else{
			
			$join = array(
							'multiple',
							array(
								array('users' , 'users.user_id = channel_post_video.user_id'),
							)
						);

			$where = "channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND users.user_status = '1'";
		
			$GROUP_BY = "channel_post_video.category";
		 
			$CategoryVideo_all =  $this->DatabaseModel->select_data('channel_post_video.post_id','channel_post_video use INDEX(post_id)',$where,10,$join,array('channel_post_video.count_views','DESC'),'',$GROUP_BY);

			$cate_post_id = array_column($CategoryVideo_all, 'post_id');
			$cate_post_id = implode(',',$cate_post_id);
			$cate_post_id = !empty($cate_post_id) ? $cate_post_id : 0 ;

			$cond = "channel_post_video.post_id IN($cate_post_id) AND channel_post_thumb.active_thumb = 1";
				
			$field = "channel_post_video.slug,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.post_id,channel_post_video.title,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration";

			$join = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id','left')
							)
				);
			
			$CategoryVideo = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,'',$join);


			/*$accessParam = array(
									'field' 	=> 'channel_post_video.slug,channel_post_video.post_key,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.post_id,channel_post_video.title,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.is_video_processed,channel_post_video.video_type',
									'where' 	=> 'privacy_status=7,user_status=1',
									'order' 	=> 'channel_post_video.count_views,DESC',
									'limit' 	=> 20,
									'group_by'	=>'channel_post_video.category'
									);
									
			$CategoryVideo	= $this->query_builder->channel_video_list($accessParam);*/
			if(!empty($CategoryVideo)){
				$data['category_video_by_group'] =$CategoryVideo;
			}
			
			$data['page_info'] 		= array('page'=>'all_category','title'=>'All Category');
			// $coverVideo = array('music','movies','television','gaming');
			// $coverRand  = array_rand($coverVideo);
			$data['cover_video'] =  $this->audition_functions->get_cover_video();  
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/icon/all_icon',$data);
			$this->load->view('home/inc/footer',$data);
		}
		
		
	}
	
	function loadMoreCateVideo(){
		

		$category_slug = isset($_SESSION['category_slug'])?$_SESSION['category_slug']:'';

		$list 	= $this->DatabaseModel->select_data('category_id,category_name','artist_category',array('category_slug'=>$category_slug,'parent_id' => 1 ),1);

		if(!empty($list)){
			$category_id = $list[0]['category_id'];

			$start_other = isset($_POST['start_other'])? $_POST['start_other'] : 0;

			$accessParam = array(
									'field' => 'channel_post_video.post_key,channel_post_video.slug,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.post_id,channel_post_video.title,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration',
									'where' => 'category='.$category_id.',privacy_status=7,user_status=1',
									'order' => 'channel_post_video.count_views,DESC',
									'limit' => '3,' . $start_other  ,
									);
				$genreVideo	= $this->query_builder->channel_video_list($accessParam);
					
				if(isset($genreVideo['channel'])){
					echo $this->common_html->loadModeVideo($genreVideo['channel']);					
				}
		}
		
	}
	
	
	function GetCategorVideoSlider(){
		
		if(isset($_POST)){
		
			$condition =  $this->common->channelGlobalCond([1,1,7,0,NULL,1,0]) . " AND artist_category.parent_id = 1 AND artist_category.status = 1" ;
			$join = array('multiple',
								array(
									array('channel_post_video','artist_category.category_id = channel_post_video.category','RIGHT'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
			);
			$Artistcate = $this->DatabaseModel->select_data('category_id,category_name,category_slug','artist_category',$condition,array(1,$_POST['start']),$join,'','','artist_category.category_slug');
					$data	=	array();
					if(!empty($Artistcate)){
						foreach ($Artistcate as $Artistcate){
							$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.slug,channel_post_video.uploaded_video,channel_post_video.video_duration,channel_post_video.is_video_processed,channel_post_video.video_type";
						
							$where = "artist_category.category_slug = '".$Artistcate['category_slug']."' AND  channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 AND  users.user_status = '1'";
									   
							$join  = array(
											'multiple',
											array(
												array('artist_category','artist_category.category_id = channel_post_video.category','LEFT'),
												array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
												array('users' , 'users.user_id = channel_post_video.user_id'),
											)
										);
							
							$order 	= array('channel_post_video.count_views','DESC'); 
													
							$limit = 10;
							
							$cateVideo = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
							
							if(isset($cateVideo[0])){
								 
								$color = ($_POST['start']%2 == 0)? "bg-white" : "";
							
								$autoplay=array("2000","2500","3000");
								$random_keys=array_rand($autoplay,1);
								$auto = $autoplay[$random_keys];
								
								$href = '<a href="'.base_url("icon?iv=".$Artistcate["category_slug"]).'" class="dis_sh_btn muli_font">See all
								<span class="dis_sh_btnicon">
									<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
									<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
									</svg>
								</span>
								</a>';
								
								$htmlarray = array(	'color'=>$color,
													'title'=>$Artistcate["category_name"],
													'href' =>$href,
													'auto' =>$auto, 
													'videoData'=>$this->common_html->swiper_slider_without_html($cateVideo)
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