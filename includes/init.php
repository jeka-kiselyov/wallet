<?php


if (!defined('SITE_PATH'))
define ('SITE_PATH', realpath(dirname(__FILE__)."/../")  );
 
 define ('SITE_PATH_SETTINGS', SITE_PATH.DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR);
 define ('SITE_PATH_INCLUDES', SITE_PATH.DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR);
 define ('SITE_PATH_APP', SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR);
 define ('SITE_PATH_LIBS', SITE_PATH_INCLUDES."libs".DIRECTORY_SEPARATOR);

 require SITE_PATH_SETTINGS."environment.php";
 require SITE_PATH_SETTINGS."db.php";
 require SITE_PATH_LIBS."autoload.php";

 // require SITE_PATH_LIBS."Smarty".DIRECTORY_SEPARATOR."Smarty.class.php";

 //require SITE_PATH_LIBS."adodb5".DIRECTORY_SEPARATOR."adodb-exceptions.inc.php";
 //require SITE_PATH_LIBS."adodb5".DIRECTORY_SEPARATOR."adodb.inc.php";

 require SITE_PATH_INCLUDES."db.class.php";
 require SITE_PATH_INCLUDES."schema.class.php";
 require SITE_PATH_INCLUDES."inflector.class.php";
 require SITE_PATH_INCLUDES."registry.class.php";
 require SITE_PATH_INCLUDES."settings.class.php";
 require SITE_PATH_INCLUDES."router.class.php";
 require SITE_PATH_INCLUDES."checker.class.php";

 require SITE_PATH_INCLUDES."base.class.php";
 require SITE_PATH_INCLUDES."singleton.base.class.php";
 require SITE_PATH_INCLUDES."collection.class.php";
 require SITE_PATH_INCLUDES."entity.base.class.php";
 require SITE_PATH_INCLUDES."controller.base.class.php";
 require SITE_PATH_INCLUDES."model.base.class.php";

 require SITE_PATH_INCLUDES."entityvalidation.exception.class.php";


 if (defined('PHPUNIT') && PHPUNIT)
 {
 	error_reporting(E_ALL);
	require SITE_PATH_INCLUDES."testcase.class.php"; 	
 }

 $registry = registry::getInstance();

 $registry->set('schema', schema::getInstance());
 $registry->set("db", db::getInstance());

 $settings = settings::getInstance($registry);

 $registry->set("settings", $settings);

 if ($settings['mvc']['enable_magic_entities_and_models'])
	require SITE_PATH_INCLUDES."autoloader.php";
 else
	require SITE_PATH_INCLUDES."autoloader_no_magic.php";

 date_default_timezone_set($settings['timezone']);

 $router = new router( $registry);
 $router->setPath (SITE_PATH_APP.'controllers');
 $registry->set("router", $router);

 $tpl = new Smarty();
 $tpl->addPluginsDir(SITE_PATH_INCLUDES.DIRECTORY_SEPARATOR.'template_plugins');

 $tpl->template_dir = SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."templates";
 $tpl->cache_dir = SITE_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."templates_cache";
 $tpl->compile_dir = SITE_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."templates_compiled";

 $tpl->caching = $registry->settings['cache']['enable_smarty_cache'];
 $tpl->cache_lifetime  = $registry->settings['cache']['smarty']['cache_lifetime'];

 $registry->set("tpl", $tpl);

 $registry->tpl->assign("settings",$registry->settings);

 if (!defined('DO_NOT_DELEGATE') || !DO_NOT_DELEGATE)
	 $router->delegate();

?>