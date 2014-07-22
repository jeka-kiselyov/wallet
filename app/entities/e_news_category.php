<?php


 class news_category extends entity_base
 {
 	function delete()
 	{
		$this->db->delete('news_items_categories', "news_category_id='".(int)$this->id."' ");
		return parent::delete();
 	}

 }

?>