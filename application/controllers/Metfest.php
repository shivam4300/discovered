<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Metfest extends CI_Controller {

    private $uid;

    public function __construct(){
		
		parent::__construct();
        // $this->uid = is_login();

       
	}

    public function index(){
        $data['page_info'] = array('page'=>'Metfest','title'=>'Metfest');
        $data['search_query'] = [];	
        $this->load->view('home/inc/header',$data);
        $this->load->view('home/metfest',$data);
        $this->load->view('home/inc/footer',$data);	
    }
}

