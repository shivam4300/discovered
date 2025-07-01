<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_mrss extends CI_Controller {
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('api_validation','aws_s3_action'));
		$this->load->library(array('query_builder','Valuelist','share_url_encryption'));
		
		
	}

	function is_ajax(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
	}

    public function index(){
		$data['web_mode'] = $this->DatabaseModel->select_data('mode_id,mode','website_mode',array('channel_status'=>1));

		$data['user_list']= $this->DatabaseModel->select_data('user_id,user_name','users',array('is_deleted'=>0,'user_status'=>1,'user_dir'=>1,'user_role'=>'member'));
		
		$data['category'] = $this->DatabaseModel->select_data('category_id,category_name','artist_category',array('level'=>1));
		
		// $data['mrss_feed'] = $this->DatabaseModel->select_data('post_ids','mrss_video_feed',array('mode_id'=>1),1);
		
        $data['page_menu']  = 'mrss|mrss_generator|MRSS Generator|mrss_generator'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/mrss/mrss_generator');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
    }

	 function getMrssPostIds(){
		$data['mrss_feed'] = $this->DatabaseModel->select_data('post_ids','mrss_video_feed',array('mode_id'=>$_POST['mode_id']),1);
		$this->respMessage 	= '';
		$this->statusCode 	= 1;
		$this->statusType 	= 'Success';
		$this->show_my_response($data);
	 }
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message'] = $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	
	

	function microsoft($mode=''){
		
		header('Content-type: application/xml');
		
		if(!empty($mode)){

			if(isset($_SESSION['mrss_mode'][$mode])){
				$post_keys =  $_SESSION['mrss_mode'][$mode];
			}else{
				$post_keys = $this->DatabaseModel->select_data('*','mrss_video_feed',array('mode_id'=>$mode),1);
				$post_keys = isset($post_keys[0]['post_ids'])?$post_keys[0]['post_ids']:'';
			}
			
			
			$offset		 	= 	30;
		
			$page 		 	= 	(isset($_GET['page'])) ? $_GET['page'] : 0; 
			
			$page_number 	= 	($page == null)? 0 : $page ; 

			$start  	 	= $page_number * $offset ;
				
			$next_page 	 	= $page_number+1;

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
			

			if(!empty($post_keys)){
				if($mode == 10){
					
						$field = 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.order_,articles_content.image_data,articles_content.content,articles_content.plain_content,users.user_name,users.user_name,article_categories.cat_name';

						$where 	= 	' articles.article_id IN ('.$post_keys.')';

						$join  = array(
							'multiple',
								array(
									array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
									array('users' , 'users.user_id = articles.ar_uid','left'),
									array('article_categories' , 'article_categories.id = articles.ar_category_id','left'),
								)
						);

						$order = array('articles_content.order_', 'asc');

						$main_article	= $this->DatabaseModel->select_data($field,'articles',$where,'',$join, $order);

						$xml .= $this->article_mrss($main_article);
				}else{
					$xml .= $this->video_mrss($post_keys,$next_page,$start,$mode);
				}
			}
			$xml .= '</channel>'; 
			$xml .= '</rss>';
		}
		print_r($xml);
	}
	
	function article_mrss($main_article){
		$xml = '';
		
		$all_articles 	= [];
		foreach($main_article as $article){
			$all_articles[$article['article_id'] .'|'. $article['article_type'] ][] = $article;
		}
		
		foreach($all_articles as $key => $item){

			$link 	= base_url('article/'.$item[0]['article_id']).'?utm_source=msn&amp;utm_medium=article&amp;utm_campaign='.urlencode($item[0]['ar_slug']).'&amp;utm_id='.$item[0]['article_id'].'&amp;utm_term='.$item[0]['ar_author_name'];
			$a 		= htmlspecialchars( $item[0]['ar_title'] , ENT_QUOTES | ENT_SUBSTITUTE , 'UTF-8');
			$t 		= htmlspecialchars( $item[0]['ar_tag'] , ENT_QUOTES | ENT_SUBSTITUTE , 'UTF-8');
			$item[0]['ar_title'] = $a;
			$item[0]['ar_tag'] = $t;
			$xml .= '<item>';
			$xml .= '<guid isPermaLink="false">'.$item[0]['article_id'].'</guid>';
			$xml .= '<title>'.$item[0]['ar_title'].'</title>';
			$xml .= '<pubDate>'. date("c", strtotime($item[0]['ar_date_created'])).'</pubDate>';
			$xml .= '<link>'. $link .'</link>';
			$xml .= '<dc:creator>'.$item[0]['ar_author_name'].'</dc:creator>';
			$xml .= '<mi:shortTitle>'.$item[0]['ar_title'].'</mi:shortTitle>';
			$xml .= '<media:keywords>'.$item[0]['ar_tag'].'</media:keywords>';
			$xml .= '<category>'.$item[0]['cat_name'].'</category>';
			
			$type = explode('|',$key);
			
			if($type[1] == 'default'){
				$c = 0;
				$m = 0;
				$encodedcontent = '';
				
				foreach($item as $list){
					if( $list['content_type'] == 'ckeditor' && $c == 0){
						$xml .= '<description>
									<![CDATA[
									'.$list['plain_content'].'
									]]>
								</description>';
						$c++;
					}

					if( $list['content_type'] == 'image' && $m == 0){
						$ext 		= "image/" . pathinfo($list['content'], PATHINFO_EXTENSION);
						$imgdata 	= 	json_decode($list['image_data'],true);
						$author  	= 	isset($imgdata['author'])? $imgdata['author'] : '';
						$publisher  = 	isset($imgdata['publisher'])? $imgdata['publisher'] : '';
						$license_id = 	isset($imgdata['license_id'])? $imgdata['license_id'] : '';
						$mcredit 	= 	$author .  '/' .  $publisher;
						
						$xml .= '<media:content url="'. AMAZON_URL . $list['content'].'" type="'.$ext.'" medium="image">
									<media:credit>'.$mcredit.'</media:credit>
									<media:title>'.$list['ar_title'].'</media:title>
									<media:text>'.$list['ar_title'].'</media:text>
									<mi:hasSyndicationRights>1</mi:hasSyndicationRights>
									<mi:licenseId>'.$license_id.'</mi:licenseId>
									<mi:licensorName>'.$publisher.'</mi:licensorName>
								</media:content>';
						$m++;
					}
					
					if( $list['content_type'] == 'ckeditor'){	// append all article_content in $encodedcontent
						$encodedcontent .= $list['content'];
					}

					if ($list['content_type'] == 'image' && $m != 0) {
						$ext 		= "image/" . pathinfo($list['content'], PATHINFO_EXTENSION);
						$imgdata 	= 	json_decode($list['image_data'],true);
						$author  	= 	isset($imgdata['author'])? $imgdata['author'] : '';
						$publisher  = 	isset($imgdata['publisher'])? $imgdata['publisher'] : '';
						$license_id = 	isset($imgdata['license_id'])? $imgdata['license_id'] : '';
						$mcredit 	= 	$author .  '/' .  $publisher;
						$encodedcontent .= '<img src="'. AMAZON_URL . $list['content'] .'" alt="'.$list['ar_title'].'" title="'.$list['ar_title'].'"
						data-portal-copyright="'.$mcredit.'" data-has-syndication-rights="1" data-license-id="'.$license_id.'"
						data-licensor-name="'.$publisher.'" />';
					}

					if ($list['content_type'] == 'video') {
						$imgdata 	= 	json_decode($list['image_data'],true);
						$author  	= 	isset($imgdata['author'])? $imgdata['author'] : '';
						$publisher  = 	isset($imgdata['publisher'])? $imgdata['publisher'] : '';
						$mcredit 	= 	$author .  '/' .  $publisher;
						$vid = explode("?",$list['content']);
						
						if(isset($vid[0])){

							$post_key   = explode("/",$vid[0]);

							if(isset($post_key[1])){

								$where 		= 	$this->common->channelGlobalCond();
					
								$where 	   .= 	' AND channel_post_video.post_id ='.$post_key[1].'';
						
								$join 		= 	array('multiple' , array(
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
								
								$videoData   = $this->DatabaseModel->select_data('channel_post_video.post_id,channel_post_video.user_id,channel_post_video.title,channel_post_video.uploaded_video,channel_post_video.description,channel_post_video.iva_id,channel_post_thumb.image_name,users.user_name,category_name','channel_post_video',$where,1,$join);

								if(!empty($videoData)){
									$video  		= 	$videoData[0];

									$post_id 		= 	$video['post_id'];
									
									$title 			= 	$this->cleanString($video['title']);

									$m3u8 			= 	$this->share_url_encryption->FilterIva($video['user_id'],$video['iva_id'],$video['image_name'],$video['uploaded_video'],false,'.m3u8',1);

									$thumb 			= 	str_replace(".webp","",preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $m3u8['thumb'])); 

									$mp4_video 		=   AMAZON_URL . $video['uploaded_video'];

									$mp4_mime_type 	= 	$this->share_url_encryption->mime_type($mp4_video);

									$description 	=	$video['description'];
									$description 	= 	str_replace("&", " ", $description);

									$encodedcontent .='<video id="'.$post_id.'" title="'.$title.'" poster="'.$thumb.'" data-description="'.$description.'" data-portal-copyright="'.$mcredit.'"><source src="'.$mp4_video.'" type="'.$mp4_mime_type.'"></source></video>';			
								}	
							}	
						}			
					}
				}

				$xml .= '<content:encoded>
							<![CDATA[
							'.$encodedcontent.'
							]]>
						</content:encoded>';
			}else
			if($type[1] == 'slider'){
				$part = 0;
				$articles_chunk = [];
				foreach($item as $new_key => $new_item){
					if($new_item['content_type'] != 'part'){
						$articles_chunk[$part][] = $new_item;
 					}else{
						$part++;
					}
				}
				$xml .= '<media:group>';
				
				foreach($articles_chunk as $articles){
					$ar_index_zero =  isset($articles[0]) ? $articles[0] : [];
					$ar_index_one  =  isset($articles[1]) ? $articles[1] : [];
					
					$articles[0] = isset($ar_index_zero['content_type']) && ($ar_index_zero['content_type'] == 'image' || $ar_index_zero['content_type'] == 'video')  ? $ar_index_zero : $ar_index_one;
					
					$articles[1] = isset($ar_index_one['content_type']) && $ar_index_one['content_type'] == 'ckeditor' ? $ar_index_one : $ar_index_zero;
					
					$description = '';
					if(isset($articles[1]['content_type']) && $articles[1]['content_type'] == 'ckeditor'){
						$description = '<media:description>
									<![CDATA[
									'.$articles[1]['plain_content'].'
									]]>
								</media:description>';
					}

					if(isset($articles[0]['content_type']) &&  $articles[0]['content_type'] == 'image'){
						$ext 		= "image/" . pathinfo($articles[0]['content'], PATHINFO_EXTENSION);
						$imgdata 	= 	json_decode($articles[0]['image_data'],true);
						$author  	= 	isset($imgdata['author'])? $imgdata['author'] : '';
						$publisher  = 	isset($imgdata['publisher'])? $imgdata['publisher'] : '';
						$license_id = 	isset($imgdata['license_id'])? $imgdata['license_id'] : '';
						$mcredit 	= 	$author .  '/' .  $publisher;
						
						$xml .= '<media:content url="'. AMAZON_URL . $articles[0]['content'].'" type="'.$ext.'" medium="image">
									<media:credit>'.$mcredit.'</media:credit>
									<media:title>'.$articles[0]['ar_title'].'</media:title>
									<media:text>'.$articles[0]['ar_title'].'</media:text>
									<mi:hasSyndicationRights>1</mi:hasSyndicationRights>
									<mi:licenseId>'.$license_id.'</mi:licenseId>
									<mi:licensorName>'.$publisher.'</mi:licensorName>
									'.$description.'
								</media:content>';
					}

					if(isset($articles[0]['content_type']) &&  $articles[0]['content_type'] == 'video'){
						$imgdata 	= 	json_decode($articles[0]['image_data'],true);
						$author  	= 	isset($imgdata['author'])? $imgdata['author'] : '';
						$publisher  = 	isset($imgdata['publisher'])? $imgdata['publisher'] : '';
						$mcredit 	= 	$author .  '/' .  $publisher;
						$vid 		= 	explode("?",$articles[0]['content']);
						
						if(isset($vid[0])){
							$post_key   = explode("/",$vid[0]);
							
							if(isset($post_key[1])){
								$where 		= 	$this->common->channelGlobalCond();
								$where 	   .= 	' AND channel_post_video.post_id ='.$post_key[1].'';

								$join 		= 	array('multiple' , array(
														array(	'channel_post_thumb',
																	'channel_post_thumb.post_id = channel_post_video.post_id',
																	'left'),
														array(	'users',
																	'channel_post_video.user_id = users.user_id',
																	'left'),
													));
								
								$videoData   = $this->DatabaseModel->select_data('channel_post_video.post_id,channel_post_video.user_id,channel_post_video.iva_id,channel_post_video.title,channel_post_thumb.image_name,uploaded_video,','channel_post_video',$where,1,$join);
								
								if(!empty($videoData)){
									$video  		= 	$videoData[0];
									$post_id 		= 	$video['post_id'];
									$title 			= 	$this->cleanString($video['title']);
									$m3u8 			= 	$this->share_url_encryption->FilterIva($video['user_id'],$video['iva_id'],$video['image_name'],$video['uploaded_video'],false,'.m3u8',1);
									$thumb_image 	= 	str_replace("","",preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $m3u8['thumb'])); 
									$thumb_image 	=   str_replace(".webp","",$thumb_image);
									$extension 		=   "image/" . pathinfo($thumb_image, PATHINFO_EXTENSION);
								
									$xml .= '<media:content url="'.$thumb_image .'" type="'.$extension.'" medium="image">
										<media:credit>'.$mcredit.'</media:credit>
										<media:title>'.$title.'</media:title>
										<media:text>'.$title.'</media:text>
										<mi:hasSyndicationRights>1</mi:hasSyndicationRights>
										<mi:licenseId>'.$post_id.'</mi:licenseId>
										<mi:licensorName>'.$publisher.'</mi:licensorName>
										'.$description.'
									</media:content>'; 
								}	
							}	
						}			
					
					}

				}
				$xml .= '</media:group>';
			}
			
			$xml .= '</item>';
		}
		
		return $xml;
	}

	function video_mrss($post_keys,$next_page,$start,$mode){
		$xml = '';

		$current_mode 	= $this->valuelist->mode();
		$current_mode 	= isset($current_mode[$mode])?$current_mode[$mode]:'';
		
		$where 			= 	$this->common->channelGlobalCond();
		
		$where 			.= 	' AND channel_post_video.post_id IN ('.$post_keys.')';

		$join 			= 	array('multiple' , array(
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
		
		
		
		$videoData   	= $this->DatabaseModel->select_data('channel_post_video.*,channel_post_thumb.image_name,users.user_name,category_name','channel_post_video',$where,array($offset,$start),$join,array('channel_post_video.post_id','DESC'));
		
		if(sizeof($videoData) >= $offset){
			// $xml  .= '<atom:link rel="next" href="'.base_url('admin_mrss/microsoft/'.$mode) .'"/>';
		}

		foreach($videoData as $item){
			$title 				= 	$this->cleanString($item['title']);
			$m3u8 				= 	$this->share_url_encryption->FilterIva($item['user_id'],$item['iva_id'],$item['image_name'],$item['uploaded_video'],false,'.m3u8',1);
			$mp4 				= 	$this->share_url_encryption->FilterIva($item['user_id'],$item['iva_id'],$item['image_name'],$item['uploaded_video'],false,'.mp4',0);
			
			$thumb 				= 	str_replace(".webp","",preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $m3u8['thumb']))  ; 
			
			$m3u8_video 		= 	preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $m3u8['video']) ; 
			$m3u8_mime_type 	= 	$this->share_url_encryption->mime_type($m3u8_video);

			$mp4_video 			= 	preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $mp4['video']) ; 
			//$mp4_video 			=   AMAZON_URL . $item['uploaded_video'];

			$key 	    		= 	explode('.', $item['uploaded_video']);
			$folder 			= 	explode('/',$key[0]);
			$file_name  		= 	$folder[2].'_720.mp4';
			$mp4_video    		= 	AMAZON_TRANCODE_URL.$key[0].'/'.$file_name;

			$mp4_mime_type 		= 	$this->share_url_encryption->mime_type($mp4_video);

			$created_at			=	date(DATE_ATOM,  strtotime($item['created_at']));
			$delete_status 		= 	($item['delete_status'] == 1) ? 'deleted' : 'active';
			
			$description 		=	$item['description'];
			$description 		= 	str_replace("&", " ", $description);
			
			$embedcv 			= base_url('embedcv/'.$item['post_id']);
			
			$link 				= base_url('watch/'.$item['post_key']).'?utm_source=msn&amp;utm_medium='.$current_mode.'&amp;utm_campaign='.urlencode($item['slug']).'&amp;utm_id='.$item['post_id'].'&amp;utm_term='.$item['user_name'];
			
			// <pubDate>'. date('Y-m-d', strtotime($item['created_at'])).'</pubDate>

			$xml .= '<item>
						<guid isPermaLink="false">'.$item['post_key'].'</guid>
						<title>'. $title .'</title>
						<pubDate>'. date('Y-m-d', strtotime('-7 days')).'</pubDate>
						<link>'. $link .'</link>
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
		
		return $xml;
	}

	function cleanString($string){
		$title 	= 	($string != null)? remove_special_char($string) : PROJECT . ' Cool Videos' ;
		$title = stripslashes($title); 
		$title = str_replace("&#39","'",$title); 
		
		return $title;
	}

	function add_mrss_feed(){
		if ($this->input->is_ajax_request()){
			$checkValidation = check_api_validation($_POST , array('mrss_post_ids|require,mode|require'));
			if($checkValidation['status'] == 1){
				$param = [
					'post_ids'=>  trim($_POST['mrss_post_ids'],','),
					'updated_at' 	=>  date('Y-m-d H:i:s'),
					'mode_id' 	=>  $_POST['mode'],
				];
				$mrss_feed = $this->DatabaseModel->select_data('*','mrss_video_feed',array('mode_id'=>$param['mode_id']),1);

				if(empty($mrss_feed)){
					$param['created_at'] 	= $param['updated_at'];
					
					if($id = $this->DatabaseModel->access_database('mrss_video_feed','insert',$param)){
						$this->respMessage 	= 'Your Mrss Feed added successfully.';
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';
					}else{
						$this->respMessage 	= 'Insert Operation failed.';
					}
				}else{
					if($this->DatabaseModel->access_database('mrss_video_feed','update',$param,array('mode_id'=>$param['mode_id']))){
						$this->respMessage 	= 'Your Mrss Feed updated successfully.';
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';
					}else{
						$this->respMessage 	= 'Update Operation failed.';
					}
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			
			$this->show_my_response();
		}else{
			 exit('No direct script access allowed');
		}
	}

	function getMrssContentQueue(){
		if ($this->input->is_ajax_request()){
			$data = [];
			$checkValidation = check_api_validation($_POST , array('mode|require'));
			if($checkValidation['status'] == 1){

				$data['mrss_feed'] =  '';
				if(!empty($_POST['mrss_post_ids'])){
					
					if(isset($_POST['mode']) && $_POST['mode'] == 10 ){
						$data['mrss_feed'] = $this->DatabaseModel->select_data('articles.article_id AS post_id,articles.ar_title AS title','articles','articles.article_id IN ('.trim($_POST['mrss_post_ids'],',').')');
					}else{
						$data['mrss_feed'] = $this->DatabaseModel->select_data('post_id,title,uploaded_video','channel_post_video use INDEX(post_id)','channel_post_video.post_id IN ('.trim($_POST['mrss_post_ids'],',').')');
					}
					
				}
				$this->respMessage 	= '';
				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($data);
		}else{
			 exit('No direct script access allowed');
		}
	}

	function getVideoStatus(){ 
		$data=[];
		if(isset($_POST['videoData'])){
			$videoData = json_decode($_POST['videoData'],true);
			$viData='';
			for($i=0; $i<sizeof($videoData); $i++){
				$val = $videoData[$i];
				$viData .= "'$val',";
			}
			$viData = rtrim($viData,',');
		
			$where 		= "file_id IN ($viData)";
			$videoData 	= $this->DatabaseModel->query('SELECT t1.* FROM aws_sns_rec t1 JOIN (SELECT file_id, MAX(id) id FROM aws_sns_rec WHERE '.$where.' GROUP BY file_id) t2 ON t1.file_id = t2.file_id AND t1.id = t2.id;','array');

			// $videoData 	= $this->DatabaseModel->select_data('*','aws_sns_rec',$where);
			$data['videoData'] 	= $videoData;
		}
		$this->statusCode 	= 1;
		$this->statusType 	= 'Success';
		$this->show_my_response($data);
	}

	function getMyPostIdsOfMode(){
		$mrss_feed = $this->DatabaseModel->select_data('post_ids','mrss_video_feed',array('mode_id'=>$_POST['mode']),1);
		$data['mrss_feed'] = isset($mrss_feed[0]['post_ids'])?$mrss_feed[0]['post_ids']:'';
		$this->statusCode 	= 1;
		$this->statusType 	= 'Success';
		$this->show_my_response($data);
	}

	function clearModeAllList(){
		if ($this->input->is_ajax_request()){
			$data = [];
			$checkValidation = check_api_validation($_POST , array('mode|require'));
			if($checkValidation['status'] == 1){
				if($this->DatabaseModel->access_database('mrss_video_feed','update',['post_ids'=>''],array('mode_id'=>$_POST['mode']))){
					$this->respMessage 	= 'Your MRSS list has been successfully deleted.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
				}else{
					$this->respMessage 	= 'Update Operation failed.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($data);
		}else{
			 exit('No direct script access allowed');
		}
	}

	function removeMrssPostId(){
		if ($this->input->is_ajax_request()){
			$data = [];
			$checkValidation = check_api_validation($_POST , array('mode|require','mrss_post_ids|require'));
			if($checkValidation['status'] == 1){
				
				$mrpid = explode(',',$_POST['mrss_post_ids']);

				$key = array_search($_POST['remove_mrss_post_id'], $mrpid, true);
				if ($key !== false) {
					unset($mrpid[$key]);
				}

				
				$data['mrss_feed'] = implode( ',' , array_values($mrpid));
				
				if($this->DatabaseModel->access_database('mrss_video_feed','update',['post_ids'=>$data['mrss_feed']],array('mode_id'=>$_POST['mode']))){
					$this->respMessage 	= 'Your Mrss Feed updated successfully.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
				}else{
					$this->respMessage 	= 'Update Operation failed.';
				}
				
				
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($data);
		}else{
			 exit('No direct script access allowed');
		}
	}

	function access_article_list(){
		$_GET = $_SERVER["REQUEST_METHOD"] == 'GET' ? $_GET : $_POST ;
		
		$data = array();
		$leadsCount = 0;

		// $where  = ['articles.complete_status' =>1, 'articles.delete_status'=>0 , 'articles_content.content_type' => 'image', 'articles_content.order_' => 0];
		$where  = ' articles.complete_status = 1 AND articles.delete_status = 0 AND articles.privacy_status = 7 AND articles_content.order_ = 0 '; 
		
		if(isset($_GET['msn_feed_status'])){
			$l = $_GET['msn_feed_status'];
			$where .= " AND articles.msn_feed_status = $l";
		}


		if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
			// $where['user_level'] = $_GET['user_level'];
			$where .= ' AND user_level = '.$_GET['user_level'];
		}
		
		if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
			// $where['user_id'] = $_GET['user_id'];
			$where .= ' AND users.user_id = '.$_GET['user_id'];
		}
	
		$field = 'articles.ar_title AS title,articles.ar_date_created AS created_at,articles.article_id AS post_id,articles.article_type,articles_content.content AS image_name,articles_content.content_type,users.user_name,users.user_id';
		
		$search_fields = array( 'articles.ar_title', 'users.user_name', 'users.user_id', 'articles.article_id' );

		$search = 	isset($_GET['search']['value'])?$_GET['search']['value']:'';
		if(!empty($search)){
				$where .= ' AND (';
				for($i=0;$i < sizeof($search_fields); $i++){
					if($search_fields[$i] != ''){
						$where .= "$search_fields[$i] LIKE '%".$search."%'";
						if(sizeof($search_fields) - $i != 1){
							$where .= ' OR ';
						}	
					}
				}
				$where .= ')';
		}

		$join  = array(
			'multiple',
				array(
					array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
					array('users' , 'users.user_id = articles.ar_uid','left'),
				)
		);

		$order 	= array('articles.article_id', 'DESC');

		$main_article	= $this->DatabaseModel->select_data($field,'articles',$where,[$_GET['length'],$_GET['start']],$join,$order,'','articles_content.content');
		
		$data = [];
		foreach($main_article as $list){
			$list['image_thumb'] 	= ( $list['content_type'] == 'image' )? AMAZON_URL.$list['image_name']: base_url( 'repo/images/blog_pp.png' ) ;
			$list['created_ago'] 	= time_elapsed_string($this->common->manageTimezone($list['created_at']),$full = false);
			$list['vurl'] 			= base_url('article/'.$list['post_id']);
			$list['article_type'] 	= ucfirst($list['article_type']);
			array_push($data , $list);
		}
		
		$leadsCount	= $this->DatabaseModel->aggregate_data('articles' ,'articles.article_id', 'COUNT' , $where  , $join);
	
		return json_encode(array(
			'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' => $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data,
			));
		 
	}

	function access_channel_video_list(){
		$_GET = $_SERVER["REQUEST_METHOD"] == 'GET' ? $_GET : $_POST ;
		
		$leadsCount =0;
		$data 	= array();
		
		if(isset($_GET['length']) && !empty(trim($_GET['length']))){
			
			if(isset($_GET['mode']) && $_GET['mode'] ==  10){
				echo $this->access_article_list();die;
			}

			$search = 	isset($_GET['search']['value'])?$_GET['search']['value']:'';
			
			$order 	= array('channel_post_thumb.post_id', 'DESC');

			$cond 	= 	$this->common->channelGlobalCond();
			
			if(isset($_GET['msn_feed_status'])){
				$l = $_GET['msn_feed_status'];
				$cond .= " AND channel_post_video.msn_feed_status = $l";
			}
			
			if(isset($_GET['videolength']) && !empty($_GET['videolength'])){
				$c = explode('-',$_GET['videolength']);
				$cond  .=  ' AND video_duration BETWEEN "'.($c[0]*60).'" AND "'.($c[1]*60).'" ';
			}
			
			if(isset($_GET['mode']) && !empty($_GET['mode'])){
				$cond .= " AND channel_post_video.mode = '".$_GET['mode']."'";
				
			}
			
			if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
				$l = $_GET['user_level'];
				$cond .= " AND users.user_level = $l";
			}
			
			if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
				$cond .= " AND channel_post_video.user_id = '".$_GET['user_id']."'";
			}

			if(isset($_GET['filter_by_size']) && !empty($_GET['filter_by_size'])){
				$cond .= " AND channel_post_video.video_size <= 1610612736";  // less than OR euqal to  1.5 GB
			}

			$filed = array('channel_post_thumb.image_name','channel_post_video.post_id','channel_post_video.title','users.user_name','channel_post_video.created_at','channel_post_video.post_key','users.user_id','video_size');

			if(!empty($search)){
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
			}
			
			
			$join 	= 	array('multiple' , array(
				array(	'channel_post_thumb',
							'channel_post_thumb.post_id = channel_post_video.post_id',
							'left'),
				array(	'users',
							'channel_post_video.user_id = users.user_id',
							'left'),
			));


			$resultData 	= $this->DatabaseModel->select_data($filed,'channel_post_video use INDEX(post_id)', $cond ,array($_GET['length'],$_GET['start']) , $join, $order );
			$leadsCount 	= $this->DatabaseModel->aggregate_data('channel_post_video use INDEX(post_id)','channel_post_video.post_id','COUNT',$cond,$join);
				
			foreach($resultData as $list){
				$img 		= explode('.',$list['image_name']);
				$list['image_thumb'] = $imgUrl 	= AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$img[0].'_thumb.'.$img[1] ;
				$list['vurl'] = $vurl 		= base_url('watch?p='.$list['post_key']);
				$list['created_ago'] = time_elapsed_string($this->common->manageTimezone($list['created_at']),$full = false);
				$list['video_size']  = formatSizeUnits($list['video_size']); //find this finction in info helper
				
				array_push($data , $list);
					
			}
			
		
		echo json_encode(array( 
			'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' => $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data, 
			));
		}
	}

	function markAsPublished(){
		if ($this->input->is_ajax_request()){
			$data = [];
			$checkValidation = check_api_validation($_POST , array('mode|require','mrss_post_ids|require'));
			if($checkValidation['status'] == 1){
				$mode = $this->input->post('mode');
				$mrss_post_ids = $this->input->post('mrss_post_ids');
				
				if($mode == 10){
					$where_in = 'article_id IN ( '. trim($mrss_post_ids ,',') .' )';
					$r = $this->DatabaseModel->access_database('articles','update',['msn_feed_status' => 1 ], $where_in );
					
				}else{
					$where_in = 'post_id IN ( '. trim($mrss_post_ids ,',') .' )';
					$r =$this->DatabaseModel->access_database('channel_post_video','update',['msn_feed_status' => 1 ], $where_in );
				}
				
				if($r){
					$this->DatabaseModel->access_database('mrss_video_feed','update',['post_ids'=>''],array('mode_id'=>$mode));

					$this->respMessage 	= 'Your Mrss Feed marked successfully.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
				}else{
					$this->respMessage 	= 'Update Operation failed.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($data);
		}else{
			 exit('No direct script access allowed');
		}
	}

	function create_720_video(){
		if ($this->input->is_ajax_request()){
			$data = [];
			$checkValidation = check_api_validation($_POST , array('mode|require'));
			if($checkValidation['status'] == 1){
				$mode = $this->input->post('mode');
				$mrss_feed = $this->DatabaseModel->select_data('post_ids','mrss_video_feed',array('mode_id'=>$mode),1);
				$post_keys = isset($mrss_feed[0]['post_ids'])?$mrss_feed[0]['post_ids']:'';

				if(!empty($post_keys)){
					$where 		= 'channel_post_video.post_id IN ('.$post_keys.')';
					$videoData  = $this->DatabaseModel->select_data('uploaded_video','channel_post_video',$where);

					if(!empty($videoData)){
						foreach($videoData as $v){
							$r = ElasticTranscoder720($v['uploaded_video']);
							$data['result'] = $r;	
						}
						$this->respMessage 	= 'Video quality 720 created successfully.';
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';
					}else{
						$this->respMessage = 'Selected videos not found.';
					}
				}else{
					$this->respMessage = 'Please select videos.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($data);
		}else{
			 exit('No direct script access allowed');
		}		
	}
 
	function export_mrss_videos($mode=''){
		if(!empty($mode)){
			$mrss_feed = $this->DatabaseModel->select_data('post_ids','mrss_video_feed',array('mode_id'=>$mode),1);
			$post_keys = isset($mrss_feed[0]['post_ids'])?$mrss_feed[0]['post_ids']:'';

			if(!empty($post_keys)){
				$where 		= 'channel_post_video.post_id IN ('.$post_keys.')';
				$videoData  = $this->DatabaseModel->select_data('post_id,post_key,title,uploaded_video','channel_post_video',$where);
				$current_mode 	= $this->valuelist->mode();
				$current_mode 	= isset($current_mode[$mode])?$current_mode[$mode]:'';
				if(!empty($videoData)){
					$filename = ucfirst($current_mode)."_mrss_videos_".date('d-m-Y').".csv";
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"$filename\"");
					header("Pragma: no-cache");
					header("Expires: 0");
					ob_clean();

					$handle = fopen('php://output', 'w');
					
					$vdata = [];
					$this->load->library('share_url_encryption');
					fputcsv($handle, ['Post Key','Title','MP4 URL']);
					foreach ($videoData as $list) {
						$key 	    = explode('.', $list['uploaded_video']);
						$folder 	= explode('/',$key[0]);
						$file_name  = $folder[2].'_720.mp4';
						$mp4_url    = AMAZON_TRANCODE_URL.$key[0].'/'.$file_name;
						$vdata = array( 
							$list['post_key'],
							$list['title'],
							$mp4_url,
						);
						fputcsv($handle, $vdata);
					}
					fclose($handle);
					exit;
				}
			}
		}
		exit;
	}
	
	
}
