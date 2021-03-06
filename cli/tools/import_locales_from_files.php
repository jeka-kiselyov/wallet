<?php

	require "includes/tools_init.php";

	$db = db::getInstance();

	$i18n_strings = autoloader_get_model_or_class('i18n_strings');
	$i18n_languages = autoloader_get_model_or_class('i18n_languages');

	$to_import = array();
	$files = scandir(SITE_PATH_SETTINGS.'locales');
	foreach ($files as $file)
		if (strpos($file, '.json') === strlen($file)-5)
			$to_import[] = $file;

	if (!count($to_import))
	{
		logstr("There's nothing to import in ".SITE_PATH_SETTINGS.'locales'.". Done.");
		exit;
	}

	logstr("Count of locales to import from ".SITE_PATH_SETTINGS.'locales'.": ".count($to_import));

	foreach ($to_import as $file) {
		logstr('Importing locale from '.$file);
		$data = @file_get_contents(SITE_PATH_SETTINGS.'locales'.DIRECTORY_SEPARATOR.$file);
		$data = @json_decode($data, true);

		if (!$data || !isset($data['code'], $data['name'], $data['strings'], $data['is_default']) || !$data['code']|| !$data['name'])
		{
			logstr('Empty. Done.');
			continue;
		}

		/// 1st step. Language
		$in_db = $db->getrow("SELECT * FROM i18n_languages WHERE code = '".$db->escape($data['code'])."' LIMIT 1;");
		if (!$in_db)
		{
			/// not in db. Create new one
			$i18n_language = new i18n_language;
			$i18n_language->code = $data['code'];
			$i18n_language->name = $data['name'];
			$i18n_language->is_default = isset($data['is_default']) ? $data['is_default'] : 0;
			$i18n_language->save();

			$in_db = $db->getrow("SELECT * FROM i18n_languages WHERE code = '".$db->escape($data['code'])."' LIMIT 1;");

			if (!$in_db)
			{
				logstr('WARN: Can not save language to database');
				continue;
			}
			logstr('Added new language to database');
		}

		$i18n_language = $i18n_languages->get_by_id($in_db['id']);

		foreach ($data['strings'] as $string => $translation) {
			// add string if it's not in database
			$i18n_language->add_string_to_translate($string);

			$string_in_db = $db->getrow("SELECT * FROM i18n_strings WHERE BINARY string = '".$db->escape($string)."' LIMIT 1;");
			$i18n_string = $i18n_strings->get_by_id($string_in_db['id']);

			$i18n_string->update_translation($i18n_language->id, $translation);
		}


		logstr('Done with file '.$file);
	}

	logstr('Done');

