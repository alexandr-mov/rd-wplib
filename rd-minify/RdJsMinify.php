<?php

class RdJsMinify extends RdMinify {
	public function __construct() {
		parent::__construct();

		$this->cacheDir = RD_MINIFY_CACHE_JS_DIR;
		$this->hFilename = RD_MINIFY_FILENAME_JS_HEADER;
		$this->fFilename = RD_MINIFY_FILENAME_JS_FOOTER;
		$this->funcMinify = 'JSMin::minify';
		$this->formatUri = "<script src='%s' type='text/javascript'></script>\n";
		$this->isMinify = true;
	}
}