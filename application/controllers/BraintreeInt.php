<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/braintree/lib/Braintree.php';

require_once APPPATH."/third_party/wocommerce/autoload.php";

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class BraintreeInt extends CI_Controller {

    function __Construct(){
        Parent::__Construct();
		$this->load->library(array('cart'));
    }
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
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
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'wp_api' => true,'version' => 'wc/'.$v]  
        );
    }
	
	private function wcmpClient(){
        $this->woocommerce = new Client(
            STORE_URL,STORE_KEY,STORE_SECREATS,[ 'version' => 'wcmp/v1']  
        );
    }
	
	Private function get_result($results){
        return json_decode(json_encode($results), true);
    }
	
	public function accesstoken(){
        $gateway = $this->brainConfig();
        $clientToken = $gateway->clientToken()->generate();
        echo json_encode(array('client' => $clientToken));
    } 

     /* Config function to get the braintree config data to process all the apis on braintree gateway */
    public function brainConfig(){
        return $gateway = new Braintree\Gateway([
             'environment' => 'sandbox',
             'merchantId' => 'bkvnhsb95z5qn7w3',
             'publicKey' => 'y2nwv7dtvz2ck6qn',
             'privateKey' => '1ef78cdbf3bfa8d3a1784f3ca281f232',
        ]);
    }


	public function successBraintree(){
		$resp = [];
		if(isset($_POST['nonce'])){
			$gateway = $this->brainConfig();
			$orderIds   = '';
			$orderId = '';
				$totalaAmt = 0;
				if(isset($_SESSION['orderData'])){ 
					$orderData  = $_SESSION['orderData'];
					//$orderIds   = array_column($_SESSION['orderData'],'id');
					//$orderId    = implode(",",$orderIds);
					$orderId    = $orderData['id'];
					$totalaAmt  = $this->cart->total();
				}

			$result = $gateway->transaction()->sale([
					'amount' => $totalaAmt,
					'paymentMethodNonce' => $_POST['nonce'],
					//'customerId' => $result->customer->id,
					//'deviceData' => $_POST['device_data'],
					'orderId' => $orderId,
					'options' => [
						'submitForSettlement' => True,
						/*'paypal' => [
							'customField' => '', //$_POST["PayPal custom field"],
							'description' => '', //$_POST["Description for PayPal email receipt"],
					  ],*/
					],
				]);
			if ($result->success) {
				$this->wcClient('v1'); // used API v1 version here
				/*$updateOrders = [];
				foreach($orderIds as $order_id){
					$updateOrders['update'][] = [	'id' => $order_id,
													'transaction_id' =>$result->transaction->id, 
													'set_paid'=>true
												];
				}
				$this->woocommerce->post('orders/batch',  $updateOrders);*/
				$updateOrderData = array('transaction_id' =>$result->transaction->id, 
										 'set_paid'=>true
										 //'status'=>'processing'
										);
				$this->woocommerce->put('orders/'.$orderId, $updateOrderData);
				
				$this->saveOrdersOnLocalDB(); // save order to local database
				
				unset($_SESSION['orderData']);
				$this->cart->destroy();
				$resp['data'] 		= array('transaction_id'=>$result->transaction->id, 'orderId'=>$orderId);
				$this->statusCode   =  1;
				$this->statusType   = 'Success';
				$this->respMessage  = 'Order placed Successfully.';
			  //print_r("Success ID: " . $result->transaction->id);
			} else {
				$this->respMessage  = $result->message;
				//print_r("Error Message: " . $result->message);
			}
		}else{
			$this->respMessage  = 'Something went wrong please try again!';
		}
		$this->show_my_response($resp);
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
	
	
	public function createCustomer($braintreeDetail){
        $customerId = $this->session->userdata('customer_id');
        if($customerId == ''){
            $resp = ['status' => 0, 'msg' => 'Something went wrong, Please try again.'];
        }else{
            $getBraintreeId = $this->DatabaseModel->select_data([
                'table' => 'shipping_address',
                'field' => 'braintree_id, first_name, last_name, email, contact id',
                'where' => ['customer_id' => $customerId, 'store_id' => $this->session->userdata('store_id')],
                'limit' => 1
            ]);
            
            $braintreeId = '';
            if(!empty($getBraintreeId)){
                if($getBraintreeId[0]['braintree_id'] != ''){
                    $braintreeId = $getBraintreeId[0]['braintree_id'];
                    $resp = ['status' => 1, 'braintreeId' => $braintreeId];
                }else{
                    $gateway = $this->brainConfig();
                    $result = $gateway->customer()->create([
                        'firstName' => $getBraintreeId[0]['first_name'],
                        'lastName' => $getBraintreeId[0]['last_name'],
                        'phone' => $getBraintreeId[0]['contact'],
                        'email' => $getBraintreeId[0]['email']
                    ]);
                       
                    if ($result->success) {
                        $updateBraintreeId = $this->DatabaseModel->update_data([
                            'table' => 'user',
                            'where' => $getBraintreeId[0]['id'],
                            'data' => ['braintree_id' => $result->customer->id],
                            'limit' => 1
                        ]);
                        
                        $braintreeId = $result->customer->id;
                        $resp = ['status' => 1, 'braintreeId' => $braintreeId];
                    }else{
                        $resp = ['status' => 0, 'msg' => $result];
                    }
                }
            }else{
                $resp = ['status' => 0, 'msg' => 'Something went wrong.'];
            }
        }
        return $resp;
    }
	
	
	
	

    public function successBraintree_old(){ 
       echo"<pre>"; 
        print_r($_POST); exit;
        // if(isset(Auth::user()->id)){
            // $checkPlan = Plan::where(['id' => $request->plan_id])->get();
            // if(sizeof($checkPlan) > 0){
                try{
                    $gateway = $this->brainConfig();
                    $response = $gateway->transaction()->sale([
                        'amount' => $request->amount,
                        'paymentMethodNonce' => $request->payment_method_nonce,
                        'customerId' => $this->createCustomer(),
                        'options' => [
                            'submitForSettlement' => true,
                        ],
                    ]);
                    $resp = $response->transaction;
                    if(!empty($resp) && $resp != 'null'){
                        $errResp = (object)['transaction_id' => $resp->id, 'amount' => $resp->amount, 'payment_gateway' => 'braintree', 'currency' => getCurrency(['curr_code' => $resp->currencyIsoCode]), 'order_id' => uniqid(), 'discount' => $request->discountApplied, 'plan_exact_amount' => $request->planExactAmnt, 'taxPercent' => $request->taxPercent, 'taxAmount' => $request->taxApplied ];

                        if ($response->success == true) {
                            // if(Session::get('coupon_id') != ''){
                            //     $this->checkAppliedCoupon(Session::get('coupon_id'));
                            //     Session::forget('coupon_id');
                            // }
                            
                            $getResp = $this->savePaymentData([ 'user_id' => Auth::user()->id, 'plan_id' => $request->plan_id, 'respObj' => $errResp, 'type' => 'braintree' ]);
                            alert()->success( __('frontWords.txn_id').' : '.$resp->id, __('frontWords.payment_done'))->persistent("Close");    
                            return redirect('/');
                        }else{
                            $success = paymentGateway::create(['user_id' => Auth::user()->id, 'plan_id' => $request->plan_id, 'payment_data' => json_encode([$errResp]), 'type' => 'braintree', 'payment_gateway' => 'braintree', 'status' => 0, 'order_id' => uniqid()]);
                            alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
                            return Redirect::back();
                        }  
                    }  else{
                        alert()->error( __('frontWords.try_again'))->persistent("Close");  
                        return Redirect::back();
                    }   
                }catch(\Exception $e){
                    alert()->error($e->getMessage())->persistent("Close");  
                    return Redirect::back();
                }    
            // }else{
            //     alert()->error( __('frontWords.try_again'))->persistent("Close");  
            //     return Redirect::back();
            // }
            // alert()->error( __('frontWords.try_again'))->persistent("Close");  
            // return Redirect::back();
        // }else{
        //     alert()->error( __('frontWords.login_err'))->persistent("Close");  
        //     return Redirect::back();
        // }
    }
	
	
			/* $result = $gateway->customer()->create([
							'firstName' => 'Nitesh',
							'lastName' => 'Modi',
							'phone' => '1234567890',
							'email' => 'test@gmail.com'
						]); */
			
			
			/* $result = $gateway->transaction()->sale([
					  'amount' => 1000,
					  'orderId' => 4651,
					 // 'merchantAccountId' => 'a_merchant_account_id',
					  'paymentMethodNonce' => $_POST['nonce'],
					  //'deviceData' => $deviceDataFromTheClient,
					  'customer' => [
						'firstName' => 'Drew',
						'lastName' => 'Smith',
						'company' => 'Braintree',
						'phone' => '312-555-1234',
						'fax' => '312-555-1235',
						'website' => 'http://www.example.com',
						'email' => 'drew@example.com'
					  ],
					  'billing' => [
						'firstName' => 'Paul',
						'lastName' => 'Smith',
						'company' => 'Braintree',
						'streetAddress' => '1 E Main St',
						'extendedAddress' => 'Suite 403',
						'locality' => 'Chicago',
						'region' => 'IL',
						'postalCode' => '60622',
						'countryCodeAlpha2' => 'US'
					  ],
					  'shipping' => [
						'firstName' => 'Jen',
						'lastName' => 'Smith',
						'company' => 'Braintree',
						'streetAddress' => '1 E 1st St',
						'extendedAddress' => 'Suite 403',
						'locality' => 'Bartlett',
						'region' => 'IL',
						'postalCode' => '60103',
						'countryCodeAlpha2' => 'US'
					  ],
					  'options' => [
						'submitForSettlement' => true
					  ]
					]); */
					
		public function updateOrderStatus($orderId=''){
			
			$this->wcClient();
			$data = array('set_paid'=>true,
							//'status'=>'processing'
							);
									
			$this->woocommerce->put('orders/'.$orderId, $data);
			echo "success";
		}
	
	

}    
