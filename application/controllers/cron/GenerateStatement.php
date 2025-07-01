<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GenerateStatement extends CI_Controller {
	public $uid 		= '';
	
	public $statusCode 	= '';
	public $statusType 	= '';
	public $respMessage = '';
	
	
	public function __construct(){
		parent::__construct(); 
	}
	function index(){
		$this->generate_advertisement_statement();
		$this->generate_advertisement_statement_filmhub();
	}
	
	function generate_advertisement_statement(){
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		
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
	
	
	
}
