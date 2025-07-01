<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_popular_data extends CI_Controller {
	Public $videoCheckCond = '';	
	Public $mostPopularVideo = array(1=>'music', 2=>'movies', 3=>'television', 6 => 'store', 7 => 'gaming');

	public function __Construct(){
		parent::__Construct();
		$this->videoCheckCond = $this->common->channelGlobalCond();
	}
	
	Public function index(){
		$this->most_popular_video();
		$this->top_video_of_the_month();
		$this->new_releases();
	}

	
	Public $videoJoinData = array(
		'multiple' , 
		array(
			array(
				'channel_post_video' , 
				'channel_post_video.post_id = channel_video_view_count_by_date.video_id'
			),
			array('channel_post_thumb' , 
				'channel_post_thumb.post_id = channel_post_video.post_id'
			),
			array(
				'users' , 
				'users.user_id = channel_post_video.user_id'
			),
			
		)
	);
	
	public function most_popular_video(){
		/*
		This function is use for find the most popular vide based on video view count.
		In this function we are getting top 10 popular video from the date wise view count.
		
		$getNoOfPopularVideo, define how many video record get 
		$popularVideoOrder, indetify the order of popular video 
		*/
		
		$getNoOfPopularVideo = 100;
		$popularVideoOrder = 'DESC';
		
		
		foreach($this->mostPopularVideo as $key => $val){
			$myCond = $this->videoCheckCond." AND channel_post_video.mode ='$key'";
			$popularVideoData = $this->DatabaseModel->select_data('video_id' , 'channel_video_view_count_by_date' , $myCond , $getNoOfPopularVideo,$this->videoJoinData , array('SUM(view_count)' , $popularVideoOrder),'',array('video_id'), '');
			$data['data'] = $this->get_my_comma_sperated_data($popularVideoData , 'video_id')['string'];
			$this->DatabaseModel->update_data('homepage_sliders' , $data , array('type' =>'most_popular_videos', 'mode'=>$key) , 1);
		}
		
	}
	
	
	public function top_video_of_the_month(){
		/*
		This function is use for find the top video of the month vide based on video view count.
		In this function we are getting top 10 popular video of the month from the date wise view count.
		
		$getNoOfPopularVideo, define how many video record get 
		$popularVideoOrder, indetify the order of popular video 
		*/
		
		$getNoOfPopularVideo = 100;
		$popularVideoOrder = 'DESC';
		
		$myD = new DateTime('first day of this month');
		$firstDateOfThisMonth = $myD->format('Y-m-d');
		
		
		foreach($this->mostPopularVideo as $key => $val){
			
			$myCond = $this->videoCheckCond." AND channel_video_view_count_by_date.view_date >= '$firstDateOfThisMonth' AND channel_post_video.mode ='$key'";
			
			$popolarMonthVideoData = $this->DatabaseModel->select_data(
																	'video_id' , 
																	'channel_video_view_count_by_date' , 
																	$myCond  , 
																	$getNoOfPopularVideo  , 
																	$this->videoJoinData , 
																	array('SUM(view_count)' , 
																	$popularVideoOrder) , 
																	'' , 
																	array('video_id') ,
																	''
																);
			
			$popularVideoThisMonth = $this->get_my_comma_sperated_data($popolarMonthVideoData , 'video_id');
			
			$popularVideoLastMonth = array();
			
			if($popularVideoThisMonth['length'] < 10){
				$preLimit = $getNoOfPopularVideo-$popularVideoThisMonth['length'];
				$myD = new DateTime('first day of last month');
				$firstDateOfLastMonth = $myD->format('Y-m-d');
				
				$checkMyCond = $this->videoCheckCond." AND view_date >= '$firstDateOfLastMonth' AND channel_post_video.mode ='$key'" ;
				
				if($popularVideoThisMonth['string'] != ''){
					$checkMyCond .= " AND video_id NOT IN (".$popularVideoThisMonth['string'].")";
				}
				
				$popolarLastMonthVideoData = $this->DatabaseModel->select_data('video_id' , 'channel_video_view_count_by_date' , $checkMyCond , $preLimit  , $this->videoJoinData , array('SUM(view_count)' , $popularVideoOrder) , '' , array('video_id') , '');
			
				$popularVideoLastMonth = $this->get_my_comma_sperated_data($popolarLastMonthVideoData , 'video_id')['array'];
			}
			
			$popularVideoThisMonth = array_merge($popularVideoThisMonth['array'] , $popularVideoLastMonth);
			
			$data['data'] = implode(',' , $popularVideoThisMonth);
			$this->DatabaseModel->update_data('homepage_sliders' , $data , array('type' => 'top_videos_of_the_month', 'mode'=>$key) , 1);
		}
		
		
	}
	
	public function new_releases(){
		/*
		This function is use for find the most popular vide based on video view count.
		In this function we are getting top 10 popular video from the date wise view count.
		
		$getNoOfPopularVideo, define how many video record get 
		$popularVideoOrder, indetify the order of popular video 
		*/
		
		$getNoOfPopularVideo = 100;
		$popularVideoOrder = 'DESC';
			
		$this->videoJoinData = array(
							'multiple' , 
							array(
								array(
									'users' , 
									'users.user_id = channel_post_video.user_id'
								),
								array('channel_post_thumb' , 
									'channel_post_thumb.post_id = channel_post_video.post_id'
								)
							)
						);
					
		
		
		foreach($this->mostPopularVideo as $key => $val){
			$myCond = $this->videoCheckCond . " AND channel_post_video.mode = $key";
			$popularVideoData = $this->DatabaseModel->select_data('channel_post_video.post_id' , 'channel_post_video' , $myCond , $getNoOfPopularVideo  , $this->videoJoinData, array('channel_post_video.post_id' , $popularVideoOrder));
		
		
			$data['data'] = $this->get_my_comma_sperated_data($popularVideoData , 'post_id')['string'];
			$this->DatabaseModel->update_data('homepage_sliders' , $data, array('type' =>'new_release_video', 'mode'=>$key) , 1);
		}
		
		
	}
	
	public function RandomGenreVideo(){
		
		foreach($this->mostPopularVideo as $key => $val){
			
			$field = 'channel_post_video.post_id,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.user_id,channel_post_video.uploaded_video,channel_post_video.slug,channel_post_video.genre,mode_of_genre.genre_name,mode_of_genre.genre_slug,channel_post_video.iva_id';
			
			$where = "channel_post_video.delete_status = '0' AND channel_post_video.complete_status = '1' AND channel_post_video.active_status = '1' AND channel_post_video.privacy_status = '7' AND channel_post_thumb.active_thumb = 1 AND users.user_status = '1' AND channel_post_video.mode = ". $key ."";
				   
			$join = array('multiple',
						array(
						   array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id'),
						   array('users','users.user_id = channel_post_video.user_id'),
						   array('mode_of_genre','mode_of_genre.genre_id = channel_post_video.genre', 'left')
						)
					);
			
			$order 	= 'rand()';;
			
			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,6,$join,$order);
			
			$data['data'] = $this->get_my_comma_sperated_data($videoData , 'post_id')['string'];
			
			$this->DatabaseModel->update_data('homepage_sliders' , $data, array('type' =>'explore_videos_by_genres', 'mode'=>$key) , 1);
			
		}	
	}
	
	function get_my_comma_sperated_data($myData , $myKey){
		$arrayData = array();
		
		if(!empty($myData)){
			foreach($myData as $videoD){
				array_push($arrayData , $videoD[$myKey]);
			}
		}
		return array('string' => implode(',' , $arrayData) , 'array' => $arrayData , 'length' => count($arrayData));
	}
	
	function updateSpotlightMode(){
		$a=array(1,2,3,7);
		shuffle($a);
		$this->DatabaseModel->update_data('website_mode',['spotlight_active_mode'=>$a[0]],['mode_id' =>8],1);
	}
	
	
}