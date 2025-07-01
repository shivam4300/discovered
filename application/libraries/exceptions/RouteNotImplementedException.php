<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/NotImplementedException"]);

class RouteNotImplementedException extends NotImplementedException {

	public function __construct($class, $method) {
		$this->title = "The route exists but is not implemented.";
		$this->detail = "Method '$method' is not implemented in class '$class'.";
		parent::__construct();
	}

}
