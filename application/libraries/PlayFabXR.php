<?php

class PlayFabXR {

	public static $url = PLAYFAB_XR_URI;
	public static $apiKey = PLAYFAB_XR_API_KEY;
	public static $apiSecret = PLAYFAB_XR_API_SECRET;
	public static $appId = PLAYFAB_XR_APP_ID;

	public function __construct() {
		$CI =& get_instance();
		$CI->load->library('exceptions/PlayFabXRException');
	}

	public static function arrayToStats($array) {
		$stats = [];
		foreach ($array as $key => $value) {
			$stats[] = [
				"StatisticName" => $key,
				"Value" => $value,
			];
		}
		return $stats;
	}

	public static function statsToArray($stats) {
		return zip(column($stats, 'StatisticName'), column($stats, 'Value'));
	}

	public static function auth($route, $params) {
		$headers = [
			"X-App-Id: " . static::$appId,
			"auth-type: " . "client"
		];
		return static::call("auth/$route", $headers, $params);
	}

	public static function client($route, $params) {
		$headers = [
			"X-App-Id: " . static::$appId,
		];
		return static::call("client/$route", $headers, $params);
	}

	public static function server($route, $params) {
		$headers = [
			"X-Api-Key: " . static::$apiKey,
			"X-Api-Secret: " . static::$apiSecret,
			"X-App-Id: " . static::$appId,
		];
		return static::call("server/$route", $headers, $params);
	}

	private static function call($route, $headers, $params) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => static::$url . "/$route",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_HTTPHEADER => $headers,
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
		if (!$response || !$response->success) {
			throw new PlayFabXRException($response);
		}
		return $response;
	}

}