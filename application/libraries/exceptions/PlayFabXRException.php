<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServiceUnavailableException"]);

class PlayFabXRException extends ServiceUnavailableException {

	public function __construct($playFabXRError=null) {
		$this->title = "An error occured during a call to PlayFabXR";
		$this->detail = $playFabXRError;
		parent::__construct();
	}

}
