<?php

class PlayFab  extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('BecomeAFanModel');
		$this->load->model('GamificationLogModel');
		$this->load->model('ChannelPostVideoModel');
		$this->load->library([
			'exceptions/ObjectNotFoundException',
			'exceptions/MissingInputParameterException',
			'exceptions/UnauthenticatedException',
			'Gamification',
		]);
	}

	public function getPlayerChannelsProgression() {
		$playFabId = require_input('playFabId');
		$channelPlayFabIds = require_input('channelPlayFabIds');

		$player = first($this->UserModel->get(['playfab_id' => $playFabId]));

		if (!$player) {
			throw new ObjectNotFoundException("Player not found");
		}
		if (!$channelPlayFabIds || !is_array($channelPlayFabIds) || count($channelPlayFabIds) == 0) {
			throw new Exception("Invalid Channel Ids");
		}

		$channelIds = $this->db->query('SELECT * FROM `users` WHERE playfab_id IN ?', [$channelPlayFabIds]);
		if ($channelIds->num_rows() == 0) {
			throw new ObjectNotFoundException("Channels not found");
		}
		$channelIds = $channelIds->result();
		
		$fan = $this->db->query('SELECT * FROM `become_a_fan` WHERE following_id = ? AND user_id IN ?', [$player->user_id, array_column($channelIds, 'user_id')])->result();
		
		$channels = [];
		foreach ($channelIds as $channel) {
			// *** Uncomment if you ever need to know if the player has loved the featured video of the channel already
			// $hasPlayerLovedVideo = $this->db->query('SELECT * FROM `channel_video_vote` WHERE user_id = ? AND post_id = ?', [$player->user_id, $featuredVideo->post_id])->row();
			$featuredVideo = first($this->ChannelPostVideoModel->get(["user_id" => $channel->user_id, "featured_by_user" => 1, "active_status" => 1], ['created_at' => 'DESC']));
			if (!$featuredVideo) {
				$featuredVideo = first($this->ChannelPostVideoModel->get(["user_id" => $channel->user_id, "featured_by_admin" => 1, "active_status" => 1], ['created_at' => 'DESC']));
			}
			if (!$featuredVideo) {
				$featuredVideo = first($this->ChannelPostVideoModel->get(["user_id" => $channel->user_id, "active_status" => 1], ['created_at' => 'DESC']));
			}

			$shared = $this->db->query('SELECT * FROM `gamification_log` WHERE `subject` = ? AND `verb` = "player_shared_video" AND `complement` = ?', [$playFabId, $featuredVideo->post_id])->result();

			$channels[$channel->playfab_id] = [
				'channel_id' => $channel->user_id,
				'is_fan' => in_array($channel->user_id, array_column($fan, 'user_id')) ? true : false,
				'has_shared_featured_video' => $shared ? true : false,
				// *** Uncomment if you ever need to know if the player has loved the featured video of the channel already
				'featured_video' => $featuredVideo ? $featuredVideo->post_key : null,
				// 'debug' => $this->db->last_query(),
				// 'loved_video' => $hasPlayerLovedVideo ? true : false,
			];
		}
		
		$this->show_my_response($channels);
	}
}