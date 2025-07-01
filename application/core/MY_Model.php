<?php

class MY_Model extends CI_Model {

	static public $tableName = null;
	static public $idColumn = 'id';
	static public $privateFields = [];

	public function __construct() {
		parent::__construct();
		$this->load->helper('str');
		$this->load->helper('iter');
	}

	public function getTableName() {
		if (static::$tableName) {
			return static::$tableName;
		}
		$tableName = snake(static::class);
		if (ends_with($tableName, '_model')) {
			$tableName = slice($tableName, 0, len($tableName) - len('_model'));
		}
		return $tableName;
	}

	public function getIdColumn() {
		return static::$idColumn;
	}

	public function public($obj) {
		return pluck($obj, not_in(keys($obj), $this->private()));
	}

	public function private() {
		return static::$privateFields;
	}

	public function where($where) {
		switch (gettype($where)) {
			case 'string': 
			case 'integer': $where = [$this->getIdColumn() => $where]; break;
		}
		return $where;
	}

	public function get($where=null, $order=[], $limit=null, $offset=null) {
		$table = $this->getTableName();
		$where = $this->where($where);
		if ($order) {
			foreach ($order as $column => $direction) {
				$this->db->order_by($column, $direction);
			}
		}
		if ($where) {
			$query = $this->db->get_where($table, $where, $limit, $offset);
		} else {
			$query = $this->db->get($table, $limit, $offset);
		}
		return $query->result();
	}

	public function count($where=null) {
		$this->db->select($this->getIdColumn());
		$this->db->from($this->getTableName());
		$this->db->where($this->where($where));
		return $this->db->count_all_results();
	}

	public function require($id) {
		$instance = first($this->get($id));
		if (!$instance) {
			throw new ObjectNotFoundException(static::class, $this->getIdColumn(), $id);
		}
		return $instance;
	}

	public function save($data, $where=null) {
		$id = get($data, $this->getIdColumn());
		if (!$where || $id) {
			$where = $id;
		}
		if ($this->get($where, [], 1)) {
			$this->update($data, $where);
		} else {
			$this->insert($data);
		}
	}

	public function replace($data) {
		$table = $this->getTableName();
		if (!$data) {
			return;
		}
		$this->db->replace($table, $data);
	}

	public function update($data, $where=null) {
		if (!$data) {
			return;
		}
		$table = $this->getTableName();
		$where = $this->where($where);
		$this->db->update($table, $data, $where);
	}

	public function insert($data) {
		$table = $this->getTableName();
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

}