<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ConflictException"]);

class ObjectAlreadyExistsException extends ConflictException {

	public function __construct($class, $key, $value) {
		$this->title = "Object already exists.";
		$this->detail = "Class: '$class' $key: '$value'";
		parent::__construct();
	}

}
