<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payouts extends CI_Controller { 
	public $uid = '';
	
	public $statusCode = '';
	public $statusType = '';
	public $respMessage = '';
	
	
	public function __construct(){
		parent::__construct();
		
		if(isset($_SESSION['user_accesslevel']) && ($_SESSION['user_accesslevel'] == 4 || !is_login()) ) {    /*IF CATEGORY EQUAL TO FAN*/
			redirect(base_url());
		}
		
		$this->load->library(array('manage_session')); 
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
		$data['page_info'] = array('page'=>'payouts','title'=>'Payouts');
		
		$this->load->view('backend/include/header',$data);
		$this->load->view('backend/payouts');
		$this->load->view('backend/include/footer');
	}
	function show_outstanding_details(){
		
		if(isset($_GET['length'])){
		
			$currency 	=  	$this->common->currency;
			$data 		= 	array();
			$search 	= 	trim($_GET['search']);
			
			$colm 		= 	6;
			$order 		= '	DESC'; 
			
			if(isset($_GET['order'][0]['column']) && !empty($_GET['order'][0]['column'])){
				$colm 	= $_GET['order'][0]['column'];
				$order 	= $_GET['order'][0]['dir'];								
			}
			
			$start 		= $_GET['start'];
			$filed 		= array(null,'debit','credit','balance','entry_against','created_at','outstanding_id');
			$join 		= '';
			
			$cond 		= 'user_id  = '.$this->uid.' AND ';
			
			$cond 		.= ' (';
			for($i=0;$i < sizeof($filed); $i++){
				if($filed[$i] != ''){
					$cond .= "$filed[$i] LIKE '%".$search."%'";
					if(sizeof($filed) - $i != 1){
						$cond .= ' OR ';
					}	
				}
				
			}
			$cond .= ')';
			
			if(isset($_GET['date_range'])  && !empty($_GET['date_range'])){
				$rangeArray = explode('-',$_GET['date_range']);
				$date1 		= "'".date('Y-m-d' , strtotime($rangeArray[0]))."'";
				$date2 		= "'".date('Y-m-d' , strtotime($rangeArray[1]))."'";
				
				$cond 		.=" AND created_at >= $date1 AND created_at <= $date2 ";
			}
			
			
			$resultData = $this->DatabaseModel->select_data($filed,'outstandings', $cond ,array($_GET['length'],$start) , $join , array($filed[$colm] , $order));
			
			$leadsCount = $this->DatabaseModel->aggregate_data('outstandings','outstanding_id','COUNT',$cond);
			
			$entry_against = array(0 => null,1=>'Advertising Earning',9=>'Payment Transfer');
			
			foreach($resultData as $list){
					$start++;
					$created_at = date('Y-m-d h:i',strtotime($this->common->manageTimezone($list['created_at'],'h')));
					
					array_push($data , array(	$start,
												isset($entry_against[$list['entry_against']])?$entry_against[$list['entry_against']]:'', 
												$currency.' '.$list['debit'],
												$currency.' '.$list['credit'],
												$currency.' '.$list['balance'],
												$created_at
											)); 
			}
			
			echo json_encode(array( 
				'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
				'recordsTotal' => $leadsCount,
				'recordsFiltered' => $leadsCount,
				'data' => $data, 
				));
				
			
		}	
		
	}
	function show_payment_details(){
		if(isset($_GET['length'])){
		
			$currency 	=  	$this->common->currency;
			$data 		= 	array();
			$search 	= 	trim($_GET['search']);
			
			$colm 		= 	1;
			$order 		= '	ASC';
			
			if(isset($_GET['order'][0]['column']) && !empty($_GET['order'][0]['column'])){
				$colm 	= $_GET['order'][0]['column'];
				$order 	= $_GET['order'][0]['dir'];								
			}
			
			$start 		= $_GET['start'];
			$filed 		= array(NULL,'created_at','pay_through','payout_item_id','pay_amount','payment_status','payout_batch_id');
			$join 		= '';
			
			$cond 		= 'user_id  = '.$this->uid.' AND ';
			
			$cond 		.= ' (';
			for($i=0;$i < sizeof($filed); $i++){
				if($filed[$i] != ''){
					$cond .= "$filed[$i] LIKE '%".$search."%'";
					if(sizeof($filed) - $i != 1){
						$cond .= ' OR ';
					}	
				}
				
			}
			$cond .= ')';
			
			if(isset($_GET['date_range'])  && !empty($_GET['date_range'])){
				$rangeArray = explode('-',$_GET['date_range']);
				$date1 		= "'".date('Y-m-d' , strtotime($rangeArray[0]))."'";
				$date2 		= "'".date('Y-m-d' , strtotime($rangeArray[1]))."'";
				
				$cond 		.=" AND created_at >= $date1 AND created_at <= $date2 ";
			}
			
			
			$resultData = $this->DatabaseModel->select_data($filed,'payment_history', $cond ,array($_GET['length'],$start) , $join , array($filed[$colm] , $order));
			
			$leadsCount = $this->DatabaseModel->aggregate_data('payment_history','pay_id','COUNT',$cond);
			
			
			foreach($resultData as $list){
					$start++;
					$created_at = date('F d,y',strtotime($this->common->manageTimezone($list['created_at'],'h')));
					
					array_push($data , array(	$start,
												$created_at, 
												$list['pay_through'],
												'<a href="'.base_url('backend/statement?batch_id='.$list['payout_batch_id'] ).'">'.$list['payout_item_id'].'</a>',
												$currency.' '.$list['pay_amount'],
												$list['payment_status'],
												
											)); 
			}
			
			echo json_encode(array( 
				'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
				'recordsTotal' => $leadsCount,
				'recordsFiltered' => $leadsCount,
				'data' => $data, 
				));
				
			
		}	
	}
	
	
	
	
}
