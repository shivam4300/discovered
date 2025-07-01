<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class SignupAlreadyCompletedException extends BadRequestException {

	public function __construct() {
		$this->title = "The authenticated user account has already signup";
		parent::__construct();
	}

}
