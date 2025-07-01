<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class GameAlreadyAnsweredException extends BadRequestException {

	public function __construct($gameId) {
		$this->title = "You already have answered this game.";
		$this->detail = "GameId: '$gameId'.";
		parent::__construct();
	}

}
