<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statement extends CI_Controller {
	public $uid 		= '';
	
	public $statusCode 	= '';
	public $statusType 	= '';
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
		$data['page_info'] = array('page'=>'statements','title'=>'Statements');
		if(isset($_GET['batch_id']) && !empty($_GET['batch_id'])){
			$_SESSION['batch_id'] = $_GET['batch_id'];
		}else{
			unset($_SESSION['batch_id']);
		}
		$this->load->view('backend/include/header',$data);
		$this->load->view('backend/statement'); 
		$this->load->view('backend/include/footer');
	}
	
	function show_statement_details(){
		
		if(isset($_GET['length'])){
			$currency 	=  $this->common->currency;
			$data 		= array();
			$search 	= trim($_GET['search']);
			
			$colm = 1;
			$order = 'DESC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm = $_GET['order'][0]['column'];
				$order = $_GET['order'][0]['dir'];								
			}
			
			$start = $_GET['start'];
			 
			$filed = array(null,'statement_month','(advertising_earning+merchandise_earning+media_earning+shows_earning+made_pp_earning-received_pp_earning+made_endorsement_earning-received_endorsement_earning) AS Total','payment_status','statement_id');
			
			$condfiled 	= array(null,'statement_month');
			
			$orderfiled = array(null,'statement_month','Total',null);
			
			$join = '';
			
			$cond = 'user_id  = '.$this->uid.' AND ';
			
			if(isset($_SESSION['batch_id']) && !empty($_SESSION['batch_id'])){
				$cond .= "payment_txnid  = '{$_SESSION['batch_id']}' AND ";
			}
			
			$cond .= ' (';
			for($i=0;$i < sizeof($condfiled); $i++){
				if($condfiled[$i] != ''){
					$cond .= "$condfiled[$i] LIKE '%".$search."%'";
					if(sizeof($condfiled) - $i != 1){
						$cond .= ' OR ';
					}	
				}
				
			}
			$cond .= ')';
			
			if(isset($_GET['date_range'])  && !empty($_GET['date_range'])){
				$rangeArray = explode('-',$_GET['date_range']);
				$date1 		= "'".date('Y-m-d' , strtotime($rangeArray[0]))."'";
				$date2 		= "'".date('Y-m-d' , strtotime($rangeArray[1]))."'";
				
				$cond .=" AND statement_month >= $date1 AND statement_month <= $date2 ";
			}
			
			
			$resultData = $this->DatabaseModel->select_data($filed,'statements', $cond ,array($_GET['length'],$start) , $join , array($orderfiled[$colm] , $order) );
			
			$leadsCount = $this->DatabaseModel->aggregate_data('statements','statement_id','COUNT',$cond);
			
			
			foreach($resultData as $list){
					$start++;
					$payment_status = ($list['payment_status'] == 0)? 'PENDING': (($list['payment_status'] == 1)? 'IN PROCESS':( ($list['payment_status'] == 2)?'PAID':'FAILED' ) );
					array_push($data , array(
											'<div class="tbl_serialno">
											'.$start.'.
											</div>
											<div class="tbl_checkbox" >
												<input type="checkbox" id="'.$list['statement_id'].'">
												<label for="'.$list['statement_id'].'"></label>
											</div>',
											// $list['statement_number'] ,
											date('F Y',strtotime($list['statement_month'])),
											$list['Total'],
											$payment_status,
											'<a  class="table_embedbtn GetStateMent common_click" data-statement_id="'.$list['statement_id'].'">
												View 
												<span>
													<svg xmlns="http://www.w3.org/2000/svg" width="13px" height="15px">
														<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
														 d="M10.610,15.002 L2.375,15.002 C1.130,15.002 -0.005,13.988 -0.005,12.875 L-0.005,2.123 C-0.005,1.010 1.130,-0.004 2.375,-0.004 L8.232,-0.004 C8.747,-0.004 9.244,0.202 9.596,0.560 L12.485,3.503 C12.810,3.834 12.990,4.266 12.990,4.720 L12.990,12.875 C12.990,13.988 11.855,15.002 10.610,15.002 ZM9.147,1.620 L9.147,2.624 C9.147,3.070 9.616,3.898 10.061,3.898 L11.294,3.898 L9.147,1.620 ZM11.523,5.225 L10.061,5.225 C8.793,5.225 7.680,4.237 7.680,3.110 C7.680,3.110 7.673,2.357 7.679,1.323 L2.375,1.323 C1.906,1.323 1.462,1.712 1.462,2.123 L1.462,12.875 C1.462,13.287 1.906,13.675 2.375,13.675 L10.610,13.675 C11.079,13.675 11.523,13.287 11.523,12.875 L11.523,5.225 ZM8.962,11.276 L3.575,11.276 C3.171,11.276 2.842,10.979 2.842,10.614 C2.842,10.248 3.171,9.951 3.575,9.951 L8.962,9.951 C9.367,9.951 9.696,10.248 9.696,10.614 C9.696,10.979 9.367,11.276 8.962,11.276 ZM8.962,8.841 L3.575,8.841 C3.171,8.841 2.842,8.543 2.842,8.177 C2.842,7.812 3.171,7.515 3.575,7.515 L8.962,7.515 C9.367,7.515 9.696,7.812 9.696,8.177 C9.696,8.543 9.367,8.841 8.962,8.841 ZM6.916,6.374 L3.575,6.374 C3.171,6.374 2.842,6.077 2.842,5.712 C2.842,5.346 3.171,5.049 3.575,5.049 L6.916,5.049 C7.320,5.049 7.649,5.346 7.649,5.712 C7.649,6.077 7.320,6.374 6.916,6.374 Z"/>
														</svg>
												</span>
												</a>'
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
	
	function generate_advertisement_statement(){
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		
		$this->load->library('query_builder');
		
		$currency 	=  	$this->common->currency;
		$last_month = 	date('Y-m', strtotime(date('Y-m')." -1 month"));
		
		$filed 		= 	array('video_userid','SUM(view_count) AS ViewCount','SUM(ads_count) AS AdCount','SUM(creator_share_amount) AS ShareAmount');
		
		$cond		=	array('DATE_FORMAT(view_date,"%Y-%m")'=>$last_month);
		
		$resultData = 	$this->DatabaseModel->select_data($filed,'channel_video_view_count_by_date',$cond,'' , '' , '' ,'','video_userid');
		
		foreach($resultData as $result){
			
			$user_id		=	$result['video_userid'];
			$ShareAmount	=	$result['ShareAmount'];
			
			$data_array	 	= [	'user_id' 				=> 	$user_id,
								'statement_month' 		=> 	date('Y-m-d',strtotime('last day of previous month')),
								'advertising_ads_count' => 	$result['ViewCount'],
								'advertising_view_count'=> 	$result['AdCount'],
								'advertising_earning' 	=> 	$ShareAmount,
								'created_at' 			=> 	date('Y-m-d H:i:s') 	
							];	
			
			$this->DatabaseModel->access_database('statements','insert',$data_array);
			
			if($ShareAmount > 0){
				$array = array(	'user_id'=>$user_id,
								'debit'=>$ShareAmount,
								'entry_against'=>1
								);
				$this->query_builder->outstanding($array);
			}	
			
		}
		
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else 
		{
			$this->db->trans_commit();
			return TRUE;
		}
		
	}	
	
	function generate_advertisement_statement_new(){
		
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		
		$this->generate_advertisement_statement_filmhub();

		$this->load->library('query_builder');
		
		$currency 	=  	$this->common->currency;
		$last_month = 	date('Y-m', strtotime(date('Y-m')." -1 month"));
		
		$filed 		= 	array('video_id','video_userid','SUM(view_count) AS ViewCount','SUM(ads_count) AS AdCount','SUM(creator_share_amount) AS ShareAmount');
		
		$cond		=	array('DATE_FORMAT(view_date,"%Y-%m")'=>$last_month, 'parent_uname'=>"");
		
		$join = array('multiple' , array(
					array(	'channel_post_video', 
							'channel_post_video.post_id = channel_video_view_count_by_date.video_id',
							'left')
				));

		$resultData = 	$this->DatabaseModel->select_data($filed,'channel_video_view_count_by_date',$cond,'' , $join , '' ,'','video_userid');
		
		if(!empty($resultData)){
			foreach($resultData as $result){
				
				$user_id		=	$result['video_userid'];
				$ShareAmount	=	$result['ShareAmount'];
				
				$data_array	 	= [	'user_id' 				=> 	$user_id,
									'statement_month' 		=> 	date('Y-m-d',strtotime('last day of previous month')),
									'advertising_ads_count' => 	$result['ViewCount'],
									'advertising_view_count'=> 	$result['AdCount'],
									'advertising_earning' 	=> 	$ShareAmount,
									'created_at' 			=> 	date('Y-m-d H:i:s') 	
								];	
				
				$this->DatabaseModel->access_database('statements','insert',$data_array);
				
				if($ShareAmount > 0){
					$array = array(	'user_id'=>$user_id,
									'debit'=>$ShareAmount,
									'entry_against'=>1
									);
					$this->query_builder->outstanding($array);
				}	
				
			}
			
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			}else 
			{
				$this->db->trans_commit();
				return TRUE;
			}
		}else{
			return FALSE;
		}
		
	}


	function generate_advertisement_statement_filmhub(){
		
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		
		$this->load->library('query_builder');
		
		$currency 	=  	$this->common->currency;
		$last_month = 	date('Y-m', strtotime(date('Y-m')." -1 month"));
		
		$filed 		= 	array('users.user_id','video_id','video_userid','SUM(view_count) AS ViewCount','SUM(ads_count) AS AdCount','SUM(creator_share_amount) AS ShareAmount');
		
		$cond		=	array('DATE_FORMAT(view_date,"%Y-%m")'=>$last_month, 'parent_uname'=>PARENT_UNIQUE_NAME);
		
		$join = array('multiple' , array(
					array(	'channel_post_video', 
							'channel_post_video.post_id = channel_video_view_count_by_date.video_id',
							'left'),
					array(	'users' , 'users.user_uname = channel_post_video.parent_uname'),
				));

		$resultData = 	$this->DatabaseModel->select_data($filed,'channel_video_view_count_by_date',$cond,'' , $join , '' ,'','video_userid');
		
		if(!empty($resultData)){
			foreach($resultData as $result){
				
				$user_id		=	$result['user_id'];
				$ShareAmount	=	$result['ShareAmount'];
				
				$data_array	 	= [	'user_id' 				=> 	$user_id,
									'statement_month' 		=> 	date('Y-m-d',strtotime('last day of previous month')),
									'advertising_ads_count' => 	$result['ViewCount'],
									'advertising_view_count'=> 	$result['AdCount'],
									'advertising_earning' 	=> 	$ShareAmount,
									'created_at' 			=> 	date('Y-m-d H:i:s') 	
								];	
				
				$this->DatabaseModel->access_database('statements','insert',$data_array);
				
				if($ShareAmount > 0){
					$array = array(	'user_id'=>$user_id,
									'debit'=>$ShareAmount,
									'entry_against'=>1
									);
					$this->query_builder->outstanding($array);
				}	
				
			}
			
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			}else 
			{
				$this->db->trans_commit();
				return TRUE;
			}
		}else{
			return FALSE;
		}
		
	}


	function getStatementDetails(){
		if(isset($_POST['statement_id'])){
			
			$uid = $this->uid;
			$statement_id = $_POST['statement_id'];
			
			$accessParam = array(
			'field' => 'users.user_email,users.user_address,users.user_name,users_content.uc_city,country.country_name,state.name',
			'where' => 'user_id='.$uid,
			);
			$this->load->library(array('query_builder')); 				
			$user			= $this->query_builder->user_list($accessParam);
			
			$user_name 		= isset($user['users'][0])? $user['users'][0]['user_name'] 		: '';
			$user_email 	= isset($user['users'][0])? $user['users'][0]['user_email'] 	: '';
			$user_address 	= isset($user['users'][0])? $user['users'][0]['user_address'] 	: '';
			$uc_city 		= isset($user['users'][0])? $user['users'][0]['uc_city'] 		: '';
			$state_name 	= isset($user['users'][0])? $user['users'][0]['name'] 			: '';
			$country_name 	= isset($user['users'][0])? $user['users'][0]['country_name'] 	: '';
			
			$field			= array('advertising_earning','merchandise_earning','media_earning','shows_earning','made_pp_earning','(-1 * received_pp_earning) AS received_pp_earning','made_endorsement_earning','(-1 * received_endorsement_earning) AS received_endorsement_earning','statement_month','statements.payment_status','pay_through','payout_item_id','pay_amount','payment_history.created_at');
			
			$join 			= array('payment_history',
									'payment_history.payout_batch_id = statements.payment_txnid',
									'left');
			$resultData 	= $this->DatabaseModel->select_data($field,'statements',array('statement_id'=>$statement_id),1, $join);
			// echo '<pre>';
			// print_r($resultData);die;
			$field[5] 		= 'received_pp_earning';	
			$field[7] 		= 'received_endorsement_earning';
			
			$sources		= '';
			$total 			= 0; 
			
			$coloum_name 	= array('Advertising','Merchandise','Media','Shows','Partnership Program Made','Parternership Program Received','Endorsement Made','Endorsement Received',);
			
			foreach($coloum_name as $key => $value){
			$sources	  .= '<tr>
								<td style="text-align:center">'.  ($key+1) .'</td>
								<td>'.$value.'</td>
								<td>'.$this->common->currency .' '. $resultData[0][$field[$key]] . '</td>
								<!--td>$500</td>
								<td>$500</td-->
							 </tr>'; 
				$total += $resultData[0][$field[$key]];	
			}
			$payment_status = $resultData[0]['payment_status'];
			$payment_status = ($payment_status == 0)? 'Pending': (($payment_status == 1)? 'In Process':( ($payment_status == 2)?'Paid':'Failed' ) );
			
			$payment_detail = ($payment_status != 'Pending') 
								?'<h5 class="stmnt_user">Pay through: <span>'. $resultData[0]['pay_through'].'</span></h5>
								 <h5 class="stmnt_user">Pay txn id : <span>'.$resultData[0]['payout_item_id'].'</span></h5>
								 <h5 class="stmnt_user">Pay amount : <span>$'.$resultData[0]['pay_amount'].'</span></h5>
								 <h5 class="stmnt_user">pay date   : <span>'.$resultData[0]['created_at'].'</span></h5>'
								:'';
			$string = '<table class="table stmnt_maintbl" cellpadding="0"cellspacing="0">
					<tbody>
						<tr style="border-bottom: 1px solid #dddddd;">
							<td style="text-align:left;padding-bottom: 20px;">
								<a href=""><img src="'.base_url('repo/images/dashboard_logo.png').'" alt="'.PROJECT.'"></a>

							</td>
							<td style="text-align: right;vertical-align: middle;padding-bottom: 20px;">
								<h4 class="statement_tittle">Monthly Statement</h4>
							</td>
						</tr>
						
						<tr>
							<td colspan="2">
								<table class="table stmnt_fromto" cellpadding="0"cellspacing="0">
									<tbody>
										<tr>
											<td>
											<address>
												<h5 class="stmnt_user"> Statement to:</h5>
												<p>
												'.ucfirst($user_name).',
												<br>'.$user_address.',
												</br>'.$uc_city.',
												</br>'.$state_name.','.$country_name.'
												</p>
												<h5 class="stmnt_user s_email"> Email Id : <span> <a href="mailto:'.$user_email.'" class="primary_link">'.$user_email.'</a></span></h5>
											</address>	
											</td>
											<td style=" text-align: right;">
												<h5 class="stmnt_user"> Statement Month : <span>'.date('Y/m/d',strtotime($resultData[0]['statement_month'])).'</span></h5>
												<h5 class="stmnt_user">Pay status :   <span>'.$payment_status.'</span></h5>
												'.$payment_detail.'
												
											</td>
										</tr>
										<tr>
											<table class="table stmnt_notetbl" cellpadding="0"cellspacing="0">
												<tbody>
													<tr>
														<td>
														<p class="stmnt_note_prea"><strong>Note:</strong> <br>Payouts will start only upon you providing accurate Tax ID, Bank/Paypal information.To update <a class="statement_link" target="_blank" href="'.base_url('backend/setting').'"> click here </a>
														</td>
													</tr>
												</tbody> 
											</table>
										</tr>
										
										
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table class="table stmnt_content" >
									<thead>
										<tr>
											<th>SR</th>
											<th>Source</th>
											<th>Earning</th>
											<!--th>Deduction</th>
											<th>Total</th-->
										</tr>
									</thead>
									<tbody>
										'.$sources.'
									
									<tr style="background:rgb(236, 88, 31);color:rgb(255, 255, 255);">
										<td colspan="2" style="padding:8px 0;text-align: right;padding-right: 50px;text-transform: uppercase;">
										
										</td>
										<td style="padding:8px 0;font-weight: bold;font-size: 19px;">
											'.$this->common->currency.$total.'		
										</td>
									</tr>
									
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<table class="table stmnt_btm_note" >
								<tbody>
									<tr>
										<td style="border-top:0;">
										<p class="stmnt_btoom_note">Billing or Payout questions? See General <span>Inquiries > Billing </span>section on  <a class="statement_link" hredf="#">'.base_url('help').'</a></p>
										</td>
									</tr>
									<tr>
										<td>
										<p class="stmnt_btoom_note">Discovered USA Inc.,  PO Box 67369, Scotts Valley, CA 95067 United States</p>
										</td>
									</tr>
								</tbody>
							</table>
						</tr>
					  
					</tbody>
				  </table>';
			echo  json_encode(array('status'=>1,'data'=>$string));	  
		}
	}
	
	
	
	
}
