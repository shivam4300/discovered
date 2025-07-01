<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller { 
	
	public $dateRangeArray = '';
	public $rangeDateArray = array();
	public $uid = '';
	public $CPA = 5;
	public $parentUname = '';

	public function __construct(){
		parent::__construct();
		
		if(isset($_SESSION['user_accesslevel']) && ($_SESSION['user_accesslevel'] == 4 || !is_login()) ) {    /*IF CATEGORY EQUAL TO FAN*/
			redirect(base_url());
		}
		if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'express' ) {    /*IF CATEGORY EQUAL TO FAN*/
			redirect(base_url());
		}
		
		
		$this->load->library(array('manage_session','common')); 
		$this->uid = is_login();

		$this->parentUname = isset($_SESSION['user_uname'])?$_SESSION['user_uname']:'';
		
		$this->rangeDateArray = array(
									1 => date('Y-m-d'),
									2 => date('Y-m-d',strtotime("-1 days")),
									3 => array(date('Y-m-d',strtotime("-7 days")) , date('Y-m-d')),
									4 => array(date('Y-m-d',strtotime("-30 days")) , date('Y-m-d')),
									5 => array(date("Y-m-d", strtotime("first day of this month")) , date("Y-m-d", strtotime("last day of this month"))),
									6 => array(date("Y-m-d", strtotime("first day of previous month")) , date("Y-m-d", strtotime("last day of previous month"))),
									7 => array(date('Y-m-d', strtotime('first day of january this year')) , date('Y-m-d', strtotime('last day of december this year'))),
									8 => array((date("Y")-1).'-01-01' , (date("Y")-1).'-12-31'),
									9 => array('myStartDate' , 'myEndDate')
								);
	}
	 
	function index(){
		$data['page_info'] = array('page'=>'dashboard','title'=>'Dashboard');

		$this->load->view('backend/include/header',$data);
		$this->load->view('backend/dashboard');
		$this->load->view('backend/include/footer');
	}
	
	
	function get_basic_data(){
		
		ini_set('memory_limit', '-1');
		if($this->dateRangeArray == ''){
			$myCondArray = array();
			
			foreach($this->rangeDateArray as $typeK => $dateRange){
				if(is_array($dateRange)){
					$cndData = "(MyDateColumnData >= '".$dateRange[0]."' AND MyDateColumnData <= '".$dateRange[1]."')";
				}else{
					$cndData = "MyDateColumnData = '$dateRange'";
				}
				$myCondArray[$typeK] = $cndData;
			}						
			$this->dateRangeArray = $myCondArray;
		}
		
		
		$myGlobalCond 	= $this->dateRangeArray[$_POST['type']];
		
		if(isset($_POST['start']) && isset($_POST['end'])){
			$myGlobalCond = str_replace('myStartDate' , date('Y-m-d' , strtotime($_POST['start'])) , $myGlobalCond);
			$myGlobalCond = str_replace('myEndDate' , date('Y-m-d' , strtotime($_POST['end'])) , $myGlobalCond);
		}
	
		
		$advertisingEarning = $merchentiseEarning = $mediaEarning = $showsEarning = $partenrShipProgramEarning = $endoresmentEarning = 0;
		
		$dateCoulumn = $adsCoulumn = $tableName = $join = '';
		if(isset($_POST['target']) && ($_POST['target'] == 'advertisingEarning' || $_POST['target'] == 'totalEarning')){ 
			$dateCoulumn 	= 'view_date';
			$adsCoulumn 	= 'ads_count'; 
			$creator_share 	= 'creator_share_amount'; 
			$tableName 		= 'channel_video_view_count_by_date use INDEX(video_userid,view_date)';
			
			if($this->parentUname == PARENT_UNIQUE_NAME){
				$myGlobalCond = '(video_userid = '.$this->uid.' OR channel_post_video.parent_uname = "'.$this->parentUname.'") AND '.str_replace('MyDateColumnData','view_date',$myGlobalCond);
			}else{
				$myGlobalCond = '(video_userid = '.$this->uid.' AND channel_post_video.parent_uname ="") AND '.str_replace('MyDateColumnData','view_date',$myGlobalCond);
			}
			
			$join = array('multiple' , array(
						array(	'channel_post_video', 
								'channel_post_video.post_id = channel_video_view_count_by_date.video_id',
								'left')
					));


			$advertisingEarning = round($this->DatabaseModel->aggregate_data('channel_video_view_count_by_date use INDEX(video_userid,view_date)' ,'creator_share_amount','SUM',$myGlobalCond,$join ),3);
		}
		
		
		$myFilteredData = array();
		if($tableName != ''){
			$myFilteredData = $this->DatabaseModel->select_data("$dateCoulumn,$adsCoulumn,$creator_share" , $tableName , $myGlobalCond, '' , $join);
		}
		
		$myDateWiseAds 		= array();
		$myDateWiseValue 	= array();
		$total_adsCount 	= 0;
		
		foreach($myFilteredData as $myFilterData){
			@ $myDateWiseValue[$myFilterData[$dateCoulumn]]	+= $myFilterData[$creator_share] ;
			$total_adsCount 								+= $myFilterData[$adsCoulumn];
			$myDateWiseAds[$myFilterData[$dateCoulumn]] 	= $myDateWiseValue[$myFilterData[$dateCoulumn]] ;
		}
		
		$labelData 		= array();
		$seriesData 	= array();
		$holdAllView 	= array(0);
		
		if($_POST['type'] <= 8){
			$targetDateRange = $this->rangeDateArray[$_POST['type']];
			
			if(!is_array($targetDateRange)){
				$targetDateRange = array($targetDateRange , $targetDateRange);
			}
		}elseif($_POST['type'] == 9){
			$targetDateRange = array($_POST['start'] , $_POST['end']);
		}else{
			echo json_encode(array('status' => 0));
			exit;
		}

		if($_POST['type']	==	7 || $_POST['type']	==	8){
			$datediff = ceil((strtotime($targetDateRange[1]) - strtotime($targetDateRange[0])) / (60 * 60 * 24*30));
			
			for($dasdas = $datediff-1; $dasdas >= 0; $dasdas--){
				$dt 		= 	date('Y-m-d' ,strtotime("-".$dasdas." months" , strtotime($targetDateRange[1])));
				$dt_last 	= 	date('Y-m-t' ,strtotime("-".$dasdas." months" , strtotime($targetDateRange[1])));
				$new_dt 	=	date("t", strtotime($dt));
				$adsCount	=	0;
				
				for ($i=($new_dt-1); $i>= 0; $i--) { 
					$dt1 = date('Y-m-d' ,strtotime("-".$i." days" , strtotime($dt_last)));
					$adsCount += (isset($myDateWiseAds[$dt1]))?$myDateWiseAds[$dt1]:0;
				}

				array_push($labelData , date('M Y' , strtotime($dt)));
				array_push($seriesData , array('meta' => date('M Y' , strtotime($dt)) , 'value' => $adsCount));
				array_push($holdAllView , $adsCount);
			}
		}else{

			$datediff = ceil((strtotime($targetDateRange[1]) - strtotime($targetDateRange[0])) / (60 * 60 * 24));

			for($dasdas = $datediff; $dasdas >= 0; $dasdas--){
				$dt 		= date('Y-m-d' ,strtotime("-".$dasdas." days" , strtotime($targetDateRange[1])));
				$adsCount 	= (isset($myDateWiseAds[$dt]))?$myDateWiseAds[$dt]:0;

				array_push($labelData , date('d M' , strtotime($dt)));
				array_push($seriesData , array('meta' => date('d M Y' , strtotime($dt)) , 'value' => $adsCount));
				array_push($holdAllView , $adsCount);
			}
		}

		$chartData = array(
						'label' => $labelData,
						'series'=> $seriesData,
						'max' 	=> max($holdAllView)
					);
		
		if(isset($_POST['totalEarningDetail']) || $_POST['target'] == 'totalEarning'){
			$totalEarning = $advertisingEarning+$merchentiseEarning+$mediaEarning+$showsEarning+$partenrShipProgramEarning+$endoresmentEarning;
			
			$totalEarningsBreakdown = array(
										'label'  => array(1 , 2 , 3 , 4 , 5 , 6),
										'series' => array(
													array('meta' => 'Advertising' , 'value' => $advertisingEarning),
													array('meta' => 'Merchentise' , 'value' => $merchentiseEarning),
													array('meta' => 'Media' , 'value' => $mediaEarning),
													array('meta' => 'Shows' , 'value' =>  $showsEarning),
													array('meta' => 'PartenrShipProgram' , 'value' => $partenrShipProgramEarning),
													array('meta' => 'Endoresment' , 'value' => 0)
												)
										);
			
			$resp = array('status' => 1 , 'data' => array(
														'totalEarning' 			=> $totalEarning,
														'advertisingEarning' 	=> $advertisingEarning,
														'merchentiseEarning' 	=> $merchentiseEarning,
														'mediaEarning' 			=> $mediaEarning,
														'showsEarning' 			=> $showsEarning,
														'partenrShipProgramEarning' => $partenrShipProgramEarning,
														'endoresmentEarning' 	=> $endoresmentEarning,
														'mainChart' 			=> $chartData,
														'earningsBreakdown' 	=> $totalEarningsBreakdown,
														'currency'				=>	$this->common->currency
													));
		}else{
			
			$EarningAmount  =  ($_POST['target'] == 'advertisingEarning') ? $advertisingEarning :  0;
			$adsViewsCount  =  ($_POST['target'] == 'advertisingEarning') ? $total_adsCount :  0;
			
			$resp = array('status' => 1 , 'data' => array(
													'mainChart' => $chartData,
													'earningThrough' => array('amount' 		=> $EarningAmount, 
																			  'per' 		=> $adsViewsCount,
																			  'currency'	=>	$this->common->currency
																			  )
													)
											);
		}
		echo json_encode($resp);
	}

}
