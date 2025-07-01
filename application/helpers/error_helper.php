<?php

if ( ! function_exists('is_json')) {
	function is_json() {
		$ci =& get_instance();
		$class = $ci->router->class ?? null;
		if (!class_exists($class)) {
			return false;
		}
		return ($class::$type ?? null) === "json";
	}
}

if (!function_exists('show_value_error')) {
	function show_value_error($value) {
		show_error($value, 401, "Value Error");
	}
}

if (!function_exists('show_type_error')) {
	function show_type_error($type) {
		show_error($type, 500, "Type Error");
	}
}

if (!function_exists('require_file')) {
	function require_file($key, $type=null) {
		$file = get($_FILES, $key);
		if (!$file) {
			throw new MissingFileParameterException($key);
		}
		if ($type) {
			if ($file['type'] != $type) {
				throw new InvalidParameterTypeException($key, $type, $file['type']);
			}
		}
		return $file;
	}
}

if (!function_exists('require_input')) {
	function require_input($key) {
		global $_INPUT;
		if (!$_INPUT) {
			$_INPUT = json_decode(file_get_contents("php://input"));
		}
		if (!isset($_INPUT->$key)) {
			throw new MissingInputParameterException($key);
		}
		return $_INPUT->$key;
	}
}