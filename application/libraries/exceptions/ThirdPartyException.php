<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServiceUnavailableException"]);

class ThirdPartyException extends ServiceUnavailableException {

	public function __construct($response) {
		$this->title = "An error occurred in a transaction with a third party.";
		$this->detail = "Third party response: '$response'.";
		parent::__construct();
	}

}
