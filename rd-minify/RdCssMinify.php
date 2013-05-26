<?php

class RdCssMinify extends RdMinify {
	public function __construct() {
		parent::__construct();

		$this->cacheDir = RD_MINIFY_CACHE_CSS_DIR;
		$this->hFilename = RD_MINIFY_FILENAME_CSS;
		$this->funcMinify = '';
		$this->formatUri = "<link rel='stylesheet' href='%s' type='text/css' media='all' />\n";
	}
}