<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/OKException"]);

class ItemClassNotImplementedException extends OKException {

	public function __construct($itemClass, $itemId) {
		$this->title = "The item class is not implemented.";
		$this->detail = "ItemClass: '$itemClass' ItemId: '$itemId'";
		parent::__construct();
	}

}
