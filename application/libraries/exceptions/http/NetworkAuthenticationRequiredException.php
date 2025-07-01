<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServerException"]);

class NetworkAuthenticationRequiredException extends ServerException {

	const MESSAGE = "Network Authentication Required";
	const HTTP_CODE = 511;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
