<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class InvalidParameterValueException extends BadRequestException {

	public function __construct($name=null, $value=null, $expected=null) {
		$this->title = "A parameter with an invalid value was submitted.";
		$this->detail = "Invalid parameter: name: '$name' value: '$value' expected: '$expected'.";
		parent::__construct();
	}

}
