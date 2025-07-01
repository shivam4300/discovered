<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/wocommerce/autoload.php";

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class Store extends CI_Controller {
    Private $woocommerce = '';
    Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
    Private $uid = '';
    Private $storestatus = 0;
    Private $store_vendor_id = 0; 
	public function __construct(){
		parent::__construct();
		
        if(isset($_SESSION['user_accesslevel']) && ($_SESSION['user_accesslevel'] == 4 || !is_login()) ) {    /*IF CATEGORY EQUAL TO FAN*/
			redirect(base_url());
		}
		$this->load->library(array('common','form_validation','creator_jwt'));
		$this->load->helper(array('url','api_validation','info','info_helper'));
		$this->uid = is_login();
        
        $store = get_store_status();
        $this->storestatus = isset($store['store_status'])?$store['store_status']:0;
        $this->store_vendor_id = isset($store['store_vendor_id'])?$store['store_vendor_id']:0;
    }
    private function wcmpClient(){
        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'version' => 'wcmp/v1']  
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
    public function check_redirect(){
        if($this->storestatus != 2){
            redirect(base_url('backend/store'));
        }
    }
    public function index(){
        if($this->storestatus == 2){
            redirect(base_url('backend/store/manage_products'));
        }
        $data['page_info'] = array('page'=>'store_request','title'=>'Store');
        $data['store_status'] =  $this->storestatus;

        $this->load->view('backend/include/header',$data);
		$this->load->view('backend/store/store_request');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
    }

    public function manage_products(){
        $this->check_redirect();
        $data['page_info'] 	= array('page'=>'products','title'=>'Manage Products');

        $this->load->view('backend/include/header',$data);
		$this->load->view('backend/store/manage_products');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
    }
    
    public function manage_orders(){
        $this->check_redirect();
        $data['page_info'] 	= array('page'=>'products','title'=>'Manage Orders');

        $this->load->view('backend/include/header',$data);
		$this->load->view('backend/store/manage_orders');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
    }

    public function getVendorList(){
        $this->wcmpClient();
        $results    = $this->woocommerce->get('vendors');   
        echo '<pre>';
        print_r($results);
    }


    public function request_store_access(){
        $resp = [];
        if($this->is_ajax()){
            $TokenResponce = $this->creator_jwt->MatchToken();
            if($TokenResponce['status'] == 1){
                
                $rules = array(
                    array( 'field' => 'store_request', 'label' => 'Store request', 'rules' => 'trim|required'),
                );
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run()){
                    try {
                        $uinfo = $this->DatabaseModel->select_data('user_uname,user_name,user_email,users_billing_and_payment_info.*','users',array('user_id'=>$this->uid),1,['users_billing_and_payment_info','users_billing_and_payment_info.billing_user_id  = users.user_id']); 
                       
                        if(!empty($uinfo)){
                            
                            if(isset($uinfo[0]['payment_method_type']) && $uinfo[0]['payment_method_type'] !== NULL){
                                $uuname     = isset($uinfo[0]['user_uname'])?$uinfo[0]['user_uname']:'';
                                $uname      = isset($uinfo[0]['user_name'])? explode(' ',$uinfo[0]['user_name']):[];
                                $first_name = isset($uname[0]) ?  $uname[0] : '';
                                $last_name  = isset($uname[1]) ?  $uname[1] : '';
                                
                                if(!empty($uuname)){
                                    $this->wcmpClient();

                                    $payment = [
                                        "payment_mode"          => isset($uinfo[0]['payment_method_type']) && $uinfo[0]['payment_method_type'] == 1 ? 'direct_bank':'paypal_payout',
                                        "bank_account_type"     => "savings",
                                        "bank_name"             => isset($uinfo[0]['bank_name'])?$uinfo[0]['bank_name']:'',
                                        "bank_account_number"   => isset($uinfo[0]['bank_acc_number'])? base64_decode($uinfo[0]['bank_acc_number']):'',
                                        "bank_address"          => "",
                                        "account_holder_name"   => isset($uinfo[0]['billing_name'])?$uinfo[0]['billing_name']:'',
                                        "aba_routing_number"    => isset($uinfo[0]['routing_number'])? base64_decode($uinfo[0]['routing_number']):'',
                                        "destination_currency"  => "",
                                        "iban"                  => "",
                                        "paypal_email"          => isset($uinfo[0]['paypal_id'])? base64_decode($uinfo[0]['paypal_id']):''
                                    ];
                                    
                                    $data = [
                                        'login'         => $uuname,
                                        'first_name'    => $first_name,
                                        'last_name'     => $last_name,
                                        'nice_name'     => $first_name . '' .$last_name,
                                        'display_name'  => $first_name . '' .$last_name,
                                        'email'         => isset($uinfo[0]['user_email'])?$uinfo[0]['user_email']:'',
                                        'url'           => base_url('channel?user='.$uuname.'#store'),
                                        'status'        => 1,
                                        'payment'       => $payment,
                                        
                                    ];
                                    
                                    $results    = $this->woocommerce->post('vendors', $data);
                                    
                                    $results   = $this->get_result($results);
                    
                                    if(isset($results['id'])){
                                      
                                        $Sinfo = $this->DatabaseModel->select_data('store_id','users_store',array('store_user_id'=>$this->uid),1); 
                                        if(empty($Sinfo)){
                                            $this->DatabaseModel->access_database('users_store','insert',['store_status'=>1,'store_user_id'=>$this->uid,'store_vendor_id'=>$results['id'],'created_at'=>date('Y-m-d H:i:s')]);
                                        }
                                        
                                        $this->statusCode   =  1;
                                        $this->statusType   = 'Success';
                                        $this->respMessage  = 'We have received your store request successfully.';
                                    }else{
                                        $this->respMessage = 'Something Went Wrong. Please Try Again.';
                                    }
                                }else{
                                    $this->respMessage = 'Please complete your profile before store request.'; 
                                }
                           }else{
                             $this->respMessage = 'Please go to setting menu and choose one payment option before proceeding.';
                           }
                        }else{
                            $this->respMessage = 'User not found. Please Try Again.'; 
                        }
                       
                    }catch (HttpClientException $e) {
                        $this->respMessage = $e->getMessage();
                    } 
                }else{
                    $this->validation_error();
                }            
            }else{
                $this->respMessage = $TokenResponce['message'];
            }

        }else{
            $this->respMessage = 'Unauthorized Access ! Please try again.';
        }
        $this->show_my_response($resp);
    }

    function delete_vendor(){
        $this->wcmpClient();
        $results    = $this->woocommerce->delete('vendors/14', ['force' => true]);
                                
        $results   = $this->get_result($results);
        echo '<pre>';print_r($results);
    }

    private function wcClient(){
        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'version' => 'wc/v3']  
        );
    }

    public function show_my_products(){
        $this->wcClient();
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     =   $_GET['search'];
        $leadsCount = 	0;
        
        $results    = $this->woocommerce->get('products?vendor='. $this->store_vendor_id,['offset' => $start,'per_page' => $length ,'search' => $search ]);
        $results  = $this->get_result($results);
        // print_r( $results );die;
        $lastResponse = $this->woocommerce->http->getResponse();
        $headers    = $lastResponse->getHeaders();
        // $totalPages = $headers['X-WP-TotalPages'];
        $leadsCount = $headers['X-WP-Total'];
        
		if(!empty($results)){
			$start++;
			foreach($results as $list){ 
                $image = isset($list['images'][0]['src']) ? $list['images'][0]['src'] : '';
                $c = '';
                foreach($list['categories'] as $cat){
                    $c .= ucfirst($cat['name']).'<br/>';
                }
				array_push($data , array(
					$start++,
                    '<div class="dis_admin_img_div"><img width="212" height="157" onerror="this.onerror=null;this.src=\''.base_url('repo/images/thumbnail.jpg').'\'" src="'.$image.'"></div>',
					$list['name'],
					$list['sku'],
					$list['stock_quantity'],
					$list['sale_price'],
                    $c,
                    $list['date_created'],
                    // $list['stock_status'],
                    $list['status'],
					'<div class="table_actionboxs">
					<span class="dropdown-toggle" data-video="6600" data-toggle="dropdown" aria-expanded="true">
						<svg xmlns="https://www.w3.org/2000/svg" width="15px" height="4px">
						<path fill-rule="evenodd" fill="rgb(168, 170, 180)" d="M13.031,4.000 C11.944,4.000 11.062,3.104 11.062,2.000 C11.062,0.895 11.944,-0.000 13.031,-0.000 C14.119,-0.000 15.000,0.895 15.000,2.000 C15.000,3.104 14.119,4.000 13.031,4.000 ZM7.500,4.000 C6.413,4.000 5.531,3.104 5.531,2.000 C5.531,0.895 6.413,-0.000 7.500,-0.000 C8.587,-0.000 9.469,0.895 9.469,2.000 C9.469,3.104 8.587,4.000 7.500,4.000 ZM1.969,4.000 C0.881,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.881,-0.000 1.969,-0.000 C3.056,-0.000 3.937,0.895 3.937,2.000 C3.937,3.104 3.056,4.000 1.969,4.000 Z"></path>
						</svg>
					</span>
				<ul class="action_drop">
				<li>
					<a href="'.base_url().'backend/store/createProduct/'.$list['id'].'" target="_blank" class="">
						<span class="drop_icon">
							<svg width="12px" height="auto" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 492.49284 492" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0" data-original="#000000" class=""></path><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0" data-original="#000000" class=""></path></g></svg>
							</span>
						Edit
					</a>	
				</li>
				<li>
					<a href="" data-delete-id="'.$list['id'].'" data-field="id" data-action-url="admin_store/deleteCategory">
						<span class="drop_icon">
							<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="13px" height="auto" viewBox="0 0 512 512"><g><g><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m424 64h-88v-16c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16h-88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zm-216-16c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96z" data-original="#000000" class=""></path><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m78.364 184c-2.855 0-5.13 2.386-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042c.136-2.852-2.139-5.238-4.994-5.238zm241.636 40c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z" data-original="#000000" class=""></path></g></g></svg>
							</span>
						Delete
					</a>	
				</li>
				
				</ul></div>'
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

    public function show_my_orders(){
        $this->wcClient();
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     =   $_GET['search'];
        $leadsCount = 0;
		$orderBy 		= ['suborder_id','DESC'];
		
		$vendorOrders = $this->DatabaseModel->select_data('vendor_id,main_order_id,suborder_id,customer_id,total_amount,order_status,payment_method,date_created,order_key','vendor_orders_details',array('vendor_id'=>$this->store_vendor_id),array($length,$start),'',$orderBy ); 
		
		if(!empty($vendorOrders)){
			$leadsCount = $this->DatabaseModel->aggregate_data('vendor_orders_details','vendor_orders_details.id','COUNT',array('vendor_id'=>$this->store_vendor_id));
			$start++;
			foreach($vendorOrders as $list){ 
                array_push($data , array( 
					$start++,
					$list['suborder_id'], 
					$list['date_created'],
					DEFAULT_CURRENCY_SYMBOL.' '.$list['total_amount'], 
					$list['order_status'],
					$list['payment_method'],
                    $list['order_key'],
					'<a href="'.base_url().'backend/store/view_order/'.$list['suborder_id'].'" class="viewOrder" title="View"><i class="fa fa-fw fa-eye"></i></a>'.
					'<!--a href="" data-delete-id="'.$list['suborder_id'].'" data-field="id" data-action-url="admin_store/deleteCategory"><i class="fa fa-fw fa-trash"></i></a-->'
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
		
		
	public function view_order($order_id = ''){
		if($order_id !=''){
			$this->wcClient();
			$results    = $this->woocommerce->get('orders/'.$order_id);
			$data['orderDetails']  	= $this->get_result($results);
			$prod_img_arr = array();
			if(!empty($data['orderDetails'])){
				$pids = [];
				foreach($data['orderDetails']['line_items'] as $item){
					$pids[] = $item['product_id'];
				}
				
				$results    = $this->woocommerce->get('products', ['include'=>array_unique($pids)]);
				$pro  		= $this->get_result($results);
				
				foreach ($pro as $key => $value) {
					$prod_img_arr[$value['id']] = $value['images'][0]['src'];
				}
			}
			$data['prod_img_arr'] = $prod_img_arr;
			
			//echo "<pre>";
		//	print_R($data['orderDetails']);die; 
			$data['page_info'] = array('page'=>'view_order','title'=>'View Orders');
			$this->load->view('backend/include/header',$data);
			$this->load->view('backend/store/processed_order');
			$this->load->view('backend/include/footer');
			$this->load->view('common/notofication_popup');
			
		}
	}		
		
		
	public function show_my_ordersOld(){
        $this->wcClient();
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$length 	= 	$_GET['length'];
	    $search     =   $_GET['search'];
        $leadsCount = 	0;
        
        $results    = $this->woocommerce->get('orders?vendor='. $this->store_vendor_id,['offset' => $start,'per_page' => $length ,'search' => $search ]);
        $results  = $this->get_result($results);
        // print_r( $results );die;
        $lastResponse = $this->woocommerce->http->getResponse();
        $headers    = $lastResponse->getHeaders();
        // $totalPages = $headers['X-WP-TotalPages'];
        $leadsCount = $headers['X-WP-Total'];
        
		if(!empty($results)){
			$start++;
			foreach($results as $list){ 
                $image = isset($list['images'][0]['src']) ? $list['images'][0]['src'] : '';
                $c = '';
                // foreach($list['categories'] as $cat){
                //     $c .= ucfirst($cat['name']).'<br/>';
                // }
				array_push($data , array(
					$start++,
                    // '<div class="dis_admin_img_div"><img width="212" height="157" onerror="this.onerror=null;this.src=\''.base_url('repo/images/thumbnail.jpg').'\'" src="'.$image.'"></div>',
					$list['line_items'][0]['name'],
					$list['line_items'][0]['quantity'],
					$list['status'],
					$list['total'].' '.$list['currency_symbol'],
                    $list['date_modified'],
                    // $list['stock_status'],
                    $list['payment_method'],
                    $list['order_key'],
					'<div class="table_actionboxs">
					<span class="dropdown-toggle" data-video="6600" data-toggle="dropdown" aria-expanded="true">
						<svg xmlns="https://www.w3.org/2000/svg" width="15px" height="4px">
						<path fill-rule="evenodd" fill="rgb(168, 170, 180)" d="M13.031,4.000 C11.944,4.000 11.062,3.104 11.062,2.000 C11.062,0.895 11.944,-0.000 13.031,-0.000 C14.119,-0.000 15.000,0.895 15.000,2.000 C15.000,3.104 14.119,4.000 13.031,4.000 ZM7.500,4.000 C6.413,4.000 5.531,3.104 5.531,2.000 C5.531,0.895 6.413,-0.000 7.500,-0.000 C8.587,-0.000 9.469,0.895 9.469,2.000 C9.469,3.104 8.587,4.000 7.500,4.000 ZM1.969,4.000 C0.881,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.881,-0.000 1.969,-0.000 C3.056,-0.000 3.937,0.895 3.937,2.000 C3.937,3.104 3.056,4.000 1.969,4.000 Z"></path>
						</svg>
					</span>
				<ul class="action_drop">
				<li>
					<a href="'.base_url().'backend/store/createProduct/'.$list['id'].'" target="_blank" class="">
						<span class="drop_icon">
							<svg width="12px" height="auto" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 492.49284 492" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0" data-original="#000000" class=""></path><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0" data-original="#000000" class=""></path></g></svg>
							</span>
						Edit
					</a>	
				</li>
				<li>
					<a href="" data-delete-id="'.$list['id'].'" data-field="id" data-action-url="admin_store/deleteCategory">
						<span class="drop_icon">
							<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="13px" height="auto" viewBox="0 0 512 512"><g><g><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m424 64h-88v-16c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16h-88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zm-216-16c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96z" data-original="#000000" class=""></path><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="m78.364 184c-2.855 0-5.13 2.386-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042c.136-2.852-2.139-5.238-4.994-5.238zm241.636 40c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z" data-original="#000000" class=""></path></g></g></svg>
							</span>
						Delete
					</a>	
				</li>
				
				</ul></div>'
				));
			}
		}
		
		
		echo json_encode(array(
			'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' => $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data,
		));
		// echo json_encode(array(
		// 	'draw' => '',
		// 	'recordsTotal' => '',
		// 	'recordsFiltered' => '',
		// 	'data' => '',
		// ));
	}

    public function createProduct($pro_id=''){
        $this->check_redirect();
		$this->wcClient();
        $data['page_info'] 	= array('page'=>'create_product','title'=>'Create product');
		
		$start 		= 	0; //$_GET['start'];
		$length 	= 	10; //$_GET['length'];
	    $search     =   ''; //$_GET['search']['value'];
        $leadsCount = 	0;
        
        $results    		       = $this->woocommerce->get('products/categories',['offset' => $start,'per_page' => $length ,'search' => $search ]);
        $data['pro_cats']  	       = $this->get_result($results);
		
	    $attributes 		       = $this->woocommerce->get('products/attributes');
		$data['attributes'] 	   = $this->get_result($attributes);
		
		$shippingClasses 		   = $this->woocommerce->get('products/shipping_classes');
		$data['shipping_classes']  = $this->get_result($shippingClasses);
		
		$proDetails = [];
		if(!empty($pro_id)){
			$proDetails  =   $this->woocommerce->get('products/'.$pro_id);
			$proDetails  = $this->get_result($proDetails);
		}
		$data['proDetails'] = $proDetails;
		//echo "<pre>";
		//print_R($data['proDetails']);die;
		
        $this->load->view('backend/include/header',$data);
		$this->load->view('backend/store/create_product');
		$this->load->view('backend/include/footer');
		$this->load->view('common/notofication_popup');
    }
	
    public function addEditProducts(){
		
        $resp = [];
        $rules = array(
            array( 'field' => 'pro_title',  	'label' => 'product title', 'rules' => 'trim|required'),
			array( 'field' => 'regular_price', 	'label' => 'Price', 'rules' => 'trim|required'),
			array( 'field' => 'sale_price', 	'label' => 'sales price', 'rules' => 'trim|required'),
			array( 'field' => 'product_cats', 	'label' => 'category', 'rules' => 'trim|required'),
			array( 'field' => 'pro_discription','label' => 'Discriptions', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $this->wcClient();
				
				$img_key_Arr = [];
				if(isset($_POST['id']) && !empty($_POST['id'])){
                    $result   = $this->woocommerce->get('products/'.$_POST['id']);
                    $result   = $this->get_result($result);
                    if(isset($result['images']) &&  !empty($result['images'])) {
                        $img_key_Arr = $result['images'];
                    }
                }
				
				$removedImgIds = $this->input->post('removed_img_id');
				
				if(!empty($img_key_Arr) && !empty($removedImgIds)){
					$ids = array_column($img_key_Arr, 'id');
					//foreach($removedImgIds as $v){
						if (array_search($removedImgIds, $ids) !==false) {
							$key = array_search($removedImgIds, $ids);
							unset($img_key_Arr[$key]);
						}
					//}
				}
				
				$unlinkImgName = [];
				$prodpath = 'uploads/admin/store_prod/';
				
				if(isset($_FILES['pro_img']['name']) && !empty($_FILES['pro_img']['name'])){
					$files = $_FILES;
					$countImg = count($_FILES['pro_img']['name']);
					for($i=0; $i<$countImg; $i++)
					{
						$_FILES['pro_img']['name']= $files['pro_img']['name'][$i];
						$_FILES['pro_img']['type']= $files['pro_img']['type'][$i];
						$_FILES['pro_img']['tmp_name']= $files['pro_img']['tmp_name'][$i];
						$_FILES['pro_img']['error']= $files['pro_img']['error'][$i];
						$_FILES['pro_img']['size']= $files['pro_img']['size'][$i];
						$r 	= $this->audition_functions->upload_file('./'.$prodpath,'jpg|png|jpeg','pro_img',true);
						if($r != 0){
							$img_key = $r['file_name'];
							$unlinkImgName[] = $img_key;
							$img_key = base_url($prodpath.$img_key);
							$img_key_Arr[] = array('src' => $img_key);
						}
					}	
				}				
			
                
                $name = $this->input->post('pro_title');
                $categories = $this->input->post('product_cats');
				$categories = explode(',', $categories);
				$updateCate =[];
				if(!empty($categories)){
					foreach($categories as $cat){
						$updateCate[] = array('id'=> $cat);
					}						
				}
				$attributesArr = [];
				$attributes = $this->input->post('attr');
				if(!empty($attributes)){
					foreach($attributes as $a){
						$attributesArr[] = array('id' =>$a['id'],
												'position' => 0,
												'visible' => false,
												'variation' => true,
												'options' => [$a['options']]
												);
					}
				}
				
				$tags = []; 
				$pro_tags = $this->input->post('pro_tags');
				if(!empty($pro_tags)){
					$pro_tags = explode('_',$pro_tags);
					$tags[] = array('name' => $pro_tags[0].'_'.$this->store_vendor_id);
				}
				
				$shipping_class_id = $this->input->post('shipping_class');
				$shippingClasses   = $this->woocommerce->get('products/shipping_classes/'.$shipping_class_id);
				$shipping_classes  = $this->get_result($shippingClasses);
                $regular_price = $this->input->post('regular_price');
                $sale_price = $this->input->post('sale_price');
                $discount = discountPercentage($regular_price, $sale_price);
                $data = [
                    'name' => $name,
                    'slug'=> slugify($name),
                    'vendor' => $this->store_vendor_id,
                    'description' => $this->input->post('pro_discription'),
                    'sku' => $this->input->post('pro_sku'),
                    'regular_price' => $this->input->post('regular_price'),
                    'sale_price' => $this->input->post('sale_price'),
                    'manage_stock' => true,
                    'stock_quantity' => $this->input->post('stock_qty'),
                    'stock_status' => $this->input->post('stock_status'),
					'shipping_class'=> isset($shipping_classes['slug']) ? $shipping_classes['slug'] : '',
                    'shipping_class_id' =>$shipping_class_id,
                    //'reviews_allowed' => $this->input->post('reviews_allowed'),
                    'categories' => $updateCate,
                    'attributes' => $attributesArr,
                    //'default_attributes' => $this->input->post('default_attributes'),
					'weight' => $this->input->post('weight'),
					'tags' =>$tags,
					'dimensions' =>array(	"length"=> $this->input->post('length'),
											"width"=> $this->input->post('width'),
											"height"=> $this->input->post('height')
										),
					'parent_id' => $this->store_vendor_id, // parent_id use as vendor id
					'meta_data' => [array('key'=>'vendor_id', 'value'=>$this->store_vendor_id)],
					'meta_data' => [array('key'=>'discount', 'value'=>$discount)],
					
				];
				
				
				/*$img_key_Arr[] = Array
						(
							'id' => 356,
							'date_created' => '2022-06-20T01:19:25',
							'date_created_gmt' => '2022-06-20T11:19:25',
							'date_modified' => '2022-06-20T01:19:25',
							'date_modified_gmt' => '2022-06-20T11:19:25',
							'src' => 'https://ecomm-test.discovered.tv/wp-content/uploads/2022/06/76a66d13f20a773e3aa713347eb0400f.jpg',
							'name' => '76a66d13f20a773e3aa713347eb0400f.jpg',
							'alt' => ''
						);
				$img_key_Arr[] =  array('src' => 'https://ecomm-test.discovered.tv/wp-content/uploads/2022/06/76a66d13f20a773e3aa713347eb0400f.jpg');*/
				
				
				if(!empty($img_key_Arr)){
					$data['images'] = $img_key_Arr;
				}
               
				
                if(isset($_POST['id']) && !empty($_POST['id'])){
					$results    = $this->woocommerce->put('products/'.$_POST['id'], $data);
                    $this->respMessage  = 'Product updated Successfully.';
                }else{
					/*foreach($unlinkImgName as $img){
						if(!empty($img)){
							unlink('./'.$prodpath.basename($img));
						}
					}*/
					$results    = $this->woocommerce->post('products', $data);
                    $this->respMessage  = 'Product Added Successfully.';
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

    function deleteProducts(){
        $resp = [];
        $rules = array(
            array( 'field' => 'id', 'label' => 'Id', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            try {
                $id = $this->input->post('id');
                $prodpath = 'uploads/admin/store_prod/';
                $result   = $this->woocommerce->get('products/'. $id);
                $result   = json_decode(json_encode($result), true);
                if(isset($result['image']['src']) &&  !empty($result['image']['src'])) {
                    $img_key = $result['image']['src'];
                    unlink('./'.$prodpath.basename($img_key));
                }
                $results = $this->woocommerce->delete('products/'.$id, ['force' => true]);
               
                $results  = $this->get_result($results);
               
                if(isset($results['id'])){
                    $this->statusCode   =  1;
                    $this->statusType   = 'Success';
                    $this->respMessage  = 'Product Deleted Successfully.';
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

/*********************Categories Section End *************************/
	
	function getAttrbuteTermsByAjax(){
		$resp = [];
        $rules = array(
            array( 'field' => 'attribute_id', 'label' => 'Attribute Id', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
			$this->wcClient();
			$start 		= 	0;
			$length 	= 	100;
			$search     =   isset($_POST['term']) ? $_POST['term'] : '';
			
			$attribute_id =	$this->input->post('attribute_id');

			$results    =   $this->woocommerce->get('products/attributes/'.$attribute_id.'/terms',['offset' => $start,'per_page' => $length ,'search' => $search ]);
			$results    =   $this->get_result($results);
			$terms = [];
			$termHtml = '<option value="">Select Options</option>';
			if(!empty($results)){
				foreach($results as $term){
					$name = $term['name'];
					$colorPicker = '';
					if($attribute_id ==24){
						$name = $term['slug'];
						$colorPicker = '<span class="color-preview" style="background-color:'.$term['name'].'"></span>';
					}
					
					//$termHtml .='<option value="'.$term['name'].'">'.$name.'</option>'; 
					//$terms[] = array('id' =>$term['id'] , 'text'=>$term['name']);
					
					$terms[] = [
					   'id' => $term['name'],
					   'text' => $colorPicker.'<span>'.$name.'</span>',
					   'html' => $colorPicker.'<span>'.$name.'</span>'
					];
					
				}
			}
			$this->statusCode   =  1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Terms Fetch Successfully.';
			//$resp['data'] = $termHtml;
			$resp['terms'] = $terms;
			
		
		}else{
            $this->validation_error();
        }
		$this->show_my_response($resp);
		
	}
	
	function getProTaglist(){
		$this->wcClient();
		$tagName = '';
		if(isset($_POST['query']) && !empty($_POST['query'])){
			$tagName = $_POST['query'];
		}
		$results    =   $this->woocommerce->get('products/tags/',['name' => $tagName ]);
		$results    =   $this->get_result($results);
		$tags = [];
		foreach($results as $list){
			$tags[] = $list['name'];
		}
		echo json_encode($tags);
	}
	
	function deleteMedia(){
		 $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'version' => 'wp/v2']  
        );
		$id = 341;
		$results = $this->woocommerce->delete('media/'.$id, ['force' => true]);
               
		$results  = $this->get_result($results);
		print_R($results);die;
	}
    
	public function updateOrderStatus(){
		$resp = [];
        $rules = array(
            array( 'field' => 'order_id', 'label' => 'order Id', 'rules' => 'trim|required'),
            //array( 'field' => 'vendor_id', 'label' => 'vendor Id', 'rules' => 'trim|required'),
			array( 'field' => 'order_status', 'label' => 'order status', 'rules' => 'trim|required'),
        );
        $this->form_validation->set_rules($rules);
		if($this->form_validation->run()){	
			$this->wcClient();
			
			$orderId 	 = $_POST['order_id'];
			$orderStatus = $_POST['order_status'];
			//$vendor_id   = $_POST['vendor_id'];
			$data = array('status'=>$orderStatus);
			$this->woocommerce->put('orders/'.$orderId, $data);
			$this->DatabaseModel->access_database('vendor_orders_details','update',array('order_status'=> $orderStatus),['suborder_id'=>$orderId]);
			$this->statusCode   =  1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Order status change Successfully.';
		}else{
            $this->validation_error();
        }
		$this->show_my_response($resp);
	}
	
	
}

	