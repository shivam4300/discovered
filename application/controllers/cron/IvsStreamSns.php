<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class IvsStreamSns extends CI_Controller {
	public $responses='';
	function __Construct(){
		parent::__Construct();
		$this->load->library(array('Audition_functions'));
	}
	function index(){
		$record = json_decode(  file_get_contents('php://input') , true );
		// print_r($record);die;
		if(!empty($record)){
			$resources = implode('', $record['resources']);
			
			$arn=$this->DatabaseModel->select_data('*','users_ivs_info',['channel_arn'=> stripslashes($resources)]);	
				
			if(!empty($arn)){
				
				$data = [
						 'channel_arn' 	=> 	$resources,
						 'user_id' 		=> 	$arn[0]['user_id'],
						 'message'		=>	json_encode( $record  ),
						 'status'		=> 	$record['detail']['recording_status'],
						];
				
				$this->DatabaseModel->access_database('aws_sns_ivs','insert',$data);
				
				if($record['detail']['recording_status'] == 'Recording End'){
					$recorded_path 	= stripslashes($record['detail']['recording_s3_key_prefix']);
					$stream_id 		= $record['detail']['stream_id'];
					
					$sourceKeyname 	= $recorded_path.'/media/hls/master.m3u8';
					$targetKeyname 	= 'aud_'.$data['user_id'].'/videos/';
					
					$stream_url 	= AMAZON_STREAM_URL;
					
					$this->load->helper('aws_s3_action');
					$recorded_duration_s 	= $record['detail']['recording_duration_ms'] / 1000;
					
					$name		=	'';
					$keyname 	= 	$recorded_path.'/media/thumbnails/thumb0.jpg';
					$dobe  		= 	DoesObjectExist($keyname,$bucket = STREAM_BUCKET ,'us-east-1');
					
					if($dobe == 1){
						$name 			= rand().'.jpeg';
						$pathToImages 	= user_abs_path($data['user_id'],'images').$name;
						$s3path 		= $stream_url.$keyname;
						
						if(file_put_contents($pathToImages, file_get_contents($s3path))){
							$this->load->library('audition_functions');
							$this->audition_functions->resizeImage('315','217',$pathToImages,'',FALSE,TRUE);
							upload_all_images($data['user_id']);
						};
					}
					$ivs_info					=	json_decode($arn[0]['ivs_info'],true);
					$streamKeyArn 				=	stripslashes($ivs_info['streamKey']['arn']);
						
					$r = $this->common->CallCurl('POST' ,json_encode(array('arn'=>$streamKeyArn,'channelArn'=>$data['channel_arn'])), base_url('cron/IvsStreamSns/resetStream'),array('Content-Type:application/json'));
					
					$r = json_decode($r ,true);
					$where  = ['user_id' =>	$data['user_id']];
					if($r['status'] == 1){
						$ivs_info['streamKey'] 		= 	$r['data'];
						$update = [	'ivs_info'		=>	json_encode($ivs_info),
									'is_live' 		=> 	0 , 
									'live_pid' 		=> 	'' , 
									'schedule_time'	=> 	'',
									'is_scheduled'	=> 	0
									];
						$this->DatabaseModel->access_database('users_ivs_info','update',$update,$where);
					}
					// print_r($r['data']);die;
					$where['is_stream_live'] = 1;
								  
					$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
					
					if(isset($post[0]['post_id'])){
						$post_id = $post[0]['post_id'];
						
						$thumb_array = array(
								'post_id' 		=> $post_id ,
								'user_id' 		=> $data['user_id'],
								'image_name' 	=> $name,
								'active_thumb' 	=> 0,
							); 
						$this->DatabaseModel->access_database('channel_post_thumb','insert',$thumb_array);
						
						$update = 
								[ 'uploaded_video' 	=> 	$stream_url.$sourceKeyname,
								  'is_stream_live'	=>	0,
								  'video_duration'	=>	$recorded_duration_s
								];
						
						$this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
						echo $post_id;
					}
				
				}else{
					
					$user_id = $arn[0]['user_id'];
					$where  = ['user_id'	=>	$user_id , 'is_stream_live' => 1];
					$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
					if(isset($post[0]['post_id'])){
						$post_id = $post[0]['post_id'];
						$this->audition_functions->sendNotiOnLiveStreaming($user_id  ,$post_id,$post[0]['title'],$status = 3);
					}
				}
			}
		}
		echo 1;
	}
	
	function stremingSns_old(){
		$record = json_decode(  file_get_contents('php://input') , true );
		
		if(!empty($record)){
			$resources = implode('', $record['resources']);
			
			$arn=$this->DatabaseModel->select_data('*','users_ivs_info',['channel_arn'=> stripslashes($resources)]);	
				
			if(!empty($arn)){
				
				$data = [
						 'channel_arn' 	=> 	$resources,
						 'user_id' 		=> 	$arn[0]['user_id'],
						 'message'		=>	json_encode( $record  ),
						 'status'		=> 	$record['detail']['recording_status'],
						];
				
				$this->DatabaseModel->access_database('aws_sns_ivs','insert',$data);
				
				if($record['detail']['recording_status'] == 'Recording End'){
					$recorded_path 	= stripslashes($record['detail']['recording_s3_key_prefix']);
					
					$sourceKeyname 	= $recorded_path.'/media/hls/480p/';
					
					$targetKeyname 	= 'aud_'.$data['user_id'].'/videos/';
					
					$stream_id 		= $record['detail']['stream_id'];
					
					$this->load->helper('aws_s3_action');
					$dobe  = DoesObjectExist($sourceKeyname.'playlist.m3u8',$bucket = STREAM_BUCKET ,'us-east-1');
					
					$f = '480p';
					if($dobe == 0){
						$f = '480p60';
						$sourceKeyname 	= $recorded_path.'/media/hls/480p60/';
					}
					 
					$CO = copyObject($recorded_path.'/media/hls/' ,$targetKeyname , $stream_id, $stream_id.'.m3u8','master.m3u8');
					$CO = copyObject($sourceKeyname,$targetKeyname , $stream_id .'/'.$f, 'playlist.m3u8','playlist.m3u8');
					
					if($CO['status'] == 1){
						$recorded_duration_s = $record['detail']['recording_duration_ms'] / 1000;
						$file_count = round( $recorded_duration_s/12) + 5 ;
						copyinbatch($sourceKeyname,$targetKeyname , $stream_id .'/'.$f , $file_count);
						
						
						$name='';
						$keyname = $recorded_path.'/media/thumbnails/thumb0.jpg';
						$dobe  = DoesObjectExist($keyname,$bucket = STREAM_BUCKET ,'us-east-1');
						if($dobe == 1){
							$k 				= putObjectAcl($keyname, STREAM_BUCKET ,'us-east-1');
							$name 			= rand().'.jpeg';
							$pathToImages 	= user_abs_path($data['user_id'],'images').$name;
							$s3path 		= AMAZON_STREAM_URL.$keyname;
							
							if(file_put_contents($pathToImages, file_get_contents($s3path))){
								// $this->load->library('audition_functions');
								$this->audition_functions->resizeImage('315','217',$pathToImages,'',FALSE,TRUE);
								upload_all_images($data['user_id']);
							};
						}
						
						s3_delete_matching_object($recorded_path,STREAM_BUCKET,'us-east-1');
						
						$ivs_info				=	json_decode($arn[0]['ivs_info'],true);
						$streamKeyArn 			=	stripslashes($ivs_info['streamKey']['arn']);
						
						$stream_url = base_url('cron/IvsStreamSns/resetStream');
						$r = $this->common->CallCurl('POST' ,json_encode(array('arn'=>$streamKeyArn,'channelArn'=>$data['channel_arn']) ), $stream_url,array('Content-Type:application/json'));
					
						$ivs_info['streamKey'] 	= 	json_decode($r['data']) ;
							
						$where  = ['user_id'	=>	$data['user_id']];
						$update = [	'ivs_info'	=>	json_encode($ivs_info),
									'is_live' 	=> 	0 , 
									'live_pid' 	=> 	'' , 
									'is_scheduled'=> 0,
									'schedule_time'=> ''
									];
						$this->DatabaseModel->access_database('users_ivs_info','update',$update,$where);
						
						$where['is_stream_live'] = 1;
								  
						$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
						if(isset($post[0]['post_id'])){
							$post_id = $post[0]['post_id'];
							$thumb_array = array(
									'post_id' 		=> $post_id ,
									'user_id' 		=> $data['user_id'],
									'image_name' 	=> $name,
									'active_thumb' 	=> 0,
								); 
							$this->DatabaseModel->access_database('channel_post_thumb','insert',$thumb_array);
							
							$update = [	'uploaded_video' => $targetKeyname.$stream_id.'.mp4','is_stream_live'=>0];
							
							$this->DatabaseModel->access_database('channel_post_video','update',$update,$where);
							
							// $this->audition_functions->sendNotiOnLiveStreaming($data['user_id'],$post_id,$post[0]['title'],$status = 3);
							
							echo $post_id;
						}
						
					}else{
						echo $CO['message'];
					}
					
				}else{
					
					$user_id = $arn[0]['user_id'];
					$where  = ['user_id'	=>	$user_id , 'is_stream_live' => 1];
					$post = $this->DatabaseModel->select_data('post_id,title','channel_post_video',$where);
					if(isset($post[0]['post_id'])){
						$post_id = $post[0]['post_id'];
						$this->audition_functions->sendNotiOnLiveStreaming($user_id  ,$post_id,$post[0]['title'],$status = 3);
					}
				}
			}
		}
		echo 1;
	}
	
	function putMetadata(){
		$this->load->helper('aws_ivs_action');
		$r = putMetadata($channelArn="arn:aws:ivs:us-east-1:201068771454:channel/VkyY8nKlIsfK",$metadata="This is my matadata");
		echo '<pre>';
		print_r($r);
	}
	
	function m3u8tomp4(){
		$this->load->helper('aws_s3_action');
		registerStreamWrapper();
		$data = file_get_contents('s3://discovered.tv.transcoder/aud_215/videos/zJ6EaZqWFaI4kCr25svq/zJ6EaZqWFaI4kCr25svq_Ott_Hls_Ts_Avc_Aac_16x9_1280x720p_30Hz_6500Kbps_00001.ts');
		
		$path = user_abs_path(215,'videos/');
		
		// file_put_contents($path.'m3u8tomp4.mp4' , $data);
		
		$ffmpeg = 'ffmpeg -i https://s3-us-west-1.amazonaws.com/discovered.tv.transcoder/aud_215/videos/zJ6EaZqWFaI4kCr25svq/zJ6EaZqWFaI4kCr25svq.m3u8 -bsf:a aac_adtstoasc -vcodec copy -c copy -crf 50 '. $path.'m3u8tomp41.mp4';
		$output = exec( $ffmpeg ,$responce );
		print_r($output);
	}
	function copyObject(){
		$this->load->helper('aws_s3_action');
		$sourceKeyname = 'ivs/v1/201068771454/VkyY8nKlIsfK/2021/5/1/12/29/uxNBbmdB3RYb/media/hls/480p/';
		// $sourceKeyname = 'ivs\/v1\/201068771454\/VkyY8nKlIsfK\/2021\/5\/1\/12\/29\/uxNBbmdB3RYb\/media\/hls\/480p/';
		$targetKeyname = 'aud_215/videos/';
		$t = 'ajaydeep';
		
		copyObject($sourceKeyname,$targetKeyname , $t, $t.'.m3u8','playlist.m3u8');
		copyinbatch($sourceKeyname,$targetKeyname ,'ajaydeep');
		
		
	}
	function callscurl(){
		$r = $this->common->CallCurl('POST' ,json_encode(array('arn'=>'arn:aws:ivs:us-east-1:201068771454:stream-key/Pl4kTJiTt7Vo','channelArn'=>'arn:aws:ivs:us-east-1:201068771454:channel/mURWlMSTpmk3') ), base_url('cron/IvsStreamSns/resetStream'),array('Content-Type:application/json'));
		print_r($r);
	}
	function resetStream(){
		$record = json_decode(  file_get_contents('php://input') , true );
		
		$arn = stripcslashes($record['arn']);
		$channelArn = stripcslashes($record['channelArn']);
		// print_r($arn);die;
		$this->load->helper('aws_ivs_action');
		$r = resetStream($arn,$channelArn);
		
		echo json_encode($r); 
	}
	
	// function stremingSns1(){
		// $ajson = json_encode(  json_decode(  file_get_contents('php://input') , true ));
		// $pubId = $this->DatabaseModel->access_database('aws_sns_ivs','insert',['message'=> $ajson ]);
	
	// }
	
	function downloadImgS3(){
		$this->load->helper('aws_s3_action');
		$keyname = 'ivs/v1/201068771454/4AYfDymEBZlb/2021/9/2/5/48/Xr8CCmFSOrlO/media/thumbnails/thumb0.jpg';
		$dobe  = DoesObjectExist($keyname,$bucket = STREAM_BUCKET ,'us-east-1');
		if($dobe == 1){
			$k = putObjectAcl($keyname, STREAM_BUCKET ,'us-east-1');
			$name = rand().'.jpeg';
			$pathToImages = user_abs_path(215,'images').$name;
			$s3path = 'https://discovered-ivs-stream.s3.amazonaws.com/ivs/v1/201068771454/4AYfDymEBZlb/2021/9/2/5/48/Xr8CCmFSOrlO/media/thumbnails/thumb0.jpg';
			
			if(file_put_contents($pathToImages, file_get_contents($s3path))){
				// $this->load->library('audition_functions');
				$this->audition_functions->resizeImage('315','217',$pathToImages,'',$maintain_ratio = false,$create_thumb= TRUE);
			};
		}else{
			echo 'No object';
		}
	}
			
	
	
}

