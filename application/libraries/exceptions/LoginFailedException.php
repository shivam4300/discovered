<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/UnauthorizedException"]);

class LoginFailedException extends UnauthorizedException {

	public function __construct($login, $password, $obfuscate = true) {
		$this->title = "The system could not authenticate you with the credentials provided.";
		if ($obfuscate) {
			$password = '**********';
		}
		$this->detail = "User: '$login', Password: '$password'.";
		parent::__construct();
	}

}
