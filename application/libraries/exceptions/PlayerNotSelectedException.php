<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/PreconditionFailedException"]);

class PlayerNotSelectedException extends PreconditionFailedException {

	public function __construct() {
		$this->title = "No player is selected. You must select a player first.";
		$this->detail = "";
		parent::__construct();
	}

}
