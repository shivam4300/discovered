<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller { 
	public $uid = '';
	
	public $statusCode = '';
	public $statusType = '';
	public $respMessage = '';
	
	
	public function __construct(){
		parent::__construct();
		
		if(isset($_SESSION['user_accesslevel']) && ($_SESSION['user_accesslevel'] == 4 || !is_login()) ) {    /*IF CATEGORY EQUAL TO FAN*/
			redirect(base_url());
		}
		
		$this->load->library(array('manage_session','common')); 
		$this->load->helper(array('url','api_validation'));
		
		$this->uid = is_login();
		
		
	}
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] 	= $this->statusType;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	} 
	function index(){
		
		$data['page_info'] = array('page'=>'setting','title'=>'Setting');
		
		$data['countries'] = $this->DatabaseModel->select_data('country_id,country_name','country',array('status'=>1));
		$data['tax_entities'] = $this->DatabaseModel->select_data('tax_entity_id,tax_entity_name',' tax_entity_classification', array('status'=>1));
		
		$data['usersBillingDetail'] = $this->DatabaseModel->select_data('*','users_billing_and_payment_info',array('billing_user_id'=>$this->uid),1);
		
		if(!isset($data['usersBillingDetail'][0]) && empty($data['usersBillingDetail'][0])){
			
			$join = array('multiple' , array(
									array(	'users_content', 
											'users_content.uc_userid= users.user_id ',
											'left'),
									));
									
		
			$data['usersDetail'] = $this->DatabaseModel->select_data('users.user_name,users.user_phone,users.user_email,users.user_address,users_content.uc_country,users_content.uc_state,users_content.uc_city,users_content.uc_zipcode','users',array('users.user_id'=>$this->uid),1,$join);
		}
		
		$this->load->view('backend/include/header',$data);
		$this->load->view('backend/setting');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
	}
	function getStateArray(){
		if ($this->input->is_ajax_request()){
		   if(isset($_POST['id'])){
			  $state = $this->DatabaseModel->select_data('id,name','state',array('country_id'=>$_POST['id'])); 
			  echo json_encode(array('status'=>1,'data'=>$state));
		   }
		}
	}
	
	function SaveBillingInfo(){
		if ($this->input->is_ajax_request()) {
				$checkValidation = check_api_validation($_POST , array('billing_name|require','billing_contact|require','tax_entity_type|require','tax_entity_id|require','first_address|require','country|require','state|require','city|require'));
		
				if($checkValidation['status'] == 1){
					
					$_POST['billing_email_list'] 	= json_encode($_POST['billing_email_list']);
					// $_POST['billing_email'] 		= json_encode($_POST['billing_email']);
					$_POST['billing_update_date'] 	= date('Y-m-d');
						
					$usersBillingDetail =  $this->DatabaseModel->select_data('bid','users_billing_and_payment_info',array('billing_user_id'=>$this->uid),1);
					if(isset($usersBillingDetail[0]) && !empty($usersBillingDetail[0])){
						
						$this->DatabaseModel->access_database('users_billing_and_payment_info','update',$_POST, array('billing_user_id'=>$this->uid));
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Billing information updated successfully.';
					}else{
						$_POST['billing_user_id'] 		= $this->uid;
						
						$this->DatabaseModel->access_database('users_billing_and_payment_info','insert',$_POST, '');
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Billing information added successfully.';
					}
				}else{
					$this->respMessage = $checkValidation['message'];
				}
			$this->show_my_response();
		}
	}
	
	function SavePaymentInfo(){
			if ($this->input->is_ajax_request()) {
				$checkValidation = check_api_validation($_POST , array('bank_name','bank_acc_number','routing_number'));
		
				if($checkValidation['status'] == 1){
					
					$is_check = $this->config->item('is_ach_validation_check');
					
					if($_POST['payment_method_type'] == 1 && $is_check){
							
						$ach = $this->common->ach ;
						
						$arrayData = array(	
							'token'   		=> $ach['token'],
							'nachaid' 		=> $ach['nachaid'],
							'routingnumber' => $_POST['routing_number'],
							'accountnumber' => $_POST['bank_acc_number'],
							'amount'		=>	1
						);
						
						$varifyUrl = $ach['url'].'Verify';
						
						$header = array('content-type'  => $ach['content_type'] );
						
						$responce	=	$this->common->CallAchCurl('POST', $arrayData ,$varifyUrl,$header);
						
						if($responce['status'] == 1)
						{	
							if($responce['data']['Code'] != 1111)
							{	$d 	= $responce['data'];
								$this->respMessage = isset($d['Message'])? $d['Message'] : $d['Details'];
								return $this->show_my_response();
							}
						}else
						{
								$this->respMessage = $responce['message'];
								return $this->show_my_response();
						}
				
					}
					
					
					$usersPayDetail =  $this->DatabaseModel->select_data('bid','users_billing_and_payment_info',array('billing_user_id'=>$this->uid),1);
					
					$_POST['bank_acc_number'] 	= base64_encode( $_POST['bank_acc_number']);
					$_POST['routing_number'] 	= base64_encode( $_POST['routing_number']);
					$_POST['swift_code'] 		= base64_encode( $_POST['swift_code']);
					$_POST['paypal_id'] 		= base64_encode( $_POST['paypal_id']);
					$_POST['pm_update_date'] 	= date('Y-m-d');	
					
					if(isset($usersPayDetail[0]) && !empty($usersPayDetail[0])){
						
						$this->DatabaseModel->access_database('users_billing_and_payment_info','update',$_POST, array('billing_user_id'=>$this->uid));
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Payment information updated successfully.';
					}else{
						$_POST['billing_user_id'] 		= $this->uid;
						
						$this->DatabaseModel->access_database('users_billing_and_payment_info','insert',$_POST, '');
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Payment information added successfully.';
					}
				}else{
					$this->respMessage = $checkValidation['message'];
				}
			$this->show_my_response();
		}
	}
	
	
	function encodePayInfo(){
		$usersPayDetail =  $this->DatabaseModel->select_data('*','users_billing_and_payment_info');
		foreach($usersPayDetail as $pay){
			$update 	= array(
				'bank_acc_number' => base64_encode( $pay['bank_acc_number']),
				'routing_number' => base64_encode( $pay['routing_number']),
				'swift_code' => base64_encode( $pay['swift_code']),
				'paypal_id' => base64_encode( $pay['paypal_id']),
			);
			$this->DatabaseModel->access_database('users_billing_and_payment_info','update',$update, array('bid'=> $pay['bid']));
		}
	}
	
}
