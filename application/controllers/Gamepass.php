<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gamepass extends CI_Controller {
    public function __construct(){

		parent::__construct();
        // $this->uid = is_login();


	}

    public function index(){
        $this->audition_functions->manage_my_web_mode_session('');
        $data['page_info'] = array('page'=>'gamepass','title'=>'Game Pass');
        $this->load->view('home/inc/header',$data);
        $this->load->view('home/gamepass',$data);
        $this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
    }






}

