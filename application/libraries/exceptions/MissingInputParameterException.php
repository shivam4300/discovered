<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class MissingInputParameterException extends BadRequestException {

	public function __construct($name=null) {
		$this->title = "A required INPUT parameter was not submitted or was misspelled.";
		$this->detail = "Missing parameter : '$name'";
		parent::__construct();
	}

}
