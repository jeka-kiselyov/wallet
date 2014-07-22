<?php

 class i18n_translation extends entity_base
 {
 	protected function after_construct()
 	{
 		if (!$this->id)
 			throw new Exception("Direct creation of tranlation entity is not allowed. Use methods of i18n_language or i18n_string");
	}

 	public function delete()
 	{
 		$language = $this->i18n_languages->get_by_id($this->language_id);
 		$language->translated_strings_count--;
 		$language->save();

        $this->cache->clean_matching_tags(array('i18n'));
 		return parent::delete();
 	}
 }

?>