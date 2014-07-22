<?php
	////
	////  Usage: php backup_to_pbworks.php network workspace apikey
	////
	//// Archives uploads folder and mysql database and saves it in pbworks workspace
	//// 

	require "includes/cron_init.php"; //// You can change this to any init you want. 
	/// Basically we need only __db_database__, __db_host__ etc params from settings/db.php
	/// But you can easily use your own values few lines below and remove this require

	$backup_name = __db_database__."_backup";
	$backups = array();
	$backups[] = array('type'=>'mysql', 'host'=>__db_host__, 'user'=>__db_username__, 'password'=>__db_password__, 'database'=>__db_database__);
	$backups[] = array('type'=>'folder', 'folder'=>realpath(dirname(__FILE__)."/../../")."/app/public/uploads");

	$tmp_path = realpath(dirname(__FILE__))."/tmp/";   //// should be writable

	$network = false;
	$workspace = false;
	$apikey = false;

	if (isset($argv[1])) $network = trim($argv[1]); 
	if (isset($argv[2])) $workspace = trim($argv[2]);
	if (isset($argv[3])) $apikey = trim($argv[3]);


	if (!$network || !$workspace || !$apikey)
		die("ERROR: Please run script as 'php backup_to_pbworks.php network workspace apikey'\n");

	$pbworks = new pbworks($network, $workspace, $apikey);
	$free_space = $pbworks->get_free_space();

	echo "Free space on pbworks workspace: ".$free_space." bytes\n\n";

	if (!$free_space)
		die("ERROR: Can't get free space on workspace. Be sure to run script as 'php backup_to_pbworks.php network workspace apikey'\n");

	$time_string = date("Ymd-Hi"); /// time part of filenames

	$filter = $backup_name; /// We'll use it to filter remote backups;
	$archive_file_name = $filter."-".$time_string.".tgz";

	$keep_rules = array();	//// Keep archives for this times
	$keep_rules[] = array('from' => false, 'to'=>time()-24*60*60); // 1 day.
	$keep_rules[] = array('from' => time()-24*60*60, 'to'=>time()-2*24*60*60); // 2 days
	$keep_rules[] = array('from' => time()-2*24*60*60, 'to'=>time()-3*24*60*60); // 3 days
	$keep_rules[] = array('from' => time()-3*24*60*60, 'to'=>time()-7*24*60*60); // 1 week
	$keep_rules[] = array('from' => time()-7*24*60*60, 'to'=>time()-30*24*60*60); // 1 month
	$keep_rules[] = array('from' => time()-30*24*60*60, 'to'=>time()-365*24*60*60); // 1 year

	
	/// Remove old files from pbworks
	echo "Removing remote backups...\n";
	$i = 0;
	$files = $pbworks->get_files($filter);
	echo "There're ".count($files)." files on pbworks workspace\n";
	foreach ($keep_rules as $rule) 
	{
		$rule['most_recent_file'] = false;
		$rule['oldest_file'] = false;
		$count_files = 0;

		foreach ($files as $key=>$file) 
			if (  ($rule['from'] !== false && $file['mtime'] >= $rule['to'] && $file['mtime'] <= $rule['from']) ||  ($rule['from'] === false && $file['mtime'] >= $rule['to'])  )
			{
				$count_files++;

				if ($rule['most_recent_file'] === false) 
					$rule['most_recent_file'] = $key;
				else
				{
					if ($file['mtime'] > $files[$rule['most_recent_file']]['mtime'])
						$rule['most_recent_file'] = $key;						
				}
				if ($rule['oldest_file'] === false) 
					$rule['oldest_file'] = $key;
				else
				{
					if ($file['mtime'] < $files[$rule['oldest_file']]['mtime'])
						$rule['oldest_file'] = $key;						
				}
			}

		if ($rule['most_recent_file'] !== false)
			$files[$rule['most_recent_file']]['keep'] = true;
		if ($rule['oldest_file'] !== false)
			$files[$rule['oldest_file']]['keep'] = true;

		echo "Keep rule #".$i++.": ".$count_files." files\n";
	}

	/// Send queries to pbworks to remove
	foreach ($files as $file)
	{
		if (!isset($file['keep']) || !$file['keep'])
		{
			echo "Removing remote backup ".$file['name']."\n";
			$pbworks->remove_file($file['name']);
		} else
		  echo "Keeping remote backup ".$file['name']."\n";
	}

	echo "Done.\n\n";

	$i=0;
	$ready_filenames = array();
	foreach ($backups as $backup)
	{
		echo "Making backup #".$i."...\n";
		if ($backup['type'] == 'mysql')
			$filename = $i."_".$backup['database']."_".$time_string.".sql";
		elseif ($backup['type'] == 'folder')
			$filename = $i."_backup_".$time_string.".tar";

		echo "Filename: ".$filename."\n";

		if ($backup['type'] == 'mysql')
			$cmd = "mysqldump --host=".$backup['host']." --password=".$backup['password']." --user=".$backup['user']." -C ".$backup['database']." >> ".$tmp_path.$filename;
		else
			$cmd = "tar -cvPf ".$tmp_path.$filename." ".$backup['folder'];

		echo "Executing...\n";
		$s = exec($cmd, $o);
		sleep(1);

		if (is_file($tmp_path.$filename))
		{
			$ready_filenames[] = $tmp_path.$filename;
			echo "File is ready.\n";
		} else {
			echo "ERROR: File is not ready. Please check configuration.\n";
		}

		$i++;
	}

	//// Merging.
	echo "Making merge archive...\n";
	$merge_cmd = "tar -zPcvf ".$tmp_path.$archive_file_name." ".implode(" ", $ready_filenames);
	echo "Executing...\n";
	$s = exec($merge_cmd, $o);
	sleep(1);

	if (is_file($tmp_path.$archive_file_name))
	{
		echo "Backup file created: ".$archive_file_name."\n";
		echo "Uploading...\n";
		///// Uploading
		$success = $pbworks->submit_file($tmp_path.$archive_file_name);
		if ($success)
		{
			echo "SUCCESS: Backup file is on pbworks now\n";
		} else {
			echo "ERROR: Can not upload file to pbworks\n";
		}
	} else {
		echo "ERROR: No backup. Something is wrong\n";
	}

	///// Clean up
	echo "Cleaning up...\n";
	foreach ($ready_filenames as $filename) 
	{
		unlink($filename);
	}
	unlink($tmp_path.$archive_file_name);
	echo "Done.\n";




