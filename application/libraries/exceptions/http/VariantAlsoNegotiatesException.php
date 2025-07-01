<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServerException"]);

class VariantAlsoNegotiatesException extends ServerException {

	const MESSAGE = "Variant Also Negotiates";
	const HTTP_CODE = 506;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
