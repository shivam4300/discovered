<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class CodeAlreadySubmittedException extends BadRequestException {

	public function __construct($code) {
		$this->title = "Code already submitted.";
		$this->detail = "Code: '$code'.";
		parent::__construct();
	}

}
