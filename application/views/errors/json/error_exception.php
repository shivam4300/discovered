<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');
$status_code = 500;

if ($exception instanceof AppException) {
	$status_code = $exception->getCode();
}

$response = [
	"status" => $status_code,
	"message" => $exception->getMessage(),
	"type" => get_class($exception),
];

if ($exception instanceof AppException) {
	$response['title'] = $exception->getTitle();
	$response['detail'] = $exception->getDetail();
	$response['instance'] = $exception->getInstance();
}

set_status_header($status_code);

if (in_array(ENVIRONMENT, ["local", "development"])) {
	$response['file'] = $exception->getFile();
	$response['line'] = $exception->getLine();
	$response['trace'] = $exception->getTrace();
}

echo json_encode($response);