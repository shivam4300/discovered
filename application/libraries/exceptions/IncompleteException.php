<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/OKException"]);

class IncompleteException extends OKException {

	public function __construct($item_instance_id) {
		$this->title = "Incomplete.";
		$this->detail = "ItemInstanceId: '$item_instance_id'.";
		parent::__construct();
	}

}
