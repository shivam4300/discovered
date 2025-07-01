<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServerException"]);

class GatewayTimeoutException extends ServerException {

	const MESSAGE = "Gateway Timeout";
	const HTTP_CODE = 504;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
