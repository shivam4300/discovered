<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class SelfReferenceException extends BadRequestException {

	public function __construct($code) {
		$this->title = "You cannot refer yourself.";
		$this->detail = "Code: '$code'.";
		parent::__construct();
	}

}
