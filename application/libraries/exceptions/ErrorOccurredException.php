<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/OKException"]);

class ErrorOccurredException extends OKException {

	public function __construct($detail=null) {
		$this->title = "An error occurred. Please try again later.";
		$this->detail = $detail;
		parent::__construct();
	}

}
