<?php

class Fan extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('FanModel');
		$this->load->model('UserModel');
		$this->load->model('PlayerModel');
		$this->load->library([
			'exceptions/ObjectNotFoundException',
			'exceptions/MissingInputParameterException',
			'exceptions/UnauthenticatedException', 
			'ServerAuth',
			'Gamification',
		]);
	}

	public function createByUser() {
		ServerAuth::requireAuthentication();
		$user_id = require_input('user_id');
		$following_id = require_input('following_id');
		$user = $this->UserModel->require($user_id);
		$following = $this->UserModel->require($following_id);
		$this->Gamification->become_fan($user, $following);
		$this->show_my_response();	
	}

	public function createByPlayer() {
		ServerAuth::requireAuthentication();
		$playfab_id = require_input('playfab_id');
		$following_id = require_input('following_id');
		$player = $this->PlayerModel->require($playfab_id);
		$following = $this->PlayerModel->require($following_id);
		Gamification::become_fan($player, $following);
		$this->show_my_response();	
	}

}