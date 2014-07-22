<?php

	require "includes/tools_init.php";

	$db = db::getInstance();
	$schema = schema::getInstance();

	logstr('Running database schema updater');

	$stats = array('added_tables'=>0, 'removed_tables'=>0, 'changed_tables'=>0, 'added_fields'=>0, 'changed_fields'=>0, 'removed_fields'=>0, 'added_indexes'=>0, 'removed_indexes'=>0);

	$tables_in_schema = $schema->get_tables();
	$tables_in_db = $schema->get_tables_from_db();

	logstr('Count of tables in schema: '.count($tables_in_schema));
	logstr('Count of tables in database: '.count($tables_in_db));

	foreach ($tables_in_schema as $table_name) 
	{
		logstr('Checking table `'.$table_name.'` from schema file');
		$fields_in_schema = $schema->get_fields($table_name);
		$fields_in_db = @$schema->get_fields_from_db($table_name);

		if (!in_array($table_name, $tables_in_db))
		{
			logstr("There's no `".$table_name."` table in database. Adding it");

			if (confirm('add `'.$table_name.'` table'))
			{
				logstr('Count of fields in schema: '.count($fields_in_schema));

				// Insert Primary Key id
				$fields_queries = array();
				$fields_queries[] = " `id` bigint(11) NOT NULL AUTO_INCREMENT ";

				foreach ($fields_in_schema as $field_name => $parameters) 
				{
					$fields_queries[] = $schema->schema_field_to_sql_item($field_name, $parameters);
				}

				// Insert Primary Key key
				$fields_queries[] = " PRIMARY KEY (`id`) ";

				foreach ($fields_in_schema as $field_name => $parameters) 
				{
					if (isset($parameters['primaryKey']) && $parameters['primaryKey'])
						$fields_queries[] = "PRIMARY KEY (`".$db->escape($field_name)."`)";
					else {
						if (isset($parameters['unique']) && $parameters['unique'])
							$fields_queries[] = "UNIQUE KEY `".$db->escape($field_name)."` (`".$db->escape($field_name)."`)";
						elseif (isset($parameters['key']) && $parameters['key'])
						$fields_queries[] = "KEY `".$db->escape($field_name)."` (`".$db->escape($field_name)."`)";
					}

					$stats['added_fields']++;
				}

				$query = "CREATE TABLE `".$db->escape($table_name)."` (";
				$query.= implode(", ", $fields_queries);
				$query.= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

				$db->query($query);

				$stats['added_tables']++;

				logstr('Done');
			}
		}
		else
		{
			logstr("There's `".$table_name."` table in database. Checking fields");
			logstr('Count of fields in schema: '.count($fields_in_schema));
			logstr('Count of fields in database: '.count($fields_in_db));

			$changed = false;

			foreach ($fields_in_schema as $field_name => $parameters) 
			if ($field_name != 'id')
			{
				logstr('Checking field `'.$field_name.'`');

				$field_in_schema_query = $schema->schema_field_to_sql_item($field_name, $parameters);
				if (isset($fields_in_db[$field_name]))
				{
					$field_in_db_query = $schema->schema_field_to_sql_item($field_name, $fields_in_db[$field_name]);
					if ($field_in_db_query == $field_in_schema_query)
						logstr('Is in database and is same');
					else
					{
						logstr('Is in database, but different. Updating...');
						if (confirm('update field `'.$field_name.'` in `'.$table_name.'` table'))
						{
							$db->query("ALTER TABLE `".$db->escape($table_name)."` 
													CHANGE  `".$db->escape($field_name)."` ".$field_in_schema_query);
							$stats['changed_fields']++;
							$changed = true;
							logstr('Done');
						}
					}
				}
				else
				{
					logstr('Is not in database. Adding...');
					if (confirm('add field `'.$field_name.'` to `'.$table_name.'` table'))
					{
						$db->query("ALTER TABLE `".$db->escape($table_name)."` 
												ADD  ".$field_in_schema_query);
						$stats['added_fields']++;
						$changed = true;
						logstr('Done');
					}
				}
			}

			logstr('Checking indexes...');
			/// Checking Indexes
			foreach ($fields_in_schema as $field_name => $parameters)
			{		
				logstr('Checking indexes for '.$field_name.' field...');
				if (isset($parameters['primaryKey']) && $parameters['primaryKey']) 
				{
					logstr('Skip primaryKey '.$field_name.' field...');
				}
				elseif (isset($parameters['unique']) && $parameters['unique'])
				{
					if (!isset($fields_in_db[$field_name]) || !isset($fields_in_db[$field_name]['unique']) || !$fields_in_db[$field_name]['unique'])
					{
						/// Need to insert unique key
						logstr('Unique index is not in database. Adding...');
						if (confirm('add unique index to field `'.$field_name.'` in `'.$table_name.'` table'))
						{
							if (isset($fields_in_db[$field_name]) && isset($fields_in_db[$field_name]['key']) && $fields_in_db[$field_name]['key'])
							{
								/// DROP INDEX FIRST
								$db->query("ALTER TABLE `".$db->escape($table_name)."` 
														DROP INDEX `".$field_name."`;");
							}
							$db->query("ALTER TABLE `".$db->escape($table_name)."` 
													ADD UNIQUE (`".$field_name."`);");
							$stats['added_indexes']++;
							$changed = true;
							logstr('Done');
						}
					}
				} elseif (isset($parameters['key']) && $parameters['key'])
				{
					if (!isset($fields_in_db[$field_name]) || !isset($fields_in_db[$field_name]['key']) || !$fields_in_db[$field_name]['key'])
					{
						/// Need to insert index
						logstr('Index is not in database. Adding...');
						if (confirm('add index to field `'.$field_name.'` in `'.$table_name.'` table'))
						{
							if (isset($fields_in_db[$field_name]) && isset($fields_in_db[$field_name]['unique']) && $fields_in_db[$field_name]['unique'])
							{
								/// DROP UNIQUE FIRST
								$db->query("ALTER TABLE `".$db->escape($table_name)."` 
														DROP INDEX `".$field_name."`;");
							}
							$db->query("ALTER TABLE `".$db->escape($table_name)."` 
													ADD INDEX (`".$field_name."`);");
							$stats['added_indexes']++;
							$changed = true;
							logstr('Done');
						}
					}

				} elseif 	(isset($fields_in_db[$field_name]) 
							&& (	(isset($fields_in_db[$field_name]['key']) && $fields_in_db[$field_name]['key']) 
									|| 
									(isset($fields_in_db[$field_name]['unique'])  && $fields_in_db[$field_name]['unique']) 
								)
							)
				{
					/// Need to remove index
					logstr('Index is not defined in schema.  Removing it from database...');
					if (confirm('remove index from field `'.$field_name.'` in `'.$table_name.'` table'))
					{
						$db->query("ALTER TABLE `".$db->escape($table_name)."` 
												DROP INDEX `".$field_name."`;");
						$stats['removed_indexes']++;
						$changed = true;
						logstr('Done');
					}
				}
			}	


			foreach ($fields_in_db as $in_db_field_name => $in_db_parameters) 
			if ($in_db_field_name != 'id')
			{
				if (!isset($fields_in_schema[$in_db_field_name]))
				{
					logstr('Database field `'.$in_db_field_name.'` is not defined in schema. Removing it from database table');
					if (confirm('remove field `'.$in_db_field_name.'` from `'.$table_name.'` table'))
					{
						$db->query("ALTER TABLE `".$db->escape($table_name)."` 
												DROP  `".$db->escape($in_db_field_name)."`");
						$stats['removed_fields']++;
						$changed = true;
						logstr('Done');	
					}
				}
			}

			if ($changed)
				$stats['changed_tables']++;
		}
	}

	foreach ($tables_in_db as $in_db_table_name) 
	{
		if (!in_array($in_db_table_name, $tables_in_schema))
		{
			logstr('Database table `'.$in_db_table_name.'` is not defined in schema. Removing it from database');
			if (confirm('remove table `'.$in_db_table_name.'` from database'))
			{
				$db->query("DROP TABLE `".$db->escape($in_db_table_name)."` ");
				$stats['removed_tables']++;
				$changed = true;
				logstr('Done');	
			}
		}
	}

	if ($cli_stats)
	{
		logstr("Finished in ".(microtime(true) - TIME_START)." seconds", 'stats');
		logstr("Results: ", 'stats');
		logstr("Added tables: ".$stats['added_tables'], 'stats');
		logstr("Changed tables: ".$stats['changed_tables'], 'stats');
		logstr("Removed tables: ".$stats['removed_tables'], 'stats');
		logstr("Added fields: ".$stats['added_fields'], 'stats');
		logstr("Changed fields: ".$stats['changed_fields'], 'stats');
		logstr("Removed fields: ".$stats['removed_fields'], 'stats');

		$total_operations = 0;
		foreach ($stats as $stat) 
			$total_operations+=$stat;
		logstr("Total operations: ".$total_operations, 'stats');
	}

	logstr("OK", 'system');		