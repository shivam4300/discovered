<?php 

require APPPATH . '/libraries/JWT.php';

class Creator_jwt {
	
	private $key = "1234567890qwertyuiopmnbvcxzasdfghjkl"; 
  private $apiKey = "83d63cf713964c3eccf7e87480ee5b7d0fd02a23a46f16a0f619d280c0492563";

	public function __construct() {
		$this->CI = get_instance();
		$this->CI->load->library("exceptions/UnauthenticatedException");
	}

	public function GenerateToken($data) { 
		return JWT::encode($data, $this->key);
	}

	public function DecodeToken($token) {
		return (array) JWT::decode($token, $this->key, ['HS256']);
	}
	
	public function MatchToken() {	
		$resp = ["status" => 0,'message' => 'Something Went Wrong.'];
		$received_Token = $this->CI->input->get_request_header('Authorization');
		if (!empty($received_Token)) {
			if (preg_match('/Bearer\s(\S+)/', $received_Token , $matches)) {
				$received_Token = $matches[1];
			}
		}
		try {
			$jwtData = $this->DecodeToken($received_Token);
			if (is_login() == $jwtData['user_login_id']) {
				$resp = ["status" => 1];
			} else {
				$resp = ["status" => 0, 'message' => 'Authorization token not matched'];
			}
		} catch (Exception $e) {
			$resp = ["status" => 0, "message" => $e->getMessage()];
		}
		return $resp;
	}
	
	public function is_allow_access() {	
		$resp = [
			"status" => 0, 
			'message' => 'Something Went Wrong.'
		];
		$received_Token = $this->CI->input->get_request_header('Authorization');
		if (!empty($received_Token)) {
			if (preg_match('/Bearer\s(\S+)/', $received_Token , $matches)) {
				$received_Token = $matches[1];
			}
		}
		try {
			$jwtData = $this->DecodeToken($received_Token);
			if (!empty($jwtData['user_login_id'])) {
				$resp = ["status" => 1, 'userid' => $jwtData['user_login_id']];
			} else {
				$resp = ["status" => 0, 'message' => 'Authorization token not matched'];
			}
		} catch (Exception $e) {
			$resp = ["status" => 0, "message" => $e->getMessage()];
		}
		return $resp;
	}

	public function require_user_id() {
		$response = $this->is_allow_access();
		if ($response['status'] == 0) { 
			throw new UnauthenticatedException($response['message']);
		}
		return $response['userid'];
	}

	public function refresh_token() {
		$userid = $this->require_user_id();
		$CI =& get_instance();
		$CI->load->model('DatabaseModel');
        $userDetails 	= $CI->DatabaseModel->select_data('user_name,user_uname,sigup_acc_type,user_status,user_level,user_cate,user_email,store_customer_id','users',array('user_id'=>$userid),1); 
		$userContent 	= $CI->DatabaseModel->select_data('uc_type,is_iva,uc_country,is_ele','users_content',array('uc_userid'=>$userid),1); 
       
		$user_type 		= '0';
		$is_iva 		= '0';
		$is_ele 		= '0';
		$uc_country 	= ' ';
		
		if( isset($userContent[0]) ){
			if(!empty($userContent[0]['uc_type'])){
				$user_type 	= $userContent[0]['uc_type'];
			}
			if(!empty($userContent[0]['is_iva'])){
				$is_iva 	= $userContent[0]['is_iva'];
			}if(!empty($userContent[0]['is_ele'])){
				$is_ele 	= $userContent[0]['is_ele'];
			}
			if(!empty($userContent[0]['uc_country'])){
				$uc_country = $userContent[0]['uc_country'];
			}
		}
		
		$firstname 	= '';
		$store_customer_id = '';
        if( !empty($userDetails[0]['user_name']) ) {
            $fullname 	= $userDetails[0]['user_name'];
            $store_customer_id 	= $userDetails[0]['store_customer_id'];
            $f_arr  	= explode(' ',$fullname);
            $firstname 	= ucfirst(isset($f_arr[0]) ? $f_arr[0] : '' );
        }

        $session	= array(
            'user_login_id'		=> $userid,
            'user_name'			=> $firstname,
            'user_uname'		=> $userDetails[0]['user_uname'],
            'sigup_acc_type'	=> $userDetails[0]['sigup_acc_type'],
            'user_login'		=> true,
            'user_status'		=> $userDetails[0]['user_status'],
            'account_type'		=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
            'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
            'is_iva'			=> $is_iva,
            'is_ele'			=> $is_ele,
            'primary_type'		=> (string) $user_type,
            'uc_country'		=> $uc_country,
            'user_email'		=> $userDetails[0]['user_email'],
			'iat'				=> time(),
			'store_customer_id' => $store_customer_id,
			'sub'				=> $userid,
		);
		
		$jwtToken 	= 	$CI->creator_jwt->GenerateToken($session);
		setcookie("AuthTkn", $jwtToken , time()+ 3600 * 6,'/');
		$CI->session->set_userdata($session);
	}
	
  public function isAuthorized(){
    $device = $this->CI->input->get_request_header('device');
    if(!in_array($device,['ANDROID','IOS','TIZEN','ANDROIDTV','ROKU'])){
        $secretKey = $this->CI->input->get_request_header('principal');
        if(empty($secretKey)){
            echo json_encode(array('status'=>0,'message'=>'Access denied.'));die;
        }else if($secretKey != $this->apiKey){
            echo json_encode(array('status'=>0,'message'=>'Access denied.'));die;
        }
    }
}
}
