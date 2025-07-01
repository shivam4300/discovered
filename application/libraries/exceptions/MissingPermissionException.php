<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ForbiddenException"]);

class MissingPermissionException extends ForbiddenException {

	public function __construct($role_slug, $permission_slug) {
		$this->title = "Missing either permission or elevation to execute this request";
		$this->detail = "Current Role: '$role_slug' Missing permission: '$permission_slug'";
		parent::__construct();
	}

}
