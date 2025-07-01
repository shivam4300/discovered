<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endorse extends CI_Controller {
	
	private $uid;
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('manage_session','audition_functions','form_validation')); 
		if(!is_login()){
			redirect(base_url());
		}
		$this->uid = is_login();
		
	}
	function countEndorsement($cond){
		return  $this->DatabaseModel->aggregate_data('become_a_endorser','endorse_id','COUNT',$cond);
	}
	
	function IWantToendorseYou(){
		if(isset($_POST)){
			if(!empty($_POST['endorsee_id'])){
				
				$endorser_id 	= $this->uid;
				$endorsee_id 	= $_POST['endorsee_id'];
				$current_date 	= "'".date('Y-m-d')."'";
				
				$up_to_cond = "(endorser_id = $endorser_id) AND ((endorsement_status = 1) || (endorsement_status = 2 AND endorsement_end_date > MONTH($current_date)))";
				
				$total_endoroser_endorsement = $this->countEndorsement($up_to_cond);
				
				$cond = "field IN('endorse_up_to','receive_up_to','revenue_share')";
				$detail = $this->DatabaseModel->select_data('value','endorsement_details',$cond);
				
				$endorse_up_to 	= $detail[0]['value'];		
				$receive_up_to 	= $detail[1]['value'];		
				$revenue_share 	= $detail[2]['value'];		
				
				$endorser = array('endorsee_id'=>trim($endorsee_id),'endorser_id'=>$endorser_id,);	
				$isEndorse_user = $this->DatabaseModel->select_data('endorsement_status','become_a_endorser',$endorser,1);
					
					if(empty($isEndorse_user)){
						
						if($total_endoroser_endorsement < $endorse_up_to ){
							
							$up_to_cond = "endorsee_id = $endorsee_id AND endorsement_status = 2 AND endorsement_end_date >= MONTH($current_date)";
							$total_endorosee_endorsement = $this->countEndorsement($up_to_cond);
							
							if($total_endorosee_endorsement < $receive_up_to){
								
									$endorser['created_date'] 		= date('Y-m-d H:i:s');
									$endorser['revenue_share'] 		= $revenue_share;
									$endorser['endorsement_status'] = 1;
									
									$insert_id = $this->DatabaseModel->access_database('become_a_endorser','insert',$endorser);
									if($insert_id >0){
										
										/* start Insert notification*/
										$insert_array = array(	'noti_type'		=>	1,
																'noti_status'	=>	1,
																'from_user'		=>	$endorser_id,
																'to_user'		=>	$endorsee_id,
																'reference_id'	=>	$insert_id,
																'created_at'	=>	date('Y-m-d H:i:s')
																);
										$this->audition_functions->insertNoti($insert_array);
										/* end Insert notification*/
										
										/* start send firebase notification*/
										$token 		= $this->audition_functions->getFirebaseToken($endorsee_id);
										$fullname 	= $this->audition_functions->get_user_fullname($endorser_id);
										$mess 		= $this->audition_functions->getNotiStatus(1,1);
										
										if(!empty($token)){
											// $token_array = [$token];
											$msg_array =  [
												'title'	=>$mess,
												'body'	=>$fullname,
												'icon'	=>base_url('repo/images/firebase.png'),
												'click_action'=>$this->audition_functions->getNotiLink(1,1,$insert_id,true)
											];
											$this->audition_functions->sendNotification($token,$msg_array);
										}
										/* end send firebase notification*/
									}
							}else{
								echo json_encode(array('status'=>0,'messsage'=>'You can\'t send endorsement request because Endorsee has already accepted '.$receive_up_to.' request,'));die;
							}
						
						
						}else{
							echo json_encode(array('status'=>0,'messsage'=>'You can\'t send more than '.$endorse_up_to.' endorse request'));die;
						}
					}else{
						if($isEndorse_user[0]['endorsement_status']== '1'){  /*Requested*/
							if($this->DatabaseModel->access_database('become_a_endorser','delete','',$endorser)>0){
								/* start delete notification*/
								$where_array = array(	'noti_type'		=>	1,
														'noti_status'	=>	1,
														'from_user'		=>	$endorser_id,
														'to_user'		=>	$endorsee_id,
														);
								$this->audition_functions->deleteNoti($where_array);
								/* end delete notification*/
							}
						}
					}
				echo json_encode(array('status'=>1,'data'=>EndorseButton(trim($_POST['endorsee_id']))));
			}
		}
	}
	
	public function request($id=null){
		if(!empty($id)){
			$endorse_id = $this->common->base64url_decode($id);
			$IsEndorse = $this->DatabaseModel->select_data('*','become_a_endorser',array('endorse_id'=>$endorse_id),1);
			
			if(isset($IsEndorse[0]) && !empty($IsEndorse)){
				$data = $IsEndorse[0];
				$data['endorse_id'] = $id;
				
				$data['page_info'] = array('page'=>'endorsement','title'=>'Endorsement');
				$this->load->view('home/inc/header',$data);
				$this->load->view('home//endorsement/agreement',$data);
				$this->load->view('home/inc/footer',$data);
			}else{
				redirect(base_url());
			}
		}
	}
	
	public function endorse_me(){
		if(isset($_POST)){
			
			if(!empty($_POST['endorse_id']) || !empty($_POST['endorsement_status'])){
				
				$uid 		= $this->uid;
				$status 	= $_POST['endorsement_status'];
				$endorse_id = $this->common->base64url_decode($_POST['endorse_id']);
				$current_date 	= "'".date('Y-m-d')."'";
				
				$cond 		= array('endorse_id'=>$endorse_id,'endorsee_id'=>$uid);
				
				$IsEndorse = $this->DatabaseModel->select_data('*','become_a_endorser',$cond,1);
				
				if(isset($IsEndorse[0]) && !empty($IsEndorse)){
					
					$endorser_id = $IsEndorse[0]['endorser_id'];
					
					if($IsEndorse[0]['endorsement_status'] == 1 || $IsEndorse[0]['endorsement_status'] == 3){ 
						
						$condi = "field IN('endorse_up_to','receive_up_to','initial_term')";
						$detail = $this->DatabaseModel->select_data('value','endorsement_details',$condi);
						
						$endorse_up_to 	= $detail[0]['value'];		
						$receive_up_to 	= $detail[1]['value'];		
						$initial_term 	= $detail[2]['value'];		

						if($status == 2){
							
							$up_to_cond = "endorsee_id = $uid AND endorsement_status = 2 AND endorsement_end_date >= MONTH($current_date)";
							
							$total_endorosee_endorsement = $this->countEndorsement($up_to_cond);
							if($total_endorosee_endorsement >= $receive_up_to){
								echo json_encode(array('status'=>0,'message'=>'You can\'t accept this endorsement request now because you have already accepted '.$receive_up_to. ' request'));die;
							}
						}
						
						$endorsee['endorsement_date'] 		= date('Y-m-d');
						$endorsee['endorsement_status'] 	= $status;
						$endorsee['endorsement_end_date'] 	= date('Y-m-d', strtotime('+'.$initial_term.' years'));
						
						
						$this->DatabaseModel->update_data('become_a_endorser',$endorsee,$cond);
						
						/* start Insert notification*/
						$insert_array = array(	'noti_status'	=>	$status,
												'noti_type'		=>	1,
												'from_user'		=>	$this->uid,
												'to_user'		=>	$endorser_id,
												'reference_id'	=>	$endorse_id,
												'created_at'	=>	date('Y-m-d H:i:s')
												);
						$this->audition_functions->insertNoti($insert_array);
						/* end Insert notification*/
						
						/* start send firebase notification*/
						$token 		= $this->audition_functions->getFirebaseToken($endorser_id);
						$fullname 	= $this->audition_functions->get_user_fullname($this->uid);
						$mess 		= $this->audition_functions->getNotiStatus($status,1);
						
						if(!empty($token)){
							// $token_array = [$token];
							$msg_array =  [
								'title'=>$fullname . ': ' . $mess,
								'body'=>'',
								'icon'=>base_url('repo/images/firebase.png'),
								'click_action'=>$this->audition_functions->getNotiLink($status,1,$endorse_id,true)
							];
							$this->audition_functions->sendNotification($token,$msg_array);
						}
						/* end send firebase notification*/
						
						if($status == 2){
							$cond = array(	'noti_status'	=>	3,
											'noti_type'		=>	1,
											'from_user'		=>	$this->uid,
											'to_user'		=>	$endorser_id
											);
							$this->DatabaseModel->access_database('notifications','delete','',$cond);
							echo json_encode(array('status'=>1,'message'=>'Congratulation ! You accepted endorsement request Successfully'));
						}else{
							echo json_encode(array('status'=>1,'message'=>'You declined endorsement request Successfully'));
						}
						
					}else{
						echo json_encode(array('status'=>0,'message'=>'You can\'t declined this request now because you have already accepted it'));
					}
				}else{
					echo json_encode(array('status'=>0,'message'=>'Something Went Wrong'));
				}
				
			}
		}
	}
	
	
	
	
	
	
	
	




/***************************** Casting  call ENDS ***********************************/
	
}
?>
