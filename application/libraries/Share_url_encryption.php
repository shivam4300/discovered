<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Share_url_encryption{
	 public function __construct()
	{
        $this->CI = get_instance();
	}
	function share_single_page_link_creator($param , $type , $returnType = NULL , $option = array()){
		// $shareUrlName = 'share';
		$shareUrlName = 'watch';
		$uniqueIdParam = 'p';
		
		$idLists = array();
		
		$data = '';
		
		if($type == 'encode'){
			
			$paramData = array($uniqueIdParam => $param);
			if(!empty($option)){
				$paramData = array_merge($paramData,$option);
			}
			
			foreach($paramData as $par => $pData){
				/* if($par == $uniqueIdParam && $shareUrlName == 'share'){
					$shareData = explode('|' , $pData);
					$shareType = $shareData[0];
					if($shareType == 2){ //chanel Video
						$shareUrlName = 'watch';
					}
				} */
				$myID = str_rot13(str_replace('=' , 'a' , base64_encode($pData)));
				array_push($idLists , $myID);
				$data .= '/'.$myID;
				// $data .= (($data == '')?'?':'&').$par.'='.$myID;
			}
			
			if($returnType == 'id'){
				$data = $idLists;
			}else{
				$data = base_url($shareUrlName.$data);
			}
			
		}else{
			$data = base64_decode(str_replace('a' , '=' , str_rot13($param)));
		}
		return $data;
	}

	function share_single_article_link_creator($id , $type , $returnType = NULL , $option = array()){
		
		$data = '';
		
		if($type == 'encode'){
			$data = str_rot13(str_replace('=' , 'a' , base64_encode($id)));
			
		}else{
			$data = base64_decode(str_replace('a' , '=' , str_rot13($id)));
		}
		return $data;
	}
	
	
	function FilterIva($uid,$isIvaVideo,$image_name,$uploaded_video=null,$amINeedThumb = true,$extension='.m3u8',$is_vid_processed=0){
			
			$data 			= array();
			$data['thumb'] 	= '';
			$data['webp'] 	= '';
			$image_name = trim($image_name);
			if(!empty($image_name)){
				$noImg 			=  	CDN_BASE_URL . 'repo/images/thumbnail.jpg';
				if(isset($image_name) && !empty($image_name)){
					$screen 	=	($amINeedThumb)? getChnlthmb($uid,$image_name): getChnlImg($uid,$image_name);
				}else{
					$screen 	=	$noImg;
				}
				
				$isIvaVideo  	=  (!empty(trim($isIvaVideo)))?1:0;
				
				if($isIvaVideo){
					
					$isLocalUpload = explode('https',$image_name);
					if(sizeof($isLocalUpload) > 1){
						$data['thumb'] 	= $image_name;
					}else{
						$data['thumb'] 	= $screen;
					}
					
				}else{
					$data['thumb'] 	= $screen;
					$data['webp'] 	= $screen;
				}
			}
			
			$uploaded_video = trim($uploaded_video);
			if(!empty($uploaded_video)){
				$Arr = explode('https://',$uploaded_video);
				
				if($isIvaVideo || isset($Arr[1])){
					$video 	= $uploaded_video ;
				}else{
					$video =  AMAZON_URL .$uploaded_video;
					
					$key 		= explode('.',$uploaded_video);
					$path 		= explode('/',$key[0]);
					$file  		= isset($path[2])?$path[2]:'';
					$key_file 	= trim($key[0].'/'.$file.$extension);
					
					if($is_vid_processed == 1){
						$video =  AMAZON_TRANCODE_URL .$key_file;
					}else{
						$this->CI->load->helper(array('aws_s3_action')); 
						
						if(DoesObjectExist($key_file) == 1){
							$video =  AMAZON_TRANCODE_URL.$key_file;
							$this->CI->db->set('is_video_processed',1);
							$this->CI->db->where('uploaded_video',$uploaded_video);
							$this->CI->db->update('channel_post_video');
						}	
					}
				}
				$data['video'] 	= $video;
			}
			return $data;
	}
	
	
	function getPreviewFile($videoFile,$video_type){
		$ext  = pathinfo($videoFile , PATHINFO_EXTENSION);
		$previewFile=$videoFile;
		if( $ext == 'm3u8'){
			$pattern = "/simultv/i";
			if(preg_match($pattern, $previewFile)==0 && $video_type!= 2){
				$previewFile = str_replace($ext,'mp4', $videoFile);
			}
		}
		return $previewFile;
	}
	
	
	/* function FilterChannelVideos($uid,$video_type,$uploaded_video=null,$image_name,$amINeedThumb=true,$extension='.m3u8',$is_vid_processed=0){
			$data 			= array();
			$video 			= '';
			
			if($video_type == 0 ||  $video_type == 4){
				$video =  AMAZON_URL .$uploaded_video;
					
				$key 		= explode('.',$uploaded_video);
				$path 		= explode('/',$key[0]);
				$file  		= isset($path[2])?$path[2]:'';
				$key_file 	= trim($key[0].'/'.$file.$extension);
				
				if($is_vid_processed == 1){
					$video =  AMAZON_TRANCODE_URL .$key_file;
				}else{
					$this->CI->load->helper(array('aws_s3_action')); 
					if(DoesObjectExist($key_file) == 1){
						$video =  AMAZON_TRANCODE_URL .$key_file;
						$this->CI->db->set('is_video_processed',1);
						$this->CI->db->where('uploaded_video',$uploaded_video);
						$this->CI->db->update('channel_post_video');
					}	
				}
			}elseif($video_type == 1 ){
				$video 	= $uploaded_video ;
			}elseif($video_type == 2 ){
				$video 	= AMAZON_STREAM_URL.$uploaded_video ;
			}
			
			$data['video'] 	= $video;

			$data['thumb'] 	= '';
			$data['webp'] 	= '';
			
			
			$image_name = trim($image_name);
			if(!empty($image_name)){
				$noImg 			=  	base_url('repo/images/thumbnail.jpg');
				if(isset($image_name) && !empty($image_name)){
					$screen 	=	($amINeedThumb)? getChnlthmb($uid,$image_name): getChnlImg($uid,$image_name);
				}else{
					$screen 	=	$noImg;
				}
				
				if($video_type == 1){
					$isLocalUpload = explode('https',$image_name);
					if(sizeof($isLocalUpload) > 1){
						$data['thumb'] 	= $image_name;
					}else{
						$data['thumb'] 	= $screen;
					}
					
				}else{
					$data['thumb'] 	= $screen;
					$data['webp'] 	= str_replace('?q=','.webp?q=',$screen);
				}
			}
			return $data;
	} */
	
	function mime_type($file){
		$type= '';
		
		$file = explode('?',$file);
		
		if(isset($file[0]) && !empty($file[0])){
			 $ext  = pathinfo($file[0] , PATHINFO_EXTENSION);
			
			 if( trim($ext) == 'm3u8'){
				 $type = 'application/x-mpegURL';
			 }else if(trim($ext) == 'mp4' || $ext == 'mov'){
				  $type = 'video/mp4';
			 }
		}
		 
		return $type;
		
	}
	
	function FilterSocialVideo($pub_id,$uid,$publish_video,$is_vid_processed=0,$extension='.m3u8'){
		$publish_video = trim($publish_video);
		if(!empty($publish_video)){
			$video 	= AMAZON_URL. 'aud_'.$uid.'/videos/'.$publish_video;
			$file = explode('.',$publish_video)[0];
			$path = trim('aud_'.$uid.'/videos/'.$file.'/'.$file.$extension) ; 
			
			if($is_vid_processed == 1){
				$video =  AMAZON_TRANCODE_URL .$path;
			}else{
				$this->CI->load->helper(array('aws_s3_action')); 
				if(DoesObjectExist($path) == 1){
					$video =  AMAZON_TRANCODE_URL .$path;
					$this->CI->db->set('is_video_processed',1);
					$this->CI->db->where('pub_id',$pub_id);
					$this->CI->db->update('publish_data');
				}
			}
			return array('video'=>$video,'mime_type'=>$this->mime_type($video)) ;
		}
	}
	
}	# end class 
 
?>