<?php

class RdDebug {
	private function __construct() {}
	private function __clone() {}

	private static function trace($name, $message) {
		$debugMessage = RdCache::get($name);

		if ( false === $message ) {
			$debugMessage .= '<p>false</p>';
		} else if ( null === $message ) {
			$debugMessage .= '<p>null</p>';
		} else if ( '' === $message ) {
			$debugMessage .= '<p>empty string</p>';
		} else {
			$debugMessage .= '<pre>' . print_r($message, true) . '</pre>';
		}

		$debugMessage .= '<br />';
		RdCache::set($name, $debugMessage);
	}

	public static function message($message) {
		self::trace('debugMessage', $message);
	}

	public static function notice($message) {
		self::trace('debugNotice', $message);
	}

	public static function error($message) {
		self::trace('debugError', "<p style='color:red'>{$message}</p>");
	}

	public static function checkPoint($title) {
		$time  = self::get_sec();
		$sTime = $time - RdCache::get('tStart');
		$lTime = $time - RdCache::get('tLast');
		$mem   = self::get_mem();
		$sMem  = $mem - RdCache::get('mStart');
		$lMem  = $mem - RdCache::get('mLast');

		RdCache::set('tLast', $time);
		RdCache::set('mLast', $mem);

		$msg  = $title . "\n\n";
		$msg .= sprintf("sTime: %s lTime: %s\n", substr($sTime, 0, 5), $lTime);
		$msg .= sprintf("sMem: %.6f Mb lMem: %.6f Mb\n", $sMem, $lMem);
		$msg .= '<hr>';

		self::trace('debugCheckPoint', $msg);
	}

	public static function dump($var) {
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}

	private static function get_sec() {
	  list($time,$ms) = explode(" ", microtime());
	  return $time + $ms;
	}

	private static function get_mem() {
		return memory_get_usage() / 1048576;
	}

	public static function totalTime() {
		printf("ГЕНЕРАЦИЯ СТРАНИЦЫ %f сек.", self::get_sec() - RdCache::get('tStart'));
	}

	public static function memoryPeak() {
		printf("ПИКОВАЯ ПАМЯТЬ %.6f Mb.", memory_get_peak_usage()/1048576);
	}

	public static function init() {
		define('SAVEQUERIES', true);

		RdCache::set('tStart', self::get_sec());
		RdCache::set('mStart', self::get_mem());

		add_filter('admin_footer', 'RdDebug::flush');
	  add_filter('wp_footer', 'RdDebug::flush');
	}

	public static function flush() {
		$error = RdCache::get('debugError');
		$notice = RdCache::get('debugNotice');
		$msg = RdCache::get('debugMessage');
		$checkPoints = RdCache::get('debugCheckPoint');

		RdCache::set('debugError', '');
		RdCache::set('debugNotice', '');
		RdCache::set('debugMessage', '');
		RdCache::set('debugCheckPoint', '');

		?>
			<br>
			<br>
			<div><?php self::totalTime(); ?></div>
			<br>
			<div><?php self::memoryPeak(); ?></div>
			<br>

			<div class="debug_spoiler" status="hide">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG ERROR:</a>
						<?php echo empty($error) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php echo $error; ?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG NOTICE:</a>
						<?php echo empty($notice) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php echo $notice; ?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG MESSAGES: </a>
						<?php echo empty($msg) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php echo $msg; ?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler" status="hide">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG CHECK POINT:</a>
						<?php echo empty($checkPoints) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php echo $checkPoints; ?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler" status="show">
				<p class="debug_title">
					<b><a href="javascript:void(0)">DEBUG WARNING TRACE QUERY:</a></b>
				</p>
				<div class="debug_content">
					<?php
						global $wpdb;

						foreach ( $wpdb->queries as $key => &$query ) {
							if ( !preg_match('/(^0\.000)|E/', $query[1]) ) {
								printf("
									<p>query # %s</p>
									<p style='color:red'>time: %s</p>
									<pre>%s</pre>
									<hr>
									<br>",
									$key, 
									$query[1], 
									is_array($query[0]) ? print_r($query[0], true) : $query[0]);
							}
						}
						
					?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG $_POST:</a>
						<?php echo empty($_POST) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php self::dump($_POST); ?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG $_SESSION:</a>
						<?php echo empty($_SESSION) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php 
						if ( isset($_SESSION) ) {
							self::dump($_SESSION);
						} 
					?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler" status="hide">
				<p class="debug_title">
					<b>
						<a href="javascript:void(0)">DEBUG $_SERVER:</a>
						<?php echo empty($_SERVER) ? 'empty' : ''; ?>
					</b>
				</p>
				<div class="debug_content">
					<?php self::dump($_SERVER); ?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler" status="hide">
				<p class="debug_title">
					<b><a href="javascript:void(0)">DEBUG WP_QUERY:</a></b>
				</p>
				<div class="debug_content">
					<?php 
						global $wp_query;
	  				self::dump($wp_query);
	  			?>
				</div>
			</div>
			<br>

			<div class="debug_spoiler" status="hide">
				<p class="debug_title">
					<b><a href="javascript:void(0)">DEBUG TRACE QUERY:</a></b>
				</p>
				<div class="debug_content">
					<?php
						global $wpdb;
						
						printf("<p>Количество запросов: %s</p><hr>", $wpdb->num_queries);

						foreach ( $wpdb->queries as $key => &$query ) {
							printf("
								<p>query # %s</p>
								<p>time: %s</p>
								<pre>%s</pre>
								<hr>
								<br>",
								$key,
								$query[1], 
								is_array($query[0]) ? print_r($query[0], true) : $query[0]);
						}
						
					?>
				</div>
			</div>
			<br>

			<script type="text/javascript">
				(function() {
					$spoilers = jQuery('.debug_spoiler');

					$spoilers.each(function() {
						var $this = jQuery(this);

						// init hided div
						if ( 'hide' === $this.attr('status') ) {
							$this.find('.debug_content').hide();
						}

						$this.find('.debug_title').click(function() {
							if ( 'hide' === $this.attr('status') ) {
								$this.attr('status', 'show');
								$this.find('.debug_content').show();
								return;
							}
							$this.attr('status', 'hide');
							$this.find('.debug_content').hide();
						});
					});
				})();
			</script>
		<?php
	}
}
