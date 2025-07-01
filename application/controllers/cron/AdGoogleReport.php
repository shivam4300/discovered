<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdGoogleReport extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		if(isset($_POST) && !empty($_POST)){
	        if(!isset($_SERVER['HTTP_REFERER'])){
                die('Direct Access Not Allowed!!');
	        }
	    }
	
	}
	/* DONT RUN THIS API IN ANY CASE MANUALLY */
	/* GOOGLE AD EXCHANGE REPORT START */
	function index(){
	
		try { 
			$output = exec('cd "'.ABS_PATH.'application/controllers/cron/" && /usr/bin/php7.3 RunInventoryReport.php 2>&1');
			// print_r($output);die;
			if(!empty($output)){
				$r = $this->common->CallCurl('POST',json_encode(['output' => $output]), base_url('cron/AdGoogleReport/readReport/'.$output),['Content-Type:application/json']);
				print_r($r);die;
			}else{
				echo 'no output';
			}
					
		}catch(Exception $e) {
			log_message('error', 'Path : controllers/cron/AdGoogleReport.php  :  ' . $e->getMessage());
		}
	}
	
	public function readReport($output){
		
		if(!empty($output)){ 
			$dir =  trim(ABS_PATH.'downloads/'.$output.'.csv.gz') ;
			
			if(file_exists($dir)){
				
				$lines 	= gzfile($dir);
				
				foreach ($lines as $line){
					$column 	= explode(',',$line);
					$content 	= explode('=',$column[0]);
					
					if(trim($content[0]) == 'video_id'){
						$this->Addadscount($content[0],$content[1],(int) $column[5], (int) $column[6] / 1000000,date('Y-m-d',strtotime($column[1])));
					}
				}
				$this->DatabaseModel->access_database('cron_test','insert',array('cron_name'=>'AdGoogleReport','date'=>date('Y-m-d H:i:s') )); 
				echo json_encode(['status' => 1]); 
			}else{
				
				echo json_encode(['status' => 2 ,'dir' => $dir]); 
			}
		}else{
			echo json_encode(['status' => 3]); 
		}
	}
	
	function Addadscount($ads_against,$post_id,$ads_count,$earning,$date){
		$join = array('multiple' , array(
							array(	'users_content', 
									'users_content.uc_userid= channel_post_video.user_id ',
									'left'),
							));
			
		$condition = array('channel_post_video.post_id'=>$post_id);
		
		$detail = $this->DatabaseModel->select_data('channel_post_video.user_id,channel_post_video.video_ads_rate_plan,users_content.user_ads_rate_plan,users_content.uc_country','channel_post_video use INDEX(post_id)',$condition,1,$join);
			
		if(isset($detail[0])){
			
			$user_id 	= 	$detail[0]['user_id'];
			$cond 		= 	'status = 1 AND ';
			
			if(!empty($detail[0]['video_ads_rate_plan'])){
				$cond  .= 	'rdetail_id = '.$detail[0]['video_ads_rate_plan'];
			}else
			if(!empty($detail[0]['user_ads_rate_plan'])){
				$cond  .= 	'rdetail_id = '.$detail[0]['user_ads_rate_plan'];
			}else{
				$cond  .= 	'plan_type = 0 OR country = '.$detail[0]['uc_country'];
			}
			
			$field = 'dtv_discount,dtv_share,creator_share';
			$rate_plan 	= $this->DatabaseModel->select_data($field,'ads_global_rate_details',$cond,2);
			
			$rate_plan 	= (sizeof($rate_plan) == 2) ? $rate_plan[1] : $rate_plan[0] ;
			
			$dda 		= $earning * $rate_plan['dtv_discount']/ 100;
			
			$RM 		= $earning - $dda;
			$dsa 		= $RM * $rate_plan['dtv_share']	/ 100;
			$csa 		= $RM - $dsa;
			
			$table1  	= 'channel_video_view_count_by_date'; $index1 = ' use INDEX(video_id,video_userid,view_date)';
			
			$YESTRDAY  	= 	date('Y-m-d',strtotime("-1 days")); 
			
			$cond_array = 	array(
								'video_id'				=>	$post_id,
								'video_userid'			=>	$user_id,
								'view_date'				=>	$date
							);
		
			$this->db->set('gam_ads_count',''.$ads_count.'', FALSE);
			$this->db->set('gam_dtv_discount_amount',''.$dda.'', FALSE);
			$this->db->set('gam_dtv_share_amount',' '.$dsa.'', FALSE);
			$this->db->set('gam_creator_share_amount',''.$csa.'', FALSE);
			$this->db->where($cond_array);
			$this->db->update($table1.$index1);
		}
				
	}
	
	
	
}
