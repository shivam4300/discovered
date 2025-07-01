<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/InternalServerErrorException"]);

class MissingConfigurationException extends InternalServerErrorException {

	public function __construct($name, $class) {
		$this->title = "A required configuration parameter is not set.";
		$this->detail = "Missing parameter : '$name' in class : '$class'";
		parent::__construct();
	}

}
