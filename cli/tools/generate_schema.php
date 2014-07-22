<?php

	require "includes/tools_init.php";

	$db = db::getInstance();
	$schema = schema::getInstance();

	$tables_in_db = $schema->get_tables_from_db();

	echo "<?php\n\n\t/* Generated schema file */ \n\n";

	foreach ($tables_in_db as $table_name) 
	{
		echo "\t\$schema['".$table_name."'] = array();\n";
		$fields = $schema->get_fields_from_db($table_name);
		$fields_descs = array();

		$max_field_name_length = 0;
		foreach ($fields as $key => $value)
			if (strlen($key) > $max_field_name_length)
				$max_field_name_length = strlen($key);

		$fields_descs[] = "\t\t// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement";

		foreach ($fields as $field_name => $params) 
		if ($field_name != 'id')
		{
			$indented_field_name = str_pad("'".$field_name."'", $max_field_name_length + 2); // plus quotes
			$fields_desc = "\t\t".$indented_field_name." => array('type'=>\"".$params['type']."\"";
			if (isset($params['primaryKey']) && $params['primaryKey'])
				$fields_desc.= ", 'primaryKey'=>true";
			if (isset($params['autoIncrement']) && $params['autoIncrement'])
				$fields_desc.= ", 'autoIncrement'=>true";
			if (isset($params['unique']) && $params['unique'])
				$fields_desc.= ", 'unique'=>true";
			if (isset($params['key']) && $params['key'])
				$fields_desc.= ", 'key'=>true";
			if (isset($params['defaultValue']))
				$fields_desc.= ", 'defaultValue'=>\"".addslashes($params['defaultValue'])."\"";

			$fields_desc.=")";

			$fields_descs[] = $fields_desc;
		}

		echo "\t\$schema['".$table_name."']['fields'] = array(\n".implode(",\n", $fields_descs)."\n\t);\n\n";
	}

	echo "\n\nreturn \$schema;\n\n";


	
	exit(0);

?>