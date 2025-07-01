<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_finance extends CI_Controller {
	
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
	
    function report($password = NULL){  //OMjkyG
	
        if(!empty($password) || isset($_SESSION['password'])){
                
                $password = isset($_SESSION['password'])? $_SESSION['password'] : $password ;
                
                if(md5($password) == '5745b18e77c8d80e85cf8b43e1abbe25'){
                    
                    $_SESSION['password'] = $password;
                    
                    $data['page_menu'] 	= 	'setting|report|Report|report';
                    
                    $data['group_by']	=	array('website_mode.mode'=>'Mode','users.user_name'=>'Creator','artist_category.category_name'=>'Creator Type','country.country_name'=>'Country','state.name'=>'State','users_content.uc_city'=>'City','channel_post_video.title'=>'Video');
                    
                    $this->load->view('admin/include/header',$data);
                    $this->load->view('admin/report/finance_report',$data);
                    $this->load->view('common/notofication_popup');
                    $this->load->view('admin/include/footer',$data);
                    
                }else{
                    echo 'Password Mismatch <br> <a href="'.base_url('admin').'">Back</a>';
                }
            }else{
                echo 'Please add password in the end of above url for example:  /DFSDE1 <br> <a href="'.base_url('admin').'">Back</a>';
            }
        } 
        
        function access_finance_report(){
            $leadsCount =0;
            $data 	= array();
            if(isset($_GET['group_by']) && !empty(trim($_GET['group_by']))){
                
                $search 	= trim($_GET['searches']);
                $group_by 	= trim($_GET['group_by']);
                
                
                $colm 		=  1;
                $order 		= 'DESC';
                
                if(isset($_GET['order'][0]['column'])){
                    $colm 	= $_GET['order'][0]['column'];
                    $order 	= $_GET['order'][0]['dir'];								
                }
                
                $cond = "";
                
                $start = $_GET['start'];
                
                $filed = array($group_by,'SUM(channel_video_view_count_by_date.view_count) AS ViewCount',' SUM(channel_video_view_count_by_date.ads_count) AS AdCount ','SUM(channel_video_view_count_by_date.creator_share_amount) AS ShareAmount','SUM(channel_video_view_count_by_date.dtv_share_amount) AS DtvAmount','SUM(channel_video_view_count_by_date.dtv_discount_amount) AS DtvDisAmount','SUM(creator_share_amount+dtv_share_amount+dtv_discount_amount) AS Total','post_key');
                
                $condfiled = array($group_by,'channel_video_view_count_by_date.view_count','channel_video_view_count_by_date.ads_count','channel_video_view_count_by_date.creator_share_amount','channel_video_view_count_by_date.dtv_share_amount','channel_video_view_count_by_date.dtv_discount_amount');
                
                $orderfiled = array($group_by,'channel_post_video.created_at','ViewCount','AdCount','ShareAmount','DtvAmount','DtvDisAmount');
                
                $join = array('multiple' , array(
                        array(	'channel_post_video',
                                'channel_post_video.post_id = channel_video_view_count_by_date.video_id',
                                'left'),
                        array(	'website_mode', 
                                'website_mode.mode_id 		= channel_post_video.mode', 
                                'left'),
                        array(	'users', 
                                'users.user_id 				= channel_post_video.user_id', 
                                'left'),
                        array(	'artist_category', 
                                'users.user_level 			= artist_category.category_id', 
                                'left'),
                        array(	'users_content', 
                                'users.user_id 				= users_content.uc_userid', 
                                'left'),
                        array(	'country', 
                                'users_content.uc_country 	= country.country_id', 
                                'left'),
                        array(	'state', 
                                'users_content.uc_state 	= state.id', 
                                ''),
                        ));
                
                if('users.user_name' == $group_by){
                        array_push($join[1],	array(	'users_billing_and_payment_info', 
                                                    'users.user_id 	= users_billing_and_payment_info.billing_user_id', 
                                                    'left'));
                        $filed = array_merge($filed,array('billing_name','billing_contact','billing_email','billing_email_list','tax_entity_type','tax_entity_other','tax_entity_id','payment_method_type','bank_name','bank_acc_number','routing_number','swift_code','paypal_id'));
                }
              
                /* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/  
			    $cond = $this->common->channelGlobalCond([1,1,NULL,0,NULL,1,0]) . ' AND ';
               
                $cond .= ' (';
                for($i=0;$i < sizeof($condfiled); $i++){
                    if($condfiled[$i] != ''){
                        $cond .= "$condfiled[$i] LIKE '%".$search."%'";
                        if(sizeof($condfiled) - $i != 1){
                            $cond .= ' OR ';
                        }	
                    }
                    
                }
                $cond .= ')';
                
                if(isset($_GET['date_range'])  && !empty($_GET['date_range'])){
                    $rangeArray = explode('-',$_GET['date_range']);
                    $date1 		= "'".date('Y-m-d' , strtotime($rangeArray[0]))."'";
                    $date2 		= "'".date('Y-m-d' , strtotime($rangeArray[1]))."'";
                    
                    $cond .=" AND channel_video_view_count_by_date.view_date >= $date1 AND channel_video_view_count_by_date.view_date <= $date2 ";
                }
            
                
                $resultData = $this->DatabaseModel->select_data($filed,'channel_video_view_count_by_date', $cond ,array($_GET['length'],$start) , $join , array($orderfiled[$colm] , $order) ,'',$group_by);
                
                $leadsCount =	$this->DatabaseModel->aggregate_data('channel_video_view_count_by_date',"DISTINCT {$group_by}",'COUNT',$cond,$join);
                $tax_entities = $this->DatabaseModel->select_data('tax_entity_id,tax_entity_name','tax_entity_classification', array('status'=>1));
                
                $this->load->library('common');
                foreach($resultData as $list){
                    $clm 			= 	explode('.',$condfiled[0])[1];
                    $full_title 	= 	trim($list[$clm]) ; 
                    $title			=  (strlen($full_title)< 25)?$full_title:substr($full_title,0,25)."...";
                        
                    $billing_name 	= $billing_contact = $email = $tax_enitity = $tax_entity_id = $BAN = $RN = $SC = $PPI =$payment_method = $bank_name ='';
                        
                    if($clm == 'user_name'){
                        
                        $billEmail 		= 	json_decode($list['billing_email']);
                        $billEmailList 	=	json_decode($list['billing_email_list']);
                        
                        
                        if(is_array($billEmailList)){
                            foreach($billEmailList as $key=>$value){
                                if(isset($billEmail[$key]) && $billEmail[$key] == $key)
                                $email .= $value.'<br>';
                            }
                        }
                        
                        if(!empty($list['tax_entity_type'])){
                            $tax_enitity = $tax_entities[$list['tax_entity_type'] -1]['tax_entity_name'];
                            $tax_enitity = ($list['tax_entity_type'] == 6)? $tax_enitity .'-' . $list['tax_entity_other'] : $tax_enitity;
                        }
                        
                        $BAN 				= $list['bank_acc_number'];
                        $RN 				= $list['routing_number'];
                        $SC 				= $list['swift_code'];
                        $PPI 				= $list['paypal_id'];
                        $tax_entity_id 		= $list['tax_entity_id'];
                        $bank_name 			= $list['bank_name'];
                        $billing_name 		= $list['billing_name'];
                        $billing_contact 	= $list['billing_contact'];
                        $payment_method 	= ($list['payment_method_type'] == 1)?'ACH':( ($list['payment_method_type'] == 2)? "PayPal":'' );
                    }
                    $link = ($group_by == 'channel_post_video.title')? 'href="'.base_url('watch?p='.$list['post_key']).'" target="_blank"' : '';
                    
                    array_push($data , array(
                        '<a '.$link.' title="'.$full_title.'">'.$title.'</a>' ,
                        $list['ViewCount'],
                        $list['AdCount'],
                        $list['ShareAmount'],
                        $list['DtvAmount'],
                        $list['DtvDisAmount'],
                        $list['Total'],
                        $billing_name,
                        $billing_contact,
                        $email,
                        $tax_enitity, 
                        $tax_entity_id, 
                        $payment_method,
                        $bank_name,
                        $BAN,
                        $RN,
                        $SC,
                        $PPI
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
        
        function searchForId($id, $array) {
           foreach ($array as $key => $val) {
               if ($val['user_id'] === $id) {
                   return true;
               }
           }
           return false;
        }
        
        
        function is_multi_array( $arr ) { 
            rsort( $arr ); 
            return isset( $arr[0] ) && !empty( $arr[0]) && is_array( $arr[0] )?true:false; 
        } 
        function filterCustomerFileName($transactions,$custom_file_id){
            // print_r($this->is_multi_array( $transactions ));die;
            if($this->is_multi_array( $transactions )){
                $new = array_filter($transactions, function ($txn) use ($custom_file_id) {
                    $txnx = str_split($txn['CustomerFileName'],12);
                    return ($txnx[0] == $custom_file_id);
                });
                return array_values($new);
            }else{
                $txn = str_split($transactions['CustomerFileName'],12);
                if($txn[0] == $custom_file_id){
                    return array($transactions);
                }
            }
            
        }
        
        
        function access_payment_history(){
            $leadsCount =0;
            $data 	= array();
            
            if(isset($_GET['length']) && !empty(trim($_GET['length']))){
                
                $search 	= trim($_GET['search']);
                
                $colm 		=  1;
                $order 		= 'DESC';
                if(isset($_GET['order'][0]['column'])){
                    $colm 	= $_GET['order'][0]['column'];
                    $order 	= $_GET['order'][0]['dir'];								
                }
                
                $cond = "";
                
                $start = $_GET['start'];
                
                $filed = array('users.user_uname','users.user_name','pay_through','payout_batch_id','payout_item_id','pay_amount','currency','payment_status','payment_fess','receiver_detail','created_at','message');
                
                $join = array('multiple' , array(
                            array(	'users', 
                                    'users.user_id = payment_history.user_id', 
                                    'left'),
                        ));
                
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
                
                $resultData = $this->DatabaseModel->select_data($filed,'payment_history', $cond ,array($_GET['length'],$start) , $join , array($filed[$colm],$order) );
                $leadsCount =	$this->DatabaseModel->aggregate_data('payment_history','payment_history.pay_id','COUNT',$cond,$join);
                foreach($resultData as $list){
                    if($list['pay_through'] == 'PAYPAL'){
                        $status = '<a href="javascript:;" data-toggle="tooltip" title="'.$this->valuelist->PayPalTransatctionStatus($list['payment_status']).'">'.$list['payment_status']. '</a><br><a href="javascript:;" data-toggle="tooltip" title="'.$this->valuelist->PayPalTransactionErrorMessages($list['message']).'">'.$list['message']. '</a>';
                    }else{
                        $status = '<a href="javascript:;"  data-toggle="tooltip" title="'.$this->valuelist->achReturnTxnStatus($list['message']).'">'.$list['payment_status']. '</a><br><a href="javascript:;" data-toggle="tooltip" title="'.$this->valuelist->achReturnCodeMessage($list['message']).'">'.$list['message']. '</a>';
                    }
                        array_push($data , array(
                                $list['user_uname'],
                                $list['user_name'],
                                $list['pay_through'],
                                $list['payout_batch_id'],
                                $list['payout_item_id'],
                                $list['pay_amount'],
                                $list['currency'],
                                $status,
                                $list['payment_fess'],
                                $list['receiver_detail'],
                                $list['created_at'],
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
        
        function access_payment_batches(){
            $this->is_ajax();
            $leadsCount =0;
            $data 	= array();
            
            if(isset($_GET['length']) && !empty(trim($_GET['length']))){
                
                $search 	= trim($_GET['search']);
                
                $colm 		= 8;
                $order 		= 'DESC';
                if(isset($_GET['order'][0]['column']) && !empty($_GET['order'][0]['column'])){
                    $colm 	= $_GET['order'][0]['column'];
                    $order 	= $_GET['order'][0]['dir'];								
                }
                
                $cond = "";
                
                $start = $_GET['start'];
                
                $filed = array('txnid','custom_file_id','level','operational_url','status','query_type','method','created_at','ach_id');
                
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
                
                $resultData = $this->DatabaseModel->select_data($filed,'payment_batches', $cond ,array($_GET['length'],$start) , '' , array($filed[$colm],$order) );
                $leadsCount =	$this->DatabaseModel->aggregate_data('payment_batches','ach_id','COUNT',$cond);
                foreach($resultData as $list){
                    
                        $url = ($list['method'] == 'ACH') ? 'updateAchPaymentStatusStatement/'.$list['txnid'].'/UPDATE' : 'updatePaypalPaymentStatusStatement/'.$list['txnid'] ;
                        array_push($data , array(
                                $list['txnid'],
                                $list['custom_file_id'],
                                $list['level'],
                                $list['operational_url'],
                                '<a data-toggle="tooltip" href="javascript:;" title="'.$this->valuelist->PayPalBatchStatus($list['status']).'">'.strtoupper($list['status']). '</a>', 
                                $list['query_type'],
                                $list['method'],
                                date('Y-m-d h:i:s',strtotime($list['created_at'])),
                                '<a data-toggle="tooltip" title="Refresh" data-batch="'.base_url('admin_finance/'.$url).'" style="cursor: pointer;"><i class="fa fa-refresh" style="font-size:24px"></i></a>'
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

        function access_payment_statement(){
            $leadsCount =0;
            $data 	= array();
            
            if(isset($_GET['length']) && !empty(trim($_GET['length']))){
                
                $search 	= trim($_GET['search']);
                
                $colm 		=  1;
                $order 		= 'DESC';
                if(isset($_GET['order'][0]['column'])){
                    $colm 	= $_GET['order'][0]['column'];
                    $order 	= $_GET['order'][0]['dir'];								
                }
                
                $cond = "";
                
                $cond .= "users_billing_and_payment_info.payment_method_type = ".$_GET['payment_mode']." AND "; 
                
                $start = $_GET['start'];
                
                $filed = array('users.user_uname','users.user_name','users.outstanding','payment_method_type','bank_acc_number','routing_number','paypal_id','users.user_id');
                
                $condfiled = array('users.user_uname','users.user_name','users.outstanding','payment_method_type','bank_acc_number','routing_number','paypal_id');
                
                $orderfiled = array('users.user_uname','users.user_name','users.outstanding',null,null,null,null,null);
                
                $join = array('multiple' , array(
                        array(	'users_billing_and_payment_info', 
                                'users.user_id 				= users_billing_and_payment_info.billing_user_id', 
                                'left'),
                        ));
                
                $cond .="users.user_status = 1 AND users.outstanding > 0 AND ";
                
                $cond .= '(';
                for($i=0;$i < sizeof($condfiled); $i++){
                    if($condfiled[$i] != ''){
                        $cond .= "$condfiled[$i] LIKE '%".$search."%'";
                        if(sizeof($condfiled) - $i != 1){
                            $cond .= ' OR ';
                        }	
                    }
                }
                $cond .= ')';
                
                $resultData = $this->DatabaseModel->select_data($filed,'users', $cond ,array($_GET['length'],$start) , $join , array($orderfiled[$colm] , $order ));
                
                $leadsCount =	$this->DatabaseModel->aggregate_data('users','DISTINCT users.user_id','COUNT',$cond,$join);
                /* 
                $cond = "payment_status != 'SUCCESS'  AND  payment_status != 'APPROVED' ";
                $payment_status = $this->DatabaseModel->select_data('user_id,payment_status','payment_history',$cond);
                 */
                foreach($resultData as $list){
                        $uid = $list['user_id'];
                        $payment_method 	= ($list['payment_method_type'] == 1)?'ACH': 'PAYPAL';
                        
                        $payment_detail = ($payment_method == 'PAYPAL') ? base64_decode($list['paypal_id']) : base64_decode($list['routing_number']) .'|' . base64_decode($list['bank_acc_number'] );
                        
                        /* if($this->searchForId( $uid,$payment_status)){
                            $pending = 'PENDING';
                        }else{
                            $pending = '<input type="checkbox" value="'.$uid.'" class="checkAll" id="checkUser_'.$uid.'" name="user_id[]">';
                        } */
                        $pending = '<input type="checkbox" value="'.$uid.'" class="checkAll" id="checkUser_'.$uid.'" name="user_id[]">';
                        // $cond		=	'user_id = '.$uid.' AND payment_status != 2'; 	
                        $cond		=	'user_id = '.$uid.' AND payment_status != 2 AND total > 0'; 	
                        $statements = 	$this->DatabaseModel->select_data('statement_id,statement_month,total,payment_status','statements',$cond);
                        
                        $li = '';
                        foreach($statements as $state){
                            $s = ($state['payment_status'] == 0)? 'PENDING': (($state['payment_status'] == 1)? 'IN PROCESS':'FAILED' );
                            
                            $li .= '<tr>';
                            $li .= '<td><input type="checkbox" value="'.$state['statement_id'].'" name="statement_id['.$uid.'][]" data-amount="'.$state['total'].'" data-uid="'.$uid.'" data-statement="MyallStatement" class="statement_'.$uid.'"></td>';
                            $li .= '<td>'.date('F-y',strtotime($state['statement_month'])).'</td>';
                            $li .= '<td>'.$state['total'].'</td>';
                            $li .= '<td>'.$s.'</td>';
                            $li .= '</tr>';
                        }
                        
                        array_push($data , array(
                                $pending,
                                '<a target="_blank" href="'.base_url('profile?user='.$list['user_uname']).'">'.$list['user_name'].'</a>',
                                $list['outstanding'],
                                '<table class="table"><tr><td>#</td><td>MONTH</td><td>AMOUNT</td><td>STATUS</td></tr>'.$li.'</table>',
                                $payment_method,
                                $payment_detail,
                                '<span id="Stetement_total_'.$uid.'"></span>',
                                $uid
                        ));
                    }
                        
                }
                
            // $this->session->set_userdata('export_payouts_report',$data);
            echo json_encode(array( 
                'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
                'recordsTotal' => $leadsCount,
                'recordsFiltered' => $leadsCount,
                'data' => $data, 
                ));	
        }
        
        function send_payout_statement(){
            // echo '<pre>';
            
            $resp = array();
            if( isset($_POST['payment_mode']) && isset($_POST['user_id']) ){
                $time = time();
                
                if($_POST['payment_mode'] == 1){  // FOR ACH
                    
                    $resultData = 	$this->session->userdata('export_payouts_report');
                    $ach 		= 	$this->common->ach ;	
                    $nachaid 	= 	trim($ach['nachaid']);	
                    $token 		= 	trim($ach['token']);	
                    $YYMMDD 	= 	date('ymd');	
                    
                    $fileDebits	=	0;
                    
                    $join = array('multiple' , array(
                                array(	'users', 
                                        'users.user_id 	= statements.user_id', 
                                        'left'),
                                array(	'users_billing_and_payment_info', 
                                        'users.user_id 	= users_billing_and_payment_info.billing_user_id', 
                                        'left'),
                                
                            ));
                    $all_state_ids = [];		
                    foreach($_POST['user_id'] as $user_id){
                        $statement_ids 	= 	$_POST['statement_id'][$user_id];
                        $all_state_ids 	= 	array_merge($all_state_ids,$statement_ids);
    
                        $statement_ids 	= 	implode(',',$statement_ids);
                        
                        $cond			=	"statement_id IN({$statement_ids})"; 
                        
                        $statements 	= 	$this->DatabaseModel->select_data('sum(total) AS totals,user_name,routing_number,bank_acc_number','statements',$cond,'',$join);
                        if(isset($statements[0]['totals'])){
                            $UserName 			= 	$statements[0]['user_name'];
                            $amount 			= 	$statements[0]['totals'];
                            $routing_number 	= 	base64_decode($statements[0]['routing_number']);
                            $bank_acc_number 	= 	base64_decode($statements[0]['bank_acc_number']);
                            
                            $items[] = array("{$nachaid},Discovered USA,{$nachaid},Discovered USA,WEB,Payment,{$YYMMDD},{$user_id},{$UserName},{$routing_number},{$bank_acc_number},22,{$amount},{$user_id},S,,") ;
                            
                            $fileDebits += $amount;
                        }
                    }
                    $CsvString = "";
                    foreach($items as $RowIndex => $RowItem){
                        foreach($RowItem as $ColIndex => $ColItem){
                            $CsvString .= $ColItem;
                        }
                    $CsvString .= "\r\n";
                    }
                    // print_r($CsvString);
                    // die;
                    
                    
                    $arrayData = array(	
                                    'securityToken' => 	$token,
                                    'nachaid' 		=> 	$nachaid,
                                    'fileTransactionCount' => sizeof($items),
                                    'fileDebits' 	=> 	$fileDebits,
                                    'fileCredits' 	=> 	0,
                                    'fileName'		=> 	rand(10,10).time(),
                                    'fileContent'	=>	base64_encode($CsvString),
                                );
                                
                    $varifyUrl 	= 	$ach['url'].'UploadFile';
                    $header 	= 	array('content-type'  => $ach['content_type'] );
                    // echo '<pre>';print_r( str_replace("%E2%80%8B","",http_build_query($arrayData)) );die;
                    $responce	=	$this->common->CallAchCurl('POST', $arrayData ,$varifyUrl,$header);
                    
                    $transactionid = '';
                    if($responce['status'] == 1){
                        $d = $responce['data'];
                        if($responce['data']['Code'] == 000 ){  /* Successfull */
                            $transactionid	=	$responce['data']['Value'];
                            $insert_id = $this->DatabaseModel->access_database(	'payment_batches','insert',
                                                                        array(	'txnid'=>$transactionid,
                                                                                'custom_file_id'=>$arrayData['fileName'],
                                                                                'level'=>1,
                                                                                'status'=>$d['Message'],
                                                                                'method'=>'ACH',
                                                                                'created_at'=>date('Y-m-d H:i:s')) 	);
                            $this->DatabaseModel->access_database('statements','update',array('payment_status'=>1,'payment_txnid'=>$transactionid),'statement_id IN('.implode(',',$all_state_ids).')');
                        }else{
                            $this->respMessage = isset($d['Message'])? $d['Message'] : $d['Details'];
                            return $this->show_my_response();
                        }
                    }else{
                            $this->respMessage = $responce['message'];
                            return $this->show_my_response();
                    }
                    
                    if(!empty($transactionid)){
                        $resp['data'] = $transactionid;
                        
                        $this->statusCode = 1;
                        $this->statusType = 'Success';
                    }else{
                        $this->respMessage = 'Transaction id not generated .';
                        return $this->show_my_response();
                    }
                
                }else
                
            
                if($_POST['payment_mode'] == 2){   // FOR PAYPAL 
                    
                    $paypal  				= $this->common->paypal;
                    
                    $token = $this->common->CallCurl('POST',$paypal['access_token_post'],$paypal['access_token_url'],$paypal['access_token_header'],$paypal['credential']);
                    $token = json_decode($token,true);
                    // print_r($token);die;
                    if(isset($token['access_token'])){
                            
                             // $token = json_decode($token['data'],true);
                             
                             if(isset($token['access_token'])){
                                $items=[]; 
                                $resultData = $this->session->userdata('export_payouts_report');
                                $fileDebits	=	0;
                    
                                $join = array('multiple' , array(
                                            array(	'users', 
                                                    'users.user_id 	= statements.user_id', 
                                                    'left'),
                                            array(	'users_billing_and_payment_info', 
                                                    'users.user_id 	= users_billing_and_payment_info.billing_user_id', 
                                                    'left'),
                                            
                                        ));
                                $all_state_ids = [];		
                                foreach($_POST['user_id'] as $user_id){
                                    $statement_ids 	= 	$_POST['statement_id'][$user_id];
                                    $all_state_ids 	= 	array_merge($all_state_ids,$statement_ids);
    
                                    $statement_ids 	= 	implode(',',$statement_ids);
                                    
                                    $cond			=	"statement_id IN({$statement_ids})"; 
                                    
                                    $statements 	= 	$this->DatabaseModel->select_data('sum(total) AS totals,user_name,paypal_id','statements',$cond,'',$join);
                                    if(isset($statements[0]['totals'])){
                                        $amount 	= 	$statements[0]['totals'];
                                        $paypal_id 	= 	base64_decode($statements[0]['paypal_id']);
                                        
                                        $items[] = array(	'recipient_type' => 'EMAIL',
                                                            'amount' => array (
                                                                        'value' => number_format($amount,2),
                                                                        'currency' => 'USD'
                                                                        ),
                                                            'note' => 'Thanks for your patronage!',
                                                            'sender_item_id' => $user_id,
                                                            'receiver' => $paypal_id 
                                                        ) ;
                                    }
                                }	
                                
                                $itemData = array(
                                'sender_batch_header' => array (
                                        'sender_batch_id' 	=> 'Payouts_'.$time,
                                        'email_subject' 	=> 'You have a payout!',
                                        'email_message' 	=> 'You have received a payout! Thanks for using our service!',
                                        ),
                                'items' => $items, 
                                );
                                
                                $header	= 	array('Content-Type: application/json',
                                                  'Authorization:'. $token['token_type'].' '.$token['access_token'] );
                                
                                $paid 	= $this->common->CallCurl('POST',json_encode($itemData),$paypal['payout_url'],$header);
                                $paid = json_decode($paid,true);
                                // print_r(json_decode($paid['data'],true) );die;
                                if(isset($paid['batch_header'])){
                                    
                                        // $paid = json_decode($paid['data'],true);
                                        
                                        if(isset($paid['batch_header']['payout_batch_id'])){
                                             
                                            $payout_batch_id 	= $paid['batch_header']['payout_batch_id'];
                                            $url				= str_replace('batch',$payout_batch_id,$paypal['show_payout_url']);	
    
                                            $ShowPayout = $this->common->CallCurl('GET','', $url,$header);
    
                                            $ShowPayout = json_decode($ShowPayout,true);
    
                                            if(isset($ShowPayout['batch_header']['payout_batch_id'])){
                                                $payout_batch_id = $ShowPayout['batch_header']['payout_batch_id'];
                                                
                                                $this->DatabaseModel->access_database(	'payment_batches','insert',
                                                                                            array(	'txnid'	=> $payout_batch_id,
                                                                                                    'custom_file_id'=>'Payouts_'.$time,
                                                                                                    'level'=>1,
                                                                                                    'operational_url'=>$url,
                                                                                                    'status'=>$ShowPayout['batch_header']['batch_status'],
                                                                                                    'method'=>'PAYPAL',
                                                                                                    'created_at'=>date('Y-m-d H:i:s')) 	);
                                                $ins = $this->DatabaseModel->access_database('statements','update',array('payment_status'=>1,'payment_txnid'=>$payout_batch_id),'statement_id IN('.implode(',',$all_state_ids).')');
                                                if($ins){
                                                    $insert = [];
                                                    if(isset($ShowPayout['items'])){
                                                        foreach($ShowPayout['items'] as $item){
                                                            $array = [ 	'user_id' 		=> 	$item['payout_item']['sender_item_id'],
                                                                        'pay_through'	=>	'PAYPAL',
                                                                        'payout_batch_id'=>	$payout_batch_id,
                                                                        'payout_item_id'=>	$item['payout_item_id'],
                                                                        'pay_amount'	=>	$item['payout_item']['amount']['value'],
                                                                        'currency'		=>	$item['payout_item']['amount']['currency'],
                                                                        'payment_status'=>	$item['transaction_status'],
                                                                        'payment_fess'	=>	$item['payout_item_fee']['value'],
                                                                        'receiver_detail'=>	$item['payout_item']['receiver'],
                                                                        'created_at'	=>	date('Y-m-d h:i:s'),
                                                                        ];
                                                            if($array['payment_status'] == 'SUCCESS'){
                                                                $this->query_builder->outstanding(array('user_id'		=> 	$array['user_id'],
                                                                                                        'credit' 		=>	$array['pay_amount'],
                                                                                                        'entry_against'	=>	9 ));
                                                                $this->DatabaseModel->access_database('statements','update',array('payment_status'=>2),array('payment_txnid'=>$payout_batch_id,'user_id'=>$array['user_id']));
                                                                                                        
                                                            }else{
                                                                $array['message'] = isset($item['errors']['name']) ? $item['errors']['name'] : '';
                                                            }
                                                            array_push($insert ,$array);
                                                        }
                                                        $this->db->insert_batch('payment_history', $insert);
                                                    }
                                                    
                                                    $this->statusCode = 1;
                                                    $this->statusType = 'Success';
                                                    $this->respMessage = 'Don\'t make payment again.Payment Process can take some time';
                                                }else{
                                                    $this->respMessage = 'Something went wrong';
                                                } 
                                            }else{
                                            $this->respMessage 	= 'Batch header not available' ; 
                                            }
                                              
                                         }else{
                                             $this->respMessage 	= 'Something Went Wrong ! Batch id not genreated ' ;
                                         }
                                    
                                }else{
                                     $this->respMessage = $paid['message'] ;
                                }
                                    
                         }else{
                             $this->respMessage 	= 'Something went wrong2' ;
                         }
                            
                    }else{
                        $this->respMessage 	= $token['message'] ;
                    }
                    
                }
            }else{
                $this->respMessage 	= 'Select minimum one user';
            }
            
            $this->show_my_response($resp);
        }

        function AchPaymentProcessStatement(){
            $resp = array();
            if(isset($_POST['fileId']) && isset($_POST['url'])){
                $ach 		= 	$this->common->ach ;	
                $fileId 	= 	trim($_POST['fileId']);
                $url 		= 	trim($_POST['url']);
                $level 		= 	trim($_POST['level']);
                $varifyUrl 	= 	$ach['url'].$url;
                $arrayData = array(	
                                    'securityToken' => trim($ach['token']),
                                    'nachaid' 		=> trim($ach['nachaid']),
                                    'fileId' 		=> $fileId,
                                );
                                
                
                $header 	= 	array('content-type'  => $ach['content_type'] );
                $responce	=	$this->common->CallAchCurl('POST', $arrayData ,$varifyUrl,$header);
                
                if($responce['status'] == 1){
                    $d = $responce['data'];
                    if($responce['data']['Code'] == 000 ){  /* Successfull */
                       $this->db->update('payment_batches',array('level'=>$level,'status'=>$d['Message'],'operational_url'=>$url),array('txnid'=>$fileId));
                       if($level == 4 && $d['Message'] == 'Successful'){
                            $this->updateAchPaymentStatusStatement($fileId,'INSERT');
                       }elseif($url == 'RemoveUploadedFile' || $url == 'CancelProcessingOfUploadedFile'){
                           $this->DatabaseModel->access_database('statements','update',array('payment_status'=>0),array('payment_txnid'=>$fileId));
                       }
                       $resp['data'] =  isset($responce['data']['Value'])?$responce['data']['Value']:'';
                       $this->statusCode = 1;
                       $this->statusType = 'Success';
                    }else{
                        $this->respMessage = isset($d['Message'])? $d['Message'] : $d['Details'];
                        return $this->show_my_response();
                    }
                }else{
                        $this->respMessage = $responce['message'];
                        return $this->show_my_response();
                }
            }else{
                $this->respMessage 	= 'Something went wrong ! Transaction id not found. ';
            }
            $this->show_my_response($resp);
        }

        function updateAchPaymentStatusStatement($txnid="",$query_type){
            if(!empty($txnid)){
                $resultData = $this->DatabaseModel->select_data('*','payment_batches',array('txnid'=>$txnid),1 );
                
                if(isset($resultData[0]) && !empty($resultData[0])){
                    if($resultData[0]['level']  == 4  && $resultData[0]['status'] == 'Successful'){
                        $startDate 	= 	date('Ymd',strtotime($resultData[0]['created_at']));
                        $endDate 	= 	date('Ymd', strtotime($startDate. ' + 15 days'));
                        $ach 		= 	$this->common->ach ;	
                        $url 		= 	$ach['url'].'GetTransactionsAndReturnsHistory';
                        $arrayData 	= 	array(	
                                            'token' 				=> $ach['token'],
                                            'nachaid' 				=> $ach['nachaid'],
                                            'transactionStatusID' 	=> '',
                                            'individualName' 		=> '',
                                            'individualID' 			=> '',
                                            'customerTrace' 		=> '',
                                            'amount' 				=> '',
                                            'routingNumber' 		=> '',
                                            'accountNumber' 		=> '',
                                            'fedTrace' 				=> '',
                                            'dateTypeID' 			=> 1,
                                            'startDate' 			=> $startDate,
                                            'endDate' 				=> $endDate,
                                        );
                        
                        $responce	=	$this->common->CallAchCurl('POST',$arrayData,$url,array('content-type'  => $ach['content_type'] ));
                        
                        if($responce['status'] == 1){
                            $d = $responce['data'];
                            
                            if( (isset($d['TransactionsAndReturns']['Transaction'])) && !empty($d['TransactionsAndReturns']['Transaction']) ){  /* Successfull */
                                    $transactions 	= 	$d['TransactionsAndReturns']['Transaction'];
                                        
                                    $transactions 	=	$this->filterCustomerFileName($transactions,$resultData[0]['custom_file_id']);
                                    $batch = [];
                                    if(!empty($transactions)){
                                        
                                        $tx_status = array(1=>'CUSTOMER WAREHOUSE',2=>'ACH WAREHOUSE',3=>'SUSPENDED',4=>'ORIGINATED',5=>'VOIDED',6=>'PENDING PARENT APPROVAL',8=>'APPROVED',9=>'PENDING BANK APPROVAL',11=>'REJECTED',12=>'');
                                        
                                        if($query_type == 'INSERT'){
                                            foreach($transactions as $item){
                                                
                                                $PS = isset($tx_status[$item['TransactionStatusID']])?$tx_status[$item['TransactionStatusID']] : 'NA';
                                                
                                                $array = [ 	'user_id' 		=> 	$item['IndividualID'],
                                                            'pay_through'	=>	'ACH',
                                                            'payout_batch_id'=>	$txnid,
                                                            'payout_item_id'=>	$item['TransactionID'],
                                                            'pay_amount'	=>	$item['Amount'],
                                                            'currency'		=>	'USD',
                                                            'payment_status'=>	$PS,
                                                            'payment_fess'	=>	0,
                                                            'receiver_detail'=>	$item['RDFIRoutingNumber'].'|'.$item['RDFIAccountNumber'],
                                                            'created_at'	=>	date('Y-m-d h:i:s'),
                                                            ];
                                                if($PS == 'ORIGINATED'){
                                                        $this->query_builder->outstanding(array('user_id'=> $array['user_id'],
                                                                                                'credit' =>$array['pay_amount'],
                                                                                                'entry_against'=>9
                                                                                                ));
                                                        $this->DatabaseModel->access_database('statements','update',array('payment_status'=>2),array('payment_txnid'=>$txnid,'user_id'=> $array['user_id']));											
                                                }
                                                array_push($batch ,$array);
                                            }
                                        
                                            if($this->db->insert_batch('payment_history', $batch)){
                                                return 1;
                                            }else{
                                                return 0;
                                            }
                                        }else{
                                            
                                            foreach($transactions as $item){
                                                $user_id 		= $item['IndividualID'];
                                                $payout_item_id = $item['TransactionID'];
                                                $PS 			= isset($tx_status[$item['TransactionStatusID']])?$tx_status[$item['TransactionStatusID']] : 'NA';
                                                
                                                $histData=$this->DatabaseModel->select_data('*','payment_history',['payout_item_id'=>$payout_item_id,'user_id'=>$user_id],1 );
                                                
                                                $payment_status = isset($histData[0]['payment_status']) && !empty($histData[0]['payment_status']) ? $histData[0]['payment_status'] : '';
                                                // print_r($item);die;  
                                                if( $this->db->affected_rows() > 0 && $PS == 'ORIGINATED' && $payment_status != 'ORIGINATED' && !isset($item['Return'])){
                                                    $this->db->update('payment_history',['payment_status'=>$PS],['payout_item_id'=>$payout_item_id]);
                                                    // print_r($payment_status);die;
                                                    $this->query_builder->outstanding([ 'user_id'		=>	$user_id,
                                                                                        'credit' 		=>	$item['Amount'],
                                                                                        'entry_against'	=>	9]);
                                                    $this->DatabaseModel->access_database('statements','update',['payment_status'=>2],['payment_txnid'=>$txnid,'user_id'=>$user_id]);
                                                
                                                }else{
                                                    if(isset($item['Return']) && !empty($item['Return'])){
                                                        
                                                            if($payment_status == 'ORIGINATED'){
                                                                
                                                                $this->query_builder->outstanding([ 'user_id'		=>	$user_id,
                                                                                                    'debit' 		=>	$item['Amount'],
                                                                                                    'entry_against'	=>	10]);
                                                            }
                                                            $eReason = isset($item['Return']['ReturnCode']) ? $item['Return']['ReturnCode'] : '';
                                                            $this->db->update('payment_history',['payment_status'=>'RETURNED','message'=>$eReason],['payout_item_id'=>$payout_item_id,'user_id'=>$user_id]);
                                                        
                                                            $this->db->update('statements',['payment_status'=>3],['payment_txnid'=>$txnid,'user_id'=>$user_id]);
                                                    }
                                                }
                                            }
                                        
                                            $this->statusCode = 1;
                                            $this->statusType = 'Success';
                                            $this->respMessage = 'Updation Done';
                                        }
                                            
                                    }else{
                                        $this->respMessage = 'NO transactions Matched';
                                        return $this->show_my_response();
                                    }	
                            }else{
                                $this->respMessage = isset($d['Message'])? $d['Message'] : (isset($d['Details']) ? $d['Details'] : '') ;
                                return $this->show_my_response();
                            }
                        }else{
                                $this->respMessage = $responce['message'];
                                return $this->show_my_response();
                        }
                        
                    }else{
                        $this->respMessage 	= 'Process isn\'t  completed, current level is '.$resultData[0]['operational_url'].'.' ;
                    }
                }else{
                    $this->respMessage 	= 'Something went Wrong' ;
                }
                
            }else{
                $this->respMessage 	= 'Transaction id cant\'t be blank' ;
            }
            $this->show_my_response();
        }
        
        function updatePaypalPaymentStatusStatement($batch_id=""){
            if(!empty($batch_id)){
                $paypal  				= $this->common->paypal;
                
                $token = $this->common->CallCurl('POST',$paypal['access_token_post'],$paypal['access_token_url'],$paypal['access_token_header'],$paypal['credential']);
                $token = json_decode($token,true);
                if(isset($token['access_token'])){
                     if(isset($token['access_token'])){
                         
                        $access_token 	= 	$token['token_type'].' '.$token['access_token'];
                        $url	   		= 	str_replace('batch',$batch_id,$paypal['show_payout_url']);	
                        $header			= 	array('Content-Type: application/json','Authorization:'.$access_token);
                                                    
                        $ShowPayout = $this->common->CallCurl('GET','', $url,$header);
                        $ShowPayout = json_decode($ShowPayout,true);
                        
                        if(isset($ShowPayout['batch_header'])){
                            $payout_batch_id 	= $ShowPayout['batch_header']['payout_batch_id'];
                            
                            $this->db->update('payment_batches',array('level'=>2,'status'=>$ShowPayout['batch_header']['batch_status']),array('txnid'=>$batch_id));
                            
                            if(isset($ShowPayout['items'])){
                                // print_r($ShowPayout);die;
                                foreach($ShowPayout['items'] as $item){
                                    $user_id			= $item['payout_item']['sender_item_id'];
                                    $transaction_status = $item['transaction_status'];	
                                    $payout_item_id 	= $item['payout_item_id'];	
                                    $value 				= $item['payout_item']['amount']['value'];
                                         
                                    if($transaction_status == 'SUCCESS'){
                                        
                                        $this->DatabaseModel->access_database('statements','update',array('payment_status'=>2),array('payment_txnid'=>$payout_batch_id,'user_id'=>$user_id));
                                        $this->db->update('payment_history',array('payment_status'=>$user_id),
                                                                            array('payout_item_id'=>$payout_item_id) );
                                        if(  $this->db->affected_rows() > 0){
                                            $this->query_builder->outstanding(array('user_id'		=>	$user_id,
                                                                                    'credit' 		=>	$value,
                                                                                    'entry_against'	=>	9)	
                                                                                    );
                                        }
                                    }else{
                                        $this->db->update('statements',['payment_status'=>3],['payment_txnid'=>$payout_batch_id,'user_id'=>$user_id]);
                                        
                                        $historyData = $this->DatabaseModel->select_data('*','payment_history',['payout_item_id'=>$payout_item_id,'user_id'=>$user_id],1 );
                                        
                                        if(isset($historyData[0]['payment_status']) && !empty($historyData[0]['payment_status'])){
                                            if($historyData[0]['payment_status'] == 'SUCCESS'){
                                                $this->query_builder->outstanding(array('user_id'		=>	$user_id,
                                                                                        'debit' 		=>	$value,
                                                                                        'entry_against'	=>	10)
                                                                                    );
                                            }
                                            $error_reason = isset($item['errors']['name']) ? $item['errors']['name'] : '';
                                            $this->db->update('payment_history',['payment_status'=>$transaction_status,'message'=>$error_reason],['payout_item_id'=>$payout_item_id,'user_id'=>$user_id]);
                                        }
                                    }
                                }
                                $this->statusCode = 1;
                                $this->statusType = 'Success';
                                $this->respMessage = 'Updation Done';
                            }
                        }else{
                         $this->respMessage 	= 'Batch header not available' ; 
                        }
                     }else{
                         $this->respMessage 	= 'Something went wrong2' ;
                     }
                }else{
                    $this->respMessage 	= $token['message'] ;
                }
            }else{
                $this->respMessage 	= 'Empty Batch Id' ;
            }
            $this->show_my_response();
        }

        function home_sliders(){
            $data['page_menu'] 	= 	'setting|homepage_slider|Homepage Slider|HomepageSlider';
            $cond				=	array('page_status'=>1);
            $data['web_mode'] 	= 	$this->DatabaseModel->select_data('mode_id,mode','website_mode',$cond);
            $this->load->view('admin/include/header',$data);
            $this->load->view('admin/setting/home_sliders',$data);
            $this->load->view('common/notofication_popup');
            $this->load->view('admin/include/footer',$data);
        }
	
}
