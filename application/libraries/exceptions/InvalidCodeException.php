<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/OKException"]);

class InvalidCodeException extends OKException {

	public function __construct($code) {
		$this->title = "Invalid code.";
		$this->detail = "Code: '$code'.";
		parent::__construct();
	}

}
