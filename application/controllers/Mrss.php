<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mrss extends CI_Controller {
	public $responses='';
	function __Construct(){
		parent::__Construct();
			$this->load->library(array('share_url_encryption','query_builder'));
			$this->load->helper(array('info'));
	}
	
	function index(){
		header('Content-type: application/xml');
		$offset		 = 50;
		
		$page 		 = (isset($_GET['page'])) ? $_GET['page'] : 0; 
		
		$page_number = ($page == null)? 0 : $page ; 
		
		$where = array('complete_status'=>1 , 'active_thumb'=> 1); 
		$join = array('multiple' , array(
										array(	'channel_post_thumb',
													'channel_post_thumb.post_id = channel_post_video.post_id',
													'left'),
									));
									
		 $total_video =	(int) $this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$where,$join);
		
		$start  	 = $page_number * $offset ;
		
		$next_page 	 = $page_number+1;
		
		$videoData   = $this->DatabaseModel->select_data('channel_post_video.*,channel_post_thumb.image_name','channel_post_video',$where,array($offset,$start),$join,array('channel_post_video.post_id','DESC'));
		
		$xml = '<rss xmlns:atom="http://www.w3.org/2005/Atom"
				xmlns:media="http://search.yahoo.com/mrss/"
				xmlns:openSearch="http://a9.com/-/spec/opensearchrss/1.0/"
				xmlns:dfpvideo="http://api.google.com/dfpvideo"
				xmlns:tms="http://data.tmsapi.com/v1.1"
				version="2.0">';
		$xml .= '<channel>';
		$xml .= '<title>'.PROJECT.' Videos</title>';
		$xml .= '<dfpvideo:version>2</dfpvideo:version>';
		
		if(sizeof($videoData) >= $offset){
			$xml  .= '<atom:link rel="next" href="'.base_url('mrss?page='.$next_page) .'"/>';
		}
		
		foreach($videoData as $item){
			$title = $this->cleanString($item['title']);
			$FilterData = $this->share_url_encryption->FilterIva($item['user_id'],$item['iva_id'],$item['image_name'],$item['uploaded_video'],false,'.m3u8',1);
			$thumb = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $FilterData['thumb']) ; 
			$video = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $FilterData['video']) ; 
			
			$created_at	=	date(DATE_ATOM,  strtotime($item['created_at']));
			$delete_status = ($item['delete_status'] == 1) ? 'deleted' : 'active';
			
			
			
			$xml .= '<item>
						<pubDate>'. $created_at  .' </pubDate>
						<title>'. $title .'</title>
						<media:thumbnail url="'.$thumb.'" width="280" height="190"/>
						<media:content duration="'.$item['video_duration'].'" url="'.base_url('watch/'.$item['post_key']).'"/>
						<dfpvideo:contentId>'.$item['post_id'].'</dfpvideo:contentId>
						<dfpvideo:lastModifiedDate>'. $created_at .'</dfpvideo:lastModifiedDate>
						<dfpvideo:lastMediaModifiedDate>'. $created_at .'</dfpvideo:lastMediaModifiedDate>
						<dfpvideo:cuepoints>'.round($item['video_duration']/5 ).','.round($item['video_duration']/3 ).','.round($item['video_duration']/2 ).'</dfpvideo:cuepoints>
						<media:status state="'.$delete_status.'"/>
						<dfpvideo:ingestUrl type="application/x-mpegURL" preconditioned="true">
						'.$video.'
						</dfpvideo:ingestUrl>
					</item>';
		}
		
		$xml .= '</channel>';
		$xml .= '</rss>';
		print_r($xml);
	}	
	function cleanString($string){
		$title 			= 	($string != null)? validate_input(remove_special_char($string)) : PROJECT . ' Cool Videos' ;
		$title			=  	(strlen($title)< 30)?$title:substr($title,0,30);
		return $title	=	htmlspecialchars(preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $title));
	}

	function microsoft($mode=''){
		return;
		header('Content-type: application/xml');
		$offset		 = 30;
		
		$page 		 = (isset($_GET['page'])) ? $_GET['page'] : 0; 
		
		$page_number = ($page == null)? 0 : $page ; 
		
		$where = "complete_status = 1 AND active_thumb = 1  "; 
		if($mode == 'music'){
			$where .= " AND post_key IN('ZajkZwtm',	'ZajkBGVk',	'Zaj1AGtj',	'Zaj1ZmV0',	'ZajkZQH3Zjnn',	'Zaj4AGR5',	'Zaj0ZQDk',	'ZajlZmL2',	'Zaj4AGVm',	'ZajkBGR0',	'ZajlBGNn',	'Zaj0ZGp4',	'ZajkAwH2',	'ZajkZQHmADnn',	'Zaj0Amp4',	'ZajmAwL3','ZajkZQx3ANnn',	'ZajmAmR1',	'ZajkZGRmAjnn',	'Zaj1ZmRm',	'Zaj0AGV1',	'ZajlZGD5',	'Zaj1AGtk',	'ZajkAQV1',	'ZajlZQNj',	'ZajkBGp5',	'Zaj0Awt1',	'ZajkAQt3',	'ZajkAQNm',	'Zaj1ZQHl',	'ZajmBGV5')"; 
		}
		
 
		$join = array('multiple' , array(
										array(	'channel_post_thumb',
													'channel_post_thumb.post_id = channel_post_video.post_id',
													'left'),
										array(	'users',
													'channel_post_video.user_id = users.user_id',
													'left'),
										array(	'artist_category', 
													'channel_post_video.category = artist_category.category_id', 
													'left'),
									));
									
		 $total_video =	(int) $this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$where,$join);
		
		$start  	 = $page_number * $offset ;
		
		$next_page 	 = $page_number+1;
		
		$videoData   = $this->DatabaseModel->select_data('channel_post_video.*,channel_post_thumb.image_name,users.user_name,category_name','channel_post_video',$where,array($offset,$start),$join,array('channel_post_video.post_id','DESC'));
		
		$xml = '<rss xmlns:atom="http://www.w3.org/2005/Atom"
					xmlns:media="http://search.yahoo.com/mrss/"
					xmlns:mi="http://schemas.ingestion.microsoft.com/common/"
					xmlns:dc="http://purl.org/dc/elements/1.1/"
					xmlns:content="http://purl.org/rss/1.0/modules/content/"
					xmlns:dcterms="http://purl.org/dc/terms/"
					version="2.0">';
		$xml .= '<channel>';
		$xml .= '<title>'.PROJECT.' Videos</title>';
		$xml .= '<link>'.base_url().'</link>';
		$xml .= '<description>'.PROJECT.' ' . $mode .' </description>';
		$xml .= '<language>en-us</language>';
		
		if(sizeof($videoData) >= $offset){
			$xml  .= '<atom:link rel="next" href="'.base_url('mrss/microsoft?page='.$next_page) .'"/>';
		}
		
		foreach($videoData as $item){
			$title 				= 	$this->cleanString($item['title']);
			$m3u8 				= 	$this->share_url_encryption->FilterIva($item['user_id'],$item['iva_id'],$item['image_name'],$item['uploaded_video'],false,'.m3u8',1);
			$mp4 				= 	$this->share_url_encryption->FilterIva($item['user_id'],$item['iva_id'],$item['image_name'],$item['uploaded_video'],false,'.mp4',0);
			
			// print_r($mp4);die;
			$thumb 				= 	preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $m3u8['thumb']) ; 
			$m3u8_video 		= 	preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $m3u8['video']) ; 
			$m3u8_mime_type 	= 	$this->share_url_encryption->mime_type($m3u8_video);

			$mp4_video 			= 	preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $mp4['video']) ; 
			$mp4_video 			=   AMAZON_URL . $item['uploaded_video'];
			$mp4_mime_type 		= 	$this->share_url_encryption->mime_type($mp4_video);

			$created_at			=	date(DATE_ATOM,  strtotime($item['created_at']));
			$delete_status 		= 	($item['delete_status'] == 1) ? 'deleted' : 'active';
			
			$description 		=	$item['description'];
			$description 		= 	str_replace("&", " ", $description);
			
			$embedcv = base_url('embedcv/'.$item['post_id']);
		
			$xml .= '<item>
						<guid isPermaLink="false">'.$item['post_key'].'</guid>
						<title>'. $title .'</title>
						<pubDate>'. date('Y-m-d', strtotime($item['created_at'])).'</pubDate>
						<link>'. base_url('watch/'.$item['post_key']) .'</link>
						<dc:creator>'.$item['user_name'].'</dc:creator>
						<dcterms:alternative>'. $title .'</dcterms:alternative>
						<description>'.$description.'</description>
						<media:keywords>'.$item['tag'].'</media:keywords>
						<category>'.$item['category_name'].'</category>
						<media:content url="'.$mp4_video.'" duration="'. $item['video_duration'].'" type="'.$mp4_mime_type.'" medium="video"/>
						<media:thumbnail url="'.$thumb.'" width="280" height="190"/>
						<media:title>'. $title .'</media:title>
						<media:description>'.$description.'</media:description>
					</item>';
		}
		
		// <media:content duration="'.$item['video_duration'].'" url="'.base_url('watch/'.$item['post_key']).'"/>
		// 				<video controls="true" id="'.$item['post_id'].'" title="'. $title .'" poster="'.$thumb.'" data-description="'. $title .'">
		// 					<source src="'.$mp4_video.'" type="'.$mp4_mime_type.'"></source>
		// 				</video>
		// 				<iframe width="100%" height="100%" frameborder="0" allowfullscreen="true" src="'.$embedcv.'">
		// 				</iframe>
		
		$xml .= '</channel>';
		$xml .= '</rss>';
		// echo '<pre>';
		print_r($xml);
	}		
	
	
}	