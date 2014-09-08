<?php

	class model_news_items extends model_base
	{	
		public function find_by_news_category_id($news_category_id)
		{
		    return new collection($this->entity_class_name, "SELECT news_items.* FROM `news_items` JOIN news_items_categories 
		    	ON news_items_categories.news_item_id = news_items.id WHERE news_items_categories.news_category_id = '".(int)$news_category_id."' " );			
		}
	
	
	}



