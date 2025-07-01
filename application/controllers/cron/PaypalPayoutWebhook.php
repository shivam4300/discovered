<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaypalPayoutWebhook extends CI_Controller {
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	
	public function __construct()
	{
		parent::__construct();
		// if (!isset($this->session->userdata['admin'])) {
			// redirect('auth/logout');
		// }
		// $this->load->helper(array('api_validation','aws_s3_action'));
		// $this->load->library('query_builder');
		
	} 
	
	 
	function index1(){
		$record = json_decode(  file_get_contents('php://input') , true );
		$this->DatabaseModel->access_database('cron_test','insert',['message'=>json_encode($record)]);
	}
	function index(){
		$this->respMessage  = 'NA';
		$item = json_decode(  file_get_contents('php://input') , true );
		$this->DatabaseModel->access_database('cron_test','insert',['message'=>json_encode($item)]);
		if(!empty($item)){
			$item = $item['resource'];
			
			$payout_batch_id 	= $item['payout_batch_id'];
			$user_id			= $item['payout_item']['sender_item_id'];
			$transaction_status = $item['transaction_status'];	
			$payout_item_id 	= $item['payout_item_id'];	
			$value 				= $item['payout_item']['amount']['value'];
			
			if($transaction_status == 'SUCCESS'){
				$this->db->update('statements',['payment_status'=>2],['payment_txnid'=>$payout_batch_id,'user_id'=>$user_id]);
				$this->db->update('payment_history',['payment_status'=>$transaction_status],['payout_item_id'=>$payout_item_id,'user_id'=>$user_id]);
				if(  $this->db->affected_rows() > 0){
					$this->query_builder->outstanding(array('user_id'		=>	$user_id,
															'credit' 		=>	$value,
															'entry_against'	=>	9)	
				 										);
				}
			}else{
				$this->db->update('statements',['payment_status'=>3],['payment_txnid'=>$payout_batch_id,'user_id'=>$user_id]);
				
				$historyData = $this->DatabaseModel->select_data('*','payment_history',['payout_item_id'=>$payout_item_id,'user_id'=>$user_id],1 );
				
				if(isset($historyData[0]['payment_status']) && !empty($historyData[0]['payment_status'])){
					if($historyData[0]['payment_status'] == 'SUCCESS'){
						$this->query_builder->outstanding(array('user_id'		=>	$user_id,
																'debit' 		=>	$value,
																'entry_against'	=>	10)
															);
					}
					$error_reason = !empty($payout_item_id ) ? $this->GetErrorReason($payout_item_id): '';
					$this->db->update('payment_history',['payment_status'=>$transaction_status,'message'=>$error_reason],['payout_item_id'=>$payout_item_id,'user_id'=>$user_id]);
				}
			}
		}else{
			$this->respMessage 	= 'Empty Batch Id' ;
		}
		echo $this->respMessage ;
		// $this->show_my_response();
	}
	function GetErrorReason($payout_item_id=""){
		$payout_item_id = isset($_POST['payout_item_id'])?$_POST['payout_item_id']:$payout_item_id;
		if(!empty($payout_item_id)){
			$paypal = $this->common->paypal;
			
			$token = $this->common->CallCurl('POST',$paypal['access_token_post'],$paypal['access_token_url'],$paypal['access_token_header'],$paypal['credential']);
			$token = json_decode($token,true);
			if(isset($token['access_token'])){
				$access_token 	= 	$token['token_type'].' '.$token['access_token'];
				$url	   		= 	str_replace('itemid',$payout_item_id,$paypal['show_payitem_url']);	
				$header			= 	array('Content-Type: application/json','Authorization:'.$access_token);
				$item = $this->common->CallCurl('GET','', $url,$header);
				$item = json_decode($item,true);
				
				return $errors 	= isset($item['errors']['name'])?$item['errors']['name']:'';
				// print_r($errors);die;
			}
			// else{
				// $this->respMessage 	= $token['message'] ;
			// }
		}
		// return '';
	}
	 
}
