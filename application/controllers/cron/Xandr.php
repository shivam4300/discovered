<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xandr extends CI_Controller {

	public function __construct()
	{
		//die;
		parent::__construct();
		
	}

	
	function index(){
        $authpath = ABS_PATH.'application/controllers/cron/auth.txt';
       
        $output = exec('curl -b cookies -c cookies -X POST -d @'.__DIR__.'/auth.txt "https://api.appnexus.com/auth" ');
        $output = json_decode($output,true);

        if(isset($output['response']['status']) && $output['response']['status'] == 'OK'){
            $token =  $output['response']['token'];
            // $output = exec('curl -b cookies "https://api.appnexus.com/report?advertiser_id=6001369" -XPOST -d@'.__DIR__.'/key_value_analytics.txt');
            $output = exec('curl -b cookies "https://api.appnexus.com/report?placement_id=2049643" -XPOST -d@'.__DIR__.'/key_value_analytics.txt');
            $output = json_decode($output,true);
           
            if(isset($output['response']['status']) && $output['response']['status'] == 'OK'){
                $report_id =  $output['response']['report_id'];
            
                $output = exec('curl -b cookies "https://api.appnexus.com/report-download?id="'. $report_id.' > ' . __DIR__.'/video_analytics_network.csv' );
            }
            
            // $this->readReport($output);
        }else{
			log_message('error', 'Path : controllers/cron/Xandr.php  :  ' . $output['response']['error']);
		}
    } 

    public function readReport($output){
	
			$dir =  __DIR__.'/video_analytics_network.csv' ;
           
			if(file_exists($dir)){
                $this->DatabaseModel->access_database('cron_test','insert',array('cron_name'=>'Xandr','date'=>date('Y-m-d H:i:s') )); 
				$lines 	= gzfile($dir);
                
				foreach ($lines as $line){
					$column 	= explode(',',$line);
					
					if(trim($column[2]) == 'video_id'){
                        $earning    = number_format($column[8],3);
                        $post_id    = $column[4];
                        $ads_count  = $column[6];
                        $ads_date   = $column[0];
						$this->Addadscount($ads_date,$post_id, $ads_count,$earning);
					}
				}
				unlink( __DIR__.'/video_analytics_network.csv');				
			}else{
				$this->index();
			}
	
	}
	
	function Addadscount($ads_date,$post_id,$ads_count,$earning){
		// echo $ads_date .'<br>' . $post_id. '<br>' . $ads_count.'<br>'.$earning;die;
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
				$cond  .= 	'plan_type = 0  OR country = '.$detail[0]['uc_country'];
			}
			
			$field      = 'dtv_discount,dtv_share,creator_share';
			$rate_plan 	= $this->DatabaseModel->select_data($field,'ads_global_rate_details',$cond,2);
			
			$rate_plan 	= (sizeof($rate_plan) == 2) ? $rate_plan[1] : $rate_plan[0] ;
			
			$dda 	    = $earning * $rate_plan['dtv_discount']/ 100;
			
			$RM 	    = $earning - $dda;
			$dsa 	    = $RM * $rate_plan['dtv_share']	/ 100;
			$csa 	    = $RM - $dsa;
			
			$table1     = 'channel_video_view_count_by_date'; 
            $index1     = ' use INDEX(video_id,video_userid,view_date)';
			
			$cond_array = array(
                'video_id'				=>	$post_id,
                'video_userid'			=>	$user_id,
                'view_date'				=>	date('Y-m-d',strtotime($ads_date))
            );
          
        
			$this->db->set('xndr_ads_count',''.$ads_count.'', FALSE);
			$this->db->set('xndr_dtv_discount_amount',''.$dda.'', FALSE);
			$this->db->set('xndr_dtv_share_amount',' '.$dsa.'', FALSE);
			$this->db->set('xndr_creator_share_amount',''.$csa.'', FALSE);

			$this->db->where($cond_array);
			$this->db->update($table1.$index1);
		}
				
	}

	function setPlacement(){
		$output = exec('curl -b cookies -c cookies -X POST -d @'.__DIR__.'/auth.txt "https://api.appnexus.com/auth" ');
        $output = json_decode($output,true);
		
        if(isset($output['response']['status']) && $output['response']['status'] == 'OK'){
            $output = exec('curl -b cookies -c cookies "https://api.appnexus.com/placement-set?member_id=13448&publisher_id=2049643" -X POST -d@'.__DIR__.'/placement_set.txt');
            $output = json_decode($output,true);
           
            echo '<pre>';
			print_r($output);
        }else{
			echo '<pre>';
			print_r($output);
		}
    } 

	public function readMagniteReport(){ 
		
		$dir =  __DIR__.'/video_analytics_network.csv' ;
	
		if(file_exists($dir)){
			
			$lines 	= gzfile($dir);
			
			foreach ($lines as $key => $line){
				$column 	= explode(',',$line);
				$earning    = number_format((float) $column[7],3);

				$post_id    = $column[2];
				$ads_count  = $column[6];
				$ads_date   = $column[0];
				
				$ads_date = trim( $ads_date,'"');
				$ads_date = date('Y-m-d',strtotime($ads_date));

				$post_id = preg_replace("/[^0-9]/", "", $post_id );

				if($post_id){
					$this->Addadscount($ads_date,$post_id, $ads_count,$earning);
				}
			}
		}else{
			$this->index();
		}
	}
	
	
}
