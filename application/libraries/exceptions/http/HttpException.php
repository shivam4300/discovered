<?php

$CI =& get_instance();
$CI->load->library(["exceptions/AppException"]);

class HttpException extends AppException {

	public function __construct($message=null, $no=null) {
		parent::__construct($message, $no);
	}

}
