<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends CI_Controller {

	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	private $uid;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('DatabaseModel');
		$this->load->helper(array('api_validation','aws_s3_action','info','button'));
		$this->load->library(array('query_builder','Valuelist','form_validation','creator_jwt','session','share_url_encryption'));
		$this->uid = is_login();

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
		$this->audition_functions->manage_my_web_mode_session('articles');
		$data['page_info'] = array('page'=>'article_mode','title'=>'Articles');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/blogs/article_mode',$data);
        $this->load->view('home/inc/footer',$data);

	}

	public function redirect_blog($id){
		$this->blog($id);
	}

	// get blog by id or username
	public function blog($blg_id = '', $slug = ''){
		$this->audition_functions->manage_my_web_mode_session('articles');

		if (
			$blg_id != '' && $blg_id != '0' ||
			isset($_GET['user']) && !empty($_GET['user']) ||
			isset($_GET['category']) && !empty($_GET['category'])  ||
			isset($_GET['tag']) && !empty($_GET['tag']) ||
			isset($_GET['search']) && !empty($_GET['search'])
		) {

			if(isset($_GET['user'])){
				$data['post_id'] = $_GET['user'];
				$data['mode'] 	 = 'blogs_by_username';
			}
			else if(isset($_GET['category'])){
				$data['post_id'] = $_GET['category'];
				$data['mode'] 	= 'blogs_by_category_id';
			}
			else if(isset($_GET['tag'])){
				$data['post_id'] = $_GET['tag'];
				$data['mode'] 	= 'blogs_by_tag';
			}
			else if(isset($_GET['search'])){
				$data['post_id'] = $_GET['search'];
				$data['mode'] 	= 'search';
			}
			else{
				$articlesMetaData = [];
				$joins = array('articles_content', 'articles_content.article_id = articles.article_id');

				if ( (int)$blg_id == 0 ) {

					$blg_id = $this->share_url_encryption->share_single_article_link_creator($blg_id , 'decode');

					$data['post_id'] 	= $blg_id;
					$data['mode'] 		= 'blogs_by_id';

					$where = [
						'articles.article_id' => $blg_id ,
						'articles.complete_status' => 1 ,
						'articles.active_status' => 1,
						'articles.delete_status' => 0 ,
						'articles_content.order_' =>  0
					];

					$field = 'articles.*,articles_content.content,articles_content.content_type,';

					$articlesMetaData = $this->DatabaseModel->select_data($field , 'articles', $where , '' , $joins );

					if (isset($articlesMetaData[0])) {
						$content = $articlesMetaData[0];
						$data['metaData']['title'] = $content['ar_title'];
						if ( $content['content_type'] == 'image' ) {
							$data['metaData']['image'] = AMAZON_URL . $content['content'];
						}else{
							$data['metaData']['image'] = base_url('repo/images/blog_pp.png');
						}
					}else{
						redirect(base_url('articles'));
					}

				}else{

					$data['post_id'] 	= $blg_id;
					$data['mode'] 		= 'blogs_by_id';

					$where = [
						'articles.article_id' => $blg_id ,
						'articles.complete_status' => 1 ,
						'articles.active_status' => 1,
						'articles.delete_status' => 0 ,
						'articles_content.order_' =>  0
					];

					$field = 'articles.*,articles_content.content,articles_content.content_type,';

					$articlesMetaData = $this->DatabaseModel->select_data($field , 'articles', $where , '' , $joins );

					if (isset($articlesMetaData[0])) {
						$content = $articlesMetaData[0];
						$data['metaData']['title'] = $content['ar_title'];
						if ( $content['content_type'] == 'image' ) {
							$data['metaData']['image'] = AMAZON_URL . $content['content'];
						}else{
							$data['metaData']['image'] = base_url('repo/images/blog_pp.png');
						}

					}else{
						redirect(base_url('articles'));
					}

				}
			}

			$userTaste = array(
				'mode'  => $data['mode'],
				'u_b_id'=> $data['post_id'],
			);

			$this->session->set_userdata($userTaste);


			$data['page_info'] = array('page'=>'single_blog','title'=>'Articles');

			$this->load->view('home/inc/header',$data);
			if(isset($articlesMetaData[0]['article_type']) && $articlesMetaData[0]['article_type'] == 'slider'){
				$this->load->view('home/blogs/article_slider',$data);
			}else{
				$this->load->view('home/blogs/article_single',$data);
			}
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);



		}else{ redirect(base_url('articles')); }

	}

	// get blog data API by blog id or user name or category ID or tag
	public function get_blog(){

		$resp = array();

		$uid = $this->uid;

		if($this->input->is_ajax_request()){

			if(!isset($_SESSION['art_fids']) && !empty($uid) ){
				$following = $this->DatabaseModel->select_data('user_id','become_a_fan use INDEX (following_id)',array('following_id'=>$this->uid));
				$my_fids = [];
				if(isset($following[0])){
					foreach($following as $fid){
						if($fid['user_id'] != $this->uid){
							array_push($my_fids,$fid['user_id']);
						}
					}
				}
				$_SESSION['art_fids'] = $my_fids;
			}

			$this->form_validation->set_rules('blog_id', 'blog_id', 'trim');
			$this->form_validation->set_rules('user_id', 'user_id', 'trim');
			$this->form_validation->set_rules('categ', 'categ', 'trim');
			$this->form_validation->set_rules('complete_status', 'complete_status', 'trim');
			$this->form_validation->set_rules('offset', 'offset', 'trim');
			$this->form_validation->set_rules('limit', 'limit', 'trim');

			if ($this->form_validation->run() == FALSE){
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}else{
				$arts_fids       = isset($_SESSION['art_fids']) && !empty($_SESSION['art_fids']) ?$_SESSION['art_fids']:[0];
				$blog_id 		 = isset($_POST['blog_id']) && !empty($_POST['blog_id']) ?$_POST['blog_id']:'';
				$type 		 	 = isset($_POST['type']) && !empty($_POST['type']) ?$_POST['type']:'';
				$user_id 		 = isset($_POST['user_id']) && !empty($_POST['user_id'])  ?$_POST['user_id']:'';
				$categ	 		 = isset($_POST['categ']) && !empty($_POST['categ'])  ?$_POST['categ']:'';
				$tag 		 	 = isset($_POST['tag']) && !empty($_POST['tag'])  ?$_POST['tag']:'';
				$complete_status = isset($_POST['complete_status']) ? $_POST['complete_status']:1;
				$offset 		 = isset($_POST['offset']) && !empty($_POST['offset']) && $_POST['offset'] != "NaN" ? $_POST['offset']:0;
				$limit 		 	 = isset($_POST['limit'])  && !empty($_POST['limit'])  && $_POST['limit']  != "NaN" ? $_POST['limit'] :2;
				$uid             = isset($this->uid) && !empty($this->uid) ? $this->uid : 0;

				if(!empty($blog_id)){
					$where  = 'IF(`ar_uid` IN ('.implode(',',$arts_fids).') , `privacy_status` IN (6,7) , IF(`ar_uid` = '.$uid.' , `privacy_status` IN (5,6,7) , `privacy_status` = 7 ) ) AND `complete_status`= 1 AND `delete_status` = 0 AND `articles`.`article_id` = '.$blog_id;
					$table = 'articles';
				}

				if(!empty($user_id)){
					$condition_ = '';
					if ($complete_status == 1 || $complete_status == 0) {
						$condition_ = 'AND `articles`.`complete_status` = '.$complete_status.' AND `articles`.`delete_status` = 0';
					}else if ($complete_status == 2) {
						$condition_ = 'AND `articles`.`delete_status` = 1';
					}
					$where  = '`articles_content`.`order_` IN (0 ,1)';
					$table 	= '(SELECT `articles`.* FROM `articles` LEFT JOIN `users` ON `users`.`user_id` = `articles`.`ar_uid` WHERE IF(`ar_uid` IN ('.implode(',',$arts_fids).') , `privacy_status` IN (6,7) , IF(`ar_uid` = '.$uid.' , `privacy_status` IN (5,6,7) , `privacy_status` = 7 ) ) AND `users`.`user_uname` = "'.$user_id.'" '.$condition_.' ORDER BY `articles`.`article_id` DESC  LIMIT '.$offset.' , '.$limit.') as `articles`';
				}

				if(!empty($categ)){
					$where  = '`articles_content`.`order_` IN (0 ,1)';
					$table 	= '(SELECT * FROM `articles` LEFT JOIN `article_categories` ON `article_categories`.`id` = `articles`.`ar_category_id` WHERE IF(`ar_uid` IN ('.implode(',',$arts_fids).') , `privacy_status` IN (6,7) , IF(`ar_uid` = '.$uid.' , `privacy_status` IN (5,6,7) , `privacy_status` = 7 ) ) AND `article_categories`.`cat_name` = "'.$categ.'" AND `articles`.`complete_status` = 1 AND `articles`.`delete_status` = 0 ORDER BY `articles`.`article_id` DESC LIMIT '.$offset.' , '.$limit.') as `articles`';
				}

				if(!empty($tag)){
					$where  = '`articles_content`.`order_` IN (0 ,1)';
					$table 	= '(SELECT * FROM `articles` WHERE IF(`ar_uid` IN ('.implode(',',$arts_fids).') , `privacy_status` IN (6,7) , IF(`ar_uid` = '.$uid.' , `privacy_status` IN (5,6,7) , `privacy_status` = 7 ) ) AND `articles`.`ar_tag` LIKE "%'.$tag.'%" AND `articles`.`complete_status` = 1 AND `articles`.`delete_status` = 0 LIMIT '.$offset.' ,'.$limit.') as `articles`';
				}

				if(isset($where)){
					$field = 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,users.user_uname,users_content.uc_pic,article_categories.cat_name,article_categories.id,articles_content.publish_status';
					$join  = array(
						'multiple',
							array(
								array('articles_content', 	'articles_content.article_id = articles.article_id','left'),
								array('users', 				'users.user_id = articles.ar_uid','left'),
								array('users_content', 		'users.user_id 	= users_content.uc_userid','left'),
								array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
							)
					);

					$order	= array('articles.article_id', 'desc');
					$order 	= '';


					$main_article = [];

					if (!empty($blog_id) && ($offset == 0 || $type == 'part')) {
						$main_article	= $this->DatabaseModel->select_data($field, $table, $where, '', $join, '' );
					}

					if (!empty($user_id) || !empty($categ) || !empty($tag) ) {
						$main_article	= $this->DatabaseModel->select_data($field, $table, $where, '', $join, $order);
					}

					if($offset==0){
						$offset	 = (isset($_POST['next_offset']) && !empty($_POST['next_offset'])) ? $_POST['next_offset'] : $offset;  // use only for next button
					}

					if(!empty($blog_id)){
						$c='';
						if($type == 'part'){
							$c= ' AND `article_type`= "slider"';
						}
						$table 	= '(SELECT articles.* FROM `articles` WHERE IF(`ar_uid` IN ('.implode(',',$arts_fids).') , `privacy_status` IN (6,7) , IF(`ar_uid` = '.$uid.' , `privacy_status` IN (5,6,7) , `privacy_status` = 7 ) )  AND `complete_status`= 1 AND `delete_status` = 0 AND  `articles`.`article_id` != '.$blog_id. $c. '  ORDER BY `articles`.`article_id` DESC LIMIT '.$offset.' , '.$limit.') as `articles`';

						$w = '';
						if($type != 'part'){
							$w = '`articles_content`.`order_` IN (0 ,1)';
						}
						$other_article 	= $this->DatabaseModel->select_data($field, $table, $w,'',$join,$order);
					}

					if(isset($other_article)){
						$articles = array_merge($main_article, $other_article);
					}else{
						$articles = $main_article;
					}

					$post_ids=[];
					foreach ($articles as $key => $value) {
						if($value['content_type'] == 'video'){
							$post_ids[] = (int) filter_var($value['content'], FILTER_SANITIZE_NUMBER_INT); 
						}
					}

					$vidlist=[];
					if(sizeof($post_ids) > 0){
						$vidlist = $this->getlist($post_ids);
					}

					if(sizeof($vidlist) > 0){
						foreach ($articles as $key => $value) {
							if($value['content_type'] == 'video'){
								foreach ($vidlist as $key2 => $value2) {
									if((int) filter_var($value['content'], FILTER_SANITIZE_NUMBER_INT) == $value2['post_id']){
										$articles[$key]['vidInfo'] = $value2;
									}
								}
							}
						}
					}
					
					$resp['data'] = $this->formatArticlesArray($articles);

					$this->statusCode = 1;
					if (count($resp['data']) > 0) {
						$this->statusType = 'Success';
						$this->respMessage = 'found';
					}else{
						$this->statusType = 'Empty';
						$this->respMessage = 'Not found';
					}


				}
			}
			$this->show_my_response($resp);


		}

	}

	public function getlist($post_ids){
		$post_ids = implode(',',$post_ids); // preg_replace("/[^0-9]/", "", (slugify($post_ids)));
		
		if(!empty($post_ids)){
			$data = [];
			$where 					= 'channel_post_video.post_id IN('.$post_ids.')';
		
			$field 					= 'channel_post_video.post_id,channel_post_video.user_id,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.post_key,channel_post_thumb.*'; 

			$join 					= array('multiple' , array(
										array(	'channel_post_thumb use INDEX(post_id)',
												'channel_post_thumb.post_id = channel_post_video.post_id',
												'left'),
									));

			$where 			.= ' AND channel_post_thumb.active_thumb = 1';
			
			$main_array 	= [];
			$single_videos 	= $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,'',$join,'');
			
			foreach($single_videos as $single_video){
				
				$post_id				= isset($single_video['post_id'])?$single_video['post_id']:0;
				$data['post_id'] 		= $post_id;
				
				$p_uid 					= isset($single_video['user_id'])?$single_video['user_id']:0;
				$iva_id 				= isset($single_video['iva_id'])?$single_video['iva_id']:0;
				$image_name 			= isset($single_video['image_name'])?$single_video['image_name']:'';
				$uploaded_video	 		= isset($single_video['uploaded_video'])?$single_video['uploaded_video']:'';
				$is_video_processed 	= isset($single_video['is_video_processed'])?$single_video['is_video_processed']:0;
				
				$FilterData			=	$this->share_url_encryption->FilterIva($p_uid,$iva_id,$image_name,trim($uploaded_video),true,'.m3u8',$is_video_processed);
				$videoFile 			= 	isset($FilterData['video'])?$FilterData['video']:'';
				
				$data['img'] 		= 	$FilterData['thumb'];
				$data['poster']		= 	isset($FilterData['webp'])?$FilterData['webp']:'';
				$data['sources'] 	= 	['src' => $videoFile , 'type' => $this->share_url_encryption->mime_type($videoFile)];

				$data['href'] = base_url($this->common->generate_single_content_url_param($single_video['post_key'], 2));
							
				$main_array[] = $data;
			}
			
		}
	
		return $main_array;
	}	

    // Article add or update step 1
	public function step1($id=''){

		$this->load->library('manage_session');
		
		if (!empty($this->uid)) {
			if ($id != '') { 
				$data['post_id'] = $id; 
			}else{ 
				$data['post_id'] = 0; 
			}

			$data['page_info'] = array('page'=>'blogs','title'=>'Create Article', 'post_id'=>$id);
			if ($id != 0) {
				$res = $this->validate_article_user($id);
				if (count($res) > 0 || $data['post_id'] == 0) {
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/blogs/create_blogs_1',$data);
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
				}else{
					redirect(base_url('articles'));
				}
			}else{
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/blogs/create_blogs_1',$data);
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);
				}
		}else{
			redirect(base_url('articles'));
		}

	}

	// Get article's step 1 data API
	public function get_step1_data()
	{
		$resp = array();

		$this->load->library('manage_session');
		$uid = $this->uid;

		if($this->input->is_ajax_request()){

			$this->form_validation->set_rules('post_id', 'Article Id', 'trim');

			if ($this->form_validation->run() == FALSE){
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}else{

				$res = $this->validate_article_user($this->input->post('post_id'));

				if (count($res) > 0){
					$resp['data'] = $res;
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Authenticated successfully';
				}else{
					$this->statusCode = 1;
					$this->statusType = 'Empty';
					$this->respMessage = 'No Data Found';
				}

			}
			$this->show_my_response($resp);
		}

	}

	// Article add or update step 1
	public function create_update_form1(){

		$resp = array();
		$this->load->library('manage_session');
		$uid = $this->uid;

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){

			$rule = array(
				array( 'field' => 'art_title', 'label' => 'Title', 'rules' => 'trim|required'),
				array( 'field' => 'art_category', 'label' => 'Category', 'rules' => 'trim|required'),
				array( 'field' => 'art_auth_name', 'label' => 'Blog author name', 'rules' => 'trim|required'),
				array( 'field' => 'art_tags', 'label' => 'Tags', 'rules' => 'trim|required'),
				array( 'field' => 'art_privacy_status', 'label' => 'Privacy', 'rules' => 'trim|required'),
				array( 'field' => 'art_article_type', 'label' => 'Type', 'rules' => 'trim|required'),
			);

			$this->form_validation->set_rules($rule);

			if($this->form_validation->run() == TRUE){

				$post = [
					'ar_title' 			=> $this->input->post('art_title'),
					'ar_slug' 			=> slugify($this->input->post('art_title')),
					'ar_tag' 			=> $this->input->post('art_tags'),
					'ar_category_id' 	=> $this->input->post('art_category'),
					'ar_author_name' 	=> $this->input->post('art_auth_name'),
					'privacy_status' 	=> $this->input->post('art_privacy_status'),
					'article_type' 		=> $this->input->post('art_article_type'),
					//'complete_status' 	=> 0,
					'ar_uid' 			=> $uid
				];

				$post_id['article_id'] = $this->input->post('post_id');

				if ($post_id['article_id'] == 0) {
					$post['ar_date_created']  	= date('Y-m-d H:i:s');
					// $post['ar_date_created']  	= $this->input->post('time_stamp');
					$resp['data'] = $this->DatabaseModel->access_database('articles', 'insert', $post);
				}
				else{
					$res = $this->DatabaseModel->access_database('articles', 'update', $post, $post_id);
					$resp['data'] = $post_id['article_id'];
				}

				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Data added';


			}
			else{

				$this->statusCode = 1;
				$this->statusType = 'Error';
				$this->respMessage = $this->common->form_validation_error()['message'];
			}

			$this->show_my_response($resp);
		}

	}

	// Article add or update step 2
	public function step2($id=''){

		$this->load->library('manage_session');

		if ($id != '') {
			$data['post_id'] = $id;
		}else{
			$data['post_id'] = 0;
		}
		if ($id != 0) {
			$article = $this->validate_article_user($id);

			if (count($article) > 0) {
				$data['article'] = $article[0];

				$data['page_info'] = array('page'=>'blogs','title'=>'Create Article');
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/blogs/create_blogs_2',$data);
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);
			}else{
				redirect(base_url('articles'));
			}
		}else{
			redirect(base_url('articles'));
		}


	}

	// API For getting blog updates on step2
	public function check_blog_updates()
	{
		$resp = [];

		$this->load->library('manage_session');

		if($this->input->is_ajax_request()) {

			$this->form_validation->set_rules('article_id', 'Article ID', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}else{

				$post['article_id'] = $this->input->post('post_id');

				$query = $this->DatabaseModel->access_database('articles_content', 'orderby', ['order_', 'asc'], $post);

				if (count($query) > 0) {
					$resp['data'] = $query;
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Data found';
				}else{
					$this->statusCode = 1;
					$this->statusType = 'Empty';
					$this->respMessage = 'Data not found !';
				}

			}

			$this->show_my_response($resp);
		}


	}

	// Validate user for article manipulation
	private function validate_article_user($article_id){
		if($article_id){
			$table 			= '(SELECT * FROM `articles` WHERE `ar_uid` = '.$this->uid.' AND `article_id` = '.$article_id.' ) as `articles`';

			$field 			= 'articles.*,article_categories.cat_name,article_categories.id';
	
			$join  			= array('article_categories', 'article_categories.id = articles.ar_category_id','left');
	
			$order 			= array('articles.views', 'desc');
	
			$res			= $this->DatabaseModel->select_data($field,$table,'','',$join,$order);
	
			return $res;
		}else{
			return [];
		}
	
	}
	// Get all Categories API for step 1
	public function getCategory(){
		$resp['data'] = $this->DatabaseModel->select_data('article_categories.id,article_categories.cat_name' , 'article_categories', ['status' => 1] , $limit = '' , '', ['category_order' , 'asc'] );
		if (count($resp['data']) > 0){
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = 'Category Updated Successfully.';
		}else{
			$this->statusType = 'Empty';
			$this->respMessage = 'No Categories found !';
		}
		$this->show_my_response($resp);
	}

	public function insertUpdatePara(){
		$this->load->library('manage_session');
		$resp = [];
		$uid = $this->uid;

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1){

			$this->form_validation->set_rules('content', 'Publish Input', 'trim');

			if ($this->form_validation->run() == FALSE){
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}
			else{
				$post = ['article_id' => $this->input->post('art_id'), 'user_id' => $uid, 'content_type' => 'ckeditor', 'content' => base64_decode($this->input->post('content')), 'plain_content' => htmlToPlainText(base64_decode($this->input->post('content'))) ];

				if ($this->input->post('db_id') == 0) {

					$post['order_'] = $this->input->post('order');
					$db_id = $this->DatabaseModel->access_database('articles_content', 'insert', $post);

				}else{

					$data['id'] = $this->input->post('db_id');
					$status = $this->DatabaseModel->access_database('articles_content', 'update', $post, $data);

					if ($status === 1) {
						$db_id = $data['id'];
					}else{
						$db_id = $status;
					}

				}
				$resp = ['input_id' => $this->input->post('input_id'), 'counter' => $this->input->post('counter'), 'content_length' => $this->input->post('length'), 'db_id' => $db_id ];

				$this->statusCode = 1;
				if ($db_id >= 1) {
					$this->statusType = 'Success';
					$this->respMessage = 'Data Inserted/Updated';
				}else{
					$this->statusType = 'Empty';
					$this->respMessage = 'Data not Inserted/Updated';
				}
			}
			$this->show_my_response($resp);

		}

	}

	public function update_sorting(){

		$resp = array();

		$this->load->library('manage_session');
		$uid = $this->uid;


		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){

			$this->form_validation->set_rules('db_id', 'Article ID', 'trim');
			$this->form_validation->set_rules('order_', 'Article ID', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}
			else{

				$data 	= [];
				$c 		= 0;
				$id_str 	= $this->input->post('db_id');
				$or_str 	= $this->input->post('order_');
				if ($id_str != '' && $or_str != '') {

					$id_arr		= explode(",",$id_str);
					$or_arr		= explode(",",$or_str);

					foreach ($or_arr as $key => $value) {
						$data[$key]['id']    		= $id_arr[$value];
						$data[$key]['order_']    	= $value;
						$c ++;
					}

					$res = $this->db->update_batch('articles_content', $data, 'id');
				}

				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Sorting Updated';

			}

			$this->show_my_response();


		}



	}

	public function upload_image(){



		$this->load->library('manage_session');
		$uid = $this->uid;


		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){
			$this->form_validation->set_rules('author', 'img author', 'trim');
			$this->form_validation->set_rules('publisher', 'img publisher', 'trim');
			$this->form_validation->set_rules('license_id', 'img license', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}
			else{

				$upload_path = './uploads/aud_'.$this->uid.'/images/';
				$data  = ['db_status' => '', 'aws_status' => '' ];
				$config = array(
					'upload_path' 	=> $upload_path,
					'allowed_types' => 'jpg|jpeg|png|gif',
					'max_size' 		=> '11000',
					'remove_spaces' => TRUE,
					'encrypt_name' 	=> TRUE,
				);

				$this->load->library('upload',$config);
				$this->load->library('image_lib');
				$resp = [];

				if(! $this->upload->do_upload('file')){
					$this->statusType = 'error';
					$this->respMessage = strip_tags($this->upload->display_errors()) ;
				}else{
					$uploadData 	= $this->upload->data();
					$filename 		= $uploadData['file_name'];

					$this->audition_functions->resizeImage('1060','0',
					$upload_path.$filename,'',$maintain_ratio = TRUE,$create_thumb= FALSE,55);

					$json_data      = ['author' => $this->input->post('author'), 'publisher' => $this->input->post('publisher'), 'license_id' => $this->input->post('license_id') ];

					$post 	     	= ['article_id' => $this->input->post('article_id'), 'user_id' => $uid, 'content_type' => 'image', 'content' => 'aud_'.$uid.'/images/'.$filename, 'plain_content' => 0, 'image_data' => json_encode($json_data), 'order_' => $this->input->post('order_') ];

					$data['db_status'] 		= $this->DatabaseModel->access_database('articles_content', 'insert', $post);
					$where['id'] 			= $data['db_status'];

					$img = $this->DatabaseModel->access_database('articles_content', 'select', '', $where);

					$resp = ['db_id' => $data['db_status'], 'img_src' => $img[0]['content'], 'input_id' => $this->input->post('input_id') ];

					// converting webp and thumbnail start //
					$this->load->library('convert_image_webp');

					if(file_exists($upload_path.$filename))
					$this->convert_image_webp->convertIntoWebp($upload_path.$filename);
					//'294','217',
					$this->audition_functions->resizeImage('417','417',
					$upload_path.$filename,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);

					$img = explode('.',$filename);

					$path = $upload_path.$img[0].'_thumb.'.$img[1];

					if(file_exists($path))
					$this->convert_image_webp->convertIntoWebp($path);
					// converting web and thumbnail end //

					$data['aws_status'] = upload_all_images($this->uid);

					if ($data['aws_status'] == 1 && $data['db_status'] > 0) {
						$this->statusType = 'Success';
						$this->respMessage = 'Data found';

					}else{
						$this->statusType = 'Empty';
						$this->respMessage = 'Data not found !';

					}
				}


				$this->statusCode = 1;


			}

			$this->show_my_response($resp);

		}


	}

	public function remove_object(){

		$this->load->library('manage_session');
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){

			$this->form_validation->set_rules('db_id', 'db id', 'trim');
			$this->form_validation->set_rules('key', 'key', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$where['id'] 	= $this->input->post('db_id');
				$key 			= $this->input->post('key');

				$delete_status = $this->DatabaseModel->access_database('articles_content', 'delete', '', $where);

				if ($key != 0 || !empty($key)) {

					$key_ar = explode('/', $key);
					if( $key_ar[0] != 'embedcv' ){

						$pathinfo = pathinfo($key);
						$key1  = $key;
						$key2  = $key.'.webp';
						$key3  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'];
						$key4  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'].'.webp';

						$s3_status = s3_delete_object( array( $key1, $key2, $key3, $key4 ) );
					}

				}

				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				$this->respMessage 	= 'Element Deleted Successfully';

			}
			$this->show_my_response();

		}

	}

	public function post_article(){

		$this->load->library('manage_session');
		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){

			$this->form_validation->set_rules('post_id', 'Article Id', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$where['article_id'] 		= $this->input->post('post_id');
				$data['complete_status'] 	= 1;

				$content	= $this->DatabaseModel->select_data('plain_content,content_type','articles_content',$where);
				$reading_time = 0;
				$total_seconds = 0;

				foreach ($content as $key => $value) {
					if ($value['content_type'] == 'ckeditor') {
						$ret_arr = estimateReadingTime($value['plain_content']);
						$total_seconds += $ret_arr['total_seconds'];
					}else{
						$ret_arr = estimateReadingTime($value['plain_content'], 0.10);
						$total_seconds += $ret_arr['total_seconds'];
					}
				}
				$reading_time = round($total_seconds / 60);

				$data['ar_read_time'] = ($reading_time==0)?1:$reading_time;

				$this->DatabaseModel->access_database('articles', 'update', $data, $where);

				$this->DatabaseModel->access_database('articles_content', 'update', array('publish_status'=>1), $where);

				$resp['data'] = $this->DatabaseModel->select_data('ar_slug,article_id','articles',$where);

				$resp['data'][0]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($resp['data'][0]['article_id'] , 'encode');

				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				$this->respMessage 	= 'Article Updated Successfully';

			}
			$this->show_my_response($resp);

		}
	}

	public function upload_url_s3(){
		$this->load->library('manage_session');
		$uid = $this->uid;

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){
			$this->form_validation->set_rules('author', 'img author', 'trim');
			$this->form_validation->set_rules('publisher', 'img publisher', 'trim');
			$this->form_validation->set_rules('license_id', 'img license', 'trim');

			if ($this->form_validation->run() == FALSE){
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}else
			{
				$url = $this->input->post('src');

				$ext 		= pathinfo($url, PATHINFO_EXTENSION);
				$filename 	= rand().'.'.$ext;
				$path 		= './uploads/aud_'.$this->uid.'/images/';
				$file 		= file_put_contents($path.$filename, file_get_contents($url));

				// converting webp and thumbnail start //
				$this->load->library('convert_image_webp');

				if(file_exists($path.$filename))
				$this->convert_image_webp->convertIntoWebp($path.$filename);
				//'294','217',
				$this->audition_functions->resizeImage('417','417',
				$path.$filename,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);

				$img = explode('.',$filename);

				$path = $path.$img[0].'_thumb.'.$img[1];

				if(file_exists($path)) $this->convert_image_webp->convertIntoWebp($path);
				// converting web and thumbnail end //

				if ($file != false) {
					$aws_status = upload_all_images($this->uid);

					if ($aws_status == 1) {
						$json_data  = [
							'author' 		=> $this->input->post('author'),
							'publisher' 	=> $this->input->post('publisher'),
							'license_id'	=> $this->input->post('license_id')
						];

						$post 	=	[
							'article_id' 	=> $this->input->post('article_id'),
							'user_id' 		=> $uid,
							'content_type' 	=> 'image',
							'content' 		=> 'aud_'.$this->uid.'/images/'.$filename,
							'plain_content' => 0,
							'image_data' 	=> json_encode($json_data),
							'order_' 		=> $this->input->post('order_')
						];

						$data['db_status'] 	= $this->DatabaseModel->access_database('articles_content', 'insert', $post);

						$where['id'] 		= $data['db_status'];

						$img 				= $this->DatabaseModel->access_database('articles_content', 'select', '', $where);

						$resp 				= [
							'db_id' 	=> $data['db_status'],
							'img_src' 	=> $img[0]['content'],
							'input_id' 	=> $this->input->post('input_id')
						];

						$this->statusCode 	=  1;
						$this->statusType 	= 'Success';
						$this->respMessage 	= 'Article Uploaded Successfully';

					}else{
						$this->statusCode 	=  1;
						$this->statusType 	= 'Error';
						$this->respMessage 	= 'Not uploaded to aws';
					}

				}else{
					$this->statusCode 	=  1;
					$this->statusType 	= 'Error';
					$this->respMessage 	= 'Not uploaded to server';
				}

			}
			$this->show_my_response($resp);
		}
	}

	public function articleViewCount(){

		// $this->load->library('manage_session');
		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){

			$this->form_validation->set_rules('post_id', 'Article Id', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$where['article_id'] 		= $this->input->post('post_id');
				$where['complete_status'] 	= 1;
				$where['delete_status'] 	= 0;

				$row = $this->DatabaseModel->access_database('articles', 'select', '', $where);

				if (count($row) > 0) {
					$data['views'] = $row[0]['views'] + 1;
					$this->DatabaseModel->access_database('articles', 'update', $data, $where);
				}
			}

		}


	}

	public function getMostPopularData(){


		if( $this->input->is_ajax_request() ){

			$where  		= ['articles_content.order_' => 0];
			$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,users.user_name,users_content.uc_pic,article_categories.cat_name,articles_content.publish_status';
			$join  			= array(
									'multiple',
										array(
											array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
											array('users' , 'users.user_id = articles.ar_uid','left'),
											array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
											array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
										)
									);
			$order 			= array('articles.views', 'desc');
			$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 ORDER BY `views` DESC LIMIT 0 , 4) as `articles`';

			$popular_articles	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);
			$resp = [];

			$this->statusCode = 1;

			if (count($popular_articles) > 0) {

				foreach($popular_articles as $key => $article){
					$popular_articles[$key]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($article['article_id'] , 'encode');
				}

				$this->statusType 	= 'Success';
				$this->respMessage 	= 'found';
				$resp['data'] 		= $popular_articles;

			}else{
				$this->statusType 	= 'Empty';
				$this->respMessage 	= 'Not found';
			}

			$this->show_my_response($resp);

		}


	}

	public function getBlogCategoryTops(){


		if( $this->input->is_ajax_request() ){

			$this->form_validation->set_rules('blog_id', 'Article Id', 'trim');
			$this->form_validation->set_rules('categ_by', 'categ_by', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$id 				= isset($_POST['blog_id'])?$_POST['blog_id']:0;
				$categ_by 			= isset($_POST['categ_by'])?$_POST['categ_by']:0;
				$cond               = '';
				if ($categ_by != 'by_categ_name') {
					$cat 			= getBlogCategoryById($id, 'by_article_id');
					$cond           = 'articles.ar_category_id';
				}
				else{
					$cat 			= $id;
					$cond           = 'article_categories.cat_name';
				}

				$data 			= array();

				if ($cat != '') {
					$where  		= [ $cond => $cat , 'articles.complete_status' => 1, 'articles.delete_status' => 0, 'articles.privacy_status' => 7];
					$field 			= 'articles.*,users.user_name,article_categories.cat_name';
					$limit 			= array(5, 0);
					$join  			= array(
											'multiple',
												array(
													array('users' , 'users.user_id = articles.ar_uid','left'),
													array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
												)
											);
					$order 			= array('articles.views', 'desc');

					$data = $this->DatabaseModel->select_data($field , 'articles', $where , $limit , $join, $order);

				}
				$resp = [];

				$this->statusCode = 1;

				if (count($data) > 0) {

					foreach($data as $key => $article){
						$data[$key]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($article['article_id'] , 'encode');
					}

					$this->statusType 	= 'Success';
					$this->respMessage 	= 'found';
					$resp 				= ['data' => $data, 'category' => $data[0]['cat_name']];

				}else{
					$this->statusType 	= 'Empty';
					$this->respMessage 	= 'Not found';
				}

			}
			$this->show_my_response($resp);

		}


	}

	public function preview($id = ''){

		$this->load->library('manage_session');
			if ($id != 0) {
				$res = $this->validate_article_user($id);

				if (count($res) > 0) {
					$data['page_info'] = array('page'=>'single_blog','title'=>'Preview');
					$this->load->view('home/inc/header',$data);
					$this->load->view('home/blogs/preview_blog',$data);
					$this->load->view('common/notofication_popup');
					$this->load->view('home/inc/footer',$data);

				}else{
					redirect(base_url('articles'));
				}

			}else{
				redirect(base_url('articles'));
			}
		}

	
		public function previewBlogData(){

		$this->load->library('manage_session');

		if($this->input->is_ajax_request() ){

			$this->form_validation->set_rules('blog_id', 'Article Id', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$where  		= ['articles.article_id' => $this->input->post('blog_id')];
				$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,users.user_name,users_content.uc_pic,article_categories.cat_name';
				$join  			= array(
										'multiple',
											array(
												array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
												array('users' , 'users.user_id = articles.ar_uid','left'),
												array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
												array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
											)
										);
				$order 			= array('articles_content.order_', 'asc');
				$table 			= 'articles';

				$prev_article	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);

				$this->statusCode = 1;

				if ( count($prev_article) > 0 ) {
					$uid 					= $prev_article[0]['ar_uid'];
					$resp['user_name'] 		= $prev_article[0]['user_name'];
					$resp['title'] 			= $prev_article[0]['ar_title'];
					$resp['category'] 		= $prev_article[0]['cat_name'];
					$resp['user_image'] 	= AMAZON_URL . 'aud_' . $uid . '/images/' . $prev_article[0]['uc_pic'];
					$resp['time_updated'] 	= date("j F Y",strtotime($prev_article[0]['ar_date_created']));
					$resp['data']	 	 	= $prev_article;

					$this->statusType 	= 'Success';
					$this->respMessage 	= 'found';
				}else{
					$this->statusType 	= 'Empty';
					$this->respMessage 	= 'Not found';
				}

			}
			$this->show_my_response($resp);

		}


	}

	public function delete_article_post(){

		$this->load->library('manage_session');
		$TokenResponce = $this->creator_jwt->MatchToken();

		if( $TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){

			$resp = [];
			$rule = array(
				array( 'field' => 'article_id', 'label' => 'article id', 'rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rule);
			if($this->form_validation->run()){

				$article_id = $this->input->post('article_id');

				$where['article_id'] = $article_id;
				$where['content_type'] = 'image';

				$field = 'articles_content.content_type,articles_content.content,articles_content.plain_content';
				$article_content	= $this->DatabaseModel->select_data($field,'articles_content',$where);

				if(!empty($article_content)){
					$keys = [];
					foreach($article_content as $arti){
						$key = $arti['content'];
						if ($key != 0 || !empty($key)) {
							$key_ar = explode('/', $key);
							if( $key_ar[0] != 'embedcv' ){

								$pathinfo = pathinfo($key);
								$keys[]  = $key;
								$keys[]  = $key.'.webp';
								$keys[]  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'];
								$keys[]  = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_thumb'.'.'.$pathinfo['extension'].'.webp';
							}
						}
					}
					$s3_status  = s3_delete_object($keys);
				}
				unset($where['content_type']);
				$delete_status  = $this->DatabaseModel->access_database('articles_content', 'delete', '', $where);
				$delete_status1 = $this->DatabaseModel->access_database('articles', 'delete', '', $where);
				$this->deleteArticleFromHomesliders($article_id);
				$this->deleteArticleFromCoverVideo($article_id);

				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Article deleted successfully';
			}else{
				$this->respMessage = $this->validation_error();
			}

			$this->show_my_response($resp);
		}
	}

	private function deleteArticleFromHomesliders($article_id=''){
		if(!empty($article_id)){
			$sliders= $this->DatabaseModel->select_data('id,data','homepage_sliders',['mode'=> 10]);
			if(!empty($sliders)){
				$updateArray = [];
				foreach($sliders as $s){
					$article_ids =  $s['data'];
					$id = $s['id'];
					if(!empty($article_ids)){
						$aids =  explode(',' , $article_ids);
						$key = array_search($article_id, $aids);
						if($key !==FALSE){

							unset($aids[$key]);

							$v = array_values($aids);

							$updateArray[] = array(
													'id'	=>$id,
													'data'	=>	implode(',',$v)
												);
						}
					}
				}
				if(!empty($updateArray)){
					$this->db->update_batch('homepage_sliders',$updateArray, 'id');
					//print_r($updateArray);die;
				}
			}
		}
	}

	private function deleteArticleFromCoverVideo($article_id=''){
		if(!empty($article_id)){
			$sliders= $this->DatabaseModel->select_data('id,cover_video','page_setting',['website_mode'=> 10]);

			if(!empty($sliders)){
				$updateArray = [];
				foreach($sliders as $s){
					$article_ids =  $s['cover_video'];
					$id = $s['id'];
					if(!empty($article_ids)){
						$aids =  explode(',' , $article_ids);
						$key = array_search($article_id, $aids);
						if($key !==FALSE){

							unset($aids[$key]);

							$v = array_values($aids);

							$updateArray[] = array(
													'id'	=>$id,
													'cover_video'	=>	implode(',',$v)
												);
						}
					}
				}
				if(!empty($updateArray)){
					$this->db->update_batch('page_setting',$updateArray, 'id');
					//print_r($updateArray);die;
				}
			}
		}
	}

	public function getAllCategories(){  // sidebar

		if( $this->input->is_ajax_request() ){

			$limit 				= array(4, 0);
			$this->DatabaseModel->select_data('*' , 'article_categories', ['status' => 1] , $limit , '', ['category_order' , 'asc'] );
			$table				= '('. $this->db->last_query() .') as `article_categories`';
			$field 				= 'COUNT(article_categories.id) as total,article_categories.id,articles.ar_category_id,article_categories.cat_name,article_categories.cat_img,article_categories.category_order';
			$join  				= array(
									'multiple',
										array(
											array('articles', 'article_categories.id = articles.ar_category_id','left'),
										)
									);
			$where	            = array('articles.complete_status' => 1, 'articles.delete_status' => 0, 'articles.privacy_status' => 7);
			$data 				= $this->DatabaseModel->select_data($field,$table,$where,'',$join, ['article_categories.category_order' , 'asc'],'','article_categories.id' );

			$resp['data'] = $data;

			$this->statusCode 	= 1;
			$this->statusType 	= 'Success';
			$this->respMessage 	= 'found';
			$this->show_my_response($resp);

		}

	}

	// *************************  FOR Article Mode Functions start ************************ //

	public function getBlockOneData(){ // $categ = 0 means for all category

		if ($this->input->is_ajax_request()) {

			$this->form_validation->set_rules('category_id', 'Article Id', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}else{
				$categ = !empty($this->input->post('category_id')) ?$this->input->post('category_id'):0;

				$categ_condition = '';

				if ($categ != 0) {
					$categ_condition = "AND `ar_category_id` = " . "'" . $categ . "'";
				}

				$where  		= ['articles_content.order_' => 0];

				$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 '.$categ_condition.' ORDER BY `article_id` DESC LIMIT 0 , 10) as `articles`';

				$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,users.user_name,users_content.uc_pic,article_categories.cat_name,articles_content.publish_status';

				$join  			= array(
										'multiple',
											array(
												array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
												array('users' , 'users.user_id = articles.ar_uid','left'),
												array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
												array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
											)
										);

				// $order 			= array('articles.views', 'desc');

				$get_articles	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order='');

				$top_mid_slider = [];

				foreach($get_articles as $key => $article){
					$get_articles[$key]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($article['article_id'] , 'encode');
				}

				if ($categ == 0) { // $categ = 0 means for all category

					$cond		=	array('website_mode' => 10);

					$res		= 	$this->DatabaseModel->select_data('page_setting.cover_video','page_setting',$cond,'','', '');

					if (count($res) > 0) {

						$data_arr=  explode(',', $res[0]['cover_video']);

						$field 		= '*';

						$join_array = 'article_id';

						$data       =	$this->DatabaseModel->access_database('articles', 'wherein', $data_arr, '', $join_array);
						$q = '('. $this->db->last_query(). ') as `articles`';

						$table 		= $q;

						$field 		= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name,articles_content.publish_status';

						$join  		= array(
											'multiple',
												array(
													array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
													array('users' , 'users.user_id = articles.ar_uid','left'),
													array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
												)
											);
						$order		= '';

						$where  	= ['articles.privacy_status' => 7,'articles_content.order_' => 0];

						$top_mid_slider_t = $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);

						$top_mid_slider = $this->formatArticlesArray($top_mid_slider_t);
				}
			}

			$resp = [];
			$this->statusCode 	= 1;

			if ( count($get_articles) > 0 ) {
				$this->statusType 		  = 'Success';
				$this->respMessage 		  = 'found';
				$resp['blockOneArticles'] = $get_articles;
				$resp['top_mid_slider']   = $top_mid_slider;
			}
			else{
				$this->statusType 	= 'Empty';
				$this->respMessage 	= 'Not found';
			}
			}

			$this->statusCode 	= 1;
			$this->show_my_response($resp);

		}
	}

	public function getRecommendedArticle(){

		if ($this->input->is_ajax_request()) {

			$get_type = $this->session->userdata('mode');
			$u_b_id = $this->session->userdata('u_b_id');

			$resp = [];
			$this->statusCode 	= 1;

			if ($get_type != '' && $get_type != null) {
				$categ_condition = '';

				if ($get_type == 'blogs_by_username') {
					$where['users.user_uname'] = $u_b_id;
				}

				else if ($get_type == 'blogs_by_category_id') {
					$categ = getBlogCategoryById($u_b_id, 'by_category_id');
					$categ_condition = 'AND `ar_category_id` ='. $categ;
				}

				else if ($get_type == 'blogs_by_id') {
					$categ = getBlogCategoryById($u_b_id, 'by_article_id');
					$categ_condition = 'AND `ar_category_id` ='. $categ;
				}

				$where['articles_content.order_']  	= 0;

				$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 '.$categ_condition.' ORDER BY `views` DESC LIMIT 0 , 10) as `articles`';

				$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,users.user_name,users_content.uc_pic,article_categories.cat_name';

				$join  			= array(
										'multiple',
											array(
												array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
												array('users' , 'users.user_id = articles.ar_uid','left'),
												array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
												array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
											)
										);

				$order 			= array('articles.ar_date_created', 'asc');

				$recom_artcles	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);

				foreach ($recom_artcles as $key => $value) {

					if(!is_session_uid($value['ar_uid'])){   /* FOR OTHER USER	*/
						$AmIFanOfHim = AmIFollowingHim($value['ar_uid']);

						if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
							$recom_artcles[$key]['ifh'] = 1;	/* PRIVATE,PUBLIC*/
						}else{
							$recom_artcles[$key]['ifh'] = 0;	/* ONLY PUBLIC*/
						}

					}else if($this->uid == $value['ar_uid']){ /* FOR ME */
						$recom_artcles[$key]['ifh'] = 3;
					}

				}

				if ( count($recom_artcles) > 0 ) {
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'found';
					$resp['recom_artcles'] = $recom_artcles;
				}
				else{

					$this->statusType 	= 'Empty';
					$this->respMessage 	= 'Not found';
				}


			}else{
				$this->statusType 	= 'Empty';
				$this->respMessage 	= 'Not found';
			}
			$this->show_my_response($resp);

		}
	}


	public function getAdminSidebarSlider(){
		if ($this->input->is_ajax_request()) {

			$this->form_validation->set_rules('category_id', 'Category Id', 'trim');
			$this->form_validation->set_rules('order', 'Order', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];
				$this->statusCode = 1;

			}else{
				$categ_id = !empty($this->input->post('category_id')) ?$this->input->post('category_id'):0;
				$order 	  = !empty($this->input->post('order')) ?$this->input->post('order'):0;


				$uid = $this->uid;
				$resp = [];

				$fields 	= 	array(	'slider_title',
									'type',
									'mode',
									'data',
									'status',
									'slider_order',
									'user_id',
									'id',
									'genre',
									'is_sidebar_slider',
								);

				if ($categ_id == 0) {
					$condition = ' (`genre` = 0 AND `type` != "top_in" ) ';
				}else{
					$condition = ' (`genre` = '.$categ_id.' OR `type` = "categories" OR `type` = "top_in" ) ';
				}

				if ($order != 0) { $order += 1; }
				$limit      =   array(2, $order);
				$cond		=	array($fields[2] => 10, $fields[4] => 1, $fields[9] => 1);
				$order_home	=	array($fields[5] , 'ASC');

				$this->db->where($condition);
				$res		= 	$this->DatabaseModel->select_data($fields, 'homepage_sliders', $cond, $limit, '', $order_home);

				if (count($res) > 0) {
					$main_arr = array();

					foreach ($res as $i => $value) {
						$slider_title    	= $value['slider_title'];
						if ($slider_title == 'MOST POPULAR') {

							$where  		= ['articles_content.order_' => 0];
							$field 			= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_date_created,articles_content.content_type,articles_content.content,users.user_name,article_categories.cat_name,articles_content.publish_status';
							$join  			= array(
													'multiple',
														array(
															array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
															array('users' , 'users.user_id = articles.ar_uid','left'),
															array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
														)
													);
							$order 			= array('articles.views', 'desc');
							$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 ORDER BY `views` DESC LIMIT 0 , 4) as `articles`';

							$res_arr		= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);

							foreach($res_arr as $key => $article){
								$res_arr[$key]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($article['article_id'] , 'encode');
							}

						}else

						if ($slider_title == 'TOP IN') {
							if ($categ_id == 0) { $categ_id = 1; }

							$where  		= ['articles.ar_category_id' => $categ_id, 'articles.complete_status' => 1, 'articles.delete_status' => 0, 'articles.privacy_status' => 7];
							$field 			= 'articles.article_id,articles.ar_title,articles.ar_slug,users.user_name,article_categories.cat_name';
							$limit 			= array(5, 0);
							$join  			= array(
													'multiple',
														array(
															array('users' , 'users.user_id = articles.ar_uid','left'),
															array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
														)
													);
							$order 			= array('articles.views', 'desc');

							$res_arr 		= $this->DatabaseModel->select_data($field , 'articles', $where , $limit , $join, $order);

							foreach($res_arr as $key => $article){
								$res_arr[$key]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($article['article_id'] , 'encode');
							}

						}else

						if ($slider_title == 'CATEGORIES') {

							$limit 				= array(4, 0);
							$this->DatabaseModel->select_data('*' , 'article_categories', ['status' => 1] , $limit , '', ['category_order' , 'asc'] );
							$table				= '('. $this->db->last_query() .') as `article_categories`';
							$field 				= 'COUNT(article_categories.id) as total,article_categories.id,articles.ar_category_id,article_categories.cat_name,article_categories.cat_img,article_categories.category_order';
							$join  				= array('articles', 'article_categories.id = articles.ar_category_id','left');
							$where	            = array('articles.complete_status' => 1, 'articles.delete_status' => 0, 'articles.privacy_status' => 7);
							$data 				= $this->DatabaseModel->select_data($field,$table,$where,'',$join, ['article_categories.category_order' , 'asc'],'','article_categories.id' );

							$res_arr = $data;
						}else

						{
							$where  		= ['articles_content.order_' => 0];
							$field 			= 'articles.article_id,articles.ar_title,articles.ar_slug,articles.ar_date_created,articles_content.content_type,articles_content.content,users.user_name,article_categories.cat_name,articles_content.publish_status';
							$join  			= array(
													'multiple',
														array(
															array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
															array('users' , 'users.user_id = articles.ar_uid','left'),
															array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
														)
													);
							$order 			= array('articles.views', 'desc');
							$table 			= '(SELECT * FROM `articles` WHERE `article_id` IN ('.$value['data'].') AND `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 ORDER BY `views` DESC LIMIT 0 , 4) as `articles`';

							$res_arr		= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order);

							foreach($res_arr as $key => $article){
								$res_arr[$key]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($article['article_id'] , 'encode');
							}
						}

						$main_arr[] = ['slider_data' => $res_arr, 'slider_title' => $slider_title];

					}




					$resp = [];


					$this->statusCode = 1;
					// print_r($res_arr);
					// die();
					if (count($main_arr) > 0) {
						$this->statusType 			= 'Success';
						$this->respMessage 			= 'found';
						$resp['data'] 				= $main_arr;

					}else{
						$this->statusType 			= 'Empty';
						$this->respMessage 			= 'Not found';
					}

			}else{
				$this->statusCode = 1;
			}


		}

		$this->show_my_response($resp);



		}
	}

	public function getAdminSlider(){
		if ($this->input->is_ajax_request()) {

			$this->form_validation->set_rules('category_id', 'Category Id', 'trim');
			$this->form_validation->set_rules('order', 'Order', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];
				$this->statusCode = 1;

			}else{
				$categ_id = !empty($this->input->post('category_id')) ?$this->input->post('category_id'):0;
				$order 	  = !empty($this->input->post('order')) ?$this->input->post('order'):0;


				$uid = $this->uid;
				$resp = [];

				$mode		=  mode();

				$fields 	= 	array(	'slider_title',
									'type',
									'mode',
									'data',
									'status',
									'slider_order',
									'user_id',
									'id',
									'genre',
									'is_sidebar_slider',
								);
				if ($categ_id == 0) {
					$condition = ' (`genre` = 0 AND `type` != "top_in_category") ';
				}else{
					$condition = ' (`genre` = '.$categ_id.' OR `type` = "latest_articles" OR `type` = "top_in_category" ) ';
				}

				$limit      =   array(1, $order);
				$cond		=	array($fields[2] => 10, $fields[4] => 1, $fields[9] => 0);
				$order_home	=	array($fields[5] , 'ASC');
				$this->db->where($condition);
				$res		= 	$this->DatabaseModel->select_data($fields, 'homepage_sliders', $cond, $limit, '', $order_home);


				if (count($res) > 0) {
					$slider_title    	= $res[0]['slider_title'];
					$data_arr			=  explode(',', $res[0]['data']);
					$cond               = '';

					if ($slider_title == 'LATEST ARTICLES') {

						if ( $categ_id != 0 ) { $cond = 'AND `ar_category_id` ='.$categ_id; }

						$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,users_content.uc_pic,article_categories.cat_name,articles_content.publish_status';

						$join  			= array(
												'multiple',
													array(
														array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
														array('users' , 'users.user_id = articles.ar_uid','left'),
														array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
														array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
													)
												);
						$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 '.$cond.' ORDER BY `article_id` DESC LIMIT 0 , 10) as `articles`';
						$picked_arts	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`order_` IN (0 ,1)','',$join,$order=['articles.article_id', 'desc']);

						$formated_   	= $this->formatArticlesArray($picked_arts);

					}
					else if ($slider_title == 'MOST POPULAR ARTICLES') {

						$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,users_content.uc_pic,article_categories.cat_name,articles_content.publish_status';

						$join  			= array(
												'multiple',
													array(
														array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
														array('users' , 'users.user_id = articles.ar_uid','left'),
														array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
														array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
													)
												);
						$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 ORDER BY `views` DESC LIMIT 0 , 10) as `articles`';
						$picked_arts	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`order_` IN (0 ,1)','',$join,$order=['articles.views desc', 'articles.article_id desc']);

						$formated_   	= $this->formatArticlesArray($picked_arts);


					}
					else if ($slider_title == 'TOP IN CATEGORY') {

						if ($categ_id == 0) {
							$picked_arts	= [];
						}else{
							$cond = 'AND  `ar_category_id` = '.$categ_id;
							$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,users_content.uc_pic,article_categories.cat_name,articles_content.publish_status';

							$join  			= array(
													'multiple',
														array(
															array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
															array('users' , 'users.user_id = articles.ar_uid','left'),
															array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
															array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
														)
													);
							$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 '.$cond.' ORDER BY `views` DESC LIMIT 0 , 10) as `articles`';
							$picked_arts	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`order_` IN (0 ,1)','',$join,$order=['articles.views desc', 'articles.article_id desc']);

							$formated_   	= $this->formatArticlesArray($picked_arts);
						}



					}
					else if ($slider_title == 'GLOBAL TOP TEN') {

						$cond			=	array('website_mode' => 10);

						$res			= 	$this->DatabaseModel->select_data('page_setting.cover_video','page_setting',$cond,'','', '');
						$data_arr		=  explode(',', $res[0]['cover_video']);

						$field 			= '*';

						$join_array 	= 'article_id';

						$data       	=	$this->DatabaseModel->access_database('articles', 'wherein', $data_arr, '', $join_array);
						$q 				= '('. $this->db->last_query(). ') as `articles`';

						$table 			= $q;

						$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.order_,articles_content.content,articles_content.plain_content,users.user_name,article_categories.cat_name,articles_content.publish_status';

						$join  			= array(
											'multiple',
												array(
													array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
													array('users' , 'users.user_id = articles.ar_uid','left'),
													array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
												)
											);

						$picked_arts 	= $this->DatabaseModel->select_data($field,$table,$where='`articles_content`.`order_` IN (0 ,1)','',$join,$order=['articles.views desc', 'articles.article_id desc']);


						$formated_   	= $this->formatArticlesArray($picked_arts);

					}
					else if($slider_title == 'RECOMMENDED FOR YOU'){
						$get_type = $this->session->userdata('mode');
						$u_b_id = $this->session->userdata('u_b_id');

						$field 			= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,users_content.uc_pic,article_categories.cat_name,articles_content.publish_status';

						$join  			= array(
												'multiple',
													array(
														array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
														array('users' , 'users.user_id = articles.ar_uid','left'),
														array('users_content', 'users.user_id 	= users_content.uc_userid','left'),
														array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
													)
												);

						if ($get_type != '' && $get_type != null)
						{
								$categ_condition = '';

								if ($get_type == 'blogs_by_username') {
									$categ = 'null';
									$categ_condition = 'AND `users`.`user_uname` = "'. $u_b_id .'"';
								}
								else if ($get_type == 'blogs_by_category_id') {
									$categ = 'null';
									$categ_condition = 'AND `article_categories`.`cat_name` = "'. $u_b_id .'"';
								}
								else if ($get_type == 'blogs_by_id') {
									$categ = getBlogCategoryById($u_b_id, 'by_article_id');
									$categ_condition = 'AND `ar_category_id` ='. $categ;
								}

								if ($categ_condition == '' || $categ == '') {
									$categ_condition = 'AND `ar_category_id` = 6';
								}

								$where['articles_content.order_'] = 0;

								$table 			= '(SELECT * FROM `articles` LEFT JOIN `users` ON `users`.`user_id` = `articles`.`ar_uid` LEFT JOIN `article_categories` ON `article_categories`.`id` = `articles`.`ar_category_id` WHERE `articles`.`complete_status` = 1 AND `articles`.`delete_status` = 0 AND `articles`.`privacy_status` = 7 '.$categ_condition.' ORDER BY `articles`.`article_id` DESC LIMIT 0 , 10) as `articles`';

								$picked_arts	= $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order='');

								if ( count($picked_arts) == 0 ) {
									$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 AND `ar_category_id` = 6 ORDER BY `views` DESC LIMIT 0 , 10) as `articles`';
									$picked_arts	= $this->DatabaseModel->select_data($field,$table,$where=['articles_content.order_' => 0],'',$join,$order='');
								}


						}
						else
						{
								$table 			= '(SELECT * FROM `articles` WHERE `complete_status` = 1 AND `delete_status` = 0 AND `privacy_status` = 7 AND `ar_category_id` = 6 ORDER BY `views` DESC LIMIT 0 , 10) as `articles`';

								$picked_arts	= $this->DatabaseModel->select_data($field,$table,$where=['articles_content.order_' => 0],'',$join,$order='');
						}

						$formated_   	= $this->formatArticlesArray($picked_arts);

					}
					else{

						$field 		= '*';

						$join_array = 'article_id';

						$data       =	$this->DatabaseModel->access_database('articles', 'wherein', $data_arr, '', $join_array);

						$cond = '';

						$q = '('. $this->db->last_query(). ' AND `complete_status` = 1 AND `delete_status` = 0 AND `active_status` = 1 AND `privacy_status` = 7 '.$cond.') as `articles`';

						$table 		= $q;

						$field 		= 'articles.*,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,articles_content.order_,users.user_name,article_categories.cat_name,articles_content.publish_status';

						$where      = '`articles_content`.`order_` IN (0 ,1)';

						$join  		= array(
											'multiple',
												array(
													array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
													array('users' , 'users.user_id = articles.ar_uid','left'),
													array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
												)
											);

						$picked_arts = $this->DatabaseModel->select_data($field,$table,$where,'',$join,$order=['articles.views desc', 'articles.article_id desc']);

						$formated_   = $this->formatArticlesArray($picked_arts);

					}






					$this->statusCode 	= 1;

					if ( count($picked_arts) > 0 ) {
						$this->statusType 			= 'Success';
						$this->respMessage 			= 'found';
						$resp['slider_articles'] 	= $formated_;
						$resp['slider_title'] 		= $slider_title;
					}
					else{

						$this->statusType 			= 'Empty';
						$this->respMessage 			= 'Not found';
						$resp['slider_title'] 		= $slider_title;
					}

				}else{
						$this->statusCode 			= 1;
						$this->statusType 			= 'Empty';
						$this->respMessage 			= 'Not found';
				}



			}

		$this->show_my_response($resp);

		}
	}

	private function formatArticlesArray($array){
		$c = 0;
		$formated_ = array();
		foreach($array as $k => $article){
			if ($k == 0) {
				$last_ID = $article['article_id'];
			}

			if ($last_ID != $article['article_id']) {
				$last_ID = $article['article_id'];
				$c += 1;
			}

			if ($last_ID == $article['article_id']) {
				$formated_[$c][$article['order_']] = $article;
				$formated_[$c][$article['order_']]['encoded_id'] = $this->share_url_encryption->share_single_article_link_creator($last_ID , 'encode');
			}
		}
		return $formated_;
	}

	// *************************  FOR Article Mode Functions end   ************************ //


	// *************************  FOR Article Search Function end   ************************ //

	public function searchKeyWords(){
		if ($this->input->is_ajax_request()) {
			$resp = [];
			$this->form_validation->set_rules('keywords', 'search Keywords', 'trim|required');
			$this->form_validation->set_rules('mode', 'mode', 'trim');
			$this->form_validation->set_rules('U_B_C_id', 'U_B_C_id', 'trim');
			$this->form_validation->set_rules('my_user_uname', 'My User Name', 'trim');
			$this->form_validation->set_rules('publish_status', 'Articles Publish Status', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];
				$this->statusCode = 1;

			}else{
				$keywords 			= !empty($this->input->post('keywords')) ?$this->input->post('keywords'):'empty';
				$mode 				= !empty($this->input->post('mode')) ?$this->input->post('mode'):'empty';
				$U_B_C_id 			= !empty($this->input->post('U_B_C_id')) ?$this->input->post('U_B_C_id'):'empty';
				$my_user_uname 		= !empty($this->input->post('my_user_uname')) ?$this->input->post('my_user_uname'):'empty';
				$publish_status 	= !empty($this->input->post('publish_status')) ?$this->input->post('publish_status'):'empty';
				$arts_fids       	= isset($_SESSION['art_fids']) && !empty($_SESSION['art_fids']) ?$_SESSION['art_fids']:[0];
				$uid             	= isset($this->uid) && !empty($this->uid) ? $this->uid : 0;

				if ($keywords != 'empty') {

					$complete_sts 	= "AND `complete_status`= 1";

					$cond           = "";
					if ($mode == 'blogs_by_username') {
						$cond = " AND `users`.`user_uname` = '".$U_B_C_id."'";
						if ($my_user_uname == $U_B_C_id) {
							if ($publish_status == 0) {
								$complete_sts = " AND complete_status = 0";
							}else if($publish_status == 1){
								$complete_sts = " AND complete_status = 1";
							}
						}
					}

					$table 			= '(SELECT `articles`.*,`users`.`user_name`,`users`.`user_uname` FROM `articles` LEFT JOIN `users` ON `users`.`user_id` = `articles`.`ar_uid` WHERE IF(`ar_uid` IN ('.implode(',',$arts_fids).') , `privacy_status` IN (6,7) , IF(`ar_uid` = '.$uid.' , `privacy_status` IN (5,6,7) , `privacy_status` = 7 ) )  AND `articles`.`delete_status` = 0 '.$complete_sts.$cond.' AND ( `articles`.`ar_title` LIKE "%'.$keywords.'%" OR `articles`.`ar_tag` LIKE "%'.$keywords.'%" OR `users`.`user_name` LIKE "%'.$keywords.'%" ) ORDER BY `articles`.`article_id` DESC LIMIT 0 , 10) as `articles`';

					$search_data 	= $this->DatabaseModel->select_data($field = '',$table,$where = '','',$join = '',$order=['articles.article_id', 'desc']);

					if (count($search_data) > 0) {

						$formated_ 			= $this->AddSearcResult($search_data, $keywords );
						$this->statusType 	= 'Success';
						$this->respMessage 	= 'found';
						$resp				= $formated_;

						echo json_encode($resp);
						die;

					}

				}
			}

		}
	}

	private function AddSearcResult($search_result, $keywords){
		$results = [];

		foreach($search_result as $i => $result){
			if(isset($result['ar_title'])){
				$ar_id = $this->share_url_encryption->share_single_article_link_creator($result['article_id'] , 'encode');
				array_push($results,$result['ar_title'].'@$%(_$^%*'.'ar_title'.'@$%(_$^%*'.$ar_id.'@$%(_$^%*'.$result['ar_slug']);
			}

			if(isset($result['user_name'])){
				array_push($results,$result['user_name'].'@$%(_$^%*'.'ar_user_name'.'@$%(_$^%*'.$result['user_uname']);

			}

			if(isset($result['ar_tag'])){
				$str_ar = explode(',', $result['ar_tag']  );
				foreach ($str_ar as $key => $value) {
					if (stristr($value, $keywords) !== false) {
						$a = trim($value);
						array_push($results,$a.'@$%(_$^%*'.'ar_tag');
					}
				}
			}
		}
		$data = array_values(array_unique(array_filter($results)));
		$res = [];
		foreach($data as $i => $result){
			$ar = explode("@$%(_$^%*",$result);
			$res[$i]['value'] 	= $ar[0];
			$res[$i]['type'] 	= $ar[1];
			$res[$i]['ar_id'] 	= isset($ar[2])?$ar[2]:'';
			$res[$i]['ar_slug'] = isset($ar[3])?$ar[3]:'';
		}

		return $res;
	}
	// *************************  FOR Article Search Function end   ************************ //

	public function getPublishDataStatus(){
		$this->load->library('manage_session');

		if($this->input->is_ajax_request() ){

			$this->form_validation->set_rules('pubID', 'Article Id', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$pubId = !empty($this->input->post('pubID')) ?$this->input->post('pubID'):0;
				if ($pubId != 0) {
					$data 	= $this->DatabaseModel->select_data('privacy_status','articles',['article_id' => $pubId],'','','');
					if (isset($data[0]['privacy_status'])) {
						echo json_encode($data[0]['privacy_status']);
					}else{
						echo 0;
					}

				}
			}
		}
	}

	public function changePublishStatus(){
		$this->load->library('manage_session');

		if($this->input->is_ajax_request() ){
			$resp = [];
			$this->form_validation->set_rules('pubID', 'Article Id', 'trim');
			$this->form_validation->set_rules('aud', 'Audience', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];

			}
			else{

				$pubId = !empty($this->input->post('pubID')) ?$this->input->post('pubID'):0;
				$aud = !empty($this->input->post('aud')) ?$this->input->post('aud'):0;
				if ($pubId != 0 && $aud != 0 ) {
					$data 	= $this->DatabaseModel->access_database('articles', 'update',['privacy_status' => $aud ] ,['article_id' => $pubId] , $join_array="");

					$this->statusCode = 1;

					$this->show_my_response($resp);
				}
			}
		}
	}

	public function addVideoUrlToArticle(){

		$this->load->library('manage_session');
		$uid = $this->uid;

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){
			$this->form_validation->set_rules('author', 'video author', 'trim');
			$this->form_validation->set_rules('publisher', 'video publisher', 'trim');
			$this->form_validation->set_rules('license_id', 'video license', 'trim');

			if ($this->form_validation->run() == FALSE){

				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}
			else{

				// $url = $this->input->post('src');
				$url = 'embedcv/'.$this->input->post('license_id').'?autoplay=false&muted=true&control=false';

				$json_data      	= ['author' => $this->input->post('author'), 'publisher' => $this->input->post('publisher'), 'license_id' => $this->input->post('license_id') ];

				$post 				=	['article_id' => $this->input->post('article_id'), 'user_id' => $uid, 'content_type' => 'video', 'content' => $url, 'plain_content' => 0, 'image_data' => json_encode($json_data), 'order_' => $this->input->post('order_') ];

				$data['db_status'] 	= $this->DatabaseModel->access_database('articles_content', 'insert', $post);

				$where['id'] 		= $data['db_status'];

				$video = $this->DatabaseModel->access_database('articles_content', 'select', '', $where);

				$resp = ['db_id' => $data['db_status'], 'img_src' => $video[0]['content'], 'input_id' => $this->input->post('input_id') ];

				$this->statusCode 	= 1;
				$this->statusType 	= 'Success';
				$this->respMessage 	= 'Video Added Successfully';

			}
			$this->show_my_response($resp);

		}

	}

	public function getVideoLibraryData( $status = null ){

		$this->load->library('manage_session');
		$uid = $this->uid;

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1 && $this->input->is_ajax_request() ){
			$ofiled = ['channel_post_video.post_id','','users.user_name','channel_post_video.title','channel_post_video.description','channel_post_video.tag','website_mode.mode AS web_mode','channel_post_video.created_at','mode_of_genre.genre_name','channel_post_video.age_restr','channel_post_video.video_duration','','channel_post_video.active_status','channel_post_video.uploaded_video','users.user_id','image_name','featured_by_admin','channel_post_video.post_key'];

			$search = 	isset($_POST['search'])?$_POST['search']:'';

			$limit =  isset($_POST['limit'])?$_POST['limit']:'';
			$start =  isset($_POST['start'])?$_POST['start']:'';

			$cond = "  (users.user_name LIKE '%".$search."%' OR users.user_uname LIKE '%".$search."%' OR  channel_post_video.title LIKE '%".$search."%')";

			if(isset($_POST['mode']) && !empty($_POST['mode'])){
				$cond .= " AND channel_post_video.mode = '".$_POST['mode']."'";
			}

			//$cond .= " AND channel_post_video.video_size <= 1610612736";  // less than OR euqal to  1.5 GB

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
			$search_post_id = array_column($search_result, 'post_id');
			$search_post_id = implode(',',$search_post_id);
			$search_post_id = !empty($search_post_id) ? $search_post_id : 0 ;

			$new_cond = $this->common->channelGlobalCond([$status , 1,  NULL , 0 , 1, 1 , 0]);
			$fcond 	 = $new_cond. " AND channel_post_video.post_id IN($search_post_id) ";

			$order = "FIELD(channel_post_video.post_id,$search_post_id)";
			$requestData = $this->DatabaseModel->select_data($ofiled , 'channel_post_video use INDEX(post_id)' , $fcond ,'', $join,$order);

			$resp = [];

			if( count($requestData) > 0 ){
				$this->statusType 			= 'Success';
				$this->respMessage 			= 'found';
				$resp['data'] = $requestData;
			}else{
				$this->statusType 			= 'Empty';
				$this->respMessage 			= 'Not found';
				$resp['data'] = $requestData;
			}

			$this->statusCode = 1;
			$this->show_my_response($resp);
		}
	}

	public function addNewPart(){
		$this->load->library('manage_session');
		$resp = [];
		$uid = $this->uid;

		$TokenResponce = $this->creator_jwt->MatchToken();

		if($TokenResponce['status'] == 1){

			$post = [
				'article_id' 	=> $this->input->post('art_id'),
				'user_id' 		=> $uid,
				'content_type' 	=> 'part',
			];

			// $post['order_'] = $this->input->post('order');
			$db_id = $this->DatabaseModel->access_database('articles_content', 'insert', $post);

			$resp = [
				'input_id' 			=> $this->input->post('input_id'),
				'db_id' 			=> $db_id
			];

			$this->statusCode = 1;
			if ($db_id >= 1) {
				$this->statusType = 'Success';
				$this->respMessage = 'Data Inserted/Updated';
			}else{
				$this->statusType = 'Empty';
				$this->respMessage = 'Data not Inserted/Updated';
			}
			$this->show_my_response($resp);

		}

	}

	
}
