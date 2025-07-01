<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	
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
	
	public function userlist(){
		$data['page_menu'] = 'main_userlist|sub_userlist|User List|userlist'; 
		
		$data['artist_category_list'] = $this->DatabaseModel->access_database('artist_category','select','',array('level'=>1)); 
        $data['language_list'] = $this->DatabaseModel->access_database(' language_list','select','',array('status'=>1)); 
		$data['website_mode'] = $this->DatabaseModel->access_database('website_mode','select','','');
		$data['language_list'] = $this->DatabaseModel->access_database('language_list','select','','');
		$data['country_list'] = $this->DatabaseModel->access_database('country','select','','');
		$data['state_list'] = $this->DatabaseModel->access_database('state','select','','');
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/user/userlist');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}

	public function official_user_list(){
		$data['page_menu'] = 'main_userlist|official_user_list|Official Profile|userlist'; 
		$data['artist_category_list'] = $this->DatabaseModel->access_database('artist_category','select','',array('category_id'=>130));  /***means official category***/
        $data['language_list'] = $this->DatabaseModel->access_database(' language_list','select','',array('status'=>1)); 
		$data['website_mode'] = $this->DatabaseModel->access_database('website_mode','select','','');
		$data['language_list'] = $this->DatabaseModel->access_database('language_list','select','','');
		$data['country_list'] = $this->DatabaseModel->access_database('country','select','','');
		$data['state_list'] = $this->DatabaseModel->access_database('state','select','','');
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/user/official_user_list');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}

	

	public function access_userlist($official_status = null){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	trim($_GET['search']['value']);
		
		$field		= 	['users.user_uname','users.user_name','users.user_email','users.user_phone','sigup_acc_type','users_content.total_channel_video','users.user_regdate','users.date_of_delete_or_reactivate','users.user_status','users_content.is_iva','users_content.is_ele','users_content.is_fc_member','users_content.uc_type','users_content.uc_gender','users.user_id','register_by'];
		
		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 8	 ; 
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		$order_by   = 	array($field[$colm],$order);
		$cond = 'users.user_role = "member" AND official_status = '.$official_status ;
		$deleteType = 1; // soft delete
		$delBtnTitle = 'Soft Delete';
 		if( isset($_GET['user_status']) && $_GET['user_status'] == 6){       //deleted
			$cond .= ' AND users.is_deleted = 1 ';
			$order_by = array('users.date_of_delete_or_reactivate','DESC');
			$deleteType = 2; // permanent delete
			$delBtnTitle = 'Permanent Delete';
		}else
		if( isset($_GET['user_status']) && $_GET['user_status'] == 5){		//incompleted
			$cond .= ' AND users.is_deleted = 0 AND user_status = 1 AND (user_uname IS NULL OR user_uname = "") ';         
		}
		else if(isset($_GET['user_status']) && $_GET['user_status'] == 2){
			$cond .= ' AND users.is_deleted = 0 AND user_status = '.$_GET['user_status'] . '';
		}
		else if(isset($_GET['user_status'])){
			$cond .= ' AND users.is_deleted = 0 AND user_status = '.$_GET['user_status'] . ' AND user_uname != "" ';
		}
		
		if( isset($_GET['user_type']) && !empty($_GET['user_type']))
		$cond .= ' AND user_level = '.$_GET['user_type'];
		
		if( isset($_GET['user_acc_type']) && !empty($_GET['user_acc_type']))
		$cond .= ' AND sigup_acc_type = "'.$_GET['user_acc_type'].'"';
		
		if( isset($_GET['is_giveaways']) && $_GET['is_giveaways'] == 1)
		$cond .= ' AND is_giveaways = 1';
		
		if(!empty($search))
		$cond .= " AND (users.user_uname LIKE '%".$search."%' OR  users.user_name LIKE '%".$search."%' OR users.user_email LIKE '%".$search."%')";
		
		if(isset($_GET['date_range'])  && !empty($_GET['date_range'])){
			$rangeArray = explode('-',$_GET['date_range']);
			$date1 		= "'".date('Y-m-d' , strtotime($rangeArray[0]))."'";
			$date2 		= "'".date('Y-m-d' , strtotime($rangeArray[1]))."'";
			
			$cond .=" AND users.user_regdate >= $date1 AND users.user_regdate <= $date2 ";
		}
		
		$join = array('multiple' , array(
				array(	'users_content', 
						'users.user_id 	= users_content.uc_userid','left')
				)
		);
		
		$userData 	= 	$this->DatabaseModel->select_data($field,'users use INDEX(user_id)', $cond ,array($_GET['length'],$start),$join,$order_by);

		$this->session->set_userdata('export_user_details',$this->db->last_query());
		
		$leadsCount =	$this->DatabaseModel->aggregate_data('users use INDEX(user_id)','user_id','COUNT',$cond,$join);
		
		if(!empty($userData)){
			$start++;
			foreach($userData as $list){
				$ustatus 	 = $list['user_status'];
				$user_status = ($ustatus == 1) ? 'ACTIVE' :  (  ($ustatus == 2)? 'INACTIVE' :  ( ($ustatus == 3)? 'BLOCKED' : 'INACTIVE ICON' ) );
				
				$check 		 = ($list['user_status'] == 1)? 'checked' : '';
				$ivaCheck 	 = ($list['is_iva'] == 1)? 'checked' : '';
				$eleCheck 	 = ($list['is_ele'] == 1)? 'checked' : '';
				$is_fc_member= ($list['is_fc_member'] == 1)? 'checked' : '';
				
				$gender = $list['uc_gender'];
				$gender = ($gender == 1) ? 'MALE' :  (  ($gender == 2)? 'FEMALE' :  ( ($ustatus == 3)? 'OTHER' : 'DONT WANT TO ANSWER' ) ) ;
				
				$name = !empty(trim($list['user_name']))? wordwrap($list['user_name'],20,"<br>\n") : $list['user_uname'] ;
				array_push($data , array(
					$start++,
					'<a target="_blank" href="'.base_url('profile?user='.$list['user_uname']).'">'. $name .'</a>', 
					$list['user_email'],										
					$list['user_phone'],										
					ucfirst($list['sigup_acc_type']),										
					$list['total_channel_video'],										
					date('d-F-y',strtotime($list['user_regdate'])),										
					($list['date_of_delete_or_reactivate']>0)?date('d-F-y',strtotime($list['date_of_delete_or_reactivate'])) : '--',										
					'<a data-toggle="modal" data-user-id="'.$list['user_id'].'" data-user-url="admin/getUserData" ><i class="fa fa-fw fa-edit"></i></a>',		
					'<input '.$ivaCheck.' type="checkbox" data-check-id="'.$list['user_id'].'" data-action-url="admin/updateCheckStatus/users_content">',
					'<input '.$eleCheck.' type="checkbox" data-check-id="'.$list['user_id'].'" data-action-url="admin/updateCheckStatus/users_content_ele">',					
					'<p type="button" class="dis_check_status">
					'. $user_status . ' <span class=""><input '.$check.' type="checkbox" data-check-id="'.$list['user_id'].'" data-action-url="admin/updateCheckStatus/users"></span>
					</p>',
					'<input '.$is_fc_member.' type="checkbox" data-check-id="'.$list['user_id'].'" data-action-url="admin/updateCheckStatus/users_content_fc">',
					'<!--a title="Soft Delete"><i class="fa fa-fw fa-trash"></i></a-->
					 <a title="'.$delBtnTitle.'" href="#" class="deleteUser" id="'.$list['user_id'].'" data-delete-type="'.$deleteType.'" data-action-url="admin/DeleteUserAllDetails"><i class="fa fa-fw fa-trash"></i></a>',	
					'<a class="LoginMe" data-uid="'.$list['user_id'].'"><i class="fa fa-sign-in" aria-hidden="true"></i></a>',
					$list['register_by']
				));						
			}
		}
		
		echo json_encode(array(
			'draw' 			=> (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' 	=> $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data,
		));
	}

	public function userSourceList(){
		$data['page_menu'] = 'main_userlist|sub_sourceuserlist|User Source List|userlist'; 
		
		$data['artist_category_list'] = $this->DatabaseModel->access_database('artist_category','select','',array('level'=>1)); 
        $data['language_list'] = $this->DatabaseModel->access_database(' language_list','select','',array('status'=>1)); 
		$data['website_mode'] = $this->DatabaseModel->access_database('website_mode','select','','');
		$data['language_list'] = $this->DatabaseModel->access_database('language_list','select','','');
		$data['country_list'] = $this->DatabaseModel->access_database('country','select','','');
		$data['state_list'] = $this->DatabaseModel->access_database('state','select','','');
		
		$data['soureList'] = $this->valuelist->SingupSourelist();
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/user/usersourcelist');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}

	public function access_userSourcelist(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];
		
		$field		= 	['users.user_email','users.user_uname','users.user_name','users.user_regdate','users.HDYDU','users.user_id'];
		
		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 8	 ; 
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		 
		$cond = 'users.user_role = "member" && HDYDU != "" ' ;
		
		if(isset($_GET['source_status']) && $_GET['source_status'] != ''){
			$cond .= ' AND HDYDU  LIKE "%'. $_GET['source_status'] .'%" ';
		}
		$cond .= ' AND (';
			for($i=0;$i < sizeof($field); $i++){
				if($field[$i] != ''){
					$cond .= "$field[$i] LIKE '%".$search."%'";
					if(sizeof($field) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
		$cond  = rtrim($cond , 'OR ');
		$cond .= ')';
		
		$userData 	= 	$this->DatabaseModel->select_data($field,'users', $cond ,array($_GET['length'],$start) ,'', array($field[$colm],$order) );
		$leadsCount =	$this->DatabaseModel->aggregate_data('users','user_id','COUNT',$cond);
		
		if(!empty($userData)){
			$start++;
			$arrSource = $this->valuelist->SingupSourelist();
		
			foreach($userData as $list){

				$d = json_decode($list['HDYDU'],true);
				
				$l = $arrSource[$d['mySource']];
				$c = ' | '. $d['myCustSource'];
				if($l != 'other' ){
					$c = '';
				}
				array_push($data , array(
					$start++,
					'<a target="_blank" href="'.base_url('profile?user='.$list['user_uname']).'">'.ucfirst(wordwrap($list['user_name'],20,"<br>\n")) .'</a>',
					$list['user_email'],					
					date('d-F-y',strtotime($list['user_regdate'])),
					$l .  $c
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
	
	public function export_user_details(){
		
		$data = $this->session->userdata('export_user_details');
		
		if(!empty($data)){
			$data = str_replace(", `users_content`.`is_iva`, `users_content`.`is_ele`, `users_content`.`is_fc_member`, `users_content`.`uc_type`, `users_content`.`uc_gender`, `users`.`user_id`","",$data);
			$data = explode('LIMIT',$data);
			
		
			$query = $this->db->query($data[0]);

			$this->load->dbutil();
			$this->load->helper('file');
			$this->load->helper('download');
			$delimiter = ",";
			$newline = "\r\n";
			$filename = "users.csv";
			
			$data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
			force_download($filename, $data);

		}			
		exit;
	}
	
	public function category_list(){
		$data['page_menu'] = 'main_category_listt|category_list|Category List|categorylist'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/category/categorylist');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}
	public function access_categorylist(){
		
		$data = array();
		$leadsCount = 0;
		$accessParam['where'] = '';
		$search = $_GET['search']['value'];
		
		$accessParam = array(
							'limit' => ''.$_GET['length'].','.$_GET['start'].'',
							'where' => 'keyword='.$search.'',
							'order' => 'category_order,ASC'
							);
		
		if(isset($_GET['parent_id']) && !empty($_GET['parent_id'])){
			$accessParam['where'] .= ',parent_id='.$_GET['parent_id'];
			
		}

		if(isset($_GET['cate_status']) && $_GET['cate_status'] !=''){
			$accessParam['where'] .= ',status='.$_GET['cate_status'];
		}
		
		$categoryData	= $this->query_builder->category_list($accessParam);
		
		unset($accessParam['limit']);
		
		if(!empty($categoryData['category'])){
			$leadsCount	= $this->query_builder->category_list($accessParam);
			$leadsCount = count($leadsCount['category']);
				$i=0;
				foreach($categoryData['category'] as $list){
					$check = '';
					if($list['status'] == 1){
						$check = 'checked';
					}
					$get_discovered_check ='';
					if($list['get_discovered_status'] == 1){
						$get_discovered_check = 'checked';
					}

					$Subcat = $this->query_builder->category_list(array('where' => 'category_id='.$list['parent_id'] , 'field'=>'category_name'));
					$sub="";
					if(isset($Subcat['category'][0]['category_name'])){
						$sub = $Subcat['category'][0]['category_name'];
					}
					
					array_push($data , array(
											$list['category_name'],
											($list['parent_id'] != null)? $sub :'----Main----',
											($list['status'] == 1)?'Active':'Inactive',										
											($list['category_id'] != 1)?'<a data-toggle="modal" data-target="#user_form"  data-parent-id="'.$list['parent_id'].'" data-modal-id="'.$list['category_id'].'" data-cat-name="'.$list['category_name'].'" data-cat-level="'.$list['level'].'" data-modal-url="admin/getCatData"  data-action-url="admin/get_categorylist" data-target-section="#categorylist"><i class="fa fa-fw fa-edit"></i></a>':'',		
											'<input '.$check.' type="checkbox" data-check-id="'.$list['category_id'].'" data-action-url="admin/updateCheckStatus/artist_category">',
											'<input '.$get_discovered_check.' type="checkbox" data-check-id="'.$list['category_id'].'" data-action-url="admin/updateCheckStatus/get_discovered_category">',
											'<a data-id="'.$list['category_id'].'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>'
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
	
	public function addCategoryData(){
		
		$checkValidation = check_api_validation($_POST , array('category_name|require'));
			if($checkValidation['status'] == 1){
				$category_name = strtolower(str_replace(" ","-",$_POST['category_name']));
				$_POST['category_slug'] = slugify($category_name);
				if(!empty(trim($_POST['category_id']))){
					$category_id= $_POST['category_id'];
					unset($_POST['category_id']);
					 $this->DatabaseModel->access_database('artist_category','update',$_POST,array('category_id'=>$category_id));
					 $this->statusCode = 1;
					 $this->statusType = 'Success';
					 $this->respMessage = 'Category Updated Successfully.';
				}else{
					if(isset($_POST['parent_id'])){
						$_POST['level'] = 2;
					}
					
					if( $userId = $this->DatabaseModel->access_database('artist_category','insert',$_POST,'')){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Category Added Successfully.';
					}else{
						$this->respMessage = 'Something went wrong';
					}
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	public function get_categorylist(){
		$accessParam = array(
							'where' => 'status=1,level=1',
							);
				
			// echo $accessParam['where'];die;
		$categoryData	= $this->query_builder->category_list($accessParam);
		
		$option = "<option value=''>Select</option>";
		if(!empty($categoryData['category'])){
			foreach($categoryData['category'] as $list){
				$option .= "<option value='".$list['category_id']."'>".$list['category_name']."</option>";
			}
		}
		if(isset($_POST['id'])){
			echo $option;
		}else{
			echo json_encode(array('status'=>1,'data'=>$option));
		}
		// print_r($option );die;
		
	}
	
	public function updateCheckStatus($tableType = null,$any_id=NULL){
		$checkValidation = check_api_validation($_POST , array('id|require','status|require'));
			if($checkValidation['status'] == 1){
				
				
				if($tableType == 'artist_category'){
					$table_id = "category_id";
					$status   = "status";
				}
				if($tableType == 'get_discovered_category'){
					$tableType = "artist_category";
					$table_id  = "category_id";
					$status    = "get_discovered_status";
					$CateData = $this->DatabaseModel->select_data('category_id,category_name,category_slug','artist_category',array('category_id' =>$_POST['id']),1);

					if(!empty($CateData[0])){

						if(isset($_POST['status']) && $_POST['status']==1){

								$sliderExists = $this->DatabaseModel->select_data('id','homepage_sliders',array('slider_category_slug' =>$CateData[0]['category_slug']),1);

								if(empty($sliderExists)){

									$title 		= 	trim($CateData[0]['category_name']);
									$slug		=	strtolower(str_replace("-", "_", str_replace(" ","_",$title) ) );
									$slider_array	=	array(
										'slider_title'	=>	'Get Discovered - '.$title,
										'type'			=>	$slug,
										'mode'			=>	8, // spotlight
										'slider_mode'	=>	8, // spotlight
										'user_id'		=>	0,
										'category_id'	=>	0,
										'status'		=>  1,
										'genre'			=>  0,
										'slider_type'	=>  'single',
										'query_type'	=>  'SeeAll',
										'slider_category_slug' =>$CateData[0]['category_slug'],
									);
									$this->DatabaseModel->access_database('homepage_sliders','insert',$slider_array);
								}
							
						}else if(isset($_POST['status']) && $_POST['status']==0){
							/*$sliderExists = $this->DatabaseModel->select_data('id','homepage_sliders',array('slider_category_slug' =>$CateData[0]['category_slug']),1);
							if(!empty($sliderExists[0])){
								$this->DatabaseModel->access_database('homepage_sliders','delete','', array('id'=>$sliderExists[0]['id']));
							}*/
						}
					}
				}

				if($tableType == 'user_level_type'){
					$table_id = "type_id";
					$status   = "status";
				}
				if($tableType == 'video_level_chart'){
					$table_id = "chart_id";
					$status   = "status";
				}
				if($tableType == 'website_mode'){
					$table_id = "mode_id";
					$status   = "status";
				}
				if($tableType == 'default_mode_status'){
					$table_id  = "mode_id";
					$status    = $tableType;
					$tableType = 'website_mode';
					
					$this->DatabaseModel->access_database($tableType,'update',array($status=>0),array());
					
				}
				if($tableType == 'mode_of_genre'){
					$table_id = "genre_id";
					$status   = "status";
				}
				if($tableType == 'slider_mode_of_genre'){
					$tableType=	"mode_of_genre";
					$table_id = "genre_id";
					$status   = "is_in_slider";
				}
				if($tableType == 'users_content'){
					$table_id = "uc_userid";
					$status   = "is_iva";
				}
				
				if($tableType == 'article_categories'){
					$table_id = "id";
					$status   = "status";
				}
				
				if($tableType == 'slider_mode_of_articles_category'){
					$tableType=	"article_categories";
					$table_id = "id";
					$status   = "is_in_slider";
				}
				
				if($tableType == 'users_content_fc'){
					if($_POST['status'] == 1){
						$usersDetails 	= $this->DatabaseModel->select_data('user_email,user_name','users',['user_id'=>$_POST['id']],1);
						
						if(isset($usersDetails[0])){
							$email = $usersDetails[0]['user_email'];
							$firstname = $usersDetails[0]['user_name'];
							$subject = 'Welcome to the ' . PROJECT. ' Founders Club';
							$this->load->library(array('Audition_functions'));	
							$body = $this->audition_functions->founderClubHtml();
							$this->audition_functions->HtmlMailByMandrill($email,$firstname,$subject,$body);
						}
						
					}
					
					$tableType = 'users_content';
					$table_id = "uc_userid";
					$status   = "is_fc_member";
				}
				if($tableType == 'users_content_ele'){
					$tableType = 'users_content';
					$table_id = "uc_userid";
					$status   = "is_ele";
				}
				if($tableType == 'language_list'){
					$table_id = "id";
					$status   = "status";
				}
				if($tableType == 'channel_post_video'){
					$table_id = "post_id";
					$status   = "active_status";
				
					if(isset($_POST['profwords']) && !empty(trim($_POST['profwords'])) && $_POST['status'] == 2){
						$cond  	=   'channel_post_video.post_id = ' . $_POST['id'];
  					
						$join 	= 	array('multiple' , array(
							array(	'channel_post_thumb',
										'channel_post_thumb.post_id = channel_post_video.post_id',
										'left'),
							array(	'users',
										'channel_post_video.user_id = users.user_id',
										'left'),
						));
			
						$resultData 	= $this->DatabaseModel->select_data('post_key,user_email,user_name','channel_post_video use INDEX(post_id)',$cond,1,$join);
						
						if(isset($resultData[0])){
							$email = $resultData[0]['user_email'];
							$firstname = $resultData[0]['user_name'];
							$subject = 'Need your attention … Profanity to be removed';
							$this->load->library(array('Audition_functions','parser'));	
							$data = array(
									'user_name' => $firstname,
									'profwords' => $_POST['profwords'],
									'videoURL' => base_url('watch/'.$resultData[0]['post_key']),
									'last_date' => Date('F d, Y', strtotime('+15 days')),
									'PROJECT' => PROJECT,
							);
							
							$body = $this->parser->parse('admin/channel/profanity_notice_template', $data);
							
							$this->audition_functions->HtmlMailByMandrill($email,$firstname,$subject,$body);
						}
					}
				}
				if($tableType == 'featured_channel_post_video'){
					$tableType='channel_post_video';
					$table_id = "post_id";
					$status   = "featured_by_admin";
				}
				if($tableType == 'help_faq'){
					$table_id = "faq_id";
					$status   = "status";
				}
				if($tableType == 'ads_global_rate_details'){
					$table_id = "rdetail_id";
					$status   = "status";
				}
				if($tableType == 'homepage_sliders'){
					$table_id = "id";
					$status   = "status";
				}
				if($tableType == 'homepage_sidebar_sliders'){
					$tableType='homepage_sliders';
					$table_id = "id";
					$status   = "is_sidebar_slider";
				}
				if($tableType == 'page_setting'){
					$table_id = "id";
					$status   = "cover_image_status";
				}
				if($tableType == 'site_main_data'){
					$table_id = "id";
					$mode 	  = $this->session->userdata('mode');
					$status   = $mode.'_status';
				}
				
				if($tableType == 'users_ivs_info'){
					$table_id = "id";
					$status   = "status";
					if($_POST['status'] == 1){
						$j 	= ['users','users.user_id = users_ivs_info.user_id','left'];
						$f	= ['users_ivs_info.user_id','users.user_name','users.user_email'];
						$r 	= $this->DatabaseModel->select_data($f,$tableType,['id'=>$_POST['id']],1,$j);
						
						/***************************Start notification****************************************88*/
						$insert_array = array(	'noti_type'		=>	9,
												'noti_status'	=>	1,
												'from_user'		=>	1,
												'to_user'		=>	$r[0]['user_id'],
												'created_at'	=>	date('Y-m-d H:i:s')
												);
						$this->audition_functions->insertNoti($insert_array);
						
						$token 	= $this->audition_functions->getFirebaseToken($r[0]['user_id']);
						$link = base_url('Streaming');
						
						if(!empty($token)){
							$mess 			= 	$this->audition_functions->getNotiStatus(1,9);
							$msg_array 		=  	[
									'title'	=>	PROJECT .' '. $mess,
									'body'	=>	':)',
									'icon'	=>	base_url('repo/images/firebase.png'),
									'click_action'=>$link,
									'extra_data'=>array('id'=>'','intent'=>'live_stream','videoThumb'=>'')
								];
							$this->audition_functions->sendNotification($token,$msg_array);
						}
						/*******************************End Notificatioin ************************************/
						
						/****************************Mail Start ******************************************************/
						$subj = 'Approved Live Streaming Request';
						$main = 'We have Approved your Request for live Streaming.';
						$action = 'Your request for live streaming has been approved successfully. <br> Please click on the below link to go to '.PROJECT;
						$to = '{	
								"email":"'.$r[0]['user_email'].'",
								"name":"'.$r[0]['user_name'].'",
								"type":"to"
							   }' ;
						$greeting = 'Congratulations :)';	
						$this->audition_functions->MailByMandrillforLink($to,$subj,$greeting,$action,'Take me to ' .PROJECT ,base_url('Streaming'));
						/****************************Mail End ******************************************************/
					}
				}
				if($tableType == 'users_medialive_info'){
					$table_id = "id";
					$status   = "status";
					if($_POST['status'] == 1){
						$j 	= ['users','users.user_id = users_medialive_info.user_id','left'];
						$f	= ['users_medialive_info.user_id','users.user_name','users.user_email'];
						$r 	= $this->DatabaseModel->select_data($f,$tableType,['id'=>$_POST['id']],1,$j);
						
						/***************************Start notification****************************************88*/
						$insert_array = array(	'noti_type'		=>	9,
												'noti_status'	=>	1,
												'from_user'		=>	1,
												'to_user'		=>	$r[0]['user_id'],
												'created_at'	=>	date('Y-m-d H:i:s')
												);
						$this->audition_functions->insertNoti($insert_array);
						
						$token 	= $this->audition_functions->getFirebaseToken($r[0]['user_id']);
						$link = base_url('media_stream');
						
						if(!empty($token)){
							$mess 			= 	$this->audition_functions->getNotiStatus(1,9);
							$msg_array 		=  	[
									'title'	=>	PROJECT .' '. $mess,
									'body'	=>	':)',
									'icon'	=>	base_url('repo/images/firebase.png'),
									'click_action'=>$link,
									'extra_data'=>array('id'=>'','intent'=>'live_stream','videoThumb'=>'')
								];
							$this->audition_functions->sendNotification($token,$msg_array);
						}
						/*******************************End Notificatioin ************************************/
						
						/****************************Mail Start ******************************************************/
						$subj = 'Approved Live Streaming Request';
						$main = 'We have Approved your Request for live Streaming.';
						$action = 'Your request for live streaming has been approved successfully. <br> Please click on the below link to go to ' .PROJECT;
						$to = '{	
								"email":"'.$r[0]['user_email'].'",
								"name":"'.$r[0]['user_name'].'",
								"type":"to"
							   }' ;
						$greeting = 'Congratulations :)';	
						$this->audition_functions->MailByMandrillforLink($to,$subj,$greeting,$action,'Take me to '. PROJECT ,base_url('media_stream'));
						/****************************Mail End ******************************************************/
					}
				}
				
				if($tableType == 'users'){
					$table_id = "user_id";
					$status   = "user_status";
					$_POST['status'] = ($_POST['status']== 0)?3:$_POST['status'];
					if($_POST['status'] == 1){
						$usersDetails = $this->DatabaseModel->access_database('users','select','',array('user_id'=>$_POST['id']));
						if(isset($usersDetails[0])){
							$email = $usersDetails[0]['user_email'];
							$firstname = $usersDetails[0]['user_name'];
							$subject = PROJECT. ' Account Activated';
							$action = 'Your account has been approved successfully. <br> Please click on the below link to go to '.PROJECT;
							$to = '{	
									"email":"'.$email.'",
									"name":"'.$firstname.'",
									"type":"to"
									}' ;
							
							$this->audition_functions->MailByMandrillforLink($to,$subject,'Congratulations :)',$action,'Take me to '.PROJECT ,base_url());

							if($usersDetails[0]['is_deleted'] == 1){
								$this->DatabaseModel->access_database($tableType,'update',array('is_deleted'=>0,'date_of_delete_or_reactivate'=>date('Y-m-d'),'reason_of_delete'=>'Reactivated by admin'),array($table_id=>$_POST['id'])); //Reactivate by admin
							}
						}
					}
				}
				
				if($tableType == 'users_store_status'){
					$tableType = 'users_content';
					$table_id = "uc_userid";
					$status   = "is_store";
				}
				
				if($this->DatabaseModel->access_database($tableType,'update',array($status=>$_POST['status']),array($table_id=>$_POST['id'])) > 0)
				{
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Status Updated Successfully.';
				}else{
					$this->respMessage 	= 'Something went wrong';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	
	
	
	
	public function updateParam($tableType = null){
		$checkValidation = check_api_validation($_POST , array());
			if($checkValidation['status'] == 1){
				if($tableType == 'user_level_type'){
					$res = $this->DatabaseModel->access_database($tableType,'update',array('vote_count'=>$_POST['vote_count']),array('type_id'=>$_POST['type_id']));
				}
				if($tableType == 'video_level_chart'){
					$res = $this->DatabaseModel->access_database($tableType,'update',array('vote_count'=>$_POST['vote_count']),array('chart_id'=>$_POST['chart_id']));
				}
				if($tableType == 'mode_of_genre'){
					$res = $this->DatabaseModel->access_database($tableType,'update',array('mode_id'=>$_POST['val']),array('genre_id'=>$_POST['id']));
					$res = $this->DatabaseModel->access_database($tableType,'update',array('mode_id'=>$_POST['val']),array('parent_id'=>$_POST['id']));
				}
				if($res > 0){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Data Updated Successfully.';
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	public function add_genre(){
		$checkValidation = check_api_validation($_POST , array('genre_name|require','mode_id|require'));
			if($checkValidation['status'] == 1){
				$genre_name = strtolower(str_replace(" ","-",$_POST['genre_name']));
				$_POST['genre_slug'] 	= slugify($genre_name);
				$_POST['parent_id'] 	= 0;
				$_POST['level'] 		= 1;
				if(isset($_POST['genre_id']) && $_POST['genre_id'] != ""){
					if($this->DatabaseModel->access_database('mode_of_genre','update',$_POST,array('genre_id'=>$_POST['genre_id']))){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Genre Updated Successfully.';
					}else{
						$this->respMessage = 'Something went wrong';
					}
				}else{
					if( $userId = $this->DatabaseModel->access_database('mode_of_genre','insert',$_POST,'')){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Genre Added Successfully.';
					}else{
						$this->respMessage = 'Something went wrong';
					}
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	public function add_subgenre(){
		$checkValidation = check_api_validation($_POST , array('genre_name|require','mode_id|require','parent_id|require'));
			if($checkValidation['status'] == 1){
				$genre_name = strtolower(str_replace(" ","-",$_POST['genre_name']));
				$_POST['genre_slug'] = slugify($genre_name);
				$_POST['level'] 	= 2;
				if(isset($_POST['genre_id']) && $_POST['genre_id'] != ""){
					if($this->DatabaseModel->access_database('mode_of_genre','update',$_POST,array('genre_id'=>$_POST['genre_id']))){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Genre Updated Successfully.';
					}else{
						$this->respMessage = 'Something went wrong';
					}
				}else{
					if( $userId = $this->DatabaseModel->access_database('mode_of_genre','insert',$_POST,'')){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Sub Genre Added Successfully.';
					}else{
						$this->respMessage = 'Something went wrong';
					}
				}
			
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	public function genre_list(){
		$data['page_menu'] = 'main_genre|genre_list|Genre List|genrelist'; 
		$data['website_mode'] = $this->DatabaseModel->access_database('website_mode','select','','');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/category/genrelist');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}
	function access_genrelist_old(){
		$this->is_ajax();
		$leadsCount =0;
		$data 	= array();
		
		if(isset($_GET['length']) && !empty(trim($_GET['length']))){
			
			$search = 	$_GET['search']['value'];
			$colm 	=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
			$order 	=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
			
			$cond = "";
			$start = $_GET['start'];
			
			$filed = array('genre_id','image','mode','genre_name','.mode_of_genre.status','mode_of_genre.mode_id','parent_id','level','is_in_slider');
			$join = array();
			$cond .= '(';
			for($i=0;$i < sizeof($filed); $i++){
				if($filed[$i] != ''){
					$cond .= "$filed[$i] LIKE '%".$search."%'";
					if(sizeof($filed) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
			$cond .= ')';
			if(isset($_GET['parent_id']) && !empty($_GET['parent_id'])){
				$cond .= ' AND website_mode.mode_id='.$_GET['parent_id'];
			}
			
			$join 			= array('website_mode','mode_of_genre.mode_id = website_mode.mode_id','left');
			$resultData 	= $this->DatabaseModel->select_data($filed,'mode_of_genre', $cond ,array($_GET['length'],$start) , $join, array($filed[$colm],$order) );
			$leadsCount 	= $this->DatabaseModel->aggregate_data('mode_of_genre','genre_id','COUNT',$cond,$join);
			$website_mode 	= $this->DatabaseModel->access_database('website_mode','select','','');
			
			$start++;	
			foreach($resultData as $list){
					$genre_id = $list['genre_id'];
					$options ='';
					foreach($website_mode as $mode){ 
						$selected = ($mode['mode_id'] == $list['mode_id'])?'selected':'';
						$options .=  '<option '.$selected.' value="'.$mode["mode_id"].'">'.$mode["mode"].'</option>';
					}
					$action = '<select class="changeOption form-control" data-action-url="admin/updateParam/mode_of_genre" data-id="'.$genre_id.'" >
								'.$options.'
								</select>';
					$action = ($list['level'] == 1)?$action:'';
					$status = ($list['status'] == 1)?'checked': "" ;
					$slider = ($list['is_in_slider'] == 1)?'checked': "" ;
					
					$subgenre = 'Main';
					if($list['parent_id'] != 0){
						$cond = 'genre_id = '.$list['parent_id'];
						$sub 	= $this->DatabaseModel->select_data('genre_name','mode_of_genre', $cond ,1);
						$subgenre = (isset($sub[0]['genre_name']))? $sub[0]['genre_name'] : $subgenre; 
					}
					
					array_push($data , array(
							$start++,
							'<div class="dis_admin_img_div"><img width="212" height="157" onerror="erroronimageload(this)" src="'.base_url('repo_admin/images/genre/'.$list['image']).'"></div>',
							$list['mode'],
							$list['genre_name'],
							$subgenre,
							$action,
							'<input '.$status.' type="checkbox" data-check-id="'.$genre_id.'" data-action-url="admin/updateCheckStatus/mode_of_genre">',
							'<input '.$slider.' type="checkbox" data-check-id="'.$genre_id.'" data-action-url="admin/updateCheckStatus/slider_mode_of_genre">',
							'<a data-id="'.$genre_id.'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>',
							'<div class="dis_upload_div">
								<input type="file" id="custom_file'.$genre_id.'" name="file" class="inputfile UploadFile" style="display:none;" data-file_type="jpeg|jpg|png|gif" data-path="repo_admin/images/genre" data-url="admin/uploadedfile" data-id="'.$genre_id.'">
								<label for="custom_file'.$genre_id.'"><figure><svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill= "#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"/></svg></figure></label>
							</div>'
								
							
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
	function access_genrelist(){
		$this->is_ajax();
		$leadsCount =0;
		$data 	= array();
		
		if(isset($_GET['length']) && !empty(trim($_GET['length']))){
			
			$search = 	$_GET['search']['value'];
			$colm 	=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
			$order 	=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
			
			$cond = "1 = 	1";
			$start = $_GET['start'];
			
			$filed = array('genre_id','image','website_mode.mode','genre_name','.m.status','m.mode_id','parent_id','level','is_in_slider','(select COUNT(post_id) from channel_post_video use INDEX(genre) LEFT JOIN users ON users.user_id = channel_post_video.user_id where user_status = 1 AND genre = genre_id AND delete_status = 0 AND active_status = 1) as genre_total_video','(select COUNT(post_id) from channel_post_video use INDEX(sub_genre) LEFT JOIN users ON users.user_id = channel_post_video.user_id where user_status = 1 AND sub_genre = genre_id AND delete_status = 0 AND active_status = 1) as subgenre_total_video ');

			$order_filed = array('genre_id','image','website_mode.mode','genre_name','.m.status','m.mode_id','parent_id','level','is_in_slider','genre_total_video','subgenre_total_video');

			$join = array();

			if(isset($_GET['parent_id']) && $_GET['parent_id'] != ''){
				$cond .= ' AND website_mode.mode_id='.$_GET['parent_id'].' ';
			}
			if(isset($_GET['status']) && $_GET['status'] != ''){
				$cond .= ' AND m.status='.$_GET['status'].' ';
			}
			if(isset($_GET['is_main']) && $_GET['is_main'] == 1){
				$cond .= ' AND m.level=1';
			}
			if($search != ''){
				$ofiled = array('genre_id','image','website_mode.mode','genre_name','.m.status','m.mode_id','parent_id','level','is_in_slider');
				$cond .= ' AND (';
				
				for($i=0;$i < sizeof($ofiled); $i++){
					if($ofiled[$i] != '' ){
						$cond .= "$ofiled[$i] LIKE '%".$search."%'";
						if(sizeof($ofiled) - $i != 1){
							$cond .= ' OR ';
						}	
					}
				}
				$cond .= ')';
			}
			
			
			$join = array('multiple' , array(
				array(	'website_mode use INDEX(mode_id)',
						'm.mode_id = website_mode.mode_id',
						'left'),
				) 
			); 
			
			$resultData 	= $this->DatabaseModel->select_data($filed,'mode_of_genre m use INDEX(genre_id)', $cond ,array($_GET['length'],$start) , $join, array($order_filed[$colm],$order),'','m.genre_id' );
			
			$join 			= array('website_mode','m.mode_id = website_mode.mode_id','left');
			$leadsCount 	= $this->DatabaseModel->aggregate_data('mode_of_genre m use INDEX(genre_id)','genre_id','COUNT',$cond,$join);
			
			$website_mode 	= $this->DatabaseModel->access_database('website_mode','select','','');

			$start++;	
			foreach($resultData as $list){
					$genre_id = $list['genre_id'];
					$options ='';
					foreach($website_mode as $mode){ 
						$selected = ($mode['mode_id'] == $list['mode_id'])?'selected':'';
						$options .=  '<option '.$selected.' value="'.$mode["mode_id"].'">'.$mode["mode"].'</option>';
					}
					$action = '<select class="changeOption form-control" data-action-url="admin/updateParam/mode_of_genre" data-id="'.$genre_id.'" >
								'.$options.'
								</select>';
					$action = ($list['level'] == 1)?$action:'';
					$status = ($list['status'] == 1)?'checked': "" ;
					$slider = ($list['is_in_slider'] == 1)?'checked': "" ;
					
					$subgenre = 'Main';
					if($list['parent_id'] != 0){
						$cond = 'genre_id = '.$list['parent_id'];
						$sub 	= $this->DatabaseModel->select_data('genre_name','mode_of_genre use INDEX(genre_id)', $cond ,1);
						$subgenre = (isset($sub[0]['genre_name']))? $sub[0]['genre_name'] : $subgenre; 
					}
					
					array_push($data , array(
							$start++,
							'<div class="dis_admin_img_div"><img width="212" height="157" onerror="erroronimageload(this)" src="'.base_url('repo_admin/images/genre/'.$list['image']).'"></div>',
							$list['mode'],
							$subgenre,
							$list['genre_name'],
							
							$action,
							'<input '.$status.' type="checkbox" data-check-id="'.$genre_id.'" data-action-url="admin/updateCheckStatus/mode_of_genre">',
							'<input '.$slider.' type="checkbox" data-check-id="'.$genre_id.'" data-action-url="admin/updateCheckStatus/slider_mode_of_genre">',
							'<a data-id="'.$genre_id.'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>',
							$list['genre_total_video'] == 0 ?  $list['subgenre_total_video'] : $list['genre_total_video'],
							'<div class="dis_upload_div">
								<input type="file" id="custom_file'.$genre_id.'" name="file" class="inputfile UploadFile" style="display:none;" data-file_type="jpeg|jpg|png|gif" data-path="repo_admin/images/genre" data-url="admin/uploadedfile" data-id="'.$genre_id.'">
								<label for="custom_file'.$genre_id.'"><figure><svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill= "#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"/></svg></figure></label>
							</div>',
							'<a class="openGenrePopup" data-type="'.$subgenre.'" data-id="'.$genre_id.'" data-mode="'.$list['mode_id'].'" data-title="'.$list['genre_name'].'" data-parent="'.$list['parent_id'].'" ><i class="fa fa-fw fa-edit"></i></a>
							<a href="" data-delete-id="'.$genre_id.'" data-field="genre_id" data-action-url="admin/deleteRowContent/mode_of_genre"><i class="fa fa-fw fa-trash"></i></a>',	

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
	public function selectGenreByMode(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$cond	=	array('mode_id'=>$_POST['id'],'level'=>1) ;
			$field	=	'genre_id,genre_name';
			$result = 	$this->DatabaseModel->select_data($field,'mode_of_genre',$cond);
			if(isset($result[0])){
				$option='';
				foreach($result as $list){
					$option .= '<option value="'.$list['genre_id'].'">'.$list['genre_name'].'</option>';
				}
				
				$data = $option;
			}else{
				$data = '';
			} 	
		}else{
			$data = '';
		}
		echo $data;
	}
	public function getUserData(){
		$checkValidation = check_api_validation($_POST , array('user_id|require'));
			if($checkValidation['status'] == 1){
				$uid = $_POST['user_id'];
				$accessParam = array(
								'where' => 'user_id='.$uid.'',
							);
				$userData	= $this->query_builder->user_list($accessParam);
				
				if(!empty($userData['users'][0]['category_id']))
					$userData['users'][0]['artist_subCategory'] = $this->getArtistSubCategory($userData['users'][0]['category_id']);
				
				if(!empty($userData['users'][0]['uc_country']))
					$userData['users'][0]['state_list'] = $this->getStateFromCountry($userData['users'][0]['uc_country']);
				
				if(isset($userData['users'][0]['uc_pic']))
					$userData['users'][0]['uc_pic'] = create_upic($uid,$userData['users'][0]['uc_pic']);
				
				if(isset($userData['users'][0]['uc_about']))
					$userData['users'][0]['uc_about'] = nl2br( strip_tags($userData['users'][0]['uc_about']) ,false);
				
				if(isset($userData['users'][0]['aws_s3_profile_video'])){
					$this->load->library('share_url_encryption');
					$cover 		= $userData['users'][0]['aws_s3_profile_video'];
					$ivp 		= $userData['users'][0]['is_video_processed'];
					$preview 	= $this->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4',$ivp);
					$userData['users'][0]['aws_s3_profile_video']	= isset($preview['video'])?$preview['video']:'';
				}
				// print_r($userData['users'][0]);die;
				if(!(empty($userData))){
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Data avaialble.';
					$resp =  array('data' => $userData['users'][0]);	
				}else{
					$this->respMessage = 'Invalid user id.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($resp);
	}
	
	function getArtistSubCategory($category_id = null){
		if(isset($_POST['id'])){
			$category_id = $_POST['id'];
		}
		$accessParam 	= array(
								'where' => 'parents_id='.$category_id.'',
							);
		$categoryData	= $this->query_builder->category_list($accessParam);
		
		$cat='';
		if(isset($categoryData['category'])){
			foreach($categoryData['category'] as $list){
				$cat .= '<option value="'.$list['category_id'].'">'.$list['category_name'].'</option>';
			}
		}
		if(isset($_POST['id'])){
			echo $cat ;
		}else{
			return $cat;
		}

	}
	function getStateFromCountry($country_id = null ){
		if(isset($_POST['id'])){
			$country_id = $_POST['id'];
		}
		$states = $this->DatabaseModel->select_data('*','state',array('country_id'=>$country_id));
		$state_list='';
		foreach($states as $list){
			$state_list .= '<option value="'.$list['id'].'">'.$list['name'].'</option>';
		}
		if(isset($_POST['id'])){
			echo $state_list ;
		}else{
			return $state_list;
		}
		
	}
	
	public function updateUserDetails(){
		
		$checkValidation = check_api_validation($_POST , array('user_name|require','user_phone|require','user_address|require','user_level|require','uc_country|require','uc_state|require','uc_city|require','user_email|require','user_uname|require'));
			if($checkValidation['status'] == 1){
				$user = array(
							'user_name' =>$_POST['user_name'],
							'user_phone' =>$_POST['user_phone'],
							'user_address' =>$_POST['user_address'],
							'user_level' =>$_POST['user_level']
						);
				
				if(!empty(trim($_POST['user_id']))){
					$this->DatabaseModel->access_database('users','update',$user,array('user_id'=>$_POST['user_id']));
				}else{
					$user['user_email'] =  $_POST['user_email'];
					$user['user_uname'] =  $_POST['user_uname'];
					
					if($_POST['user_level'] == 130){
						$user['user_cate'] 		= 13;
						$user['official_status']= 1;
						
					}
					
					$user_email_res	= $this->query_builder->user_list(array('where' => 'user_email='.$_POST['user_email'],'field'=>'users.user_id' ));
					$user_uname_res	= $this->query_builder->user_list(array('where' => 'user_uname='.$_POST['user_uname'],'field'=>'users.user_id' )); 
					if(!empty($user_email_res['users'])){
						$this->respMessage = 'This mail id already avaialble in our records';
						return $this->show_my_response();
						
					}else if(!empty($user_uname_res['users'])){
						$this->respMessage = 'Please choose some new uniqe user name';
						return $this->show_my_response();
					}else{
						
						$userId = $this->DatabaseModel->access_database('users','insert',$user,'');
					}
					
				}		
				
				$user_content = array(
									'uc_type'	=>(isset($_POST['uc_types']))?implode(',',$_POST['uc_types']):'',
									'uc_country'=>(isset($_POST['uc_country']))?$_POST['uc_country']:'',
									'uc_state'	=>(isset($_POST['uc_state']))?$_POST['uc_state']:'',
									'uc_city'	=>(isset($_POST['uc_city']))?$_POST['uc_city']:'',
									// 'uc_addr1'	=>(isset($_POST['uc_addr1']))?$_POST['uc_addr1']:'',
									// 'uc_addr2'	=>(isset($_POST['uc_addr2']))?$_POST['uc_addr2']:'',
									'uc_website'=>(isset($_POST['uc_website']))?$_POST['uc_website']:'',
									'uc_about'	=>(isset($_POST['uc_about']))?$_POST['uc_about']:'',
									'uc_name'	=>(isset($_POST['uc_name']))?$_POST['uc_name']:'',
									'uc_phone'	=>(isset($_POST['uc_phone']))?$_POST['uc_phone']:'',
									'uc_email'	=>(isset($_POST['uc_email']))?$_POST['uc_email']:'',
									
								);
				
				if(!empty(trim($_POST['user_id']))){
					$this->DatabaseModel->access_database('users_content','update',$user_content,array('uc_userid'=>$_POST['user_id']));
					$this->respMessage = 'User details updated successfully.';
				}else{
					$user_content['uc_userid'] =  $userId ;
					$this->DatabaseModel->access_database('users_content','insert',$user_content,'');
						
						$this->load->library('Audition_functions');	
						$email = $_POST['user_email'];
						$randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
						$this->DatabaseModel->access_database('users','update',array('user_password'=>md5($randomstr)),array('user_id'=>$userId));
							
						$subject = 'Welcome to '.PROJECT;
						$greeting = 'Thanks for creating an account with us';
						$action = 'Thank you very much for working with us. You can login to the '. PROJECT .' using these credentials.';
						// $body ="<p> Hi ".$_POST['user_name'].", <br/> Thank you very much for working with us. You can login to the DiscoveredTv using these credentials , <br/> </p> <p> Login Email : ".$_POST['user_email']."</p> <p> Password : ".$randomstr."<br/><br/> Thanks.</p> ";
						// $this->audition_functions->send_emails( $email , $subject , $body );
						$this->audition_functions->MailByMandrillForRegstr($email,$_POST['user_name'],$subject,$greeting,$action,$email,$randomstr);
						
						
					$this->respMessage = 'User details Inserted successfully.';
				}
					$this->statusCode = 1;
					$this->statusType = 'Success';
									
					
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	
	public function channel_video_list(){
		$data['page_menu'] = 'main_channel_video|sub_channel_video|Channel Video List|channel_video'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/channel/channel_video_list',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
		
	}
	public function approved_video_list(){
		$data['web_mode'] = $this->DatabaseModel->select_data('mode_id,mode','website_mode',array('channel_status'=>1));

		$data['user_list']= $this->DatabaseModel->select_data('user_id,user_name','users',array('is_deleted'=>0,'user_status'=>1,'user_dir'=>1,'user_role'=>'member'));
		
		$data['category'] = $this->DatabaseModel->select_data('category_id,category_name','artist_category',array('level'=>1));

		// $data['genre'] 	= $this->DatabaseModel->select_data('genre_id,genre_name','mode_of_genre', ['parent_id'=>0,'level'=>1,'status'=>1] ,'' , '', array('browse_order','ASC') );
		// echo '<pre>';
		// print_r($data['genre']);die;
		$data['page_menu'] = 'main_approved_video|sub_approved_video|Approved Video List|approved_video'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/channel/approved_video_list',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
		
	}
	public function getUserByCategory(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$result = $this->DatabaseModel->select_data('user_id As id,user_name As name','users',array('is_deleted'=>0,'user_status'=>1,'user_dir'=>1,'user_role'=>'member','user_level'=>$_POST['id']),'','',  array('user_name','ASC'));
			
			if(isset($result[0])){
				echo json_encode(array('status'=>1,'data'=>$result));	
			}else{
				echo json_encode(array('status'=>0,'message'=>'data not available.'));
			} 	
		}else{
			echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again.'));	
		}
	}
	
	public function getUserByMode(){
			
		$field	=	array('users.user_id As id','users.user_name As name');
	
		$join = array('multiple' , array(
				array(	'channel_post_thumb',
						'channel_post_thumb.post_id = channel_post_video.post_id',
						'left'),
				array(	'users', 
						'users.user_id 				= channel_post_video.user_id', 
						'left'),
				));
	
		/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
		$cond 		= $this->common->channelGlobalCond();
		
		$order_by	=	array('user_name','ASC');	
		$group_by 	= 	'channel_post_video.user_id';
		
		$where ="mode_of_genre.status=1 AND mode_of_genre.level=1";
		
		if(isset($_POST['mode']) && !empty($_POST['mode']) && $_POST['mode'] != 8){
			$cond 	.=' AND channel_post_video.mode='.$_POST['mode'].'';
			$where  .=' AND mode_of_genre.mode_id ='.$_POST['mode'].'';
		}
		
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$cond 	.=' AND users.user_level='.$_POST['id'].'';
		}
		
		$data['userList'] = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond ,'' , $join , $order_by,'',$group_by);
		
		
		if(isset($_POST['mode']) && !empty($_POST['mode']) && $_POST['mode'] == 10){
			$data['genreList'] = $this->DatabaseModel->select_data('id,cat_name As name','article_categories',['status' => 1],'','',array('category_order','ASC')); //It is actually category list of articles,
			array_push($data['genreList'], array('id'=>0, 'name'=>'All'));
		}else{
			$data['genreList'] = $this->DatabaseModel->select_data('mode_of_genre.genre_id As id,mode_of_genre.genre_name As name','mode_of_genre',$where,'','',array('mode_of_genre.browse_order','ASC'));
		}
	
	
		if(isset($data)){
			echo json_encode(array('status'=>1,'data'=>$data));	
		}else{
			echo json_encode(array('status'=>0,'message'=>'data not available.'));
		} 	
	} 
	
	public function rejected_video_list(){
		$data['page_menu'] = 'main_rejected_video|sub_rejected_video|Rejected Video List|rejected_video'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/channel/rejected_video_list',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
		
	}
	
	

	public function access_channel_video_list($status = null){
		
		$_GET = $_SERVER["REQUEST_METHOD"] == 'GET' ? $_GET : $_POST ;
		
		$data = array();
		$leadsCount = 0;
		$accessParam['where'] = '';
		
		$colm 	= isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] :  0 ;
		$dir 	= isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] :  'DESC' ;
		$page 	= isset($_GET['page']) ? $_GET['page'] :  0 ;
		
		$ofiled = ['channel_post_video.post_id','','users.user_name','channel_post_video.title','channel_post_video.description','channel_post_video.tag','website_mode.mode AS web_mode','channel_post_video.created_at','mode_of_genre.genre_name','channel_post_video.age_restr','channel_post_video.video_duration','','channel_post_video.active_status','channel_post_video.uploaded_video','users.user_id','image_name','featured_by_admin','channel_post_video.post_key'];
		
		$search = 	isset($_GET['search']['value'])?$_GET['search']['value']:'';
			
		$order 	= 	array($ofiled[$colm],$dir);

		
		$cond 	= ' 1 ';
		$limit =  $_GET['length'];
		$start =  $_GET['start'];
		
		$cond = "  (users.user_name LIKE '%".$this->db->escape_like_str($search)."%' ESCAPE '!' OR users.user_uname LIKE '%".$this->db->escape_like_str($search)."%' ESCAPE '!' OR  channel_post_video.title LIKE '%".$this->db->escape_like_str($search)."%' ESCAPE '!')";
		

		if(isset($_GET['videolength']) && !empty($_GET['videolength'])){
			$c = explode('-',$_GET['videolength']);
			$cond  .=  ' AND video_duration BETWEEN "'.($c[0]*60).'" AND "'.($c[1]*60).'" ';
		}
		
		if(isset($_GET['filter_profanity_words']) && !empty($_GET['filter_profanity_words']) && $status == 0){
			$str 		= file_get_contents( base_url('repo_admin/txt/cs_badWords_php.txt'));
			$str_arr 	= explode (",", $str); 
			$where 		= '';
			$total = count($str_arr);
			for ($i=0; $i < $total ; $i++) { 
				$param  = trim($str_arr[$i]);
				$where .= "title REGEXP '[[:<:]]{$param}[[:>:]]' OR description REGEXP '[[:<:]]{$param}[[:>:]]' OR tag REGEXP '[[:<:]]{$param}[[:>:]]'"; 

				if($total - 1 !== $i){
					$where .= ' OR ' ;
				}
			}
			$this->db->where('('.$where.')', '', false);
		}

		if(isset($_GET['mode']) && !empty($_GET['mode'])){
			$cond .= " AND channel_post_video.mode = '".$_GET['mode']."'";
		}
		
		if(isset($_GET['genre']) && !empty($_GET['genre'])){
			$cond .= " AND channel_post_video.genre = '".$_GET['genre']."'";
		}

		if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
			$instr = str_replace('|',',',$_GET['user_level']); 
			$cond .= " AND users.user_level IN($instr)";
		}
		
		if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
			$cond .= " AND channel_post_video.user_id = '".$_GET['user_id']."'";
		}
		
		if(isset($_GET['featured_by_admin']) && !empty($_GET['featured_by_admin'])){
			$cond .= " AND channel_post_video.featured_by_admin = '".$_GET['featured_by_admin']."'";
		}
		
		$join = array('multiple' , array(
			array(	'users', 
					'users.user_id 				= channel_post_video.user_id', 
					'left'),
			array(	'website_mode', 
					'website_mode.mode_id 		= channel_post_video.mode', 
					'left'),		
			array(	'mode_of_genre', 
					'mode_of_genre.genre_id 	= channel_post_video.genre', 
					'left'),
			array(	'channel_post_thumb',
					'channel_post_thumb.post_id = channel_post_video.post_id',
					'left'),
		));
		
		$orderin = array('channel_post_video.post_id','DESC');
		/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
		$condin = $this->common->channelGlobalCond([$status ,1,NULL,0,NULL,1,0]) .' AND '.  $cond  ;
		$joinin = array('multiple' , 
				array(
					array(	'users', 
							'users.user_id 		= channel_post_video.user_id', 
							'left'),
				)
		);

		$search_result = $this->DatabaseModel->select_data('post_id', 'channel_post_video' , $condin ,[$limit,$start]  , $joinin, $orderin );

		$this->session->set_userdata('export_video_details',$this->db->last_query());

		$search_post_id = array_column($search_result, 'post_id');
		$search_post_id = implode(',',$search_post_id);
		$search_post_id = !empty($search_post_id) ? $search_post_id : 0 ;
		
		$new_cond = $this->common->channelGlobalCond([$status , 1,  NULL , 0 , 1, 1 , 0]);
		$fcond 	 = $new_cond. " AND channel_post_video.post_id IN($search_post_id) ";
		
		$order = "FIELD(channel_post_video.post_id,$search_post_id)";
		$requestData = $this->DatabaseModel->select_data($ofiled , 'channel_post_video use INDEX(post_id)' , $fcond ,'', $join,$order);
		
		$cond 	.= ' AND '. $this->common->channelGlobalCond([$status , 1,  NULL , 0 , 1, 1 , 0]);
		
		if(isset($_GET['filter_profanity_words']) && !empty($_GET['filter_profanity_words']) && $status == 0){ $this->db->where('('.$where.')', '', false); }
		$leadsCount = $this->DatabaseModel->aggregate_data( 'channel_post_video use INDEX(post_id)','channel_post_video.post_id', 'COUNT' , $cond , $join);
		
		if(!empty($requestData)){
			$i=0;
			
			$active_status = '';
			$active_type = 'Inactive';
			$this->load->library('share_url_encryption');
			foreach($requestData as $list){
				if($list['active_status'] == 1){$active_status = 'checked="checked"' ; $active_type = 'Active';}
				
				$list['image_thumb'] = $imgUrl = base_url('repo/images/thumbnail.jpg');
				
				$url 		= AMAZON_URL . $list['uploaded_video'];
				
				$mainImg 	= AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$list['image_name'];
				
				$img 		= explode('.',$list['image_name']);
				if(isset($img[0]) && isset($img[1])){
					$list['image_thumb'] = $imgUrl 	= AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$img[0].'_thumb.'.$img[1] ;
				}
				$checked_featured = ($list['featured_by_admin'] == 1)? 'checked="checked"' : '';
				
				$list['vurl'] = $vurl 		= base_url('watch?p='.$list['post_key']);
				
				
				if ($page == 'pending') {
					array_push($data , array(
						'<input style="cursor:pointer;" type="checkbox" class="selectVideoIds" id="selectVideoIds'.$list['post_id'].'" value="'.$list['post_id'].'" />',	
						'<div class="dis_admin_img_div">
							<img src="'.$imgUrl.'"  onerror="errorThumb(this)" width="211px"> 
							<div class="overlay">
								<a target="_blank" href="'.$vurl.'" class="play_btn"><img src="'.base_url('repo/images/play_icon.png').'"></a>
							</div>
						</div>
						<br>
						<div class="btn-group">
							<a target="_blank" href="'. $mainImg .'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Image</a>
							<a target="_blank" href="'. $url.'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Video</a> 
						</div>',
						
						$list['user_name'],
						$list['title'],
						$list['description'],
						str_replace(',', '<br />', $list['tag']),
						$list['web_mode'],
						$list['created_at'],
						$list['genre_name'],
						$list['age_restr'],
						round($list['video_duration']/60),
						
						'<a  href="" data-fetch-id="'.$list['post_id'].'" data-active-status="'.$list['active_status'].'"  data-action-url="admin/getChannelVideoData" data-toggle="modal" data-target="#channel_video" data-img-src="'. $mainImg .'" data-pro-src="">
						<svg  xmlns="http://www.w3.org/2000/svg"  xmlns:xlink="http://www.w3.org/1999/xlink"  width="18px" height="11px">
						<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
						d="M17.883,5.836 C17.722,6.046 13.892,11.000 9.000,11.000 C4.108,11.000 0.277,6.046 0.117,5.836 C-0.035,5.635 -0.035,5.364 0.117,5.164 C0.277,4.953 4.108,-0.000 9.000,-0.000 C13.892,-0.000 17.722,4.953 17.883,5.164 C18.035,5.364 18.035,5.636 17.883,5.836 ZM9.000,1.138 C5.389,1.138 2.274,4.424 1.352,5.500 C2.276,6.574 5.396,9.862 9.000,9.862 C12.611,9.862 15.725,6.576 16.648,5.500 C15.724,4.425 12.603,1.138 9.000,1.138 ZM9.000,8.914 C7.037,8.914 5.440,7.382 5.440,5.500 C5.440,3.618 7.037,2.086 9.000,2.086 C10.962,2.086 12.559,3.618 12.559,5.500 C12.559,7.382 10.962,8.914 9.000,8.914 ZM9.000,3.224 C7.691,3.224 6.627,4.245 6.627,5.500 C6.627,6.755 7.691,7.776 9.000,7.776 C10.308,7.776 11.373,6.755 11.373,5.500 C11.373,4.245 10.308,3.224 9.000,3.224 Z"/>
						</svg></a>',
						'<a href="" class="dis_status_btn dis_green_btn ChangeVideoStatus" data-post-id="'.$list['post_id'].'" data-status="1" data-action-url="admin/updateCheckStatus/channel_post_video">Approve</a> 
						<a href="" class="dis_status_btn dis_red_btn ChangeVideoStatus" data-post-id="'.$list['post_id'].'" data-status="2" data-action-url="admin/updateCheckStatus/channel_post_video">Reject</a>',
						'<a href="'.base_url('monetize/'.$list['post_id'].'').'" target="_blank" title="Edit"><i class="fa fa-fw fa-edit"></i></a>'
					));
				}elseif ($page == 'approved' || $page == 'rejected') {
					array_push($data , array(
						'<div class="dis_admin_img_div">
							<img src="'.$imgUrl.'"  onerror="errorThumb(this)" width="211px">
							<div class="overlay">
								<a target="_blank" href="'.$vurl.'" class="play_btn"><img src="'.base_url('repo/images/play_icon.png').'"></a>
							</div>
						</div>
						<br>
						<div class="btn-group">
							<a target="_blank" href="'. $mainImg .'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Image</a>
							<a target="_blank" href="'. $url.'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Video</a> 
						</div>',
						
						$list['user_name'],
						$list['title'],
						$list['web_mode'],
						$list['genre_name'],
						$list['age_restr'],
						round($list['video_duration']/60),
						'<a  href="" data-fetch-id="'.$list['post_id'].'" data-active-status="'.$list['active_status'].'"  data-action-url="admin/getChannelVideoData" data-toggle="modal" data-target="#channel_video" data-img-src="'. $mainImg .'" data-pro-src="">
						<svg  xmlns="http://www.w3.org/2000/svg"  xmlns:xlink="http://www.w3.org/1999/xlink"  width="18px" height="11px">
						<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
						d="M17.883,5.836 C17.722,6.046 13.892,11.000 9.000,11.000 C4.108,11.000 0.277,6.046 0.117,5.836 C-0.035,5.635 -0.035,5.364 0.117,5.164 C0.277,4.953 4.108,-0.000 9.000,-0.000 C13.892,-0.000 17.722,4.953 17.883,5.164 C18.035,5.364 18.035,5.636 17.883,5.836 ZM9.000,1.138 C5.389,1.138 2.274,4.424 1.352,5.500 C2.276,6.574 5.396,9.862 9.000,9.862 C12.611,9.862 15.725,6.576 16.648,5.500 C15.724,4.425 12.603,1.138 9.000,1.138 ZM9.000,8.914 C7.037,8.914 5.440,7.382 5.440,5.500 C5.440,3.618 7.037,2.086 9.000,2.086 C10.962,2.086 12.559,3.618 12.559,5.500 C12.559,7.382 10.962,8.914 9.000,8.914 ZM9.000,3.224 C7.691,3.224 6.627,4.245 6.627,5.500 C6.627,6.755 7.691,7.776 9.000,7.776 C10.308,7.776 11.373,6.755 11.373,5.500 C11.373,4.245 10.308,3.224 9.000,3.224 Z"/>
						</svg></a>',
						'<a href="" class="dis_status_btn dis_red_btn ChangeVideoStatus" data-post-id="'.$list['post_id'].'" data-status="2" data-action-url="admin/updateCheckStatus/channel_post_video">Reject</a>',
						'<a href="'.base_url('monetize/'.$list['post_id'].'').'" target="_blank" title="Edit"><i class="fa fa-fw fa-edit"></i></a>'
					));
				}
							
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


	public function export_video_details($status=1){
		
		$data = $this->session->userdata('export_video_details');
		
		if(!empty($data)){
			
			$data = explode('LIMIT',$data);
			$query = $this->db->query($data[0]);
			$search_result = $query->result_array();

			$search_post_id = array_column($search_result, 'post_id');
			$search_post_id = implode(',',$search_post_id);
			$search_post_id = !empty($search_post_id) ? $search_post_id : 0 ;
		
			$ofiled = ['channel_post_video.post_id','','users.user_name','channel_post_video.title','channel_post_video.description','channel_post_video.tag','website_mode.mode AS web_mode','channel_post_video.created_at','mode_of_genre.genre_name','channel_post_video.age_restr','channel_post_video.video_duration','','channel_post_video.active_status','channel_post_video.uploaded_video','users.user_id','image_name','featured_by_admin','channel_post_video.post_key','channel_post_video.is_video_processed'];

			$orderin = array('channel_post_video.post_id','DESC');
		
			$join = array('multiple' , array(
				array(	'users', 
						'users.user_id 				= channel_post_video.user_id', 
						'left'),
				array(	'website_mode', 
						'website_mode.mode_id 		= channel_post_video.mode', 
						'left'),		
				array(	'mode_of_genre', 
						'mode_of_genre.genre_id 	= channel_post_video.genre', 
						'left'),
				array(	'channel_post_thumb',
						'channel_post_thumb.post_id = channel_post_video.post_id',
						'left'),
			));
			$new_cond = $this->common->channelGlobalCond([$status , 1,  NULL , 0 , 1, 1 , 0]);
			$fcond 	 = $new_cond. " AND channel_post_video.post_id IN($search_post_id) ";
		
			$order = "FIELD(channel_post_video.post_id,$search_post_id)";
			$videoData = $this->DatabaseModel->select_data($ofiled , 'channel_post_video use INDEX(post_id)' , $fcond ,'', $join,$order);
			
			$filename = "videos.csv";
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			ob_clean();

			$handle = fopen('php://output', 'w');
			
			$vdata = [];
			$this->load->library('share_url_encryption');
			fputcsv($handle, ['Thumbnail','Author','Title','Mode','Genre','Age Restriction','Duration(in min)','Web URL','MP4 URL','M3U8 URL']);
			foreach ($videoData as $list) {
				$imgUrl 			 = base_url('repo/images/thumbnail.jpg');
				
				$mp4_url 			 = AMAZON_URL . $list['uploaded_video'];
				
				$mainImg 			 = AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$list['image_name'];
				
				$img 				 = explode('.',$list['image_name']);
				if(isset($img[0]) && isset($img[1])){
					$imgUrl 	= AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$img[0].'_thumb.'.$img[1] ;
				}
				$checked_featured = ($list['featured_by_admin'] == 1)? 'checked="checked"' : '';
				
				$vurl 		= base_url('watch/'.$list['post_key']);

				
				$FilterData = $this->share_url_encryption->FilterIva($list['user_id'],'','',trim($list['uploaded_video']),true,'.m3u8',$list['is_video_processed']);

				$m3u8 = isset($FilterData['video'])?$FilterData['video']:'';

				$vdata = array( 
					$imgUrl,
					$list['user_name'],
					$list['title'],
					$list['web_mode'],
					$list['genre_name'],
					$list['age_restr'],
					round($list['video_duration']/60),
					$vurl,
					$mp4_url,
					$m3u8,
				);

				fputcsv($handle, $vdata);
			}
			
			fclose($handle);
			exit;
		}			
		//exit;
	}
	




	public function access_channel_video_list_old($status = null){
		
		$_GET = $_SERVER["REQUEST_METHOD"] == 'GET' ? $_GET : $_POST ;
		
		$data = array();
		$leadsCount = 0;
		$accessParam['where'] = '';
		
		$colm 	= isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] :  0 ;
		$dir 	= isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] :  'DESC' ;
		$page 	= isset($_GET['page']) ? $_GET['page'] :  0 ;
		
		$ofiled = ['channel_post_video.post_id','','users.user_name','channel_post_video.title','channel_post_video.description','channel_post_video.tag','channel_post_video.mode','channel_post_video.created_at','mode_of_genre.genre_name','artist_category.category_name','language_list.value','channel_post_video.age_restr','channel_post_video.video_duration','','channel_post_video.active_status'];
		
		$search = 	isset($_GET['search']['value'])?$_GET['search']['value']:'';
			
		$order 	= 	array($ofiled[$colm],$dir);

		$cond 	= 	$this->common->channelGlobalCond();

		$accessParam = array(
			'limit' => ''.$_GET['length'].','.$_GET['start'].'',
			'where' => 'keyword='.$search.',active_status='.$status.'',
			'order' => ''.$ofiled[$colm].','.$dir.''
		);

		// $filed = array('channel_post_thumb.image_name','channel_post_video.post_id','channel_post_video.title','users.user_name','channel_post_video.created_at','channel_post_video.post_key','users.user_id');
	
		if(isset($_GET['videolength']) && !empty($_GET['videolength'])){
			$accessParam['where'] .= ',videolength='.$_GET['videolength'].'';
			
			$c = explode('-',$_GET['videolength']);
			$cond  .=  ' AND video_duration BETWEEN "'.($c[0]*60).'" AND "'.($c[1]*60).'" ';
		}
		
		if(isset($_GET['filter_profanity_words']) && !empty($_GET['filter_profanity_words'])){
			$profanity_words = '';
			$accessParam['where'] .= ',profanity_words='.$profanity_words.'';
			
			// $str 		= file_get_contents( base_url('repo_admin/txt/cs_badWords_php.txt'));
			// $str_arr 	= explode (",", $str); 
			// $where 		= '';
			// $total = count($str_arr);
			// for ($i=0; $i < $total ; $i++) { 
			// 	$param  = trim($str_arr[$i]);
			// 	$where .= "title REGEXP '[[:<:]]{$param}[[:>:]]' OR description REGEXP '[[:<:]]{$param}[[:>:]]' OR tag REGEXP '[[:<:]]{$param}[[:>:]]'"; 

			// 	if($total - 1 !== $i){
			// 		$where .= ' OR ' ;
			// 	}
			// }
			// $this->CI->db->where($where, '', false);
		}

		if(isset($_GET['mode']) && !empty($_GET['mode'])){
			$accessParam['where'] .= ',mode='.$_GET['mode'].'';
			$cond .= " AND channel_post_video.mode = '".$_GET['mode']."'";
		}
		
		if(isset($_GET['user_level']) && !empty($_GET['user_level'])){
			$accessParam['where'] .= ',user_level='.$_GET['user_level'].'';
			$l = $_GET['user_level'];
			$cond .= " AND users.user_level = $l";
		}
		
		if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
			$accessParam['where'] .= ',user_id='.$_GET['user_id'].'';
			$cond .= " AND channel_post_video.user_id = '".$_GET['user_id']."'";
		}
		
		if(isset($_GET['featured_by_admin']) && !empty($_GET['featured_by_admin'])){
			$accessParam['where'] .= ',featured_by_admin='.$_GET['featured_by_admin'].'';
			$cond .= " AND channel_post_video.featured_by_admin = '".$_GET['featured_by_admin']."'";
		}
		/*
		if(!empty($search)){
			$cond .= ' AND (';
			for($i=0;$i < sizeof($filed); $i++){
				if($filed[$i] != ''){
					$cond .= "$filed[$i] LIKE '%".$search."%'";
					if(sizeof($filed) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
			$cond .= ')';
		}
		*/
		$channel_video_list	= $this->query_builder->channel_video_list($accessParam);
		
		if(!empty($channel_video_list['channel'])){
			
			$leadsCount =	$channel_video_list['total'];
			$i=0;
			
			$active_status = '';
			$active_type = 'Inactive';
			$this->load->library('share_url_encryption');
			foreach($channel_video_list['channel'] as $list){
				if($list['active_status'] == 1){$active_status = 'checked="checked"' ; $active_type = 'Active';}
				
				$list['image_thumb'] = $imgUrl = base_url('repo/images/thumbnail.jpg');
				
				$url 		= AMAZON_URL . $list['uploaded_video'];
				
				$mainImg 	= AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$list['image_name'];
				
				$img 		= explode('.',$list['image_name']);
				if(isset($img[0]) && isset($img[1])){
					$list['image_thumb'] = $imgUrl 	= AMAZON_URL.'aud_'.$list['user_id'].'/images/'.$img[0].'_thumb.'.$img[1] ;
				}
				$checked_featured = ($list['featured_by_admin'] == 1)? 'checked="checked"' : '';
				
				$list['vurl'] = $vurl 		= base_url('watch?p='.$list['post_key']);
				
				
				if ($page == 'pending') {
					array_push($data , array(
						'<input style="cursor:pointer;" type="checkbox" class="selectVideoIds" id="selectVideoIds'.$list['post_id'].'" value="'.$list['post_id'].'" />',	
						'<div class="dis_admin_img_div">
							<img src="'.$imgUrl.'"  onerror="errorThumb(this)" width="211px"> 
							<div class="overlay">
								<a target="_blank" href="'.$vurl.'" class="play_btn"><img src="'.base_url('repo/images/play_icon.png').'"></a>
							</div>
						</div>
						<br>
						<div class="btn-group">
							<a target="_blank" href="'. $mainImg .'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Image</a>
							<a target="_blank" href="'. $url.'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Video</a> 
						</div>',
						
						$list['user_name'],
						$list['title'],
						$list['description'],
						str_replace(',', '<br />', $list['tag']),
						$list['mode'],
						$list['created_at'],
						$list['genre_name'],
						$list['category_name'],
						$list['value'],
						$list['age_restr'],
						round($list['video_duration']/60),
						
						'<a  href="" data-fetch-id="'.$list['post_id'].'" data-active-status="'.$list['active_status'].'"  data-action-url="admin/getChannelVideoData" data-toggle="modal" data-target="#channel_video" data-img-src="'. $mainImg .'" data-pro-src="'.base_url('uploads/aud_'.$list['user_id'].'/images/'.$list['uc_pic']).'">
						<svg  xmlns="http://www.w3.org/2000/svg"  xmlns:xlink="http://www.w3.org/1999/xlink"  width="18px" height="11px">
						<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
						d="M17.883,5.836 C17.722,6.046 13.892,11.000 9.000,11.000 C4.108,11.000 0.277,6.046 0.117,5.836 C-0.035,5.635 -0.035,5.364 0.117,5.164 C0.277,4.953 4.108,-0.000 9.000,-0.000 C13.892,-0.000 17.722,4.953 17.883,5.164 C18.035,5.364 18.035,5.636 17.883,5.836 ZM9.000,1.138 C5.389,1.138 2.274,4.424 1.352,5.500 C2.276,6.574 5.396,9.862 9.000,9.862 C12.611,9.862 15.725,6.576 16.648,5.500 C15.724,4.425 12.603,1.138 9.000,1.138 ZM9.000,8.914 C7.037,8.914 5.440,7.382 5.440,5.500 C5.440,3.618 7.037,2.086 9.000,2.086 C10.962,2.086 12.559,3.618 12.559,5.500 C12.559,7.382 10.962,8.914 9.000,8.914 ZM9.000,3.224 C7.691,3.224 6.627,4.245 6.627,5.500 C6.627,6.755 7.691,7.776 9.000,7.776 C10.308,7.776 11.373,6.755 11.373,5.500 C11.373,4.245 10.308,3.224 9.000,3.224 Z"/>
						</svg></a>',
						'<a href="" class="dis_status_btn dis_green_btn ChangeVideoStatus" data-post-id="'.$list['post_id'].'" data-status="1" data-action-url="admin/updateCheckStatus/channel_post_video">Approve</a> 
						<a href="" class="dis_status_btn dis_red_btn ChangeVideoStatus" data-post-id="'.$list['post_id'].'" data-status="2" data-action-url="admin/updateCheckStatus/channel_post_video">Reject</a>',
					));
				}elseif ($page == 'approved' || $page == 'rejected') {
					array_push($data , array(
						'<div class="dis_admin_img_div">
							<img src="'.$imgUrl.'"  onerror="errorThumb(this)" width="211px">
							<div class="overlay">
								<a target="_blank" href="'.$vurl.'" class="play_btn"><img src="'.base_url('repo/images/play_icon.png').'"></a>
							</div>
						</div>
						<br>
						<div class="btn-group">
							<a target="_blank" href="'. $mainImg .'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Image</a>
							<a target="_blank" href="'. $url.'" class="btn btn-primary btn-lg btn-width" download><i class="fa fa-download"></i> Video</a> 
						</div>',
						
						$list['user_name'],
						$list['title'],
						$list['mode'],
						$list['genre_name'],
						$list['category_name'],
						$list['value'],
						$list['age_restr'],
						round($list['video_duration']/60),
						'<a  href="" data-fetch-id="'.$list['post_id'].'" data-active-status="'.$list['active_status'].'"  data-action-url="admin/getChannelVideoData" data-toggle="modal" data-target="#channel_video" data-img-src="'. $mainImg .'" data-pro-src="'.base_url('uploads/aud_'.$list['user_id'].'/images/'.$list['uc_pic']).'">
						<svg  xmlns="http://www.w3.org/2000/svg"  xmlns:xlink="http://www.w3.org/1999/xlink"  width="18px" height="11px">
						<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
						d="M17.883,5.836 C17.722,6.046 13.892,11.000 9.000,11.000 C4.108,11.000 0.277,6.046 0.117,5.836 C-0.035,5.635 -0.035,5.364 0.117,5.164 C0.277,4.953 4.108,-0.000 9.000,-0.000 C13.892,-0.000 17.722,4.953 17.883,5.164 C18.035,5.364 18.035,5.636 17.883,5.836 ZM9.000,1.138 C5.389,1.138 2.274,4.424 1.352,5.500 C2.276,6.574 5.396,9.862 9.000,9.862 C12.611,9.862 15.725,6.576 16.648,5.500 C15.724,4.425 12.603,1.138 9.000,1.138 ZM9.000,8.914 C7.037,8.914 5.440,7.382 5.440,5.500 C5.440,3.618 7.037,2.086 9.000,2.086 C10.962,2.086 12.559,3.618 12.559,5.500 C12.559,7.382 10.962,8.914 9.000,8.914 ZM9.000,3.224 C7.691,3.224 6.627,4.245 6.627,5.500 C6.627,6.755 7.691,7.776 9.000,7.776 C10.308,7.776 11.373,6.755 11.373,5.500 C11.373,4.245 10.308,3.224 9.000,3.224 Z"/>
						</svg></a>',
						'<a href="" class="dis_status_btn dis_green_btn " data-post-id="'.$list['post_id'].'" data-status="1" data-action-url="admin/updateCheckStatus/channel_post_video">Approve</a><a href="" class="dis_status_btn dis_red_btn" data-post-id="'.$list['post_id'].'" data-status="2" data-action-url="admin/updateCheckStatus/channel_post_video">Reject</a>',
					));
				}
							
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
	
	public function getChannelVideoData(){
		if(!empty($_POST['id'])){
			$accessParam = array(
							'where' => 'post_id='.$_POST['id'].',active_status='.$_POST['active_status'],
							'field'	=>	'channel_post_video.created_at,channel_post_video.post_key,website_mode.mode AS web_mode,channel_post_video.age_restr,channel_post_video.title,channel_post_video.description,channel_post_video.tag,users.user_name,users.user_email,users.user_phone,users.user_address,mode_of_genre.genre_name,artist_category.category_name,language_list.value AS language'	
							);
	
			$channel_video_list	= $this->query_builder->channel_video_list($accessParam);
			if(!empty($channel_video_list['channel'])){
				ksort($channel_video_list['channel'][0]);
				echo json_encode(array('status'=>1,'data'=>$channel_video_list['channel'][0]));
			}	
		}
	}
	
	
	public function DeleteUserAllDetails(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			
			$uid = $_POST['id'];

			if(isset($_POST['delete_type']) && $_POST['delete_type']==1){ // soft delete

				$updatetatus = array('user_status' =>2,
										'is_deleted' =>1,
										'date_of_delete_or_reactivate'=>date('Y-m-d'),
										'reason_of_delete'=>'Deleted by admin' 
										);
				
				$this->DatabaseModel->access_database(USERS,'update',$updatetatus,array('user_id'=>$uid));
			
				$this->statusCode  = 1;
				$this->statusType  = 'Success';
				$this->respMessage = 'User account deleted successfully.';

			}else if(isset($_POST['delete_type']) && $_POST['delete_type']==2){ // permanent delete
				$this->load->helper(array('aws_s3_action')); 
				
				$this->DatabaseModel->access_database('channel_cast_images','delete','', array('user_id'=>$uid));
				$this->DatabaseModel->access_database('channel_post_thumb','delete','', array('user_id'=>$uid));
				$this->DatabaseModel->access_database('channel_post_video','delete','', array('user_id'=>$uid));
				$this->DatabaseModel->access_database('publish_data','delete','', array('pub_uid'=>$uid));
				$this->DatabaseModel->access_database('users_content','delete','', array('uc_userid'=>$uid));	
				$this->DatabaseModel->access_database('become_a_endorser','delete','', array('endorser_id'=>$uid));	
				$this->DatabaseModel->access_database('become_a_fan','delete','', array('following_id'=>$uid));	
				$this->DatabaseModel->access_database('channel_video_view_count_by_date','delete','', array('video_userid'=>$uid));	
				// $this->DatabaseModel->access_database('channel_video_view_count_by_date_user','delete','', array('video_userid'=>$uid));	
				$this->DatabaseModel->access_database('channel_video_vote','delete','', array('user_id'=>$uid));	
				$this->DatabaseModel->access_database('chat_messages','delete','', array('from_user_id'=>$uid));	
				$this->DatabaseModel->access_database('chat_messages','delete','', array('to_user_id'=>$uid));	
				$this->DatabaseModel->access_database('comments','delete','', array('com_parentid'=>$uid));	
				$this->DatabaseModel->access_database('comments','delete','', array('com_uid'=>$uid));	
				$this->DatabaseModel->access_database('likes','delete','', array('like_uid'=>$uid));	
				$this->DatabaseModel->access_database('notifications','delete','', array('from_user'=>$uid));	
				$this->DatabaseModel->access_database('notifications','delete','', array('to_user'=>$uid));	
				$this->DatabaseModel->access_database('outstandings','delete','', array('user_id'=>$uid));	
				$this->DatabaseModel->access_database('payment_history','delete','', array('user_id'=>$uid));	
				$this->DatabaseModel->access_database('statements','delete','', array('user_id'=>$uid));	
				$this->DatabaseModel->access_database('users_billing_and_payment_info','delete','', array('billing_user_id'=>$uid));	
				$this->DatabaseModel->access_database('users','delete','', array('user_id'=>$uid));	
				
				if(is_dir('./uploads/aud_'.$uid.'/images')){
					rmdir('./uploads/aud_'.$uid.'/images');
				}

				if(is_dir('./uploads/aud_'.$uid.'/videos')){
					rmdir('./uploads/aud_'.$uid.'/videos');
				}

				if(is_dir('./uploads/aud_'.$uid)){
					rmdir('./uploads/aud_'.$uid);
				}

				s3_delete_matching_object('aud_'.$uid.'/' ,MAIN_BUCKET);
				s3_delete_matching_object('aud_'.$uid.'/' ,TRAN_BUCKET);
					
				$this->statusCode 	=  1;
				$this->statusType 	= 'Success';					
				$this->respMessage 	= 'All the details of user has deleted successfully';
				
			}else{
				$this->respMessage 	= 'Something went wrong. Please try again.';
			}
		}else{
			$this->respMessage 	= 'User id field required.';
		}
		$this->show_my_response();	
	}

	
	function Reorder_position($table){
		$reorder_id =  json_decode($_POST['reorder_id']);
		foreach($reorder_id as $key => $value){
			if($table == 'mode_of_genre'){
				$this->DatabaseModel->access_database('mode_of_genre','update',array('browse_order' => $key ),array('genre_id' => $value ));
			}else
			if($table == 'artist_category'){
				$this->DatabaseModel->access_database('artist_category','update',array('category_order' => $key ),array('category_id' => $value ));
			}else
			if($table == 'site_main_data'){
				$mode 	= $this->session->userdata('mode');
				$fileds = $mode.'_slider_order';
				$this->DatabaseModel->access_database('site_main_data','update',array($fileds=>$key+1 ),array('id'=>$value));	
			}else
			if($table == 'homepage_sliders'){
				
				$fileds ='slider_order';
				$this->DatabaseModel->access_database('homepage_sliders','update',array($fileds=>$key+1 ),array('id'=>$value));	
			}else
			if($table == 'article_categories'){
				
				$fileds ='category_order';
				$this->DatabaseModel->access_database('article_categories','update',array($fileds=>$key+1),array('id'=>$value));	
			}
			 
		}
	}
	
	
	function uploadedfile($website_mode=null,$page_type=null){
		$this->load->library('Audition_functions');
		if(!empty($_FILES) && !empty($_POST)){
			
			$file_name=$this->audition_functions->upload_file($_POST['path'],$_POST['file_type'],'userfile',true);
			
			 if($file_name != 0){
				 
				 
				 if(isset($file_name['file_name'])){
					
					$path 		= 	$_POST['path'];
					$file_name	=	$file_name['file_name'];	
					
					if($path == "repo_admin/images/genre"){
						
						$cond	=	array('genre_id' => $_POST['id'] ) ;
						
						$img 	= $this->DatabaseModel->select_data('image','mode_of_genre', $cond );
						
						if(!empty($img[0]['image'])){
							$file_path = ABS_PATH.$path.'/'.$img[0]['image'];
							
							if(file_exists($file_path)){
								unlink($file_path);
							}
						}
						
						
						// $this->audition_functions->resizeImage('212','157',$path.'/'.$file_name,'',$maintain_ratio = false,$create_thumb= false);
						
						if($this->DatabaseModel->access_database('mode_of_genre','update',array('image' => $file_name ),$cond ) > 0){
							echo json_encode(array('status' => 1 , 'message' =>'Image has uploaded Successfully'));
						} 
					
					}elseif($path == "repo_admin/images/homepage"){
						
						$title	=	$this->audition_functions->get_website_info('cover_image','homepage',$website_mode,'image');
						
						$file_path = ABS_PATH.$path.'/'.$title;
						
						if(file_exists($file_path .'.webp')){
							unlink($file_path);
							unlink($file_path.'.webp');
						}
							
						// $this->audition_functions->resizeImage('812','513',$path.'/'.$file_name,'',$maintain_ratio = false,$create_thumb= false);
						
						$this->load->library('convert_image_webp');
						if(file_exists($path.'/'.$file_name))
						$this->convert_image_webp->convertIntoWebp($path.'/'.$file_name);
						
						if($this->DatabaseModel->access_database('website_info','update',array('title' => $file_name ),array('field' => $_POST['id'],'website_mode'=>$website_mode,'page_type'=>$page_type,'file_type'=>'image')) > 0){
							echo json_encode(array('status' => 1 , 'message' =>'Image uploaded Successfully'));
						}
					}
					
				 }else{
						echo json_encode(array('status' => 0 , 'message' =>'Image uploaded Faild'));
				 }
			}
		}
			
	}
	
	

	
	function uploadVideo($field,$website_mode,$page_type){
		if(isset($_FILES['userfile']['name'])){
			
			$pathToVideo = ABS_PATH .'uploads/admin/video';
			$previous_Details = $this->audition_functions->get_website_info('cover_video',$page_type,$website_mode,'video');
			
			$uploaded = $this->audition_functions->upload_file($pathToVideo,'mp4','userfile',true,104857600);
			$videoName = $uploaded['file_name'];
			$file_path = './uploads/admin/video/'.$videoName;
			$amazon_path = "admin/video/";
			$res = s3_upload_object($file_path,$amazon_path);
				
				if(file_exists($file_path)){
					unlink($file_path);
				}
				if($uploaded != 0){
					if( !empty($previous_Details) ) {
						$this->DatabaseModel->access_database('website_info','update',array('title'=>$res['key']), array('field'=>'cover_video','page_type'=>$page_type,'website_mode'=>$website_mode,'file_type'=>'video'));
						
						$old_key = $previous_Details[0]['title'];
						if($old_key !== ''){
							$key = explode('.',$old_key)[0];
							s3_delete_object(array($old_key));
							s3_delete_matching_object($key,TRAN_BUCKET);
							// s3_delete_matching_object($key,'discovered.tv.thumbs');
						}
					}else {
						$this->DatabaseModel->access_database('website_info','insert',array('field'=>'cover_video','page_type'=>$page_type,'website_mode'=>$website_mode,'file_type'=>'video','title'=>$res['key']));
					}	
				
						echo json_encode($res);
				}else{
					echo 0;
				}
			
		}
		else{
			echo 0;
		}
		die();
	}
	
	
	
	
	function updateWebsiteInfo($website_mode = null,$page_type	= null){
		
		$resp =[];
		$checkValidation = check_api_validation($_POST , array());
			if($checkValidation['status'] == 1){
				if(isset($_POST['icon_menu'])){
					$_POST['icon_menu'] = json_encode($_POST['icon_menu']);
				}
				
				foreach($_POST as $key=>$value){
					// print_r($key);die;
					if($this->DatabaseModel->access_database('website_info','update',array('title'=>$value), array('field'=>$key,'page_type'=>$page_type,'website_mode'=>$website_mode,'file_type'=>'text')) > 0){
						$this->statusCode = 1;
						$this->statusType = 'Success';
						$this->respMessage = 'Information has updated successfully.';	
					}
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
			$this->show_my_response($resp);	
	}
	
	
	
	
	public function SaveEnquiryContentImage(){
		$pathToVideo = ABS_PATH .'uploads/admin/enquiry';
		if(isset($_FILES['upload']['name'])){
			$uploaded = $this->audition_functions->upload_file($pathToVideo,'jpg|jpeg|png|gif','upload',true,10000);
			if($uploaded != 0 ){
				$url = base_url('uploads/admin/enquiry/'.$uploaded['file_name']);
				$this->DatabaseModel->access_database('filebrowserimagebrowseurl','insert',array('image_name'=>$uploaded['file_name']));
				$function_number = $_GET['CKEditorFuncNum'];
				$message ='';
				echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
			}
		}
	}
	public function GetEnquiryContentImage(){
		$data = [];
		$data['images'] = $this->DatabaseModel->select_data('image_name','filebrowserimagebrowseurl','','', '' , array('id','DESC'));
		$data['page_menu']  = 'setting|enquiry_image|Enquiry Images|helpAndFaq'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/setting/GetEnquiryContentImage',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
		
	}
	
	
	function deleteRowContent($table = null){
		$checkValidation = check_api_validation($_POST , array('id|require','field|require'));
			if($checkValidation['status'] == 1){
				if(!empty($table)){
					
					$field 	= $_POST['field'];
					$id 	= $_POST['id'];
					
					if($table == 'help_faq'){
						$result = $this->DatabaseModel->select_data('icon_image',$table,array($field=>$id),1);
						$pathToImages = ABS_PATH .'uploads/admin/enquiry/';
						if(isset($result[0]['icon_image']) &&  file_exists($pathToImages.$result[0]['icon_image'])){
							@ unlink($pathToImages.$result[0]['icon_image']);
						}
					}

					if($table == 'ads_global_rate_details'){
						if($id == 1 ){
							$this->respMessage 	= 'You can\'t delete global rate plan.';
							return $this->show_my_response();die;
						}
					}

					if($table == 'users_medialive_info'){
						$result = $this->DatabaseModel->select_data('media_info,user_id',$table,array($field=>$id),1);
						if(isset($result[0]['media_info'])){
							$resources = json_decode($result[0]['media_info'],true);
							$this->load->helper('media_stream');
							
							$r =  	DeleteEndPoint(['origin_endpoint_id' => $resources['endpoint']['Id'] ]);
							$r =  	deleteMediaPackageChannel($resources['package']['Id']);
							$r =  	deleteMediaTailorConfig(['ChannelName' => $resources['tailor']['Name'] ]);
							$r = 	deleteMediaChannel($resources['Channel']['Id']);
							$r = 	deleteMediaInput($resources['input']['Id']);
							$this->DatabaseModel->access_database('channel_post_video','update',['is_stream_live' => 0],['user_id'=>$result[0]['user_id']]); 
						}
					}
					
					if($table == 'mode_of_genre'){
						$path 	= "repo_admin/images/genre";
						
						$cond	=	array('genre_id' => $id ) ;
						
						$img 	= $this->DatabaseModel->select_data('image','mode_of_genre', $cond );
						
						if(!empty($img[0]['image'])){
							$file_path = ABS_PATH.$path.'/'.$img[0]['image'];
							
							if(file_exists($file_path)){
								unlink($file_path);
							}
						}
					}
					
					if($this->DatabaseModel->access_database($table,'delete','', array($field=>$id))){
						$this->respMessage 	= 'Data has delete successfully.';
						$this->statusCode 	= 1;
						$this->statusType 	= 'Success';
					}else{
						$this->respMessage 	= 'something went wrong,please try again.';
					}
				}else{
					$this->respMessage 	= 'table missing.';
				}
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
	}
	
	function getEnqury($id=null){
		if(!empty($id)){
			$result = $this->DatabaseModel->select_data('*','help_faq',array('faq_id'=>$id),1);
			if(isset($result[0])){
				echo json_encode(array('status'=>1,'data'=>$result[0]));	
			}else{
				echo json_encode(array('status'=>0,'message'=>'data not available.'));
			} 	
		}else{
			echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again.'));	
		}
	}
	
	
	
	function getSliderTitle(){
		$st=[];
		$slider_title 	= $this->DatabaseModel->select_data('slider_title','site_main_data',array('id >'=>3));
		foreach($slider_title  as $list){
			$title = explode(',',$list['slider_title'] );
			foreach($title as $tag){
				$st[]= $tag;
			}
		}
		echo json_encode(array_values(array_unique($st)));
		
	}
	public function getGenreByCategory(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			
			$cond	=	array('channel_post_video.user_id'=>$_POST['id']) ;
			
			$join 	= 	array('multiple' , array(
							array('mode_of_genre','channel_post_video.genre = mode_of_genre.genre_id',
								'left'),
						));
			
			$field	=	'genre_id As id,genre_name As name';
			
			$result = 	$this->DatabaseModel->select_data($field,'channel_post_video',$cond,'',$join);
			
			if(isset($result[0])){
				echo json_encode(array('status'=>1,'data'=>$result));	
			}else{
				echo json_encode(array('status'=>0,'message'=>'data not available.'));
			} 	
		}else{
			echo json_encode(array('status'=>0,'message'=>'Something went wrong,please try again.'));	
		}
	}
	
	public function user_live_request(){
		$data['page_menu'] = 'main_userlist|live_request|Live Request|userlist'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/user/user_live_request');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}
	
	public function access_user_live_request(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];
		
		$field		= 	['users_ivs_info.id','users.user_name','users_ivs_info.is_live','users_ivs_info.status','users_ivs_info.user_id','channel_post_video.post_key','channel_post_video.title','users.user_uname','users_ivs_info.stream_info'];
		
		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		
		$cond 		= '1 ' ;
		
		$cond .= ' AND (';
			for($i=0;$i < sizeof($field); $i++){
				if($field[$i] != ''){
					$cond .= "$field[$i] LIKE '%".$search."%'";
					if(sizeof($field) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
		$cond  = rtrim($cond , 'OR ');
		$cond .= ')';
		
		$join = array('multiple' , array(
			array(	'users', 
					'users.user_id 		= users_ivs_info.user_id', 
					'left'),
			array(	'channel_post_video',
					'channel_post_video.post_id = users_ivs_info.live_pid',
					'left'),
			));
		// print_r(array($field[$colm],$order));die;
		$userData 	= 	$this->DatabaseModel->select_data($field,'users_ivs_info',$cond,[$_GET['length'],$start],$join,[$field[$colm],$order]);
		$leadsCount =	$this->DatabaseModel->aggregate_data('users_ivs_info','users_ivs_info.user_id','COUNT',$cond,$join);
		
		if(!empty($userData)){
			$start++;
			
			foreach($userData as $list){
				// $Rstatus = ($list['status'] == 1)?'checked':'';
				$stream_info =  json_decode($list['stream_info'],true);
				
				$info = 'Number Of Viewers - '	.$stream_info['number_of_viewers'].'<br>';
				$info .= 'Average Stream Duration(in m) - ' .$stream_info['average_stream_duration'].'<br>';
				$info .= 'Stream Per Month - ' .$stream_info['streams_per_month'].'<br>';
				$info .= 'Total Number Of Stream - ' .$stream_info['total_number_of_stream'];
				
				$request_status = [ 0 => 'Decline' , 1 => 'Enable' ];
				$options = '<option value="3">Request</option>';
				foreach($request_status as $key => $value){ 
					$selected = ($key == $list['status'] )?'selected':'';
					$options .=  '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
				}
				$action = '<select class="DisableRequest form-control" data-check-id="'.$list['id'].'" 	data-action-url="admin/updateCheckStatus/users_ivs_info">'.$options.'</select>';
							
				array_push($data , array(
					$start++,
					'<a target="_blank" href="'.base_url('profile?user='.$list['user_uname']).'">'.$list['user_name'].'</a>',
					'<a target="_blank" href="'.base_url('watch?p='.$list['post_key']).'">'.$list['title'].'</a>',
					($list['is_live'] == 1) ? "Live" : 'Offline',										
					($list['status'] == 0) ? "Disabled" : (  ($list['status'] == 1)?'Enabled':'Requested' ) ,
					$info,
					$action
					// '<input '.$Rstatus.' type="checkbox" class="DisableRequest" data-check-id="'.$list['id'].'" data-action-url="admin/updateCheckStatus/users_ivs_info">',
					
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
	
	public function media_live_request(){
		$data['page_menu'] = 'main_userlist|media_request|Live Request|userlist'; 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/user/media_live_request');
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer');
	}
	
	public function access_media_live_request(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];
		
		$field		= 	['users_medialive_info.id','users.user_name','users_medialive_info.is_live','users_medialive_info.status','users_medialive_info.user_id','channel_post_video.post_key','channel_post_video.title','users.user_uname','users_medialive_info.stream_info'];
		
		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		
		$cond 		= '1 ' ;
		
		$cond .= ' AND (';
			for($i=0;$i < sizeof($field); $i++){
				if($field[$i] != ''){
					$cond .= "$field[$i] LIKE '%".$search."%'";
					if(sizeof($field) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
		$cond  = rtrim($cond , 'OR ');
		$cond .= ')';
		
		$join = array('multiple' , array(
			array(	'users', 
					'users.user_id 		= users_medialive_info.user_id', 
					'left'),
			array(	'channel_post_video',
					'channel_post_video.post_id = users_medialive_info.live_pid',
					'left'),
			));
		// print_r(array($field[$colm],$order));die;
		$userData 	= 	$this->DatabaseModel->select_data($field,'users_medialive_info',$cond,[$_GET['length'],$start],$join,[$field[$colm],$order]);
		$leadsCount =	$this->DatabaseModel->aggregate_data('users_medialive_info','users_medialive_info.user_id','COUNT',$cond,$join);
		
		if(!empty($userData)){
			$start++;
			
			foreach($userData as $list){
				
				$stream_info =  json_decode($list['stream_info'],true);
				
				$info = 'Number Of Viewers - '	.$stream_info['number_of_viewers'].'<br>';
				$info .= 'Average Stream Duration(in m) - ' .$stream_info['average_stream_duration'].'<br>';
				$info .= 'Stream Per Month - ' .$stream_info['streams_per_month'].'<br>';
				$info .= 'Total Number Of Stream - ' .$stream_info['total_number_of_stream'];
				
				$request_status = [ 0 => 'Decline' , 1 => 'Enable', 4 => 'Hold' ];
				
				$options = '<option value="3">Request</option>';
				
				foreach($request_status as $key => $value){ 
					$selected = ($key == $list['status'] )?'selected':'';
					$options .=  '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
				}
				
				$action = '<select class="DisableRequest form-control" data-check-id="'.$list['id'].'" 	data-action-url="admin/updateCheckStatus/users_medialive_info">'.$options.'</select>';
				
				$delete  = '<a class="btn btn-app" data-action-url="cron/mediaLiveSns/deleteRowContent/users_medialive_info" data-delete-id="'.$list['id'].'"  data-field="id" ><i class="fa fa-trash"></i>Delete</a>';

				array_push($data , array(
					$start++,
					'<a target="_blank" href="'.base_url('profile?user='.$list['user_uname']).'">'.$list['user_name'].'</a>',
					'<a target="_blank" href="'.base_url('watch?p='.$list['post_key']).'">'.$list['title'].'</a>',
					($list['is_live'] == 1) ? "Live" : 'Offline',										
					($list['status'] == 0) ? "Disabled" : (  ($list['status'] == 1)?'Enabled':'Requested' ) ,
					$info,
					$action,
					$delete 
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
	
	function AddDisapproveReason(){
		$checkValidation = check_api_validation($_POST , array('id|require','reason|require'));
			if($checkValidation['status'] == 1){
				$this->DatabaseModel->access_database('users_ivs_info','update',['disable_reason'=>$_POST['reason']],array('id'=>$_POST['id']));
				$j 	= ['users','users.user_id = users_ivs_info.user_id','left'];
				$f	= ['users_ivs_info.user_id','users.user_name','users.user_email'];
				$r 	= $this->DatabaseModel->select_data($f,'users_ivs_info',['id'=>$_POST['id']],1,$j);
				
				/**************************** Notificatioin Start ******************************************************/
				
				$insert_array = array(	'noti_type'		=>	9,
										'noti_status'	=>	0,
										'from_user'		=>	1,
										'to_user'		=>	$r[0]['user_id'],
										'created_at'	=>	date('Y-m-d H:i:s')
										);
				$this->audition_functions->insertNoti($insert_array);
				
				$token 	= $this->audition_functions->getFirebaseToken($r[0]['user_id']);
				$link = base_url('Streaming');
				
				if(!empty($token)){
					$mess 			= 	$this->audition_functions->getNotiStatus(0,9);
					$msg_array 		=  	[
							'title'	=>	PROJECT .' '. $mess,
							'body'	=>	':)',
							'icon'	=>	base_url('repo/images/firebase.png'),
							'click_action'=>$link
						];
					$this->audition_functions->sendNotification($token,$msg_array);
				}
				/****************************Notificatioin End ******************************************************/
				
				/****************************Mail Start ******************************************************/
				$subj = 'Declined Live Streaming Request';
				$main = 'We have Declined your Request for live Streaming.';
				$action = $_POST['reason'] . ' <br> Please click on the below link to go to '. PROJECT;
				$to = '{	
						"email":"'.$r[0]['user_email'].'",
						"name":"'.$r[0]['user_name'].'",
						"type":"to"
					   }' ;
				$greeting = 'Your live streaming request has been declined.  Please see below the reason for declining the request.

';	
				$this->audition_functions->MailByMandrillforLink($to,$subj,$greeting,$action,'Take me to '. PROJECT ,base_url('Streaming'));
				/****************************Mail End ******************************************************/
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Its added Successfully.';
			}else{
				$this->respMessage = $checkValidation['message'];
			}
		$this->show_my_response();
		
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
	

	public function findNonProfanityIds(){
		$colm = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] :  0 ;
		$dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] :  'DESC' ;

		$ofiled = ['channel_post_video.post_id','users.user_name','channel_post_video.title','channel_post_video.mode','mode_of_genre.genre_name','artist_category.category_name','language_list.value','channel_post_video.age_restr','channel_post_video.video_duration','channel_post_video.post_id'];
		$search = '';
		$status = '';
		$accessParam = array(
			'limit' => '',
			'where' => 'keyword='.$search.',active_status=0',
			'order' => ''.$ofiled[$colm].','.$dir.''
		);
		$profanity_words = '';
		$accessParam['where'] .= ',non_profanity_words='.$profanity_words.'';

		$channel_video_list	= $this->query_builder->channel_video_list($accessParam);
		$query = $this->db->last_query();
		print_r($query);
		print_r($channel_video_list);
		die;
	}

	public function Approve_all_selected(){
		$post_ids 		= isset($_POST['ids'])?$_POST['ids'] :'' ;
		$post_ids  = 	ltrim(rtrim($post_ids, ','), ',');
		
		$post_ids_arr = explode(',',$post_ids);
		$tableType = 'channel_post_video';
		$table_id = "post_id";
		$status   = "active_status";
		$count = 0;
		foreach ($post_ids_arr as $key => $value) {
			$count += 1;
			$this->DatabaseModel->access_database($tableType,'update',array($status=>'1'),array($table_id=>$value));
		}
		


		if(count($post_ids_arr) == $count)
		{
			$this->statusCode 	= 1;
			$this->statusType 	= 'Success';
			$this->respMessage 	= 'Status Updated Successfully.';
		}else{
			$this->respMessage 	= 'Something went wrong';
		}
		$this->show_my_response();
	}

	public function cloneSlider(){
		$checkValidation = check_api_validation($_POST , array('id|require'));
		if($checkValidation['status'] == 1){
			$sliderData 	= 	$this->DatabaseModel->select_data('*','homepage_sliders',array('id'=>$_POST['id']),1);
			if(!empty($sliderData)){
				$slider 		= $sliderData[0];
				$slider['mode'] = 8; //spotlight
				unset($slider['id']);
				if($this->DatabaseModel->access_database('homepage_sliders','insert',$slider)){
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Slider copied Successfully.';
				}else{
					$this->respMessage = 'Something went wrong';
				}
			}else{
				$this->respMessage = 'Something went wrong';
			}
		}else{
			$this->respMessage = $checkValidation['message'];
		}
		$this->show_my_response();
	}

	public function magnite_report_upload(){
		$data = [];
		if(isset($_FILES['report_file']['name']) && $_FILES['report_file']['name'] != ''){

			$config['upload_path'] 		= ABS_PATH.'application/controllers/cron/';
			$config['file_name'] 		= 'video_analytics_network.csv';
			$config['overwrite'] 		= true;
			$config['encrypt_name'] 	= false;
			$config['allowed_types'] 	= 'csv';
			$config['max_size']      	= 10000;

			$this->load->library('upload', $config);
			if ($this->upload->do_upload('report_file')){
				$ud	=	$this->upload->data();
				$data['file_name'] = $ud['raw_name'].$ud['file_ext'];	
				redirect(base_url().'cron/xandr/readMagniteReport');
			}else{
				$data['error'] =  $this->upload->display_errors();
			}
		}

		$this->load->view('admin/magnite_report_upload',$data);
		
	}

	public function getGenreList(){  
		if(isset($_POST['id'])){
			$list 	= $this->DatabaseModel->select_data('genre_id as id,genre_name as name','mode_of_genre',array('mode_id'=>$_POST['id'],'level'=>1) ,'', '', ['genre_name','ASC'] );
			$data['data'] = $list;
		
					echo json_encode(['status' => 1 , 'data' => $list]);
		}
	}


}
