<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class MediaLiveSns extends CI_Controller {
	public $responses='';

	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';

	function __Construct(){
		parent::__Construct();
		$this->load->library(array('Audition_functions'));
	}
	function load(){
		$record = json_decode(  file_get_contents('php://input') , true );
		print_r($record);die; 
	}
	function index(){
		$error='';
		$record = json_decode(  file_get_contents('php://input') , true );
		
		$this->DatabaseModel->access_database('aws_sns_medialive','insert',array('message' => json_encode($record)));
		if(!empty($record)){
			$this->load->helper('media_stream');
			
			$record = json_decode($record['Message'],true);
			if(isset($record['detail']['harvest_job']['status']) && ($record['detail']['harvest_job']['status'] == 'SUCCEEDED')){
				
				$channel_id 	= $record['detail']['harvest_job']['channel_id'];
				$origin_ep_id 	= $record['detail']['harvest_job']['origin_endpoint_id'];
				
				$OEI 		  	= explode('_',$origin_ep_id);
				$uid 			= $OEI[1];
				
				$harvest_job_status = $record['detail']['harvest_job']['status'];
				$r = $this->common->CallCurl('POST',['field' => 'user_id', 'id' =>$uid  , 'deletetable' => 'no' , 'harvest_job_status' =>  $harvest_job_status ], base_url('cron/MediaLiveSns/deleteRowContent/users_medialive_info'),[]);
				log_message('debug', json_encode($r), false);
				print_r($r);die;
			}else if(isset($record['detail']['harvest_job']['status']) &&  $record['detail']['harvest_job']['status'] == 'FAILED' ){	
				$channel_id 	= $record['detail']['harvest_job']['channel_id'];
				$origin_ep_id 	= $record['detail']['harvest_job']['origin_endpoint_id'];
				
				$this->DatabaseModel->access_database('users_medialive_info','update',['is_recorded'=> 3 ],['user_id'=>$uid]); 

				$OEI 		  	= explode('_',$origin_ep_id);
				$uid 			= $OEI[1];
				$r = CreateHarvesting($uid);
				log_message('debug', json_encode($r), false);
				print_r($r);die;
			}
			else if(isset($record['detail']['alert_type']) && $record['detail']['alert_type'] == 'RTMP Stream Not Found'){	
				
				$channel_arn 	= $record['detail']['channel_arn'];
				$time 			= $record['time'];
				
				$media_info 	= $this->DatabaseModel->select_data('media_info,FST,CST,user_id','users_medialive_info','',1,'','',array('media_info',$channel_arn));
				
				if(isset($media_info[0]['media_info'])){
					$media 		= json_decode($media_info[0]['media_info'],true);
					
					$channel_id = $media['Channel']['Id'];
					$FST 		= $media_info[0]['FST']; 
					$CST 		= $media_info[0]['CST']; 
					$uid 		= $media_info[0]['user_id']; 
					
					$time 		= date('Y-m-d H:i:s',strtotime($time));
					
					$diffrenceTime 	= $this->datediff($time, date('Y-m-d H:i:s',strtotime($FST)) );
					
					if($diffrenceTime > 0){
						if($FST !== ''){ 
							$this->DatabaseModel->access_database('users_medialive_info','update',['FET'=> gmdate("Y-m-d H:i:s") ],['user_id'=>$uid]); 
							$r = CreateHarvesting($uid);
							log_message('debug', json_encode($r), false);
							print_r($r);die;
						}else{
							$this->DatabaseModel->access_database('users_medialive_info','update',['is_stream_nf_status'=> 1 ],['user_id'=>$uid]); 
							log_message('debug', 'is_stream_nf_status', false);
							print_r('is_stream_nf_status updated');die;
						}
					}
				}
			}else{
				echo 'no alert';
			}
		}
	}

	function StopRunningChannel(){
		$this->DatabaseModel->access_database('cron_test','insert',array('cron_name' => 'StopRunningChannel' , 'date' => date('Y-m-d H:i:s') ));

		$end_time 	= date('Y-m-d H:i:s', strtotime('-15 minutes',strtotime(date('Y-m-d H:i:s'))) );
		
		$join = array('multiple' , array(
				array(	'channel_post_video', 
						'users_medialive_info.live_pid 	= channel_post_video.post_id','left')
				)
		);
		
		$cond = ['is_live' => 1,'is_scheduled' => 0 ,'is_stream_nf_status' => 0 , 'created_at <=' => $end_time ];
		
		$media_info 	= 	$this->DatabaseModel->select_data('users_medialive_info.user_id,media_info','users_medialive_info',$cond,'',$join);
		$this->load->helper('media_stream');
		
		foreach($media_info as $media ){
			$can_delete_it  = 0;
			$m 		= json_decode( $media['media_info'] ,true);
			$s 		= getChannelDetail($m['Channel']['Id']);
			$state 	= isset($s['data']['State'])?$s['data']['State']:'';
			
			if($state == 'IDLE'){
				$can_delete_it++;
			}

			$proccess_id = 'index_'.$m['package']['HlsIngest']['IngestEndpoints'][0]['Id'];
			$r = describeHarvest($proccess_id);

			if( ($r['status'] == 0 ) || ($r['status'] == 1 && isset($r['data']['Status']) && $r['data']['Status'] != 'IN_PROGRESS') ){
				$can_delete_it++;
			}
			
			if($can_delete_it == 2){
				$r = $this->common->CallCurl('POST',['field' => 'user_id', 'id' => $media['user_id']  , 'deletetable' => 'no' , 'harvest_job_status' => 'FAILED' ], base_url('cron/MediaLiveSns/deleteRowContent/users_medialive_info'),[]);
			}
		}
		
		$media_info = $this->DatabaseModel->select_data('user_id,media_info','users_medialive_info',['is_stream_nf_status' => 1 , 'media_info !=' => '']);
		
		foreach($media_info as $media ){
			$m 		= json_decode($media['media_info'],true);
			
			$r = getChannelDetail($m['Channel']['Id']);
			$state = isset($r['data']['State'])?$r['data']['State']:'';

			if($state == 'IDLE'){
				$r = $this->common->CallCurl('POST',['field' => 'user_id', 'id' => $media['user_id'] , 'deletetable' => 'no'], base_url('cron/MediaLiveSns/deleteRowContent/users_medialive_info'),[]);
			}else{
				$r = 	StopMediaChannel($m['Channel']['Id']); 
				$this->DatabaseModel->access_database('users_medialive_info','update',['is_stream_nf_status'=> 0 ],['user_id'=> $media['user_id'] ]); 
			}
		}
		$this->deleteIdleChannel();
	}
	
	function deleteIdleChannel(){
		$this->load->helper('media_stream');
		$cond = ['status' => 1,'is_live' => 0,'is_scheduled' => 0 ,'is_harvested' => 1 , 'is_recorded' => 2];
		$media_info 	= 	$this->DatabaseModel->select_data('users_medialive_info.user_id,media_info','users_medialive_info',$cond,'',$join);

		$r = listofAllMediaChannels();
		
		foreach($media_info as $media ){
			if(isset($r['channels']['Channels'])){
				foreach($r['channels']['Channels'] as $channel){
					if($channel['Name'] == 'live_channel_'.$media['user_id'] &&  $channel['State'] == 'IDLE'){
						deleteMediaChannel($channel['Id']);
					}
				}
			}
		}
		sleep(10);
		foreach($media_info as $media ){
			if(isset($r['inputs']['Inputs'])){
				foreach($r['inputs']['Inputs'] as $input){
					if($input['Name'] == 'input_'.$media['user_id']){
						deleteMediaInput($input['Id']);
					}
				}
			}
		}
	}
	
	function datediff($end,$start){ 
		$this->load->helper('my');
		$r 	= caculate_time_differece($end,$start);
		return $r['minutes'] * 60 + $r['seconds']; 
	}
	
	function microt(){
		$time_start = '$time_start ' . microtime(true);
		$times=0;               // This couldn't be tough
		while($times<3000000000)
		{
			
			$times++;
		}

		$time_end = '$time_end ' .microtime(true);
		return true;
	}

	public function deleteRowContent($table = null){
		if(isset($_POST['field']) && isset($_POST['id']) && !empty($table)){
			
			$field 	= $_POST['field'];
			$id 	= $_POST['id'];
			
			if($table == 'users_medialive_info'){
				$result = $this->DatabaseModel->select_data('media_info,user_id','users_medialive_info',array($field=>$id),1);
				if(isset($result[0]['media_info'])){
					$resources = json_decode($result[0]['media_info'],true);
					log_message('debug', json_encode($resources), false);
					$this->load->helper('media_stream');
					$r = 	deleteMediaChannel($resources['Channel']['Id']);

					$r =  	DeleteEndPoint(['origin_endpoint_id' => $resources['endpoint']['Id'] ]);
					$r =  	deleteMediaPackageChannel($resources['package']['Id']);
					$r =  	deleteMediaTailorConfig(['ChannelName' => $resources['tailor']['Name'] ]);
					$r = 	deleteMediaInput($resources['input']['Id']);
					log_message('debug', json_encode($r), false);
					$update 	= ['is_stream_live' => 0];
					$update2 	= ['media_info'=>'','is_stream_nf_status' => 0,'status' => 1 , 'is_live' => 0 , 'live_pid'=> '','is_recorded' => 2] ; //is_recorded = 2 means successfull
					if(isset($_POST['harvest_job_status']) && $_POST['harvest_job_status'] == 'FAILED'){
						$update['delete_status'] 	=  1;
						$update2['is_recorded'] 	=  3;
					}

					$this->DatabaseModel->access_database('channel_post_video','update',$update, ['user_id'=>$result[0]['user_id'],'is_stream_live' => 1]); 
					
					$this->DatabaseModel->access_database('users_medialive_info','update',$update2,['user_id'=>$result[0]['user_id']]); 
					$this->microt();
					$r = 	$this->common->CallCurl('POST',['inputId' => $resources['input']['Id']], base_url('cron/MediaLiveSns/deleteMediaInput'),[]);
					log_message('debug', json_encode($r), false);
				}else{
					echo 'empty data';
				}
			}
			
			if(isset($_POST['deletetable']) && $_POST['deletetable'] == 'no'){
				$this->respMessage 	= 'media channel deleted successfully';
			}else{
				if($this->DatabaseModel->access_database($table,'delete','', array($field=>$id))){
					$this->respMessage 	= 'All Data deleted successfully.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
				}else{
					$this->respMessage 	= 'something went wrong,please try again.';
				}
			}
			
		}else{
			$this->respMessage 	= 'table missing.';
		}
		$this->show_my_response($_POST);
	}

	public function deleteMediaInput(){
		$data=[];
		if(isset($_POST['inputId'])){
			$this->load->helper('media_stream');
			sleep(5);
			$data = deleteMediaInput($_POST['inputId']);
			$this->respMessage 	= 'Input deleted successfully.';
			$this->statusCode 	= 1;
		}else{
			$this->respMessage 	= 'table missing.';
		}
		$this->show_my_response($data);
	}
	public function deleteMediaChannel(){
		$data=[];
		if(isset($_POST['channelId'])){
			$this->load->helper('media_stream');
			$data = deleteMediaChannel($_POST['channelId']);
			$this->respMessage 	= 'Channel deleted successfully.';
			$this->statusCode 	= 1;
		}else{
			$this->respMessage 	= 'table missing.';
		}
		$this->show_my_response($data);
	}

	private function show_my_response($resp = array()){
		$resp['status'] 	= $this->statusCode;
		$resp['type'] 		= $this->statusType;
		$resp['message'] 	= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
	
	public function listMediaChannels(){
		$data=[];
		if(isset($_POST['uid'])){
			$this->load->helper('media_stream');
			
			$data = listMediaChannels($_POST['uid']);
			if($data['status'] == 1){
				$this->respMessage 	= '';
				$this->statusCode 	= 1;
			}
			
		}else{
			$this->respMessage 	= 'table missing.';
		}
		$this->show_my_response($data);
	}

	public function describeHarvest($uid=215){
		$data=[];
		if(isset($uid)){
			$this->load->helper('media_stream');
			$data =	listHarvestJobs('package_215');
			echo '<pre>';
			print_r($data);die;
			
			
			$data = describeHarvest('index_1358678669');
			if($data['status'] == 1){
				$this->respMessage 	= '';
				$this->statusCode 	= 1;
			}
			
		}else{
			$this->respMessage 	= 'table missing.';
		}
		$this->show_my_response($data);
	}

	function CreateHarvesting(){
		$this->load->helper('media_stream');
		$r = CreateHarvesting(215);
		echo '<pre>';
		print_r($r);
	}
	
	
}

