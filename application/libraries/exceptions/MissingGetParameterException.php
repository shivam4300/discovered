<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class MissingGetParameterException extends BadRequestException {

	public function __construct($name, $route) {
		$this->title = "A required GET parameter was not submitted or was misspelled.";
		$this->detail = "Missing parameter : '$name' in route : '$route'";
		parent::__construct();
	}

}
