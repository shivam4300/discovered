<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class CodeInvalidOrExpiredException extends BadRequestException {

	public function __construct($code) {
		$this->title = "The code provided is either invalid or expired.";
		$this->detail = "Code: '$code'.";
		parent::__construct();
	}

}
