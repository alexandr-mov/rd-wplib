<?php

define('RD_MINIFY_NAME', 'rd-minify');
define('RD_MINIFY_DIR', dirname(__FILE__) . '/');
define('RD_MINIFY_CACHE_DIR', RD_CACHE_DIR . RD_MINIFY_NAME . '/');
define('RD_MINIFY_CACHE_URL', RD_CACHE_URL . '/' . RD_MINIFY_NAME);
define('RD_MINIFY_CACHE_JS_DIR', RD_MINIFY_CACHE_DIR . 'js/');
define('RD_MINIFY_CACHE_CSS_DIR', RD_MINIFY_CACHE_DIR . 'css/');
define('RD_MINIFY_FILENAME_JS_HEADER', 'script-head.js');
define('RD_MINIFY_FILENAME_JS_FOOTER', 'scripts-footer.js');
define('RD_MINIFY_FILENAME_CSS', 'style.css');

require_once('external/jsmin.php');
require_once('RdMinify.php');
require_once('RdJsMinify.php');
require_once('RdCssMinify.php');
