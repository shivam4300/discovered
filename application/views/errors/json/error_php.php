<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');
set_status_header($status_code = 500);

$response = [
	"status" => $status_code,
	"type" => "error",
	"message" => "Unknown PHP error",
];

echo json_encode($response);