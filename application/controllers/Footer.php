<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Footer extends CI_Controller {
	
	
	public function test(){
		$data = array();
		// $this->load->view('home/inc/header',$data);
		$this->load->view('common/privacy_policy',$data);
		// $this->load->view('home/inc/footer',$data);	
	}
	
	
	public function policies(){
		$data['page_info'] = array('page'=>'policies','title'=>'policies');
		$this->load->view('home/inc/header',$data);
		$this->load->view('common/privacy_policy',$data);
		$this->load->view('home/inc/footer',$data);	
	}
	
	
	public function terms_privacy(){
		$data['page_info'] = array('page'=>'terms_privacy','title'=>'terms_privacy');
		$this->load->view('home/inc/header',$data);
		$this->load->view('common/terms_privacy',$data);
		$this->load->view('home/inc/footer',$data);	
	}
	
	public function about_us(){
		$data['page_info'] 	= array('page'=>'about_us','title'=>'about_us');
		$this->load->view('home/inc/header',$data);
		$this->load->view('common/about_us',$data);
		$this->load->view('home/inc/footer',$data);	
	}
	
	public function giveaways(){
		$data['page_info'] = array('page'=>'giveaways','title'=>'Giveaways');
		$data['giveawaysCount'] =	$this->DatabaseModel->aggregate_data('users','user_id','COUNT',array('is_giveaways'=>1));
			
		$this->load->view('home/inc/header',$data);
		$this->load->view('common/giveaways',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('home/inc/footer',$data);	
	}
	
	public function giveaway_rules(){
		$data['page_info'] = array('page'=>'giveaway_rules','title'=>'giveaway_rules');
		$this->load->view('home/inc/header',$data);
		$this->load->view('common/giveaway_rules',$data);
		$this->load->view('home/inc/footer',$data);	
	}
	
	public function upload_video_new(){
		$data = array();
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/channel/upload_video_new',$data);
		$this->load->view('home/inc/footer',$data);	
	}
	
	
	public function playlistDummy(){
		$data['page_info'] = array('page'=>'playlist','title'=>'My playlist');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/dummyplaylist.php',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('home/inc/footer',$data);	
	}
	
	public function test_dummy(){
		$data['page_info'] 	= array('page'=>'about_us','title'=>'about_us');
		$this->load->view('home/inc/header',$data);
		$this->load->view('common/test_dummy_page',$data);
		$this->load->view('home/inc/footer',$data);	
	}
	
}