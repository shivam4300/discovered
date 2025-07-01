<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServerException"]);

class BadGatewayException extends ServerException {

	const MESSAGE = "Bad Gateway";
	const HTTP_CODE = 500;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
