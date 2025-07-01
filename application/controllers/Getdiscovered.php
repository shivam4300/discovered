<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Getdiscovered extends CI_Controller {

    private $uid;

    public function __construct(){

		parent::__construct();
        // $this->uid = is_login();


	}

    public function index(){
        $this->audition_functions->manage_my_web_mode_session('');
        $data['page_info'] = array('page'=>'getdiscovered','title'=>'getdiscovered');
        $data['search_query'] = [];
        $this->load->view('home/inc/header',$data);
        $this->load->view('home/getdiscovered',$data);
        $this->load->view('home/inc/footer',$data);
    }


    function upload_channel_video($page=NULL,$viewload = 'false',$post_id = NULL){  /*$page = single or = bulk*/
		$uid ='';
		if(!is_admin()){
			$this->load->library('manage_session');
			$uid = $this->uid;
		}
        if( (isset($_SESSION['account_type']) && isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard')  || is_admin() ){
			
            $data = [];
            $data['uid'] 			= $uid;
            $data['page_info'] 		= array('page'=>'upload_official_video','title'=>'Upload Official Video ');

            $data['category'] = $this->DatabaseModel->select_data('category_id,category_name','artist_category',array('parent_id'=>$_SESSION['account_type'],'status'=>1,'get_discovered_status'=>1),3);

            $website_mode = '';
            foreach($data['website_mode'] as $mode){
                $website_mode .= "<option value='{$mode['mode_id']}'>".ucfirst($mode['mode'])."</option>";
            }
            $data['website_mode'] = $website_mode;
            $this->load->view('home/inc/header',$data);
            $this->load->view('home/channel/get_discovered_upload',$data);
            $this->load->view('common/notofication_popup');
            $this->load->view('home/inc/footer',$data);
		}else{
			redirect(base_url());
		} 
	}



}

