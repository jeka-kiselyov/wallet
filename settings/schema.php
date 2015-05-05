<?php

	$schema['wallets'] = array();
	$schema['wallets']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'user_id'           => array('type'=>"INTEGER", 'key'=>true),
		'name' => array('type'=>"STRING(255)"),
		'type' => array('type'=>"ENUM('default','user')"),
		'status' => array('type'=>"ENUM('active','hidden')", 'defaultValue'=>"active"),
		'currency' => array('type'=>"STRING(5)", 'defaultValue'=>"USD"),
		'total' => array('type'=>'FLOAT')
	);

	$schema['transactions'] = array();
	$schema['transactions']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'user_id'           => array('type'=>"INTEGER", 'key'=>true),
		'wallet_id'           => array('type'=>"INTEGER", 'key'=>true),
		'description' => array('type'=>"STRING(255)"),
		'type' => array('type'=>"ENUM('profit', 'expense')", 'defaultValue'=>"expense"),
		'subtype' => array('type'=>"ENUM('setup', 'confirmed', 'planned')", 'defaultValue'=>"confirmed"),
		'amount' => array('type'=>'FLOAT'),
		'abs_amount' => array('type'=>'FLOAT'),
		'datetime'         => array('type'=>"INTEGER", 'key'=>true),
	);

	$schema['reccurences'] = array();
	$schema['reccurences']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'transaction_id'           => array('type'=>"INTEGER", 'key'=>true),
		'wallet_id'           => array('type'=>"INTEGER", 'key'=>true),
		'start'         => array('type'=>"INTEGER", 'key'=>true),
		'next'         => array('type'=>"INTEGER", 'key'=>true),
		'year'         => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'month'         => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'day'         => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'week'         => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'weekday'         => array('type'=>"INTEGER", 'defaultValue'=>"0"),
	);

	$schema['wallets_accesses'] = array();
	$schema['wallets_accesses']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'wallet_id'			=> array('type'=>"INTEGER", 'key'=>true),
		'original_user_id'	=> array('type'=>"INTEGER", 'key'=>true),
		'to_user_id'		=> array('type'=>"INTEGER", 'key'=>true),
		'to_email'			=> array('type'=>"STRING", 'key'=>true)
	);

	$schema['authentications'] = array();
	$schema['authentications']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'user_id'           => array('type'=>"INTEGER"),
		'third_party_name'  => array('type'=>"STRING(50)"),
		'third_party_id'    => array('type'=>"STRING"),
		'third_party_token' => array('type'=>"STRING(1000)"),
		'user_ip'           => array('type'=>"STRING(100)"),
		'auth_code'         => array('type'=>"STRING(100)", 'unique'=>true)
	);

	$schema['i18n_languages'] = array();
	$schema['i18n_languages']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'code'                     => array('type'=>"STRING(25)"),
		'name'                     => array('type'=>"STRING"),
		'is_default'               => array('type'=>"ENUM('0','1')"),
		'translated_strings_count' => array('type'=>"INTEGER", 'defaultValue'=>"0")
	);

	$schema['i18n_strings'] = array();
	$schema['i18n_strings']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'string' => array('type'=>"STRING(500)")
	);

	$schema['i18n_translations'] = array();
	$schema['i18n_translations']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'language_id' => array('type'=>"INTEGER", 'key'=>true),
		'string_id'   => array('type'=>"INTEGER", 'key'=>true),
		'translation' => array('type'=>"STRING(500)")
	);

	$schema['mailtemplates'] = array();
	$schema['mailtemplates']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'name'        => array('type'=>"STRING", 'key'=>true),
		'content'     => array('type'=>"TEXT"),
		'subject'     => array('type'=>"STRING(1000)"),
		'language_id' => array('type'=>"INTEGER", 'key'=>true, 'defaultValue'=>"0")
	);

	$schema['news_categories'] = array();
	$schema['news_categories']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'name' => array('type'=>"STRING")
	);

	$schema['news_items'] = array();
	$schema['news_items']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'title'         => array('type'=>"STRING(1000)"),
		'slug'          => array('type'=>"STRING(1000)", 'key'=>true),
		'description'   => array('type'=>"STRING(1000)"),
		'body'          => array('type'=>"TEXT"),
		'preview_image' => array('type'=>"STRING"),
		'time_created'  => array('type'=>"INTEGER"),
		'time_updated'  => array('type'=>"INTEGER"),
		'i18n_language_id'   => array('type'=>"INTEGER", 'key'=>true, 'defaultValue'=>"0")
	);

	$schema['news_items_categories'] = array();
	$schema['news_items_categories']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'news_item_id'     => array('type'=>"INTEGER", 'key'=>true),
		'news_category_id' => array('type'=>"INTEGER", 'key'=>true)
	);

	$schema['settings'] = array();
	$schema['settings']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'name'  => array('type'=>"STRING"),
		'value' => array('type'=>"TEXT")
	);

	$schema['static_pages'] = array();
	$schema['static_pages']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'title'       => array('type'=>"STRING"),
		'body'        => array('type'=>"TEXT"),
		'slug'        => array('type'=>"STRING", 'key'=>true),
		'i18n_language_id' => array('type'=>"INTEGER", 'key'=>true, 'defaultValue'=>"0")
	);

	$schema['users'] = array();
	$schema['users']['fields'] = array(
		// ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
		'email'                 => array('type'=>"STRING", 'key'=>true),
		'type'                  => array('type'=>"STRING(20)", 'defaultValue'=>"default"),
		'password'              => array('type'=>"STRING(50)", 'key'=>true),
		'login'                 => array('type'=>"STRING", 'key'=>true),
		'is_demo'              => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'is_admin'              => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'registration_date'     => array('type'=>"INTEGER"),
		'activity_date'         => array('type'=>"INTEGER"),
		'registration_ip'       => array('type'=>"STRING(20)"),
		'activity_ip'           => array('type'=>"STRING(20)"),
		'confirmation_code'     => array('type'=>"STRING"),
		'password_restore_code' => array('type'=>"STRING"),
		'auth_code'             => array('type'=>"STRING"),
		'is_banned'             => array('type'=>"INTEGER", 'defaultValue'=>"0"),
		'language_id'           => array('type'=>"INTEGER", 'defaultValue'=>"0")
	);



return $schema;
