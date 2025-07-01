<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class GameNotAnsweredException extends BadRequestException {

	public function __construct($gameId) {
		$this->title = "You have not answered this game.";
		$this->detail = "GameId: '$gameId'.";
		parent::__construct();
	}

}
