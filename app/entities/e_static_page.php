<?php


 class static_page extends entity_base
 {

	protected function validation()
	{
		return array(
				array(
					'field'=>'title', 
					'checker' => checker_rules::MIN_LENGTH(1), 
					'error_message' => 'Title is too short'
				),
				array(
					'field'=>'title', 
					'checker' => checker_rules::MAX_LENGTH(1000), 
					'error_message' => 'Title is too long'
				),
				array(
					'field'=>'slug', 
					'checker' => checker_rules::MIN_LENGTH(1), 
					'error_message' => 'Slug is too short'
				),
				array(
					'field'=>'slug', 
					'checker' => checker_rules::MAX_LENGTH(1000), 
					'error_message' => 'Slug is too long'
				),
				array(
					'field'=>'slug', 
					'checker' => checker_rules::UNIQUE_IN_DB('static_pages', 'slug', $this->id), 
					'error_message' => 'This slug is already taken by another static page'
				),
				array(
					'field'=>'body', 
					'checker' => checker_rules::MIN_LENGTH(1), 
					'error_message' => 'Body is too short'
				),
				array(
					'field'=>'body', 
					'checker' => checker_rules::MAX_LENGTH(100000), 
					'error_message' => 'Body is too long'
				),
		);
	}

 }


