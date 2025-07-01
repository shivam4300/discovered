<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/HttpException"]);

class ClientException extends HttpException {

	public function __construct($message = null, $no = null) {
		parent::__construct($message, $no);
	}

}
