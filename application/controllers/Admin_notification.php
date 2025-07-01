<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_notification extends CI_Controller {
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	public function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
	
		$this->load->library(array('query_builder'));
		
	}
	function is_ajax(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
	}
	 
	private function show_my_response($resp = array()){
		$resp['status']     = $this->statusCode;
		$resp['type']       = $this->statusType;
		$resp['message']    = $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	
    public function firebase(){
		$data['page_menu'] = 'notification|firebase_notification|Notification|firebase_notification'; 
        $data['category']	= 	$this->DatabaseModel->select_data('category_id,category_name','artist_category',['level'=>1]);
		
        $this->load->view('admin/include/header',$data);
		$this->load->view('admin/notification/notification',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}
	
	function roundUpToAny($n,$x=5) {
		return (ceil($n)%$x === 0) ? ceil($n) : round(($n+$x/2)/$x)*$x;
	}

	function send_notification(){
		try{
			$rows = [];
			$rules = array(
				array( 'field' => 'title', 'label' => 'Title', 'rules' => 'trim|required'),
				array( 'field' => 'body', 'label' => 'Message', 'rules' => 'trim|required'),
				array( 'field' => 'link', 'label' => 'Link', 'rules' => 'trim|required'),
				array( 'field' => 'platform[]', 'label' => 'Platform Type', 'rules' => 'trim|required'),
			);
			$this->load->library('form_validation');
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				extract($_POST);
			
				$icon='';
				if(isset($_FILES['icon']['name']) && !empty($_FILES['icon']['name'])){
					$pathToData = ABS_PATH .'uploads/admin/notification/';
					$r 	= $this->audition_functions->upload_file($pathToData,'jpg|png|gif|jpeg','icon',true);
					if($r != 0){
						$icon = base_url('uploads/admin/notification/'.$r['file_name']);
					} 
				}

				$cond = ['user_firebase_token !=' => ''];
				if($user_level != 'all'){
					$cond['user_level'] = $user_level;
				}

				$results 	    = $this->DatabaseModel->select_data('user_firebase_token','users',$cond);
				
				$token_array    = [];
				foreach($results as $result){
					$tokens = json_decode($result['user_firebase_token'],true);
					$token_array['web'][]       = isset($tokens['web']) && in_array('web',$platform)? $tokens['web']:'';
					$token_array['ios'][]       = isset($tokens['ios'])  && in_array('ios',$platform)? $tokens['ios']:'';
					$token_array['android'][]   = isset($tokens['android'])  && in_array('android',$platform)? $tokens['android']:'';
				}
			
				$msg_array 	=  	[
					'title'	=>	$body,
					'body'	=>	$title,
					'icon'	=>	$icon,
					'image' =>  $icon,
					'click_action'=>$link,
					//'extra_data'=>array('videoThumb'=>$icon),
					'extra_data'=>array('id'=>10835,'intent'=>'social_post','videoThumb'=>$icon)
				];
				
				$response = [];
				foreach($token_array as $p=>$t){

					if(isset($token_array[$p]) && !empty($token_array[$p])){
						
						$chunk_array = array_chunk($token_array[$p],1000);

						foreach($chunk_array as $tt=>$v){

							$tokenArr = array($p =>$chunk_array[$tt]);

							$tokenArr = array_map(function ($value) { return $value ? array_values(array_filter($value)): null; }, $tokenArr);

							if(!empty($tokenArr)){

								$response[]= $this->audition_functions->sendNotification($tokenArr,$msg_array);

							}
						}
					}
						
				}

				
				$responsee = [];
				if(!empty($response)){
					foreach($response as $resp){
						$responsee[] = array_map(function ($value) { 
							$value = json_decode($value,true);
							unset($value['results']);
							return $value;
						}, $resp);
					}
				}
				
				//print_R($responsee);die;
				$res = [];
				$websuccess = 0;
				$webfailure = 0;
				$androidsuccess = 0;
				$androidfailure = 0;
				$iossuccess = 0;
				$iosfailure = 0;
				if(!empty($responsee)){
					foreach($responsee as $v){
						foreach($v as $k=>$p){
							if($k =='web'){
								$websuccess += $p['success'];
								$webfailure += $p['failure'];
								$res['web']['success'] = $websuccess;
								$res['web']['failure'] = $webfailure;
							}else
							if($k =='android'){
								$androidsuccess += $p['success'];
								$androidfailure += $p['failure'];
								$res['android']['success'] = $androidsuccess;
								$res['android']['failure'] = $androidfailure;
							}else
							if($k =='ios'){
								$iossuccess += $p['success'];
								$iosfailure += $p['failure'];
								$res['ios']['success'] = $iossuccess;
								$res['ios']['failure'] = $iosfailure;
							}
						}
					}
				}
				
				$data_array = [
					'title' => $title,
					'message' => $body,
					'user_level' => $user_level,
					'platforms' => json_encode($platform),
					'img_link' => $icon,
					'link' => $link,
					'created_at' => date('Y-m-d H:i:s'),
					'results' => json_encode($res)
				];
				$this->DatabaseModel->access_database('firebase_notifications','insert',$data_array);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Your notifications sent successfully.';
			}else{
				$this->respMessage 	=	$this->common->form_validation_error()['message'];
			}
		}catch (Exception $e) {
			$this->respMessage = $e->getMessage();
		} 
		$this->show_my_response();
	}


	function send_notification_old(){
		$rows = [];
		$rules = array(
			array( 'field' => 'title', 'label' => 'Title', 'rules' => 'trim|required'),
			array( 'field' => 'body', 'label' => 'Message', 'rules' => 'trim|required'),
			array( 'field' => 'link', 'label' => 'Link', 'rules' => 'trim|required'),
			array( 'field' => 'platform[]', 'label' => 'Platform Type', 'rules' => 'trim|required'),
		);
		$this->load->library('form_validation');
		$this->form_validation->set_rules($rules);
		
		if($this->form_validation->run()){
			extract($_POST);
		
            $icon='';
			if(isset($_FILES['icon']['name']) && !empty($_FILES['icon']['name'])){
				$pathToData = ABS_PATH .'uploads/admin/notification/';
				$r 	= $this->audition_functions->upload_file($pathToData,'jpg|png|gif|jpeg','icon',true);
                if($r != 0){
                    $icon = base_url('uploads/admin/notification/'.$r['file_name']);
                } 
            }

            $cond = ['user_firebase_token !=' => ''];
            if($user_level != 'all'){
                $cond['user_level'] = $user_level;
            }

            $results 	    = $this->DatabaseModel->select_data('user_firebase_token','users',$cond);
            
            $token_array    = [];
            foreach($results as $result){
                $tokens = json_decode($result['user_firebase_token'],true);
                $token_array['web'][]       = isset($tokens['web']) && in_array('web',$platform)? $tokens['web']:'';
                $token_array['ios'][]       = isset($tokens['ios'])  && in_array('ios',$platform)? $tokens['ios']:'';
                $token_array['android'][]   = isset($tokens['android'])  && in_array('android',$platform)? $tokens['android']:'';
            }
           
            $token_array = array_map(function ($value) { return $value ? array_values(array_filter($value)): null; }, $token_array);
           
            $msg_array 	=  	[
                'title'	=>	$title,
                'body'	=>	$body,
                'icon'	=>	$icon,
                'click_action'=>$link
            ];
            $response = $this->audition_functions->sendNotification($token_array,$msg_array);
           
            $response = array_map(function ($value) { 
                $value = json_decode($value,true);
                unset($value['results']);
                return $value;
            }, $response);
            
            $data_array = [
                'title' => $title,
                'message' => $body,
                'user_level' => $user_level,
                'platforms' => json_encode($platform),
                'img_link' => $icon,
                'link' => $link,
                'created_at' => date('Y-m-d H:i:s'),
                'results' => json_encode($response)
            ];
            $this->DatabaseModel->access_database('firebase_notifications','insert',$data_array);
            $this->statusCode = 1;
            $this->statusType = 'Success';
            $this->respMessage = 'Your notifications sent successfully.';
		}else{
			$this->respMessage 	=	$this->common->form_validation_error()['message'];
		}
		$this->show_my_response();
	}
	

	public function show_notifications(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
		$user_level = 	$_GET['user_level'];
	    $search     =   $_GET['search']['value'];
        $colm 	    =  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 	    =  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		
		$leadsCount = 	0;
		$filed = array('','title','message','category_name','platforms','img_link','link','firebase_notifications.created_at','results');
		$join = array();
		
        $cond = "";
        
        if($user_level != ''){
            $cond .= 'user_level = ' . $user_level . ' AND ';
        }

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
        $join 			= array('artist_category','firebase_notifications.user_level = artist_category.category_id','left');
		$results 	= $this->DatabaseModel->select_data($filed,'firebase_notifications', $cond ,array($length,$start) ,$join, array($filed[$colm],$order) );
		$leadsCount = $this->DatabaseModel->aggregate_data('firebase_notifications','noti_id','COUNT',$cond,$join);
		
		if(!empty($results)){
			$start++;
			foreach($results as $list){
                $resultInfo = '';
                foreach(json_decode($list['results'],true) as $key => $r){
                    $resultInfo .=  ucfirst($key) . ': Success - '.$r['success'].' | '.' Failure - ' . $r['failure'] . '</br>';
                }
                // die;
				array_push($data , array(
					$start++,
                    $list['title'],
                    $list['message'],
                    $list['category_name'] ? $list['category_name'] : 'All',
                    implode(',',json_decode($list['platforms'])),
                    '<a href="'.$list['link'].'" target="_blank"><img width="50px" src="'.$list['img_link'].'"/></a>',
                    $list['created_at'],
                    $resultInfo,

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