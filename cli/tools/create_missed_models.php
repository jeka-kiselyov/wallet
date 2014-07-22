<?php

	require "includes/tools_init.php";

	$db = db::getInstance();

	logstr('Running missed models generator');

	if (!is_writable($site_path."/app/models"))
	{
		logstr("Please check permission of app/models directory first", 'system');
		exit(0);
	}

	$stats = array('added_models'=>0, 'missed_models'=>0, 'total_models'=>0);

	$tables_in_schema = schema::getInstance()->get_tables();

	logstr('Count of tables in schema: '.count($tables_in_schema));

	foreach ($tables_in_schema as $table_name) 
	{
		logstr('Checking table `'.$table_name.'` from schema file');
		$model_name = $table_name;
		logstr('Model name: '.$model_name);

		$stats['total_models']++;

		if (is_file($site_path."/app/models/m_".$model_name.".php"))
		{
			$class_exists = true;
			logstr('File app/models/m_'.$model_name.'.php exists');
		} else {
			$class_exists = false;
			logstr('File app/models/m_'.$model_name.'.php does not exist');
			$stats['missed_models']++;
		}

		if (!$class_exists && confirm('add class for `'.$model_name.'` to app/models/m_'.$model_name.'.php file'))
		{
			$data = "<?php\n\n";
			$data.= "\tclass ".$model_name." extends model_base\n\t{";
			$data.= "\t\n\t\n\t\n\t}\n\n\n\n";

			if (file_put_contents($site_path."/app/models/m_".$model_name.".php", $data))
			{
				$stats['added_models']++;
				logstr('File app/models/m_'.$model_name.'.php is ready');
			} else {
				logstr("Can not write to file app/models/m_".$model_name.".php", 'system');
			}
		}
	}

	if ($cli_stats)
	{
		logstr("Finished in ".(microtime(true) - TIME_START)." seconds", 'stats');
		logstr("Results: ", 'stats');
		logstr("Total models: ".$stats['total_models'], 'stats');
		logstr("Missed models: ".$stats['missed_models'], 'stats');
		logstr("Added models: ".$stats['added_models'], 'stats');
	}

	logstr("OK", 'system');		
