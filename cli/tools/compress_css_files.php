<?php

	require "includes/tools_init.php";
	$tasks = array();

	logstr('Running css files compressor');

	if (!is_dir(SITE_PATH_CACHE.'minification'))
		mkdir(SITE_PATH_CACHE.'minification');

	$files = scandir(SITE_PATH_CACHE.'minification');
	foreach ($files as $file)
		if (strpos($file, 'css-') === 0 && strpos($file, '.json') === strlen($file)-5)
			$tasks[] = $file;

	if (count($tasks))
		logstr('Count of tasks: '.count($tasks));
	else
	{
		logstr('No css-*.json tasks found in '.SITE_PATH_CACHE.'minification. Done', 'system');
		exit;
	}

	$i = 1;
	foreach ($tasks as $task) {
		logstr('Task #'.$i.': '.$task);
		$data = @json_decode(file_get_contents(SITE_PATH_CACHE.'minification/'.$task), true);

		if (!$data || !isset($data['elements']) || !isset($data['hash'])) 
		{
			logstr('Something is wrong with this task. Bad JSON format');
			continue;
		}

		$elements = $data['elements'];
		$files_count = count($elements);
		$hash = $data['hash'];

		logstr('Hash: '.$hash.' Elements count: '.$files_count);

		$initial_files_size = 0;
		/// normalize
		$normalized_elements = array();
		foreach ($elements as &$element) {
			if (substr($element, -4) !== '.css' && substr($element, -5) !== '.less')
				$element .= '.css';
			if (strpos($element, SITE_PATH_APP) !== 0)
				$element = SITE_PATH_APP.'public/'.$element;
			if (is_file($element))
				$initial_files_size+=filesize($element);
			else
			{

				logstr('WARN. Can not find file: '.$element.' Missing it.');
				continue;
			}

			if (is_file($element))
				$normalized_elements[] = $element;
			else
				logstr('WARN. Can not find file: '.$element);
		}
		$elements = $normalized_elements;

		if (!count($normalized_elements))
		{
			logstr('WARN. Source list is empty.'); continue;
		}
		if (!$initial_files_size)
		{
			logstr('WARN. Can not get size of source files. Permission issue?'); continue;
		}

		logstr('Normalized source list. Elements count: '.count($normalized_elements).' Original size: '.human_filesize($initial_files_size));

		///// target
		if (!is_dir(SITE_PATH_APP.'/public/css/dist'))
			@mkdir(SITE_PATH_APP.'/public/css/dist');
		$target = SITE_PATH_APP.'/public/css/dist/'.$hash.'.min.css';

		if (is_file($target))
		{
			$target_filesize = @filesize($target);
			$success = @unlink($target);
			if ($success)
				logstr('Target '.$target.' already exist with filesize: '.human_filesize($target_filesize).' Removed now, waiting for a new one.');
			else {
				logstr('WARN. Target '.$target.' already exist with filesize: '.human_filesize($target_filesize).' Can not remove it, something is wrong, probably permisson issue.');
				continue;
			}
		}

		logstr('Executing less if needed...');
		$to_clean_css = array();
		$to_cleanup_after = array();
		foreach ($elements as $src)
		{
			if (substr($src, -5) == '.less')
			{
				$cmd = 'lessc '.escapeshellarg($src).' > '.escapeshellarg($src.'.lessed.css');
				exec($cmd, $output);
				$to_clean_css[] = $src.".lessed.css";
				$to_cleanup_after[] = $src.".lessed.css";
			} else {
				$to_clean_css[] = $src;
			}
		}
		
		logstr('Executing cleancss...');

        $cmd = 'cleancss ';
        $cmd.=' -o '.$target.' --skip-advanced ';
        foreach ($to_clean_css as $src)
            $cmd.=escapeshellarg($src).' ';
  
        exec($cmd, $output);

		logstr('Cleaning up...');
        foreach ($to_cleanup_after as $src)
        	unlink($src);

		$output_filesize = 0;
		if (is_file($target))
			$output_filesize = filesize($target);

		if ($output_filesize)
		{
			logstr('Target is ready. Filesize: '.human_filesize($output_filesize));
			if ($output_filesize > $initial_files_size)
				logstr("yes, it's more than original ".human_filesize($initial_files_size)." probably because of @import's. But you can check.");
		}
		else {
			logstr('\033[1;31mERROR\033[0m Something is wrong, target is empty', 'system');
		}

		# code...

		$i++;
	}

	logstr('Done.', 'system');


?>