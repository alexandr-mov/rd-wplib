<?php

class RdMinify {
	protected $hFiles = array();
	protected $fFiles = array();
	protected $cacheDir = '';
	protected $hFilename = '';
	protected $fFilename = '';
	protected $funcMinify = '';
	protected $formatUri = '';
	public $isMinify = true;

	public function __construct() {
		add_action('wp_head', array(&$this, 'theHeadUri'));
		add_action('wp_footer', array(&$this, 'theFooterUri'));
	}

	public function push($file, $isHead = true) {
		if ( $isHead ) {
			$this->hFiles[] = $file;
		} else {
			$this->fFiles[] = $file;
		}
	}

	public function theHeadUri() {
		printf($this->formatUri, $this->getHeadUri());
	}
	public function theFooterUri() {
		printf($this->formatUri, $this->getFooterUri());
	}

	public function getHeadUri() {
		return $this->getUri($this->hFiles, $this->hFilename);
	}

	public function getFooterUri() {
		return $this->getUri($this->fFiles, $this->fFilename);
	}

	protected function getUri($files, $filename) {
		$md5Hash = self::md5Files($files);
		$content = '';
		$filepath = $this->cacheDir . $filename;

		if ( !file_exists($this->cacheDir . $md5Hash) ) {
			self::clear($this->cacheDir);

			foreach ( $files as $file ) {
				$content .= file_get_contents($file) . "\n\n";
			}

			if ( $this->isMinify && is_callable($this->funcMinify) ) {
				$content = call_user_func($this->funcMinify, $content);
			}

			file_put_contents($this->cacheDir . $md5Hash, '');
			file_put_contents($filepath, $content);
		}

		return rdPathToUrl($filepath) . '?' . $md5Hash;
	}

	private static function md5Files($listFiles) {
		$ret = '';

		foreach( $listFiles as $filename ) {
	    $ret .= date("YmdHis", filemtime($filename)).$filename;
	  }

	  return md5($ret);
	}

	private static function clear($dir) {
	  // $oldDate = time() - 3600;
	  // $dirContent = scandir($dir);

	  // foreach ( $dirContent as $filename ) {
	  //   if ( (32 != strlen($filename) || !filemtime($dir.$filename) ) {
	  //   	continue;
	  //   }
	    
	  //   if ( filemtime($dir.$filename) < $olddate ) {
	  //   	unlink($dir.$filename);
	  //   }
	  // }
	}
}