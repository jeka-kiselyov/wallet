<?php

	$max_users_count_to_remove = 50;
	$older_than = time() - 2*24*60*60;

	require "includes/cron_init.php";

	$db = db::getInstance();
	$m_users = autoloader_get_model_or_class('users');

	$q = "SELECT 	
				id FROM users 
				WHERE is_demo = '1' AND activity_date < '".$older_than."'
				ORDER BY users.id ASC LIMIT ".$max_users_count_to_remove;


	$users_to_remove = $db->getall($q);

	if (!count($users_to_remove))
	{
		echo "There're no users to remove\n";
		exit;
	} else {
		echo "Count of demo users to remove: ".count($users_to_remove)."\n";		
	}

	foreach ($users_to_remove as $user) {
		$u = $m_users->get_by_id($user['id']);

		echo "Removing user #".$u->id." and its data...\n";
		$u->delete();

		echo "Deleted\n";


		# code...
	}

	echo "Done\n";

?>