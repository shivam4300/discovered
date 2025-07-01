<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support_lib{ 
	function __Construct(){
		$this->CI = get_instance();		
	}
	
	
	function get_deparment() {
		$departMent=array('0'=>'Technical Query','1'=>'Revenue Related Query','2'=>'General Query');
		return $departMent;
	}
	
}
