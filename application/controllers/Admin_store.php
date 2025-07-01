<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/wocommerce/autoload.php";

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class Admin_store extends CI_Controller {
	Private $woocommerce = '';
    Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';

	public function __construct(){
		parent::__construct();
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
        $this->load->library(['form_validation']);

        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[]  
        );
    }
   
    Private function is_ajax(){
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        return true;
    }
    
    Private function show_my_response($resp = array()){
        $resp['status'] = $this->statusCode;
        $resp['type'] = $this->statusType;
        $resp['message'] = $this->respMessage;
        $this->output->set_content_type('application/json');
        $this->output->set_status_header(($resp['status'] == 1)?200:401);
        $this->output->set_output(json_encode($resp));
    }
    Private function validation_error(){
        $errors = array_values($this->form_validation->error_array());
        $this->respMessage  =  isset($errors[0])?$errors[0]:'';
    }

    Private function get_result($results){
        return json_decode(json_encode($results), true);
    }
    /***************************************************Attribute Section Start ***************************************************************/
    public function attributes(){
        $data['page_menu'] = 'store|store_attributes|Attributes|attributes';  //currentMainMenu, currentSubMenu, pageTitle, currentPageName

        $this->load->view('admin/include/header',$data);
        $this->load->view('admin/store/attributes',$data);
        $this->load->view('common/notofication_popup');
        $this->load->view('admin/include/footer',$data);
    }

    public function addEditAttribute(){
        $resp = [];
        $rules = array(
            array( 'field' => 'name', 'label' => 'attribute name', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $name = $this->input->post('name');
                $data = [
                    'name' => $name,
                    'slug' => slugify($name),
                    'type' => 'select',
                    'order_by' => 'menu_order',
                    'has_archives' => false
                ];
                
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $results    = $this->woocommerce->put('products/attributes/'.$_POST['id'], $data);
                    $this->respMessage  = 'Attributes updated Successfully.';
                }else{
                    $results    = $this->woocommerce->post('products/attributes', $data);
                    $this->respMessage  = 'Attributes Added Successfully.';
                }
                
                $results   = $this->get_result($results);

                if(isset($results['id'])){
                    $this->statusCode   =  1;
                    $this->statusType   = 'Success';
                    
                }else{
                    $this->respMessage = 'Something Went Wrong. Please Try Again.';
                }
            }catch (HttpClientException $e) {
                $this->respMessage = $e->getMessage();
            } 
        }else{
            $this->validation_error();
        }
        
        $this->show_my_response($resp);
    }

    public function show_attributes(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     = $_GET['search']['value'];
        $leadsCount = 	0;
        
        $results    = $this->woocommerce->get('products/attributes');
     
		if(!empty($results)){
			$start++;
			foreach($this->get_result($results) as $list){
				array_push($data , array(
					$start++,
					$list['name'],
					$list['slug'],
					'<a class="editAttribute" data-name="'.$list['name'].'" data-id="'.$list['id'].'"><i class="fa fa-fw fa-edit"></i></a>',
					'<a href="" data-delete-id="'.$list['id'].'" data-field="id" data-action-url="admin_store/deleteAttribute"><i class="fa fa-fw fa-trash"></i></a>'
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

    function deleteAttribute(){
        $resp = [];
        $rules = array(
            array( 'field' => 'id', 'label' => 'Id', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $id = $this->input->post('id');
                $results = $this->woocommerce->delete('products/attributes/'.$id, ['force' => true]);
               
                $results  = $this->get_result($results);
               
                if(isset($results['id'])){
                    $this->statusCode   =  1;
                    $this->statusType   = 'Success';
                    $this->respMessage  = 'Attributes Deleted Successfully.';
                }else{
                    $this->respMessage = 'Something Went Wrong. Please Try Again.';
                }
            }catch (HttpClientException $e) {
                $this->respMessage = $e->getMessage();
            } 
        }else{
            $this->validation_error();
        }
        
        $this->show_my_response($resp);
    }

    /***************************************************Attribute Section End ***************************************************************/



    /***************************************************Attribute Terms Section End ***************************************************************/
    public function attribute_terms(){
        $data['page_menu']  = 'store|store_attribute_terms|Attribute Terms|attribute_terms';  //currentMainMenu, currentSubMenu, pageTitle, currentPageName
        $results            = $this->woocommerce->get('products/attributes');
        $data['attr_list']  = $this->get_result($results);
        $this->load->view('admin/include/header',$data);
        $this->load->view('admin/store/attribute_terms',$data);
        $this->load->view('common/notofication_popup');
        $this->load->view('admin/include/footer',$data);
    }

    public function addEditAttributeTerms(){
        $resp = [];
        $rules = array(
            array( 'field' => 'name', 'label' => 'attribute name', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $name = $this->input->post('name');
                $data = [
                    'name' => $name
                ];
                
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $results    = $this->woocommerce->put('products/attributes/'.$_POST['attr_id'] .'/terms/' .$_POST['id'], $data);
                    $this->respMessage  = 'Attributes updated Successfully.';
                }else{
                    $results    = $this->woocommerce->post('products/attributes/'.$_POST['attr_id'].'/terms', $data);
                    $this->respMessage  = 'Attributes Added Successfully.';
                }
                
                $results   = $this->get_result($results);

                if(isset($results['id'])){
                    $this->statusCode   =  1;
                    $this->statusType   = 'Success';
                    
                }else{
                    $this->respMessage = 'Something Went Wrong. Please Try Again.';
                }
            }catch (HttpClientException $e) {
                $this->respMessage = $e->getMessage();
            } 
        }else{
            $this->validation_error();
        }
        
        $this->show_my_response($resp);
    }
    

    public function show_attribute_terms(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     =   $_GET['search']['value'];
        $leadsCount = 	0;
        
        $attribute_id 		= 	$_GET['attribute_id'];

        $results    =   $this->woocommerce->get('products/attributes/'.$attribute_id.'/terms',['offset' => $start,'per_page' => $length ,'search' => $search ]);
        $results    =   $this->get_result($results);
		
		if(!empty($results)){
			$start++;
			foreach($results as $list){
				array_push($data , array(
					$start++,
					$list['name'],
					$list['slug'],
					'<a class="editAttributeTerms" data-name="'.$list['name'].'" data-id="'.$list['id'].'"><i class="fa fa-fw fa-edit"></i></a>',
					'<a href="" data-delete-id="'.$attribute_id.'|'.$list['id'].'" data-field="id" data-action-url="admin_store/deleteAttributeTerms"><i class="fa fa-fw fa-trash"></i></a>'
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

    function deleteAttributeTerms(){
        $resp = [];
        $rules = array(
            array( 'field' => 'id', 'label' => 'Id', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $id             = explode('|',$this->input->post('id'));
                $attr_id        = $id[0];
                $attr_term_id   = $id[1];
                $results        = $this->woocommerce->delete('products/attributes/'.$attr_id.'/terms/'.$attr_term_id, ['force' => true]);
               
                $results        = $this->get_result($results);
               
                if(isset($results['id'])){
                    $this->statusCode   =  1;
                    $this->statusType   = 'Success';
                    $this->respMessage  = 'Attributes Terms Deleted Successfully.';
                }else{
                    $this->respMessage = 'Something Went Wrong. Please Try Again.';
                }
            }catch (HttpClientException $e) {
                $this->respMessage = $e->getMessage();
            } 
        }else{
            $this->validation_error();
        }
        
        $this->show_my_response($resp);
    }
    
    /***************************************************Attribute Terms Section End ***************************************************************/



    /***************************************************Categories Section Start ***************************************************************/

    public function categories(){
        $data['page_menu'] = 'store|store_categories|Categories|categories';  //currentMainMenu, currentSubMenu, pageTitle, currentPageName
        $this->load->view('admin/include/header',$data);
        $this->load->view('admin/store/categories',$data);
        $this->load->view('common/notofication_popup');
        $this->load->view('admin/include/footer',$data);
    }

    public function show_categories(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     =   $_GET['search']['value'];
        $leadsCount = 	0;
        
        $results    = $this->woocommerce->get('products/categories',['offset' => $start,'per_page' => $length ,'search' => $search ]);
        $results  = $this->get_result($results);

        $lastResponse = $this->woocommerce->http->getResponse();
        $headers    = $lastResponse->getHeaders();
        // $totalPages = $headers['X-WP-TotalPages'];
        $leadsCount = $headers['X-WP-Total'];
        
		if(!empty($results)){
			$start++;
			foreach($results as $list){ 
                // print_r($list);
                $img_src = isset($list['image']['src'])?
                   '<img width="212" height="157" onerror="this.onerror=null;this.src=\'https://test.discovered.tv/repo/images/thumbnail.jpg\'" src="'.$list['image']['src'].'">': '';
				array_push($data , array(
					$start++,
                    '<div class="dis_admin_img_div">
                    '.$img_src.'
                    </div>',
					$list['name'],
					$list['slug'],
					'<a class="editCategory" data-name="'.$list['name'].'" data-id="'.$list['id'].'"><i class="fa fa-fw fa-edit"></i></a>',
					'<a href="" data-delete-id="'.$list['id'].'" data-field="id" data-action-url="admin_store/deleteCategory"><i class="fa fa-fw fa-trash"></i></a>'
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
        $rules = array(
            array( 'field' => 'name', 'label' => 'attribute name', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $img_key = '';
                $catepath = 'uploads/admin/store_cate/';
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $result   = $this->woocommerce->get('products/categories/'.$_POST['id']);
                    $result   = json_decode(json_encode($result), true);
                    if(isset($result['image']['src']) &&  !empty($result['image']['src'])) {
                        $img_key = $result['image']['src'];
                    }
                }
                
                if(isset($_FILES['cate_img']['name']) && !empty($_FILES['cate_img']['name'])){
                	$r 	= $this->audition_functions->upload_file('./'.$catepath,'jpg|png|jpeg','cate_img',true);
					if($r != 0){
                        if(!empty($img_key)){
                            unlink('./'.$catepath.basename($img_key));
                        }
                        $img_key = $r['file_name'];
                        $img_key = base_url($catepath.$img_key);
                    }
				}

                $name = $this->input->post('name');
                $data = [
                    'name' => $name,
                    'slug' => slugify($name),
                ];

                if(!empty($img_key)){
                    $data['image'] = [
                        'src' => $img_key
                    ];
                }
               
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $results    = $this->woocommerce->put('products/categories/'.$_POST['id'], $data);
                    $this->respMessage  = 'Category updated Successfully.';
                }else{
                    $results    = $this->woocommerce->post('products/categories', $data);
                    $this->respMessage  = 'Category Added Successfully.';
                }
                
                $results   =   $this->get_result($results);

                if(isset($results['id'])){
                $this->statusCode   =  1;
                $this->statusType   = 'Success';
                    
                }else{
                    $this->respMessage = 'Something Went Wrong. Please Try Again.';
                }
            }catch (HttpClientException $e) {
                $this->respMessage = $e->getMessage();
            } 
        }else{
            $this->validation_error();
        }
        
        $this->show_my_response($resp);
    }

    function deleteCategory(){
        $resp = [];
        $rules = array(
            array( 'field' => 'id', 'label' => 'Id', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $id = $this->input->post('id');
                $catepath = 'uploads/admin/store_cate/';
                $result   = $this->woocommerce->get('products/categories/'. $id);
                $result   = json_decode(json_encode($result), true);
                if(isset($result['image']['src']) &&  !empty($result['image']['src'])) {
                    $img_key = $result['image']['src'];
                    unlink('./'.$catepath.basename($img_key));
                }
                $results = $this->woocommerce->delete('products/categories/'.$id, ['force' => true]);
               
                $results  = $this->get_result($results);
               
                if(isset($results['id'])){
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
            $this->validation_error();
        }
        
        $this->show_my_response($resp);
    }

    /***************************************************Categories Section End ***************************************************************/

    
	public function store_request(){
        $data['page_menu'] = 'store|store_request|Store Request |store_request';  //currentMainMenu, currentSubMenu, pageTitle, currentPageName 

        $this->load->view('admin/include/header',$data);
        $this->load->view('admin/store/store_request',$data);
        $this->load->view('common/notofication_popup');
        $this->load->view('admin/include/footer',$data);
    }
	
    public function access_store_request(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];
		
		$field		= 	[NULL,'users.user_name','users_content.is_store','users.user_uname','users_content.uc_userid'];
		
		$colm 		=  	$_GET['order'][0]['column'];
		$order 		=  	$_GET['order'][0]['dir'];
		 
		$cond = 'is_store != 0' ;
	
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
		$join = ['users','users.user_id=users_content.uc_userid','left'];
		$uinfo 	= 	$this->DatabaseModel->select_data($field,'users_content', $cond ,array($_GET['length'],$start) ,$join, array($field[$colm],$order) );
		$leadsCount =	$this->DatabaseModel->aggregate_data('users_content','uc_userid','COUNT',$cond,$join);
		
		if(!empty($uinfo)){
			$start++;
			    
			foreach($uinfo as $list){
                $options = '';
				foreach([1=>"Request",2=>"Approve",3=>"Block"] as $key => $value){ 
					$selected = ($key == $list['is_store'] )?'selected':'';
					$options .=  '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
				}
				$action = '<select class="form-control" data-check-id="'.$list['uc_userid'].'" 	data-action-url="admin/updateCheckStatus/users_store_status">'.$options.'</select>';

                array_push($data , array(
					$start++,
					'<a target="_blank" href="'.base_url('profile?user='.$list['user_uname']).'">'.ucfirst(wordwrap($list['user_name'],20,"<br>\n")) .'</a>',
					$action
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

    
	
	
	
	
	function addColorTerms(){
		$colorsArr = [ 	"#928590"=> "Venus",
        "#9370DB"=> "Medium Purple",
        "#93CCEA"=> "Cornflower",
        "#93DFB8"=> "Algae Green",
       ];
	   
	   $colorsArrChunk = array_chunk($colorsArr,100,true);
	   for($i=0; $i<sizeof($colorsArrChunk); $i++){
			$data = [];
			foreach($colorsArrChunk[$i] as $k=>$c){
				$data['create'][] = [
							'name' => $k,
							'slug' => $c,
							'description'=>$k
						];
				
			} 
			//echo "<pre>";
			//print_r($data);
			//$results    = $this->woocommerce->post('products/attributes/24/terms/batch', $data);
			//$this->respMessage  = 'Attributes Added Successfully.';
	   }
		die;
	}
	
	
	
	

}

	