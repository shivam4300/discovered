<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/NotFoundException"]);

class TeamNotFoundException extends NotFoundException {

	public function __construct($teamId) {
		$this->title = "Team not found in database.";
		$this->detail = "TeamId: '$teamId'";
		parent::__construct();
	}

}
