<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class MissingUriParameterException extends BadRequestException {

	public function __construct($name = '') {
		$this->title = "A required URI parameter was not submitted or was misspelled.";
		$this->detail = "Missing parameter : '$name'";
		parent::__construct();
	}

}
