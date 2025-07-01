<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class CodeExpiredException extends BadRequestException {

	public function __construct($code) {
		$this->title = "The code provided is expired.";
		$this->detail = "Code: '$code'.";
		parent::__construct();
	}

}
