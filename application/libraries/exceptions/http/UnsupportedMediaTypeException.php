<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class UnsupportedMediaTypeException extends ClientException {

	const MESSAGE = "Unsupported Media Type";
	const HTTP_CODE = 415;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
