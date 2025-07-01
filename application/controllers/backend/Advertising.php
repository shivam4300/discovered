<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advertising extends CI_Controller { 
	
	public $dateRangeArray = '';
	public $rangeDateArray = array();
	
	public $uid = '';
	public $parentUname = '';
	public $statusCode = '';
	public $statusType = '';
	public $respMessage = '';
	
	
	public function __construct(){
		parent::__construct();
		
		if(isset($_SESSION['user_accesslevel']) && ($_SESSION['user_accesslevel'] == 4 || !is_login()) ) {    /*IF CATEGORY EQUAL TO FAN*/
			redirect(base_url());
		}
		
		
		$this->load->library(array('manage_session','common', 'PlayFabXR'));
		$this->load->model('ChannelPostVideoModel');
		$this->load->helper(array('url','api_validation','playfab'));
		
		$this->uid = is_login();
		$this->parentUname = isset($_SESSION['user_uname'])?$_SESSION['user_uname']:'';
		
		
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
		$data['page_info'] 					= array('page'=>'advertising','title'=>'Advertising');

		$join = array('multiple' , array(
					array(	'channel_post_video', 
							'channel_post_video.post_id = channel_video_view_count_by_date.video_id',
							'left')
				));

		$cond1 = '(video_userid = '.$this->uid.' AND channel_post_video.parent_uname ="")';

		$cond2 = '(channel_post_video.user_id = '.$this->uid.' AND channel_post_video.parent_uname ="")';

		if($this->parentUname == PARENT_UNIQUE_NAME){

			$cond1 = '(video_userid = '.$this->uid.' OR channel_post_video.parent_uname = "'.$this->parentUname.'")';

			$cond2 = '(channel_post_video.user_id = '.$this->uid.' OR channel_post_video.parent_uname = "'.$this->parentUname.'")';
		}

		$data['EarningThroughAdvertising'] 	= $this->DatabaseModel->aggregate_data('channel_video_view_count_by_date','creator_share_amount','SUM',$cond1,$join);
		
		$data['NumberOfVideoUploaded'] 		= $this->DatabaseModel->aggregate_data('channel_post_video','post_id','COUNT',$cond2);
		
		$data['TotalAdViewCount'] 			= $this->DatabaseModel->aggregate_data('channel_video_view_count_by_date','ads_count','SUM',$cond1,$join);
		
		$data['AverageEarningPerVideo'] 	= ($data['NumberOfVideoUploaded'] != 0)?$data['EarningThroughAdvertising'] / $data['NumberOfVideoUploaded']:0;
		
		$data['currency'] =  $this->common->currency;
		$this->load->view('backend/include/header',$data);
		$this->load->view('backend/advertising');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
	}
	function show_advertising_details(){
		
		if(isset($_GET['length'])){
			$currency 	=  $this->common->currency;
			$data 		= array();
			$search 	= trim($_GET['search']);
			
			$colm = 4;
			$order = 'ASC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm = $_GET['order'][0]['column'];
				$order = $_GET['order'][0]['dir'];								
			}
			
			$start = $_GET['start'];
			 
			$filed = array(null,'channel_post_thumb.image_name','channel_post_video.title','website_mode.mode','channel_post_video.created_at','SUM(channel_video_view_count_by_date.view_count) AS ViewCount',' SUM(channel_video_view_count_by_date.ads_count) AS AdCount ','SUM(channel_video_view_count_by_date.creator_share_amount) AS ShareAmount','channel_post_video.active_status','channel_post_video.post_key','channel_video_view_count_by_date.video_id','channel_post_video.privacy_status','channel_post_video.post_id','channel_post_video.delete_status','channel_post_video.user_id','channel_post_video.featured_by_user');
			
			$condfiled = array(null,null,'channel_post_video.title','website_mode.mode','channel_post_video.created_at','channel_video_view_count_by_date.view_count','channel_video_view_count_by_date.ads_count','channel_video_view_count_by_date.creator_share_amount','channel_post_video.privacy_status');
			
			$orderfiled = array(null,null,'channel_post_video.title','website_mode.mode','channel_post_video.created_at','ViewCount','AdCount','ShareAmount','channel_post_video.privacy_status','channel_video_view_count_by_date.video_id');
			
			$join = array('multiple' , array(
					array(	'channel_post_video',
							'channel_post_video.post_id = channel_video_view_count_by_date.video_id',
							'right'),
					array(	'website_mode', 
							'website_mode.mode_id 		= channel_post_video.mode', 
							'left'),
					array(	'channel_post_thumb', 
							'channel_post_thumb.post_id = channel_post_video.post_id', 
							'left'),
					));
			
			if($this->parentUname == PARENT_UNIQUE_NAME){
				$cond = '(channel_post_video.user_id = '.$this->uid.' OR channel_post_video.parent_uname = "'.$this->parentUname.'") AND channel_post_thumb.active_thumb  = 1 AND ';
			}else{
				$cond = '(channel_post_video.user_id = '.$this->uid.' AND channel_post_video.parent_uname ="") AND channel_post_thumb.active_thumb  = 1 AND ';
			}
			
			
			if(isset($_GET['delete_status']) && $_GET['delete_status'] != ''){
				$cond .=" delete_status = {$_GET['delete_status']} AND ";
			}
			
			
			$cond .= ' (';
			for($i=0;$i < sizeof($condfiled); $i++){
				if($condfiled[$i] != ''){
					$cond .= "$condfiled[$i] LIKE '%".$this->db->escape_like_str($search)."%' ESCAPE '!' ";
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
				
				$cond .=" AND channel_video_view_count_by_date.view_date >= $date1 AND channel_video_view_count_by_date.view_date <= $date2 ";
			}
			
			if(isset($_GET['mode'])  && !empty($_GET['mode'])){
				$cond .=" AND channel_post_video.mode = " . $_GET['mode'];
			}
			
			if(isset($_GET['privacy_status'])  && !empty($_GET['privacy_status'])){
				$cond .=" AND channel_post_video.privacy_status = " . $_GET['privacy_status'];
			}
			
			$this->session->set_userdata('advertising_details_export',array('filed'=>$filed,'cond'=>$cond,'join'=>$join));
			
			$resultData = $this->DatabaseModel->select_data($filed,'channel_video_view_count_by_date', $cond ,array($_GET['length'],$start) , $join , array($orderfiled[$colm] , $order) ,'','channel_post_video.post_id');
			
			$leadsCount = $this->DatabaseModel->select_data($filed,'channel_video_view_count_by_date', $cond ,'' , $join , '','','channel_post_video.post_id');
			$leadsCount = sizeof($leadsCount);	
			
			foreach($resultData as $list){
				
					$start++;
					$image_name 		= 	$list['image_name'];
					$imgn 				=  	base_url('repo/images/thumbnail.jpg');
					$isIvaVideo  		= 	( count(explode('/' , $image_name)) > 1 ) ? 1 : 0;
					$img 				=	( isset($image_name) && !empty($image_name) ) ? getChnlthmb($list['user_id'],$image_name) : $imgn;
					$img 				= 	( $isIvaVideo ) ? $image_name : $img;
					$is_featured_video 	= 	( $list['featured_by_user'] ) ? 'It\'s a Featured Video' : 'Make it as </br>Featured Video';
					$post_id			=	$list['post_id'];
					$select 			= 	'';
					
					$featured_option = 'Deleted';
					if($list['delete_status'] == 1){
						$select 		= 	'<a href="javascript:;" class="table_embedbtn table_delete">Deleted</a>';
					}else{
						$post_status 	= 	$this->audition_functions->post_status();
						$selected		=	$option  = '';
						foreach($post_status as $value => $key){
							$selected 	= 	( $value == $list['privacy_status']) ? 'selected' : '';
							$option    .=	'<option '.$selected.' value="'.$value.'">'.$key.'</option>';
						}
						
						$select 		= 	'<select class="dash_selectbox_without_search ChangeOption" 
												name="privacy_status" data-url="backend/advertising/updateCheckStatus/channel_post_video" 
													data-id="'.$post_id.'">
												'.$option.'
											 </select>';
											//  onclick="redirect(\'monetize/'.$post_id.'\',10)"
						$featured_option = '<li>
												<a href="'. base_url('monetize/'.$post_id) .'" target="_blank" class="">
													<span class="drop_icon">
														<svg  width="12px" height="auto" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 492.49284 492" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0" fill="#000000" data-original="#000000" class=""></path><path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0" fill="#000000" data-original="#000000" class=""></path></g></svg>
														</span>
													Edit
												</a>	
											</li>
											<li>
												<a class="dtvShareMe common_click"  data-share="2|'.$post_id.'">
													<span class="drop_icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="13px" height="auto" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="hovered-paths"><g><g><path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="m271.176 121.396c-150.205 7.822-271 132.495-271 284.604v106l37.925-88.29c44.854-89.692 133.847-147.041 233.075-152.314v121.318l240.648-196.714-240.648-196z" fill="#000000" data-original="#000000" class="hovered-path"></path></g></g></svg>
														</span>
													Share
												</a>	
											</li>
											<li>
												<a class="delete_channel_video" data-post_id="'.$post_id.'">
													<span class="drop_icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="13px" height="auto" viewBox="0 0 512 512" ><g><g><path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="m424 64h-88v-16c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16h-88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zm-216-16c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96z" fill="#000000" data-original="#000000" class=""></path><path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="m78.364 184c-2.855 0-5.13 2.386-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042c.136-2.852-2.139-5.238-4.994-5.238zm241.636 40c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z" fill="#000000" data-original="#000000" class=""></path></g></g></svg>
														</span>
													Delete
												</a>	
											</li>
											<li>
												<a class="MakeFeatureVideo"  data-post_id="'.$post_id.'" data-user_id="'.$list['user_id'].'">
													<span class="drop_icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="13" height="auto" viewBox="0 0 512 512" ><g><g><path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="m256 8.581-81.035 162.07-174.965 26.938 124.94 126.958-29.721 178.872 160.781-83.452 160.781 83.452-29.721-178.886 124.94-126.944-174.965-26.938zm-15 324.755-51.211-51.211 21.211-21.21 30 30 60-60 21.211 21.211z" fill="#000000" data-original="#000000" class=""></path></g></g></svg>
														
														</span>
													'.$is_featured_video.'
												</a>	
											</li>
											';					 
											 
					}
					
					
					$title = (strlen($list['title'])< 30)?$list['title']:substr($list['title'],0,30)."...";
					
					array_push($data , array(
											'<div class="tbl_serialno">
											'.$start.'.
											</div>
											<div class="tbl_checkbox" >
												<input type="checkbox" name="post_id" id="'.$post_id.'" class="checked_video" value="'.$post_id.'">
												<label for="'.$post_id.'"></label>
											</div>',
											'<div class="table_preview">
												<img src="'.$img.'" alt="preview" onerror="this.onerror=null;this.src=\''.$imgn.'\'">
												<a target="_blank" href="'.base_url('watch?p='.$list['post_key']).'">
												<svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" viewBox="0 0 20 25">
												  <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAZCAMAAAAGyf7hAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAA21BMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8AAADeEzS0AAAAR3RSTlMAI6WcPzbyOgVDbn5KKoHpuRk74Z4dQiDKuhiQsiQ1WDTQvDj0+UwpFN6GX2rdof0x9axX1mIC8fsKSWWAkrG/RyFnjqSVb1myQygAAAABYktHRACIBR1IAAAACXBIWXMAAAsSAAALEgHS3X78AAAAtUlEQVQoz53R1xKCMBAF0CuiIogFexd77733/P8fObhEg8OT9+numcwkswGEeKR3vKJBZhQH+sj8f2LAiUpQ1bSQLqISjlhDNCagEachkfxiyn6diGnmghkXND7GsjmOeVsKxVK5wtGkUoWsoEa9jgaVJhhrtfnJDpUwuj30qQ8wpDIqA+MJ9Slm/O65ydsCyxX7yXoDbH9xZ+1j7zSJlqmKduAbrxw5nc7CR1yut7v0eOo0vQD8IVR/tKozOwAAAABJRU5ErkJggg==" width="20" height="25"/>
												</svg>
												</a>
											</div>',
											'<a class="primary_link" title="'.$list['title'].'">'.remove_special_char($title).'</a>' ,
											ucfirst($list['mode']),
											date('F d,y',strtotime($list['created_at'])),
											$list['ViewCount'],
											$list['AdCount'],
											$currency.round($list['ShareAmount'],3),
											$select,
											'<div class="table_actionboxs">
												<span class="dropdown-toggle" data-video="'.$post_id.'"  data-toggle="dropdown">
													<svg xmlns="https://www.w3.org/2000/svg" width="15px" height="4px" >
													<path fill-rule="evenodd"  fill="rgb(168, 170, 180)"
													d="M13.031,4.000 C11.944,4.000 11.062,3.104 11.062,2.000 C11.062,0.895 11.944,-0.000 13.031,-0.000 C14.119,-0.000 15.000,0.895 15.000,2.000 C15.000,3.104 14.119,4.000 13.031,4.000 ZM7.500,4.000 C6.413,4.000 5.531,3.104 5.531,2.000 C5.531,0.895 6.413,-0.000 7.500,-0.000 C8.587,-0.000 9.469,0.895 9.469,2.000 C9.469,3.104 8.587,4.000 7.500,4.000 ZM1.969,4.000 C0.881,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.881,-0.000 1.969,-0.000 C3.056,-0.000 3.937,0.895 3.937,2.000 C3.937,3.104 3.056,4.000 1.969,4.000 Z"/>
													</svg>
												</span>
											<ul class="action_drop">
											'.$featured_option.'
											</ul></div>'
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
	
	public function updateCheckStatus($table = null){
		$checkValidation = check_api_validation($_POST , array('id|require','status|require'));
			if($checkValidation['status'] == 1){
				if($table == 'channel_post_video'){
					$table_id = "post_id";
					$coloum   = "privacy_status";
				}
				if($table == 'channel_video_playlist'){
					$table_id = "playlist_id";
					$coloum   = "privacy_status";
				} 
				if($this->DatabaseModel->access_database($table,'update',array($coloum=>$_POST['status']),array($table_id=>$_POST['id'])) > 0){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Status Updated Successfully.';
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}

	
	public function advertising_details_export(){
		
		$data = $this->session->userdata('advertising_details_export');
		$resultData = $this->DatabaseModel->select_data($data['filed'],'channel_video_view_count_by_date',$data['cond'],'',$data['join'] , '','','channel_post_video.post_id');
		
		$heading = false;
		$post_status = $this->audition_functions->post_status();
		
		// if(!empty($resultData)){
			$filename = 'advertising-'.date('his').".xls";		 
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			ob_end_clean();
			foreach($resultData as $row){
				unset($row['image_name'],$row['active_status'],$row['video_id'],$row['delete_status'],$row['post_id'],$row['user_id'],$row['featured_by_user']);
				
				$row['post_key'] = base_url('watch?p='.$row['post_key']);
				$row['privacy_status'] = isset($post_status[$row['privacy_status']]) ? $post_status[$row['privacy_status']] : '';
				
				if(!$heading) {
				  echo implode("\t", array_keys($row)) . "\n";
				  $heading = true;
				}
				echo implode("\t", array_values($row)) . "\n";
			}
		// }			
		exit;
	}
	
	
	public function MakeFeatureVideo(){
		
		$checkValidation = check_api_validation($_POST , array('post_id|require','user_id|require'));
			if($checkValidation['status'] == 1){
				
				$table 	= 'channel_post_video ';
				$coloum = 'featured_by_user ';
				
				$cond 	= array('user_id'=>$_POST['user_id']);
				$this->DatabaseModel->access_database($table,'update',array($coloum=>0),$cond);
				
				$cond 	= array('post_id'=>$_POST['post_id'],'user_id'=>$_POST['user_id']);
				if($this->DatabaseModel->access_database($table,'update',array($coloum=>1),$cond) > 0){
					if (!playfabUpdateWeeklyChallengeMissionFeaturedVideo($_POST['user_id'], $_POST['post_id'])) {
						$this->respMessage = 'You have successfully updated the feature video. But something went wrong while updating your Weekly Challenges.';
					} else {
						$this->statusCode = 1;
						$this->statusType = 'Success'; 
						$this->respMessage = 'You have successfully updated the feature video.';
					}
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	
	
	
	
}
