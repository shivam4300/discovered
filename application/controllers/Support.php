<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {
	/*public 	$uid ;
	public 	$AVI_Subscription_Key = '263a83e3b6f943a4ba5437a69b6639f2';*/
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('share_url_encryption','form_validation'));
		$this->load->helper(array('aws_s3_action','file'));
	}
	function supportTaamSessionCheck(){
		if(empty($this->session->userdata('id'))){
			redirect(base_url('support/login'));
		}
	}
	function create_ticket(){
		$this->load->library('manage_session');
		$data['page_info'] 		= 	array('page'=>'Support','title'=>'Support');
		$data['department'] 	= 	$this->DatabaseModel->select_data('*','support_department',array('status'=>1));
		/*print_r($_SESSION);
		die();*/
		$this->load->view('home/inc/header',$data);
	    $this->load->view('support/index',$data);
	    $this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}
	function index(){
		$this->load->library('manage_session');
		$data['page_info'] 		= 	array('page'=>'ViewThread','title'=>'ViewThread');
		$data['ticketData'] 	= 	$this->DatabaseModel->select_data('*','support_ticket',array('user_id'=>$this->session->userdata('user_login_id'),'ticket_id'=>0),'','',array('id','DESC'));
		$this->load->view('home/inc/header',$data);
	    $this->load->view('support/viewThread',$data);
	    $this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}
	function ticketSingle($id=""){
		$this->load->library('manage_session');
		$data['page_info'] 		= 	array('page'=>'TicketSingle','title'=>'TicketSingle');
		$data['ticketData'] 	= 	$this->DatabaseModel->select_data('*','support_ticket',array('user_id'=>$this->session->userdata('user_login_id'),'ticket_id'=>0,'id'=>$id));
		//$data['ticketReplayData'] 	= 	$this->DatabaseModel->select_data('*','support_ticket',array('ticket_id'=>$id));
		if(!empty($data['ticketData'])){
			$this->load->view('home/inc/header',$data);
		    $this->load->view('support/ticketSingle',$data);
		    $this->load->view('common/notofication_popup');
	        $this->load->view('home/inc/footer',$data);
		}else{
			redirect(base_url('support'));
		}

	}
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message']= $this->respMessage;

		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}
	function submit_ticket(){
		//$this->load->library('manage_session');

		$this->load->library('creator_jwt');
		$uid = $this->session->userdata('user_login_id');
		$resp = array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1){
			$rules = array(
				array('field'=>'ticket_type','label'=>'Post','rules'=>'trim|required'),
				array('field'=>'subject','label'=>'Subject','rules' => 'trim|required|max_length[200]'),
				array('field'=>'message','label'=>'Message','rules'=>'trim|required|max_length[1000]'),

			);
			$this->form_validation->set_rules($rules);

			if($this->form_validation->run()){
				$file_data=array();
				for($j=0; $j < count($_FILES['image_file']['name']); $j++) {
					if(!empty($_FILES['image_file']['name'][$j])){
                       	$rns = $this->common->generateRandomString(20);
						$ext  = pathinfo($_FILES["image_file"]["name"][$j],PATHINFO_EXTENSION);;
						$name = $rns.'.'.$ext;
						$amazon_path = "support/{$name}";
						$res=multipartUploader_ad($_FILES['image_file']['tmp_name'][$j],$amazon_path);
						$file_data[]=$res['url'];
                    }
                }
				$ticket_type 	= $this->input->post('ticket_type');
				$subject 		= $this->input->post('subject');
				$message 		= $this->input->post('message');
				$technology 		= $this->input->post('technology');
				$insertArray = array(
					'ticket_type'	=>	$ticket_type,
					'subject' 		=> 	$subject,
					'message' 		=> 	$message,
					'technology' 	=> 	implode(',',$technology),
					'user_id'		=>  $this->session->userdata('user_login_id'),
					'status'		=>  0,
					'user_type'		=>  2,
					'ticket_id'			=>  0,
					'reply_type'	=>  'UTS',
					'support_id'	=>  0,
					'created_at'	=>  date('Y-m-d h:i:s'),
					'attachment_url'=>  (isset($file_data))?json_encode($file_data):''
				);
				$ins=$this->DatabaseModel->access_database('support_ticket','insert',$insertArray);
				$num_padded = 'DTV'.sprintf("%04d", $ins);
				$this->DatabaseModel->access_database('support_ticket','update',array('ticket_no'=>$num_padded),array('id'=>$ins));
				$to_email='dtv@pixelnx.com,ajaydeep.parmar@pixelnx.com';
				if($ticket_type==1){
					$to_email='samba@discovered.tv,charles@discovered.tv,skeeter@discovered.tv,dtv@pixelnx.com';
				}elseif($ticket_type==2){
					$to_email='samba@discovered.tv,ajaydeep.parmar@pixelnx.com,adops@discovered.tv';
				}elseif($ticket_type==3){
					$to_email='samba@discovered.tv,charles@discovered.tv,skeeter@discovered.tv,dtv@pixelnx.com';
				}
				$department_name=current($this->DatabaseModel->select_data('*','support_department',array('id'=>$ticket_type,'status'=>1)));
				//$from_email = "admin@discovered.tv";

        		//Load email library
				$data['ticket_id']=$num_padded;
				$data['subject']=$subject;
				$data['message']=$message;
				$data['department_name']=$department_name['name'];
				$data['ins']=$ins;
				$data['user_name']=$this->session->userdata('user_name');
				$data['receiver_email'] = $to_email;
				$data['mail_subject'] = 'New Support Ticket';

				$this->load->helper('aws_ses_action');
				send_smtp_support_mail($data);

        		//$sup_msg="A new support ticket #".$num_padded." has been created <br>".$message;
        		/*$sup_msg=$this->load->view('support/ticket_email',$data,true);
		        $this->load->library('email');
		        $config['mailtype'] = 'html';
		        $this->email->initialize($config);
		        $this->email->from($from_email, 'New Support Ticket');
		        $this->email->to($to_email);
		        $this->email->subject('New Support Ticket');
		        $this->email->message($sup_msg);
		        @$this->email->send();*/
				$this->statusCode 	= 1;
				$this->statusType 	= 'success';
				$this->respMessage  =  "A new ticket created successfully.";
				$email_list	=$this->DatabaseModel->select_data('*','support_user',array('is_mail'=>1,'support_department'=>$ticket_type,'status'=>1));
				foreach($email_list as $email_list_data){
					$data = [];
					$data['template'] 		= "A new support ticket #".$num_padded." has been created <br>".$message;
					$data['receiver_email'] = $email_list_data['user_email'];
					$data['mail_subject'] 	= 'New Support Ticket';
					send_smtp_support_mail($data);

					/*$sup_msg="A new support ticket #".$num_padded." has been created <br>".$message;
			        $this->load->library('email');
			        $config['mailtype'] = 'html';
			        $this->email->initialize($config);
			        $this->email->from($from_email, 'New Support Ticket');
			        $this->email->to($email_list_data['user_email']);
			        $this->email->subject('New Support Ticket');
			        $this->email->message($sup_msg);
			        @$this->email->send();*/
					$this->statusCode 	= 1;
					$this->statusType 	= 'success';
					$this->respMessage  =  "A new ticket created successfully.";

				}
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->statusCode 	= 1;
				$this->statusType 	= 'Error';
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}

		$this->show_my_response($resp);
	}
	public function support_admin(){
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
		$data['page_menu'] = 'support|team_list|Support|Support';
		/*$this->load->library('Support_lib');
		$data['department']=$this->support_lib->get_deparment();*/
		$data['department'] 	= 	$this->DatabaseModel->select_data('*','support_department',array('status'=>1));
		$this->load->view('admin/include/header',$data);
		$this->load->view('support/support_admin',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}

	public function department(){
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
		$data['page_menu'] = 'support|department|Department|department';

		$this->load->view('admin/include/header',$data);
		$this->load->view('support/department',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}

	public function createSupportTeam(){
		if(!empty(trim($_POST['id']))){
			$rules = array(
				array( 'field' => 'support_department', 'label' => 'Department', 'rules' => 'trim|required'),
				array( 'field' => 'user_name', 'label' => 'User name', 'rules' => 'trim|required'),
				array( 'field' => 'user_email', 'label' => 'Email', 'rules' => 'trim|required')
			);
		}else{
			$rules = array(
				array( 'field' => 'support_department', 'label' => 'Department', 'rules' => 'trim|required'),
				array( 'field' => 'user_name', 'label' => 'User name', 'rules' => 'trim|required'),
				array( 'field' => 'user_email', 'label' => 'Email', 'rules' => 'trim|required'),
				array( 'field' => 'password', 'label' => 'Password', 'rules' => 'trim|required')
			);
		}

		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$user = array(
				'support_department' =>$_POST['support_department'],
				'user_name' =>$_POST['user_name'],
				'user_email' =>$_POST['user_email'],
				'password' =>md5($_POST['password']),
				'date_time'=>date('Y-m-d h:i:s'),
				'status'=>1
			);

			if(!empty(trim($_POST['id']))){
				if(empty($_POST['password'])){
					unset($user['password']);
				}
				$this->DatabaseModel->access_database('support_user','update',$user,array('id'=>$_POST['id']));
				$this->respMessage = 'User details added successfully.';
				$this->statusCode=1;
				$this->statusType="Success";
			}else{

				$this->DatabaseModel->access_database('support_user','insert',$user);
				$this->respMessage = 'User details Inserted successfully.';
				$this->statusCode=1;
				$this->statusType="Success";
			}
		}else{
			$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode=0;
				$this->statusType="Error";
		}
		$this->show_my_response();
	}

	public function access_support_team(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];

		$field		= 	['id','user_name','user_email','support_department','status','is_mail'];

		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;

		$cond=" 1 AND user_type=2";
		if( isset($_GET['user_status']))
		$cond .= ' AND status = '.$_GET['status'];
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
		$userData 	= 	$this->DatabaseModel->select_data($field,'support_user', $cond ,array($_GET['length'],$start) ,'', array($field[$colm],$order) );
		$leadsCount =	$this->DatabaseModel->aggregate_data('support_user','id','COUNT',$cond,'');
		if(!empty($userData)){
			$start++;
			foreach($userData as $list){
				$ustatus 	 = $list['status'];
				$user_status = ($ustatus == 1) ? 'ACTIVE' : 'INACTIVE';
				$check 		 = ($list['status'] == 1)? 'checked' : '';
				$mail 		 = ($list['is_mail'] == 1)? 'checked' : '';
				array_push($data , array(
					$start++,
					$list['user_name'],
					$list['user_email'],
					'<a class="support_team" data-id="'.$list['id'].'" data-user-url="support/getUserData" ><i class="fa fa-fw fa-edit"></i></a>',
					'<input '.$check.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="support/updateCheckStatus/support_user"> '.' '.$user_status,
					'<input '.$mail.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="support/updateCheckStatus/support_user_mail"> ',
					'<a class="LoginMeSupport" data-uid="'.$list['id'].'"><i class="fa fa-sign-in" aria-hidden="true"></i></a>',
				));
			}
		}
		// $this->session->set_userdata('export_user_details',$data);
		echo json_encode(array(
			'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' => $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data,
		));
	}

	public function getUserData(){
		$rules = array(
			array('field'=>'user_id','label'=>'User','rules'=>'trim|required')
		);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$uid = $_POST['user_id'];
			/*$accessParam = array(
				'where' => 'id='.$uid.'',
			);*/
			$userData 	= 	$this->DatabaseModel->select_data('*','support_user',array('id'=>$uid ));
			if(!(empty($userData))){
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'Data avaialble.';
				$resp =  array('data' => $userData[0]);
			}else{
				$this->respMessage = 'Invalid user id.';
			}
		}else{
			$errors = array_values($this->form_validation->error_array());
			$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			$this->statusCode=0;
			$this->statusType="Error";
		}
		$this->show_my_response($resp);
	}

	public function updateCheckStatus($tableType = null){
		$rules = array(
			array('field'=>'id','label'=>'id','rules'=>'trim|required')
		);
		$this->form_validation->set_rules($rules);
			if($this->form_validation->run()){
				if($tableType == 'support_user'){
					$table_id = "id";
					$status   = "status";
				}
				if($tableType == 'support_department'){
					$table_id = "id";
					$status   = "status";
				}
				if($tableType == 'support_user_mail'){
					$tableType='support_user';
					$table_id = "id";
					$status   = "is_mail";
				}
				if($this->DatabaseModel->access_database($tableType,'update',array($status=>$_POST['status']),array($table_id=>$_POST['id'])) > 0)
				{
					$this->statusCode 	= 1;
					$this->statusType 	= 'Success';
					$this->respMessage 	= 'Status Updated Successfully.';
				}else{
					$this->respMessage  ="Somthing Went Wrong";
					$this->statusCode=0;
					$this->statusType="Error";
				}
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode=0;
				$this->statusType="Error";
			}
		$this->show_my_response();
	}

	public function dashboard(){
		$data["hede_menu"]=true;
		$this->supportTaamSessionCheck();
		$data['page_info'] 		= 	array('page'=>'Dashboard','title'=>'Dashboard');
		$data['department'] 	= 	$this->DatabaseModel->select_data('*','support_department',array('status'=>1));
		$data['user_department'] 	= 	current($this->DatabaseModel->select_data('*','support_department',array('status'=>1,'id'=>$this->session->userdata('support_department'))));
		$data['artist_category'] = $this->DatabaseModel->select_data(
																'*',
																'artist_category',
																array('level'=>1,'status'=>1)
															);
		$this->load->view('home/inc/header',$data);
	    $this->load->view('support/support_desk',$data);
	    $this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}

	public function login(){
		if(!empty($this->session->userdata('id'))){
			redirect(base_url('support/dashboard'));
		}
		$data['page_info'] 		= 	array('page'=>'SupportLogin','title'=>'SupportLogin');
		$data['noFooter']		=1;
		$data['noHeader']		=1;

		$this->load->view('home/inc/header',$data);
	    $this->load->view('support/login',$data);
	    $this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}

	public function ticketReply(){
		$this->load->library('creator_jwt');
		$uid = $this->session->userdata('user_login_id');
		$resp = array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		if($TokenResponce['status'] == 1){
			$rules = array(
				array( 'field' => 'message', 'label' => 'Message', 'rules' => 'trim|required|max_length[1000]')
			);
			$this->form_validation->set_rules($rules);

			if($this->form_validation->run()){
				$file_data=array();
				for($j=0; $j < count($_FILES['image_file']['name']); $j++) {
					if(!empty($_FILES['image_file']['name'][$j])){
                       	$rns = $this->common->generateRandomString(20);
						$ext  = pathinfo($_FILES["image_file"]["name"][$j],PATHINFO_EXTENSION);;
						$name = $rns.'.'.$ext;
						$amazon_path = "support/{$name}";
						$res=multipartUploader_ad($_FILES['image_file']['tmp_name'][$j],$amazon_path);
						$file_data[]=$res['url'];
                    }
                }

				$message 		= $this->input->post('message');
				$ticket_type 		= $this->input->post('ticket_type');
				$subject 		= $this->input->post('subject');
				$insertArray = array(
					'ticket_type'	=>	$ticket_type,
					'subject' 		=> 	$subject,
					'message' 		=> 	$message,
					'user_id'		=>  $this->session->userdata('user_login_id'),
					'status'		=>  0,
					'ticket_id'		=>  $this->input->post('ticket_id'),
					'reply_type'	=>  'UTS',
					'support_id'	=>  0,
					'created_at'	=>  date('Y-m-d h:i:s'),
					'attachment_url'=>  (isset($file_data))?json_encode($file_data):''
				);
				$this->DatabaseModel->access_database('support_ticket','insert',$insertArray);
				$this->DatabaseModel->access_database('support_ticket','update',array('status'=>3,'updated_at'=>date('Y-m-d h:i:s')),array('id'=>$this->input->post('ticket_id')));
				$this->statusCode 	= 1;
				$this->statusType 	= 'success';
				$this->respMessage  =  "You have sent a message successfully";
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode 	= 0;
				$this->statusType 	= 'Error';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}

		$this->show_my_response($resp);
	}

	public function teamAuth(){
		$resp = array();
		$rules = array(
			array('field'=>'email','label'=>'email','rules'=>'trim|required'),
			array('field'=>'password','label'=>'password','rules'=>'trim|required')
		);
		$this->form_validation->set_rules($rules);
			if($this->form_validation->run()){
				$checkUser = $this->DatabaseModel->select_data('*' , 'support_user' , array('user_email' => trim($_POST['email']) , 'password' => md5(trim($_POST['password'])),'status'=>1) , 1);
				if(!(empty($checkUser))){
					$sessData = array(
						'id' 					=> $checkUser[0]['id'],
						'support_department' 	=> $checkUser[0]['support_department'],
						'user_name' 			=> $checkUser[0]['user_name'],
						'user_email' 			=> $checkUser[0]['user_email']
					);

					$this->session->set_userdata($sessData);
					if(isset($_POST['rem_me'])){
						setcookie("sup_email", $_POST['email'] , time()+3600 * 24 * 14,'/');
						setcookie("sup_pwd", $_POST['password'] , time()+3600 * 24 * 14,'/');
					}else{
						setcookie("sup_email", $_POST['email'] , time()-3600 * 24 * 365,'/');
						setcookie("sup_pwd", $_POST['password'] , time()-3600 * 24 * 365,'/');
					}
					$this->respMessage = 'Login Successfully.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'success';
				}else{
					$this->respMessage = 'Invalid credentials, please check email or password.';
					$this->statusCode 	= 1;
					$this->statusType 	= 'Error';
				}
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode 	= 1;
				$this->statusType 	= 'Error';
			}
		$this->show_my_response($resp);
	}

	public function ticket_data(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];

		$field		= 	['id','subject','message'];

		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;


		$cond = ' ticket_id = 0 AND ticket_type = '.$this->session->userdata('support_department');
		$userData 	= 	$this->DatabaseModel->select_data('*','support_ticket', $cond ,array($_GET['length'],$start) ,'', array($field[$colm],$order) );
		$leadsCount =	$this->DatabaseModel->aggregate_data('support_ticket','id','COUNT',$cond,'');

		if(!empty($userData)){
			$start++;
			foreach($userData as $list){
				$cuser              =   current(get_user($list['user_id']));
    			$user_page_image    =   (isset($cuser['uc_pic']) && !empty($cuser['uc_pic']))?create_upic($list['user_id'],$cuser['uc_pic']) : '';
    			$onerror="'".user_default_image()."'";
				array_push($data , array(
					'<img src="'.$user_page_image.'" alt="thumb" class="img-responsive" onerror="this.onerror=null;this.src='.$onerror.'">',
					'<b><a href="'.base_url('support/ticketDetails/'.$list['id']).'" >'.$list['subject'].'</a></b><br>'.$cuser ['user_name'].' Ticket Id'.$list['id'],
					'Created '.date("j F Y",strtotime($list['created_at'])),
					'Replied On '.date("j F Y g:i a" ,strtotime($list['updated_at'])),
					'Action',
				));
			}
		}
		// $this->session->set_userdata('export_user_details',$data);
		echo json_encode(array(
					'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
					'recordsTotal' => $leadsCount,
					'recordsFiltered' => $leadsCount,
					'data' => $data,
					));
	}

	public function ticketDetails($id=""){
		$data["hede_menu"]=true;
		$this->supportTaamSessionCheck();
		$data['page_info'] 		= 	array('page'=>'TicketSingle','title'=>'TicketSingle');
		$data['ticketData'] 	= 	$this->DatabaseModel->select_data('*','support_ticket',array('ticket_id'=>0,'id'=>$id,'ticket_type'=>$this->session->userdata('support_department')));
		if(!empty($data['ticketData'])){
			$this->load->view('home/inc/header',$data);
		    $this->load->view('support/ticketDetails',$data);
		    $this->load->view('common/notofication_popup');
	        $this->load->view('home/inc/footer',$data);
		}else{
			redirect(base_url('support/dashboard'));
		}

	}

	public function adminTicketReply(){
		$this->load->library('creator_jwt');
		$uid = $this->session->userdata('id');
		$resp = array();
			$rules = array(
				array( 'field' => 'message', 'label' => 'Message', 'rules' => 'trim|required')
			);
			$this->form_validation->set_rules($rules);

			if($this->form_validation->run()){
				$message 		= $this->input->post('message');
				$ticket_type 		= $this->input->post('ticket_type');
				$subject 		= $this->input->post('subject');
				for($j=0; $j < count($_FILES['image_file']['name']); $j++) {
					if(!empty($_FILES['image_file']['name'][$j])){
                       	$rns = $this->common->generateRandomString(20);
						$ext  = pathinfo($_FILES["image_file"]["name"][$j],PATHINFO_EXTENSION);;
						$name = $rns.'.'.$ext;
						$amazon_path = "support/{$name}";
						$res=multipartUploader_ad($_FILES['image_file']['tmp_name'][$j],$amazon_path);
						$file_data[]=$res['url'];
                    }
                }

				$insertArray = array(
					'ticket_type'	=>	$ticket_type,
					'subject' 		=> 	$subject,
					'message' 		=> 	$message,
					'user_id'		=>  0,
					'status'		=>  0,
					'ticket_id'		=>  $this->input->post('ticket_id'),
					'reply_type'	=>  'STU',
					'support_id'	=>  $this->session->userdata('id'),
					'created_at'	=>  date('Y-m-d h:i:s'),
					'attachment_url'=>  (isset($file_data))?json_encode($file_data):''
				);
				$this->DatabaseModel->access_database('support_ticket','insert',$insertArray);
				$this->DatabaseModel->access_database('support_ticket','update',array('status'=>1,'updated_at'=>date('Y-m-d h:i:s')),array('id'=>$this->input->post('ticket_id')));
				$ticketData 	= current($this->DatabaseModel->select_data('*','support_ticket',array('id'=>$this->input->post('ticket_id'))));
				$notification_data= array(
					'noti_status' =>1,
					'noti_type' =>12,
					'from_user' =>1,
					'to_user' =>$ticketData['user_id'],
					'created_at'=>date('Y-m-d h:i:s'),
					'reference_id'=>$this->input->post('ticket_id')
				);
				$this->DatabaseModel->access_database('notifications','insert',$notification_data);
				$token 	= $this->audition_functions->getFirebaseToken($ticketData['user_id']);
				$link = base_url('support/ticketSingle/'.$this->input->post('ticket_id'));
				if(!empty($token)){
					$mess 			= 	$this->audition_functions->getNotiStatus(1,12);
					$msg_array 		=  	[
						'title'	=>	PROJECT .' '. $mess,
						'body'	=>	isset($ticketData['ticket_no'])?'Ticket ID : #'.$ticketData['ticket_no']:'',
						'icon'	=>	base_url('repo/images/firebase.png'),
						'click_action'=>$link
						];
					$this->audition_functions->sendNotification($token,$msg_array);
				}
				$this->statusCode 	= 1;
				$this->statusType 	= 'success';
				$this->respMessage  =  "You have sent a message successfully.";
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode 	= 1;
				$this->statusType 	= 'Error';
			}

		/*else{
			$this->respMessage = $TokenResponce['message'];
			$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode 	= 1;
				$this->statusType 	= 'Error';
		}*/

		$this->show_my_response($resp);
	}

	public function createSupportDepartment(){
		$rules = array(
			array( 'field' => 'name', 'label' => 'Department Name', 'rules' => 'trim|required')
		);

		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$user = array(
				'name' =>$_POST['name'],
				'status'=>1
			);

			if(!empty(trim($_POST['id']))){
				$this->DatabaseModel->access_database('support_department','update',$user,array('id'=>$_POST['id']));
				$this->respMessage = 'User details Inserted successfully.';
				$this->statusCode=1;
				$this->statusType="Success";
			}else{
				$this->DatabaseModel->access_database('support_department','insert',$user);
				$this->respMessage = 'User details Inserted successfully.';
				$this->statusCode=1;
				$this->statusType="Success";
			}
		}else{
			$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
				$this->statusCode=0;
				$this->statusType="Error";
		}
		$this->show_my_response();
	}

	public function access_support_department(){
		$data 		= 	array();
		$start 		= 	$_GET['start'];
		$leadsCount = 	0;
		$search 	= 	$_GET['search']['value'];
		$field		= 	['id','name'];
		$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
		$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
		$cond="";

		$userData 	= 	$this->DatabaseModel->select_data('*','support_department', $cond ,array($_GET['length'],$start) ,'', array($field[$colm],$order) );
		$leadsCount =	$this->DatabaseModel->aggregate_data('support_department','id','COUNT',$cond,'');

		if(!empty($userData)){
			$start++;
			foreach($userData as $list){
				$ustatus 	 = $list['status'];
				$user_status = ($ustatus == 1) ? 'ACTIVE' : 'INACTIVE';
				$check 		 = ($list['status'] == 1)? 'checked' : '';
				array_push($data , array(
					$start++,
					$list['name'],
					'<a class="support_department" data-id="'.$list['id'].'" data-name="'.$list['name'].'" ><i class="fa fa-fw fa-edit"></i></a>',
					'<input '.$check.' type="checkbox" data-check-id="'.$list['id'].'" data-action-url="support/updateCheckStatus/support_department"> '.' '.$user_status
				));
			}
		}
		// $this->session->set_userdata('export_user_details',$data);
		echo json_encode(array(
					'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
					'recordsTotal' => $leadsCount,
					'recordsFiltered' => $leadsCount,
					'data' => $data,
					));
	}

	public function adminTicketAccept(){
		$rules = array(
			array( 'field' => 'ticket_id', 'label' => 'ticket Name', 'rules' => 'trim|required')
		);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$check 	= $this->DatabaseModel->select_data('*','support_ticket',array('id'=>$this->input->post('ticket_id')));
			//echo $this->db->last_query();
			//print_r($check);
			if(empty($check[0]['accept_by'])){
				$this->DatabaseModel->access_database('support_ticket','update',array('accept_by'=>$this->session->userdata('id')),array('id'=>$this->input->post('ticket_id')));
				$this->respMessage = 'You accepted this ticket successfully.';
				$this->statusCode=1;
				$this->statusType="success";
			}else{
				$this->respMessage = 'Ticket Alredy Accepted';
				$this->statusCode=0;
				$this->statusType="Error";
			}

		}else{
			$errors = array_values($this->form_validation->error_array());
				$this->respMessage  = 'Somthing Went Wrong';
				$this->statusCode=0;
				$this->statusType="Error";
		}
		$this->show_my_response();
	}

	public function transferTicket(){


		$rules = array(
			array( 'field' => 'ticket_id', 'label' => 'ticket Name', 'rules' => 'trim|required'),
			array( 'field' => 'department_id', 'label' => 'Department', 'rules' => 'trim|required'),
		);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$this->DatabaseModel->access_database('support_ticket','update',array('ticket_type'=>$this->input->post('department_id')),array('id'=>$this->input->post('ticket_id')));
			$this->respMessage = 'This ticket transferred successfully';
			$this->statusCode=1;
			$this->statusType="success";
		}else{
			$errors = array_values($this->form_validation->error_array());
				$this->respMessage  = isset($errors[0])?$errors[0]:'';
				$this->statusCode=0;
				$this->statusType="Error";
		}
		$this->show_my_response();
	}

	public function TicketLimit(){

		$ticketReplayData=$this->DatabaseModel->select_data('*','support_ticket',array('ticket_id'=>$this->input->post('ticket_id')));

		$html='';
		foreach($ticketReplayData as $key=>$value){
			$url_pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';

                if($value['reply_type']=='UTS'){
                    $cuserReplay = current(get_user($value['user_id']));
                    $user_page_image    =   (isset($cuserReplay['uc_pic']) && !empty($cuserReplay['uc_pic']))?create_upic($value['user_id'],$cuserReplay['uc_pic']) : '';
                }else{
                    $user_page_image=base_url('repo/images/banner_logo1.png');
                }
                $default_image="'".user_default_image()."'";
				$user_name=  ($value['reply_type']=='UTS')?$cuserReplay['user_name'].' </h2><h2 class="dis_ticket_cd_ttl mp_0 m_b_10">Replied Ticket - ': PROJECT . ' Support Executive - ';

				if(isset($_SESSION['support_department']) && !empty($_SESSION['support_department'])){

					if($value['reply_type']=='STU'){
						$support_user 	= 	$this->DatabaseModel->select_data('user_name','support_user',array('id' =>$value['support_id']));
						$user_name=   $support_user[0]['user_name'].'</h2><h2 class="dis_ticket_cd_ttl mp_0 m_b_10">Replied Ticket -';
					}else{
						$user_name=   $cuserReplay['user_name'].' </h2><h2 class="dis_ticket_cd_ttl mp_0 m_b_10">Replied Ticket - ';
					}
				}

				$attachment_url= json_decode($value['attachment_url']);
                $y=0;
                $attech="<ul class='dis_support_attamntlist'>";
                if(!empty($attachment_url)){
                    foreach ($attachment_url as $key => $attachment_urls) {
                        $y++;

                     $attech.='<li><a href="'.$attachment_urls.'" class="" target="_blank" ><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px"><path fill-rule="evenodd" fill="rgb(151, 151, 151)" d="M13.671,3.266 C12.939,1.466 11.561,0.458 9.683,0.351 L9.667,0.351 C8.289,0.377 7.261,0.780 6.432,1.621 C5.638,2.426 4.848,3.262 4.084,4.071 L2.795,5.432 C2.157,6.103 1.519,6.773 0.894,7.458 C0.375,8.028 0.092,8.750 0.054,9.603 C-0.009,11.030 0.733,12.339 1.947,12.936 C3.211,13.559 4.647,13.320 5.609,12.332 C7.349,10.545 9.080,8.701 10.565,7.113 C11.113,6.528 11.277,5.787 11.039,4.971 C10.824,4.236 10.280,3.686 9.582,3.498 C8.876,3.307 8.137,3.521 7.605,4.068 C6.855,4.838 6.118,5.621 5.380,6.403 L4.487,7.348 C4.417,7.422 4.363,7.492 4.322,7.561 C4.120,7.907 4.167,8.349 4.439,8.637 C4.711,8.926 5.141,8.979 5.462,8.765 C5.574,8.689 5.668,8.590 5.757,8.497 L7.015,7.172 C7.583,6.572 8.151,5.973 8.721,5.375 C8.821,5.269 9.082,5.052 9.340,5.312 C9.420,5.393 9.463,5.491 9.464,5.594 C9.466,5.722 9.404,5.859 9.290,5.979 L8.567,6.744 C7.229,8.158 5.891,9.573 4.545,10.980 C3.879,11.677 2.851,11.690 2.203,11.010 C1.543,10.318 1.555,9.248 2.229,8.521 C2.560,8.165 2.895,7.813 3.230,7.461 L3.614,7.059 C4.014,6.638 4.411,6.216 4.809,5.793 C5.709,4.836 6.640,3.847 7.579,2.903 C8.151,2.329 8.910,2.047 9.710,2.116 C10.531,2.184 11.288,2.617 11.786,3.304 C12.689,4.548 12.567,6.215 11.489,7.358 C10.452,8.457 9.412,9.554 8.371,10.650 L6.335,12.796 C6.141,13.003 6.035,13.254 6.036,13.503 C6.038,13.727 6.128,13.942 6.289,14.108 C6.451,14.274 6.648,14.356 6.850,14.356 C7.077,14.356 7.310,14.252 7.507,14.046 C7.842,13.698 8.174,13.347 8.506,12.996 L8.785,12.701 C9.196,12.266 9.611,11.835 10.026,11.404 C10.969,10.424 11.945,9.410 12.859,8.366 C14.148,6.894 14.429,5.131 13.671,3.266 Z"></path></svg> Attachment '.$y.'</a><li>';
                     }
                }
				$attech.='</ul>';
            $html.='<div class="dis_ticket_chat_receiver m_b_30">
                <div class="dis_ticket_chatbox">
                    <div class="dis_ticket_chat_thumb">
                        <span class="dis_ticket_chat_timg">
                            <img src="'.$user_page_image.'" alt="thumb" class="img-responsive" onerror="this.onerror=null;this.src='.$default_image.'">
                        </span>
                    </div>
                    <div class="dis_ticket_chat_data">
                        <h2 class="dis_ticket_cd_ttl mp_0 m_b_10">'.$user_name.date("j F Y g:i a",strtotime($this->common->manageTimezone($value['created_at']))).'</h2>
                        <p class="dis_ticket_chat_des">'.nl2br(preg_replace($url_pattern, '
												<a href="$0" target="_blank"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 107.68" style="width: 13px;margin-right: 5px;"><defs><style>.cls-1{fill-rule:evenodd;}</style></defs><title>remove-link</title><path class="cls-1" d="M97.62,40.71A25.26,25.26,0,1,1,72.37,66,25.25,25.25,0,0,1,97.62,40.71ZM51,31.51a5.35,5.35,0,0,1-7.57-7.57L60.79,6.62c5-5,12-7,19-6.56a30.61,30.61,0,0,1,19.37,8.5,30.61,30.61,0,0,1,8.5,19.37,28.07,28.07,0,0,1-.28,6.29,33.23,33.23,0,0,0-9.72-1.44h-.88A17.64,17.64,0,0,0,97,28.6a19.71,19.71,0,0,0-5.41-12.47,19.69,19.69,0,0,0-12.47-5.41c-4.08-.25-8,.8-10.72,3.47L51,31.51ZM63.8,36.32a5.35,5.35,0,0,1,7.56,7.57L43.88,71.36a5.35,5.35,0,0,1-7.56-7.56L63.8,36.32Zm-7.33,40A5.35,5.35,0,1,1,64,83.92L46.89,101.07c-5,5-12,7-19,6.55a30.64,30.64,0,0,1-19.37-8.5A30.61,30.61,0,0,1,.06,79.75c-.44-7,1.56-14,6.56-19L23.76,43.64a5.35,5.35,0,1,1,7.57,7.57L14.19,68.36C11.52,71,10.47,75,10.72,79.08a19.69,19.69,0,0,0,5.41,12.47A19.71,19.71,0,0,0,28.6,97c4.08.26,8-.79,10.72-3.46L56.47,76.35Zm55.07-13.07v5.36a2,2,0,0,1-2,2H85.68a2,2,0,0,1-2-2V63.28a2,2,0,0,1,2-2h23.88a2,2,0,0,1,2,2Z"/></svg>$0</a>', $value['message'])).'</p>'.$attech.'
                    </div>
                </div>
            </div>';

        }
                       // print_r($html);
		$this->respMessage = $html;
		$this->statusCode=1;
		$this->statusType="Success";
		$this->show_my_response();
	}

	public function getUserTicket(){

		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == base_url('support/dashboard')){
			$uid = '';
		}else{
			$uid = $this->session->userdata('user_login_id');
		}

		$department =$this->session->userdata('support_department');
		
		$this->load->library('custom_pagination');

		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:10;
		$no_of_records 	= isset($_POST['no_of_records'])?$_POST['no_of_records']:10;
		$ticket_status 	= isset($_POST['ticket_status'])?$_POST['ticket_status']:'';
		$tech_status 	= isset($_POST['tech_status'])?$_POST['tech_status']:'';
		$user_type 		= isset($_POST['user_type'])?$_POST['user_type']:'';
		$date_range 	= isset($_POST['date_range'])?$_POST['date_range']:'';

		$fields = "support_ticket.user_id,support_ticket.id,support_ticket.subject,message,support_ticket.status,support_ticket.ticket_no, DATE_FORMAT(support_ticket.created_at, '%d %M %Y') as created_at , DATE_FORMAT(support_ticket.updated_at, '%d %M %Y') as updated_at ,users.user_name,artist_category.category_name,users.user_name,users.user_uname,users_content.uc_pic,support_user.user_name as suooprt_teme_name,support_ticket.accept_by,support_ticket.assign_by_admin,support_ticket.technology,support_department.name as department_name";

		$cond = "support_ticket.ticket_id= 0 ";

		$order_by = array('support_ticket.updated_at','DESC');

		$join = array('multiple' , array(
									array(	'users',
											'users.user_id 				 = support_ticket.user_id',
											'left'),
									array(	'artist_category',
											'artist_category.category_id  = users.user_level',
											'left'),
									array(	'users_content',
											'users_content.uc_userid	 =  users.user_id ',
											'left'),
								  	array(	'support_department',
											'support_department.id	 =  support_ticket.ticket_type ',
											'left'),
									array(	'support_user',
											'support_user.id	 =  support_ticket.accept_by ',
											'left'),
									));

		if(!empty($no_of_records)){
			$limit = $no_of_records;
		}

		if($uid !=''){
			$cond .=" AND support_ticket.user_id =$uid";
		}

		if($department !=''){
			$cond .=" AND ticket_type = $department";
		}

		if($ticket_status !=''){
			$cond .=" AND support_ticket.status = $ticket_status";
		}

		if(!empty($user_type)){
			$cond .=" AND artist_category.category_id = $user_type";
		}

		if($tech_status != 'All' && !empty($tech_status)){
			$cond .=" AND support_ticket.technology LIKE '%".$tech_status."%'";
		}

		if(!empty($date_range)){
			$date = explode(' - ' , $date_range);
			$date1 		= "'".date('Y-m-d' , strtotime($date[0]))."'";
			$date2 		= "'".date('Y-m-d' , strtotime($date[1]))."'";
			$cond .=" AND DATE(support_ticket.created_at) BETWEEN $date1 AND $date2";
		}


		$ticketData   = $this->DatabaseModel->select_data($fields,'support_ticket',$cond,array($limit ,$start),$join,$order_by);
		if(!empty($ticketData)){
			foreach($ticketData as $key=>$t){
				$ticketData[$key]['uc_pic'] = !empty($t['uc_pic']) ? create_upic($t['user_id'], $t['uc_pic']) : user_default_image() ;
				$ticketData[$key]['href']   = base_url('profile?user='.$t['user_uname']);
				$ticketData[$key]['uc_pic_er']=user_default_image();
				$ticketData[$key]['reply_count']=$this->DatabaseModel->aggregate_data('support_ticket','support_ticket.id','COUNT',array('ticket_id'=>$t['id']));
				
				$ticketData[$key]['last_replied']   = current($this->DatabaseModel->select_data('support_user.user_name','support_ticket',array('support_id !='=> 0,'ticket_id'=> $t['id']),1,array('support_user','support_user.id	 =  support_ticket.support_id '),array('support_ticket.id','DESC')));
				$ticketData[$key]['last_replied']   = isset($ticketData[$key]['last_replied']['user_name'])?$ticketData[$key]['last_replied']['user_name']:'';
				
				$ticketData[$key]['assign_by_name']   = current($this->DatabaseModel->select_data('support_user.user_name','support_user',array('id'=>$t['assign_by_admin']),1));
				$ticketData[$key]['assign_by_name']   = isset($ticketData[$key]['assign_by_name']['user_name'])?$ticketData[$key]['assign_by_name']['user_name']:'';
			}
		}

		$ticketCount  = $this->DatabaseModel->aggregate_data('support_ticket','support_ticket.id','COUNT',$cond,$join);

		$pagination   = $this->custom_pagination->pagination($ticketCount,$start,$limit);

		$resp 		  = array('status'=>1 , 'data'=>array('ticketData'=>$ticketData, 'pagination'=>$pagination));

		echo json_encode($resp);

	}

	public function change_ticket_status(){

		if(!empty($this->input->post('ticket_id'))){
			$this->DatabaseModel->access_database('support_ticket','update',array('status'=>$this->input->post('status')),array('id'=>$this->input->post('ticket_id')));
			$ticketData 	= current($this->DatabaseModel->select_data('*','support_ticket',array('id'=>$this->input->post('ticket_id'))));
				$notification_data= array(
					'noti_status' =>($this->input->post('status')==2)?2:3,
					'noti_type' =>12,
					'from_user' =>1,
					'to_user' =>$ticketData['user_id'],
					'created_at'=>date('Y-m-d h:i:s'),
					'reference_id'=>$this->input->post('ticket_id')
				);
				$noti_status=($this->input->post('status')==2)?2:3;
			$this->DatabaseModel->access_database('notifications','insert',$notification_data);
			$token 	= $this->audition_functions->getFirebaseToken($ticketData['user_id']);
				$link = base_url('support/ticketSingle/'.$this->input->post('ticket_id'));
				if(!empty($token)){
					$mess 			= 	$this->audition_functions->getNotiStatus($noti_status,12);
					$msg_array 		=  	[
						'title'	=>	PROJECT .' '. $mess,
						'body'	=>	isset($ticketData['ticket_no'])?'Ticket ID : #'.$ticketData['ticket_no']:'',
						'icon'	=>	base_url('repo/images/firebase.png'),
						'click_action'=>$link
						];
					$this->audition_functions->sendNotification($token,$msg_array);
				}
			$this->respMessage = 'Ticket status updated successfully';
			$this->statusCode=1;
			$this->statusType="success";
		}else{

				$this->respMessage  ="Somthing Went Wrong";
				$this->statusCode=0;
				$this->statusType="Error";
		}
		$this->show_my_response();
	}
	public function logout(){
		$this->session->sess_destroy();
		clear_cache();
        redirect(base_url('support/login'));
	}

	public function TicketLimitTest(){
		$ticketDatas 	= 	current($this->DatabaseModel->select_data('*','support_ticket',array('ticket_id'=>0,'id'=>$this->input->post('ticket_id'))));
		$ticketReplayData=$this->DatabaseModel->select_data('*','support_ticket',array('ticket_id'=>$this->input->post('ticket_id')));
		$cuser              =   current(get_user($ticketDatas['user_id']));

    	$user_page_image    =   (isset($cuser['uc_pic']) && !empty($cuser['uc_pic']))?create_upic($ticketDatas['user_id'],$cuser['uc_pic']) : '';
    	$default_image="'".user_default_image()."'";
		$html='<div class="dis_single_ticket_inner">
                    <div class="dis_single_ticket_topbox dis_comn_whiteborder">
                        <h2 class="dis_ticketbox_ttl mp_0">'.$ticketDatas['subject'].'</h2>
                        <ul class="dis_tb_hd_list dis_ticketbox_infoicon_list d-flex">
                            <li>
                                <div class="dis_ticketbox_infoicon">
                                    <span class="dis_ticketbox_infoicon_icon">
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M8.867,13.000 L1.132,13.000 C0.509,12.998 0.005,12.464 0.003,11.805 L0.003,1.195 C0.005,0.536 0.509,0.002 1.132,-0.000 L5.928,-0.000 L5.928,2.314 C5.928,3.414 6.770,4.305 7.810,4.305 L9.996,4.305 L9.996,11.805 C9.995,12.464 9.490,12.998 8.867,13.000 ZM3.655,4.140 L2.023,4.140 C1.713,4.140 1.460,4.408 1.460,4.737 C1.460,5.065 1.713,5.333 2.023,5.333 L3.655,5.333 C3.965,5.333 4.218,5.065 4.218,4.737 C4.218,4.408 3.965,4.140 3.655,4.140 ZM7.451,6.792 L2.023,6.792 C1.713,6.792 1.460,7.060 1.460,7.389 C1.460,7.717 1.713,7.985 2.023,7.985 L7.451,7.985 C7.762,7.985 8.015,7.717 8.015,7.389 C8.015,7.060 7.762,6.792 7.451,6.792 ZM7.451,9.448 L2.023,9.448 C1.713,9.448 1.460,9.715 1.460,10.043 C1.460,10.372 1.713,10.640 2.023,10.640 L7.451,10.640 C7.762,10.640 8.015,10.372 8.015,10.043 C8.015,9.715 7.762,9.448 7.451,9.448 ZM6.680,2.314 L6.680,0.562 L9.473,3.509 L7.810,3.509 C7.186,3.508 6.681,2.974 6.680,2.314 Z"/></svg>
                                    </span>
                                    <span class="dis_ticketbox_infoicon_ttl mp_0">Ticket ID #'.$ticketDatas['ticket_no'].'</span>
                                </div>
                            </li>
                            <li>
                                <div class="dis_ticketbox_infoicon">
                                    <span class="dis_ticketbox_infoicon_icon">
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.722,13.000 L1.907,13.000 C1.299,13.000 0.805,12.471 0.805,11.828 L0.805,2.612 C0.805,1.980 1.288,1.466 1.875,1.447 L1.875,3.020 C1.875,3.731 2.420,4.304 3.092,4.304 L3.860,4.304 C4.532,4.304 5.084,3.731 5.084,3.020 L5.084,1.442 L8.545,1.442 L8.545,3.020 C8.545,3.731 9.097,4.304 9.769,4.304 L10.537,4.304 C11.209,4.304 11.754,3.731 11.754,3.020 L11.754,1.447 C12.341,1.466 12.824,1.980 12.824,2.612 L12.824,11.828 C12.824,12.470 12.329,13.000 11.722,13.000 ZM11.397,6.494 C11.397,6.215 11.184,5.990 10.921,5.990 L2.687,5.990 C2.424,5.990 2.211,6.215 2.211,6.494 L2.211,11.254 C2.211,11.532 2.424,11.758 2.687,11.758 L10.921,11.758 C11.184,11.758 11.397,11.532 11.397,11.254 L11.397,6.494 ZM9.734,11.070 L8.761,11.070 C8.607,11.070 8.482,10.938 8.482,10.775 L8.482,9.745 C8.482,9.582 8.607,9.451 8.761,9.451 L9.734,9.451 C9.888,9.451 10.013,9.582 10.013,9.745 L10.013,10.775 C10.013,10.938 9.888,11.070 9.734,11.070 ZM9.734,8.497 L8.761,8.497 C8.607,8.497 8.482,8.365 8.482,8.202 L8.482,7.172 C8.482,7.009 8.607,6.877 8.761,6.877 L9.734,6.877 C9.888,6.877 10.013,7.009 10.013,7.172 L10.013,8.202 C10.013,8.365 9.888,8.497 9.734,8.497 ZM7.301,11.070 L6.328,11.070 C6.174,11.070 6.049,10.938 6.049,10.775 L6.049,9.745 C6.049,9.582 6.174,9.451 6.328,9.451 L7.301,9.451 C7.455,9.451 7.580,9.582 7.580,9.745 L7.580,10.775 C7.580,10.938 7.455,11.070 7.301,11.070 ZM7.301,8.497 L6.328,8.497 C6.174,8.497 6.049,8.365 6.049,8.202 L6.049,7.172 C6.049,7.009 6.174,6.877 6.328,6.877 L7.301,6.877 C7.455,6.877 7.580,7.009 7.580,7.172 L7.580,8.202 C7.580,8.365 7.455,8.497 7.301,8.497 ZM4.868,11.070 L3.895,11.070 C3.741,11.070 3.616,10.938 3.616,10.775 L3.616,9.745 C3.616,9.582 3.741,9.451 3.895,9.451 L4.868,9.451 C5.022,9.451 5.147,9.582 5.147,9.745 L5.147,10.775 C5.147,10.938 5.022,11.070 4.868,11.070 ZM4.868,8.497 L3.895,8.497 C3.741,8.497 3.616,8.365 3.616,8.202 L3.616,7.172 C3.616,7.009 3.741,6.877 3.895,6.877 L4.868,6.877 C5.022,6.877 5.147,7.009 5.147,7.172 L5.147,8.202 C5.147,8.365 5.022,8.497 4.868,8.497 ZM10.519,3.461 L9.759,3.461 C9.529,3.461 9.342,3.263 9.342,3.019 L9.342,0.441 C9.342,0.197 9.529,-0.000 9.759,-0.000 L10.519,-0.000 C10.749,-0.000 10.936,0.197 10.936,0.441 L10.936,3.019 C10.936,3.263 10.749,3.461 10.519,3.461 ZM3.849,3.461 L3.090,3.461 C2.859,3.461 2.672,3.263 2.672,3.019 L2.672,0.441 C2.672,0.197 2.859,-0.000 3.090,-0.000 L3.849,-0.000 C4.079,-0.000 4.266,0.197 4.266,0.441 L4.266,3.019 C4.266,3.263 4.079,3.461 3.849,3.461 Z"></path></svg>
                                    </span>
                                    <span class="dis_ticketbox_infoicon_ttl mp_0">
                                        Created Date - '.date("j F Y",strtotime($ticketDatas['created_at'])).'
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="dis_ticketbox_infoicon">
                                    <span class="dis_ticketbox_infoicon_icon">
                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="12px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M0.388,5.430 C1.171,3.414 3.287,2.405 6.735,2.405 L8.360,2.405 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.087,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.082 2.615,7.295 C2.378,7.508 2.184,7.760 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.555,9.945 1.555,10.451 C1.555,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.566,11.937 C1.525,11.987 1.468,12.011 1.396,12.011 C1.318,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.000,11.494 C0.964,11.409 0.939,11.349 0.924,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"></path></svg>
                                    </span>
                                    <span class="dis_ticketbox_infoicon_ttl mp_0">
                                        Replied On - '.date("j F Y ",strtotime($ticketDatas['updated_at'])).'
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="dis_st_chatbox_wrap">
                        <div class="dis_st_chatbox_wrap">
                            <div class="dis_ticket_chat_receiver m_b_30 m_t_30">
                                <div class="dis_ticket_chatbox">
                                    <div class="dis_ticket_chat_thumb">
                                        <span class="dis_ticket_chat_timg">
                                            <img src="'.$user_page_image.'" alt="thumb" class="img-responsive" onerror="this.onerror=null;this.src='.$default_image.'">
                                        </span>
                                    </div>
                                    <div class="dis_ticket_chat_data">
                                        <h2 class="dis_ticket_cd_ttl mp_0 m_b_10">You Created Ticket -'.date("j F Y g:i a",strtotime($this->common->manageTimezone($ticketDatas['created_at']))).' </h2>
                                        <p class="dis_ticket_chat_des">'.$ticketDatas['message'].'</p>
                                    </div>
                                </div>
                            </div>';
		foreach($ticketReplayData as $key=>$value){
                if($value['reply_type']=='UTS'){
                    $cuserReplay = current(get_user($value['user_id']));
                    $user_page_image    =   (isset($cuserReplay['uc_pic']) && !empty($cuserReplay['uc_pic']))?create_upic($value['user_id'],$cuserReplay['uc_pic']) : '';
                }else{
                    $user_page_image=user_default_image();
                }

              $user_name=  ($value['reply_type']=='UTS')?$cuserReplay['user_name']." </br>Created Ticket - ": PROJECT . " Support Executive - ";
            $html.='<div class="dis_ticket_chat_receiver m_b_30">
                <div class="dis_ticket_chatbox">
                    <div class="dis_ticket_chat_thumb">
                        <span class="dis_ticket_chat_timg">
                            <img src="'.$user_page_image.'" alt="thumb" class="img-responsive" onerror="this.onerror=null;this.src='.$default_image.'">
                        </span>
                    </div>
                    <div class="dis_ticket_chat_data">
                        <h2 class="dis_ticket_cd_ttl mp_0 m_b_10">'.$user_name.date("j F Y g:i a",strtotime($this->common->manageTimezone($value['created_at']))).'</h2>
                        <p class="dis_ticket_chat_des">'.$value['message'].'</p>
                    </div>
                </div>
            </div>';

        }
        $html.='<form class="tickeReplayForm" action="support/ticketReply">
                                <input type="hidden" name="ticket_id" value="'.$ticketDatas['id'].'">
                                <input type="hidden" name="ticket_type" value="'.$ticketDatas['ticket_type'].'">
                                <input type="hidden" name="subject" value="'.$ticketDatas['subject'].'">
                                ';
                       if($ticketDatas['status']!=2){
                               $html.='  <div class="dis_ticket_chat_sender m_b_30">
                                    <div class="dis_ticket_chatbox">
                                        <div class="dis_ticket_chat_thumb">
                                            <span class="dis_ticket_chat_timg">
                                                <img src="'.$user_page_image.'" alt="thumb" class="img-responsive" onerror="this.onerror=null;this.src='.$default_image.'">
                                            </span>
                                        </div>
                                        <div class="dis_ticket_chat_data dis_ticket_chat_textarea">
                                            <textarea name="message" cols="30" rows="6" placeholder="Enter Your Message Here"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="dis_ticket_chat_submit_btn">
                                    <button type="submit" class="dis_btn min_width_inherit b-r-5">send reply</button>
                                </div>';
                                }
                            $html.='</form>

                        </div>
                    </div>
                </div>';

		$this->respMessage = $html;
		$this->statusCode=1;
		$this->statusType="Success";
		$this->show_my_response();
	}

	public function support_ticket(){
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
		$data['page_menu'] = 'support_ticket|team_list|Support Ticket|Support';
		
		$data['department'] 	= 	$this->DatabaseModel->select_data('*','support_department',array('status'=>1));

		$this->load->view('admin/include/header',$data);
		$this->load->view('support/support_ticket',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}

	public function access_support_ticket(){
		
		if(isset($_GET['start'])){

			$data 		= 	array();
			$start 		= 	$_GET['start'];
			$leadsCount = 	0;
			$search 	= 	$_GET['search']['value'];

			$field		= 	['support_ticket.id','ticket_no','subject','support_ticket.status','support_ticket.created_at','support_ticket.updated_at','ticket_type','name','assign_by_admin'];

			$colm 		=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
			$order 		=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;

			$cond=" ticket_id=0 ";

			if(isset($_GET['ticket_status']) && $_GET['ticket_status']!='')
				$cond .= ' AND support_ticket.status = '.$_GET['ticket_status'];
			
			if(isset($_GET['tech_status']) && $_GET['tech_status'] !='All')  
				$cond .=" AND support_ticket.technology LIKE '%".$_GET['tech_status']."%'";
			

			if(isset($_GET['department']) && !empty($_GET['department']))
				$cond .= ' AND support_department.id = '.$_GET['department'];

			if(!empty($_GET['date_range'])){
				$date = explode(' - ' , $_GET['date_range']);
				$date1 		= "'".date('Y-m-d' , strtotime($date[0]))."'";
				$date2 		= "'".date('Y-m-d' , strtotime($date[1]))."'";
				$cond .=" AND DATE(support_ticket.created_at) BETWEEN $date1 AND $date2";
			} 
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
			$userData 	= 	$this->DatabaseModel->select_data($field,'support_ticket', $cond ,array($_GET['length'],$start) ,array('support_department','support_department.id=support_ticket.ticket_type'), array($field[$colm],$order) );
			$leadsCount =	$this->DatabaseModel->aggregate_data('support_ticket','support_ticket.id','COUNT',$cond,array('support_department','support_department.id=support_ticket.ticket_type'));
			if(!empty($userData)){
				$departments 	= 	$this->DatabaseModel->select_data('*','support_department',array('status'=>1));
				$user_list 	= 	$this->DatabaseModel->select_data('*','support_user',array('status'=>1));
				$start++;
				foreach($userData as $list){ 

					
					$selest_option='<select class="form-control assign_ticket" data-tid="'.$list['id'].'">';
					$selest_option.='<option value="0">Select user</option>';
					foreach($user_list as $key => $value) {
						if($value['support_department'] == $list['ticket_type']){
							$sel=($value['id']==$list['assign_by_admin'])?'selected':'';
							$selest_option.='<option value="'.$value['id'].'" '.$sel.'>'.$value['user_name'].'</option>';
						}
					}

					$selest_option.='</select>';
					$user_status='';
					if($list['status']==0){
						$user_status="Open";
					}elseif($list['status']==1){
						$user_status="Replied";
					}elseif($list['status']==2){
						$user_status="Closed";
					}elseif($list['status']==3){
						$user_status="Customer Replied";
					}
				
					
					$dep_option='<select class="form-control trsnfer_ticket" data-tid="'.$list['id'].'">';
					$dep_option.='<option value="0">Select user</option>';
					foreach($departments as $key => $department) {
						$sel=($department['id']==$list['ticket_type'])?'selected':'';
						$dep_option.='<option value="'.$department['id'].'" '.$sel.'>'.$department['name'].'</option>';
					}
					$dep_option.='</select>';
							$uid=0;
						if($list['ticket_type']==1){
							$uid=5;
						}elseif($list['ticket_type']==2){
							$uid=6;
						}elseif($list['ticket_type']==3){
							$uid=7;
						}
					array_push($data , array(
						$start++,
						'<a href="javascript:void(0)" class="LoginMeSupport" data-uid="'.$uid.'" data-tid="'.$list['id'].'">'.$list['ticket_no'].'</a>',
						$list['subject'],
						$user_status,
						date('d-m-Y',strtotime($list['created_at'])),
						date('d-m-Y',strtotime($list['updated_at'])),
						$list['name'],
						$dep_option,
						$selest_option

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
		
	}
	
	public function assign_ticket(){
		$rules = array(
			array( 'field' => 'ticket_id', 'label' => 'ticket Name', 'rules' => 'trim|required'),
			array( 'field' => 'user_id', 'label' => 'User', 'rules' => 'trim|required')

		);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$this->DatabaseModel->access_database('support_ticket','update',array('assign_by_admin'=>$this->input->post('user_id')),array('id'=>$this->input->post('ticket_id')));
			$this->respMessage = 'You assign this ticket successfully.';
			$this->statusCode=1;
			$this->statusType="success";
		}else{
			$errors = array_values($this->form_validation->error_array());
				$this->respMessage  = 'Somthing Went Wrong';
				$this->statusCode=0;
				$this->statusType="Error";
		}
		$this->show_my_response();
	}
}
