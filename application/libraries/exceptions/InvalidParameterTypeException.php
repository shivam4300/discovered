<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class InvalidParameterTypeException extends BadRequestException {

	public function __construct($name, $type, $expected) {
		$this->title = "A parameter with an invalid type was submitted.";
		$this->detail = "Invalid parameter: name: '$name' type: '$type' expected: '$expected'.";
		parent::__construct();
	}

}
