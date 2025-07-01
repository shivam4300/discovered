<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/HttpException"]);

abstract class SuccessException extends HttpException {

	public function __construct($message, $no) {
		parent::__construct($message, $no);
	}

}
