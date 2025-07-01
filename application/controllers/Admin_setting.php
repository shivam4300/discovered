<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_setting extends CI_Controller {
	
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
	
    public function main(){ 
		$data['user_level'] = $this->DatabaseModel->access_database('user_level_type','select','','');
		$data['video_level'] = $this->DatabaseModel->access_database('video_level_chart','select','','');
		$data['website_mode'] = $this->DatabaseModel->access_database('website_mode','select','','');
		
		$data['genre_mode'] = $this->DatabaseModel->select_data('mode_of_genre.image,mode_of_genre.mode_id,mode_of_genre.genre_id,mode_of_genre.genre_name,mode_of_genre.status,website_mode.mode','mode_of_genre','','',array('website_mode','mode_of_genre.mode_id = website_mode.mode_id'),array('mode_of_genre.browse_order','ASC'));
		
		$data['language_list'] = $this->DatabaseModel->access_database('language_list','select','','');
		
		$data['country_list'] = $this->DatabaseModel->select_data('country_id,country_name','country',array('status'=>1));
		
		$data['page_menu']  = 'setting|main_setting|Main Setting|mainsetting'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/main_setting');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}
    public function access_ads_global_rate_details(){
		if(isset($_GET['length'])){
			$data = array();
			$search = trim($_GET['search']['value']);
			
			$colm = 1;
			$order = 'DESC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm = $_GET['order'][0]['column'];
				$order = $_GET['order'][0]['dir'];								
			}
			
			$cond = "";
			
			$start = $_GET['start'];
			
			$filed 		= ['rdetail_id','plan_name','dtv_discount','dtv_share','creator_share','plan_type','country_name','ads_global_rate_details.status','rate_calculation','created_at'];
			
			$orderfiled = ['rdetail_id','plan_name','dtv_discount','dtv_share','creator_share','plan_type','country_name','ads_global_rate_details.status','rate_calculation','created_at'];
			
			$join 		= array('multiple' , array(
								array(	'country', 
										'ads_global_rate_details.country 	= country.country_id',
										'left')
								));
		
			$cond .= '(';
			for($i=0;$i < sizeof($orderfiled); $i++){
				if($orderfiled[$i] != ''){
					$cond .= "$orderfiled[$i] LIKE '%".$search."%'";
					if(sizeof($orderfiled) - $i != 1){
						$cond .= ' OR ';
					}
				}
			}
			$cond .= ')';
			
			$queryData = $this->DatabaseModel->select_data($filed,'ads_global_rate_details', $cond ,array($_GET['length'],$start) , $join , array($orderfiled[$colm] , $order));
			
			$leadsCount 	 =	$this->DatabaseModel->aggregate_data('ads_global_rate_details','rdetail_id','COUNT',$cond,$join);
			$totalLeadsCount =	$this->DatabaseModel->aggregate_data('ads_global_rate_details','rdetail_id','COUNT','',$join);
			
			foreach($queryData as $list){
					$start++;
					$checked = ($list['status'] == 1)? 'checked' : '';
					$plan_type = ($list['plan_type'] == 0)? 'Global' : ( $list['plan_type'] == 1 ? 'Country Specific' : ( $list['plan_type'] == 2 ? 'User Specific' : 'Video specific' )) ;
					array_push($data , array(
							$start,
							$list['plan_name'],
							$list['dtv_discount'],
							$list['dtv_share'],
							$list['creator_share'],
							$plan_type,
							$list['country_name'],
							'<input '. $checked .' type="checkbox" data-check-id="'.$list['rdetail_id'].'" data-action-url="admin/updateCheckStatus/ads_global_rate_details">',
							date('d-F-y',strtotime($list['created_at']))	,
							'<a href=""  class="getAdsPlanRate" data-id="'.$list['rdetail_id'].'" data-plan_type="'.$list['plan_type'].'"><i class="fa fa-fw fa-edit"></i></a>',							
							'<a href="" data-delete-id="'.$list['rdetail_id'].'" data-field="rdetail_id" data-action-url="admin/deleteRowContent/ads_global_rate_details"><i class="fa fa-fw fa-trash"></i></a>',								
							));
			}
			echo json_encode(array( 
				'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
				'recordsTotal' => $totalLeadsCount,
				'recordsFiltered' => $leadsCount,
				'data' => $data, 
				));
			
			}
	}
	function getAdsPlanRate($id= null,$plan_type = null){
		if(!empty($id)){
			$result = $this->DatabaseModel->select_data('*','ads_global_rate_details',array('rdetail_id'=>$id),1);
			
			if(isset($result[0])){
				$list = '';
				if($plan_type == 2){
					if(isset($result[0]['user_ids']) && !empty($result[0]['user_ids'])){
						$where = 'user_id IN('.implode(',',json_decode($result[0]['user_ids'],true)).')';
						$list = $this->DatabaseModel->select_data('user_id As id,user_name As name','users',$where);
					}

				}elseif($plan_type == 3){
					if(isset($result[0]['video_ids']) &&  !empty($result[0]['video_ids'])){
						$where = 'post_id IN('.implode(',',json_decode($result[0]['video_ids'],true)).')';
						$list = $this->DatabaseModel->select_data('post_id As id,title As name','channel_post_video',$where);
						
					}
				}

				echo json_encode(array('status'=>1,'data'=>$result[0],'list' => $list));	
			}else{
				echo json_encode(array('status'=>0,'message'=>'data not available.'));
			} 	
		}else{
			echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again.'));	
		}
	}
    function AddAdsPlan(){
		if ($this->input->is_ajax_request()){
			$checkValidation = check_api_validation($_POST , array('plan_name|require','plan_type|require','dtv_discount|require','dtv_share|require','creator_share|require'));
			if($checkValidation['status'] == 1){
			
				$array = [
							'plan_name' 		=>  $_POST['plan_name'],
							'dtv_discount'		=> 	$_POST['dtv_discount'],
							'dtv_share'			=> 	$_POST['dtv_share'],
							'creator_share'		=> 	$_POST['creator_share'],
							'plan_type'			=>	$_POST['plan_type'],
							'country'			=>	!empty($_POST['country']) ? $_POST['country'] : '',
							'video_ids'			=>  !empty($_POST['video_ids']) ? json_encode($_POST['video_ids']) : '',
							'user_ids'			=>  !empty($_POST['user_ids']) ? json_encode($_POST['user_ids']) : '',
							'created_at'		=> 	date('Y-m-d H:i:s')
							];
							
				if(isset($_POST['rdetail_id']) && !empty(trim($_POST['rdetail_id']))){
					$rdetail_id = $_POST['rdetail_id'];
					if($this->DatabaseModel->access_database('ads_global_rate_details','update',$array ,array('rdetail_id'=>$rdetail_id)) > 0){
						$this->respMessage 	= 'You have updated the plan successfully.';
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';						
					}else{
						$this->respMessage 	= 'Something went wrong,please try again.';
					}
				}else{
					if($rdetail_id = $this->DatabaseModel->access_database('ads_global_rate_details','insert',$array)){
						$this->respMessage 	= 'You have added the plan successfully.';
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';		
					}else{
						$this->respMessage 	= 'Something went wrong,please try again.';
					}
				}

				if($_POST['plan_type'] == 2){
					if(!empty($_POST['user_ids'])){
						$where = 'uc_userid IN('.implode(',',$_POST['user_ids']).')';
						$this->DatabaseModel->access_database('users_content','update',['user_ads_rate_plan' => '' ],['user_ads_rate_plan' => $rdetail_id ]);
						$this->DatabaseModel->access_database('users_content','update',['user_ads_rate_plan' => $rdetail_id ],$where);
					}

				}elseif($_POST['plan_type'] == 3){
					if(!empty($_POST['video_ids'])){
						$where = 'post_id IN('.implode(',',$_POST['video_ids']).')';
						$this->DatabaseModel->access_database('channel_post_video','update',['video_ads_rate_plan' => ''],['video_ads_rate_plan' => $rdetail_id ]);
						$this->DatabaseModel->access_database('channel_post_video','update',['video_ads_rate_plan' => $rdetail_id ],$where);
					}
				}
				


			}else{
				$this->respMessage = $checkValidation['message'];
			}
			
			$this->show_my_response();
			
		}else{
			 exit('No direct script access allowed');
		}
	}

    public function pages(){
		
	
		$data['page_menu']  = 'setting|pages_setting|Page Setting|pagesetting'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/pages_setting');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}

    public function access_page_setting_list(){
		
		$data = array();
		$leadsCount = 0;
		$accessParam['where'] = '';
		$search = trim($_GET['search']['value']);
		$start 		= 	$_GET['start'];
		$cond='';
		if(!empty($search)){
			$cond = "page_setting.cover_image_title LIKE '%".$search."%' OR page_setting.cover_image_subtitle LIKE '%".$search."%' OR page_setting.cover_over_image LIKE '%".$search."%' OR website_mode.mode LIKE '%".$search."%'";
		}
		
		$join = array('multiple' , 
						array(
							array(	'website_mode', 
									'website_mode.mode_id 	= page_setting.website_mode', 
									'left'),
						));			
					
					
		$data_list		=	$this->DatabaseModel->select_data('page_setting.*,website_mode.mode','page_setting',$cond,array($_GET['length'],$start),$join);
		$leadsCount 	 =	$this->DatabaseModel->aggregate_data('page_setting','id','COUNT',$cond,$join);
		$totalLeadsCount =	$this->DatabaseModel->aggregate_data('page_setting','id','COUNT','',$join);
		if(!empty($data_list)){
				$i=0;
				foreach($data_list as $list){
					$check = '';
					if($list['cover_image_status'] == 1){
						$check = 'checked';
					}
					
					array_push($data , array(
						$list['mode'],
						'<img src="'.base_url('repo_admin/images/homepage/'.$list['cover_image']).'" style="width: 130px;" alt="Image" onerror="this.onerror=null;this.src=\''.base_url('repo/images/thumbnail.jpg').'\'">',
						$list['cover_image_title'],
						$list['cover_image_subtitle'],
						$list['cover_over_image'],
						'<input '.$check.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="admin/updateCheckStatus/page_setting">',
						'<a class="support_team" href="'.base_url('admin_setting/add_page_data/'.$list['id']).'"><i class="fa fa-fw fa-edit"></i></a>
						<!--a data-action-url="admin/deleteRowContent/page_setting" data-delete-id="'.$list['id'].'" data-field="id"><i class="fa fa-trash"></i></a-->',
					));						
					$i++;
				}
		}
		
		echo json_encode(array(
					'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
					'recordsTotal' => $totalLeadsCount,
					'recordsFiltered' => $leadsCount,
					'data' => $data,
					));
	}

    public function add_page_data($id=""){
		$data['page_menu']  = 'setting|add_pages_setting|Add Setting|pagesetting'; 
		$cond				=	array('page_status'=>1);
		$data['web_mode'] 	= 	$this->DatabaseModel->select_data('mode_id,mode','website_mode',$cond);
		$user_list= []; /*$this->query_builder->user_list(array(	
														'where' => 	'user_status=1,user_role=member',
														'field'	=>	'users.user_id,users.user_name,users_content.uc_pic'
			 								)); */
											
		if(isset($user_list)){
			$data['user_list'] = $user_list['users'];
		}
		if(!empty($id)){
			
			$data['page_data']=$this->DatabaseModel->select_data('*','page_setting',array('id' =>$id));
			
			if(empty($data['page_data'])){
				redirect('admin/pages_setting');
			}
			
			$data['page_data'][0]['user_name'] = get_user_fullname($data['page_data'][0]['user_id']);
			
			$data['ModesVideo'] = $this->getClientVideos($data['page_data'][0]['user_id']);
			// print_r($data['ModesVideo']);die;
			$data['page_music'] =$this->audition_functions->get_new_cover_video($data['page_data'][0]['cover_video']);
		}
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/add_page_data',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}
    
    public function page_setting(){
		$user_list= $this->query_builder->user_list(array(
														'where' => 	'user_status=1,user_role=member',
														'field'	=>	'users.user_id,users.user_name,users_content.uc_pic'
													)); 
		if(isset($user_list)){
			$data['user_list'] = $user_list['users'];
		}
		
		$data['musc_vid'] = array();
		$data['movi_vid'] = array();
		
		$data['homepage_music'] 		= $this->audition_functions->get_cover_video('homepage','music');
		$data['homepage_movies'] 		= $this->audition_functions->get_cover_video('homepage','movies');
		$data['homepage_television'] 	= $this->audition_functions->get_cover_video('homepage','television');
		$data['homepage_social'] 		= $this->audition_functions->get_cover_video('homepage','social');
		$data['homepage_gaming'] 		= $this->audition_functions->get_cover_video('homepage','gaming');
		
		
		if(isset($data['homepage_music'])){
			$data['musc_vid'] = $this->getClientVideos($data['homepage_music']['user_id'],1); /* 1 Means music mode*/	
		}
		if(isset($data['homepage_movies'])){
			$data['movi_vid'] = $this->getClientVideos($data['homepage_movies']['user_id'],2); /* 2 Means movies mode*/	
		}
		if(isset($data['homepage_television'])){
			$data['tv_vid'] = $this->getClientVideos($data['homepage_television']['user_id'],3); /* 3 Means television mode*/	
		}
		if(isset($data['homepage_social'])){
			$data['social_vid'] = $this->getClientVideos($data['homepage_social']['user_id']); /* 3 Means social mode*/	
		}
		if(isset($data['homepage_gaming'])){
			$data['game_vid'] = $this->getClientVideos($data['homepage_gaming']['user_id'],7); /* 3 Means social mode*/	
		}
	
		$data['page_menu']  = 'setting|page_setting|Page Setting|pagesetting'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/page_setting');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}

    function getClientVideos($user_id = null,$mode = null){
	
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$user_id	=	$_POST['id'] ;
		}

		if(isset($_POST['mode']) && !empty($_POST['mode']) && $_POST['mode'] != 4 && $_POST['mode'] != 8 && $_POST['mode'] != 9){   // social ,spotlight, live
			$mode	=	$_POST['mode'] ;
		}

		if(!empty($user_id)){
			$data=[];
			$where = 'user_id='.$user_id.' AND privacy_status=7 ';
			
			$accessParam = array(
				'where' => 'user_id='.$user_id.',privacy_status=7',
				'field'	=>	'channel_post_video.post_id,channel_post_video.post_key,channel_post_video.title,,channel_post_video.uploaded_video'	
			);
			
			if(!empty($mode)){
				 $accessParam['where'] .= ',mode='.$mode.'';
				 $where .= 'AND mode='.$mode.' ';
			}

			if(isset($_POST['post_id']) && !empty($_POST['post_id'])){
				 $accessParam['where'] .= ',post_id='.$_POST['post_id'].'';
				  $where .= 'AND post_id='.$_POST['post_id'].' ';
			}
			
			$channel_video_list = 	$this->DatabaseModel->select_data('channel_post_video.post_id,channel_post_video.post_key,channel_post_video.title,channel_post_video.video_type,channel_post_video.is_stream_live,channel_post_video.uploaded_video','channel_post_video',$where);
			
			if(!empty($channel_video_list)){
				foreach($channel_video_list as $list){
					
					
					if($list['video_type'] == 2 && $list['is_stream_live'] == 1){
						$vid_name = $list['uploaded_video'];
					}else
					if($list['video_type'] == 2){
						$r = $this->valuelist->createS3SubKey($list['uploaded_video'], '', $ext = '.m3u8', $explode = 'live/');
						$vid_name 		=  AMAZON_TRANCODE_URL . $r['childKey'];
					}else{
						$vid_name 		=  AMAZON_URL . $list['uploaded_video'];
						$isIvaVideo  	= (count(explode('://' , $list['uploaded_video'])) > 1)?1:0;
						$vid_name		= ($isIvaVideo)? trim($list['uploaded_video']): $vid_name;
					}
					
					$data[] = array('id' => $list['post_id'], 'name' => $list['title'], 'video' => $vid_name) ;
				}
			}
			if(!isset($_POST['id'])){
				return $data;
			}else{
				echo json_encode(array('status'=>1,'data'=>$data));				
			}
			
		}
	}
	

    public function helpAndFaq(){
		$data = [];
		$data['page_menu']  = 'setting|help_and_faq|Help And Faq|helpAndFaq'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/helpAndFaq');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}
    public function access_help_enquiry($type = null){
		if(isset($_GET['length'])){
			$data = array();
			$search = trim($_GET['search']['value']);
			
			$colm = 1;
			$order = 'DESC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm = $_GET['order'][0]['column'];
				$order = $_GET['order'][0]['dir'];								
			}
			
			$cond = "type = {$type} AND ";
			
			$start = $_GET['start'];
			
			$filed 		= [null,'icon_image','title','subject','show_status','status','description','faq_id'];
			
			if($type == 2){
				$orderfiled = [null,'icon_image','title','subject','show_status','status'];
			}else{
				$orderfiled = [null,'subject','description','show_status','status'];
			}	
			
			
			$join = '';
		
			$cond .= '(';
			for($i=0;$i < sizeof($orderfiled); $i++){
				if($orderfiled[$i] != ''){
					$cond .= "$orderfiled[$i] LIKE '%".$search."%'";
					if(sizeof($orderfiled) - $i != 1){
						$cond .= ' OR ';
					}
				}
			}
			$cond .= ')';
			
			$queryData = $this->DatabaseModel->select_data($filed,'help_faq', $cond ,array($_GET['length'],$start) , $join , array($orderfiled[$colm] , $order));
			$leadsCount =	$this->DatabaseModel->aggregate_data('help_faq','faq_id','COUNT',$cond,$join);
			
			foreach($queryData as $list){
					$start++;
					
					$checked = ($list['status'] == 1)? 'checked="checked"' : '';
					$Checkedtitle   =  ($checked == 'checked="checked"') ? 'Click to disabled' : 'Click to enable';
					
					$show_status = ($list['show_status'] == 1)? 'Public' : 'Private';
					
					if($type == 2){
						array_push($data , array(
							$start,
							'<img src="'.base_url('uploads/admin/enquiry/'.$list['icon_image']).'">',
							$list['title'],
							$list['subject'],
							$show_status,
							'<input '. $checked .' type="checkbox" data-check-id="'.$list['faq_id'].'" data-action-url="admin/updateCheckStatus/help_faq" title="'.$Checkedtitle.'"><br>
							<a href=""  class="getEnqury" data-id="'.$list['faq_id'].'" ><i class="fa fa-fw fa-edit"></i></a>	<br>	
							<a href="" data-delete-id="'.$list['faq_id'].'" data-field="faq_id" data-action-url="admin/deleteRowContent/help_faq"><i class="fa fa-fw fa-trash"></i></a><br>',	
							));
					}else{
						array_push($data , array(
							$start,
							$list['subject'],
							$show_status,
							nl2br($list['description']),
							'<input '. $checked .' type="checkbox" data-check-id="'.$list['faq_id'].'" data-action-url="admin/updateCheckStatus/help_faq" title="'.$Checkedtitle.'"><br>
							<a href=""  class="getHelp" data-id="'.$list['faq_id'].'" ><i class="fa fa-fw fa-edit"></i></a>	<br>	
							<a href="" data-delete-id="'.$list['faq_id'].'" data-field="faq_id" data-action-url="admin/deleteRowContent/help_faq"><i class="fa fa-fw fa-trash"></i></a>',	
							));
					}
					
					
						
			}
			echo json_encode(array( 
				'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
				'recordsTotal' => $leadsCount,
				'recordsFiltered' => $leadsCount,
				'data' => $data, 
				));
			
			}
	}

    public function add_new_enquiries(){
		if ($this->input->is_ajax_request()){
			$icon_image='';
			if(isset($_FILES['icon_image']['name']) && !empty(trim($_FILES['icon_image']['name']))){
				if(isset($_POST['faq_id']) && !empty(trim($_POST['faq_id']))){
					$result = $this->DatabaseModel->select_data('icon_image','help_faq',array('faq_id'=>$_POST['faq_id']),1);
					$pathToImages = ABS_PATH .'uploads/admin/enquiry/';
					if(isset($result[0]['icon_image']) &&  file_exists($pathToImages.$result[0]['icon_image'])){
						@ unlink($pathToImages.$result[0]['icon_image']);
					} 
				}
						
				$pathToVideo 	= ABS_PATH .'uploads/admin/enquiry';
				$uploaded 		= $this->audition_functions->upload_file($pathToVideo,'jpg|jpeg|png|gif','icon_image',true,10000);
								
				if($uploaded != 0 ){
					$icon_image = $uploaded['file_name'];
					$this->audition_functions->resizeImage('68','68',$pathToVideo.'/'.$icon_image,'',false,false);
				}
			}
			
			if(isset($_POST['faq_id']) && !empty(trim($_POST['faq_id']))){
				$array = [	
							'title' 		=> trim($_POST['title']),
							'subject' 		=> trim($_POST['subject']),
							'description' 	=> base64_decode($_POST['description']),
							'show_status' 	=> $_POST['show_status'],
						];
				if(!empty($icon_image)){
					$array['icon_image'] = $icon_image;
				}									
				if($this->DatabaseModel->access_database('help_faq','update',$array ,array('faq_id'=>$_POST['faq_id'])) > 0){
					echo json_encode(array('status'=>1,'message'=>'You have updated enquiry successfully.'));	
				}else{
					echo json_encode(array('status'=>0,'message'=>'Oops! There’s nothing new to update here.'));	
				}
			}else{
				$array = [	
							'title' 		=> trim($_POST['title']),
							'subject' 		=> trim($_POST['subject']),
							'description' 	=> base64_decode($_POST['description']),
							'show_status' 	=> $_POST['show_status'],
							'icon_image'	=> $icon_image,
							'created_at'	=> date('Y-m-d H:i:s'),
							'type'			=> 2	
													];
				
				if($this->DatabaseModel->access_database('help_faq','insert',$array)){
					echo json_encode(array('status'=>1,'message'=>'You have added enquiry successfully.'));	
				}else{
					echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again2.'));		
				}
			}
		}else{
			 exit('No direct script access allowed');
		}
	}
	public function add_new_faq(){
		if ($this->input->is_ajax_request()){
			$icon_image='';
			if(isset($_POST['faq_id']) && !empty(trim($_POST['faq_id']))){
				$array = [	
							'subject' 		=> trim($_POST['subject']),
							'description' 	=> $_POST['description'],
							'show_status' 	=> $_POST['show_status'],
						];
				if($this->DatabaseModel->access_database('help_faq','update',$array ,array('faq_id'=>$_POST['faq_id'])) > 0){
					echo json_encode(array('status'=>1,'message'=>'You have updated FAQ successfully.'));	
				}else{
					echo json_encode(array('status'=>0,'message'=>'Oops! There’s nothing new to update here.'));	
				}
			}else{
				$array = [	
							'subject' 		=> trim($_POST['subject']),
							'description' 	=> $_POST['description'],
							'show_status' 	=> $_POST['show_status'],
							'created_at'	=> date('Y-m-d H:i:s'),
							'type'			=> 1	
													];
				if($this->DatabaseModel->access_database('help_faq','insert',$array)){
					echo json_encode(array('status'=>1,'message'=>'You have added FAQ successfully.'));	
				}else{
					echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again2.'));		
				}
			}
		}else{
			 exit('No direct script access allowed');
		}
	}

	function home_sliders(){
		$data['page_menu'] 	= 	'setting|homepage_slider|Homepage Slider|HomepageSlider';
		$cond				=	array('page_status'=>1);
		$data['web_mode'] 	= 	$this->DatabaseModel->select_data('mode_id,mode','website_mode',$cond);
		$data['article_categories'] = $this->DatabaseModel->select_data('id,cat_name','article_categories',['status' => 1],'','',array('category_order','ASC'));
		array_push($data['article_categories'], array('id'=>0, 'cat_name'=>'All'));
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/home_sliders',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}

	public function access_home_slider_videos(){ 
		$data 		= 	array();
		
		if(isset($_GET)){
			
			$leadsCount = 	0;
			$search 	= 	trim($_GET['search']['value']);
			$start 		= 	$_GET['start'];
			$gmode		=	$_GET['mode'];

			$cond 		= 	"slider_title LIKE '%".$search."%'";

			if($gmode == 'article_sidebar'){ //Temporary added for filter the sidebar articles
				$gmode = 10; 
				$cond 		.= 	" AND is_sidebar_slider = 1";
			}else{
				$cond 		.= 	" AND is_sidebar_slider = 0";
			}
			
			$order		=	array('slider_order' , 'ASC');
			
			$cond 		.= 	" AND slider_mode = $gmode";

			if($gmode == 10 && !empty($_GET['article_category'])){
				$article_cate = $_GET['article_category'];
				$cond .= " AND genre = $article_cate";
			}
			
			$result	= $this->DatabaseModel->select_data('id,type,slider_title,status,is_sidebar_slider,slider_category_slug' ,'homepage_sliders',$cond,array($_GET['length'],$start),'', $order);
			
			$leadsCount = $this->DatabaseModel->aggregate_data('homepage_sliders','id','COUNT',$cond);
			
			$totalLeadsCount = $this->DatabaseModel->aggregate_data('homepage_sliders','id','COUNT',array('slider_mode'=>$gmode));
			
			if(!empty($result)){
				//$leadsCount = count($result);
					foreach($result as $list){
						
						$check =  ($list['status'] == 1) ? 'checked="checked"' : '' ; 
						$is_sidebar_slider_check =  ($list['is_sidebar_slider'] == 1) ? 'checked="checked"' : '' ; 
						
						// $edit  = ' <a class="btn btn-app" href="add_home_slider/'.$list['id'].'"><i class="fa fa-edit"></i>Edit</a>' ;
						
						// $delete  = '<a class="btn btn-app" data-action-url="admin/deleteRowContent/homepage_sliders" data-delete-id="'.$list['id'].'"  data-field="id" ><i class="fa fa-trash"></i>Delete</a>';
						
						$not_editable_slider = ['most_popular_videos','top_videos_of_the_month','new_release_video','explore_videos_by_genres','global_top_ten','latest_articles','most_popular_articles','recommended_for_you','most_popular','global_top_ten','top_in_category','categories','top_in','top_games'];

						$not_copyable_slider = [4,6,8,9,10];

						$status =  '<a class="btn btn-app"><input '.$check.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="admin/updateCheckStatus/homepage_sliders">Status</a>';						
						
						$edit  = (!in_array($list['type'], $not_editable_slider))?' <a class="btn btn-app" href="add_home_slider/'.$list['id'].'/'.$gmode.'"><i class="fa fa-edit"></i>Edit</a>' : '';
						
						$delete  = (!in_array($list['type'], $not_editable_slider))?'<a class="btn btn-app" data-action-url="admin/deleteRowContent/homepage_sliders" data-delete-id="'.$list['id'].'"  data-field="id" ><i class="fa fa-trash"></i>Delete</a>' : '';

						$clone  = (!in_array($gmode, $not_copyable_slider) && !in_array($list['type'], $not_editable_slider))?'<a class="btn btn-app" title="Copy To Spotlight Mode" data-action-url="admin/cloneSlider" data-clone-id="'.$list['id'].'"  data-field="id" ><i class="fa fa-clone"></i>Copy</a>' : '';
						
						$is_sidebar_slider = $gmode == 10 ? '<a class="btn btn-app"><input '.$is_sidebar_slider_check.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="admin/updateCheckStatus/homepage_sidebar_sliders"><br>Is Sidebar Slider </a>' : '';
						

						if(!empty($list['slider_category_slug'])){
							$edit = '';
							$delete = '';
						}

						array_push($data , array(
												'<a data-id="'.$list['id'].'" href="javascript:;"><i class="fa fa-bars handle"></i></a>',
												$list['slider_title'],
													
												$status .
												$edit .	
												$delete.
												$clone.
												$is_sidebar_slider
												)
											); 
					}
			}
			
			echo json_encode(array(
						'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
						'recordsTotal' => $totalLeadsCount,
						'recordsFiltered' => $leadsCount,
						'data' => $data,
						));
		}
	}

	function add_home_slider($id=NULL,$mode=NULL){
		$data['page_menu'] 	= 	'setting|homepage_slider|Add New Slider|HomepageSlider';
		
		if(!empty($id)){
			$data['update'] 	= $this->DatabaseModel->select_data('*','homepage_sliders',array('id'=>$id),1);
			$user_id			= $data['update'][0]['user_id'];
			
			$user 				= $this->DatabaseModel->select_data('user_name','users use INDEX(user_id)',array('user_id'=>$user_id),1);
			$data['update'][0]['user']	= array('id'=>$user_id,'name'=>isset($user[0]['user_name'])?$user[0]['user_name']:'');
		}else{
			$data['update'][0] = ['slider_mode'=>$mode];
		}
		
		$cond				=	array('page_status'=>1);
		$data['web_mode'] 	= 	$this->DatabaseModel->select_data('mode_id,mode','website_mode',$cond);
		
		$cond				=	array('level'=>1);
		$data['category']	= 	$this->DatabaseModel->select_data('category_id,category_name','artist_category',$cond);
		
		
		$join = array('multiple' , array(
				array(	'channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id','left'),
				array(	'users','users.user_id = channel_post_video.user_id','left')
		));

		$cond 		= $this->common->channelGlobalCond();
		
		if(isset($mode) && !empty($mode) && $mode != 8 && $mode != 9 ){
			$cond 	.=' AND channel_post_video.mode='.$mode.'';
		}
		
		$data['user_list'] = $this->DatabaseModel->select_data('users.user_id As user_id,users.user_name As user_name','channel_post_video use INDEX(post_id)',$cond ,'' , $join , ['user_name','ASC'],'','channel_post_video.user_id');
		
		if($mode == 10){
			$data['genre'] = $this->DatabaseModel->select_data('id,cat_name As name','article_categories',['status' => 1],'','',array('category_order','ASC')); //It is actually category list of articles,
			array_push($data['genre'], array('id'=>0, 'name'=>'All'));
		}else{
			$data['genre'] = $this->DatabaseModel->select_data('mode_of_genre.genre_id As id,mode_of_genre.genre_name As name','mode_of_genre',['mode_id'=>$mode,'level'=>1,'status'=>1],'','',array('mode_of_genre.browse_order','ASC'));
		
		}

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/add_home_slider',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}
	
	
	public function access_article_list(){
		$_GET = $_SERVER["REQUEST_METHOD"] == 'GET' ? $_GET : $_POST ;
		
		$data = array();
		$leadsCount = 0;
		
		$search = trim($_GET['search']['value']);

		// $where  ='articles.complete_status =1 AND articles.delete_status =0 AND articles_content.content_type = "image"';
		$where  ='articles.complete_status =1 AND articles.delete_status =0 AND articles_content.order_ = 0';
	
		if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
			$where .=' AND user_level ='.$_GET['user_level'].'';
		}
		
		if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
			$where .=' AND user_id ='.$_GET['user_id'].'';
		}
		
		if(isset($_GET['genre']) && !empty($_GET['genre'])){
			$where .= ' AND articles.ar_category_id='.$_GET['genre'].'';
		}

		$order_by 	= array('articles.article_id', 'DESC');
		
		if(isset($_GET['selected_video'])  && $_GET['selected_video']=="1"){
			
			if(isset($_GET['post_ids']) && !empty($_GET['post_ids'])){
				$post_ids = $_GET['post_ids'];
				$where .=' AND articles.article_id IN('.$post_ids.')';

				$order_by ="FIELD(articles.article_id,$post_ids)";
			}
		}
		
		$searchField = array('articles.ar_title','articles.ar_date_created','articles.article_id','articles.views','articles.ar_category_id','articles_content.content','users.user_name','users.user_id','article_categories.cat_name');
		
		
		if(!empty($search)){
			$where .= ' AND (';
			for($i=0;$i < sizeof($searchField); $i++){
				if($searchField[$i] != ''){
					$where .= "$searchField[$i] LIKE '%".$search."%'";
					if(sizeof($searchField) - $i != 1){
						$where .= ' OR ';
					}	
				}
			}
			$where .= ')';
		}
		
		$field = 'articles.ar_title AS title,articles.ar_date_created AS created_at,articles.article_id AS post_id,articles.views AS count_views,articles.ar_category_id AS genre_name,articles_content.content_type AS c_type,articles_content.content AS image_name,users.user_name,users.user_id,article_categories.cat_name as genre_name';
		
		
		$join  = array(
			'multiple',
				array(
					array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
					array('article_categories' , 'article_categories.id = articles.ar_category_id','left'),
					array('users' , 'users.user_id = articles.ar_uid','left'),
				)
		);

		$main_article	= $this->DatabaseModel->select_data($field,'articles',$where,[$_GET['length'],$_GET['start']],$join,$order_by,'','articles_content.content');
		
		$data = [];
		if(!empty($main_article)){
			$web_mode = $this->valuelist->mode();
			$mode 	  = isset($web_mode[$_GET['mode']]) ? $web_mode[$_GET['mode']] : '';
			foreach($main_article as $list){
				if ($list['c_type'] == 'image') {
					$ImgUrl 		= AMAZON_URL.$list['image_name'];
				}else{
					$ImgUrl 		= base_url('repo/images/blog_pp.png');
				}
				$url 			= base_url('article/'.$list['post_id']);
				$count_votes	= 0;
				array_push($data , array(	
											'<a data-id="'.$list['post_id'].'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>',
											
											'<input type="checkbox" value="'.$list['post_id'].'" class="SelectPostIds" id="SelectPostIds'.$list['post_id'].'">',
											'<input type="checkbox" value="'.$list['post_id'].'" class="SelectOrderPostIds" id="SelectOrderPostIds'.$list['post_id'].'">',
											'<div class="dis_admin_img_div"><img src="'.$ImgUrl.'" height="157px" width="212px" onerror="this.onerror=null;this.src=\''.base_url('repo/images/thumbnail.jpg').'\'">
												<div class="overlay">
													<a href="'.$url.'" class="play_btn play_post_video"><img src="'.base_url('repo/images/play_icon.png').'">
													</a>
												</div>
											 </div>',
											 
											$list['user_name'],
											$list['title'],
											$mode,
											$list['genre_name'],
											$list['count_views'],
											$count_votes,
											'<a class="btn btn-warning LinkNpreview"  data-user_id="'.$list['user_id'].'" data-post_id="'.$list['post_id'].'">Add Link/ Change Preview</a>'
										));
		
			}
			
			$leadsCount	= $this->DatabaseModel->aggregate_data('articles' ,'articles.article_id', 'COUNT' , $where  , $join);
		}
		return json_encode(array(
			'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' => $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data,
			));
		 
	}

	public function access_slider_videos(){ 
	
		$data 					= 	array();
		$leadsCount 			= 	0;
		$accessParam['where'] 	= 	'channel_post_video.privacy_status=7';
		$search 				= 	isset($_GET['search']['value'])? trim($_GET['search']['value']): '';
		$start 					= 	isset($_GET['start'])? $_GET['start']: 0;
		
		$field					=	array(null,null,'users.user_name','channel_post_video.title','website_mode.mode','mode_of_genre.genre_name','channel_post_video.count_views','channel_post_video.count_votes','channel_post_video.post_id','channel_post_thumb.image_name','channel_post_video.uploaded_video','channel_post_video.user_id');
		
		$join = array('multiple' , array(
					array(	'channel_post_thumb',
							'channel_post_thumb.post_id = channel_post_video.post_id',
							'left'),
					array(	'users', 
							'users.user_id 				= channel_post_video.user_id', 
							'left'),
					array(	'mode_of_genre', 
							'mode_of_genre.genre_id 	= channel_post_video.genre', 
							'left'),
					array(	'website_mode', 
							'website_mode.mode_id 		= channel_post_video.mode', 
							'left'),
					));
		
		$cond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0 AND channel_post_video.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1 AND channel_post_video.is_stream_live != 1';
		
		$cond .= ' AND (';
			for($i=0;$i < sizeof($field); $i++){
				if($field[$i] != ''){
					$cond .= "$field[$i] LIKE '%".$search."%'";
					if(sizeof($field) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
		$cond .= ')';
		
		$order_by	=	array('channel_post_video.post_id','DESC');	

		if(isset($_GET['order'][0]['column'])){
			$colm 					= 	$_GET['order'][0]['column'];
			$order 					= 	$_GET['order'][0]['dir'];
			if($field[$colm] != null)
			$order_by	=	array($field[$colm],$order);
		}
		
		if(isset($_GET['mode']) && !empty($_GET['mode']) && $_GET['mode'] != 4 && $_GET['mode'] != 8  && $_GET['mode'] != 9){      //Mode 4 = social ,8 = spotlight , 9 = Live
			$cond .= ' AND channel_post_video.mode='.$_GET['mode'].'';
			if($_GET['mode'] ==  10){
				echo $this->access_article_list();die;
			}
		}
		
		if(isset($_GET['slider_type']) && $_GET['slider_type'] == 'playlist' ){
			echo $this->access_playlist();die;
		}

		if($_GET['mode'] == 9){
			// $cond .= ' AND channel_post_video.video_type = 2 ';
		}
		
		if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
			$cond .= ' AND users.user_level='.$_GET['user_level'].'';
		}
		
		if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
			$userIds = implode(',' ,$_GET['user_id']);
			$cond .= ' AND channel_post_video.user_id IN('.$userIds.')';
		}
		
		if(isset($_GET['genre']) && !empty($_GET['genre'])){
			$cond .= ' AND channel_post_video.genre='.$_GET['genre'].'';
		}
		
		if(isset($_GET['selected_video'])  && $_GET['selected_video']=="1"){
			if(isset($_GET['post_ids']) && !empty($_GET['post_ids'])){
				$post_ids = $_GET['post_ids'];
				$cond .=' AND channel_post_video.post_id IN('.$post_ids.')';

				$order_by ="FIELD(channel_post_video.post_id,$post_ids)";
			}
		}

		if(isset($_GET['selected_order_video'])  && $_GET['selected_order_video']=="1"){
			if(isset($_GET['order_post_ids']) && !empty($_GET['order_post_ids'])){
				$order_post_ids = $_GET['order_post_ids'];
				$cond .=' AND channel_post_video.post_id IN('.$order_post_ids.')';

				$order_by ="FIELD(channel_post_video.post_id,$order_post_ids)";
			}
		}
		/* if(isset($_GET['post_ids']) && !empty($_GET['post_ids'])){
			$post_ids = $_GET['post_ids'];
			$order_by ="FIELD(channel_post_video.post_id,$post_ids)";
		} */
	

		$channel_video_list = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond ,array($_GET['length'],$start) , $join , $order_by);
		
		
		if(!empty($channel_video_list)){
			
			$leadsCount =	$this->DatabaseModel->aggregate_data('channel_post_video','channel_post_video.post_id','COUNT',$cond,$join);
				$i=0;
				
				foreach($channel_video_list as $list){
					$url = AMAZON_URL . $list['uploaded_video'];
					$img = explode('.',$list['image_name']);
					$ext = isset($img[1])?$img[1]:'jpeg';
					$img = $img[0].'_thumb.'.$ext;
					
					$ImgUrl = AMAZON_URL . 'aud_'.$list['user_id'].'/images/'.$img;
					array_push($data , array(	
												'<a data-id="'.$list['post_id'].'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>',
												
												'<input type="checkbox" value="'.$list['post_id'].'" class="SelectPostIds" id="SelectPostIds'.$list['post_id'].'">',
												'<input type="checkbox" value="'.$list['post_id'].'" class="SelectOrderPostIds" id="SelectOrderPostIds'.$list['post_id'].'">',
												
												'<div class="dis_admin_img_div"><img src="'.$ImgUrl.'" height="157px" width="212px" onerror="this.onerror=null;this.src=\''.base_url('repo/images/thumbnail.jpg').'\'">
													<div class="overlay">
														<a href="'.$url.'" class="play_btn play_post_video"><img src="'.base_url('repo/images/play_icon.png').'">
														</a>
													</div>
												 </div>',
												 
												$list['user_name'],
												$list['title'],
												$list['mode'],
												$list['genre_name'],
												$list['count_views'],
												$list['count_votes'],
												'<a class="btn btn-warning LinkNpreview"  data-user_id="'.$list['user_id'].'" data-post_id="'.$list['post_id'].'">Add Link/ Change Preview</a>'
											));
					$i++;
				}
		}
		
		echo json_encode(array(
					'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
					'recordsTotal' => $leadsCount,
					'recordsFiltered' => $leadsCount,
					'data' => $data,
					));
	}
	
	public function access_playlist()
	{
		$data 					= 	array();
		$leadsCount 			= 	0;

		$search 				= 	isset($_GET['search']['value'])?$_GET['search']['value']: '';
		$start 					= 	isset($_GET['start'])? $_GET['start']: 0;
		
		$field					=	array(null,null,'users.user_name','channel_video_playlist.title','website_mode.mode','mode_of_genre.genre_name','channel_post_video.count_views','channel_post_video.count_votes','channel_video_playlist.playlist_id','channel_post_thumb.image_name','channel_post_video.uploaded_video','channel_post_video.user_id');
		
		$join = array('multiple' , array(
					array(	'channel_post_video use INDEX(post_id)',
							'channel_video_playlist.first_video_id = channel_post_video.post_id',
							'left'),
					array(	'channel_post_thumb use INDEX(post_id)',
							'channel_post_thumb.post_id = channel_post_video.post_id',
							'left'),
					array(	'users use INDEX(user_id)', 
							'users.user_id 				= channel_video_playlist.user_id', 
							'left'),
					array(	'mode_of_genre', 
							'mode_of_genre.genre_id 	= channel_post_video.genre', 
							'left'),
					array(	'website_mode', 
							'website_mode.mode_id 		= channel_video_playlist.mode', 
							'left'),
					));
		
		$cond = 'channel_post_video.active_status = 1 AND channel_post_video.complete_status = 1 AND channel_post_video.delete_status = 0  AND channel_video_playlist.privacy_status = 7 AND channel_post_thumb.active_thumb = 1 AND users.user_status = 1 AND channel_video_playlist.playlist_type = 2';

		
		$cond .= ' AND (';
			for($i=0;$i < sizeof($field); $i++){
				if($field[$i] != ''){
					$cond .= "$field[$i] LIKE '%".$search."%'";
					if(sizeof($field) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
		$cond .= ')';
		
		$order_by	=	array('channel_video_playlist.playlist_id ','DESC');	

		if(isset($_GET['order'][0]['column'])){
			$colm 					= 	$_GET['order'][0]['column'];
			$order 					= 	$_GET['order'][0]['dir'];
			if($field[$colm] != null)
			$order_by	=	array($field[$colm],$order);
		}
		
		if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
			$cond .= ' AND users.user_level='.$_GET['user_level'].'';
		}
		
		if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
			$userIds = implode(',' ,$_GET['user_id']);
			$cond .= ' AND channel_video_playlist.user_id IN('.$userIds.')';
		}
		
		if(isset($_GET['genre']) && !empty($_GET['genre'])){
			$cond .= ' AND channel_post_video.genre='.$_GET['genre'].'';
		}
		
		if(isset($_GET['selected_video'])  && $_GET['selected_video']=="1"){
			
			if(isset($_GET['post_ids']) && !empty($_GET['post_ids'])){
				$post_ids = $_GET['post_ids'];
				$cond .=' AND channel_video_playlist.playlist_id IN('.$post_ids.')';

				$order_by ="FIELD(channel_video_playlist.playlist_id,$post_ids)";
			}
		}
	
		$channel_play_list = $this->DatabaseModel->select_data($field,'channel_video_playlist use INDEX(playlist_id)',$cond ,array($_GET['length'],$start) , $join , $order_by);
		
		// echo $this->db->last_query();die;
		
		if(!empty($channel_play_list)){
			
			$leadsCount =	$this->DatabaseModel->aggregate_data('channel_video_playlist','channel_video_playlist.playlist_id','COUNT',$cond,$join);
				$i=0;
				
				foreach($channel_play_list as $list){
					$url = AMAZON_URL . $list['uploaded_video'];
					$img = explode('.',$list['image_name']);
					$ext = isset($img[1])?$img[1]:'jpeg';
					$img = $img[0].'_thumb.'.$ext;
					
					$ImgUrl = AMAZON_URL . 'aud_'.$list['user_id'].'/images/'.$img;
					array_push($data , array(	
												'<a data-id="'.$list['playlist_id'].'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>',
												
												'<input type="checkbox" value="'.$list['playlist_id'].'" class="SelectPostIds" id="SelectPostIds'.$list['playlist_id'].'">',
												'<input type="checkbox" value="'.$list['playlist_id'].'" class="SelectOrderPostIds" id="SelectOrderPostIds'.$list['playlist_id'].'">',
												
												'<div class="dis_admin_img_div"><img src="'.$ImgUrl.'" height="157px" width="212px" onerror="this.onerror=null;this.src=\''.base_url('repo/images/thumbnail.jpg').'\'">
													<div class="overlay">
														<a href="'.$url.'" class="play_btn play_post_video"><img src="'.base_url('repo/images/play_icon.png').'">
														</a>
													</div>
												 </div>',
												 
												$list['user_name'],
												$list['title'],
												$list['mode'],
												$list['genre_name'],
												$list['count_views'],
												$list['count_votes'],
												'<a class="btn btn-warning LinkNpreview"  data-user_id="'.$list['user_id'].'" data-post_id="'.$list['playlist_id'].'">Add Link/ Change Preview</a>'
											));
					$i++;
				}
		}
		
		echo json_encode(array(
					'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
					'recordsTotal' => $leadsCount,
					'recordsFiltered' => $leadsCount,
					'data' => $data,
					));
	}

	public function SaveHomePageSliders(){
	
		$checkValidation = check_api_validation($_POST , array('slider_mode|require','title|require','post_ids|require'));
			if($checkValidation['status'] == 1){
				
				$title 		= 	trim($_POST['title']);
				$slug		=	strtolower(str_replace("-", "_", str_replace(" ","_",$title) ) );
				$post_ids  	= 	ltrim(rtrim($_POST['post_ids'], ','), ',');
				$order_post_ids  	= 	ltrim(rtrim($_POST['order_post_ids'], ','), ',');
				
				if(isset($_POST['slider_mode']) && ($_POST['slider_mode'] != 8 && $_POST['slider_mode'] != 9)){             //Not equal to spotlight mode
					$_POST['mode'] = $_POST['slider_mode'];
				}
					
				if( isset($_POST['id'])  && !empty($_POST['id'])){
					$array	=	array(
								'slider_title'	=>	$title,
								'type'			=>	$slug,
								'data'			=>	$post_ids,
								'data_order'	=>	$order_post_ids,
								'mode'			=>	$_POST['mode'],
								'slider_mode'	=>  $_POST['slider_mode'],
								'user_id'		=>	isset($_POST['user_id'])?$_POST['user_id']:0,
								'category_id'	=>	$_POST['user_level'],
								'status'		=>  1,
								'genre'			=>  isset($_POST['genre'])?$_POST['genre']:'',
								'slider_type'	=>  isset($_POST['slider_type'])?$_POST['slider_type']:'single',
								'search_query'	=>  isset($_POST['search_query'])?$_POST['search_query']:'',
								'query_type'	=>  isset($_POST['query_type'])?$_POST['query_type'] : '',

							);
					$this->DatabaseModel->access_database('homepage_sliders','update',$array,array('id'=>$_POST['id']));
					
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Slider Updated Successfully.';
				}else{
					
					$array	=	array(
									'slider_title'	=>	$title,
									'type'			=>	$slug,
									'data'			=>	$post_ids,
									'data_order'	=>	$order_post_ids,
									'mode'			=>	$_POST['mode'],
									'slider_mode'	=>  $_POST['slider_mode'],
									'user_id'		=>	isset($_POST['user_id'])?$_POST['user_id']:0,
									'category_id'	=>	$_POST['user_level'],
									'status'		=>  1,
									'genre'			=>  isset($_POST['genre'])?$_POST['genre']:'',
									'slider_type'	=>  isset($_POST['slider_type'])?$_POST['slider_type']:'single',
									'search_query'	=>  isset($_POST['search_query'])?$_POST['search_query']:'',
									'query_type'	=>  isset($_POST['query_type'])?$_POST['query_type'] : '',


								);
	
					$this->DatabaseModel->access_database('homepage_sliders','insert',$array);
					
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'New Slider Added Successfully.';
					
				}
					
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}

	function RemoveCoverVideoLink(){
		$checkValidation = check_api_validation($_POST , array('mode|require','link|require','post_id|require'));
			if($checkValidation['status'] == 1){
				$links = [];
				$linkData = $this->DatabaseModel->select_data('cover_video_link','page_setting',array('website_mode' => $_POST['mode']),1);
				if(!empty($linkData)){
					$links = json_decode($linkData[0]['cover_video_link'],true);
					if(isset($links[$_POST['post_id']]))
					unset($links[$_POST['post_id']]);
				}
				
				
				$r = $this->DatabaseModel->access_database('page_setting','update',['cover_video_link' => json_encode($links)],array('website_mode'=>$_POST['mode']));
				if($r){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Link has removed successfully.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	
	function AddCoverVideoLink(){
		
			if(!empty( $_POST['btn'])){
				$checkValidation = check_api_validation($_POST , array('mode|require','link|require','btn|require','post_id|require'));
			}else{
				$checkValidation = check_api_validation($_POST , array('mode|require','post_id|require'));
			}
			
			if($checkValidation['status'] == 1){
				
				$link 		= isset($_POST['link'])? $_POST['link'] : '';
				$btn  		= isset($_POST['btn'])? $_POST['btn'] : '';
				$subtitle   = isset($_POST['subtitle'])? $_POST['subtitle'] : '';
				$links = [];
				$linkData = $this->DatabaseModel->select_data('cover_video_link','page_setting',array('website_mode' => $_POST['mode']),1);
				if(!empty($linkData)){
					$links = json_decode($linkData[0]['cover_video_link'],true);
					$links[$_POST['post_id']] = ['link'=>$link , 'btn' => $btn  , 'subtitle' => $subtitle ];
				}else{
					$links[$_POST['post_id']] =  ['link'=>$link , 'btn' => $btn  , 'subtitle' => $subtitle ];
				}
				// if(empty($_POST['link'])){
					// unset($links[$_POST['post_id']]);
				// }
				$this->DatabaseModel->access_database('page_setting','update',['cover_video_link' => json_encode($links)],array('website_mode'=>$_POST['mode']));
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Link has updated successfully.';
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	
	function getCoverVideoLink(){
		$checkValidation = check_api_validation($_POST , array('mode|require','post_id|require'));
			$resp['data'] = [];
			if($checkValidation['status'] == 1){
				$links = '';
				$linkData = $this->DatabaseModel->select_data('cover_video_link','page_setting',array('website_mode' => $_POST['mode']),1);
				if(!empty($linkData)){
					$links = json_decode($linkData[0]['cover_video_link'],true);
					
					$link =  isset($links[$_POST['post_id']])?$links[$_POST['post_id']]:[];
				}
				$resp['data'] = $link;
				
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Link has added successfully.';
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response($resp);
	}

	public function page_setting_insert(){
		
		$file_name=$this->audition_functions->upload_file('repo_admin/images/homepage','jpeg|jpg|png|gif','file',true);
		$post_ids 		= isset($_POST['post_ids'])?$_POST['post_ids'] :'' ;
		$post_ids  = 	ltrim(rtrim($post_ids, ','), ',');
		
		if(sizeof(explode(',',$post_ids)) < 12){
			
			$content_data = array(
				'cover_image_title'		=>	$_POST['cover_image_title'],
				'cover_image_subtitle'	=>	$_POST['cover_image_subtitle'],
				'cover_over_image'		=>	$_POST['cover_over_image'],
				'cover_image_status'	=>	$_POST['cover_image_status'],
				'user_id'				=>	$_POST['user_id'],
				'default_profile_video' =>	isset($_POST['default_profile_video']) ? $_POST['default_profile_video'] : '',
				'cover_video'			=>	$post_ids,
				'website_mode'			=>	$_POST['website_mode']			
			);
			
			$check=current($this->DatabaseModel->select_data('*','page_setting',array('website_mode' =>$_POST['website_mode'])));
			if($file_name != 0){
				$file_name	=	$file_name['file_name'];
				$content_data['cover_image']=$file_name;
				$file_path = ABS_PATH.'repo_admin/images/homepage/'.$check['cover_image'];
					if(file_exists($file_path .'.webp')){
						unlink($file_path);
						unlink($file_path.'.webp');
					}
				$this->load->library('convert_image_webp');
				if(file_exists('repo_admin/images/homepage/'.$file_name))
				$this->convert_image_webp->convertIntoWebp('repo_admin/images/homepage/'.$file_name);
			}
			if(!empty($_POST['id'])){
				$this->DatabaseModel->access_database('page_setting','update',$content_data,array('id'=>$_POST['id']));
				$this->updateGlobalTopTenSlider($post_ids, $_POST['website_mode']); // Update Top Ten Slider
				$this->respMessage = 'Details updated successfully.';
			}else{
				if(!empty($check)){
					$this->DatabaseModel->access_database('page_setting','update',$content_data,array('website_mode'=>$_POST['website_mode']));
					$this->updateGlobalTopTenSlider($post_ids, $_POST['website_mode']); // Update Top Ten Slider
					$this->respMessage = 'Details updated successfully.';
				}else{
					$this->DatabaseModel->access_database('page_setting','insert',$content_data,'');
					$this->updateGlobalTopTenSlider($post_ids, $_POST['website_mode']); // Update Top Ten Slider
					$this->respMessage = 'Details Added successfully.';
				}
			}
			// $this->db->cache_delete_all();
			$this->removeChacheFile();
			
			
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->show_my_response();
		}else{
			$this->respMessage = 'You can\'t add more than 11 videos.';
			$this->show_my_response();
		}
	}
	function removeChacheFile(){
		
		$dirname = ABS_PATH.'application/cache/channel+index';
		if(is_dir($dirname)) {
			
			array_map('unlink', glob("$dirname/*"));
			rmdir($dirname);
		}
		$dirname = ABS_PATH.'application/cache/profile+index';
		if(is_dir($dirname)) {
			
			array_map('unlink', glob("$dirname/*"));
			rmdir($dirname);
		}
	}
	function updateGlobalTopTenSlider($post_ids, $web_mode){
		if(in_array($web_mode, [1,2,3,7,8])){
			$this->DatabaseModel->access_database('homepage_sliders','update',array('data'=>$post_ids),array('type'=>'global_top_ten', 'mode'=>$web_mode));
		}
	}
	
	function create_vid_preview(){
		if(isset($_POST['startTime']) ){
			// $this->load->helper('aws_s3-action');
			$url = explode(AMAZON_URL ,$_POST['url']);
			$Surl = explode(AMAZON_TRANCODE_URL ,$_POST['url']);
			
			if(isset($url[1])){
				
				$key 	= explode('.',$url[1]);
				$file 	= explode('videos',$key[0])[1];
				$oldkey = $key[0].$file.'.'.$key[1];
				
				$oldkey = str_replace(".MOV",".mp4",$oldkey);
				
				$oldkey = str_replace(".mov",".mp4",$oldkey);
				
				if(DoesObjectExist($oldkey,$bucket = TRAN_BUCKET )){
					$invali = createCasheInvalidatin(['/'.$oldkey]);
					$dele = s3_delete_object( array(trim($oldkey)),TRAN_BUCKET);
				}
				$res = ElasticTranscoder($url[1],$_POST['startTime']);
				// print_r($res);die;
				if($res['status'] == 1){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage 	= 'Transcoder Job Created.' ;
				}else{
					$this->respMessage 	= $res['error'] ;
				}
			}else if(isset($Surl[1])){
				$tt 		= $_POST['startTime'];
				$segment 	= $_POST['TotalTime'] / 7;
				
				$ts 		= 0;
				for($i= 0; $i < floor($segment); $i++){
					$starP 	= $i * 7;
					$endP 	= $i+1;
					$endP 	= $endP*7;
					
					if($tt > $starP   && $tt < $endP    ){
						
						$t1 = $tt - $starP ;
						$t2 = $endP  - $tt;
						
						if($t1 > $t2){
							$ts = $i+1;
						}else{
							$ts = $i ;
						}
						break;
					}
				} 
				
				$key 	= str_replace(basename($_POST['url']),"",$_POST['url']);
				
				$srcKey = explode(AMAZON_TRANCODE_URL,$key)[1] ; 
				// print_r($srcKey);die; 
				$this->load->helper('aws_s3_action');
				$index = 'index_1_'.$ts.'.ts';
				$r = ElasticTranscoderLivePreview($srcKey,$index);
				if($r['status'] == 1){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage 	= 'Live preview Job Created.' ;
				}else{
					$this->respMessage 	= $r['error'] ;
				}
			}
			else{
				$this->respMessage 	= 'File is not supported.' ;
			}
			$this->show_my_response();
		}else{
			$this->respMessage 	= 'Something went wrong. Please try again.' ;
		}
		$this->show_my_response();
	}
  
	public function flags_group(){      
		$data['page_menu']  = 'flags|flags_group|Flags Report|flags_group'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/flags/flags_group');
		$this->load->view('common/notofication_popup'); 
		$this->load->view('admin/include/footer');
	}

	public function flags_report($related_with=NULL,$related_id=NULL){ 
		$data['page_menu']  = 'flags|flags_report|Flags Report|flags_report'; 
		$parent_id = 0;
		$cond ="parent_id = {$parent_id} AND status = 1";
					
		if($parent_id == 0){
			
			$cond .=" AND (SELECT COUNT(viol_id) AS viol_id from violations_category c2 where c2.parent_id = c1.viol_id) > 0";
		}

		$data['viol_cate'] = $this->DatabaseModel->select_data('viol_id,violations_title,parent_id','violations_category c1',$cond);
		
		$data['related_with']  = $related_with; 
		$data['related_id']  = $related_id; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/flags/flags_report');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}
	
	
	public function getViolSubCategory(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$parent_id = $_POST['id'];
			$cond ="parent_id = {$parent_id} AND status = 1";
			if($parent_id == 0){
				
				$cond .=" AND (SELECT COUNT(viol_id) AS viol_id from violations_category c2 where c2.parent_id = c1.viol_id) > 0";
			}

			$result = $this->DatabaseModel->select_data('viol_id as id,violations_title as name','violations_category c1',$cond);
			if(isset($result[0])){
				echo json_encode(array('status'=>1,'data'=>$result));	
			}else{
				echo json_encode(array('status'=>0,'message'=>'data not available.'));
			} 	
		}
		else{
			echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again.'));	
		}
	}
	

	public function show_flags_group_report(){
		if(isset($_GET['length'])){
			$data 	= array();
			$search = trim($_GET['search']['value']);
			// $related_with 	= isset($_GET['related_with'])?$_GET['related_with']:2;
			
			$colm 	= 1;
			$order 	= 'DESC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm 	= $_GET['order'][0]['column'];
				$order 	= $_GET['order'][0]['dir'];								
			}
			
			$cond 	= "violations_history.status = 0"; 
			
			if(isset($_GET['action_status']) && !empty($_GET['action_status'])){
				$cond = 'violations_history.status='.$_GET['action_status'].'';
			}

			$start 	= $_GET['start'];
			
			$filed 		= [NULL,'related_with','related_id','COUNT(viol_his_id) AS totalReport','violations_history.action','pub_content','user_name','user_uname','title','post_key','com_text'];
			$searchfield= [NULL,'related_with','related_id','','violations_history.action'];
			$orderfield = ['viol_his_id','related_with','related_id','totalReport','violations_history.action'];
			
			$join 	= 	array('multiple' , array(
								array(	'users', 
										'users.user_id 	= violations_history.related_id',
										'left'),
								array(	'publish_data', 
										'publish_data.pub_id 	= violations_history.related_id',
										'left'),
								array(	'channel_post_video', 
										'channel_post_video.post_id 	= violations_history.related_id',
										'left'),
								array(	'comments', 
										'comments.com_id 	= violations_history.related_id',
										'left')
							)
						);
		
			if(!empty($search)){
				$cond .= ' AND (';
				for($i=0;$i < sizeof($searchfield); $i++){
					if($searchfield[$i] != ''){
						$cond .= "$searchfield[$i] LIKE '%".$search."%'";
						if(sizeof($searchfield) - $i != 1){
							$cond .= ' OR ';
						}
					}
				}
				$cond .= ')';
			}

			if(isset($_GET['related_with']) && !empty($_GET['related_with'])){
				$cond .= ' AND related_with='.$_GET['related_with'].'';
			}

			

			if(isset($_GET['date_range'])  && !empty($_GET['date_range'])){
				$rangeArray = explode('-',$_GET['date_range']);
				$date1 		= "'".date('Y-m-d' , strtotime($rangeArray[0]))."'";
				$date2 		= "'".date('Y-m-d' , strtotime($rangeArray[1]))."'";
				
				$cond .=" AND DATE(violations_history.created_at) >= $date1 AND DATE(violations_history.created_at) <= $date2 ";
			}

			$queryData = $this->DatabaseModel->select_data($filed,'violations_history', $cond ,array($_GET['length'],$start) , $join , array($orderfield[$colm] , $order),'',array("related_id", "related_with"));
			// print_r($queryData);die;
			$leadsCount =	$this->DatabaseModel->aggregate_data('violations_history','viol_his_id','COUNT',$cond,$join,'','');
			
			$this->load->library('share_url_encryption');
			
			$last_related_with = [];
			$last_related_id = [];
			
			foreach($queryData as $list){
				$start++;
				$related_with 	= $list['related_with'];
				$related_with 	= $related_with == 1 ? 'User' :(  $related_with == 2 ? 'Social' : (  $related_with == 3 ?  'Channel' : 'Comment'  ))  ;
				$related_title 	= $related_with == 'User' ?  $list['user_name'] :  (  $related_with == 'Social' ?   $list['pub_content'] : ( $related_with == 'Channel' ? $list['title'] :  $list['com_text'] ) ) ;
				
				$socialLink = '';
				if($related_with == 'Social'){
					$socialLink = $this->share_url_encryption->share_single_page_link_creator('1|'.$list['related_id'] , 'encode');
				}

				$related_link	= $related_with == 	'User' ? base_url('profile?user='.$list['user_uname']) : (  $related_with == 'Social' ?  $socialLink :  ($related_with == 'Channel' ? base_url('watch/'.$list['post_key'] )  : '') );
				
				$last_related_with[] 	= $list['related_with']; 
				$last_related_id[] 		= $list['related_id'];

				$deleteCom =  $related_with == 'Comment' ?'<a href="javascript:;" data-action-url="admin_setting/deleteComment" data-delete-id="'.$list['related_id'].'" data-field="" ><i class="fa fa-fw fa-trash"></i></a>':'';

				array_push($data , array(
					$start,
					$related_with,
					'<a target="_blank" href="'. $related_link .'">'.$related_title . '</a>',
					$list['totalReport'],
					'NA',
					'<a href="'.base_url('admin_setting/flags_report/'.$list['related_with']. '/'. $list['related_id'] ).'">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="11px">
					<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M17.883,5.836 C17.722,6.046 13.892,11.000 9.000,11.000 C4.108,11.000 0.277,6.046 0.117,5.836 C-0.035,5.635 -0.035,5.364 0.117,5.164 C0.277,4.953 4.108,-0.000 9.000,-0.000 C13.892,-0.000 17.722,4.953 17.883,5.164 C18.035,5.364 18.035,5.636 17.883,5.836 ZM9.000,1.138 C5.389,1.138 2.274,4.424 1.352,5.500 C2.276,6.574 5.396,9.862 9.000,9.862 C12.611,9.862 15.725,6.576 16.648,5.500 C15.724,4.425 12.603,1.138 9.000,1.138 ZM9.000,8.914 C7.037,8.914 5.440,7.382 5.440,5.500 C5.440,3.618 7.037,2.086 9.000,2.086 C10.962,2.086 12.559,3.618 12.559,5.500 C12.559,7.382 10.962,8.914 9.000,8.914 ZM9.000,3.224 C7.691,3.224 6.627,4.245 6.627,5.500 C6.627,6.755 7.691,7.776 9.000,7.776 C10.308,7.776 11.373,6.755 11.373,5.500 C11.373,4.245 10.308,3.224 9.000,3.224 Z"></path>
					</svg></a>',
					'<a href="javascript:;" class="OpenFlagModal" data-related_with="'.$list['related_with'].'" data-related_id="'.$list['related_id'].'" ><i class="fa fa-fw fa-edit"></i></a>'.$deleteCom .'',
					$list['related_with'], // just to get the action from the below step
					$list['related_id']  // just to get the action from the below step
				));
			}
			
			$rel_with 	= 	implode(',',$last_related_with); 
			$rel_id 	= 	implode(',',$last_related_id); 
			
			if($rel_id){
				$cond 		= 	"related_with IN (". $rel_with .") AND related_id IN (". $rel_id .") AND status  = 1";

				if(isset($_GET['action_status']) && !empty($_GET['action_status'])){
					$cond .= " AND violations_history.action !=''";
				}else{
					$cond .= " AND violations_history.action =''";
				}

				$join 		= 	array('multiple' , array(
									array(	'violations_action', 
											'violations_action.violact_id 	= violations_history.action',
											'left'),
								)
							);
				$restultData = $this->DatabaseModel->select_data('violations_action.action,related_with,related_id','violations_history', $cond ,'', $join , array('viol_his_id','DESC'),'',array("related_id", "related_with",'action'));
				
				foreach($data as $key => $value){
					foreach($restultData as $result){
						if($result['related_with'] == $value[7] && $result['related_id'] == $value[8]){
							$data[$key][4] = $result['action'];
						}
					}
				}
			}
			
			
			echo json_encode(array( 
				'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
				'recordsTotal' => $leadsCount,
				'recordsFiltered' => $leadsCount,
				'data' => $data,
				));
			
			}
	}
	
	function deleteComment(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			
			$cid = $_POST['id'];
			
			$comments = $this->DatabaseModel->select_data('*','comments',array('com_parentid'=>$cid));
			$this->DatabaseModel->access_database('comments','delete','', array('com_id'=>$cid));
			
			$this->DatabaseModel->access_database('notifications','delete','', array('reference_id'=>$cid,'noti_type'=>2));
			
			if(isset($comments[0])){
				$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$cid));
				foreach($comments as $comm){
					$subComments = $this->DatabaseModel->select_data('*','comments',array('com_parentid'=>$comm['com_id']));	
					foreach($subComments as $subComm){
						$this->DatabaseModel->access_database('notifications','delete','', array('reference_id'=>$subComm['com_id'],'noti_type'=>2));
					}
					$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$comm['com_id']));
				}
			}
			$this->DatabaseModel->access_database('violations_history','delete','', array('related_with'=>4,'related_id'=>$cid));
			$this->respMessage 	= 'Comment deleted successfully.';
			$this->statusCode 	= 1;
			$this->statusType 	= 'Success';	
			
			$this->show_my_response();
			
		}
	}

	public function show_flags_report(){
		if(isset($_GET['length'])){
			$data 	= array();
			$search = trim($_GET['search']['value']);
			$related_with 	= isset($_GET['related_with'])?$_GET['related_with']:'';
			$related_id 	= isset($_GET['related_id'])?$_GET['related_id']:'';
			
			$colm 	= 1;
			$order 	= 'DESC';
			
			if(isset($_GET['order'][0]['column'])){
				$colm 	= $_GET['order'][0]['column'];
				$order 	= $_GET['order'][0]['dir'];								
			}
			
			$cond 	= "related_with = {$related_with} AND related_id = {$related_id}";
			
			$start 	= $_GET['start'];
			
			$filed 	= [NULL,'related_with','related_id','avcategory.violations_title AS av_cate_name','bvcategory.violations_title AS bv_cate_name','viol_msg','violations_history.status','violations_history.created_at','violations_action.action','pub_content','reportofusers.user_name AS reportofusername','reportofusers.user_uname AS reportofuseruname','title','post_key','reportfromusers.user_name AS reportfromusername','reportfromusers.user_uname AS reportofuseruname'];
			$searchfield = [NULL,'related_with','related_id','avcategory.violations_title','bvcategory.violations_title','viol_msg','violations_history.status','violations_history.created_at','violations_action.action'];
			$orderfield = ['viol_his_id','related_with','related_id','av_cate_name','bv_cate_name','viol_msg','violations_history.status','violations_history.created_at','violations_action.action'];
			
			$join 	= 	array('multiple' , array(
								array(	'violations_category  AS avcategory', 
										'avcategory.viol_id	= violations_history.viol_cate',
										'left'),
								array(	'violations_category AS bvcategory', 
										'bvcategory.viol_id 	= violations_history.viol_subcate',
										'left'),
								array(	'users AS reportofusers', 
										'reportofusers.user_id 	= violations_history.related_id',
										'left'),
								array(	'users AS reportfromusers', 
										'reportfromusers.user_id 	= violations_history.related_user_id',
										'left'),
								array(	'publish_data', 
										'publish_data.pub_id 	= violations_history.related_id',
										'left'),
								array(	'channel_post_video', 
										'channel_post_video.post_id 	= violations_history.related_id',
										'left'),
								array(	'violations_action', 
										'violations_action.violact_id 	= violations_history.action',
										'left'),
							)
						);
			if(!empty($search)){			
				$cond .= ' AND (';
				for($i=0;$i < sizeof($searchfield); $i++){
					if($searchfield[$i] != ''){
						$cond .= "$searchfield[$i] LIKE '%".$search."%'";
						if(sizeof($searchfield) - $i != 1){
							$cond .= ' OR ';
						}
					}
				}
			
				$cond .= ')';
			}

			if(isset($_GET['viol_cate']) && !empty($_GET['viol_cate'])){
				$cond .= ' AND violations_history.viol_cate='.$_GET['viol_cate'].'';
			}

			if(isset($_GET['viol_subcate']) && !empty($_GET['viol_subcate'])){
				$cond .= ' AND violations_history.viol_subcate='.$_GET['viol_subcate'].'';
			}

			$queryData = $this->DatabaseModel->select_data($filed,'violations_history', $cond ,array($_GET['length'],$start) , $join , array($orderfield[$colm] , $order));
			$leadsCount =	$this->DatabaseModel->aggregate_data('violations_history','viol_his_id','COUNT',$cond,$join);
			
			$this->load->library('share_url_encryption');
			foreach($queryData as $list){
					$start++;
					
					$status 		= ($list['status'] == 0)? 'Pending' : 'Done';
					$related_with 	= $list['related_with'];
					$related_with 	= $related_with == 1 ? 'User' :(  $related_with == 2 ? 'Social' : 'Channel' );
					$related_title 	= $related_with == 'User' ?  $list['reportofusername'] :  (  $related_with == 'Social' ?   $list['pub_content'] : $list['title'] ) ;
					
					$socialLink = '';
					if($related_with == 'Social'){
						$socialLink = $this->share_url_encryption->share_single_page_link_creator('1|'.$list['related_id'] , 'encode');
					}

					// $related_link	= $related_with == 	'User' ? base_url('profile?user='.$list['reportofuseruname']) : (  $related_with == 'Social' ?  $socialLink :  base_url('watch/'.$list['post_key'] ) );
					
					array_push($data , array(
						$start,
						$list['av_cate_name'],
						$list['bv_cate_name'],
						$list['viol_msg'],
						$status,
						$list['created_at'],
						$list['reportfromusername'],
						$list['action'],
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

	function add_flag_action(){
		if ($this->input->is_ajax_request()){
			$checkValidation = check_api_validation($_POST , array('action|require'));
			if($checkValidation['status'] == 1){
			
				$array = [
					'action' 		=>  $_POST['action'],
					'created_at' 	=>  date('Y-m-d H:i:s'),
				];

				$where = [
					'related_with' 		=>  $_POST['related_with'],
					'related_id' 	=>  $_POST['related_id'],
					'status' 		=>  0,
				];
				
				if($id = $this->DatabaseModel->access_database('violations_action','insert',$array)){
					
					$update = $this->DatabaseModel->access_database('violations_history','update',['action' => $id,'status' =>1 ] ,$where);
					
					$this->respMessage 	= 'Your action is submitted successfully.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';		
				}else{
					$this->respMessage 	= 'Something went wrong,please try again.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			
			$this->show_my_response();
		}else{
			 exit('No direct script access allowed');
		}
	}


	public function getActiveUserList(){
		if(isset($_POST['search']) && !empty($_POST['search'])){
			$search = $_POST['search'];
			$field	=	array('users.user_id As id','users.user_name As name');
		
			$join = array('multiple' , array(
						array(	'users_content', 
								'users.user_id 	= users_content.uc_userid', 
								'left')
						)
					);
			$cond  = "is_deleted = 0 AND user_status = 1 AND user_role = 'member' AND ";
			$cond .= "user_name LIKE '%".$search."%' OR user_uname LIKE '%".$search."%'";

			$data['list']= $this->DatabaseModel->select_data($field,'users',$cond,'',$join,array('user_name','ASC'));
			echo json_encode(array('status'=>1,'data'=>$data));	 	
		}
	} 
	public function getActiveVideoList(){
		if(isset($_POST['search']) && !empty($_POST['search'])){
			$search = $_POST['search'];
			$field	=	array('channel_post_video.post_id As id','channel_post_video.title As name');
						
			$cond = $this->common->channelGlobalCond(); 
			$cond .= " AND title LIKE '%".$search."%'";
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			
			$join  = array(
						'multiple',
						array(
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);
			
			$data['list'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,'',$join,array('title','ASC'));
			echo json_encode(array('status'=>1,'data'=>$data));	 		
		}
	} 




}
