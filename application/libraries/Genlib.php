<?php
defined('BASEPATH') or exit('Access Denied');

class Genlib
{
    protected $CI;
    public $statusCode = 0;
	public $respMessage = '';
	
	public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message'] = $this->respMessage;
		$resp['Token'] = $this->csrfToken();
		
		$this->CI->output->set_content_type('application/json');
		$this->CI->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->CI->output->set_output(json_encode($resp));
	}
	public function is_ajax_request(){
        if (!$this->CI->input->is_ajax_request()) {
            $this->respMessage = 'Invalid Request.';
			$this->show_my_response();
			exit;
        }
    }
	
	public function is_user_exist($emailOrUid, $field= NULL){
		if($field == NULL){
			$field = '*';
		}
		
		$where = "u_email = '{$emailOrUid}' || u_id = '{$emailOrUid}'";
		$result = $this->CI->DBfile->select_data([
            'field' => $field,
            'table' => USERTBL,
            'where' => $where,
            'limit' => 1
        ]);
		return (!empty($result))?$result:'';
	}
	
	
	function upload_file($uploadPath,$allowedType,$fileName,$encrypt= false ,$fileSize = null){
		$config['upload_path']          = $uploadPath;
		$config['allowed_types']        = $allowedType;
		$config['encrypt_name'] 		= $encrypt;
		$config['max_size']      		= $fileSize; 
		
		$this->CI->load->library('upload', $config);
		if ($this->CI->upload->do_upload($fileName)){
			
			$ud		=	$this->CI->upload->data();
			$image	=	$ud['raw_name'].$ud['file_ext'];
	    	return array('file_name'=>$image,'file_type'=>$ud['file_ext'],'w'=>$ud['image_width'],'h'=>$ud['image_height']);
		}else{
			return array('error'=>$this->CI->upload->display_errors());
	    }
	}
	
	function CallCurl($method, $data, $url, $header = [], $user_pass = ''){
		$curl = curl_init();
		if($data){ 
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS,$data); 
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
			$resp = array('status'=>0,'message'=> "cURL Error #:" . $err );
		
		}else 
		{
			$resp = array('status'=>1,'data'=> $response );
		}
	  
	   	return $resp;
	}
	function create_ucimg($uid,$img){
		return base_url('uploads/user_'.$uid . '/images/'.$img);
	}
	
	function csrfToken(){
		return $this->CI->security->get_csrf_hash();
	}
    
}
