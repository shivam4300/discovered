<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/UnauthorizedException"]);

class UnauthenticatedException extends UnauthorizedException {

	public function __construct($message=null) {
		$this->title = "Missing or invalid token";
		$this->detail = $message ? "TokenDecode: $message" : null;
		parent::__construct();
	}

}
