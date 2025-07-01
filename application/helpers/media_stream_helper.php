<?php
	require 'awsv3/vendor/autoload.php';

	use Aws\MediaLive\MediaLiveClient;
	use Aws\MediaPackage\MediaPackageClient;
	use Aws\Exception\AwsException;
	use Aws\Ssm\SsmClient;
	use Aws\CloudWatch\CloudWatchClient;
	use Aws\MediaTailor\MediaTailorClient;
	
	function client_api_arguments(){       
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


	function GetInputGroup(){
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->listInputSecurityGroups([
				'MaxResults' => 1,
				'NextToken' => '',
			]);
			
			return array('status'=>1 ,'data'=>$result);
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}

	function createMediaInput($optionArr=array()){
		extract($optionArr);	
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->createInput([
				'inputClass' => 'SINGLE_PIPELINE', //STANDARD
				'Destinations' => [
					[
						'StreamName' => 'discovered_live_'.$uid.'/'.rand(),
					]
					
					// ,[
						// 'StreamName' => 'discovered_'.$uid.'_2/'.rand(),
					// ]
				],
				'InputSecurityGroups' => [$InputSecurityGroups],
				'InputSourceType' => 'STATIC',
				'Name' => 'input_'.$uid,
				'RequestId' => '',
				// 'RoleArn' => '',
				'Tags' => [],
				'Type' => 'RTMP_PUSH',
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	function updateMediaInput($optionArr){
		extract($optionArr);
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->updateInput([
			'inputClass' => 'SINGLE_PIPELINE', //STANDARD
			'Destinations' => [
				[
					'StreamName' => 'discovered_live_'.$uid.'/'.rand(),
				]
			],
			'InputId' => $InputId, // REQUIRED
		]);
			
		return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	function createMediaPackageChannel($optionArr=array()){
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
			$result = $MediaPackageClient->createChannel([
				'Description' => '',
				'Id' => 'package_'.$uid, // REQUIRED
				'Tags' => [],
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function CreateEndPoint_old($optionArr=array()){
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
			$result = $MediaPackageClient->createOriginEndpoint([
			'ChannelId' => $channel_id, // REQUIRED
			'Description' => '',
			'HlsPackage' => [
				'AdMarkers' => 'PASSTHROUGH', //SCTE35_ENHANCED
				'AdTriggers' => [],//'SPLICE_INSERT'
				'AdsOnDeliveryRestrictions' => 'RESTRICTED',
			],
			'Id' =>'endpoint_'.$uid, // REQUIRED
			'ManifestName' => 'index',
			'Origination' => 'ALLOW',
			'StartoverWindowSeconds' => 86400, //86400
			"Segment" => [
				'Encryption' => [
					'SegmentDurationSeconds' => 3
				]
			],
			'LowLatencyHlsManifests' => [
				[
					'ManifestName' => '',
					'ManifestWindowSeconds' => 30, // Duration of the manifest
				]
			],
			'Tags' => [],
			
		]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
		
	}

	function CreateEndPoint($optionArr=array()){
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
			$result = $MediaPackageClient->createOriginEndpoint([
			'ChannelId' => $channel_id, // REQUIRED
			'Description' => '',
			'HlsPackage' => [
				'AdMarkers' => 'PASSTHROUGH', //SCTE35_ENHANCED
				'AdTriggers' => [],//'SPLICE_INSERT'
				'AdsOnDeliveryRestrictions' => 'RESTRICTED',
				'SegmentDurationSeconds' => 1
			],
			'Id' =>'endpoint_'.$uid, // REQUIRED
			'ManifestName' => 'index',
			'Origination' => 'ALLOW',
			'StartoverWindowSeconds' => 86400, //86400
			"Segment" => [
				'Encryption' => [
					'SegmentDurationSeconds' => 1
				]
			],
			'LowLatencyHlsManifests' => [
				[
					'ManifestName' => '',
					'ManifestWindowSeconds' => 30, // Duration of the manifest
				]
			],
			'Tags' => [],
			
		]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
		
	}
	
	function DeleteEndPoint($optionArr=array()){
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
			$result = $MediaPackageClient->DeleteOriginEndpoint([
				'Id' => $origin_endpoint_id, // REQUIRED
			]);
			
		return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
		
	}
	
	function createParamter($optionArr = array()){
		extract($optionArr);
		try{
			$SsmClient = new SsmClient( client_api_arguments() );
			$result = $SsmClient->putParameter([
					'Name' => '/medialive/'.$name, // REQUIRED
					'Type' => 'SecureString',
					'Value' => $value, // REQUIRED
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function createMediaChannel($optionArr=array()){
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaPackageClient->createChannel(
			[
			  "Name" 	=> 'live_channel_'.$uid,
			  'Id'		=> 'mid_'.$uid,
			  "RoleArn" => "arn:aws:iam::933624745932:role/MediaLiveAccessRole",
			  "InputSpecification" => [
				"Codec" 		=> "AVC",
				"Resolution" 	=> "HD",
				"MaximumBitrate"=> "MAX_20_MBPS"
			  ],
			  "LogLevel" => "DISABLED",
			  "Tags" => [],
			  "ChannelClass" => "SINGLE_PIPELINE",
			  "PipelineDetails" => [],
			  "MaintenanceWindow" => "NOT_NEEDED",
			  "MaintenanceStatus" => "",
			  "InputAttachments" => [
				[
				  "InputAttachmentName" => $input_name,
				  "InputId" => $input_id,
				  "inputSettings" => [
					"SourceEndBehavior" => "CONTINUE",
					"InputFilter" => "AUTO",
					"FilterStrength" => 1,
					"DeblockFilter" => "DISABLED",
					"DenoiseFilter" => "DISABLED",
					"Smpte2038DataPreference" => "IGNORE",
					"AudioSelectors" => [],
					"CaptionSelectors" => []
				  ]
				]
			  ],
			  "Destinations" => [
				[
				  "Id" => 'Media-'.$uid,
				  "Settings" => [],
				  "MediaPackageSettings" => [
					[
					  "ChannelId" => "package_{$uid}"
					]
				  ]
				]
			  ],
			  "EncoderSettings" => [
				"AudioDescriptions" => [
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 64000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_3_aac64"
				  ],
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 96000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_1_aac96"
				  ],
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 96000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_3_aac96"
				  ]
				],
				"captionDescriptions" => [],
				"OutputGroups" => [
				  [
					"OutputGroupSettings" => [
					  "MediaPackageGroupSettings" => [
						"Destination" => [
						  "DestinationRefId" => 'Media-'.$uid
						]
					  ]
					],
					"Name" => 'Media-'.$uid,
					"Outputs" => [
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "1280_720_1",
						"VideoDescriptionName" => "video_1280_720_1",
						"AudioDescriptionNames" => [
						  "audio_3_aac96"
						],
						"CaptionDescriptionNames" => []
					  ],
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "640_360",
						"VideoDescriptionName" => "video_640_360",
						"AudioDescriptionNames" => [
						  "audio_3_aac64"
						],
						"CaptionDescriptionNames" => []
					  ],
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "854_480",
						"VideoDescriptionName" => "video_854_480",
						"AudioDescriptionNames" => [
						  "audio_1_aac96"
						],
						"CaptionDescriptionNames" => []
					  ]
					]
				  ]
				],
				"TimecodeConfig" => [
				  "Source" => "SYSTEMCLOCK"
				],
				"VideoDescriptions" => [
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 800000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 800000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 360,
					"Name" => "video_640_360",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 640
				  ],
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 1200000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 1200000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 480,
					"Name" => "video_854_480",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 854
				  ],
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 3300000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 3300000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 720,
					"Name" => "video_1280_720_1",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 1280
				  ]
				]
			  ],
			 
			]
		);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	
	function createMediaChannel_old($optionArr=array()){
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaPackageClient->createChannel(
			
			[
			  "Name" 	=> 'live_channel_'.$uid,
			  'Id'		=> 'mid_'.$uid,
			  "RoleArn" => "arn:aws:iam::201068771454:role/MediaLiveAccessRole",
			  "InputSpecification" => [
				"Codec" 		=> "AVC",
				"Resolution" 	=> "HD",
				"MaximumBitrate"=> "MAX_20_MBPS"
			  ],
			  "LogLevel" => "DISABLED",
			  "Tags" => [],
			  "ChannelClass" => "SINGLE_PIPELINE",
			  "PipelineDetails" => [],
			  "MaintenanceWindow" => "NOT_NEEDED",
			  "MaintenanceStatus" => "",
			  // "arn" => "arn:aws:medialive:us-east-1:201068771454:channel:6223448",
			  "InputAttachments" => [
				[
				  "InputAttachmentName" => $input_name,
				  "InputId" => $input_id,
				  "inputSettings" => [
					"SourceEndBehavior" => "CONTINUE",
					"InputFilter" => "AUTO",
					"FilterStrength" => 1,
					"DeblockFilter" => "DISABLED",
					"DenoiseFilter" => "DISABLED",
					"Smpte2038DataPreference" => "IGNORE",
					"AudioSelectors" => [],
					"CaptionSelectors" => []
				  ]
				]
			  ],
			  // "state" => "IDLE",
			  // "pipelinesRunningCount" => 0,
			  "Destinations" => [
				[
				  "Id" => 'Media-'.$uid,
				  "Settings" => [],
				  "MediaPackageSettings" => [
					[
					  "ChannelId" => "package_{$uid}"
					]
				  ]
				]
			  ],
			  // "egressEndpoints" => [
				// [
				  // "sourceIp" => "52.206.107.73"
				// ]
			  // ],
			  "EncoderSettings" => [
				"AudioDescriptions" => [
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 64000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_2_aac64"
				  ],
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 64000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_3_aac64"
				  ],
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 96000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_1_aac96"
				  ],
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 96000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_2_aac96"
				  ],
				  [
					"AudioSelectorName" => "default",
					"CodecSettings" => [
					  "AacSettings" => [
						"Bitrate" => 96000,
						"RawFormat" => "NONE",
						"Spec" => "MPEG4"
					  ]
					],
					"AudioTypeControl" => "FOLLOW_INPUT",
					"LanguageCodeControl" => "FOLLOW_INPUT",
					"Name" => "audio_3_aac96"
				  ]
				],
				"captionDescriptions" => [],
				"OutputGroups" => [
				  [
					"OutputGroupSettings" => [
					  "MediaPackageGroupSettings" => [
						"Destination" => [
						  "DestinationRefId" => 'Media-'.$uid
						]
					  ]
					],
					"Name" => 'Media-'.$uid,
					"Outputs" => [
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "960_540",
						"VideoDescriptionName" => "video_960_540",
						"AudioDescriptionNames" => [
						  "audio_2_aac96"
						],
						"CaptionDescriptionNames" => []
					  ],
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "1280_720_1",
						"VideoDescriptionName" => "video_1280_720_1",
						"AudioDescriptionNames" => [
						  "audio_3_aac96"
						],
						"CaptionDescriptionNames" => []
					  ],
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "480_270",
						"VideoDescriptionName" => "video_480_270",
						"AudioDescriptionNames" => [
						  "audio_2_aac64"
						],
						"CaptionDescriptionNames" => []
					  ],
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "640_360",
						"VideoDescriptionName" => "video_640_360",
						"AudioDescriptionNames" => [
						  "audio_3_aac64"
						],
						"CaptionDescriptionNames" => []
					  ],
					  [
						"OutputSettings" => [
						  "MediaPackageOutputSettings" => []
						],
						"OutputName" => "768_432",
						"VideoDescriptionName" => "video_854_480",
						"AudioDescriptionNames" => [
						  "audio_1_aac96"
						],
						"CaptionDescriptionNames" => []
					  ]
					]
				  ]
				],
				"TimecodeConfig" => [
				  "Source" => "SYSTEMCLOCK"
				],
				"VideoDescriptions" => [
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 400000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 15000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "DISABLED",
						"GopNumBFrames" => 0,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 400000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 272,
					"Name" => "video_480_270",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 480
				  ],
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 800000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 800000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 360,
					"Name" => "video_640_360",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 640
				  ],
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 1200000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 1200000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 432,
					"Name" => "video_854_480",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 768
				  ],
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 2200000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 2200000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 540,
					"Name" => "video_960_540",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 960
				  ],
				  [
					"CodecSettings" => [
					  "H264Settings" => [
						"ColorMetadata" => "INSERT",
						"AdaptiveQuantization" => "AUTO",
						"Bitrate" => 3300000,
						"EntropyEncoding" => "CAVLC",
						"FlickerAq" => "ENABLED",
						"FramerateControl" => "SPECIFIED",
						"FramerateNumerator" => 30000,
						"FramerateDenominator" => 1001,
						"GopBReference" => "ENABLED",
						"GopNumBFrames" => 3,
						"GopSize" => 1,
						"GopSizeUnits" => "SECONDS",
						"Level" => "H264_LEVEL_AUTO",
						"LookAheadRateControl" => "HIGH",
						"ParControl" => "SPECIFIED",
						"Profile" => "HIGH",
						"RateControlMode" => "QVBR",
						"MaxBitrate" => 3300000,
						"Syntax" => "DEFAULT",
						"SceneChangeDetect" => "ENABLED",
						"SpatialAq" => "ENABLED",
						"TemporalAq" => "ENABLED"
					  ]
					],
					"Height" => 720,
					"Name" => "video_1280_720_1",
					"ScalingBehavior" => "DEFAULT",
					"Width" => 1280
				  ]
				]
			  ],
			 
			]
		);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function UpdateScteSchedule($optionArr){
		extract($optionArr);
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->batchUpdateSchedule([
			'ChannelId' => $channel_id, // REQUIRED
			'Creates' => [
				'ScheduleActions' => [ // REQUIRED
					[
						'ActionName' => 'Scte-Media-'. rand() , // REQUIRED
						'ScheduleActionSettings' => [ // REQUIRED
							'Scte35SpliceInsertSettings' => [
								'Duration' => 2700000,
								'SpliceEventId' => rand() , // REQUIRED
							],
							
						],
						'ScheduleActionStartSettings' => [ // REQUIRED
							'ImmediateModeScheduleActionStartSettings' => [
								"time" => date('Y-m-d\TH:i:s')
							]
						]
					]
				]
			]
		]);
			
		return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function getChannelDetail($channel_id){
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->describeChannel([
			'ChannelId' => $channel_id, // REQUIRED
		]);
			
		return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	function StartMediaChannel($channel_id){
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->startChannel([
				'ChannelId' => $channel_id, // REQUIRED
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	function StopMediaChannel($channel_id){
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			$result = $MediaClient->stopChannel([
			'ChannelId' => $channel_id, // REQUIRED
		]);
			
		return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	function createMediaHarvesting($optionArr){
		
		extract($optionArr);
		try{
			$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
			$result = $MediaPackageClient->createHarvestJob([
				'EndTime' => $end_time, // REQUIRED
				'Id' => $random_id, // REQUIRED
				'OriginEndpointId' => $origin_endpoint_id, // REQUIRED
				'S3Destination' => [ // REQUIRED
					'BucketName' => TRAN_BUCKET, // REQUIRED
					'ManifestKey' => $key, // REQUIRED
					// 'CannedACL' => 'bucket-owner-full-control',
					// 'RoleArn' => 'arn:aws:iam::201068771454:role/MediaPakageRoleForAccessS3', // REQUIRED  orignial 
					'RoleArn' => 'arn:aws:iam::933624745932:role/MediaPakageRoleForAccessS3Serverguy', // REQUIRED serverguy
				],
				'StartTime' => $start_time, // REQUIRED
			]);
			
		return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function getMetricStatistics($optionArr){
		extract($optionArr);
		try{
			$CloudWatchClient = new CloudWatchClient( client_api_arguments() );
			$EndTime =  gmdate("Y-m-d\TH:i:s\Z");
			$startTime = gmdate("Y-m-d\TH:i:s\Z", time() - 120);
			// echo $EndTime.'///'.$startTime;die;
			$result = $CloudWatchClient->getMetricStatistics([
				'Dimensions' => [
					[
						'Name' => 'Pipeline', // REQUIRED
						'Value' => $Pipeline, // REQUIRED
					],
					[
						'Name' => 'ChannelId', // REQUIRED
						'Value' => $ChannelId, // REQUIRED
					]
					// ...
				],
				'EndTime' => $EndTime, // REQUIRED
				// 'ExtendedStatistics' => ['<string>', ...],
				'MetricName' => $MetricName, // REQUIRED 
				'Namespace' => 'AWS/MediaLive', // REQUIRED
				'Period' => '1', // REQUIRED
				'StartTime' => $startTime, // REQUIRED
				'Statistics' => ['Average'],
				// 'Unit' => 'Seconds',
				// 'Unit' => 'Seconds|Microseconds|Milliseconds|Bytes|Kilobytes|Megabytes|Gigabytes|Terabytes|Bits|Kilobits|Megabits|Gigabits|Terabits|Percent|Count|Bytes/Second|Kilobytes/Second|Megabytes/Second|Gigabytes/Second|Terabytes/Second|Bits/Second|Kilobits/Second|Megabits/Second|Gigabits/Second|Terabits/Second|Count/Second|None',
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	
	function Ads(){
		return 'https://pubads.g.doubleclick.net/gampad/live/ads?iu=/22019190093/dtv_livestreaming_web&description_url=https%3A%2F%2Fdiscovered.tv%2F&tfcd=0&npa=0&sz=1x1%7C400x300%7C640x480&cmsid=2528975&vid=[player_params.vid]&gdfp_req=1&output=vast&unviewed_position_start=1&env=vp&impl=s&devicetype=[player_params.devicetype]&correlator=[avail.random]';
		return "https://tv.springserve.com/vast/[player_params.placementId]?cb=[avail.random]&url=[session.referer]&pod_max_dur=[session.avail_duration_secs]&pod_ad_slots=[session.avail_num]&w=[player_params.w]&h=[player_params.h]&us_privacy=1---&app_bundle=[player_params.app_bundle]&app_name=[player_params.app_name]&app_store_url=[player_params.app_store_url]&did=[player_params.did]";
		// return "https://secure.adnxs.com/ssptv?id=[player_params.placementId]&referrer=discovered.tv&vwidth=1920&vheight=1080&vcontext=1&skippable=1&vmaxduration=60&kw_viewer_id=[player_params.viewerid]&kw_video_id=[player_params.video_id]&kw_utm_source=discovered&kw_user_id=[player_params.userid]&kw_source=discovered&kw_icon=1&kw_genre=[player_params.genreid]&kw_category=[player_params.categoryid]&us_privacy=1---&ip=[session.client_ip]&ua=[session.user_agent]&devicetype=[player_params.devicetype]&correlator=[avail.random]";
		// return "https://secure.adnxs.com/ssptv?id=[player_params.placementId]&referrer=discovered.tv&vwidth=1920&vheight=1080&vcontext=1&vframeworks=1,2&skippable=1&vmaxduration=60&kw_viewer_id=[player_params.viewerid]&kw_video_id=[player_params.video_id]&kw_utm_source=discovered&kw_user_id=[player_params.userid]&kw_source=discovered&kw_icon=1&kw_genre=[player_params.genreid]&kw_category=[player_params.categoryid]&us_privacy=1---&ip=[session.client_ip]&ua=[session.user_agent]&devicetype=[player_params.devicetype]&correlator=[avail.random]";
		// return 'https://pubads.g.doubleclick.net/gampad/live/ads?iu=/22019190093/discovered.tv_live_test&description_url=https%3A%2F%2Fdiscovered.tv%2F&tfcd=0&npa=0&sz=1x1%7C400x300%7C640x480&cust_params=[player_params.cust_params]&cmsid=2528975&vid=[player_params.vid]&gdfp_req=1&output=vast&unviewed_position_start=1&env=vp&impl=s&devicetype=[player_params.devicetype]&correlator=[avail.random]';
	}
	function putPlaybackConfiguration($optionArr){
		// $ci = & get_instance();
		// $ci->load->helper('media_stream');
		extract($optionArr);
		try{
			$MediaTailorClient = new MediaTailorClient( client_api_arguments() );
			
			$result = $MediaTailorClient->putPlaybackConfiguration([
				'AdDecisionServerUrl' => Ads(),
				'ManifestProcessingRules' => [
					'AdMarkerPassthrough' => [
						'Enabled' => true,
					],
				],
				'Name' => 'mediatailor-live-'.$uid,
				'PersonalizationThresholdSeconds' => 8,
				'SlateAdUrl' => AMAZON_URL.'Discovered+Video.mp4',
				// 'Tags' => ['<string>', ...],
				// 'TranscodeProfileName' => '<string>',
				'VideoContentSourceUrl' => str_replace('index.m3u8', '', $EndPoint) ,
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	
	function deleteMediaTailorConfig($optionArr){
		
		extract($optionArr);
		try{
			// print_r( client_api_arguments());die;
			$MediaTailorClient = new MediaTailorClient( client_api_arguments() );
			
			$result = $MediaTailorClient->deletePlaybackConfiguration([
				 'Name' => $ChannelName, // REQUIRED
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function deleteMediaChannel($channel_id){
		// extract($optionArr);
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			
			$result = $MediaClient->deleteChannel([
				'ChannelId' => $channel_id, // REQUIRED
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}

	function deleteMediaInput($input_id){
		// extract($optionArr);
		try{
			$MediaClient = new MediaLiveClient( client_api_arguments() );
			
			$result = $MediaClient->deleteInput([
				'InputId' => $input_id, // REQUIRED
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}
	
	function deleteMediaPackageChannel($channel_id){
		
		// extract($optionArr);
		try{
			$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
			$result = $MediaPackageClient->deleteChannel([
				'Id' => $channel_id, // REQUIRED
			]);
			
			return array('status'=>1 ,'data'=>$result);
			
		}catch (AwsException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}catch (InvalidArgumentException $e) {
			return array('status'=>0 , 'message'=> $e->getMessage());
		}
	}

	function CreateHarvesting($uid = 215){
			$CI   	=	& get_instance();
			$resp 	= 	[];
			$MDLV 	= 	$CI->DatabaseModel->select_data('*','users_medialive_info',['user_id'=>$uid],1);
			
			if(!empty($MDLV)){
				if($MDLV[0]['is_harvested'] == 0 && $MDLV[0]['FST'] != NULL && $MDLV[0]['FET']  != NULL ){
				
					$channel_start_time 	= $MDLV[0]['CST'];
					$start_time 			= $MDLV[0]['FST'];
					
					$diffInFrameNChannel 	= datediff($start_time,$channel_start_time);
					
					// $start_time = date('Y-m-d H:i:s', strtotime('+'.$diffInFrameNChannel.' seconds',strtotime($start_time)) );
					$start_time 	= date('Y-m-d H:i:s', strtotime('-5 seconds',strtotime($start_time)) );
					$end_time 	= date('Y-m-d H:i:s', strtotime('-2 seconds',strtotime($MDLV[0]['FET'])) );
				
					$start_time = timez( $start_time,$MDLV[0]['tz_offset']) ;
					$end_time 	= timez($end_time,$MDLV[0]['tz_offset']) ;
					// echo $start_time;
					// echo '<br>';
					// echo $end_time;die;
					$duration 	= datediff($end_time,$start_time);
					
					if(isset($MDLV[0]['media_info']) && !empty($MDLV[0]['media_info'])){ 
						
						$media_info 	= json_decode($MDLV[0]['media_info'] , true);
						$EOP 			= $media_info['endpoint']['Id'];
						
						$random_id 		= 'index_'.$media_info['package']['HlsIngest']['IngestEndpoints'][0]['Id'];
						$key  			= 'aud_'.$uid.'/live/'.$random_id.'/'.$random_id.'.m3u8' ; 

						$optionArr 		= ['start_time'=>$start_time,'end_time'=>$end_time,'random_id'=>$random_id,'origin_endpoint_id'=>$EOP,'key'=>$key];
					
						$r = createMediaHarvesting($optionArr); 
						
						if($r['status'] == 1){
							$update = [
										'is_live' 		=> 	0 , 
										'live_pid' 		=> 	'' , 
										'schedule_time'	=> 	'',
										'is_scheduled'	=> 	0,
										'FET'			=> 	'',
										'FST'			=> 	'',
										'is_harvested'	=> 	1,      // it means harvesting started
										'is_recorded'	=> 	1,      //it means , recording under process
									];
							
							$CI->DatabaseModel->access_database('users_medialive_info','update',$update,['user_id' => $uid]);
							
							$update = 
									[ 'uploaded_video' 	=> 	'aud_'.$uid.'/live/'.$random_id.'.mp4',
									  'is_stream_live'	=>	0,
									  'is_video_processed'	=> 1,
									  'video_duration'	=>	$duration
									];
							
							$CI->DatabaseModel->access_database('channel_post_video','update',$update,['post_id' => $MDLV[0]['live_pid'] ]);
							
							$resp = ['status'=>1,'message'=>'Harvest jobs created.'];
						}else{
							$resp = ['status'=>0,'message'=>$r['message']];
						}
					}else{
						$resp = ['status'=>0,'message'=>'Empty media info'];
					}
					
				}else{
					$resp = ['status'=>0,'message'=>'Already harvested'];
				}
			}else{
				$resp = ['status'=>0,'message'=>'Empty data'];
			}
		
			return $resp;
		}
		
		function datediff($end,$start){ 
			$CI =& get_instance();
			$CI->load->helper('my');
			$r 	= caculate_time_differece($end,$start);
			return $r['minutes'] * 60 + $r['seconds']; 
		}
		
		function timez($dateTime,$offset){
			$CI =& get_instance();
			$r = $CI->common->manageTimezone($dateTime,$clock="H",$offset); 
			$r = date('Y-m-d\TH:i:s',strtotime($r));
			
			$offset = (int)$offset;
			$sign =   $offset < 0 ? '+' : '-';
			
			$hours = intdiv(abs($offset), 60). ':'. (abs($offset) % 60);
			$hours = date('H:i',strtotime($hours));
			return $r.$sign.$hours;
		}
		
		function harvest(){
			$CI   	=	& get_instance();

			$update = [
						'is_live' 		=> 	0 , 
						'live_pid' 		=> 	'' , 
						'schedule_time'	=> 	'',
						'is_scheduled'	=> 	0,
						'FET'			=> 	'',
						'FST'			=> 	'',
						'is_harvested'	=> 	1,
						];
			
			$CI->DatabaseModel->access_database('users_medialive_info','update',$update,['user_id'=> '218' ]);
		}

		function listMediaChannels($uid){ 
			try{
				$MediaClient = new MediaLiveClient( client_api_arguments() );
				$result = $MediaClient->ListChannels([
					'MaxResults' => 10,
					'NextToken' => '',
				]);
				$channel_id = '';
				foreach($result['Channels'] as $channel){
					if($channel['Name'] == 'live_channel_'.$uid ){
						$channel_id = $channel['Id'];
					}
				}
				$input_id = '';
				$result = $MediaClient->listInputs([
					'MaxResults' => 10,
					'NextToken' => '',
				]);
				foreach($result['Inputs'] as $input){
					if($input['Name'] == 'input_'.$uid ){
						$input_id = $input['Id'];
					}
				}
				return array('status'=>1 ,'channel_id'=>$channel_id,'input_id'=>$input_id);
				
			}catch (AwsException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}catch (InvalidArgumentException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}
		}
	
		function listHarvestJobs($package_id){
		
			try{
				$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
				$result = $MediaPackageClient->listHarvestJobs([
					'IncludeChannelId' => $package_id, // REQUIRED
				]);
				
			return array('status'=>1 ,'data'=>$result);
				
			}catch (AwsException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}catch (InvalidArgumentException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}
		}
	
		function describeHarvest($proccess_id){
		
			try{
				$MediaPackageClient = new MediaPackageClient( client_api_arguments() );
				$result = $MediaPackageClient->describeHarvestJob([
					'Id' => $proccess_id, // REQUIRED
				]);
				
			return array('status'=>1 ,'data'=>$result);
				
			}catch (AwsException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}catch (InvalidArgumentException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}
		}

		function listofAllMediaChannels(){ 
			try{
				$MediaClient = new MediaLiveClient( client_api_arguments() );
				$result1 = $MediaClient->ListChannels([
					'MaxResults' => 20,
					'NextToken' => '',
				]);
				$result2 = $MediaClient->listInputs([
					'MaxResults' => 20,
					'NextToken' => '',
				]);
				return array('status'=>1 ,'channels'=>$result1,'inputs'=>$result2);
				
			}catch (AwsException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}catch (InvalidArgumentException $e) {
				return array('status'=>0 , 'message'=> $e->getMessage());
			}
		}