<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Embed extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->library(array('Audition_functions','query_builder','share_url_encryption')); 
	}

	function index($vid_id){ 
		if(!empty($vid_id)){
			$where 	= 	array('pub_id'=>$vid_id);
			$social = $this->DatabaseModel->select_data('pub_uid,pub_media,pub_content,is_video_processed','publish_data use INDEX(pub_id)',$where,1);
			if(!empty($social)){
				
					$pub_uid 				= 	$social[0]['pub_uid'];
					$is_vid_processed 		= 	$social[0]['is_video_processed'];
					$title 					= 	$social[0]['pub_content'];
					$data['title']			=  (strlen($title)< 50)?$title:substr($title,0,50)."...";
					
					$media 					= 	explode('|',$social[0]['pub_media']);	
					$publish_video 			= 	$media[0];					
					
					$Filter					=	$this->share_url_encryption->FilterSocialVideo($vid_id,$pub_uid,$publish_video,$is_vid_processed);
					$data['file']			=	$Filter['video'];
					$data['mime_type'] 		=	$Filter['mime_type'];
					
					$data['vid_id']	 		= 	base_url('embed/'.$vid_id) ;
					$data['post_key']		= 	$this->share_url_encryption->share_single_page_link_creator("1|{$vid_id}" , 'encode');
					
					$ThumbImage 			=	(sizeof($media) == 3)? "uploads/aud_".$pub_uid."/images/".$media[2] : 'repo/images/thumbnail.jpg';
					$data['ThumbImage'] 	=	base_url($ThumbImage);
					
					$data['user_id'] 		=	$pub_uid;
					$data['post_id'] 		=	$vid_id;
					
					$data['web_mode']		= 	1;
					$data['mode']			= 	1;
					$data['is_stream_live']	= 	0;
					
				$this->load->view('common/embed',$data);				
			}
		}
	}
	
	function embed_channel_video($vid_id, $list = '') {
		$this->embed_video($vid_id, $list = '',$page = 'embed');
	}

	function withOutSheMedia($vid_id, $list = '',$page = 'embed'){
		$this->embed_video($vid_id, $list = '',$page = 'embed2');
	}

	function withONlySheMediaTag($vid_id, $list = '',$page = 'embed'){
		$this->embed_video($vid_id, $list = '',$page = 'embed3');
	}

	function withSheMediaTagAfterPlayerLoad($vid_id, $list = '',$page = 'embed'){
		$this->embed_video($vid_id, $list = '',$page = 'embed4');
	}

	function embed_video($vid_id, $list = '', $page){
		if (empty($vid_id)) return;

		$fields = 'channel_post_video.title,channel_post_video.video_type,channel_post_video.is_video_processed,
			channel_post_video.uploaded_video,channel_post_video.tag,channel_post_video.post_key,
			channel_post_video.category,channel_post_video.genre,channel_post_video.mode,
			channel_post_video.user_id,channel_post_thumb.image_name,channel_post_video.iva_id,
			website_mode.mode AS web_mode,channel_post_video.is_stream_live';

		$where = 'channel_post_video.post_id=' . $vid_id . ' AND ' . $this->common->channelGlobalCond();

		$join = [
			'multiple',
			[
				['channel_post_thumb USE INDEX(post_id)', 'channel_post_thumb.post_id = channel_post_video.post_id', 'left'],
				['website_mode USE INDEX(mode_id)', 'website_mode.mode_id = channel_post_video.mode', 'left'],
				['users USE INDEX(user_id)', 'channel_post_video.user_id = users.user_id', 'left']
			]
		];

		$result = $this->DatabaseModel->select_data($fields, 'channel_post_video use INDEX(post_id)', $where, 1, $join);

		if (empty($result[0])) {
			echo 'This is private video.';
			return;
		}

		$video = $result[0];
		$pub_uid = $video['user_id'];
		$filter = $this->share_url_encryption->FilterIva(
			$pub_uid,
			$video['iva_id'],
			$video['image_name'],
			trim($video['uploaded_video']),
			false,
			'.m3u8',
			$video['is_video_processed']
		);

		$data = [
			'title'        => $video['title'],
			'ThumbImage'   => $filter['thumb'],
			'file'         => $filter['video'],
			'mime_type'    => $this->share_url_encryption->mime_type($filter['video']),
			'vid_id'       => base_url('embedcv/' . $vid_id),
			'post_key'     => base_url() . $this->common->generate_single_content_url_param($video['post_key'], 2),
			'post_id'      => $vid_id,
			'user_id'      => $pub_uid,
			'web_mode'     => $video['web_mode'],
			'tag'          => $video['tag'],
			'mode'         => $video['mode'],
			'genre'        => $video['genre'],
			'category'     => $video['category'],
			'video_type'   => $video['video_type'],
			'is_stream_live' => $video['is_stream_live']
		];

		if (!empty($list)) {
			$playlist = $this->share_url_encryption->share_single_page_link_creator($list, 'encode', 'id');
			$data['playlist_id'] = $playlist[0] ?? '';
			$data['pListId'] = $list;
		}

		if ($video['is_stream_live'] == 1) {
			$ivs_info = $this->DatabaseModel->select_data(
				'ivs_info,schedule_time,is_scheduled,is_chat,is_live',
				'users_ivs_info',
				['user_id' => $pub_uid],
				1
			);
		
			if (!empty($ivs_info[0]) && $ivs_info[0]['is_live'] == 1) {
				$ivs = $ivs_info[0];
				$streaming = json_decode($ivs['ivs_info'], true);
				$data['strArn'] = $streaming['channel']['arn'] ?? '';
				$data['schedule_time'] = $ivs['schedule_time'];
				$data['is_scheduled'] = $ivs['is_scheduled'];
				$data['is_chat'] = $ivs['is_chat'];
			}
		
			$media_info = $this->DatabaseModel->select_data(
				'media_info,schedule_time,is_scheduled,is_chat,is_live',
				'users_medialive_info',
				['user_id' => $pub_uid],
				1
			);
		
			if (!empty($media_info[0]) && $media_info[0]['is_live'] == 1) {
				$media = $media_info[0];
				$data['media_info'] = $media['media_info'];
				$data['schedule_time'] = $media['schedule_time'];
				$data['is_scheduled'] = $media['is_scheduled'];
				$data['is_chat'] = $media['is_chat'];
			}
		}
		
		$this->load->view('common/'.$page, $data);
	}

	function style404(){
		$style= '.error_wrapper {
				padding: 0;
				text-align: center;
			}
			.error_inner {
				padding: 0;
				margin: 0;
			}
			.error_nb {
				padding: 0;
				margin: 0;
				font-size: 70px;
			}
			.error_wrapper .dis_btn{
				padding: 10px 10px;
				text-transform: uppercase;
				letter-spacing: 0.3px;
				font-size: 14px;
				font-weight: 500;
				color: #fff;
				background: #eb581f;
				border: 1px solid #eb581f;
				display: inline-block;
				outline: none;
				text-align: center;
				transition: all 0.3s linear;
				-ms-transition: all 0.3s linear;
				-o-transition: all 0.3s linear;
				-webkit-transition: all 0.3s linear;
				-moz-transition: all 0.3s linear;
				text-decoration: none;
			}
			.error_wrapper.dis_btn:hover, .error_wrapper .dis_btn:focus {
				opacity: 1;
				background: transparent;
				color: #eb581f;
				outline:0;
			}
			';
		
		$data['style'] 		= $style;
		$data['top']		=	'<i class="fa fa-refresh fa-spin" style="font-size:48px;margin-top:15px;"></i>';	
		$data['heading']	=	'Connect streaming software to go live';	 
		$data['content']	=	'Once you created video stream, you will be able to go live';	
		$data['link']		=	'javascript:;';	
		$data['button']		=	'Stream Setup Help';	
		return $data;
	}
	
	
	
	
}
