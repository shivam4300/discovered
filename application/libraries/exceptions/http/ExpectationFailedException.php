<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class ExpectationFailedException extends ClientException {

	const MESSAGE = "Expectation Failed";
	const HTTP_CODE = 417;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
