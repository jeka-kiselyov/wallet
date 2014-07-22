<?php

 class i18n_string extends entity_base
 {
 	public function translate_to($language_id)
 	{
 		$language_id = (int)$language_id;
 		$in_db_translation = $this->db->getone('SELECT translation FROM i18n_translations WHERE string_id = ? AND language_id = ?', array($this->id, $language_id));
 		if ($in_db_translation)
 			return $in_db_translation;
 		else
 			return $this->string;
 	}

 	public function update_translation($language_id, $translation)
 	{
 		$language_id = (int)$language_id;

        $this->cache->clean_matching_tags(array('i18n'));
 		$in_db = $this->db->getone('SELECT translation FROM i18n_translations WHERE string_id = ? AND language_id = ?', array($this->id, $language_id));
 				var_dump($in_db); 
 		if ($in_db)
 		{
 			if ($translation)
 			{
	 			$this->db->update('i18n_translations', array('translation'=>$translation), 'string_id = ? AND language_id =?', array($this->id, $language_id));
 			} else {
 				$this->db->delete('i18n_translations', 'string_id = ? AND language_id =?', array($this->id, $language_id));
		 		$language = $this->i18n_languages->get_by_id($language_id);
		 		$language->translated_strings_count--;
		 		$language->save();
 			}
 			return true;
 		} else {
 			if ($translation)
 			{
	 			$this->db->insert('i18n_translations', array('string_id'=>$this->id, 'language_id'=>$language_id, 'translation'=>$translation));
		 		$language = $this->i18n_languages->get_by_id($language_id);
		 		$language->translated_strings_count++;
		 		$language->save();
 			}
 		}
 	}

 	public function delete()
 	{
 		$tranlations = $this->i18n_translations->get_by_string_id($this->id);
 		$tranlations->delete();
        $this->cache->clean_matching_tags(array('i18n'));
 		return parent::delete();
 	}

 	public function save()
 	{
 		return parent::save();
 	}
 }

?>