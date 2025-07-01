<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	public $uid 		= '';
	public $statusCode 	= '';
	public $respMessage = '';
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('manage_session')); 
		$this->load->helper(array('api_validation'));
		if(!is_login()){
			redirect(base_url());
		}
		$this->uid = is_login();
	}
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	} 

	public function index(){
		
		$data['page_info'] = array('page'=>'setting','title'=>'Setting');
		
		$data['countries'] = $this->DatabaseModel->select_data('country_id,country_name','country',array('status'=>1));
		
		$join = array('multiple' , array(
									array(	'users_content', 
											'users_content.uc_userid= users.user_id ',
											'left'),
									));
		$data['usersDetail'] = $this->DatabaseModel->select_data('users.user_name,users.user_phone,users.user_email,users_content.uc_gender,users_content.uc_dob,users.user_address,users_content.uc_country,users_content.uc_state,users_content.uc_city,users_content.uc_zipcode,users_content.uc_type,users.user_level','users',array('users.user_id'=>$this->uid),1,$join);
		
		$data['uid'] = $this->uid;
		
		$uc_state = isset($data['usersDetail'][0]['uc_state']) ? $data['usersDetail'][0]['uc_state'] : '';
		$data['state_name'] = $this->DatabaseModel->select_data('name','state',array('id'=> $uc_state )); 
		
		
		$account_type = isset($data['usersDetail'][0]['user_level']) ? $data['usersDetail'][0]['user_level'] : '';
		$data['artist_category'] = $this->DatabaseModel->select_data(
															'*',
															'artist_category',
															array('level'=>2,'status'=>1,'parent_id'=>$account_type)
															,'','',
															array('category_name','ASC')
														);
		// echo '<pre>';
			// print_r($data['usersDetail']);die;
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/setting/setting');
		$this->load->view('home/inc/footer');
		$this->load->view('common/notofication_popup');
	}

	public function getStateArray(){
		if ($this->input->is_ajax_request()) {
		   if(isset($_POST['id'])){
			  $state = $this->DatabaseModel->select_data('id,name','state',array('country_id'=>$_POST['id'])); 
			  echo json_encode(array('status'=>1,'data'=>$state));
		   }
		}
	}
	
	public function UpdateUserInfo(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
		
			if(isset($_POST) && !empty($_POST)){
				
				$checkValidation = check_api_validation($_POST , array('user_name|require','uc_gender|require','uc_dob|require','user_phone|require','user_address|require','uc_country|require','uc_state|require','uc_city|require'));
			
				if($checkValidation['status'] == 1){
					$this->DatabaseModel->access_database('users','update',array('user_name'=>$_POST['user_name'],'user_phone'=>$_POST['user_phone'],'user_address'=>$_POST['user_address']), array('user_id'=>$this->uid));
					$array = array('uc_zipcode'=>$_POST['uc_zipcode'],'uc_country'=>$_POST['uc_country'],'uc_state'=>$_POST['uc_state'],'uc_city'=>$_POST['uc_city'],'uc_gender'=>$_POST['uc_gender'],'uc_dob'=>$_POST['uc_dob']);
					
					if(isset($_POST['uc_type']) && $_POST['uc_type'] != ''){
						$array['uc_type'] = $_POST['uc_type'];
					}
					$this->DatabaseModel->access_database('users_content','update',$array, array('uc_userid'=>$this->uid));
					
					$this->statusCode = 1;
					$this->respMessage ='Profile updated successfully';
					
				}else{
					$this->respMessage =$checkValidation['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}	
		$this->show_my_response();
	}

	public function UpdateUserPassword(){
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
		
			if(isset($_POST) && !empty($_POST)){
				$checkValidation = check_api_validation($_POST , array('new_password|require','confirm_password|require'));
			
				if($checkValidation['status'] == 1){
						if($_POST['new_password'] == $_POST['confirm_password']){
							$user_password = $this->DatabaseModel->select_data('user_password','users',array('user_id'=>$this->uid)); 
							
							$this->DatabaseModel->access_database('users','update',array('user_password'=>md5($_POST['new_password'])), array('user_id'=>$this->uid)) ;
							
							$this->statusCode = 1;
							$this->respMessage = 'Password reset successfully.';
						}else{
							$this->respMessage = 'Confirm Password entered isn\'t match.';
						}
				}else{
					$this->respMessage =$checkValidation['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}
	
	public function geneOpenAiText(){
		$data = [];
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){
		
			if(isset($_POST) && !empty($_POST)){
				
				$checkValidation = check_api_validation($_POST , array('content|require','max_token|require','tone|require'));
			
				if($checkValidation['status'] == 1){
					$header	=  array('Content-Type: application/json','Authorization: Bearer '.OPEN_AI_KEY);
					
					$array = [
						50 => 'fifty',
						100 => 'one hundred',
						150 => 'one hundred fifty',
					];

					$promt = "Answer the below question in a fewer than ". $array[$_POST['max_token']] ." words : " ;
					$promt .= PHP_EOL;
					$promt .= $_POST['content'];
					
					$item = [
						"messages" => [ 
							[
								"role"=> "user",
								"content"=> $promt
							]
						],
						"temperature"=> 0.3,
						"max_tokens"=>  (int) $_POST['max_token'],
						"top_p"=> 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0,
						"model"=> "gpt-3.5-turbo",
					];

  					$r = $this->common->CallCurl('POST',json_encode($item),'https://api.openai.com/v1/chat/completions',$header);
					$r = json_decode($r,true);
					
					if(isset($r['error'])){
						$this->statusCode = 0;
						$this->respMessage = $r['error']['code'];
					}else{
						$data['data'] = $r;
						$this->statusCode = 1;
						$this->respMessage ='AI content generated successfully';
					}
					$data['data'] = $r;
				}else{
					$this->respMessage =$checkValidation['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}	
		$this->show_my_response($data);
	}
	
	
	
	
}
