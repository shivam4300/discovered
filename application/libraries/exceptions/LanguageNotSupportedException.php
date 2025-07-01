<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/NotFoundException"]);

class LanguageNotSupportedException extends NotFoundException {

	public function __construct($language) {
		$this->title = "The requested language is not supported.";
		$this->detail = "Requested language: '$language'.";
		parent::__construct();
	}

}
