<?php


 class news_item extends entity_base
 {
 	private $joined_news_categories;

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
		return parent::save();
	}


 }

?>