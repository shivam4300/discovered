<?php

class Gamification {

	public static function become_fan($user, $following) {
		$fan = [
			'user_id' => $user->user_id,
			'following_id' => $following->user_id,
		];
		$CI =& get_instance();
		$CI->load->model('FanModel');
		if ($CI->FanModel->get($fan)) {  
			return; // Already a fan
		}
		$CI->FanModel->save($fan, $fan);
		static::player_became_fan($user, $following);
	}

	public static function player_published_video($user, $post_id) {
		$CI =& get_instance();
		$CI->load->library('PlayFabXR');
		$CI->load->model('ChannelPostVideoModel');
		$post = first($CI->ChannelPostVideoModel->get(["post_id" => $post_id]));
		$playerId = getPlayFabId($user->user_id, true, true);
		PlayFabXR::server('WritePlayerEvent', [
			'PlayFabId' => $playerId,
			'EventName' => "player_published_video",
			'Body' => json_encode([
				'videoId' => $post->post_key,
			]),
		]);
	}

	public static function player_added_castcrew($user, $post_id) {
		$log = [
			'subject' => $user->playfab_id,
			'verb' => 'player_added_castcrew',
			'complement' => $post_id,
		];
		$CI =& get_instance();
		$CI->load->model('GamificationLogModel');
		$CI->load->model('ChannelPostVideoModel');
		$CI->load->model('UserModel');
		if ($CI->GamificationLogModel->get($log)) {
			return;  // Already happened and can only happen once.
		}
		$CI->GamificationLogModel->save($log);
		$CI->load->library('PlayFabXR');
		$CI->load->model('ChannelPostVideoModel');
		$post = first($CI->ChannelPostVideoModel->get(["post_id" => $post_id]));
		$playerId = getPlayFabId($user->user_id, true, true);

		PlayFabXR::server('WritePlayerEvent', [
			'PlayFabId' => $playerId,
			'EventName' => "player_added_castcrew",
			'Body' => json_encode([
				'videoId' => $post->post_key,
			]),
		]);
	}

	public static function isPlayerFeatured($playfab_id) {
		$CI =& get_instance();
		$CI->load->library('PlayFabXR');
		$globalVariables = PlayFabXR::server('GetGlobalVariable', [
			"dataKey" => "PreviousWeekLeaderboard",
		])->data->GlobalVariable->dataVal;
		return in_array($playfab_id, column($globalVariables, 'PlayFabId'));
	}

	public static function player_shared_video($user, $post_id) {
		if (!$user || !$post_id) return;

		$log = [
			'subject' => $user->playfab_id,
			'verb' => 'player_shared_video',
			'complement' => $post_id,
		];
		$CI =& get_instance();
		$CI->load->model('GamificationLogModel');
		$CI->load->model('ChannelPostVideoModel');
		$CI->load->model('UserModel');
		if ($CI->GamificationLogModel->get($log)) {
			return;  // Already happened and can only happen once.
		}
		$CI->GamificationLogModel->save($log);
		$CI->load->library('PlayFabXR');

		$post = first($CI->ChannelPostVideoModel->get(["post_id" => $post_id]));
	
		$channelPlayfabId = getPlayFabId($post->user_id, true, true);
		$playerId = getPlayFabId($user->user_id, true, true);

		if ($user->playfab_id == $channelPlayfabId) {
			PlayFabXR::server('WritePlayerEvent', [
				'PlayFabId' => $playerId,
				'EventName' => "player_shared_ownvideo",
				'Body' => json_encode([
					'videoId' => $post->post_key,
				]),
			]);
			return;
		}

		PlayFabXR::server('WritePlayerEvent', [
			'PlayFabId' => $playerId,
			'EventName' => "player_shared_video",
			'Body' => json_encode([
				'videoId' => $post->post_key,
				'channel_playfab_id' => $channelPlayfabId,
			]),
		]);

		if (!self::isPlayerFeatured($channelPlayfabId)) {
			PlayFabXR::server('WritePlayerEvent', [
				'PlayFabId' => $channelPlayfabId,
				'EventName' => "video_received_share",
				'Body' => json_encode([
					'videoId' => $post->post_key,
					'player_playfab_id' => $user->playfab_id,
				]),
			]);
		}

	}

	public static function player_loved_video($user, $post_id) {
		$log = [
			'subject' => $user->playfab_id,
			'verb' => 'player_loved_video',
			'complement' => $post_id,
		];
		$CI =& get_instance();
		$CI->load->model('GamificationLogModel');
		$CI->load->model('ChannelPostVideoModel');
		$CI->load->model('UserModel');
		if ($CI->GamificationLogModel->get($log)) {
			return;  // Already happened and can only happen once.
		}
		$CI->GamificationLogModel->save($log);
		$CI->load->library('PlayFabXR');

		$post = first($CI->ChannelPostVideoModel->get(["post_id" => $post_id]));
		$channelPlayfabId = getPlayFabId($post->user_id, true, true);
		$playerId = getPlayFabId($user->user_id, true, true);

		PlayFabXR::server('WritePlayerEvent', [
			'PlayFabId' => $playerId,
			'EventName' => "player_loved_video",
			'Body' => json_encode([
				'post_id' => $post_id,
				'channel_playfab_id' => $channelPlayfabId,
			]),
		]);

		if (!self::isPlayerFeatured($channelPlayfabId)) {
			PlayFabXR::server('WritePlayerEvent', [
				'PlayFabId' => $channelPlayfabId,
				'EventName' => "video_received_love",
				'Body' => json_encode([
					'post_id' => $post_id,
					'player_playfab_id' => $user->playfab_id,
				]),
			]);
		}
	}

	/**
	 * @param [type] $user The user being followed.
	 * @param [type] $following The user following another.
	 * @return void
	 */
	public static function player_became_fan($user, $following) {
		$playFabId = getPlayFabId($user->user_id, true, true);
		$log = [
			'subject' => $following->playfab_id,
			'verb' => 'player_became_fan',
			'complement' => $playFabId,
		];
		$CI =& get_instance();
		$CI->load->model('GamificationLogModel');
		if ($CI->GamificationLogModel->get($log)) {  
			return;  // Already happened and should only happen once.
		}
		$CI->GamificationLogModel->save($log);
		$CI->load->library('PlayFabXR');
		$CI->load->model('FanModel');
		PlayFabXR::server('WritePlayerEvent', [
			'PlayFabId' => $following->playfab_id,
			'EventName' => "player_became_fan",
			'Body' => json_encode([
				'channelPlayFabId' => $playFabId,
				'total_fans' => $CI->FanModel->count(['user_id' => $user->user_id]),
			]),
		]);
	}

	public static function get_player_channel_progression($channel) {
		$CI =& get_instance();
		$CI->load->model('GamificationLogModel');
	}

	public static function get_player_channels_progression($channels) {
		return array_map(function($channel) {
			return self::get_player_channel_progression($channel);
		}, $channels);
	}
}