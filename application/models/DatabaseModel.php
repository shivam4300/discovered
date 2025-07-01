<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DatabaseModel extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

		#common function of DML commands
		function access_database($tablename, $mode, $data_array, $where_array="", $join_array=""){
		if($mode == 'select')
		{
			$this->db->select('*');
			$this->db->from($tablename);
			if($where_array!='')
				$this->db->where($where_array);

			if($join_array != '' && $join_array[0] == 'limit'){
				$this->db->limit($join_array[1], $join_array[2]);
			}

			$rs=$this->db->get();
			return $rs->result_array();
		}
		elseif($mode=='wherein'){
		    $this->db->select("*");
		    $this->db->from($tablename);
            $this->db->where_in($join_array , $data_array);
            if($where_array!='')
				$this->db->where($where_array);

            $rs=$this->db->get();
			return $rs->result_array();
		}
		elseif($mode=='insert'){
			$this->db->insert($tablename,$data_array);
			return $this->db->insert_id();
		}
		elseif($mode=='update'){
			$this->db->where($where_array);
			$this->db->update($tablename,$data_array);
			return $this->db->affected_rows();
		}
		elseif($mode=='delete'){
			$this->db->delete($tablename,$where_array);
			return $this->db->affected_rows();
		}
		elseif($mode == 'like')
		{
			$this->db->select('*');
			$this->db->from($tablename);
			$this->db->like($where_array);
			if($join_array != ''){
			    $this->db->or_like($join_array);
			}
			if($data_array != '') {
			    $this->db->having($data_array);
			}
			$rs=$this->db->get();
			return $rs->result_array();
		}
		elseif($mode=='orderby'){
		    $this->db->select('*');
			$this->db->from($tablename);
			if($where_array!='')
				$this->db->where($where_array);

			$this->db->order_by($data_array[0], $data_array[1]);
			$rs=$this->db->get();
			return $rs->result_array();

		}
		elseif($mode=='totalvalue'){
		    $this->db->select("SUM(".$data_array[0].") AS ".$data_array[1]."");
            $this->db->from($tablename);
            if($where_array!='')
				$this->db->where($where_array);

            $rs=$this->db->get();
			return $rs->result_array();

		}
		elseif($mode=='groupby'){
		    $this->db->select('*');
			$this->db->from($tablename);
			if($where_array!='')
				$this->db->where($where_array);

			$this->db->group_by($data_array);
			$rs=$this->db->get();
			return $rs->result_array();

		}
		elseif($mode=='join_order_limit'){
		    $this->db->select('*');
			$this->db->from($tablename);
			$this->db->join($join_array[0], $join_array[1]);
			if($where_array!='')
				$this->db->where($where_array);

			$this->db->order_by($data_array[0], $data_array[1]);
			$this->db->limit($join_array[2], $join_array[3]);
			$rs=$this->db->get();
			return $rs->result_array();

		}
		elseif($mode == 'select_like')
		{
			$this->db->select('*');
			$this->db->from($tablename);
			if($where_array!='')
				$this->db->where($where_array);

            if($data_array!='')
                $this->db->like($data_array);

            if($join_array!='')
                $this->db->where_in($join_array[0] , json_decode($join_array[1]));

			$rs=$this->db->get();
			return $rs->result_array();
		}
		elseif($join_array != ''){
			$this->db->select('*');
			$this->db->from($tablename);
			$this->db->join($join_array[0], $join_array[1]);
			
			if($mode!='')
			{
				if(gettype($mode) == 'array') {
					$this->db->join($mode[0],$mode[1]);
				}
			}
				
			if($data_array!='')
				$this->db->join($data_array[0],$data_array[1]);
			
			if($where_array!='')
				$this->db->where($where_array);

			$rs=$this->db->get();
			return $rs->result_array();
		}
	}


	function select_data($field , $table , $where = '' , $limit = '' , $join_array = '',$order='',$like = '' ,$group = '',$having = ''){
		$this->db->select($field);
		$this->db->from($table);
		if($where != ""){
			$this->db->where($where);
		}

		if($join_array != ''){
			if(in_array('multiple',$join_array)){
				foreach($join_array['1'] as $joinArray){
					if(isset($joinArray[2])){
						$this->db->join($joinArray[0], $joinArray[1],$joinArray[2]);	
					}else{
						$this->db->join($joinArray[0], $joinArray[1]);
					}
					
				}
			}else{
				if(isset($join_array[2])){
					$this->db->join($join_array[0], $join_array[1], $join_array[2]);
				}else{
					$this->db->join($join_array[0], $join_array[1]);
				}
			}
		}


		if($limit != ""){
			if(is_array($limit)){
				$this->db->limit($limit['0'] , $limit['1']);
			}else{
				$this->db->limit($limit);
			}

		}
		if($having != ""){
			$this->db->having($having);
		}
		if($order != ""){
			// print_r($order);die;
			if(is_array($order) && in_array('multiple',$order)){
				foreach($order['1'] as $orderArr){
					$this->db->order_by($orderArr['0'] , $orderArr['1']);
				}
			}else{
				if(is_array($order)){
					$this->db->order_by($order['0'] , $order['1']);
				}else{
					$this->db->order_by($order);
				}
			}
			
			
			
		}
		
		if($group != ""){
			$this->db->group_by($group);
		}
		
		
		if($like != ""){
			$like_key = explode(',',$like['0']);
			$like_data = explode(',',$like['1']);
			for($i='0'; $i<count($like_key); $i++){
				if($like_data[$i] != ''){
					$this->db->like($like_key[$i] , $like_data[$i]);
				}
			} 
		}
		return $this->db->get()->result_array();
	}
	function insert_data($table , $data){
		
		$this->db->insert($table , $data);
		return $this->db->insert_id();
		die();
	}
	
	# function for delete data from database 
	function delete_data($table , $condition , $limit = ''){
		$this->db->where($condition);
		
		if($limit != ""){
			if(count($limit)>1){
				$this->db->limit($limit['0'] , $limit['1']);
			}else{
				$this->db->limit($limit);
			}
			
		}
		return $this->db->delete($table);
		die();
	}
	
	function dele($tablename,$where_array){
		return $this->db->delete($tablename,$where_array);
	}
	
	# function for update data in database 
	function update_data($table , $data , $condition , $limit = ''){
		$this->db->where($condition);
		
		if($limit != ""){
			if(is_array($limit)){
				$this->db->limit($limit['0'] , $limit['1']);
			}else{
				$this->db->limit($limit);
			}
			
		}
		
		 $this->db->update($table,$data);
		 return $this->db->affected_rows();
		die();
	}
	
	# function for update data in database with limit
	function update_data_limit($table , $data , $condition , $limit = NULL){
		//$this->check_hit($table , 'update');
		$this->db->where($condition);
		$this->db->limit($limit);
		return $this->db->update($table,$data);
		die();
	}
	
	# function for update data in database 
	function update_data_join($table , $data , $condition , $join_array = ''){
		$this->db->where($condition);
		if($join_array != ''){
			if(in_array('multiple',$join_array)){
				foreach($join_array['1'] as $joinArray){
					$this->db->join($joinArray[0], $joinArray[1]);
				}
			}else{
				$this->db->join($join_array[0], $join_array[1]);
			}
		}
		return $this->db->update($table,$data);
		die();
	}
	
	# function for call the aggregate function like as 'SUM' , 'COUNT' etc 
	function aggregate_data($table , $field_nm , $function , $where = NULL , $join_array = NULL,$limit = NULL,$group=NULL){
		$this->db->select("$function($field_nm) AS MyFun");
        $this->db->from($table);
	
		if($where != ''){
			 $this->db->where($where);
		}
		
		if($join_array != ''){
			if(in_array('multiple',$join_array)){
				foreach($join_array['1'] as $joinArray){
					if(isset($joinArray[2])){
						$this->db->join($joinArray[0], $joinArray[1],$joinArray[2]);	
					}else{
						$this->db->join($joinArray[0], $joinArray[1]);
					}
					
				}
			}else{
				if(isset($join_array[2])){
					$this->db->join($join_array[0], $join_array[1], $join_array[2]);
				}else{
					$this->db->join($join_array[0], $join_array[1]);
				}
			}
		}
		if($limit != ""){
			if(is_array($limit)){
				$this->db->limit($limit['0'] , $limit['1']);
			}else{
				$this->db->limit($limit);
			}

		}
		if($group != ""){
			$this->db->group_by($group);
		}
        $query1 = $this->db->get();
		
        if($query1->num_rows() > 0){ 
			$res = $query1->row_array();
			return $res['MyFun'];													
        }else{
			return 0;
		}  
		die();  
	}
	
	public function query($query,$return_type){
		$query = $this->db->query($query);
		if($return_type == 'num_rows'){
		  return $query->num_rows();
		}elseif($return_type == 'array'){
			 return $query->result_array();
		}
	}
}
?>
