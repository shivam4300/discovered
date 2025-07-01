<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	
	public function __construct()
	{
		parent::__construct();
		// if (!isset($this->session->userdata['admin'])) {
			// redirect('auth/logout');
		// }
		$this->load->helper(array('api_validation','aws_s3_action'));
		$this->load->library('query_builder');
		
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
	
	
	function MassPayment($methodName_, $nvpStr){
		
		$environment = 'sandbox'; // or 'beta-sandbox' or 'live'.

		$API_UserName = urlencode('ajaydeepparmar-facilitator_api1.gmail.com');
		$API_Password = urlencode('EKJNLJU85LHGZDDL');
		$API_Signature = urlencode('AEvg9f6BKxrhAAdrDS9wSE0EE8FFAipGWop0WiGqAx08wfVX4J9ZIVlZ');
		
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		
		if("sandbox" === $environment || "beta-sandbox" === $environment){
		 $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
		}
		 
		$version = urlencode('51.0');

		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature";

		// Set the request as a POST FIELD for curl.
		// echo $nvpreq.$nvpStr;die;
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq.$nvpStr); 

		// Get response from the server.
		$httpResponse = curl_exec($ch);
		// echo '<pre>';
// print_r( $httpResponse);die;
		if( !$httpResponse){
		  echo $methodName_ . ' failed: ' . curl_error($ch) . '(' . curl_errno($ch) .')';
		}

		// Extract the response details.
		$httpResponseAr = explode("&",$httpResponse);

		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value){
		  $tmpAr = explode("=", urldecode($value));
		  if(sizeof($tmpAr) > 1){
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		  }
		}

		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)){
		  exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}
		// print_r($httpParsedResponseAr);

		return $httpParsedResponseAr;
	}
	
	
	function MadePayment(){
		
		$vEmailSubject 		= PROJECT . ' Monthly Payment';
		// Set request-specific fields.
		$emailSubject 		= urlencode($vEmailSubject);
		$receiverType 		= urlencode('EmailAddress');
		$currency 			= urlencode('USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD') 
		
		$receivers = array(
		  0 => array(
			'receiverEmail' => "ajaydeepparmar-buyer@gmail.com", 
			'amount' 		=> "1.35",
			'uniqueID' 		=> "id_001",
			'note' 			=> "product"
			), 
		 
		);
		$receiversLenght 	= 	count($receivers);
		// Add request-specific fields to the request string.
		$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

		$receiversArray 	= 	array();

		for($i = 0; $i < $receiversLenght; $i++){
			$receiversArray[$i] = $receivers[$i];
		}

		foreach($receiversArray as $i => $receiverData){
			$receiverEmail 	= 	urlencode($receiverData['receiverEmail']);
			$amount 		= 	urlencode($receiverData['amount']);
			$uniqueID 		= 	urlencode($receiverData['uniqueID']);
			$note 			= 	urlencode($receiverData['note']);
			$nvpStr 	   .= 	"&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
		}
 
		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = $this->MassPayment('MassPay',$nvpStr);
		echo '<pre>';
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
			echo 'MassPay Completed Successfully: ' . $httpParsedResponseAr;
		}else{
			echo '\nMassPay failed: ';
			print_r($httpParsedResponseAr);
		}
	}
	
	
	function getAccessToken(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_USERPWD, 'AaQZeZeDCb5_ea7nOyv27zAOpaQAoi0pXkDGBWgghVDC5HRemeiLAE8dKZfAyfAQs72ocyA6fzZ0DPwn' . ':' . 'ECTMnACxqOAGWMBiV5oJfqE1IKCrnp-QXxgIEKCqQYYMvxJKZpSXMBKnzSQz03porzb9ebQowwS13cX0');

		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Accept-Language: en_US';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		
		echo '<pre>';print_r(json_decode(json_encode($result),true));
	
	}
	function create_payout_1(){
		$time = time();
		$data = array (
			'sender_batch_header' => array (
				'sender_batch_id' => 'Payouts_'.$time,
				'email_subject' => 'You have a payout!',
				'email_message' => 'You have received a payout! Thanks for using our service!',
			),
			'items' => array (
				array (
					'recipient_type' => 'EMAIL',
					'amount' => array (
						'value' => '20',
						'currency' => 'USD',
					),
					'note' => 'Thanks for your patronage!',
					'sender_item_id' => 'item_1'.$time,
					'receiver' => 'sb-0nwsi2252927@personal.example.com'
				)
			),
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payouts');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Bearer A21AAEj1c7RoLyenpDgx3j6sR_JPjLUuR4afn5n_-jFg6M8J-1aS_FYbAbA1D-2EqzrBg74KMlo9yE_ygSVpgKo2DYFbyL9HQ';
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		print_r($result);
	}
	
	function show_payout($batch=""){
		
		// $requestBody = file_get_contents('php://input');
		// $this->DatabaseModel->access_database('paypal_webhook','insert',array('data'=>$requestBody,'created_at'=>date('Y-m-d h:i:s')));
		
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payouts/'.$batch.'?page_size=1000&page=1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Bearer A21AAEj1c7RoLyenpDgx3j6sR_JPjLUuR4afn5n_-jFg6M8J-1aS_FYbAbA1D-2EqzrBg74KMlo9yE_ygSVpgKo2DYFbyL9HQ';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		 echo '<pre>';
		  print_r(json_decode($result,true));
	}
	function webhook(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/notifications/webhooks');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"url\": \"https://test.discovered.tv/payment/show_payout\",\n  \"event_types\": [\n    {\n      \"name\": \"PAYMENT.AUTHORIZATION.CREATED\"\n    },\n    {\n      \"name\": \"PAYMENT.AUTHORIZATION.VOIDED\"\n    }\n  ]\n}");

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Bearer A21AAFweFoKn2j6wHQZrXbXlqjjG6ZyOzMxLLUskqJ8kqMYyWHT18mMF8mobHsNp0v8L0H7FBdVAQY0T1muECQeBzJ9YTxwLQ';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		 echo '<pre>';
		  print_r(json_decode($result,true));
	}
	function listwebhook(){
		$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/notifications/webhooks');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Authorization: Bearer A21AAFweFoKn2j6wHQZrXbXlqjjG6ZyOzMxLLUskqJ8kqMYyWHT18mMF8mobHsNp0v8L0H7FBdVAQY0T1muECQeBzJ9YTxwLQ';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
					 echo '<pre>';
		  print_r(json_decode($result,true));
	}
	function varifyWebhook(){
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/notifications/verify-webhook-signature');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"transmission_id\": \"69cd13f0-d67a-11e5-baa3-778b53f4ae55\",\n  \"transmission_time\": \"2016-02-18T20:01:35Z\",\n  \"cert_url\": \"cert_url\",\n  \"auth_algo\": \"SHA256withRSA\",\n  \"transmission_sig\": \"lmI95Jx3Y9nhR5SJWlHVIWpg4AgFk7n9bCHSRxbrd8A9zrhdu2rMyFrmz+Zjh3s3boXB07VXCXUZy/UFzUlnGJn0wDugt7FlSvdKeIJenLRemUxYCPVoEZzg9VFNqOa48gMkvF+XTpxBeUx/kWy6B5cp7GkT2+pOowfRK7OaynuxUoKW3JcMWw272VKjLTtTAShncla7tGF+55rxyt2KNZIIqxNMJ48RDZheGU5w1npu9dZHnPgTXB9iomeVRoD8O/jhRpnKsGrDschyNdkeh81BJJMH4Ctc6lnCCquoP/GzCzz33MMsNdid7vL/NIWaCsekQpW26FpWPi/tfj8nLA==\",\n  \"webhook_id\": \"1JE4291016473214C\",\n  \"webhook_event\": {\n    \"id\": \"8PT597110X687430LKGECATA\",\n    \"create_time\": \"2013-06-25T21:41:28Z\",\n    \"resource_type\": \"authorization\",\n    \"event_type\": \"PAYMENT.AUTHORIZATION.CREATED\",\n    \"summary\": \"A payment authorization was created\",\n    \"resource\": {\n      \"id\": \"2DC87612EK520411B\",\n      \"create_time\": \"2013-06-25T21:39:15Z\",\n      \"update_time\": \"2013-06-25T21:39:17Z\",\n      \"state\": \"authorized\",\n      \"amount\": {\n        \"total\": \"7.47\",\n        \"currency\": \"USD\",\n        \"details\": {\n          \"subtotal\": \"7.47\"\n        }\n      },\n      \"parent_payment\": \"PAY-36246664YD343335CKHFA4AY\",\n      \"valid_until\": \"2013-07-24T21:39:15Z\",\n      \"links\": [\n        {\n          \"href\": \"https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B\",\n          \"rel\": \"self\",\n          \"method\": \"GET\"\n        },\n        {\n          \"href\": \"https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/capture\",\n          \"rel\": \"capture\",\n          \"method\": \"POST\"\n        },\n        {\n          \"href\": \"https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/void\",\n          \"rel\": \"void\",\n          \"method\": \"POST\"\n        },\n        {\n          \"href\": \"https://api.paypal.com/v1/payments/payment/PAY-36246664YD343335CKHFA4AY\",\n          \"rel\": \"parent_payment\",\n          \"method\": \"GET\"\n        }\n      ]\n    }\n  }\n}");
		curl_setopt($ch, CURLOPT_POST, 1);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Bearer A21AAEj1c7RoLyenpDgx3j6sR_JPjLUuR4afn5n_-jFg6M8J-1aS_FYbAbA1D-2EqzrBg74KMlo9yE_ygSVpgKo2DYFbyL9HQ';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);
		 echo '<pre>';
		  print_r(json_decode($result,true));
	}
}
