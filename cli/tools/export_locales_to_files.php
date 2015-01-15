<?php

	require "includes/tools_init.php";

	$db = db::getInstance();

	$languages = $db->getall('SELECT * FROM i18n_languages');
	foreach ($languages as $lang) 
	{
		logstr('Generating locale file for language with code '.$lang['code']);
		$data = array();

		$strings = $db->getall("SELECT * FROM i18n_translations LEFT JOIN i18n_strings ON i18n_translations.string_id = i18n_strings.id WHERE i18n_translations.language_id = '".$lang['id']."'");
		foreach ($strings as $s) 
		{
			$data[$s['string']] = $s['translation'];
		}

		logstr('Items count: '.count($data));

		$res = array('code'=>$lang['code'], 'name'=>$lang['name'], 'is_default'=>$lang['is_default'], 'strings'=>$data);
		$data = json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		$success = @file_put_contents(SITE_PATH_SETTINGS.'locales'.DIRECTORY_SEPARATOR.$lang['code'].'.json', $data);

		if ($success)
			logstr('Saved to '.SITE_PATH_SETTINGS.'locales'.DIRECTORY_SEPARATOR.$lang['code'].'.json');
		else
			logstr('WARN: Can not save to '.SITE_PATH_SETTINGS.'locales'.DIRECTORY_SEPARATOR.$lang['code'].'.json');
	}
	logstr('Done');

