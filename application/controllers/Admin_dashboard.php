<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_dashboard extends CI_Controller {
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	public function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
		$this->load->helper(array('api_validation','aws_s3_action'));
		$this->load->library(array('query_builder','Valuelist'));
		
	}
	function is_ajax(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
	}

	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message'] = $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}

	public function index(){
		$data['page_menu'] = 'main_dashboard|sub_dashboard|Dashboard|dashboard'; 
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/dashboard',$data);
		$this->load->view('admin/include/footer',$data);
	}
	
	public function getSupportData(){
		$resultData = $this->DatabaseModel->select_data('*','support_department');
		foreach ($resultData as $key => $value) {
			$resultData[$key]['open'] =	$this->DatabaseModel->aggregate_data('support_ticket','id','COUNT',array('ticket_type'=>$value['id'],'ticket_id'=>0,'status'=>0));
			$resultData[$key]['replied'] =	$this->DatabaseModel->aggregate_data('support_ticket','id','COUNT',array('ticket_type'=>$value['id'],'ticket_id'=>0,'status'=>1));
			$resultData[$key]['close'] =	$this->DatabaseModel->aggregate_data('support_ticket','id','COUNT',array('ticket_type'=>$value['id'],'ticket_id'=>0,'status'=>2));
		}
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "dss";
		$this->show_my_response($resp);
	} 

	public function getUserData(){
		$resultData['totalUser'] = $this->DatabaseModel->aggregate_data('users','user_id','COUNT');
		$resultData['activeUser'] = $this->DatabaseModel->aggregate_data('users','user_id','COUNT',array('is_deleted' => 0,'user_status'=>1,'official_status'=>0,'user_uname !='=>""));
		$resultData['inactiveUser'] = $this->DatabaseModel->aggregate_data('users','user_id','COUNT','(user_status = 2 OR user_status = 4 ) AND is_deleted = 0 AND official_status = 0 ');
		$resultData['officialUser'] = $this->DatabaseModel->aggregate_data('users','user_id','COUNT',array('official_status'=>1,'is_deleted' => 0));
		$resultData['incompleteUser'] = $this->DatabaseModel->aggregate_data('users','user_id','COUNT','users.is_deleted = 0 AND user_role = "member" AND official_status = 0 AND user_status = 1 AND (user_uname IS NULL OR user_uname = "")');
		$resultData['deletedUser'] = $this->DatabaseModel->aggregate_data('users','user_id','COUNT','users.is_deleted = 1 AND user_role = "member" ');
		$resultData['totalCouponCode'] = $this->DatabaseModel->aggregate_data('gamepass_coupon_codes','id','COUNT');
		$resultData['redeemCouponCode'] = $this->DatabaseModel->aggregate_data('gamepass_coupon_codes','id','COUNT','status = 1');

		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	} 
	function getGraphDataByYear(){
		$ly = date('Y')-1;
		
		$cond = 'user_role = "member"' ;
		$resultData = [];
		if(isset($_POST['startDate'])  && $_POST['startDate'] == date('Y-01-01')  && $_POST['endDate'] == date('Y-m-d') ){ 
			$date1 		= "'".date('Y-m-d' , strtotime($_POST['startDate']))."'";
			$date2 		= "'".date('Y-m-d' , strtotime($_POST['endDate']))."'";

			$cond .=" AND user_regdate >= $date1 AND user_regdate <= $date2 ";
			$resultData=$this->DatabaseModel->query("SELECT count(user_id) as usercount , MONTH(user_regdate) as regdate FROM users WHERE ".$cond."  GROUP BY MONTH(user_regdate)","array");
		}else
		if(isset($_POST['startDate'])  && $_POST['startDate'] == date($ly.'-01-01')  && $_POST['endDate'] == date($ly.'-12-31') ){ 
			$date1 		= "'".date('Y-m-d' , strtotime($_POST['startDate']))."'";
			$date2 		= "'".date('Y-m-d' , strtotime($_POST['endDate']))."'";

			$cond .=" AND user_regdate >= $date1 AND user_regdate <= $date2 ";
			$resultData=$this->DatabaseModel->query("SELECT count(user_id) as usercount , MONTH(user_regdate) as regdate FROM users WHERE ".$cond."  GROUP BY MONTH(user_regdate)","array");
		}else
		if(isset($_POST['startDate'])  && !empty($_POST['startDate'])){
			$date1 		= "'".date('Y-m-d' , strtotime($_POST['startDate']))."'";
			$date2 		= "'".date('Y-m-d' , strtotime($_POST['endDate']))."'";

			$cond .=" AND DATE(user_regdate) >= $date1 AND DATE(user_regdate) <= $date2 ";
			$resultData=$this->DatabaseModel->query("SELECT count(user_id) as usercount , DATE(user_regdate) as regdate FROM users WHERE ".$cond."  GROUP BY DATE(user_regdate)","array");
		}
		
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}
	function getCountByTicketType(){
		if(empty($_POST['startDate']) && empty($_POST['endDate'])){
			$resultData=$this->DatabaseModel->query("SELECT COUNT(id) as total,status FROM `support_ticket` WHERE ticket_id =0 GROUP BY status","array");
		}else{
			$cond =" AND DATE(support_ticket.created_at) BETWEEN '".$_POST['startDate'] ."' AND '".$_POST['endDate']."'";
			$resultData=$this->DatabaseModel->query("SELECT COUNT(id) as total,status FROM `support_ticket` WHERE ticket_id =0 ".$cond." GROUP BY status","array");
		}
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}

	function getTicketList(){
		$join = array('multiple' , array(
			array(	'users', 
					'users.user_id 			= support_ticket.user_id'),
			array(	'support_department', 
					'support_department.id 				= support_ticket.ticket_type'),
			// array(	'support_user', 
			// 		'support_user.support_department 				= support_department.id'),
			),
			);
			
		
		if(empty($_POST['startDate']) && empty($_POST['endDate'])){
			$data['latest_ticket'] = $this->DatabaseModel->select_data('*,support_ticket.id as t_id, support_ticket.status as t_status','support_ticket',array('ticket_no !='=>'','support_ticket.status'=>0),10 ,$join, array('support_ticket.id','desc') );
		}else{
			$cond ="ticket_no !='' AND support_ticket.status=0 AND DATE(support_ticket.created_at) BETWEEN '".$_POST['startDate'] ."' AND '".$_POST['endDate']."'";
			$data['latest_ticket']=$this->DatabaseModel->query("SELECT *,`support_ticket`.`id` as t_id, `support_ticket`.`status` as t_status FROM `support_ticket` JOIN users ON `users`.`user_id`= `support_ticket`.`user_id` JOIN support_department ON `support_department`.`id`= `support_ticket`.`ticket_type` WHERE ".$cond." ORDER BY support_ticket.id DESC","array");
		}
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($data);
	}
	function getTicketGraphDataByYear(){
		$ly = date('Y') - 1;
		$cond = '';
		$resultData = $this->DatabaseModel->select_data('*','support_department');
		foreach ($resultData as $key => $value) {
			$id = $value['id'];
			
			if(isset($_POST['startDate'])  && $_POST['startDate'] == date('Y-01-01')  && $_POST['endDate'] == date('Y-m-d') || 
			isset($_POST['startDate'])  && $_POST['startDate'] == date($ly.'-01-01')  && $_POST['endDate'] == date($ly.'-12-31')){ 
				$resultData[$key]['count_data']=$this->DatabaseModel->query("SELECT COUNT(id) as total, MONTH(created_at) as month FROM `support_ticket` WHERE `ticket_type`=". $value['id']." and ticket_id = 0 AND YEAR(created_at)=".date('Y')."  GROUP BY month","array");
				$isyear = 1;
			}else{
				$cond =" AND DATE(support_ticket.created_at) BETWEEN '".$_POST['startDate'] ."' AND '".$_POST['endDate']."'";
				$resultData[$key]['count_data']=$this->DatabaseModel->query("SELECT COUNT(id) as total,DAY(created_at) as month FROM `support_ticket` WHERE `ticket_type`=". $value['id']." and ticket_id=0 ".$cond." GROUP BY month","array");
				$isyear = 0;
				
			}
			
			// if(isset($_POST['startDate'])  && $_POST['startDate'] == date('Y-01-01')  && $_POST['endDate'] == date('Y-m-d') ){ 
			// 	$date1 		= "'".date('Y-m-d' , strtotime($_POST['startDate']))."'";
			// 	$date2 		= "'".date('Y-m-d' , strtotime($_POST['endDate']))."'";
				
			// 	$cond = "ticket_type = ". $id ." AND  ticket_id = 0  AND DATE(created_at) >= $date1 AND DATE(created_at) <= $date2 ";
			// 	$resultData[$key]['count_data'] = $this->DatabaseModel->query("SELECT count(id) as TicketCount , MONTH(created_at) as RegDate FROM support_ticket WHERE ".$cond."  GROUP BY MONTH(created_at)","array");
			// 	$isyear = 1;
			// }else
			// if(isset($_POST['startDate'])  && $_POST['startDate'] == date($ly.'-01-01')  && $_POST['endDate'] == date($ly.'-12-31') ){ 
			// 	$date1 		= "'".date('Y-m-d' , strtotime($_POST['startDate']))."'";
			// 	$date2 		= "'".date('Y-m-d' , strtotime($_POST['endDate']))."'";
				
			// 	$cond = "ticket_type = ". $id ." AND  ticket_id = 0  AND DATE(created_at) >= $date1 AND DATE(created_at) <= $date2 ";
			// 	$resultData[$key]['count_data'] = $this->DatabaseModel->query("SELECT count(id) as TicketCount , MONTH(created_at) as RegDate FROM support_ticket WHERE ".$cond."  GROUP BY MONTH(created_at)","array");
			// 	$isyear = 1;
			// }else
			// if(isset($_POST['startDate'])  && !empty($_POST['startDate'])){
			// 	$date1 		= "'".date('Y-m-d' , strtotime($_POST['startDate']))."'";
			// 	$date2 		= "'".date('Y-m-d' , strtotime($_POST['endDate']))."'";
	
			// 	$cond = "ticket_type = ". $id ." AND  ticket_id = 0  AND DATE(created_at) >= $date1 AND DATE(created_at) <= $date2 ";
			
			// 	$resultData[$key]['count_data'] = $this->DatabaseModel->query("SELECT count(id) as TicketCount , DATE(created_at) as RegDate FROM support_ticket WHERE ".$cond."  GROUP BY DATE(created_at)","array");
			// 	$isyear = 0;
			// }
		}
		// echo $this->db->last_query();die; 
		$resp['isyear'] = $isyear;
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}
	

	function getLatestUserRecord(){
		$join = array('multiple' , array(
									
									array(	'artist_category', 
											'artist_category.category_id  = users.user_level', 
											'left'),	
									array(	'users_content', 
											'users_content.uc_userid	 =  users.user_id ', 
											'left')	
									));
		$resultData = $this->DatabaseModel->select_data('*','users',array('artist_category.category_id'=>1),8,$join,array('user_id','DESC'));
		if(!empty($resultData)){
			foreach($resultData as $key=>$t){
				$resultData[$key]['uc_pic'] = !empty($t['uc_pic']) ? create_upic($t['user_id'], $t['uc_pic']) : user_default_image() ;
				$resultData[$key]['href']   = base_url('profile?user='.$t['user_uname']);
				$resultData[$key]['uc_pic_er']=user_default_image();
			}	
		}
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}
	function getLatestUserRecordEmerging(){
		$join = array('multiple' , array(
									
									array(	'artist_category', 
											'artist_category.category_id  = users.user_level', 
											'left'),	
									array(	'users_content', 
											'users_content.uc_userid	 =  users.user_id ', 
											'left')	
									));
		$resultData = $this->DatabaseModel->select_data('*','users',array('artist_category.category_id'=>2),8,$join,array('user_id','DESC'));
		if(!empty($resultData)){
			foreach($resultData as $key=>$t){
				$resultData[$key]['uc_pic'] = !empty($t['uc_pic']) ? create_upic($t['user_id'], $t['uc_pic']) : user_default_image() ;
				$resultData[$key]['href']   = base_url('profile?user='.$t['user_uname']);
				$resultData[$key]['uc_pic_er']=user_default_image();
			}	
		}
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}
	function latestmemberBrand(){
		$join = array('multiple' , array(
									
									array(	'artist_category', 
											'artist_category.category_id  = users.user_level', 
											'left'),	
									array(	'users_content', 
											'users_content.uc_userid	 =  users.user_id ', 
											'left')	
									));
		$resultData = $this->DatabaseModel->select_data('*','users',array('artist_category.category_id'=>3),8,$join,array('user_id','DESC'));
		if(!empty($resultData)){
			foreach($resultData as $key=>$t){
				$resultData[$key]['uc_pic'] = !empty($t['uc_pic']) ? create_upic($t['user_id'], $t['uc_pic']) : user_default_image() ;
				$resultData[$key]['href']   = base_url('profile?user='.$t['user_uname']);
				$resultData[$key]['uc_pic_er']=user_default_image();
			}	
		}
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}
	function mapData(){
		$resultData=$this->DatabaseModel->query("SELECT `uc_country`,country.country_name FROM `users_content` JOIN `country` ON country.country_id=users_content.uc_country GROUP BY `uc_country`","array");
	
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}
	function getLatestVideo(){
		$join = array('multiple' , array(
			array(	'channel_post_thumb', 
					'channel_post_thumb.post_id = channel_post_video.post_id', 
					'left'),
			array(	'website_mode', 
					'website_mode.mode_id 		= channel_post_video.mode', 
					'left'),
		));	
	
		$resultData = $this->DatabaseModel->select_data('*','channel_post_video',array('delete_status'=> 0,'channel_post_thumb.active_thumb' => 1 ),10, $join,array('channel_post_video.post_id','DESC'));
		foreach($resultData as $key=> $list){
			$image_name 		= 	$list['image_name'];
			$imgn 				=  	base_url('repo/images/thumbnail.jpg');
			$isIvaVideo  		= 	( count(explode('/' , $image_name)) > 1 ) ? 1 : 0;
			$img 				=	( isset($image_name) && !empty($image_name) ) ? getChnlthmb($list['user_id'],$image_name) : $imgn;
			$resultData[$key]['img'] 				= 	( $isIvaVideo ) ? $image_name : $img;
			$resultData[$key]['url']=base_url('watch?p='.$list['post_key']);
			$resultData[$key]['error_image']=$imgn;
			$resultData[$key]['created_at']=date('F d,y',strtotime($list['created_at']));
		}
		$resp['data'] = $resultData;
		$this->statusCode = 1;
		$this->statusType = 'Success';
		$this->respMessage = "";
		$this->show_my_response($resp);
	}

	
}