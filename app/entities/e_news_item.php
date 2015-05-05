<?php


 class news_item extends entity_base
 {
 	private $joined_news_categories;

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
					'checker' => checker_rules::UNIQUE_IN_DB('news_items', 'slug', $this->id), 
					'error_message' => 'This slug is already taken by another news item'
				),
				array(
					'field'=>'description', 
					'checker' => checker_rules::MAX_LENGTH(1000), 
					'error_message' => 'Description is too long'
				),
				array(
					'field'=>'preview_image', 
					'checker' => checker_rules::MAX_LENGTH(255), 
					'error_message' => 'Preview image filename is too long'
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

 	public function after_construct()
 	{
 		$this->update_joined();
 	}

 	private function update_joined()
 	{
		if (!$this->id)
		{
			$this->joined_news_categories = array();
		} else {
			$this->joined_news_categories = new collection("news_category", "SELECT news_categories.* FROM news_categories JOIN news_items_categories ON news_categories.id = news_items_categories.news_category_id WHERE news_items_categories.news_item_id = '".(int)$this->id."' ");
		}
 	}

 	public function get_categories()
 	{
 		return $this->joined_news_categories;
 	}

 	public function set_categories($ids_array)
 	{
 		if (!$this->id)
 		{
 			$this->save();
 			$this->update_joined();
 		}

 		foreach ($ids_array as $id) 
 		{
 			$id = (int)$id;
 			$this->add_to_category($id);
 		}

 		foreach ($this->joined_news_categories as $news_category) 
 		{
 			if (!in_array($news_category->id, $ids_array))
 				$this->remove_from_category($news_category->id);
 		}
 	}

 	public function is_in_category($category_id)
 	{
 		if (!$this->id)
 			return false;
 		
 		$category_id = (int)$category_id;

		$category = $this->joined_news_categories->get_entity_by_id($category_id);
 		
 		if ($category)
 			return true;
 		else
 			return false;
 	}

 	public function add_to_category($category_id)
 	{
 		if (!$this->id)
 			return false;

 		$category_id = (int)$category_id;

 		if ($this->is_in_category($category_id))
 			return false;

 		$category_to_add = $this->news_categories->get_by_id($category_id);
 		if ($category_to_add)
 		{
	 		$news_items_category = new news_items_category;
	 		$news_items_category->news_category_id = $category_to_add->id;
	 		$news_items_category->news_item_id = $this->id;
	 		$news_items_category->save();

	 		$this->joined_news_categories->add_entity($category_to_add);
 		} else
 		return false;
 	}

 	public function remove_from_category($category_id)
 	{
 		if (!$this->id)
 			return false;

 		$category_id = (int)$category_id;

		$category_to_remove = $this->joined_news_categories->get_entity_by_id($category_id);
		if ($category_to_remove)
		{
			$this->db->delete('news_items_categories', "news_item_id='".(int)$this->id."' AND news_category_id = '".(int)$category_to_remove->id."'");
			$this->joined_news_categories->remove_entity_with_id($category_id);
			return true;
		} else
		return false;
 	}

 	function delete()
 	{
		$this->db->delete('news_items_categories', "news_item_id='".(int)$this->id."' ");
		return parent::delete();
 	}

	function save()
	{
		if (!$this->id)
		{
			$this->fields['time_created'] = time();
		}
		$this->fields['time_updated'] = time();
		$success = parent::save();

		if ($success)
			$this->update_joined();

		return $success;
	}


 }

?>