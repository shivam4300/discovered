<?php
	require 'aws/vendor/autoload.php';

	use Aws\CognitoIdentity\CognitoIdentityClient;
	use Aws\AwsClient ;
	use Aws\Sts\StsClient ;
	use Aws\Exception\CredentialsException ;
	use Aws\Exception\AwsException ;
	
	global $bucket_name,$providedIdentity,$IdentityPool,$AuthRole,$RoleSessionName;

	$bucket_name = MAIN_BUCKET;
	
	$providedIdentity = AWS_PROVIDED_IDENTITY;         /*******AWS Acc - 933624745932 */  
	$IdentityPool 	  = AWS_IDENTITY_POOL;
	$AuthRole		  = AWS_AUTH_ROLE;
	$RoleSessionName  = AWS_ROLE_SESSION_NAME;

	function CognitoObject(){
		try{
			$client = CognitoIdentityClient::factory(array(
				'version'     => 'latest',
				'credentials' => array(
						'key'         => BUCKET_KEY,
						'secret'      => BUCKET_SECRET,
					),
				'region'  => 'us-east-1'
			));
			return array('status'=>1,'client'=>$client);
		}catch(CredentialsException $e){
			return array('status'=>0,'message'=>$e->getMessage());
		}
	}
	
	
	
	function getIdToken($identityfier){
		global $providedIdentity,$IdentityPool,$AuthRole,$RoleSessionName ;
		$client = CognitoObject();
		
		if($client['status'] == 1){
			try {
				$client = $client['client'];
				$TokenDuration = 18000;
				$result = $client->getOpenIdTokenForDeveloperIdentity([
					'IdentityPoolId' => $providedIdentity, // REQUIRED
					'Logins' => [$IdentityPool => $identityfier], // REQUIRED,
					'TokenDuration'=>$TokenDuration
				
				]);
				$time = date("m/d/Y h:i:s A", time() + $TokenDuration);
				return $idToken = array('status'=>1,'IdentityId'=>$result->get('IdentityId'),'Token' => $result->get('Token'),'expire_in' =>$time, 'exp_timestamp'=>strtotime($time), 'RoleArn'=>$AuthRole,'RoleSessionName'=>$RoleSessionName,'TokenDuration'=>$TokenDuration);
			
			} catch (AwsException $e) {
				return $idToken = array('status'=>0 ,'message'=>$e->getMessage() );
			}
		}else{
			return $idToken = array('status'=> 0 ,'message'=>$obj['message']);
		}
		
	}
	
	function getTempCredential(){
		try{
		$t = getIdToken('ajaydeep313');
		// $t['Token'] = 'eyJraWQiOiJ1cy13ZXN0LTIxIiwidHlwIjoiSldTIiwiYWxnIjoiUlM1MTIifQ.eyJzdWIiOiJ1cy13ZXN0LTI6MjE1ODVmM2UtNTU0MC00OWJiLWI4OGUtZDJhZjdkNzAxM2UxIiwiYXVkIjoidXMtd2VzdC0yOjEwMDRjNDZmLWEyYmQtNDJkMi1iOGZkLTQxZTM0ZWEwZmI3ZiIsImFtciI6WyJhdXRoZW50aWNhdGVkIiwibXkuZGlzY292ZXJlZC50diIsIm15LmRpc2NvdmVyZWQudHY6dXMtd2VzdC0yOjEwMDRjNDZmLWEyYmQtNDJkMi1iOGZkLTQxZTM0ZWEwZmI3ZjphamF5ZGVlcDMxMyJdLCJpc3MiOiJodHRwczovL2NvZ25pdG8taWRlbnRpdHkuYW1hem9uYXdzLmNvbSIsImV4cCI6MTYwMTQ0NzE3MSwiaWF0IjoxNjAxNDQ2MjcxfQ.CWJgG8ZCw-JXTAoEx5KoH_uuuBuIBpJwgW0-KCIg1qVeoCgSOzm90-ZsVS7NHMxb62c2J7UO54bpxTbOLe1FeNFQSFXg93mX-SLMwKS7VdMIxhOilF0mDzens-_efdA4_Juae6D1oujslCSiUxxVpTtld_rNcP4hoEwaVXgl7qI2IJPRksFgjKTYebWCoiX4zLL6gS_4ppA3TCLS_WtW9X4J7AmvWEjtJhvFoOXpTvCTl8VF4SJwQv1qVnRngEqbjfZoeaXET_yrJkEKQfdjLAHcChqPTEMytt0QF8RSR7mXwBRT4tJQxRhFseSLLxCIifjh38ZKO8N5xYtsac7n4w';
		
		
		global $AuthRole,$bucket_name,$RoleSessionName,$IdentityPool ;
		// echo $RoleSessionName;die;
		$stsClient = new StsClient([
			'version' => '2011-06-15',
			'region'=> 'us-west-2'
		]);

		// $result = $stsClient->getSessionToken();

		$result = $stsClient->AssumeRoleWithWebIdentity([
			  'RoleArn' 		=> $AuthRole,
			  'RoleSessionName' => $RoleSessionName,
			  'WebIdentityToken'=> $t['Token'],
			  'ProviderId' 		=> $IdentityPool,
		]);
		}catch(CredentialsException $e){
			$result =  array('status'=>0,'message'=>$e->getMessage());
		}
		echo '<pre>';
		print_r($result );die;
		 $s3Client = new S3Client([
			'version'     => 'letest',
			'region'      => $bucket_name,
			'credentials' =>  [
				'key'    => $result['Credentials']['AccessKeyId'],
				'secret' => $result['Credentials']['SecretAccessKey'],
				'token'  => $result['Credentials']['SessionToken']
			]
		]);
	
	}
	function setRules(){
		global $providedIdentity,$IdentityPool ;
		$client = CognitoObject();
		if($client['status'] == 1){
			try {
				$client = $client['client'];
				$result = $client->setIdentityPoolRoles([
					"IdentityPoolId"=> $providedIdentity,
					'RoleMappings' => [
						$IdentityPool => [
							'AmbiguousRoleResolution' => 'AuthenticatedRole',
							'RulesConfiguration' => [
								'Rules' => [ // REQUIRED
									[
										'Claim' => 'isAdmin', // REQUIRED
										'MatchType' => 'Contains', // REQUIRED
										'RoleARN' => $AuthRole, // REQUIRED
										'Value' => 'yes', // REQUIRED
									],
									// ...
								],
							],
							'Type' => 'Rules', // REQUIRED
						],
						// ...
					],
					'Roles' => ['authenticated'=>'Cognito_DiscoveredAuth_Role'], // REQUIRED
				]);
				return $role = array('status'=>1,'data' => $result );
				
			} catch (AwsException $e) {
				return $role = array('status'=>0 ,'message'=>$e->getMessage() );
			}
			
		}else{
			return $role = array('status'=> 0 ,'message'=>$obj['message']);
		}
		
		return $result;
	}
	
	
	
	

