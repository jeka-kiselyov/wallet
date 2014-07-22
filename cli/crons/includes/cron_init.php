<?php

	
	$site_path = realpath(dirname(__FILE__)."/../../../");

	if (!defined('SITE_PATH'))
		define ('SITE_PATH', $site_path);

	set_include_path(SITE_PATH);
	
	define('TIME_START', microtime(true));
	define('DO_NOT_DELEGATE', true);

	error_reporting(E_ALL);

	require "includes/init.php";
