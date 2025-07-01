<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/NotFoundException"]);

class ItemNotFoundException extends NotFoundException {

	public function __construct($user_id, $itemInstanceId) {
		$this->title = "Item not found in user's inventory.";
		$this->detail = "UserId: '$user_id' ItemInstanceId: '$itemInstanceId'";
		parent::__construct();
	}

}
