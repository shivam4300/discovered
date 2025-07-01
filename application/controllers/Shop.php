<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct()
	{
		parent::__construct();

	}
	public function index(){
		$data['page_info'] 	= 	array('page'=>'shop' ,'title'=>'Shop');
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/shop/amazon_shop',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}

    public function ladybanneker(){
		$data['page_info'] 	= 	array('page'=>'shop' ,'title'=>'Shop');
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/shop/ladybanneker',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}

	public function rengabricks(){
		$data['page_info'] 	= 	array('page'=>'shop' ,'title'=>'Renga Bricks');
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/shop/rengabricks',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}
	public function gymwrap(){
		$data['page_info'] 	= 	array('page'=>'shop' ,'title'=>'Gymwrap');
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/shop/gymwrap',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}
	public function groovz(){
		$data['page_info'] 	= 	array('page'=>'shop' ,'title'=>'Groovz');
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/shop/groovz',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}

}