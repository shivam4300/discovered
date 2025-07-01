<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class AdTodayGoogleReport extends CI_Controller {

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
		die;
		// $output = exec('cd "/home/discovered-efs/discovered.tv/public_html/ad_manager/src/AdManager/v202002/ReportService/" && php RunTodayInventoryReport.php');
		$output = exec('cd "/home/discovered-efs/discovered.tv/public_html/application/controllers/cron/" && /usr/bin/php7.3 RunTodayInventoryReport.php');
		$this->readReport($output);
	}
	
	public function readReport($output){
		if(!empty($output)){
			$dir =  ABS_PATH.'downloads/'.$output.'.csv.gz' ;
			if(file_exists($dir)){
				$lines 	= gzfile($dir);
				foreach ($lines as $line){
					$d 	= explode(',',$line);
					$v 	= explode('=',$d[0]);
					
					if(trim($v[0]) == 'video_id'){
						
						$earning = (int) $d[5] ;
						 
						$this->Addadscount($v[0],$v[1],(int) $d[4], $earning / 1000000);
					}
				}
				unlink($dir);
				$this->DatabaseModel->access_database('cron_test','insert',array('cron_name'=>'AdTodayGoogleReport','date'=>date('Y-m-d H:i:s')));
			}else{
				$this->index();
			}
		}
	}
	
	function Addadscount($ads_against,$post_id,$ads_count,$earning){
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
			
			$field = 'dtv_discount,dtv_share,creator_share';
			$rate_plan 	= $this->DatabaseModel->select_data($field,'ads_global_rate_details',$cond,2);
			
			$rate_plan 	= (sizeof($rate_plan) == 2) ? $rate_plan[1] : $rate_plan[0] ;
			
			$dda 	= $earning * $rate_plan['dtv_discount']/ 100;
			
			$RM 	= $earning - $dda;
			$dsa 	= $RM * $rate_plan['dtv_share']	/ 100;
			$csa 	= $RM - $dsa;
			
			$table1  = 'channel_video_view_count_by_date'; $index1 = ' use INDEX(video_id,video_userid,view_date)';
			
			$Today  	= 	date('Y-m-d'); 
			
			$cond_array = 	array('video_id'				=>	$post_id,
								  'video_userid'			=>	$user_id,
								  'view_date'				=>	$Today
								 );
			
			$this->db->set('ads_count',''.$ads_count.'', FALSE);
			$this->db->set('dtv_discount_amount',''.$dda.'', FALSE);
			$this->db->set('dtv_share_amount',' '.$dsa.'', FALSE);
			$this->db->set('creator_share_amount',''.$csa.'', FALSE);
			$this->db->where($cond_array);
			$this->db->update($table1.$index1);
		}
				
	}
	public function getHome()
    {
        $home = null;

        if (!empty(getenv('HOME'))) {
            // Try the environmental variables.
            $home = getenv('HOME');
        } elseif (!empty($_SERVER['HOME'])) {
            // If not in the environment variables, check the superglobal $_SERVER as
            // a last resort.
            $home = $_SERVER['HOME'];
        } elseif (!empty(getenv('HOMEDRIVE')) && !empty(getenv('HOMEPATH'))) {
            // If the 'HOME' environmental variable wasn't found, we may be on
            // Windows.
            $home = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        } elseif (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
            $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
        }

        if ($home === null) {
            throw new UnexpectedValueException('Could not locate home directory.');
        }

        echo  rtrim($home, '\\/');
    }
	
	
	
}
