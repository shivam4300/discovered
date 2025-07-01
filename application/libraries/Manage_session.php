<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_session { 
	public $CI;
	public function __construct(){
		
		$this->CI = get_instance();
		
		if(isset($_SESSION['user_login_id'])){
			
			$account_type = isset($_SESSION['account_type']) ? $_SESSION['account_type']: '0';
			
			if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard'){
				
				if($account_type == '0' || empty($_SESSION['user_uname'])){ 	
					redirect(base_url('account_type')); 
				}
				elseif( $_SESSION['primary_type'] == NULL || $_SESSION['primary_type'] == '0' ){
					if($account_type != 4){
						redirect(base_url('primary_type/'.$account_type));
					}
				}
				
				$user_status = isset($_SESSION['user_status']) ? $_SESSION['user_status']: '';
				
				if( $user_status == 4 ){	
					redirect(base_url('home/messages/icon_inactive'));
				}else
				if( $user_status == 2 ){	
					redirect(base_url('home/messages/inactive'));
				}else
				if( $user_status == 3 ){
					redirect(base_url('home/messages/blocked'));
				}
				
				if(!isset($_SESSION['users_content'])){
					$uinfo = $this->CI->DatabaseModel->select_data('uc_type','users_content', array('uc_userid'=>$_SESSION['user_login_id']),1);
				
					if(isset($uinfo[0]['uc_type']) && !empty($uinfo[0]['uc_type']) || $account_type == 4 ){
						$_SESSION['users_content'] = 1;
					}else{
						redirect(base_url('artist_info'));
					}
				}
			}
			
		}else{
			redirect(base_url());
		}

		
	}
	

	
	
}
?>