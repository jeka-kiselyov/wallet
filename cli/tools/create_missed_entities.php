<?php

	require "includes/tools_init.php";

	$db = db::getInstance();

	logstr('Running missed entities generator');

	if (!is_writable($site_path."/app/entities"))
	{
		logstr("Please check permission of app/entities directory first", 'system');
		exit(0);
	}

	$stats = array('added_entities'=>0, 'missed_entities'=>0, 'total_entities'=>0);

	$tables_in_schema = schema::getInstance()->get_tables();

	logstr('Count of tables in schema: '.count($tables_in_schema));

	foreach ($tables_in_schema as $table_name) 
	{
		logstr('Checking table `'.$table_name.'` from schema file');
		$entity_name = Inflector::singularize($table_name);
		logstr('Entity name: '.$entity_name);

		$stats['total_entities']++;

		if (is_file($site_path."/app/entities/e_".$entity_name.".php"))
		{
			$class_exists = true;
			logstr('File app/entities/e_'.$entity_name.'.php exists');
		} else {
			$class_exists = false;
			logstr('File app/entities/e_'.$entity_name.'.php does not exist');
			$stats['missed_entities']++;
		}

		if (!$class_exists && confirm('add class for `'.$entity_name.'` to app/entities/e_'.$entity_name.'.php file'))
		{
			$data = "<?php\n\n";
			$data.= "\tclass ".$entity_name." extends entity_base\n\t{";
			$data.= "\t\n\t\n\t\n\t}\n\n\n\n";

			if (file_put_contents($site_path."/app/entities/e_".$entity_name.".php", $data))
			{
				$stats['added_entities']++;
				logstr('File app/entities/e_'.$entity_name.'.php is ready');
			} else {
				logstr("Can not write to file app/entities/e_".$entity_name.".php", 'system');
			}
		}
	}

	if ($cli_stats)
	{
		logstr("Finished in ".(microtime(true) - TIME_START)." seconds", 'stats');
		logstr("Results: ", 'stats');
		logstr("Total entities: ".$stats['total_entities'], 'stats');
		logstr("Missed entities: ".$stats['missed_entities'], 'stats');
		logstr("Added entities: ".$stats['added_entities'], 'stats');
	}

	logstr("OK", 'system');		
