<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aws_notify extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('aws_s3_action')); 
	}
	
	public function index(){
		echo 'Developer Worked, Please try different URL.';
		exit();
	}
	
	public function sns(){
		
		$sns = s3_sns_notify();
		
		if(is_array($sns)){
		    $file_id = $user_id = '0';
		    if(isset($sns['message'])){
    		    $ajson = json_decode( $sns['message'], true );
    		    $a = $ajson['outputKeyPrefix'];
                $b = explode('/', $a);
                $file_id = isset($b[2]) ? $b[2] : 0;
                $c = explode('_', $b[0]);
                $user_id = isset($c[1]) ? $c[1] : 0;

				if(isset($ajson['input']['key'])){
					$this->updateVideoSize($ajson['input']['key']);
				}

				if(isset($ajson['outputs'][0]['key']) && strpos($ajson['outputs'][0]['key'],"_720")){
					$key_acl = $a.$ajson['outputs'][0]['key'];
					putObjectAcl($key_acl,TRAN_BUCKET,BUCKET_REGION);
				}
			}
		    
			$data_array = array(
				'msg_id' => $sns['messageid'],
				'file_id' => $file_id,
				'user_id' => $user_id,
				'message' => json_encode( $sns )
			);
			$aws_rec = $this->DatabaseModel->access_database( 'aws_sns_rec', 'insert', $data_array, '');
			if($aws_rec){
				
			}
		}
	}


	public function updateVideoSize($post_id=""){
		if(!empty($post_id)){
			$videos = $this->DatabaseModel->select_data('post_id,uploaded_video','channel_post_video',array('uploaded_video'=>$post_id),1);
			if(!empty($videos)){
				try{
					$v = $videos[0];
					$fileSize = checkmeta($v['uploaded_video']);
					if(isset($fileSize['statusCode']) && $fileSize['statusCode']==200){
						$this->DatabaseModel->update_data('channel_post_video',array('video_size'=>$fileSize['headers']['content-length']),array('post_id'=>$v['post_id']));
					}
				}catch(Exception $e){
					return true;
				}
			}
		}
	}

	
	public function test(){
		$aws_rec = $this->DatabaseModel->access_database( 'aws_sns_rec', 'select', '', '');		
		echo "<pre>";
		$a = json_decode( $aws_rec[0]['message'], true );
		$a = json_decode( $a['message'], true );
		print_r($a);
	}
	
	public function video(){
		$this->load->view('user/video');
	}
	
	
	
	public function deletelog(){
		$this->load->helper('aws_s3_action_helper');
		$this->load->helper('url');
		$objects = getAllObjects();
		//print_r($objects);
		$arr = array();
		//echo count($objects['Contents']);
		foreach( $objects['Contents'] as $object ){
			if(strpos($object['Key'], 'log2020') !== false){
				$arr[] = $object['Key'];
			}
		}
		print_r($arr);
		echo count($arr);
		s3_delete_object( $arr );
		if(!empty($arr)){
			echo '<script>setTimeout(function(){ window.location.reload(); }, 5000);</script>';
		}
	}
	
	public function createMediaConverterJob(){
		$this->load->helper('aws_s3_action');
		$uploaded_video = $this->DatabaseModel->select_data('uploaded_video','channel_post_video', '' ,  '' , '', '',array('uploaded_video','aud_'));
		for($i=0; $i < sizeof($uploaded_video);$i++){
			
				$key 		= explode('.',$uploaded_video[$i]['uploaded_video']);
				$folder 	= explode('/',$key[0]);
				$file  		= $folder[2];
				$key_file 	= $key[0].'/'.$file.'.m3u8';
				if(DoesObjectExist($key_file) == 0){
					CreateJob($uploaded_video[$i]['uploaded_video'],$key[0].'/');
				}
				
		}
	}
	public function createMCJobForPublicPost(){
		
		$pub_media = $this->DatabaseModel->select_data('pub_uid,pub_media','publish_data', '' ,  '' , '', '',array('pub_media','video'));
		for($i=0; $i < sizeof($pub_media);$i++){
				$file 	= explode('|',$pub_media[$i]['pub_media'])[0];
				
				$key 	= explode('.',$file)[0];
				
				if(DoesObjectExist('aud_'.$pub_media[$i]['pub_uid'].'/videos/'.trim($key).'/'.trim($key).'.m3u8') == 0){
					CreateJob('aud_'.$pub_media[$i]['pub_uid'].'/videos/'.trim($file)  ,  'aud_'.$pub_media[$i]['pub_uid'].'/videos/'.trim($key).'/');
				}
		}
	}
	public function createMCJobForCoverVideo(){
		
		$users_content = $this->DatabaseModel->select_data('aws_s3_profile_video','users_content',array('aws_s3_profile_video != ' => '') ,  '' , '', '');
		// echo '<pre>';
		// print_r($users_content);die;
		
		for($i=0; $i < sizeof($users_content);$i++){
			
				$key 		= explode('.',$users_content[$i]['aws_s3_profile_video']);
				$folder 	= explode('/',trim($key[0]));
				$file  		= trim($folder[2]);
				$key_file 	= trim($key[0]).'/'.trim($file).'.m3u8';
				if(DoesObjectExist(trim($key_file)) == 0){
					CreateJob($users_content[$i]['aws_s3_profile_video'],trim($key[0]).'/');
				}
				
		}
	}
	public function readDirectory(){
		echo '<pre>';
		$dir = "uploads/";
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
			
				$path = 'uploads/'.$file.'/videos/'; 
				if (is_dir($path)){
					if ($vd = opendir($path)){
						while (($vdfile = readdir($vd)) !== false){
							if(!empty(trim($vdfile,'.'))){
									echo "filename:" . $file . "<br>";
										echo "vdfile: {$vdfile} <br>";
									
									$amazon_path = $file."/videos/";
									$res = s3_upload_object(trim($file_path),trim($amazon_path));
									CreateJob(trim($file).'/videos/'.trim($vdfile)  ,  trim($file).'/videos/'.trim(explode('.',$vdfile)[0]) .'/' );
									
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
	
	public function createTranscoderJob(){  
		$this->load->helper('aws_s3_action');
		$uploaded_video = $this->DatabaseModel->select_data('uploaded_video','channel_post_video', '' ,  '' , '', '',array('uploaded_video','aud_'));
		for($i=0; $i < sizeof($uploaded_video);$i++){
			
				$key 		= explode('.',$uploaded_video[$i]['uploaded_video']);
				$folder 	= explode('/',$key[0]);
				$file  		= $folder[2];
				$file_name  = $file.'.mp4';
				$key_file 	= $key[0].'/'.$file_name;
				if(DoesObjectExist($key_file) == 0){
					
					ElasticTranscoder($uploaded_video[$i]['uploaded_video'],$key[0],$file);
				}
				
		}
	}
	
	public function createTCJobForPublicPost(){
		
		$pub_media = $this->DatabaseModel->select_data('pub_uid,pub_media','publish_data', '' ,  '' , '', '',array('pub_media','video'));
		for($i=0; $i < sizeof($pub_media);$i++){
				$file 	= explode('|',$pub_media[$i]['pub_media'])[0];
				
				$key 	= explode('.',$file)[0];
				if(DoesObjectExist('aud_'.$pub_media[$i]['pub_uid'].'/videos/'.trim($key).'/'.trim($key).'.mp4') == 0){
					ElasticTranscoder('aud_'.$pub_media[$i]['pub_uid'].'/videos/'.trim($file)  ,  'aud_'.$pub_media[$i]['pub_uid'].'/videos/'.trim($key) , $key);
				}
			
		}
	}
	public function createTCJobForCoverVideo(){
		
		$users_content = $this->DatabaseModel->select_data('aws_s3_profile_video','users_content',array('aws_s3_profile_video != ' => '') ,  '' , '', '');
		// echo '<pre>';
		// print_r($users_content);die;
		
		for($i=0; $i < sizeof($users_content);$i++){
			
				$key 		= explode('.',$users_content[$i]['aws_s3_profile_video']);
				$folder 	= explode('/',trim($key[0]));
				$file  		= trim($folder[2]);
				$key_file 	= trim($key[0]).'/'.trim($file).'.mp4';
				if(DoesObjectExist(trim($key_file)) == 0){
					// echo $users_content[$i]['aws_s3_profile_video'];echo '<br>';
					// echo trim($key[0]);echo '<br>';
					// echo $file;die;
					ElasticTranscoder($users_content[$i]['aws_s3_profile_video'],trim($key[0]), $file);
				}
				
		}
	}
	// public function deleteTemp(){
		
		// $uploaded_video = $this->DatabaseModel->select_data('uploaded_video','channel_post_video', '' ,  '' , '', '',array('uploaded_video','aud_'));
		// for($i=0; $i < sizeof($uploaded_video);$i++){
			
				// $key 		= explode('.',$uploaded_video[$i]['uploaded_video']);
				// $folder 	= explode('/',$key[0]);
				// $file  		= $folder[2];
				// $file_name  = $file.'.mp4';
				// $key_file 	= $key[0].'/'.$file_name;
				// echo $p =  $key[0].'//'.$file_name.'.mp4';
				// s3_delete_matching_object($p,'discovered.tv.transcoder');
				// echo '<br>';
				
				
				
		// }
		
	// }
	
}
?>