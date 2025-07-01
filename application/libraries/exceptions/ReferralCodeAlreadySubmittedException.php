<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class ReferralCodeAlreadySubmittedException extends BadRequestException {

	public function __construct($code) {
		$this->title = "You already have submitted a referral code.";
		$this->detail = "Code: '$code'.";
		parent::__construct();
	}

}
