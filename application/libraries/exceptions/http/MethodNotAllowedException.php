<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class MethodNotAllowedException extends ClientException {

	const MESSAGE = "Method Not Allowed";
	const HTTP_CODE = 405;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
