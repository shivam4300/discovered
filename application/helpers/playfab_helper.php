<?php

function getPlayFabId($uid=null, $link=false, $sync=false) {
	$CI =& get_instance();
	$CI->load->model('UserModel');
	$CI->load->library('creator_jwt', 'PlayFabXR');
	$uid = $uid ?? $CI->creator_jwt->require_user_id();
	$user = $CI->UserModel->require($uid);
	if (!in_array('playfab_id', keys($user))) {
		// Automatic database structure migration
		$CI->db->simple_query('ALTER TABLE users ADD playfab_id varchar(16) NULL;');
		$CI->db->simple_query('CREATE INDEX users_playfab_id_IDX USING BTREE ON users (`playfab_id`);');
	}
	$playFabId = get($user, 'playfab_id');
	if (!$playFabId && $link) {
		$playFabId = linkPlayFabAccount($user, $sync);
	}
	return $playFabId;
}

function linkPlayFabAccount($user, $sync=false) {
	$CI =& get_instance();
	$uid = get($user, 'user_id');
	$jwt = $CI->creator_jwt->GenerateToken(['sub' => $uid,]);
	$CI->load->library('PlayFabXR');
	$response = PlayFabXR::auth("LoginWithJWT", ['JWT' => $jwt]);
	$user->playfab_id = $response->data->LoginResult->PlayFabId ?? null;
	if (!$user->playfab_id) {
		return null;
	}
	// Save playFabId in DB.
	$CI->UserModel->save($user);
	if ($sync) {
		syncPlayFabAccount($user);
	}
	return $user->playfab_id;
}

function syncPlayFabAccount($user) {
	syncPlayFabPlayerDisplayName($user);
	syncPlayFabPlayerProfilePicture($user);
	syncPlayFabPlayerStats($user);
}

function syncPlayFabPlayerDisplayName($user) {
	if (!$user->playfab_id || !$user->user_id) {
		return;
	}
	$CI =& get_instance();
	$CI->load->library('PlayFabXR');
	PlayFabXR::server('SetPlayerDisplayName', [
		'PlayFabId' => $user->playfab_id,
		'DisplayName' => getDisplayName($user),
	]);
}

function getDisplayName($user) {
	$displayName = $user->user_name;
	if (len($displayName) < 3) {
		$displayName = $user->user_uname;
	}
	if (len($displayName) < 3) {
		$displayName = $user->playfab_id;
	}
	if (len($displayName) > 25) {
		$displayName = substr($displayName, 0, 22) . "...";
	}
	return $displayName;
}

function syncPlayFabPlayerProfilePicture($user) {
	if (!$user->playfab_id || !$user->user_id) {
		return;
	}
	$CI =& get_instance();
	$CI->load->model('UserContentModel');
	$userContent = first($CI->UserContentModel->get(['uc_userid' => $user->user_id]));
	$imgFileName = get($userContent, 'uc_pic');
	if ($imgFileName) {
		$CI->load->library('PlayFab');
		$ext = end(explode('.', $imgFileName));
		$imageName = slice($imgFileName, 0, -len(".$ext"));
		PlayFab::server("UpdateAvatarUrl", [
			'ImageUrl' => AMAZON_URL . sprintf("aud_%s/images/%s_thumb.%s", $user->user_id, $imageName, $ext),
			'PlayFabId' => $user->playfab_id,
		]);
	} else {
		$CI->load->library('PlayFabXR');
		PlayFabXR::server('WritePlayerEvent', [
			'PlayFabId' => $user->playfab_id,
			'EventName' => "player_skipped_profilepicture"
		]);
	}
}

function syncPlayFabPlayerStats($user) {
	PlayFabXR::server("SetPlayerStatistics", [
		"PlayFabId" => $user->playfab_id,
		"Statistics" => json_encode(PlayFabXR::arrayToStats([
			"user_type" => $user->user_level < 4 ? 1 : 0  // 1 for levels 1,2 and 3... 0 for level 4.
		])),
	]);
}

function playfabUpdateWeeklyChallengeMissionFeaturedVideo($user_id, $post_id) {
	$playfabId = getPlayFabId($user_id, true);

	$CI =& get_instance();
	$CI->load->model('ChannelPostVideoModel');
	$CI->load->library('PlayFabXR');

	$videoKey = first($CI->ChannelPostVideoModel->get(["post_id" => $post_id]))->post_key;
	try {
		PlayFabXR::server('ExecuteFunction', [
			"PlayFabId" => $playfabId,
			"FunctionName" => "WeeklyChallengesUpdate",
			"Arguments" => '{"videoId":"'. $videoKey .'"}',
		]);

		return true;
	} catch (Exception $e) {
		return false;
	}
}