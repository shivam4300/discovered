<?php

class Channel extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('UserContentModel');
		$this->load->model('ChannelPostVideoModel');
		$this->load->library([
			'exceptions/MissingUriParameterException',
		]);
	}

	public function redirect($playfab_id) {
		$user = first($this->UserModel->get(["playfab_id" => $playfab_id]));
		$channel = get($user, 'user_uname');	
		redirect("/channel?user=$channel", 'location', 302);
		$this->show_my_response($user);
	}

	public function getFeaturedVideosIdFromPlayFabId($playfab_id) {
		if (!$playfab_id || $playfab_id == "null" || $playfab_id == "undefined") {
			throw new MissingUriParameterException("playfab_id");
		}

		$user = first($this->UserModel->get(["playfab_id" => $playfab_id]));
		$video = first($this->ChannelPostVideoModel->get(["user_id" => $user->user_id, "featured_by_user" => 1, "active_status" => 1], ['created_at' => 'DESC']));

		if (!$video) {
			$video = first($this->ChannelPostVideoModel->get(["user_id" => $user->user_id, "featured_by_admin" => 1, "active_status" => 1], ['created_at' => 'DESC']));
		}

		if (!$video) {
			$video = first($this->ChannelPostVideoModel->get(["user_id" => $user->user_id, "active_status" => 1], ['created_at' => 'DESC']));
		}

		$this->show_my_response($video->post_key ?? null);
	}

}