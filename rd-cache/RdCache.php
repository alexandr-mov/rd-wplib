<?php

class RdCache {
	private static $cache = array();

	private function __construct() {}
	private function __clone() {}

	public static function set($key, $value) {
		self::$cache[$key] = $value;
	}

	public static function get($key) {
		if ( isset(self::$cache[$key]) ) {
			return self::$cache[$key];
		}
		return null;
	}
}
