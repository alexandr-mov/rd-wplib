<?php

function rdPathToUrl($dir) {
	return RD_HOME_URL . '/' . str_replace(ABSPATH, '', rtrim($dir, '/'));
}