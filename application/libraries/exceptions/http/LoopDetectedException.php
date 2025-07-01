<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServerException"]);

class LoopDetectedException extends ServerException {

	const MESSAGE = "Loop Detected";
	const HTTP_CODE = 508;

	public function __construct() {
		$this->status = isset($this->status) ? $this->status : self::HTTP_CODE;
		$this->title = isset($this->title) ? $this->title : self::MESSAGE;
		parent::__construct(self::MESSAGE, self::HTTP_CODE);
	}

}
