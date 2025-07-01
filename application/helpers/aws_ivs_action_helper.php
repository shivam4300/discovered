<?php
	require 'awsv3/vendor/autoload.php';

	use Aws\IVS\IVSClient;
	use Aws\Exception\AwsException;
	
	global $recording_config;
	
	$recording_config  = 'arn:aws:ivs:us-east-1:201068771454:recording-configuration/1lToBxatkAth' ;
	
	function api_arguments_(){
		$args = array(
			'version'     => 'latest',
			'region'      => 'us-east-1',
			'credentials' => array(
				'key'         => BUCKET_KEY,
				'secret'      => BUCKET_SECRET,
			)
		);
		
		return $args;
	}

	function createChannel($optionArr=array()){
		
		global $recording_config;
		
		extract($optionArr);
		
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			$result = $IVSClient->createChannel([
				'authorized' 	=> false,
				'latencyMode' 	=> $latencyMode,//NORMAL 
				'name' 			=> $name,
				'recordingConfigurationArn' => $recording_config,
				'tags' 			=> [],
				'type' 			=> $type,//STANDARD
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}

	function getStream($channelArn){
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			
			$result = $IVSClient->getStream([
				'channelArn' => $channelArn,
			]);
			if(isset($result['stream'])){
				
				$startTime 		= date('Y-m-d H:i',strtotime($result['stream']['startTime']));
				date_default_timezone_set("UTC");
				$currentTime 	= date('Y-m-d H:i');
				
				$startTime 		= new DateTime($startTime);
				$currentTime 	= new DateTime($currentTime);
				
				$interval 		= $startTime->diff($currentTime);
				
				$result['stream']['duration'] = $interval->format("%H:%I:%S");
				
				return array('status'=>1 ,'data'=>$result['stream']);
			}else{
				return array('status'=>1 ,'message'=>'Something went wrong ! ');
			}
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
		
	function getRecordingConfiguration($channelArn){
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			return $result = $IVSClient->getRecordingConfiguration([
				'arn' => $channelArn,
			]);
		}catch (AwsException $e) {
			return $e->getMessage();
		}catch (InvalidArgumentException $e) {
			return $e->getMessage();
		}catch (ResourceNotFoundException $e) {
			return $e->getMessage();
		}
	}
	
	
	function putMetadata($channelArn,$metadata){
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			return $result = $IVSClient->putMetadata([
				'channelArn' => $channelArn,
				'metadata'	 => $metadata	
			]);
		}catch (AwsException $e) {
			return $e->getMessage();
		}catch (InvalidArgumentException $e) {
			return $e->getMessage();
		}catch (ResourceNotFoundException $e) {
			return $e->getMessage();
		}
	}
	/* function GetStreamKey($channelArn){
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			$result = $IVSClient->GetStreamKey([
				'arn' => $channelArn,
			]);
			echo '<pre>';
			print_r($result);
			
		}catch (AwsException $e) {
			return $e->getMessage();
		}catch (InvalidArgumentException $e) {
			return $e->getMessage();
		}catch (ResourceNotFoundException $e) {
			return $e->getMessage();
		}
	} */
	function resetStream($arn,$channelArn){
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			$result = $IVSClient->DeleteStreamKey([
				'arn' => $arn,
			]);
			
			if(isset($result['@metadata']['statusCode']) && $result['@metadata']['statusCode'] == 204){
				$result = $IVSClient->CreateStreamKey([
					'channelArn' => $channelArn,
				]);
				if(isset($result['@metadata']['statusCode']) && $result['@metadata']['statusCode'] == 200){
					return array('status'=>1 , 'data'=> $result['streamKey']);
				}
			}
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (ResourceNotFoundException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function getStreamInfo($channelArn){
		try{
			$IVSClient = new IVSClient( api_arguments_() );
			
			$result = $IVSClient->ListStreamKeys([
				'channelArn' => $channelArn,
				'maxResults'=>1
			]);
			
			if(isset($result['streamKeys'][0]['arn'])){
				$result = $IVSClient->GetStreamKey([
					'arn' => $result['streamKeys'][0]['arn']
				]);
				if(isset($result['@metadata']['statusCode']) && $result['@metadata']['statusCode'] == 200){
					return array('status'=>1 , 'data'=> $result['streamKey']);
				}
			}else{
				return array('status'=>0 , 'message'=> 'No key available.' );
			}
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (ResourceNotFoundException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	