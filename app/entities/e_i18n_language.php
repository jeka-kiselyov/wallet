<?php

 class i18n_language extends entity_base
 {
 	public $translations = null;
 	private $not_translated_strings = null;

 	public function get_strings()
 	{
 		if (is_null($this->translations))
 			$this->load_translations();

 		if (is_null($this->not_translated_strings))
 			$this->load_not_translated_strings();

 		$ret = array();

 		foreach ($this->not_translated_strings as $string) {
 			if (isset($this->translations[$string]))
 				$ret[$string] = $this->translations[$string];
 		}
 		foreach ($this->translations as $key=>$string) {
 				$ret[$key] = $string;
 		}


 		return $ret;
 	}

 	public function add_translation($string_id, $translation)
 	{
 		$string_id = (int)$string_id;

 		if (is_null($this->translations))
 			$this->load_translations();

 		if (is_null($this->not_translated_strings))
 			$this->load_not_translated_strings();

 		if (isset($this->not_translated_strings[$string_id]) && !isset($this->translations[$this->not_translated_strings[$string_id]]) )
 		{
 			$this->translations[$this->not_translated_strings[$string_id]] = $translation;
 			unset($this->not_translated_strings[$string_id]);

 			$this->db->insert('i18n_translations', array('language_code'=>$this->code, 'language_id'=>$this->id, 'string_id'=>$string_id, 'translation'=>$translation));
 			$this->translated_strings_count = count($this->translations);
 			$this->save();

			$this->cache->set($this->not_translated_strings, 'system_i18n_not_translated_strings_'.$this->code, array('i18n', 'i18n_not_translated', 'i18n_languages', 'i18n_language_'.$this->code));
			$this->cache->set($this->translations, 'system_i18n_table_'.$this->code, array('i18n', 'i18n_languages', 'i18n_language_'.$this->code));
 		} else {
 			throw new Exception("Translation for this string is already in database");
 		}
 	}

 	public function add_string_to_translate($string)
 	{
 		$in_db = $this->db->getone('SELECT id FROM i18n_strings WHERE string = BINARY ?', $string);
 		if ($in_db)
 			return false;
 		else
 		{
 			$this->db->insert('i18n_strings', array('string'=>$string));
 			$string_id = $this->db->insert_id();
 			$this->not_translated_strings[$string_id] = $string;
 			$this->cache->clean_matching_tags(array('i18n_not_translated_'.$this->code));
 			return true;
 		}
 	}

 	public function translate($string)
 	{
 		if ($this->code == 'default')
 			return $string;

 		if (is_null($this->translations))
 			$this->load_translations();

 		if (isset($this->translations[$string]))
 			return $this->translations[$string];
 		else
 			return $string;
 	}

 	public function get_one_string_to_translate()
 	{
 		if (is_null($this->not_translated_strings))
 			$this->load_not_translated_strings();
 		
 		reset($this->not_translated_strings);
		$first_key = key($this->not_translated_strings);

 		if (isset($this->not_translated_strings[$first_key]))
 			return array('string'=>$this->not_translated_strings[$first_key], 'id'=>$first_key);
 		else
 			return false;
 	}

 	public function get_not_translated_strings()
 	{
 		if (is_null($this->not_translated_strings))
 			$this->load_not_translated_strings();

 		return $this->not_translated_strings;
 	}

 	public function get_translations()
 	{
 		if (is_null($this->translations))
 			$this->load_translations();

 		return $this->translations;
 	}

 	private function load_not_translated_strings()
 	{
		if ($this->not_translated_strings = $this->cache->get('system_i18n_not_translated_strings_'.$this->code))
		  return true;

 		$this->not_translated_strings = array();

		$rs = $this->db->execute("SELECT i18n_strings.id, i18n_strings.string FROM i18n_strings LEFT OUTER JOIN i18n_translations ON i18n_strings.id = i18n_translations.string_id
										AND i18n_translations.language_id =  '".$this->id."' WHERE i18n_translations.language_id IS NULL");
		if ($rs)
		  while ($arr = $rs->FetchRow()) 
		  {
		    $this->not_translated_strings[$arr['id']] = $arr['string'];
		  }

		$this->cache->set($this->not_translated_strings, 'system_i18n_not_translated_strings_'.$this->code, array('i18n', 'i18n_not_translated', 'i18n_not_translated_'.$this->code, 'i18n_language_'.$this->code));
		return true;
 	}

	private function load_translations()
	{
		if ($this->code == 'default' || !$this->code)
		  return false;

		if ($this->translations = $this->cache->get('system_i18n_table_'.$this->code))
		  return true;

		$this->translations = array();

		$rs = $this->db->execute("SELECT i18n_translations.translation, i18n_strings.string FROM i18n_translations JOIN i18n_strings ON i18n_translations.string_id = i18n_strings.id 
		                          WHERE i18n_translations.language_id = '".$this->id."'");
		if ($rs)
		  while ($arr = $rs->FetchRow()) 
		  {
		    $this->translations[$arr['string']] = $arr['translation'];
		  }

		$this->cache->set($this->translations, 'system_i18n_table_'.$this->code, array('i18n', 'i18n_translated', 'i18n_translated_'.$this->code ,'i18n_language_'.$this->code));
		return true;
	}

	public function save()
	{
        $this->cache->clean_matching_tags(array('i18n'));
        return parent::save();
	}

 	public function delete()
 	{
 		$tranlations = $this->i18n_translations->get_by_language_id($this->id);
 		$tranlations->delete();
        $this->cache->clean_matching_tags(array('i18n'));

        $this->db->update('users', array('language_id'=>0), 'language_id=?', array($this->id));
		$this->db->delete('news_items', "language_id='".(int)$this->id."' ");
		$this->db->delete('static_pages', "language_id='".(int)$this->id."' ");
		$this->db->delete('mailtemplates', "language_id='".(int)$this->id."' ");
        
 		return parent::delete();
 	}

 }

?>