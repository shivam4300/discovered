<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/ServiceUnavailableException"]);

class PlayFabException extends ServiceUnavailableException {

	public function __construct($playFabError=null) {
		$this->title = "An error occured during a call to PlayFab";
		$this->detail = $playFabError;
		parent::__construct();
	}

}
