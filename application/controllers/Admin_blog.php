<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_blog extends CI_Controller {
	
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
	
    public function create_blog_category(){
		$data['page_menu'] = 'blogs|blog_category|Blogs|blog_category'; 
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/blog/create_category',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}
	
	
	public function show_categories(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     =   $_GET['search']['value'];
        $colm 	=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 	=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		
		$leadsCount = 	0;
		$filed = array(null,'id','cat_name','cat_img','cat_slug','status','is_in_slider','category_order');
		$join = array();
		$cond = "";
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
        //$join 			= array('website_mode','mode_of_genre.mode_id = website_mode.mode_id','left');
		$results 	= $this->DatabaseModel->select_data($filed,'article_categories', $cond ,array($_GET['length'],$start) ,'', array($filed[$colm],$order) );
		$leadsCount = $this->DatabaseModel->aggregate_data('article_categories','id','COUNT',$cond,'');
			
		if(!empty($results)){
			$start++;
			foreach($results as $list){ 
				$status = ($list['status'] == 1)?'checked': "" ;
				$slider = ($list['is_in_slider'] == 1)?'checked': "" ;
                array_push($data , array(
					$start++,
                    '<div class="dis_admin_img_div"><img width="212" height="157" onerror="erroronimageload(this)" src="'.base_url('repo_admin/images/blog_cate/'.$list['cat_img']).'"></div>',
					$list['cat_name'],
					$list['cat_slug'],
					'<input '.$status.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="admin/updateCheckStatus/article_categories">',
					'<input '.$slider.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="admin/updateCheckStatus/slider_mode_of_articles_category">',
					'<a class="editCategory" data-name="'.$list['cat_name'].'" data-id="'.$list['id'].'"><i class="fa fa-fw fa-edit"></i></a>',
					'<a data-id="'.$list['id'].'" href="javascript:;"><i class="fa fa-bars handle ui-sortable-handle"></i></a>',
					//'<a href="" data-delete-id="'.$list['id'].'" data-field="id" data-action-url="admin_blog/deleteCategory"><i class="fa fa-fw fa-trash"></i></a>'
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
	
	
	public function addEditCategory(){
		$resp = [];
        $checkValidation = check_api_validation($_POST , array('name|require'));
			if($checkValidation['status'] == 1){
				$cate_name = strtolower(str_replace(" ","-",$_POST['name']));
				$updateData = array('cat_name'=>$_POST['name'],
									'cat_slug'=>slugify($cate_name),
									);
				
				if(isset($_FILES['cate_img']['name']) && !empty($_FILES['cate_img']['name'])){
					$img_key='';
					$catepath = 'repo_admin/images/blog_cate/';
					if(!empty($_POST['id'])){
						$results 	= $this->DatabaseModel->select_data('cat_img','article_categories', array('id'=>$_POST['id']),1);
						if(isset($results[0]['cat_img']) && !empty($results[0]['cat_img'])) {
							$img_key = $results[0]['cat_img'];
						}
                    }
						
					$r 	= $this->audition_functions->upload_file('./'.$catepath,'jpg|png|jpeg','cate_img',true);
					if($r != 0){
						$pathToImages = ABS_PATH.'repo_admin/images/blog_cate/';
                        if(!empty($img_key) && file_exists($pathToImages.$img_key)){
							unlink('./'.$catepath.basename($img_key));
                        }
                        $updateData['cat_img'] =  $r['file_name'];
                    }
				}
				
				
				if(isset($_POST['id']) && !empty($_POST['id'])){
					$this->DatabaseModel->access_database('article_categories','update',$updateData,array('id'=>$_POST['id']));
					$this->statusCode = 1;
					$this->statusType = 'Success';
					$this->respMessage = 'Category updated Successfully.';
				}else{
					if( $cateId = $this->DatabaseModel->access_database('article_categories','insert',$updateData,'')){
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
	
    function deleteCategory(){
        $resp = [];
        $checkValidation = check_api_validation($_POST , array('id|require'));
			if($checkValidation['status'] == 1){
            try {
                $id = $this->input->post('id');
                $results 	= $this->DatabaseModel->select_data('cat_img','article_categories', array('id'=>$id),1);
				if(isset($results[0]['cat_img']) && !empty($results[0]['cat_img'])) {
					$img_key = $results[0]['cat_img'];
					$catepath = 'repo_admin/images/blog_cate/';
					$pathToImages = ABS_PATH.'repo_admin/images/blog_cate/';
					if(!empty($img_key) && file_exists($pathToImages.$img_key)){
						unlink('./'.$catepath.basename($img_key));
					}
				}
                if($this->DatabaseModel->access_database('article_categories','delete','', array('id'=>$id))){
                    $this->statusCode   =  1;
                    $this->statusType   = 'Success';
                    $this->respMessage  = 'Category Deleted Successfully.';
                }else{
                    $this->respMessage = 'Something Went Wrong. Please Try Again.';
                }
            }catch (HttpClientException $e) {
                $this->respMessage = $e->getMessage();
            } 
        }else{
            $this->respMessage = $checkValidation['message'];
        }
        
        $this->show_my_response($resp);
    }




}