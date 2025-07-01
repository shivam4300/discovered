<?php
if (!function_exists('equal')) {
	function equal($var, $value, $key = null, $strict = true) {
		$filtered = [];
		foreach ($var as $k => $v) {
			if ($key) {
				$comparedValue = get($v, $key);
			} else {
				$comparedValue = $v;
			}
			if ($strict) {
				if ($comparedValue === $value) {
					$filtered[$k] = $v;
				}
			} else {
				if ($comparedValue == $value) {
					$filtered[$k] = $v;
				}
			}
		}
		return $filtered;
	}
}

if (!function_exists('not_equal')) {
	function not_equal($var, $value, $key = null, $strict = true) {
		$filtered = [];
		foreach ($var as $k => $v) {
			if ($key) {
				$comparedValue = element($v, $key);
			} else {
				$comparedValue = $v;
			}
			if ($strict) {
				if ($comparedValue !== $value) {
					$filtered[$k] = $v;
				}
			} else {
				if ($comparedValue != $value) {
					$filtered[$k] = $v;
				}
			}
		}
		return $filtered;
	}	
}

if (!function_exists('in')) {
	function in($var, $values, $key = null, $default = null) {
		$filtered = [];
		foreach ($var as $k => $v) {
			if ($key) {
				if (in_array(get($v, $key, $default), $values, true)) {
					$filtered[$k] = $v;
				}
				continue;
			}
			if (in_array($v, $values, true)) {
				$filtered[$k] = $v;
			}
		}
		return $filtered;
	}
}

if (!function_exists('not_in')) {
	function not_in($var, $values, $key = null, $strict = true) {
		$filtered = [];
		foreach ($var as $k => $v) {
			if ($key) {
				if (!in_array(get($v, $key), $values, $strict)) {
					$filtered[$k] = $v;
				}
				continue;
			}
			if (!in_array($v, $values, $strict)) {
				$filtered[$k] = $v;
			}
		}
		return $filtered;
	}
}

if (!function_exists('keys')) {
	function keys($var) {
		if (is_object($var)) {
			$var = (array) $var;
		}
		return array_keys($var);
	}
}

if (!function_exists('values')) {
	function values($var) {
		if (is_object($var)) {
			$var = (array) $var;
		}
		return array_values($var);
	}
}

if (!function_exists('pluck')) {
	function pluck($var, $keys, $defaults = null) {
		$plucked = [];
		foreach (defaults($keys, $defaults) as $key => $default) {
			$plucked[$key] = get($var, $key, $default);
		}
		return $plucked;
	}
}

if (!function_exists('get')) {
	function get($var, $key, $default = null) {
		if (is_object($var)) {
			if (isset($var->$key)) {
				return $var->$key;
			}
		} elseif (is_array($var) && isset($var[$key])) {
			return $var[$key];
		}
		return $default;
	}
}

if (!function_exists('defaults')) {
	function defaults($keys, $defaults) {
		if ($defaults === null) {
			$defaults = array_fill(0, len($keys), null);
		}
		return array_combine($keys, $defaults);
	}
}

if (!function_exists('len')) {
	function len($var) {
		if (is_array($var)) {
			return sizeof($var);
		}
		if (is_string($var)) {
			return strlen($var);
		}
		return 0;
	}
}

if (!function_exists('slice')) {
	function slice($iterable, $offset, $length = null, $preserve_keys = false) {
		if (is_string($iterable)) {
			// substr actually checks if $length is null or if it is a variable that is set to null.
			if ($length === null) {
				return substr($iterable, $offset);
			} else {
				return substr($iterable, $offset, $length);
			}
		}
		if (is_object($iterable)) {
			$iterable = (array) $iterable;
		}
		if (is_array($iterable)) {
			return array_slice($iterable, $offset, $length, $preserve_keys);
		}
		return null;
	}
}

if (!function_exists('starts_with')) {
	function starts_with($var, $prefix) {
		if (len($prefix) === 0) {
			return true;
		}
		if (is_string($var) && is_string($prefix)) {
			return strpos($var, $prefix) === 0;
		}
		return slice($var, 0, len($prefix)) === $prefix;
	}
}
if (!function_exists('ends_with')) {
	function ends_with($var, $suffix) {
		if (len($suffix) === 0) {
			return true;
		}
		if (is_string($var) && is_string($suffix)) {
			return strpos($var, $suffix, strlen($var) - strlen($suffix)) === strlen($var) - strlen($suffix);
		}
		return slice($var, -len($suffix)) === $suffix;
	}
}

if (!function_exists('first')) {
	function first($iter) {
		if ($iter !== null) {
			foreach ($iter as $value) {
				return $value;
			}
		}
		return null;
	}
}

if (!function_exists('zip')) {
	function zip($keys, $values) {
		return array_combine($keys, $values);
	}
}

if (!function_exists('zipluck')) {
	function zipluck($iter, $map) {
		return zip(values($map), pluck($iter, keys($map)));
	}
}

if (!function_exists('ziplucks')) {
	function ziplucks($rows, $map) {
		$mapped = [];
		foreach ($rows as $row) {
			$mapped[] = zip(values($map), pluck($row, keys($map)));
		}
		return $mapped;
	}
}

if (!function_exists('column')) {
	function column($var, $key, $default = null) {
		$column = [];
		foreach ($var as $i => $row) {
			$column[$i] = get($row, $key, $default);
		}
		return $column;
	}
}
if (!function_exists('key_by')) {
	function key_by($iterable, $key) {
		return array_column($iterable, null, $key);
	}
}