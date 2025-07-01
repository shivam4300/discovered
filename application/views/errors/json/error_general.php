<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');
set_status_header($status_code);

echo json_encode([
	"status" => $status_code,
	"type" => "error",
	"message" => $heading . ": " . $message,
]);