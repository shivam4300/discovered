<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common { 
	
	public $currency,$ach_url,$ach_token,$ach_nachaid,$ach_header;
	public $ach,$paypal = array();
	function __Construct(){
		$this->CI = get_instance();
		$this->currency 		= '$';
		/*$this->ach	=	array(
				'url' => 'https://api.ach.com/webservice/V1/gateway.asmx/',	
				'token' => 'fab20e2f-9ca2-450c-ab0d-c4f8f4b2108e',
				'nachaid' => '9-00000386',	
				'content_type' => 'application/x-www-form-urlencoded',	
		);*/
		$this->ach	=	array(
				'url' => 'https://api.ach.com/webservice/V1/gateway.asmx/',	
				'token' => '1388c6c7-843c-439d-a5cb-75bba0c6e476​',	
				'nachaid' => '1834428091',	
				'content_type' => 'application/x-www-form-urlencoded',	
		);
		$this->paypal	=	array( 
				// 'credential' 			=> 'AaQZeZeDCb5_ea7nOyv27zAOpaQAoi0pXkDGBWgghVDC5HRemeiLAE8dKZfAyfAQs72ocyA6fzZ0DPwn' . ':' . 'ECTMnACxqOAGWMBiV5oJfqE1IKCrnp-QXxgIEKCqQYYMvxJKZpSXMBKnzSQz03porzb9ebQowwS13cX0', /*test*/
				'credential' 			=> 'AaTRl0tBjokA0twajvNPocxqO5D0rYRvuSAtsfytnTUJvJ_ZJ3gwaoCEhTY5y_uLKpr5xE9EjTRqhD-8' . ':' . 'EB_enePEz8BNzbXMjEBc-iE6AwJ1yMWcD03TaVehLj_IIPxZTSPAc7SRAi4FYB8Al9ZzT9VeKxuM6ea4',
				'access_token_url' 		=> 'https://api.paypal.com/v1/oauth2/token',
				'access_token_post' 	=> 'grant_type=client_credentials',
				'access_token_header'	=> array('Accept			: application/json',
												 'Accept-Language	: en_US',
												 'Content-Type		: application/x-www-form-urlencoded'),	
				'payout_url' 			=> 'https://api.paypal.com/v1/payments/payouts',		
				'show_payout_url' 		=> 'https://api.paypal.com/v1/payments/payouts/batch?page_size=1000&page=1',
				'show_payitem_url' 		=> 'https://api.paypal.com/v1/payments/payouts-item/itemid'
			,		
		);
			// dTvdevelop
		// Welcome#123
		//samba paypal account
		
	}
	
	
	
	function generate_single_content_url_param_old($pagamData , $contentType){
		$uniqueIdParam = 'p';
		$shareUrlName = ($contentType == 2)?'watch' : 'share';
		return $shareUrlName.'?'.$uniqueIdParam.'='.$pagamData;
	}
	function generate_single_content_url_param($pagamData , $contentType){
		$shareUrlName = ($contentType == 2)?'watch' : 'share';
		return $shareUrlName.'/'.$pagamData;
	}
	
	function generateRandomString($length = 10,$onlyNum=false) {
		if($onlyNum){
			$characters = '0123456789';
		}else{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	function getlocationbyip($ip='') {
		//$json = file_get_contents('https://geolocation-db.com/json/'.$ip);
		$json = file_get_contents('https://ipwho.is/'.$ip); 
		$data = json_decode($json);
		return json_encode(array(	'ip_address'	=>$ip, 
									'country_name'	=>isset($data->country)?$data->country:'',
									'state'			=>isset($data->region)?$data->region:'',
									'city'			=>isset($data->city)?$data->city:''
								)
						);
	}
	
	function manageTimezone($time,$clock="H",$TimeZoneOffset = ''){ 	 
		// if(isset($_SESSION['TimeZoneOffset'])){
			$TimeZoneOffset = ($TimeZoneOffset != '')? $TimeZoneOffset : ( isset($_SESSION['TimeZoneOffset'])? $_SESSION['TimeZoneOffset'] : 0 );
			if($TimeZoneOffset < 0){
				$TimeZoneOffset= abs($TimeZoneOffset);
				$time = new DateTime($time);
				$time->add(new DateInterval('PT' . $TimeZoneOffset . 'M'));
				return   $time->format('Y-m-d '.$clock.':i:s');	
			}else{
				$time = new DateTime($time);
				$time->sub(new DateInterval('PT' . $TimeZoneOffset . 'M'));
				return   $time->format('Y-m-d '.$clock.':i:s');
			}
		// }
	}
	
	function scheduleTimezone($time,$clock="H",$TimeZoneOffset){ 	  
		if($TimeZoneOffset  > 0){
			$TimeZoneOffset= abs($TimeZoneOffset);
			$time = new DateTime($time);
			$time->add(new DateInterval('PT' . $TimeZoneOffset . 'M'));
			return   $time->format('Y-m-d '.$clock.':i:s');	
		}else{
			$TimeZoneOffset= abs($TimeZoneOffset);
			$time = new DateTime($time);
			$time->sub(new DateInterval('PT' . $TimeZoneOffset . 'M'));
			return   $time->format('Y-m-d '.$clock.':i:s');
		}
	}
	
	function base64url_encode( $data ){
	  return rtrim( strtr( base64_encode( $this->generateRandomString(5).'|'.$data ), '+/', '-_'), '=');
	}

	function base64url_decode( $data ){
	  $decode = base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
	  return explode('|',$decode)[1];
	}
	
	
	function CallAchCurl($method , $arrayData , $url,$header){
		
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				// str_replace("%E2%9%8B","",http_build_query($arrayData))
				if($arrayData){ curl_setopt($curl, CURLOPT_POSTFIELDS, str_replace("%E2%80%8B","",http_build_query($arrayData)) ); }
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $arrayData);                    
				break;
			default:
				$url = sprintf("%s?%s", $url, http_build_query($arrayData));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	  
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) 
		{
			$resp = array('status'=>0,'message'=> "cURL Error #:" . $err );
		
		}else 
		{
			// echo '<pre>';print_r(html_entity_decode($response));die;
			$response 	= simplexml_load_string(html_entity_decode($response));
				
			$jsonfile 	= json_encode($response);
			$myarray 	= json_decode($jsonfile, true);
			$resp 		= array('status'=>1,'data'=> isset($myarray['Response'])?$myarray['Response']:$myarray );
		}
	  
	   	return $resp;
	}
	
	function CallCurl($method , $Data , $url,$header,$user_pass = ''){
		$curl = curl_init();
		if($Data){ 
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS,$Data); 
		}
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		if(!empty($user_pass))
		curl_setopt($curl, CURLOPT_USERPWD , $user_pass);
	  
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) 
		{
			$resp = json_encode(array('status'=>0,'message'=> "cURL Error #:" . $err ));
		
		}else 
		{
			$resp = $response ;
		}
	  
	   	return $resp;
	}
	
	function GobalPrivacyCond($uid,$table = 'channel_post_video'){
		$cond = '';
		if(!is_session_uid($uid)){   /* FOR OTHER USER	*/
			$AmIFanOfHim = AmIFollowingHim($uid);  
			if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
				$cond = ' AND '.$table.'.privacy_status IN(6,7) ';/* PRIVATE,PUBLIC*/
			}else{
				$cond = ' AND '.$table.'.privacy_status IN(7) ';	/* ONLY PUBLIC*/
			}
		}
		return $cond;
	}

	/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
	function channelGlobalCond($options = [1 , 1,  7 , 0 , 1, 1 , 0]){
		$condArray = [ 
			'channel_post_video.active_status' 	,
			'channel_post_video.complete_status' ,	
			'channel_post_video.privacy_status',	
			'channel_post_video.delete_status' ,	
			'channel_post_thumb.active_thumb' ,
			'users.user_status' ,  
			'users.is_deleted'
		]; 
		
		$cond = ''; 
		if($options){   
			foreach($options as $index => $option){
				if($option !== NULL){
					
					$cond .= $condArray[$index] . ' = ' . $option ;

					if(sizeof($options)- 1 != $index){
						$cond .= ' AND ';
					}
				}

			}
		}
		return $cond;
	}

	function form_validation_error(){
		$errors = array_values($this->CI->form_validation->error_array());
		return array('status'=>0 , 'message' =>isset($errors[0])?$errors[0]:'' );
	}
	
	
	function validateRecaptcha()
	{
		try{
			$captcha_response = trim($this->CI->input->post('g-recaptcha-response'));

			if(!empty($captcha_response) && $captcha_response != '')
			{
				$check = array(
					'secret'		=>	RECAPTCHA_SECRET_KEY,
					'response'		=>	$this->CI->input->post('g-recaptcha-response')
				);

				$startProcess = curl_init();

				curl_setopt($startProcess, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");

				curl_setopt($startProcess, CURLOPT_POST, true);

				curl_setopt($startProcess, CURLOPT_POSTFIELDS, http_build_query($check));

				curl_setopt($startProcess, CURLOPT_SSL_VERIFYPEER, false);

				curl_setopt($startProcess, CURLOPT_RETURNTRANSFER, true);

				$receiveData = curl_exec($startProcess);

				$finalResponse = json_decode($receiveData, true);
				if($finalResponse['success'])
				{
					$resp = array('status'=>1, 'message'=>'reCAPTCHA verify Successfully.');
				}
				else
				{
					$resp = array('status'=>0, 'message'=>'Something went wrong. Please try again.');
				}
			}
			else
			{
				$resp = array('status'=>0, 'message'=>'Please complete the reCAPTCHA.');
			}
		} catch (Exception $e) {
			$resp = ["status" => 0, "message" => $e->getMessage()];
		}
		return $resp;
	}

}
