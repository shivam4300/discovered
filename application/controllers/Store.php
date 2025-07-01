<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/wocommerce/autoload.php";

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class Store extends CI_Controller {
	
	Private $woocommerce = '';
	private $uid;
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	Private $store_vendor_id = 0; 
	
	public function __construct(){
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->library(array('share_url_encryption', 'form_validation', 'cart'));
		$this->load->helper('button');
		
		$this->uid = is_login();
		
		$store = get_store_status();
		$this->store_vendor_id = isset($store['store_vendor_id'])?$store['store_vendor_id']:0;
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
	
	private function wcClient($v = 'v3'){
        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'version' => 'wc/'.$v]  
        );
    }
	//
	private function wlClient($v = 'v3'){
        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'wp_api' => true, 'version' => 'wc/'.$v]  
        );
    }
	//
	private function wcmpClient(){
        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'version' => 'wcmp/v1']  
        );
    }
	
	Private function get_result($results){
        return json_decode(json_encode($results), true);
    }
	
	
	public function dominos(){
		$data['page_info'] = array('page'=>'dominos','title'=>'Dominos');
		echo $this->load->view('home/inc/header',$data,true);
		echo '<iframe width="100%" height="780" src="https://www.dominos.com/en/" title="Dominos" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		echo $this->load->view('home/inc/footer',$data,true);
	}
	
	public function homedepot(){ 
		$data['page_info'] = array('page'=>'homedepot','title'=>'Homedepot');
		echo $this->load->view('home/inc/header',$data,true);
		echo '<iframe width="100%" height="780" src="https://www.homedepot.com/" title="Homedepot" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		echo $this->load->view('home/inc/footer',$data,true);
	}
	
	
	public function shop(){
		
		$uid = $this->uid;
		
		$other_user = isset($_GET['user'])?$_GET['user']:'';
		
		$data = [ 	
			'uid'			=> $uid,
			'letest_post_id'=> '',
			'user_id'		=> '',
			'web_mode'		=> '',
			'sub_catname'	=> '',
			'musics'		=> [],
			'movies'		=> [],
			'televisions'	=> [],
			'incomplete_video'=> [],
		];
		
			
		$data['other_user'] = $other_user;
		
		$getUser = [];
		if(!empty($other_user)){
			$getUser  	= $this->DatabaseModel->select_data('user_id,sigup_acc_type','users use INDEX(user_id)',['user_uname'=>$other_user],1);
			$uid		= isset($getUser[0]['user_id'])? $getUser[0]['user_id'] : $uid ;
		}
		
		if(WhoAmI($uid) != 4 && isset($getUser[0]['sigup_acc_type']) && $getUser[0]['sigup_acc_type'] == 'standard'){	
			$data['is_session_uid'] = (is_session_uid($uid))?1:0;
			$data['uid'] = $uid;
			
			$userDetail	= $this->query_builder->user_list(array(
							'field' =>'users.user_id,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_type,users_content.aws_s3_profile_video,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users_content.uc_type,users.referral_by,users_content.is_fc_member',
							'where' => 'user_id='.$uid,
						));
			
			if(isset($userDetail['users'])){
				$userDetail = $userDetail['users'];
				
				if(isset($userDetail[0]['referral_by']) && !empty($userDetail[0]['referral_by'])){
					$referral_by 	= $userDetail[0]['referral_by'];
					$referral_name 	= $this->DatabaseModel->select_data('user_name','users',array('user_uname'=>$referral_by));
					if(isset($referral_name[0]['user_name']) && !empty($referral_name[0]['user_name'])){
						$data['referral_name'] 	=	$referral_name[0]['user_name'];
						$data['referral_by']  	=	$referral_by;
					}
				}
					
				if(!empty($userDetail)) 
					$data['userDetail'] = $userDetail;
				else
					redirect(base_url());
				
				
				if(!empty($userDetail[0]['uc_type'])){
					$sub_cat = $this->DatabaseModel->select_data('category_name','artist_category','category_id IN ('.$userDetail[0]['uc_type'].')');
					
					$size = (sizeof($sub_cat) <= 5)?sizeof($sub_cat):5;
					for($i=0;$i < $size; $i++ ){
						$data['sub_catname'] .=  $sub_cat[$i]['category_name'].',';
					}
					$data['sub_catname'] = rtrim($data['sub_catname'] ,", ");
				}
				
				
				$field ="channel_post_video.mode,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type";
				
				$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0';
					
				$globalCond =  $this->common->GobalPrivacyCond($uid);
				
				$where .=  $globalCond;	  
				
				$join  = array(
								'multiple',
								array(
									array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
										  'left'),
									array('users' , 'users.user_id = channel_post_video.user_id'),
								)
							);
				
				$Order = array('channel_post_video.post_id','DESC');
			
				
				$joinsPlaylist  = array
						(
							'multiple',
							array(
								array('channel_post_video','channel_post_video.post_id = channel_video_playlist.first_video_id','left'),
								array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id AND channel_post_thumb.active_thumb =1',
								'left'),
							)
						);
						
				$wherePlaylist = 'channel_video_playlist.user_id = '.$uid . ' '. $this->common->GobalPrivacyCond($uid,'channel_video_playlist'); 
				 
				$OrderPlaylist = array('channel_video_playlist.playlist_id','DESC');
				
				$data['playlist'] = $this->DatabaseModel->select_data("channel_video_playlist.first_video_id,channel_post_video.user_id,channel_video_playlist.playlist_id,channel_video_playlist.title,channel_video_playlist.video_ids,channel_post_thumb.image_name",'channel_video_playlist',$wherePlaylist,10,$joinsPlaylist,$OrderPlaylist);
				
				array_push($join[1],array(	'website_mode','website_mode.mode_id = channel_post_video.mode','left'));
				
				$where_cond = ' channel_post_video.featured_by_user = 1 AND ' . $where ; 
				
				$field = 'channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video';
				
				$channel_video  = $this->DatabaseModel->select_data($field ,'channel_post_video',$where_cond,1,$join);
				
				if(!isset($channel_video[0]['post_id']))
				$channel_video  = $this->DatabaseModel->select_data($field,'channel_post_video',$where,1,$join,$Order);
				
				
				if(isset($channel_video[0]['post_id']) && !empty($channel_video[0]['post_id'])){
					$vCount   				= 	sizeof($channel_video);
					$index 					= 	0;
					$post_key 				= 	$channel_video[$index]['post_key'];
					$iva_id   				= 	$channel_video[$index]['iva_id'];
					$up_video 				= 	$channel_video[$index]['uploaded_video'];
					
					$data['single_video'] 	= 	base_url().$this->common->generate_single_content_url_param($post_key , 2);
					
					$data['feature_video'] 	= 	$this->share_url_encryption->FilterIva($uid, $iva_id,'',$up_video,'','.m3u8');
					$data['feature_video'] 	= 	isset($data['feature_video']['video'])?str_replace("m3u8","mp4",$data['feature_video']['video']):'';
					
					$data['feature_pid']  	= 	$channel_video[$index]['post_id'];
					$data['title']    		= 	$channel_video[$index]['title'];
						
				}
				
				$cover 					= 	$userDetail[0]['aws_s3_profile_video'];
				$url					=	"";
				$preview				=	"";
				if(!(empty($cover))){
					$url 				= 	AMAZON_URL .$cover;
					$preview			=	$this->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4');
					$preview			=	isset($preview['video'])?$preview['video']:'';
				}
				$data['cover_video'] 	= 	array('url'=>$url,'preview'=>$preview);
				
				$data['DP'] 			= 	!empty($userDetail[0]['uc_pic']) ? create_upic($uid, $userDetail[0]['uc_pic']) : user_default_image() ;
								
				$data['metaData'] 		= 	array(
						'title' 		=> 	$userDetail[0]['user_name'] . ' - Discovered', 
						'description' 	=> 	'', 
						'image' 		=> 	$data['DP']
				);
				
				$data['page_info'] = array('page'=>'my_shop','title'=>'My Channel');
				// echo '<pre>';
				// print_r($data);die;
				$this->load->view('home/inc/header',$data);
				$this->load->view('home/shop/my_store',$data);
				$this->load->view('common/notofication_popup');
				$this->load->view('home/inc/footer',$data);
			}else{
				redirect(base_url());
			}
	
		}else{
			redirect(base_url('profile?user='.$other_user));
		}
	}
	
	
	
	public function test($page = null){
		$data['page_info'] = array('page'=>'store','title'=>'My playlist');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/shop/'.$page,$data);
        $this->load->view('home/inc/footer',$data);
	
	}
	
	
	public function single_product($pro_id=''){
		if(!empty($pro_id)){
			$data['prod_id'] = $pro_id;
			$this->wcClient();
			$proDetails  			= $this->woocommerce->get('products/'.$pro_id);
			$data['single_product'] = $this->get_result($proDetails);
			$data['page_info'] 		= array('page'=>'store_single_page','title'=>'Single product');
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/shop/single_product',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);	
		}else{
			redirect(base_url()); 
		}
	}
	
	
	function getSingleProdAjax(){
		$resp = [];
		if(!empty($_POST['prod_id'])){
			$pro_id = $_POST['prod_id'];
			$this->wcClient();
			$proDetails  			= $this->woocommerce->get('products/'.$pro_id);
			$data['single_product'] = $this->get_result($proDetails);
			$data['single_product']['rating_star'] = showRatingStar(round($data['single_product']['average_rating']));
			$data['single_product']['average_rating'] = round($data['single_product']['average_rating']);
			$data['single_product']['fav'] = $this->checkFavorite($pro_id);
			$related_product = [] ;
			$data['variants'] = '';
			$other_related_products = [];
			if(!empty($data['single_product'])){
				$related_product[]  = $data['single_product'];
				$related_ids 	  	= $data['single_product']['related_ids'];
				$vendor_id 	  		= $data['single_product']['vendor'];
				
				if(!empty($related_ids)){
					$relatedpro  = $this->woocommerce->get('products',['include'=>$related_ids]);
					$rtd 		 = $this->get_result($relatedpro);
					if(!empty($rtd)){
						$related_product = array_merge($related_product,$rtd);
						$garry = [];
						foreach($related_product as $r){
							if($r['vendor'] == $vendor_id){  //same vendor related products
								if(!empty($r['attributes'])){
									$key_size = array_search('Size' , array_column($r['attributes'],'name'));
									$key_color = array_search('Color' , array_column($r['attributes'],'name'));
									$garry[ $r['attributes'][$key_size]['options'][0] ][$r['id']] = $r['attributes'][$key_color]['options'][0];
								}
							}else{
								$other_related_products[] = $r; // other vendor related products
							}
						}
						$data['variants'] = $garry;
					}
				}
				
				$autoplay	= array("4000","4500","5000");
				$random_keys= array_rand($autoplay,1);
				$auto 		= $autoplay[$random_keys];
				$data['other_related_products'] = array(	'color'=>"bg-white",
															'title'=>'Customers Also Viewed',
															'type'=> 'customers_also_viewed',
															'href' =>'',
															'auto' =>$auto, 
															'prodList'=> $this->getProdDetails($other_related_products),
															'errorImg' => base_url('repo/images/thumbnail.jpg')
														);
														
														
			
				$data['related_product']  		= $this->getProdDetails($related_product);

				$this->statusCode   =  1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Product fetch Successfully.';
			}
		}
		$resp = array('status'=>1,'data'=>$data);
		echo json_encode($resp);
	}
	
	public function getRelatedProducts($related_ids = []){
		$rtd = [];
		if(!empty($related_ids)){
			$this->wcClient();
			$start 		= 	0; //$_GET['start'];
			$length 	= 	10; //$_GET['length'];
			$search     =   ''; //$_GET['search']['value'];
			$leadsCount = 	0;
			$relatedpro  = $this->woocommerce->get('products',['include'=>$related_ids, 'offset' => $start,'per_page' => $length, 'search' => $search]);
			$rtd 		 = $this->get_result($relatedpro);
		}
		return $rtd; 
	}
	
	 
	
	public function getProductsList(){
		$slidersTypes = array(
								array('type'=>'our_new_product', 'title'=>'Our New Product Teasers','orderby'=>'date'),
								array('type'=>'most_popular_products', 'title'=>'Most Popular Products', 'orderby'=>'popularity'),
								array('type'=>'top_rated_products', 'title'=>'Top Rated Products', 'orderby'=>'rating'),
						);
		$data=array();
		//if(isset($_POST['uid']) && !empty($_POST['uid'])){
			$vendor_id  = $this->store_vendor_id; //$_POST['uid'];
			$this->wcClient();
			$data 		= 	array();
			$start 		= 	isset($_POST['start']) ? $_POST['start'] : 0;
			$length 	= 	isset($_POST['limit']) ? $_POST['limit'] : 1;
			$search     =   isset($_POST['search']) ? $_POST['start'] : '';
			$leadsCount = 	0;
			if($start > 0){
				$length += $start; 
			}
				for($i=$start; $i<$length; $i++){
					if(isset($slidersTypes[$i])){
						$slider 	= $slidersTypes[$i];
						$results    = $this->woocommerce->get('products?vendor='.$vendor_id,['offset' => 0,'per_page' =>10 ,'search' => $search , 'orderby'=>$slider['orderby']]);
						$results  	= $this->get_result($results);
						if(!empty($results)){
							$this->statusCode   =  1;
							$this->statusType   = 'Success';
							$this->respMessage  = 'Products Fetch Successfully.';
							
							$autoplay	= array("4000","4500","5000");
							$random_keys= array_rand($autoplay,1);
							$auto 		= $autoplay[$random_keys];
							$products 	= array('color'=>"bg-white",
												'title'=>$slider['title'],
												'type'=> $slider['type'],
												'href' =>'',
												'auto' =>$auto, 
												'prodList'=> $this->getProdDetails($results),
												'errorImg' => base_url('repo/images/thumbnail.jpg')
											);
							array_push($data,$products);
						}	
					}
				}
			$resp = array('status'=>1,'data'=>$data);				
		//}
		echo json_encode($resp);
	}
	
	function getProdDetails($products = array()){
		if(!empty($products)){
			foreach($products as $k=>$v){
				// DEFAULT_CURRENCY_SYMBOL.
				$products[$k]['sale_price'] 		= $products[$k]['sale_price'];
				$products[$k]['regular_price'] 		= $products[$k]['regular_price'];
				$products[$k]['single_prod_link'] 	= base_url('store/single_product/'.$v['id']);
				$products[$k]['rating_star'] 		= showRatingStar($products[$k]['average_rating']); //helper function
				$products[$k]['fav'] 				= $this->checkFavorite($v['id']);
				$products[$k]['average_rating']		= round($products[$k]['average_rating']);
			}
		}
		return $products;
	}
	
	private function checkFavorite($id){
		if (isset($this->uid)) {
			$data_['user_id'] = $this->uid;
			$check = $this->DatabaseModel->access_database('users_woocommerce_wishlist','select',$this->uid, $data_);
				if (count($check) > 0) {
					
					$wishlist_array = explode (",", $check[0]['wish_list']);
					$checkInWislist = in_array($id, $wishlist_array);
					if ($checkInWislist) {
						$res = 1;
					}else{
						$res = 0;
					}
					return $res;
				}else{
					return 0;
				}
		}else{
			return $res = 0;
		}
	}

	public function payment(){
		$data['page_info'] = array('page'=>'store_payment','title'=>'Payment');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/shop/payment',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('home/inc/footer',$data);	
	}
	
	public function shipping(){
		
		if(!empty($this->uid) && isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])){
			$data['page_info'] = array('page'=>'store','title'=>'Shipping');
		
			$this->wcClient();
			// $search  			= 'nitesh.modi@himanshusofttech.com';
			$search  			= $_SESSION['user_email'];
			$results    		= $this->woocommerce->get('customers',['email' =>$search , 'role'=>'customer']);
			$data['customer']  	= $this->get_result($results);
			
			$data['countries']  = $this->DatabaseModel->select_data('country_id,country_name','country',array('status'=>1));
		
			$uc_state = isset($data['customer'][0]['shipping']['state']) ? $data['customer'][0]['shipping']['state'] : '';
			$data['state_name'] = $this->DatabaseModel->select_data('name','state',array('id'=> $uc_state )); 
		
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/shop/shipping',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);	
		}else{
			redirect(base_url());
		}
		
	}
	
	public function cart(){
		$data['page_info'] = array('page'=>'store','title'=>'My Cart');
		$data['uid'] = $this->uid;
		
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/shop/cart',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('home/inc/footer',$data);	
		
	}
	
	
	
	public function addToCartAjax(){
		$resp = [];
		if(!empty($_POST['prod_id'])){
			
			$product_id  = $_POST['prod_id'];
			$qty  		 = $_POST['qty'];
			$this->wcClient();
			$proDetails  = $this->woocommerce->get('products/'.$product_id);
			$product 	 = $this->get_result($proDetails);
			
			if(!empty($product)){
				
				$cart=$this->cart->contents(); 
				
				//$qty = 0;	
				$rowid = '';
				foreach($cart as $item)
				{
					if($item['id'] == $product_id){
						//$qty=$item['qty'];
						$rowid = $item['rowid'];
					}
				}
				
				if($product['stock_quantity'] > $qty)
				{
					if($rowid !==''){
						
						/*$data = array(
										'rowid'=>$rowid,
										'qty'=> $qty
								);
						$this->cart->update($data);*/
						
						$this->respMessage  = 'Already Added To Cart.';
					}else{
						
						$res = preg_replace("/[^a-zA-Z0-9\s]/", "", $product['name']);	
						$data = array(
										'id'      => $product['id'],
										'qty'     => $qty,
										'price'   => $product['sale_price'],
										'name'    => $res,
										'sku_no'  => $product['sku'],
										'image'   => !empty($product['images']) ? $product['images'][0]['src'] : '',
										'options' => array(	'rating'=>round($product['average_rating']),
															'rating_star' =>showRatingStar($product['average_rating']),
															'stock_quantity' =>$product['stock_quantity']
														),
										'vendor_id'=>$product['vendor']
									  );
						$this->cart->insert($data);
						
						$this->respMessage  = 'Added To Cart.';
						
					}
					
					$resp['cart']  		= $this->getMyCartDetails();
					$this->statusCode   =  1;
					$this->statusType   = 'Success';
				}else{
					$this->respMessage  = 'Out of stock.';
				}
			}
		}
		$this->show_my_response($resp);
	}
	
	
	public function getMyCartDetails(){
		$data['cartItems']  = $this->cart->contents();
		$data['TotalItem']  = $this->cart->total_items();
		$data['amount'] 	= $this->cart->total();
		return $data;
	}
	
	public function loadMyCart(){
		$resp['cart']  		= $this->getMyCartDetails();
		$this->statusCode   =  1;
		$this->statusType   = 'Success';
		$this->respMessage  = 'Cart Items.';
		$this->show_my_response($resp);
	}
	
	public function minus_ajax_cart()
	{
		$resp = [];
		$rules = array(
                    array( 'field' => 'proRowid', 'label' => 'Product row id', 'rules' => 'trim|required'),
                );
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$product_rowid = $this->input->post('proRowid');
			$qty		   = $this->input->post('qty') - 1;
			$data = array(
						  'rowid' => $product_rowid,
						  'qty'   => $qty,
						  );
						
			$this->cart->update($data);
			$resp['cart']  		= $this->getMyCartDetails();;
			$this->statusCode   =  1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Cart update Successfully.';
			
		}else{
			$this->validation_error();
		} 
		$this->show_my_response($resp);
	}
	
	
	public function plus_ajax_cart()
	{
		$resp = [];
		$rules = array(
                    array( 'field' => 'proid', 'label' => 'Product id', 'rules' => 'trim|required'),
                    array( 'field' => 'proRowid', 'label' => 'Product row id', 'rules' => 'trim|required'),
                );
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$product_id = $this->input->post('proid');
			$product_rowid = $this->input->post('proRowid');
			$qty		   = $this->input->post('qty') + 1;
			
			$this->wcClient();
			$proDetails  = $this->woocommerce->get('products/'.$product_id);
			$product 	 = $this->get_result($proDetails);
			
			if(!empty($product) && $product['stock_quantity'] >= $qty){
				$data = array(
							  'rowid' => $product_rowid,
							  'qty'   => $qty,
							  );
							
				$this->cart->update($data);
				$this->statusCode   =  1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Cart update Successfully.';
			}else{
				$this->statusCode   =  1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Out Of Stock.';
			}
			
			 
			$resp['cart']  		= $this->getMyCartDetails();
			
		}else{
			$this->validation_error();
		} 
		$this->show_my_response($resp);
	}

	public function remove_ajax_cartitem(){
		$resp = [];
		$rules = array(
                    array( 'field' => 'proRowid', 'label' => 'Product row id', 'rules' => 'trim|required'),
                );
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$product_rowid=$this->input->post('proRowid');
			
			$cart=$this->cart->contents();
			$rowid="all";
			if(!empty($cart))
			{
				foreach($cart as $item)
				{
					if($item['rowid'] === $product_rowid){
						$rowid=$item['rowid'];
					}
				}


				if ($rowid==="all"){  
				
					$this->cart->destroy();  
					
				}else{
					
					$data = array(
						'rowid'   => $rowid,
						'qty'     => 0
					   );
										 
					$this->cart->update($data);
				}
			}
			
			$resp['cart']  		= $this->getMyCartDetails();
			$this->statusCode   =  1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Item Removed Successfully.';
		}else{
			$this->validation_error();
		} 
		$this->show_my_response($resp);
	}
	
	
	function addShippingAddressOld(){
		$resp = [];
		$rules = array(
                    array( 'field' => 'ship_fname', 'label' => 'first name', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_lname', 'label' => 'last name', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_phone', 'label' => 'phone nomber', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_email', 'label' => 'email address', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_country', 'label' => 'country', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_state', 'label' => 'state', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_city', 'label' => 'city', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_zipcode', 'label' => 'zipcode', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_address', 'label' => 'address', 'rules' => 'trim|required'),
                );
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){	
			$this->wcClient();
			$post = $this->input->post();
			
			$country_name  = $this->DatabaseModel->select_data('country_name,iso','country',array('country_id'=>$post['ship_country']));
			$country_code  = isset($country_name[0])? $country_name[0]['iso'] : '';
			
			$state_name    = $this->DatabaseModel->select_data('name','state',array('id'=> $post['ship_state'])); 
			$state_name    = isset($state_name[0])? $state_name[0]['name'] : '';
			
			$data = [
					'email' 	 => $post['ship_email'],
					'password' 	 => $post['ship_email'],
					'first_name' => $post['ship_fname'],
					'last_name'  => $post['ship_lname'],
					'username'   => $post['ship_fname'].''.$post['ship_lname'],
					'shipping'    => [
						'first_name'=> $post['ship_fname'],
						'last_name' => $post['ship_lname'],
						'company'	=> '',
						'address_1' => $post['ship_address'],
						'address_2' => '',
						'city' 		=> $post['ship_city'],
						'state' 	=> $post['ship_state'],
						'postcode' 	=> $post['ship_zipcode'],
						'country' 	=> $post['ship_country'],
						'email' 	=> $post['ship_email'],
						'phone' 	=> $post['ship_phone']
					],
					/*'shipping' => [
						'first_name' => 'John',
						'last_name' => 'Doe',
						'company' => '',
						'address_1' => '969 Market',
						'address_2' => '',
						'city' => 'San Francisco',
						'state' => 'CA',
						'postcode' => '94103',
						'country' => 'US'
					]*/
				];
			$data['billing'] = [];
			if(isset($post['is_same_billing_add'])){	
				$data['billing'] = $data['shipping'];	
			}
			$user_id  = '';
			try {
				if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
					unset($data['username']);
					$results    = $this->woocommerce->put('customers/'.$_POST['user_id'], $data);
					$user 	    = $this->get_result($results);
					$user_id    = $user['id'];
					//$this->respMessage  = 'Customer updated Successfully.';
				}else{
					
					$results    = $this->woocommerce->post('customers', $data);
					$user 	    = $this->get_result($results);
					$user_id    = $user['id'];
					//$this->respMessage  = 'Customer Added Successfully.';
				}
			}catch(Exception $e) {
				$mess = explode('already registered',$e->getMessage());
				if(isset($mess[1])){
					$this->respMessage  = 'An account is already registered as a vendor with your email address,Please create a new account for purchase this product.';
					return $this->show_my_response();die;
				}
				
			}
			
			$cart = $this->cart->contents();
			if(!empty($user_id) && !empty($cart)){
				
				$data['billing']['country']  = $country_code;
				$data['billing']['state']    = $state_name;
				$data['shipping']['country'] = $country_code;
				$data['shipping']['state']   = $state_name;
				$order = [];
				$line_items = [];
				$vendor_order = [];
				
				foreach($cart as $item){
					$vendor_order[$item['vendor_id']][] =  array('product_id'=>$item['id'], 'quantity' =>$item['qty']);
				}
				
				if(!empty($vendor_order)){
					$super_order_id = random_int(100000, 999999);
					foreach($vendor_order as $v){
						
						$line_items = $v;
						$orderData  = [
							//'parent_id'=>$super_order_id,
							'payment_method' => 'PayPal',
							'payment_method_title' => 'PayPal Bank Transfer',
							'customer_id'=> $user_id,
							//'set_paid' => true, 
							'billing'  => $data['billing'],
							'shipping' => $data['shipping'],
							'line_items' => $line_items,
							'meta_data' => [array('key'=>'customer_id', 'value'=>$user_id)],
							/*'shipping_lines' => [
								[
									'method_id' => 'flat_rate',
									'method_title' => 'Flat Rate',
									'total' => '10.00'
								]
							]*/
						];
					
						$orderResults  = $this->woocommerce->post('orders', $orderData);
						$order[] 	   = $this->get_result($orderResults);
					}
				}
				$_SESSION['orderData'] = $order;
			}
			$resp['data'] = $data;
			$resp['redirect']   = 'store/payment';
			$this->statusCode   =  1;
			$this->statusType   = 'Success';
			//$this->respMessage  = 'Shipping address added Successfully.';
			
			
		}else{
			$this->validation_error();
		} 
			
		$this->show_my_response($resp);
	}
	
	function addShippingAddress(){
		$resp = [];
		$rules = array(
                    array( 'field' => 'ship_fname', 'label' => 'first name', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_lname', 'label' => 'last name', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_phone', 'label' => 'phone nomber', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_email', 'label' => 'email address', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_country', 'label' => 'country', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_state', 'label' => 'state', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_city', 'label' => 'city', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_zipcode', 'label' => 'zipcode', 'rules' => 'trim|required'),
                    array( 'field' => 'ship_address', 'label' => 'address', 'rules' => 'trim|required'),
                );
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){	
			$this->wcClient();
			$post = $this->input->post();
			
			$country_name  = $this->DatabaseModel->select_data('country_name,iso','country',array('country_id'=>$post['ship_country']));
			$country_code  = isset($country_name[0])? $country_name[0]['iso'] : '';
			
			$state_name    = $this->DatabaseModel->select_data('name','state',array('id'=> $post['ship_state'])); 
			$state_name    = isset($state_name[0])? $state_name[0]['name'] : '';
			
			$data = [
					'email' 	 => $post['ship_email'],
					'password' 	 => $post['ship_email'],
					'first_name' => $post['ship_fname'],
					'last_name'  => $post['ship_lname'],
					'username'   => $post['ship_fname'].''.$post['ship_lname'],
					'shipping'    => [
						'first_name'=> $post['ship_fname'],
						'last_name' => $post['ship_lname'],
						'company'	=> '',
						'address_1' => $post['ship_address'],
						'address_2' => '',
						'city' 		=> $post['ship_city'],
						'state' 	=> $post['ship_state'],
						'postcode' 	=> $post['ship_zipcode'],
						'country' 	=> $post['ship_country'],
						'email' 	=> $post['ship_email'],
						'phone' 	=> $post['ship_phone']
					],
					/*'shipping' => [
						'first_name' => 'John',
						'last_name' => 'Doe',
						'company' => '',
						'address_1' => '969 Market',
						'address_2' => '',
						'city' => 'San Francisco',
						'state' => 'CA',
						'postcode' => '94103',
						'country' => 'US'
					]*/
				];
			$data['billing'] = [];
			if(isset($post['is_same_billing_add'])){	
				$data['billing'] = $data['shipping'];	
			}
			$user_id  = '';
			try {
				if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
					unset($data['username']);
					$results    = $this->woocommerce->put('customers/'.$_POST['user_id'], $data);
					$user 	    = $this->get_result($results);
					$user_id    = $user['id'];

					$data_where['user_id'] = $this->uid;
					$data_update['store_customer_id'] = $user_id;
					$this->DatabaseModel->access_database('users','update', $data_update, $data_where);
					$this->session->set_userdata('store_customer_id', $user_id);
					//$this->respMessage  = 'Customer updated Successfully.';
				}else{
					
					$results    = $this->woocommerce->post('customers', $data);
					$user 	    = $this->get_result($results);
					$user_id    = $user['id'];
					
					$data_where['user_id'] = $this->uid;
					$data_update['store_customer_id'] = $user_id;
					$this->DatabaseModel->access_database('users','update', $data_update, $data_where);
					$this->session->set_userdata('store_customer_id', $user_id);
					//$this->respMessage  = 'Customer Added Successfully.';
				}
			}catch(Exception $e) {
				$mess = explode('already registered',$e->getMessage());
				if(isset($mess[1])){
					$this->respMessage  = 'An account is already registered as a vendor with your email address,Please create a new account for purchase this product.';
					return $this->show_my_response();die;
				}
				
			}
			
			$cart = $this->cart->contents();
			if(!empty($user_id) && !empty($cart)){
				$line_items = [];
				// $customer_status = ['customer_req' => 0];
				// $customer_status = array('customer_req' => 0);
				foreach($cart as $item){
					$line_items[] = array('product_id'=>$item['id'], 'quantity' =>$item['qty'], 'meta_data' => [ ['key'=>'customer_req', 'value'=> '0,0'] ]);
				}
				
				$data['billing']['country']  = $country_code;
				$data['billing']['state']    = $state_name;
				$data['shipping']['country'] = $country_code;
				$data['shipping']['state']   = $state_name;
				$super_order_id = rand(100000,999999);
				$orderData = [
					//'customer_note'=>"{$super_order_id}",
					'payment_method' => 'PayPal',
					'payment_method_title' => 'PayPal Bank Transfer',
					'customer_id'=> $user_id,
					//'set_paid' => true, 
					'billing'  => $data['billing'],
					'shipping' => $data['shipping'],
					'line_items' => $line_items,
					'meta_data' => [array('key'=>'customer_id', 'value'=>$user_id)],
					/*'shipping_lines' => [
						[
							'method_id' => 'flat_rate',
							'method_title' => 'Flat Rate',
							'total' => '10.00'
						]
					]*/
				];
				// print_r($orderData);
				// die();
				$orderResults  = $this->woocommerce->post('orders', $orderData);
				$order 	       = $this->get_result($orderResults);
				$_SESSION['orderData'] = $order;
				$this->saveOrdersOnLocalDB();
				//print_r($order);die;
			}
			$resp['data'] = $data;
			$resp['redirect']   = 'store/payment';
			$this->statusCode   =  1;
			$this->statusType   = 'Success';
			//$this->respMessage  = 'Shipping address added Successfully.';
			
			
		}else{
			$this->validation_error();
		} 
			
		$this->show_my_response($resp);
	}
	
	
	function updateCountryCode(){
		
		
		
		$vh_countries  = $this->DatabaseModel->select_data('*','vh_country');
		//print_R($vh_countries);die;
		//foreach($vh_countries as $con){
			
			/*$updateArr = array( 'iso'=>$con['iso'],
								'iso3'=>$con['iso3'],
								'numcode'=>$con['numcode'],
								'phonecode'=>$con['phonecode'],
								);*/
								
			//echo "<pre>";
			//print_R($updateArr);
			
			//$this->DatabaseModel->access_database('country','update',$updateArr,['country_name'=>$con['name']]);
			
			
		//}
		
		
		
		
	}
	
	public function saveOrdersOnLocalDB(){
		$this->wcClient('v1');
		$orderResults  = $this->woocommerce->get('orders',['offset' => 0,'per_page' =>20,'order'=>'desc']);
		$orders 	   = $this->get_result($orderResults);
		if(!empty($orders)){
			$pids = [];
			foreach($orders as $o){
				foreach($o['line_items'] as $item){
					$pids[] = $item['product_id'];
				}
			}
			
			$this->wcClient('v3');
			$results    = $this->woocommerce->get('products', ['include'=>array_unique($pids)]);
			$pro  		= $this->get_result($results);
			$vendor 	= array_column($pro,'vendor','id');
			
			$suborder = [];
			foreach($orders as $o){
				if($o['parent_id'] != 0){
					$vendor_id 	= $vendor[$o['line_items'][0]['product_id']];
					$suborder   = array('vendor_id'=>$vendor[$o['line_items'][0]['product_id']],
									  'main_order_id'	=>$o['parent_id'],
									  'suborder_id'  	=>$o['id'],
									  'customer_id'  	=>$o['customer_id'],
									  'total_amount' 	=>$o['total'],
									  'order_status' 	=>$o['status'],
									  'payment_method'	=>$o['payment_method'],
									  'date_created'  	=>$o['date_created'], //date('Y-m-d H:i:s')
									  'order_key'     	=>$o['order_key'],
									);
					$orderExists  	= $this->DatabaseModel->select_data('id','vendor_orders_details',['vendor_id'=>$vendor_id, 'main_order_id'=>$o['parent_id'],'suborder_id'=>$o['id']]);				
					if(empty($orderExists)){				
						$this->DatabaseModel->access_database('vendor_orders_details','insert',$suborder);
					}else{
						$this->DatabaseModel->access_database('vendor_orders_details','update',$suborder,['vendor_id'=>$vendor_id, 'main_order_id'=>$o['parent_id'],'suborder_id'=>$o['id']]);
					}
				}
			}
		}
	}
	
	public function getAllOrdersByVendor($vendor_id=27){
		$this->wcClient();
		$orderResults  = $this->woocommerce->get('orders?vendor='.$vendor_id);
		$order 	       = $this->get_result($orderResults);
		
		echo "<pre>";
		print_r($order);
	}
	
	
	public function getHomePageProducts($vendor_id=27){
		$this->wcClient();
		$products_type  = 'popularity';
		$start 			= 0; //$_GET['start'];
		$length 	    = 10; //$_GET['length'];
		$search         = ''; //$_GET['search']['value'];
		$leadsCount     = 0;
		$results   = $this->woocommerce->get('products',['orderby'=>$products_type, 'offset' => $start,'per_page' => $length,]);
		$products  = $this->get_result($results);
		
		echo "<pre>";
		print_r($products);
	}
	
	
	public function add_favorite()
	{
		if(!empty($_POST['prod_id']) && !empty($_POST['user_id'])){
			$post['user_id'] = $this->input->post('user_id');
			$post['wish_list'] = $this->input->post('prod_id');
			$data_['user_id'] = $this->uid;
			$check = $this->DatabaseModel->access_database('users_woocommerce_wishlist','select',$post['user_id'], $data_);
			if (count($check) > 0) {
				
				$wishlist_array = explode (',', $check[0]['wish_list']);

				$index = array_search($post['wish_list'], $wishlist_array);

				if($index !== FALSE){
					unset($wishlist_array[$index]);
				}else{
					array_push($wishlist_array, $post['wish_list']);
				}

				$comma_str = implode(',', $wishlist_array);
				$data_arr['wish_list'] = ltrim($comma_str, ',');
				$where_arr['user_id'] = $post['user_id'];

				$this->DatabaseModel->access_database('users_woocommerce_wishlist','update',$data_arr, $where_arr);
				// $this->get_favorite(substr($data_arr['wish_list'], 1));

			} else{
				
				$this->DatabaseModel->access_database('users_woocommerce_wishlist','insert',$post);
				// $this->get_favorite($post['wish_list']);
			}
		}
	}

	public function get_favorite()
	{	
		if ($this->uid !== '') {
			$data_['user_id'] = $this->uid;
			$check = $this->DatabaseModel->access_database('users_woocommerce_wishlist','select', $data_, $data_);
			
			if (count($check) > 0 && $check[0]['wish_list'] != '') {
				$this->wcClient();
				$result = $this->woocommerce->get('products/?include='.$check[0]['wish_list']);
				$results = json_decode(json_encode($result), true);
				for ($i=0; $i < count($results); $i++) { 
					$rounded = round($results[$i]['average_rating']);
					$results[$i]['rating_star_html'] = showRatingStar($rounded);
					$results[$i]['rounded_rating'] = $rounded;
				}
				
				$data['data_arr'] = $results;
				$data['status'] = 1;
			}else{
				$data['status'] = 0;
			}
			
		}else{
			$data['status'] = 0;
		}
		echo json_encode($data);
		
	}
	
	private function get_users_favorite_array()
	{	
		$results = '';
		if ($this->uid !== '') {
			$data_['user_id'] = $this->uid;
			$check = $this->DatabaseModel->access_database('users_woocommerce_wishlist','select', $data_, $data_);
			
			if (count($check) > 0 && $check[0]['wish_list'] != '') {
				$this->wcClient();
				$results = $this->woocommerce->get('products/?include='.$check[0]['wish_list']);
				
			}	
		}
		return $results;
	}

	public function wishlist(){
		$data['page_info'] = array('page'=>'store','title'=>'My Wishlist');
		$data['uid'] = $this->uid;
		if ($this->uid != '') {
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/shop/wishlist',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);	
		}else{
			redirect(base_url());
		}
		
	}

	public function createProductReview(){
		if(!empty($this->uid) && isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])){
			$resp = [];
			$rules = array(
						array( 'field' => 'prod_id', 'label' => 'product id', 'rules' => 'trim|required'),
						array( 'field' => 'prod_review', 'label' => 'review', 'rules' => 'trim|required'),
						array( 'field' => 'prod_rating', 'label' => 'rating', 'rules' => 'trim|required'),
					);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run()){	
				try{
					$this->wcClient();
					
					$data = [
						'product_id' 	=> $_POST['prod_id'],
						'review' 	 	=> $_POST['prod_review'],
						'reviewer'   	=> $_SESSION['user_name'],
						'reviewer_email'=> $_SESSION['user_email'],
						'rating'		=> $_POST['prod_rating'],
						
					];
					$reviewResult  = $this->woocommerce->post('products/reviews',$data); 
					$review 	   = $this->get_result($reviewResult);
					$resp['prod_id'] 	=  $_POST['prod_id'];
					$this->statusCode   =  1;
					$this->statusType   = 'Success';
					$this->respMessage  = 'Review added Successfully.';
				}catch(Exception $e) {
					$this->respMessage  = $e->getMessage();
				}
			}else{
				$this->validation_error();
			} 
		}
		$this->show_my_response($resp);
	}
	
	
	public function getProductReviews(){
		$resp = [];
		$rules = array(
					array( 'field' => 'prod_id', 'label' => 'product id', 'rules' => 'trim|required'),
				);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){	
			$this->wcClient();
			$length = 5;
			$prod_id 			= $_POST['prod_id'];
			$start  			= $_POST['start'];
			$productReview   	= $this->woocommerce->get('products/reviews?product='.$prod_id,['offset' => $start,'per_page' => $length]);
			$reviews 	     	= $this->get_result($productReview);
			$resp['reviews'] 	= $reviews;
			// $resp['reviews']['rating_star'] = showRatingStar($resp['reviews']['rating']);
			
			$resp['reviewer'] = 0;
			if (isset($_SESSION['user_email'])) {
				$search = $_SESSION['user_email'];
				$find_reviewer   	= $this->woocommerce->get('products/reviews?product='.$prod_id,['search' => $search]);
				if (count($find_reviewer) > 0) {
					$resp['reviewer'] += 1;
				}
			}else{
				$resp['reviewer'] += 1;
			}
			for ($i=0; $i < count($resp['reviews']); $i++) { 
				$resp['reviews'][$i]['rating_star_html'] = showRatingStar($resp['reviews'][$i]['rating']);
			}
			
			$this->statusCode   = 1;
			$this->statusType   = 'Success';
			$this->respMessage  = 'Review list.';
		}else{
			$this->validation_error();
		} 
		$this->show_my_response($resp);
	}
	
	
	public function orders(){
		$data['page_info'] = array('page'=>'store','title'=>'My Orders');
		$data['uid'] = $this->uid;
		if ($this->uid != '') {
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/shop/my_orders',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);	
		}else{
			redirect(base_url());
		}
		
	}

	public function get_my_orders()
	{	
		$resp = [];
		$start = $_POST['start'];
		$length = 5;

		$this->wcClient('v1');
		if ($_SESSION['store_customer_id'] != '') {
			// $order   	= $this->woocommerce->get('orders?customer='.$_SESSION['store_customer_id']);
			$order   	= $this->woocommerce->get('orders?customer='.$_SESSION['store_customer_id'], ['offset' => $start,'per_page' => $length]);
			$orders  	= $this->get_result($order);
			
			// print_r($orders);
			// die();

			if (count($orders) > 0) {
				$simple_arr_prod_ids = array();
				foreach ($orders as $key => $value) {
					if ($value['parent_id'] != 0) {
						foreach ($value['line_items'] as $k => $item) {
							$simple_arr_prod_ids[] = $item['product_id'];
							$uniq_ids = array_unique($simple_arr_prod_ids);
							$id_str = implode(',', $uniq_ids);
						}
					}
				}
				$results = $this->woocommerce->get('products/?include='.$id_str);
				$prod_arr  	= $this->get_result($results);
				$prod_img_arr = array();
				foreach ($prod_arr as $key => $value) {
					$prod_img_arr[$value['id']] = $value['images'][0]['src'];
				}
	
				foreach ($orders as $key => $value) {
					foreach ($value['line_items'] as $k => $item) {
						foreach ($prod_img_arr as $ke => $va) {
							if ($ke == $item['product_id']) {
								$orders[$key]['line_items'][$k]['prod_image'] = $va;
							}
						}
					}
				}
	
				$this->statusCode   = 1;
				$this->statusType   = 'success_orders';
				$this->respMessage  = 'Order list.';
				$resp['my_orders']  = $orders;
			}else{
				$resp['my_orders']  = '';
				$this->statusCode   = 1;
				$this->statusType   = 'empty_orders';
				$this->respMessage  = 'Order list empty.';
			}
		}

		
		$this->show_my_response($resp);
	}

	public function get_order_by_id()
	{
		$order_id = $this->input->post('order_id');
		if ($order_id != '') {
			$this->wcClient('v1');
			$results = $this->woocommerce->get('orders/'.$order_id);
			$single_prod_arr  	= $this->get_result($results);
			$single_prod_arr = [$single_prod_arr];
			// print_r($single_prod_arr);
			// die();
			if (count($single_prod_arr) > 0) {

				$simple_arr_prod_ids = array();
				foreach ($single_prod_arr as $key => $value) {
					if ($value['parent_id'] != 0) {
						foreach ($value['line_items'] as $k => $item) {
							$simple_arr_prod_ids[] = $item['product_id'];
							$uniq_ids = array_unique($simple_arr_prod_ids);
							$id_str = implode(',', $uniq_ids);
						}
					}
				}
				
				$result = $this->woocommerce->get('products/?include='.$id_str);
				$prod_arr  	= $this->get_result($result);
				// print_r($prod_arr);die();
				$prod_img_arr = array();
				foreach ($prod_arr as $key => $value) {
					$prod_img_arr[$value['id']] = $value['images'][0]['src'];
				}
	
				foreach ($single_prod_arr as $key => $value) {
					foreach ($value['line_items'] as $k => $item) {
						foreach ($prod_img_arr as $ke => $va) {
							if ($ke == $item['product_id']) {
								$single_prod_arr[$key]['line_items'][$k]['prod_image'] = $va;
							}
						}
					}
				}
				// print_r($single_prod_arr);
				// die();
				$this->statusCode   = 1;
				$this->statusType   = 'order_data_1';
				$this->respMessage  = 'Order data available';
				$resp['single_sub_order']  = $single_prod_arr;
			}else{
				$this->statusCode   = 1;
				$this->statusType   = 'order_data_0';
				$this->respMessage  = 'Order data not available';
				$resp['single_sub_order']  = '';
			}
		}
		$this->show_my_response($resp);
	}

	public function update_order_meta()
	{
		$action   = $this->input->post('action');
		$reason   = $this->input->post('reason');
		$order_id = $this->input->post('order_id');
		$prod_id  = $this->input->post('ord_prod_id');

		$filter_ids = rtrim($prod_id,",");
		$prod_ids_ar = explode (",", $filter_ids); 
		
		$meta = [];

		foreach ($prod_ids_ar as $key => $value) {
			$meta_data[$key]['id'] = $value;
			$meta_data[$key]['meta_data'] = [ ['key'=>'customer_req', 'value'=> $action.','.$reason] ];
		}
		
		$orderData = [
			'line_items' => $meta_data,
		];

		// print_r($orderData);
		// die();

		$this->wcClient();
		$results = $this->woocommerce->put('orders/'.$order_id, $orderData);
		$prod_arr  	= $this->get_result($results);
		
		if (count($prod_arr) > 0) {
			$this->statusCode   = 1;
			$this->statusType   = 'order_update_1';
			$this->respMessage  = 'Order data available';
		}else{
			$this->statusCode   = 1;
			$this->statusType   = 'order_update_0';
			$this->respMessage  = 'Order data not available';
		}
		$resp = [];
		$this->show_my_response($resp);
	}
}

	