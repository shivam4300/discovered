<?php
	require 'aws/vendor/autoload.php';

	use Aws\S3\S3Client;
	use Aws\Exception\AwsException;

	/* sns notitfication */
	use Aws\Sns\SnsClient;
	use Aws\Sns\Message;
	use Aws\Sns\MessageValidator;
	use Aws\Sns\Exception\InvalidSnsMessageException;

	use Aws\Common\Exception\MultipartUploadException;
	use Aws\S3\MultipartUploader;
	use Aws\S3\Transfer;
	
	use Aws\MediaConvert\MediaConvertClient;
	use Aws\MediaConvert\Exception\MediaConvertException;
	
	use Aws\S3\S3ClientInterface; 
	
	use Aws\ElasticTranscoder\ElasticTranscoderClient;
	
	use Aws\CloudFront\CloudFrontClient;
	
	use Aws\CommandPool;
	use Aws\CommandInterface;
	use Aws\ResultInterface;
	use GuzzleHttp\Promise\PromiseInterface;
	
	global $bucket_name;

	$bucket_name = MAIN_BUCKET;

	function api_arguments(){
		$args = array(
			'version'     => 'latest',
			'region'      => BUCKET_REGION,
			'credentials' => array(
				'key'         => BUCKET_KEY,
				'secret'      => BUCKET_SECRET,
			)
		);
		return $args;
	}

	function createSignedUrl($key){
		try{
			if(!empty($key)){
				$s3Client = new S3Client(array(
					'version'     => 'latest',
					'region'      => BUCKET_REGION, //'s-drc2',
					//'endpoint'	=>	'https://s-drc2.cloud.gcore.lu/',
					'credentials' => array(
						'key'         => BUCKET_KEY, //'TP2RG173DA224RWHR6MF',
						'secret'      => BUCKET_SECRET //'3bUatr0go6dAyJWNw4LHN9hfaYfCctqBup4EC5QB',
					)

				));
				//GetObject
				$cmd = $s3Client->getCommand('PutObject', [
					'Bucket' => MAIN_BUCKET, //'main',
					'Key' => $key
				]);
				
				$request = @ $s3Client->createPresignedRequest($cmd, '+10 minutes');
				
				$presignedUrl = (string)$request->getUri();

				//print_r($presignedUrl);die;

				return array('status'=>1, 'presignedUrl'=>$presignedUrl);
			}else{
				return array('status'=>0, 'message'=>'The key must not be empty.');
			}
		}catch (S3Exception $e) {
			//return $e->getMessage();
			return array('status'=>0, 'message'=>$e->getMessage());
		}
		
	}
	
	function s3_upload_object_ad($source, $key , $ci_mime = false){
		try{
			if($ci_mime){
				$CI =& get_instance();
				$CI->load->helper('file');
				$mime_type = get_mime_by_extension($source);
			}else{
				$mime_type = @ mime_content_type($source);
			}

			global $bucket_name;
			//Create a S3Client
			$s3Client = new S3Client( api_arguments() );
			
			$object = array(
				'ACL' => 'public-read',
				'Bucket' => $bucket_name,
				'Key' => $key,
				'SourceFile' => $source,
				'ContentType'=>$mime_type
			);
			
			$result = $s3Client->putObject( $object );
			
			$ObjectURL = $result->get( 'ObjectURL' );
			
			return array( 'url' => $ObjectURL, 'key' => $key );
			
			return $result;
		
		}catch (S3Exception $e) {
			return $e->getMessage();
		}
	}

	function multipartUploader_ad($source, $key,$ci_mime=false){
			global $bucket_name;
			
			if($ci_mime){
				$CI =& get_instance();
				$CI->load->helper('file');
				$mime_type = get_mime_by_extension($source);
			}else{
				$mime_type = mime_content_type($source);
			}
			//Create a S3Client
			$s3Client = new S3Client( api_arguments() );
			$uploader = new MultipartUploader($s3Client, $source, [
				'ACL' => 'public-read',
				'bucket' => $bucket_name,
				'key'    => $key,
				'before_initiate' => function(\Aws\Command $command) use ($mime_type){
					$command['ContentType'] = $mime_type;  //  video/mp4
				}
			]);
			
			try {
				$result = $uploader->upload();
				$ObjectURL = $result->get( 'ObjectURL' );
			
				return array('status'=>1,'url' => $ObjectURL, 'key' => $key );
			} catch (MultipartUploadException $e) {
				return $e->getMessage() . PHP_EOL;
			}
	}

	function multipartUploader_transfer($source, $key){
			global $bucket_name;
			//$mime_type = mime_content_type($source);
			//Create a S3Client
			$s3Client = new S3Client( api_arguments() );
			$uploader = new Transfer($s3Client, $source,$key);
			
			try {
				print_r($uploader);
				/*$result = $uploader->upload();
				$ObjectURL = $result->get( 'ObjectURL' );
			
				return array( 'url' => $ObjectURL, 'key' => $key );*/
			} catch (MultipartUploadException $e) {
				return $e->getMessage() . PHP_EOL;
			}
	}

	function s3_get_object( $keyname, $bucket_name = '' , $region = '' ){
		try{
			
			if($bucket_name == ''){
				global $bucket_name;
			}
			
			$api_arguments = api_arguments();
			
			if(!empty($region))
			$api_arguments['region'] = $region;
		
			$s3Client = new S3Client( $api_arguments );
			
			$object = array(
				'Bucket' => $bucket_name,
				'Key'    => $keyname
			);
			
			$result = $s3Client->getObject( $object );
			header("Content-Type: {$result['ContentType']}");
			return $result['Body'];
			
			/*
			print_r($result); 
			$metadata = $result->get( '@metadata' );
			print_r($metadata['headers']); 
			if(!empty($metadata['headers'])){
				foreach($metadata['headers'] as $k=>$v){
					header("{$k}: {$v}");
				}
			}
			echo $result['Body'];
			*/
			
		}catch(S3Exception $e){
			return $e->getMessage();
		}
	}
	function s3headObject( $keyname, $bucket_name = '' , $region = '' ){
		try{
			
			if($bucket_name == ''){
				global $bucket_name;
			}
			
			$api_arguments = api_arguments();
			
			if(!empty($region))
			$api_arguments['region'] = $region;
		
			$s3Client = new S3Client( $api_arguments );
			
			$object = array(
				'Bucket' => $bucket_name,
				'Key'    => $keyname
			);
			
			return $result = $s3Client->headObject( $object );
			header("Content-Type: {$result['ContentType']}");
			return $result['Body'];
			
			/*
			print_r($result); 
			$metadata = $result->get( '@metadata' );
			print_r($metadata['headers']); 
			if(!empty($metadata['headers'])){
				foreach($metadata['headers'] as $k=>$v){
					header("{$k}: {$v}");
				}
			}
			echo $result['Body'];
			*/
			
		}catch(Aws\S3\Exception\S3Exception $e){
			return $e->getMessage();
		}
	}

	function s3_delete_object( $keys, $bucket_name = '' ){
		if($bucket_name == ''){
			global $bucket_name;
		}
		
		$s3Client = new S3Client( api_arguments() );
		
		$object = array(
			'Bucket' => $bucket_name,
			'Delete' => array(
				'Objects' => array_map(function ($keys) {
					return ['Key' => $keys];
				}, $keys)
			)
		);
		
		$result = $s3Client->deleteObjects( $object );
		
		$mes = $result->get( 'Deleted' );
		
		return $mes;
	}

	function s3_delete_matching_object($keys, $bucket_name = '' , $region = ""){
		
		try{
			$api_arguments =  api_arguments() ;
			
			if(!empty($region))
			$api_arguments['region'] = $region;
			
			$s3Client = new S3Client($api_arguments);
		
			$result = $s3Client->deleteMatchingObjects($bucket_name ,$keys);
			return $result ;
		}catch (S3Exception $e) {
			return $e->getMessage();
		}catch (Aws\S3\Exception\PermanentRedirectException $e) {
			return $e->getMessage();
		}
	}

	function s3_get_bucket(){
		try{
			//Create a S3Client
			$s3Client = new S3Client( api_arguments() );

			//Listing all S3 Bucket
			$buckets = $s3Client->listBuckets();
			/*foreach ($buckets['Buckets'] as $bucket) {
				echo $bucket['Name'] . "<br>";
			}*/
			
			return !empty($buckets['Buckets']) ? $buckets['Buckets'] : array();	
		}catch (S3Exception $e) {
			return $e->getMessage();
		}
	}

	
	function s3_sns_notify(){
		try {
			// Create a message from the post data and validate its signature
			$message = Message::fromRawPostData();
			$validator = new MessageValidator();
			$validator->validate($message);
		} catch (Exception $e) {
			return array('message' => $e->getMessage());
			// Pretend we're not here if the message is invalid
			// http_response_code(404);
			die;
		}
		// return $message;
		if ($message['Type'] === 'SubscriptionConfirmation') {
			// Confirm the subscription by sending a GET request to the SubscribeURL
		   file_get_contents($message['SubscribeURL']);
		   
		   return true;
		}

		if ($message['Type'] === 'Notification') {
		   // Do whatever you want with the message body and data.
			$a = array( 
				'messageid' => $message['MessageId'],
				'message' => $message['Message']
			);
		   
			return $a;
		   
		}
	}
	
	function registerStreamWrapper(){
		try{
			$s3Client = new S3Client( api_arguments() );
			$s3Client->registerStreamWrapper();
			
		
		}catch (S3Exception $e) {
			
			return $e->getMessage();
		}
		
	}

	function getAllObjects($key_type=""){
		try{
			global $bucket_name;
			//Create a S3Client
			$s3Client = new S3Client( api_arguments() );

			//Listing all S3 Bucket
			$objects = $s3Client->listObjects(array('Bucket' => $bucket_name,'Delimiter'=>'aud','MaxKeys'=>2000));
			/*foreach ($buckets['Buckets'] as $bucket) {
				echo $bucket['Name'] . "<br>";
			}*/
			
			return $objects;	
		}catch (S3Exception $e) {
			return $e->getMessage();
		}
	}

	function getAllfilmhubObjects($Step,$Prefix='',$Marker=''){       
		try{
			$bucket_name = FILMHUB_BUCKET; 
			$s3Client = new S3Client( api_arguments() );
			if($Step == 1){
				return $s3Client->listObjects(array('Bucket' => $bucket_name,'Delimiter' => '/', 'MaxKeys'=> 1000));
			}else
			if($Step == 2){
				return $s3Client->listObjects(array('Bucket' => $bucket_name ,'Prefix' => $Prefix));
			}	   
		}catch (S3Exception $e) {
			return $e->getMessage();
		}
	}
	
	function DoesObjectExist($key_file,$bucket = TRAN_BUCKET ,$region = ''){
		try{
			$api_arguments =  api_arguments() ;
			
			if(!empty($region))
			$api_arguments['region'] = $region;
			
			$s3Client = new S3Client($api_arguments);
			
			$response = $s3Client->doesObjectExist($bucket, $key_file);
			
			if($response){
				return 1;
			}else{
				return 0;
			}
		}catch (S3Exception $e) {
			return $e->getMessage();
		}catch (Exception $e) {
			return $e->getMessage();
		}
	}

	function checkmeta($key){
	
		//Create a S3Client
		$s3Client = new S3Client( api_arguments() );

		$result = $s3Client->headObject([
			'Bucket' => MAIN_BUCKET, // REQUIRED
			'Key'=> $key
		]);
		return  $result->get( '@metadata' ); 
	}
	
	function upload_all_images($uid){
		$CI =& get_instance();
		$CI->load->helper('file');

		$path = 'uploads/aud_'.$uid.'/images/';
		if (is_dir($path)){	
			if ($imgDir = opendir($path)){
				while (($file = readdir($imgDir)) !== false){
					$file =  str_replace(".lossless.webp","",trim($file));
					$file =  str_replace(".lossy.webp","",trim($file));
					
					$mime = get_mime_by_extension($file);
					if(!empty($file) && $file !== '.' && $file !== '..' && in_array($mime,['image/webp' ,'image/jpeg' ,'image/jpg' ,'image/png' ,'image/gif'])  ){
						$amazon_path = "aud_{$uid}/images/{$file}";
						$res = s3_upload_object_ad($path.$file,$amazon_path,false);
						if(!empty($res)){
							if(file_exists($path.$file)){
								@ unlink($path.$file);
							}
						}
					}
					
				}
				closedir($imgDir);
			}
			return true;
		}
	}

	
	function CreateJob($key,$Newkey){
		
		$api_arguments 				=  	api_arguments();
		$api_arguments['endpoint'] 	= 	MC_END_POINT;
		
		$mediaConvertClient 		= new MediaConvertClient($api_arguments);
	
		$result = $mediaConvertClient->createJob([
				"JobTemplate" => 'System-Ott_Hls_Ts_Avc_Aac',
				"Role" => "arn:aws:iam::201068771454:role/MediaConvert_Default_Role", 
				"Settings"=> [
					"Inputs"=> [
						[
							"AudioSelectors"=> [
								"Audio Selector 1"=> [
									"Offset"=> 0,
									"DefaultSelection"=> "NOT_DEFAULT",
									"ProgramSelection"=> 1,
									"SelectorType"=> "TRACK",
									"Tracks"=> [
										1
									]
								]
							],
							"VideoSelector"=> [
								"ColorSpace"=> "FOLLOW",
								 "Rotate" => "AUTO", 
							],
							"FilterEnable"=> "AUTO",
							"PsiControl"=> "USE_PSI",
							"FilterStrength"=> 0,
							"DeblockFilter"=> "DISABLED",
							"DenoiseFilter"=> "DISABLED",
							"TimecodeSource"=> "EMBEDDED",
							"FileInput"=> "s3://". MAIN_BUCKET . "/". $key ,
						]
					],
				   "OutputGroups"=> [
						[
							 "OutputGroupSettings" => [
								 "HlsGroupSettings" => [
										 "Destination" => "s3://".TRAN_BUCKET."/".$Newkey  ,
										 "DestinationSettings"=>[
											   "S3Settings"=>[
												 "AccessControl"=> [
												   "CannedAcl"=> 'PUBLIC_READ'
												]
											  ]
											]
									]
							]
						]
					]
				]
		]);
		return true;
		// print_r($result);

	}
	
	function MCendpoint(){
		$client = new Aws\MediaConvert\MediaConvertClient(api_arguments());
		//retrieve endpoint
		try {
			$result = $client->describeEndpoints([]);
			echo '<pre>';
			print_r($result);
		} catch (Aws\Exception\CredentialsException $e) {
			// output error message if fails
			echo $e->getMessage();
			echo "\n";
		}
		
	}
	
	function ElasticTranscoder720($srcKey,$StartTime=''){
		
		$Newkey = explode('.',$srcKey)[0];
		$FileName = explode('videos/',$srcKey)[1];
		
		$api_arguments 	=  api_arguments();
		$api_arguments['version'] = 'latest';
		
		$ElasticTranscoder = ElasticTranscoderClient::factory($api_arguments);
		
		$FileName = str_replace(".MOV",".mp4",$FileName);
		$FileName = str_replace(".mov",".mp4",$FileName);

		$File 	  = explode('.',$FileName);
		$FileName = $File[0].'_720.'.$File[1];
		//$StartTime = number_format( $StartTime );
		//$StartTime = str_replace( ',', '', $StartTime );

		$param = [
			'PipelineId'=> '1707464012507-xakihn',  //serverguy
			'OutputKeyPrefix'=> $Newkey . '/',
			'Input'=> [
				'Key'=> $srcKey,
				'FrameRate'=> 'auto',
				'Resolution'=> 'auto',
				'AspectRatio'=> 'auto',
				'Interlaced'=> 'auto',
				'Container'=> 'auto'
			],
			 'Outputs'=> [
				[
				  /*'Composition'=> [
					[
					  'TimeSpan'=> [
						'Duration'=> '10',
						'StartTime'=> $StartTime
					  ]
					],
					
				  	],*/
					'Watermarks' => array(
						array(
							'PresetWatermarkId' => 'BottomRight',
							'InputKey' => 'assets/image/logo.png',
						),
					),
				  
					'Key' => $FileName,
					'PresetId' => '1707551946689-tadl2z', 
				],
			   
			  ]
		];
		//print_R($param);die;
		try{
		$result = $ElasticTranscoder->createJob($param);
		return ['status'=>1,'data'=>$result];

		}catch (Aws\Exception\CredentialsException $e) {
			return ['status'=>0,'error'=>$e->getMessage()];
		}
		catch (Aws\ElasticTranscoder\Exception\ElasticTranscoderException $e) {
			return ['status'=>0, 'error'=> $e->getMessage()];
		}
		return true;
	}


	function ElasticTranscoder($srcKey,$StartTime){
		
		$Newkey = explode('.',$srcKey)[0];
		$FileName = explode('videos/',$srcKey)[1];
		
		$api_arguments 	=  api_arguments();
		$api_arguments['version'] = 'latest';
		
		$ElasticTranscoder = ElasticTranscoderClient::factory($api_arguments);
		
		$FileName = str_replace(".MOV",".mp4",$FileName);
		$FileName = str_replace(".mov",".mp4",$FileName);


		$StartTime = number_format( $StartTime );
		$StartTime = str_replace( ',', '', $StartTime );

		$param = [
			'PipelineId'=> '1707464012507-xakihn',  //serverguy
			'OutputKeyPrefix'=> $Newkey . '/',
			'Input'=> [
				'Key'=> $srcKey,
				'FrameRate'=> 'auto',
				'Resolution'=> 'auto',
				'AspectRatio'=> 'auto',
				'Interlaced'=> 'auto',
				'Container'=> 'auto'
			],
			 'Outputs'=> [
				[
				  'Composition'=> [
					[
					  'TimeSpan'=> [
						'Duration'=> '10',
						'StartTime'=> $StartTime
					  ]
					],
					
				  ],
				  
					'Key' => $FileName,
					'PresetId' => '1351620000001-000010', 
				],
			   
			  ]
		];
		try{
		$result = $ElasticTranscoder->createJob($param);
			return ['status'=>1,'data'=>$result];

		}catch (Aws\Exception\CredentialsException $e) {
			return ['status'=>0,'error'=>$e->getMessage()];
		}
		catch (Aws\ElasticTranscoder\Exception\ElasticTranscoderException $e) {
			return ['status'=>0, 'error'=> $e->getMessage()];
		}
		return true;
	}
	
	function ElasticTranscoderLivePreview($srcKey,$index,$StartTime = 0){
		
		$r 	= explode('live/',$srcKey)[1];
		$FileName = explode('/',$r )[0] . ".mp4";
		
		$DestiKey = $srcKey;
		// echo $srcKey.$FileName;die;
		createCasheInvalidatin(['/'.$srcKey.$FileName]);
		
		$r = s3_delete_object([$srcKey.$FileName],TRAN_BUCKET);
		
		$api_arguments 	=  api_arguments();
		$api_arguments['version'] = '2012-09-25';

		$ElasticTranscoder = ElasticTranscoderClient::factory($api_arguments);
		
		$param = [
			'PipelineId'=> '1707464183604-narfqe',
			'OutputKeyPrefix'=> $DestiKey ,
			'Input'=> [
				'Key'=> $srcKey.$index,
				'FrameRate'=> 'auto',
				'Resolution'=> 'auto',
				'AspectRatio'=> 'auto',
				'Interlaced'=> 'auto',
				'Container'=> 'auto'
			],
			 'Outputs'=> [
				[
				  'Composition'=> [
					[
					  'TimeSpan'=> [
						'Duration'=> '7',
						'StartTime'=> number_format( $StartTime )
					  ]
					],
					
				  ],
				  
					'Key' => $FileName,
					'PresetId' => '1351620000001-000010', 
				],
			   
			  ]
		];
		try{
		$result = $ElasticTranscoder->createJob($param);
			return ['status'=>1,'data'=>$result];

		}catch (Aws\Exception\CredentialsException $e) {
			return ['status'=>0,'error'=>$e->getMessage()];
		}
		return true;
	}
	
	function createCasheInvalidatin($pathInArray){
		
		try{
			$api_arguments 	=  api_arguments();
			$api_arguments['version'] = '2018-11-05';
		
			$CasheClient = CloudFrontClient::factory($api_arguments);
			
			$CasheClient = $CasheClient->createInvalidation([
				'DistributionId' => 'E2EZL5XBKDF018', // REQUIRED  serverguy
				'InvalidationBatch' => [ // REQUIRED
					'CallerReference' => time(), // REQUIRED
					'Paths' => [ // REQUIRED
						'Items' => $pathInArray,
						'Quantity' => sizeof($pathInArray), // REQUIRED
					],
				],
			]);
			$CasheClient =  ['status'=>1,'data'=> $CasheClient];
		}catch(Aws\Exception\UnresolvedApiException $e){
			$CasheClient =  ['status'=>0,'error'=>$e->getMessage()];
		}catch(InvalidArgumentException $e){
			$CasheClient =  ['status'=>0,'error'=>$e->getMessage()];
		}catch(Aws\CloudFront\Exception\CloudFrontException $e){
			$CasheClient =  ['status'=>0,'error'=>$e->getMessage()];
		}
		
		
		return $CasheClient;
	}
	
	function copyinbatch($sourceKeyname,$targetKeyname ,$folder , $file_count = 200){
		$sourceBucket 	= STREAM_BUCKET;
		$targetBucket 	= TRAN_BUCKET;
		
		$api_arguments 	= api_arguments() ;
		$s3Client 		= new S3Client( $api_arguments );
			
		$batch = array();
		for ($i = 0; $i <= $file_count; $i++) {
			$batch[] = $s3Client->getCommand('CopyObject', [
				'ACL'				   => 'public-read',
				'CopySource'           => "{$sourceBucket}/{$sourceKeyname}{$i}.ts",
				'Bucket'               => $targetBucket ,
				'Key'                  => $targetKeyname.$folder.'/'.$i.'.ts',
			]);
		}
		try {
			$results = CommandPool::batch($s3Client, $batch);
			foreach($results as $result) {
				if ($result instanceof ResultInterface) {
					// Result handling here
				}
				if ($result instanceof AwsException) {
					// AwsException handling here
				}
			}
		} catch (\Exception $e) {
			// General error handling here
		}
	}
	function copyObject($sourceKeyname,$targetKeyname ,$stream_id,$tfile,$srcFile ){
		try{
			
			$sourceBucket 	= STREAM_BUCKET;
			$targetBucket 	= TRAN_BUCKET;
			
			$api_arguments 	= api_arguments() ;
			$s3Client 		= new S3Client( $api_arguments );
			$result = $s3Client->copyObject([
				'ACL'				   => 'public-read',
				'CopySource'           => "{$sourceBucket}/{$sourceKeyname}{$srcFile}",
				'Bucket'               => $targetBucket ,
				'Key'                  => $targetKeyname.$stream_id.'/'.$tfile,
				// 'ServerSideEncryption' => 'AES256',
			]);
			
			$ObjectURL = $result->get( '@metadata' );
			
			return array('status'=>1,'statusCode'=>$ObjectURL['statusCode']);
		}catch (Aws\S3\Exception\S3Exception $e) {
			return array('status'=>0,'message'=>$e->getMessage());
		}catch (Aws\S3\Exception\PermanentRedirectException $e) {
			return array('status'=>0,'message'=>$e->getMessage());
		}
	}
	
	function putObjectAcl($Key, $bucket_name='',$region=''){
		if($bucket_name == ''){
			global $bucket_name;
		}
		
		try{
			$api_arguments =  api_arguments() ;
			
			if(!empty($region))
			$api_arguments['region'] = $region;
			
			$s3Client = new S3Client($api_arguments);
		
			$result = $s3Client->putObjectAcl([
				'ACL' => 'public-read',
				'Bucket' => $bucket_name, // REQUIRED
				'Key' => $Key, // REQUIRED
				
			]);
			// $ObjectURL = $result->get('key');
			return $result ;
		}catch (S3Exception $e) {
			return $e->getMessage();
		}catch (Aws\S3\Exception\PermanentRedirectException $e) {
			return $e->getMessage();
		}
		
		
	}


	function copyObjectFilmhub($sourceKeyname,$targetKeyname ){
		try{
			
			$sourceBucket 	= FILMHUB_BUCKET;
			$targetBucket 	=  MAIN_BUCKET;
			
			
			$api_arguments 	= api_arguments();
			$s3Client 		= new S3Client( $api_arguments );
			$result = $s3Client->copyObject([
				'ACL'				   => 'public-read',
				'CopySource'           => "{$sourceBucket}/{$sourceKeyname}",
				'Bucket'               => $targetBucket,
				'Key'                  => $targetKeyname,
				'ContentType'			=> 'video/mp4'
				// 'ServerSideEncryption' => 'AES256',
			]);
			
			$ObjectURL = $result->get( '@metadata' );
			
			return array('status'=>1,'statusCode'=>$ObjectURL['statusCode'],'key'=>$targetKeyname);
		}catch (Aws\S3\Exception\S3Exception $e) {
			return array('status'=>0,'message'=>$e->getMessage());
		}catch (Aws\S3\Exception\PermanentRedirectException $e) {
			return array('status'=>0,'message'=>$e->getMessage());
		}
	}

function getSize($sourceKeyname){
	try{
		$sourceBucket 	= FILMHUB_BUCKET;
		$api_arguments 	= api_arguments();
		$s3Client 		= new S3Client( $api_arguments );
		$obj_data = $s3Client->headObject([
			'Bucket' => $sourceBucket,
			'Key'    => $sourceKeyname
		]);
		
		return array('status'=>1,'filesize'=>$obj_data['ContentLength']);

	}catch (Aws\S3\Exception\S3Exception $e) {
		return array('status'=>0,'message'=>$e->getMessage());
	}catch (Aws\S3\Exception\PermanentRedirectException $e) {
		return array('status'=>0,'message'=>$e->getMessage());
	}
}
	