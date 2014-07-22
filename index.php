<?php

 define('TIME_START', microtime(true));

 error_reporting(E_ALL);

 $site_path = realpath(dirname(__FILE__));

 if (!defined('SITE_PATH'))
 define ('SITE_PATH', $site_path);

 header('Content-Type: text/html; charset=utf-8');

 require "includes/init.php";

?>