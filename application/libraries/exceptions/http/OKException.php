<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/SuccessException"]);


class OKException extends SuccessException {

	const MESSAGE = "OK";
	const HTTP_CODE = 200;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
