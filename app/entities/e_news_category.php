<?php


 class news_category extends entity_base
 {

	protected function validation()
	{
		return array(
				array(
					'field'=>'title', 
					'checker' => checker_rules::MIN_LENGTH(1), 
					'error_message' => 'Name is too short'
				),
				array(
					'field'=>'title', 
					'checker' => checker_rules::MAX_LENGTH(255), 
					'error_message' => 'Name is too long'
				),
		);
	}

 	function delete()
 	{
		$this->db->delete('news_items_categories', "news_category_id='".(int)$this->id."' ");
		return parent::delete();
 	}

 }

?>