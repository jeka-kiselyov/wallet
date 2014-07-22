<?php

	$max_users_count_to_update = 500; 
	$add_if_not_on_mailchimp = true;

	require "includes/cron_init.php";

	$db = db::getInstance();

	$q = "SELECT 	
				users.id as id, users.email as email, users.login as login FROM users 
				WHERE ISNULL(is_in_sync_with_mailchimp) OR is_in_sync_with_mailchimp = '0' 
				ORDER BY users.id DESC";

	if ($max_users_count_to_update)
		$q.= " LIMIT ".(int)$max_users_count_to_update;

	try {
		$users_to_update = $db->getall($q);
	} catch (Exception $e) {
		die("Can not get list of users to update. Don't you forget to add `is_in_sync_with_mailchimp` field to users table?\n");
	}
	$users_to_update_count = count($users_to_update);

	$list_name_to_work_with = $settings->mailchimp_main_list;

	if (!$list_name_to_work_with)
		die("Can not find mailchimp_main_list setting\n");

	if ($users_to_update_count)
		echo "There'".($users_to_update_count > 1 ? "re " : "s ").$users_to_update_count." ".($users_to_update_count > 1 ? "users" : "user")." to update\n\n";
	else
	{
		echo "There're no users to update\n";
		exit;
	}

	$mailchimp = autoloader_get_model_or_class('mailchimp_api');

	$ret = $mailchimp->mailchimp->lists->getList();
	if (!$ret)
		die("Can no get lists from MailChimp. Invalid API key?\n");

	$selected_list_id = false;
	$selected_list_name = '';

	echo "Lists on MailChimp\n";
	foreach ($ret['data'] as $list) {
		echo $list['id']." ".$list['name']."\n";

		if ($list['name'] == $list_name_to_work_with)
		{

			$selected_list_name = $list['name'];
			$selected_list_id = $list['id'];
		}
	}

	if (!$selected_list_id)
		die("Can not find the list to work with\n");

	echo "Selected list. Id: ".$selected_list_id." Name: ".$selected_list_name."\n";

	echo "\n";

	$check = array();
	foreach ($users_to_update as $user) 
	if (isset($user['email']) && $user['email'] && strpos($user['email'], '@') !== false)
	{
		$check[] = array('email'=>$user['email']);
	}

	$emails_in_list = check_emails_in_list($selected_list_id, $check);

	$update = array();
	$updated_ids = array();
	foreach ($users_to_update as $user) 
	{
		$user['email'] = trim($user['email']);
		$merge = array();

		$first_name = $user['login']; $last_name = '';
		$login = explode(" ", $user['login']);
		if (count($login) > 1)
		{
			$first_name = $login[0];
			$last_name = $login[1];
		}

		$merge['FNAME'] = $first_name;
		$merge['LNAME'] = $last_name;

		//// Here you can add additional MERGE fields. $merge['MMERGE6'] = $user['birthday'] etc.

		if (in_array($user['email'], $emails_in_list))
		{
			//// Update user on mailchimp
			$update[] = array('email'=>array('email'=>$user['email']), 'merge_vars'=>$merge);
			$updated_ids[] = $user['id'];
		} else
		{
			//// Subscribe user on mailchimp
			$update[] = array('email'=>array('email'=>$user['email']), 'merge_vars'=>$merge);
			$updated_ids[] = $user['id'];
		}
	}

	$ret = array();
	$ret = $mailchimp->mailchimp->lists->batchSubscribe($selected_list_id, $update, null, true);

	$add_count = 0;	if (isset($ret['add_count'])) $add_count = (int)$ret['add_count'];
	$update_count = 0;	if (isset($ret['update_count'])) $update_count = (int)$ret['update_count'];

	if (count($updated_ids) > 0)
	{
		$q = "UPDATE users SET is_in_sync_with_mailchimp = '1' WHERE id IN (".implode(",", $updated_ids).")";
		$db->query($q);
	}
	

	echo "Results. Updated: ".$update_count.". Added: ".$add_count."\n";

	if ($missed_count)
		echo "Also, there'".($missed_count > 1 ? "re " : "s ")." ".$missed_count." ".($missed_count > 1 ? "users" : "user")." from db missed in mailchimp list\n";

	function check_emails_in_list($selected_list_id, $emails)
	{
		global $mailchimp;
		if (count($emails) > 40)
		{
			$len = count($emails);
			$firsthalf = array_slice($emails, 0, $len / 2);
			$secondhalf = array_slice($emails, $len / 2);

			return array_merge(check_emails_in_list($selected_list_id, $firsthalf), check_emails_in_list($selected_list_id, $secondhalf));
		} else {
			$ret = $mailchimp->mailchimp->lists->memberInfo($selected_list_id, $emails);

			$emails_in_list = array();
			if (isset($ret['data']) && is_array($ret['data']))
				foreach ($ret['data'] as $in_list_user) 
					if (isset($in_list_user['email']))
						$emails_in_list[] = trim($in_list_user['email']);

			return $emails_in_list;
		}
	}

?>