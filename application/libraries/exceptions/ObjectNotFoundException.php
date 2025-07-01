<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/NotFoundException"]);

class ObjectNotFoundException extends NotFoundException {

	public function __construct($class=null, $key=null, $value=null) {
		$this->title = "Object not found.";
		$this->detail = "Class: '$class' $key: '$value'";
		parent::__construct();
	}

}
