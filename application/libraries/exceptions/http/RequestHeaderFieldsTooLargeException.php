<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class RequestHeaderFieldsTooLargeException extends ClientException {

	const MESSAGE = "Request Header Fields Too Large";
	const HTTP_CODE = 431;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