class pbworks {

	private $regular_query_timeout = 30;
	private $upload_query_timeout = 300;
	public $network;
	public $workspace;
	private $user_key;

	public function pbworks($network, $workspace, $user_key)
	{
		$this->network = $network;
		$this->workspace = $workspace;
		$this->user_key = $user_key;
	}

	public function get_host()
	{
		return $this->network."-".$this->workspace.".pbworks.com";
	}

	public function get_free_space()
	{
		$stats = $this->query("op/GetStorageInfo");
		if (isset($stats->available))
			return $stats->available;
		else
			return 0;
	}

	public function remove_file($filename)
	{
	    $data = $this->query("op/DeleteFile/file/".$filename);
    
		if (isset($data->success) && $data->success)
			return true;
		else
			return false;
	}

	public function get_files($filter = false)
	{
		if ($filter)
			$data = $this->query("op/GetFiles/filter/".$filter);
  		else
			$data = $this->query("op/GetFiles");


		$files = array();
		if (!isset($data->files) || !$data->files)
			return array();

		foreach ($data->files as $f)
			$files[] = array('name'=>$f->name, "mtime"=>$f->mtime);
   
		return $files;
	}

	public function submit_file($filename)
	{
		set_time_limit($this->upload_query_timeout);

		$fname = explode("/", $filename);
		if ($fname)
			$fname = $fname[count($fname)-1];
		else
			$fname = $filename;

		$boundary = md5(rand(0, time())).md5(time());
  
		$precontent = "--".$boundary."\r\n";
		$precontent.= "Content-Disposition: form-data; name=\"var_file\"; filename=\"".urlencode($fname)."\"\r\n";
		$precontent.= "Content-Type: application/x-gzip\r\n\r\n";
		$postcontent="\r\n--".$boundary."--\r\n";
  
		$content_length = strlen($precontent)+strlen($postcontent)+filesize($filename);

		$fp = fsockopen("ssl://".$this->get_host(), 443, $errno, $errstr, $this->upload_query_timeout);
  
		if (!$fp) 
		{
			return false;
		}

		$out = "POST /api_v2/op/PutFile/user_key/".$this->user_key."/filename/".urlencode($fname)."/raw/false HTTP/1.1\r\n";
		$out .= "Host: ".$this->get_host()."\r\n";
		$out .= "Content-Type: multipart/form-data, boundary=".$boundary."\r\n";
		$out .= "Content-Length: ".$content_length."\r\n\r\n";

		fwrite($fp, $out);  
		fwrite($fp, $precontent);

		$toout = fopen($filename, "rb");
		while (!feof($toout))
		{
			fwrite($fp, fread($toout, 100*1024));
			usleep(100);
		}

		fclose($toout); 
		fwrite($fp, $postcontent);

		$headers = "";
		$results = "";

		$current = 'headers';

		while (!feof($fp)) {
			if ($current == 'results') 
				$results.= fgets($fp, 128);
			else
				$headers.= fgets($fp, 128);

			if ($current == 'headers' && strpos($headers, "\r\n\r\n") !== false)
			{
				$headers = explode("\r\n\r\n", $headers);
				$results = $headers[1];
				$headers = $headers[0];
				$current = 'results';
			}   
		}

		$results = substr($results, strpos($results, "/*-secure-")+11);  /// remove secure wrapper from json
		$results = substr($results, 0, strrpos($results, "*/"));

		$results = @json_decode($results);

		if (!isset($results->success) || !$results->success)
			return false;
		else
			return true;
	}

	public function query($params)
	{
		$fp = fsockopen("ssl://".$this->get_host(), 443, $errno, $errstr, $this->regular_query_timeout);
 
		if (!$fp) 
		{
			throw new Exception("Can not connect to ".$this->get_host()." via ssl", 1);
		}
  
		$out = "GET /api_v2/".$params."/user_key/".$this->user_key." HTTP/1.1\r\n";
		$out .= "Host: ".$this->get_host()."\r\n";
		$out .= "Connection: Close\r\n\r\n";

		fwrite($fp, $out);    
		$headers = "";
		$results = "";
  
		$current = 'headers';
  
		while (!feof($fp)) 
		{
			if ($current == 'results') 
				$results.= fgets($fp, 128);
			else
				$headers.= fgets($fp, 128);
       
			if ($current == 'headers' && strpos($headers, "\r\n\r\n") !== false)
			{
				$headers = explode("\r\n\r\n", $headers);
				$results = $headers[1];
				$headers = $headers[0];
				$current = 'results';
			}   
		}
  
		$results = substr($results, strpos($results, "/*-secure-")+11);  /// remove s$
		$results = substr($results, 0, strrpos($results, "*/"));
  
		$results = @json_decode($results);
  
		if (!$results)
		{
			return false;
		}

		return $results;
	}

}