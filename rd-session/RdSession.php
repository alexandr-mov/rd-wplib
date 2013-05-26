<?php

class RdSession {
	private static $data = array();

	private function __construct(){}
	private function __clone() {}

	public static function init() {
    if (!session_id()) {
        session_start();
    }

    $_SESSION = array_merge($_SESSION, self::$data);
    self::$data = &$_SESSION;
  }

  public static function set($name, $value) {
  	if ( !is_string($name) && !is_numeric($name) ) {
  		return;
  	}
  	self::$data[$name] = $value;
  }

  public static function get($name) {
  	if ( !isset(self::$data[$name]) ) {
  		return null;
  	}
  	return self::$data[$name];
  }

  public static function delete($name) {
    if ( !isset(self::$data[$name]) ) {
      return;
    }
    unset(self::$data[$name]);
  }
}
