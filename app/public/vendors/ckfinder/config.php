<?php

	define("DO_NOT_DELEGATE", true);
	require dirname(__FILE__)."/../../../../includes/init.php";

	$settings = $registry->settings;

	if (!isset($settings['ckfinder_enabled']) || !$settings['ckfinder_enabled'])
		die("CKFinder is disabled");	

	$sessions = autoloader_get_model_or_class('sessions'); 
	$logged_in_user = $sessions->get_user();

	function CheckAuthentication()
	{
		global $logged_in_user;
		if (!$logged_in_user || !$logged_in_user->id)
			return false;

		return true;
	}

	$config['LicenseName'] = '';
	$config['LicenseKey'] = '';

	$baseUrl = $settings->site_path.'/uploads/';
	$baseDir = SITE_PATH_APP."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
	
	if ($logged_in_user)
	{
		if ($settings['ckfinder_separate_folder_for_each_user'] && !$logged_in_user->is_admin)
		{
			$baseUrl = $settings->site_path.'/uploads/users/'.$logged_in_user->id."/";
			$baseDir = SITE_PATH_APP."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."users".DIRECTORY_SEPARATOR.$logged_in_user->id.DIRECTORY_SEPARATOR;
		}

	}

	$config['Thumbnails'] = Array(
			'url' => $settings->site_path.'/uploads/' . '_thumbs',
			'directory' => SITE_PATH_APP."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR. '_thumbs',
			'enabled' => true,
			'directAccess' => false,
			'maxWidth' => 100,
			'maxHeight' => 100,
			'bmpSupported' => false,
			'quality' => 80);

	$config['Images'] = Array(
			'maxWidth' => 1600,
			'maxHeight' => 1200,
			'quality' => 80);

	$config['RoleSessionVar'] = 'CKFinder_UserRole';

	if ($settings['ckfinder_allow_users_to_create_subfolders'] || $logged_in_user->is_admin)
	{
		$config['AccessControl'][] = Array(
				'role' => '*',
				'resourceType' => '*',
				'folder' => '/',

				'folderView' => true,
				'folderCreate' => true,
				'folderRename' => true,
				'folderDelete' => true,

				'fileView' => true,
				'fileUpload' => true,
				'fileRename' => true,
				'fileDelete' => true);		
	} else {
		$config['AccessControl'][] = Array(
				'role' => '*',
				'resourceType' => '*',
				'folder' => '/',

				'folderView' => true,
				'folderCreate' => false,
				'folderRename' => false,
				'folderDelete' => false,

				'fileView' => true,
				'fileUpload' => true,
				'fileRename' => true,
				'fileDelete' => true);			
	}


	$config['DefaultResourceTypes'] = '';

	$config['ResourceType'][] = Array(
			'name' => 'Files',				// Single quotes not allowed
			'url' => $baseUrl . 'files',
			'directory' => $baseDir . 'files',
			'maxSize' => "8M",
			'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
			'deniedExtensions' => '');

	$config['ResourceType'][] = Array(
			'name' => 'Images',
			'url' => $baseUrl . 'images',
			'directory' => $baseDir . 'images',
			'maxSize' => "8M",
			'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
			'deniedExtensions' => '');

	$config['ResourceType'][] = Array(
			'name' => 'Flash',
			'url' => $baseUrl . 'flash',
			'directory' => $baseDir . 'flash',
			'maxSize' => "8M",
			'allowedExtensions' => 'swf,flv',
			'deniedExtensions' => '');

	if ($logged_in_user && $logged_in_user->id && $logged_in_user->is_admin)
	{
		$config['ResourceType'][] = Array(
				'name' => 'Users',
				'url' => $baseUrl . 'users',
				'directory' => $baseDir . 'users',
				'maxSize' => "8M",
				'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
				'deniedExtensions' => '');
	}


	$config['CheckDoubleExtension'] = true;
	$config['DisallowUnsafeCharacters'] = true;
	$config['FilesystemEncoding'] = 'CP1250';
	$config['SecureImageUploads'] = true;
	$config['CheckSizeAfterScaling'] = true;
	$config['HtmlExtensions'] = array('html', 'htm', 'xml', 'js');
	$config['HideFolders'] = Array(".svn", "CVS");
	$config['HideFiles'] = Array(".*");
	$config['ChmodFiles'] = 0777 ;
	$config['ChmodFolders'] = 0755 ;
	$config['ForceAscii'] = true;
	$config['XSendfile'] = false;


	include_once "plugins/imageresize/plugin.php";
	include_once "plugins/fileeditor/plugin.php";
	include_once "plugins/zip/plugin.php";

	$config['plugin_imageresize']['smallThumb'] = '90x90';
	$config['plugin_imageresize']['mediumThumb'] = '120x120';
	$config['plugin_imageresize']['largeThumb'] = '180x180';
