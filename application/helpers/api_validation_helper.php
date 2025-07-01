<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('check_api_validation')){
	
	
    function check_api_validation($requestType , $requestData){
		
		$resp = array('status' => 1);
		
		foreach($requestData as $pVar){
			
			$checkMyParam = explode('|' , $pVar); // FIELD_NAME | CHECK_RQUIRE
			
			$myFieldName = $checkMyParam[0];
			
			$isRequire = (isset($checkMyParam[1]) && $checkMyParam[1] == 'require')?true:false;
			
			$isCheckValidation = (isset($checkMyParam[2]))?$checkMyParam[2]:false;
			
			$passRex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#])[A-Za-z\d$@$!%*?&#]{8,}$/";		
			$websiteRex = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";		
			$mobileRex = "/((?:\+|00)[17](?: |\-)?|(?:\+|00)[1-9]\d{0,2}(?: |\-)?|(?:\+|00)1\-\d{3}(?: |\-)?)?(0\d|\([0-9]{3}\)|[1-9]{0,3})(?:((?: |\-)[0-9]{2}){4}|((?:[0-9]{2}){4})|((?: |\-)[0-9]{3}(?: |\-)[0-9]{4})|([0-9]{7}))/";		
			
			
			$field = explode('_',$myFieldName);
			$field1 = 	isset($field[0])?$field[0]:'' ;
			$field2 =	isset($field[1])?$field[1]:'';
			$myFieldNames = 	ucfirst($field1).' '.ucfirst($field2);
			// echo $requestType[$myFieldName];
			if(!isset($requestType[$myFieldName])){ // Check missing field 
				$resp = array('status' => 0 , 'message' => "You missed out '$myFieldNames' fields");
				break;
				
			}elseif($isRequire == true && trim($requestType[$myFieldName]) == ''){ // Check required field
				$resp = array('status' => 0 , 'message' => "'$myFieldNames' field can't be empty.");
				break;
			}elseif($isCheckValidation != false && isset($requestType[$myFieldName]) && $requestType[$myFieldName] != ''){
				
				$fieldValue = $requestType[$myFieldName];
				
				if($isCheckValidation == 'email' && !filter_var($fieldValue , FILTER_VALIDATE_EMAIL)){
					$resp = array('status' => 0 , 'message' => "'$myFieldNames' should be valid.");
					break;
				}elseif($isCheckValidation == 'password' && !preg_match($passRex, $fieldValue)){
					$resp = array('status' => 0 , 'message' => "'$myFieldNames' should be 'minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character'.");
					break;
				}elseif($isCheckValidation == 'website' && !preg_match($websiteRex, $fieldValue)){
					$resp = array('status' => 0 , 'message' => "'$myFieldNames' should be valid.");
					break;
				}elseif($isCheckValidation == 'mobile' && !preg_match($mobileRex, $fieldValue)){
					$resp = array('status' => 0 , 'message' => "'$myFieldNames' should be valid.");
					break;
				}
			}
		}
		return $resp;
		
    }   
}
