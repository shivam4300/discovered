<?php

class GamificationLogModel extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function save($data, $where=null) {
		$data['timestamp'] = date('Y-m-d h:i:s');
		parent::save($data, $data);
	}

}