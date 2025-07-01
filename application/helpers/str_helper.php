<?php

if (!function_exists('snake')) {
	/**
	 * Change string case to snake case.
	 * Ex: this_is_a_string
	 *
	 * @param string $str
	 * @return string
	 */
	function snake($str) {
		if (is_string($str)) {
			$str = str_replace('-', '_', $str);
			$snake = "";
			$isFirst = true;
			foreach (str_split($str) as $char) {
				$lower = strtolower($char);
				if ($char !== $lower) {
					if (!$isFirst) {
						$snake .= '_';
					}
				}
				$snake .= $lower;
				$isFirst = $lower == '/' ?: false;
			}
			$sanitized = '';
			while ($sanitized !== $snake) {
				$snake = $sanitized ?: $snake;
				$sanitized = str_replace('__', '_', $snake);
			}
			return $snake;
		}
		return $str;
	}
}