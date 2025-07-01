<?php

class PlayFab {

	public static $url = PLAYFAB_URI;
	public static $secret = PLAYFAB_SECRET;
	public static $titleId = PLAYFAB_TITLE_ID;

	public function __construct() {
		$CI =& get_instance();
		$CI->load->library('exceptions/PlayFabException');
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
		return array_combine(column($stats, 'StatisticName'), column($stats, 'Value'));
	}

	public static function client($route, $auth, $params) {
		$headers = [
			"X-Authorization: " . $auth,
		];
		return static::call("Client/$route", $headers, $params);
	}

	public static function server($route, $params) {
		$headers = [
			"X-SecretKey: " . static::$secret,
			"Content-Type: application/json",
		];
		return static::call("Server/$route", $headers, $params);
	}

	private static function call($route, $headers, $params) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => sprintf(static::$url . "/$route", static::$titleId),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($params),
			CURLOPT_HTTPHEADER => $headers,
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
		if (!$response || isset($response->error)) {
			throw new PlayFabException($response);
		}
		return $response;
	}

}