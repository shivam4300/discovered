<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class URITooLongException extends ClientException {

	const MESSAGE = "URI Too Long";
	const HTTP_CODE = 414;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
