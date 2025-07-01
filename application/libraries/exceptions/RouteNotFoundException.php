<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/NotFoundException"]);

class RouteNotFoundException extends NotFoundException {

	public function __construct($route) {
		$this->title = "Route not found";
		$this->detail = "Cannot find: '$route'.";
		parent::__construct();
	}

}
