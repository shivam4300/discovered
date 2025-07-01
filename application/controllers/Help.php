<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library(array('form_validation'));
	}

	private $uid;
	public $statusCode 	= 	'';
	public $respMessage = 	'';
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}

	public function index(){

		$data['page_info'] = array('page'=>'help','title'=>'Help');
		$data['search_query'] = (isset($_GET['search_query']))?urldecode(trim($_GET['search_query'])):'';
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/help/help',$data);
		$this->load->view('home/inc/footer',$data);
	}

	function search_content(){

			$list = '';
			$search_result=[];

			$searchKey = addslashes(validate_input($_GET['q']));

			$filed 		= ['title','subject'];

			$cond = (!is_login())? 'show_status = 1 AND ':'';

			$cond .= '(';
			for($i=0;$i < sizeof($filed); $i++){
				$cond .= "$filed[$i] LIKE '%".$searchKey."%'";
				if(sizeof($filed) - $i != 1){
					$cond .= ' OR ';
				}
			}
			$cond .= ')';

			$Qresult = $this->DatabaseModel->select_data($filed,'help_faq', $cond);

			$resp=[];
			if(isset($Qresult[0])){
					foreach($Qresult as $list){
						if($list['title'] != null)
						array_push($resp , array('label' => $list['title'] , 'value' => $list['title']));

						array_push($resp , array('label' => $list['subject'] , 'value' => $list['subject']));
					}
			}
			echo $_GET['callback'].'('.json_encode($resp).');';
	}


	public function get_my_content($searchType){
		$searchKey = (isset($_GET['search_query']))?$_GET['search_query']:'';

		$searchKey = addslashes(validate_input($searchKey));

		$limitData = 5;
		$startOffset = (isset($_POST['start']))?$_POST['start']:0;

		$result = '';
		if(!empty($searchType)){
			if($searchType == '2'){

				$filed 		= ['icon_image','title','subject','status','description','faq_id'];

				$cond  = (!is_login())? 'show_status = 1 AND ':'';

				$cond  .= 'type ='.$searchType. ' AND ' ;
				$cond .= '(';
				for($i=0;$i < sizeof($filed); $i++){
					if($filed[$i] != ''){
						$cond .= "$filed[$i] LIKE '%".$searchKey."%'";
						if(sizeof($filed) - $i != 1){
							$cond .= ' OR ';
						}
					}
				}
				$cond .= ')';
				$join	=	'';

				$Qresult = $this->DatabaseModel->select_data($filed,'help_faq', $cond ,array($limitData+1,$startOffset));

				if(isset($Qresult[0])){
					$checkChanelCount = 1;

					foreach($Qresult as $list){
						if($checkChanelCount++ <= $limitData){
							$result .='<div class="panel panel-default">
										  <div class="panel_topbox">
											<div class="enqry_thumb">
												<a>
													<img src="'.base_url('uploads/admin/enquiry/'.$list['icon_image']).'" alt="" class="image-fluid">
												</a>
											</div>
											<div class="enqry_headinfo">
												<a class="e_movie_title">'.$list['title'].'</a>
												<p>'.$list['subject'].'</p>
											</div>
											<div class="enqry_dropbtn">
											  <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$list['faq_id'].'">
											  <i class="fa-solid fa-angle-down"></i>
											  </a>
											</div>
										  </div>

										  <div id="collapse'.$list['faq_id'].'" class="panel-collapse collapse">
											<div class="panel-body">
											<p>
											'.json_decode($list['description']).'
											</p>
											</div>
										  </div>
										</div>';
						}

					}

					if(sizeof($Qresult) > $limitData){
						$result .= '<div class="col-md-12">
							<div class="profile_load_more text-center">
								<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
							</div>
						</div>';
					}
				}else{
					$result .= '<div class="col-md-12">
									<div class="profile_load_more text-center">
										'.$this->common_html->content_not_available_html().'
									</div>
								</div>';
				}

			}else if($searchType == '1'){
				$filed 		= ['subject','description','faq_id'];

				$cond  = 'type ='.$searchType. ' AND status = 1 AND' ;

				$cond .= '(';
				for($i=0;$i < sizeof($filed); $i++){
					if($filed[$i] != ''){
						$cond .= "$filed[$i] LIKE '%".$searchKey."%'";
						if(sizeof($filed) - $i != 1){
							$cond .= ' OR ';
						}
					}
				}
				$cond .= ')';
				$join	=	'';

				$Qresult = $this->DatabaseModel->select_data($filed,'help_faq', $cond ,array($limitData+1,$startOffset));

				if(isset($Qresult[0])){
					$checkChanelCount = 1;

					foreach($Qresult as $list){
						if($checkChanelCount++ <= $limitData){

							$result .= '<div class="faq_box_wrapper">
											<h4 class="faq_title">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.99 511.97" width="27px" height="27px"><defs><style>.cls-1{fill:#fff;}.cls-2,.cls-3{fill:#eb581f;}.cls-2{opacity:0.2;}</style></defs><title>Asset 1</title><g id="Layer_2" data-name="Layer 2"><g id="Capa_1" data-name="Capa 1"><path class="cls-1" d="M37.85,272.74a170.45,170.45,0,1,1,315.57-86.92A173.47,173.47,0,0,1,178.35,353.7H21.05L37.6,325.58A51.25,51.25,0,0,0,37.85,272.74Z"/><path class="cls-2" d="M474.18,418.84A170.44,170.44,0,0,0,351.71,160.61a171.5,171.5,0,0,1,1.7,25.21A173.47,173.47,0,0,1,178.34,353.7H160.56A174.36,174.36,0,0,0,333.69,499.8H491l-16.56-28.13A51.28,51.28,0,0,1,474.18,418.84Z"/><path class="cls-3" d="M484.89,465.46a38.85,38.85,0,0,1,0-40.17C537.73,339.38,510.94,226.9,425,174.07a182.57,182.57,0,0,0-61.88-23.93,182.66,182.66,0,0,0-54.54-99.95A180.87,180.87,0,0,0,168.36.64C67.81,8.52-7.31,96.41.57,197a182.36,182.36,0,0,0,26.94,82.12,38.87,38.87,0,0,1,0,40.18L10.59,347.49a12.18,12.18,0,0,0,10.47,18.39H150.59A186.9,186.9,0,0,0,333.21,512H491a12.16,12.16,0,0,0,10.47-18.38ZM42.35,341.53l5.84-9.86a63.19,63.19,0,0,0,0-65.38,158.27,158.27,0,1,1,293.05-80.72,161.32,161.32,0,0,1-162.9,156ZM333.69,487.62A162.54,162.54,0,0,1,175.42,365.88h2.92A185.77,185.77,0,0,0,365.58,185.94v-10.6a158.28,158.28,0,0,1,98.62,237,63.19,63.19,0,0,0,0,65.38l5.84,9.86Z"/><path class="cls-3" d="M196.48,153.92h0c0,12.17-11.44,20-40.78,21.18l-1.22,1.22,5.36,37.74h24.34l2.56-14.24c24.84-4.39,44.93-16.56,44.93-46.39h0c0-30.92-22.77-48.7-56.37-48.7a73.08,73.08,0,0,0-57,25.2l21.43,23.5a48,48,0,0,1,34.94-16.07C188.57,137.36,196.48,143.45,196.48,153.92Z"/><rect class="cls-3" x="152.16" y="232.52" width="37.74" height="37.25"/></g></g></svg>
											'.$list['subject'].'</h4>
											<p class="faq_discription">'.nl2br($list['description']).'</p>
										</div>';
						}

					}

					if(sizeof($Qresult) > $limitData){
						$result .= '<div class="col-md-12">
										<div class="profile_load_more text-center">
											<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
										</div>
									</div>';
					}
				}else{
					$result .= '<div class="col-md-12">
									<div class="profile_load_more text-center">
										'.$this->common_html->content_not_available_html().'
									</div>
								</div>';
				}
			}
		}
		echo $result;
	}

	function get_violations_category(){
		$this->load->library('manage_session');
		$uid = is_login();
		$this->load->library('creator_jwt');
		$resp = array();

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1){
			if( !empty($uid) && $this->input->is_ajax_request()) {
				$rules = array(
					array( 'field' => 'parent_id', 'label' => 'Parent_id', 'rules' => 'trim|required'),
					array( 'field' => 'type', 'label' => 'Type', 'rules' => 'trim|required'),
				);
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run()){
					$parent_id = $this->input->post('parent_id');

					$type = $this->input->post('type');

					$cond ="parent_id = {$parent_id} AND status = 1 AND type = '{$type}'";

					if($parent_id == 0){

						$cond .=" AND (SELECT COUNT(viol_id) AS viol_id from violations_category c2 where c2.parent_id = c1.viol_id) > 0";
					}

					$resp['cate'] = $this->DatabaseModel->select_data('viol_id,violations_title,parent_id','violations_category c1',$cond);
					$this->respMessage = 	'';
					$this->statusCode  =  1;

				}else{
					$this->respMessage  =  $this->common->form_validation_error()['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response($resp);
	}

	function submit_violations_history(){
		$this->load->library('manage_session');
		$uid = is_login();

		$resp = array();
		$this->load->library('creator_jwt');
		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1){
			if( !empty($uid) && $this->input->is_ajax_request()) {
				$rules = array(
					array( 'field' => 'viol_cate', 'label' => 'Cateogory', 'rules' => 'trim|required'),
					array( 'field' => 'viol_subcate', 'label' => 'Sub Cateogory', 'rules' => 'trim|required'),
					array( 'field' => 'viol_msg', 'label' => 'Message', 'rules' => 'trim|max_length[250]'),
					array( 'field' => 'related_with', 'label' => 'Related With', 'rules' => 'trim|required'),
					array( 'field' => 'related_id', 'label' => 'Related ID', 'rules' => 'trim|required'),
				);
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run()){
					$related_with 	= $this->input->post('related_with');
					$related_id 	= $this->input->post('related_id');
					$related_user_id 	= $uid;
					$viol_cate 	= $this->input->post('viol_cate');
					$viol_subcate 	= $this->input->post('viol_subcate');
					$viol_msg 	= $this->input->post('viol_msg');

					$resp = $this->DatabaseModel->select_data('viol_his_id','violations_history',array(
						'related_with' 		=> 	$related_with,
						'related_id'		=>	$related_id,
						'related_user_id' 	=> 	$related_user_id,
						'status' 			=> 	0,
					));

					if(isset($resp[0]) && !empty($resp[0])){
						$where = array(
							'related_with' 		=> 	$related_with,
							'related_id'		=>	$related_id,
							'related_user_id' 	=> 	$related_user_id,
						);
						$data = array(
							'viol_cate'			=>	$viol_cate,
							'viol_subcate' 		=> 	$viol_subcate,
							'viol_msg' 			=> 	$viol_msg,
						);
						$id = $this->DatabaseModel->access_database('violations_history','update',$data,$where);
						if($id){
							$this->respMessage = 	'Thanks for your feedback.';
							$this->statusCode  =  1;
						}else{
							$this->respMessage = 	'No updates to save.';
						}

					}else{
						$data = array(
							'viol_cate'			=>	$viol_cate,
							'viol_subcate' 		=> 	$viol_subcate,
							'viol_msg' 			=> 	$viol_msg,
							'related_with' 		=> 	$related_with,
							'related_id'		=>	$related_id,
							'related_user_id' 	=> 	$related_user_id,
							'status' 			=> 	0,
							'created_at'		=> 	date('Y-m-d H:i:s')
						);
						$id = $this->DatabaseModel->access_database('violations_history','insert',$data);
						if($id){
							$this->respMessage = 	'Thanks for your feedback.';
							$this->statusCode  =  1;
						}else{
							$this->respMessage = 	'No data to save.';
						}
					}
				}else{
					$this->respMessage  =  $this->common->form_validation_error()['message'];
				}
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		$this->show_my_response();
	}









}
?>
