<?php

$CI =& get_instance();
$CI->load->library(["exceptions/http/BadRequestException"]);

class QuestionAlreadyAnsweredException extends BadRequestException {

	public function __construct($questionId) {
		$this->title = "You already have answered this question.";
		$this->detail = "QuestionId: '$questionId'.";
		parent::__construct();
	}

}
