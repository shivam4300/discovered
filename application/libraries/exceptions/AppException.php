<?php

class AppException extends \Exception {

	static $path = "Exceptions";
	public $status = null;
	public $title = null;
	public $detail = null;

	public function __construct($message=null, $no=null) {
		parent::__construct($message, $no);
	}

	public function getStatus() {
		return $this->status;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getDetail() {
		return $this->detail;
	}

	public function getType() {
		return static::class;
	}

	public function getInstance() {
		return $this->getType() . "/". uniqid();
	}

}
