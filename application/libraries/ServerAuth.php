<?php

class ServerAuth {

	public function __construct() {
		$CI = get_instance();
		$CI->load->library([
			'exceptions/UnauthenticatedException', 
		]);
	}

	public static function getAuthKey() {
		$CI = get_instance();
		return $CI->input->get_request_header('api_key');
	}

	public static function isAuthenticated() {
		$key = static::getAuthKey();
		return $key && $key === AZURE_FUNCTION_API_KEY;
	}

	public static function requireAuthentication() {
		if (!static::isAuthenticated()) {
			throw new UnauthenticatedException();
		}
	}

}