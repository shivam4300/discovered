<?php

class MY_Controller extends CI_Controller {

	public $statusCode = 1;
	public $statusType = 'Success';
	public $respMessage;
	public $output;
	public $hasModel = false;
	public static $type = 'json';

	static public $modelName = null;

	static function getModelName() {
		return (static::$modelName ?? static::class) . 'Model';
	}

	public function __construct() {
		parent::__construct();
		$this->load->helper('str');
		$this->load->helper('iter');
		$this->load->helper('error');
		$model = static::getModelName();
		if (file_exists(APPPATH."models/$model.php")) {
			$this->load->model($model);
			$this->hasModel = true;
		}
	}

	protected function show_my_response($data = null) {
		$response = [
			"status" => $this->statusCode,
			"type" => $this->statusType,
			"message" => $this->respMessage,
		];
		if ($data) {
			$response["data"] = $data;
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response));
	}

	public function index() {		
		$model = static::getModelName();
		if (!$this->hasModel) {
			$this->show_my_response();
			return;
		}
		$table = $this->$model->getTableName();
		$fields = $this->$model->db->list_fields($table);
		$where = not_equal($this->input->get($fields, TRUE), null);
		$limit = $this->input->get('limit', TRUE);
		$order = $this->input->get('order', TRUE);
		$offset = $this->input->get('offset', TRUE);
		$rows = $this->$model->get($where, $order, $limit, $offset);
		$data = [];
		foreach ($rows as $row) {
			$data[] = $this->$model->public($row);
		}
		$this->show_my_response($data);
	}

}