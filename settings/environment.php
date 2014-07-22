<?php

	if (defined("PHPUNIT") && PHPUNIT)
	{
		/// Running PHPUNIT tests
		define('ENVIRONMENT_MODE', 'phpunit');

	} elseif (defined("DO_NOT_DELEGATE") && DO_NOT_DELEGATE) {
		/// Running as CLI
		define('ENVIRONMENT_MODE', 'cli');

	} else {
		/// Running serving web
		define('ENVIRONMENT_MODE', 'web');

	}

	if (ENVIRONMENT_MODE == 'web')
	{
		// As running under apache - get current server and staging using env variables, or $_SERVER['HTTP_HOST']
		// if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localsite.com')
		// {
		// 	define('ENVIRONMENT_STAGE', 'dev');
		// 	define('ENVIRONMENT_SERVER', 'developersmachine');
		// }
		// or
		// if (getenv('some_variable_declared_in_apache_configs'))
		// {
		// 	...
		// }
		if (getenv('localhost'))
		{
			define('ENVIRONMENT_STAGE', 'dev');
			define('ENVIRONMENT_SERVER', 'localhost');
		} else {
			define('ENVIRONMENT_STAGE', 'prod');
			define('ENVIRONMENT_SERVER', 'live');
		}
	} else {
		// On other cases(CLI) - determine current server by env variable or path to files
		if (strpos(SITE_PATH , "/home/jeka911/www") === 0)
		{
			define('ENVIRONMENT_STAGE', 'dev');
			define('ENVIRONMENT_SERVER', 'localhost');
		} else {
			define('ENVIRONMENT_STAGE', 'prod');
			define('ENVIRONMENT_SERVER', 'live');
		}
	}


