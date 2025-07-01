<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ClientException"]);

class ForbiddenException extends ClientException {

	const MESSAGE = "Forbidden";
	const HTTP_CODE = 403;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : static::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : static::MESSAGE;
		parent::__construct(static::MESSAGE, static::HTTP_CODE);
	}

}
