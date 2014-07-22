<?php

	$cli_debug = false;
	$cli_stats = false;
	$cli_force = false;

	foreach ( $argv as $key => $value ) 
	{
		if ($value == '-f' || $value == '-F' || $value == '--force')
			$cli_force = true;
		if ($value == '-s' || $value == '-S' || $value == '--stats')
			$cli_stats = true;
		if ($value == '-d' || $value == '-D' || $value == '--debug')
			$cli_debug = true;
	}

	system("stty -icanon");
	stream_set_blocking(STDIN, false);
	
	$site_path = realpath(dirname(__FILE__)."/../../../");

	if (!defined('SITE_PATH'))
		define ('SITE_PATH', $site_path);

	set_include_path(SITE_PATH);
	
	define('TIME_START', microtime(true));
	define('DO_NOT_DELEGATE', true);

	error_reporting(E_ALL);

	require "includes/init.php";

	function logstr($string, $type = 'debug')
	{
		global $cli_debug, $cli_stats;

		if ($type == 'debug' && $cli_debug)
			fwrite(STDOUT, $string."\n");
		if ($type == 'stats' && $cli_stats)
			fwrite(STDOUT, $string."\n");
		if ($type == 'system')
			fwrite(STDOUT, $string."\n");
	}

	function confirm($what)
	{
		global $cli_force;
		if ($cli_force)
			return true;

		fwrite(STDOUT, "Are you sure that you want to ".$what."? (y/n)\n");
		do {
			$selection = fgetc(STDIN);
			usleep(1000);
		} while (trim($selection) != 'y' && trim($selection) != 'n');

		fwrite(STDOUT, "\n");

		if (trim($selection) == 'y')
			return true;
		else
			return false;
	}
