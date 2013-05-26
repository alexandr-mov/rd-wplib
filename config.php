<?php

define('RD_HOME_URL', home_url());
define('RD_WPLIB_NAME', 'rd-wplib');
define('RD_WPLIB_DIR', ABSPATH . RD_WPLIB_NAME . '/');
define('RD_WPLIB_URL', RD_HOME_URL . '/' . RD_WPLIB_NAME);
define('RD_CACHE_NAME', 'rd-cache');
define('RD_CACHE_DIR', ABSPATH . RD_CACHE_NAME . '/');
define('RD_CACHE_URL', RD_HOME_URL . '/' . RD_CACHE_NAME);

define('RD_DEBUG', true);

require_once(RD_WPLIB_DIR . 'rd-cache/RdCache.php');
require_once(RD_WPLIB_DIR . 'rd-debug/RdDebug.php');
require_once(RD_WPLIB_DIR . 'rd-session/RdSession.php');
require_once(RD_WPLIB_DIR . 'rd-utils/rd-functions.php');
require_once(RD_WPLIB_DIR . 'rd-minify/main.php');

if ( defined('RD_DEBUG') && true === RD_DEBUG ) {
	RdDebug::init();
}

add_action('init', 'RdSession::init');

