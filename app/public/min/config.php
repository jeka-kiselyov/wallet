<?php

	error_reporting(0);
    define('CUR_DIR', dirname(__FILE__));
    define('SITE_PATH', realpath(CUR_DIR."/../../../"));
    define('CACHE_DIR', SITE_PATH."/cache/css_cache");

    $settings = require (SITE_PATH."/settings/environment.php");
    $settings = require (SITE_PATH."/settings/settings.php");

    $min_css_enableCache = true;
    if (isset($settings['minify_css_cache']) && $settings['minify_css_cache'] === false)
    	$min_css_enableCache = false;
    $min_css_enableMinify = true;
    if (isset($settings['minify_css_minify']) && $settings['minify_css_minify'] === false)
    	$min_css_enableMinify = false;

	error_reporting(0);

	$min_enableBuilder = false;
	$min_errorLogger = false;
	$min_allowDebugFlag = false;
	$min_cachePath = CACHE_DIR;
	$min_documentRoot = SITE_PATH."/app/public";
	$min_cacheFileLocking = true;
	$min_serveOptions['bubbleCssImports'] = false;
	$min_serveOptions['maxAge'] = 31536000;

	$min_serveOptions['minifierOptions']['text/css']['preserveComments'] = false;

	$min_serveOptions['minApp']['groupsOnly'] = false;
	$min_serveOptions['rewriteCssUris'] = true;
	$min_symlinks = array();
	$min_serveOptions['minApp']['allowDirs'] = array(
	    '//' // allow from our alias target
	); 

	$min_uploaderHoursBehind = 0;

	$min_libPath = dirname(__FILE__) . '/lib';
	ini_set('zlib.output_compression', '0');
