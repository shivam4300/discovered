<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class NotFoundException extends ClientException {

	const MESSAGE = "Not found!";
	const HTTP_CODE = 404;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
