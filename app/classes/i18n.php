<?php

class i18n extends singleton_base 
{
  private $current_language_code = 'default';
  private $current_language = null;

  private $gather_strings = false;

  function __construct() 
  {
    parent::__construct();

    if ($this->registry->settings->i18n_gather_strings)
    {
      $this->gather_strings = true;
    }

    if ($this->gather_strings)
    {
      $this->current_language = $this->i18n_languages->get_by_code('default');
    }
  }

  public function get_current_language()
  {
    return $this->current_language;
  }

  public function get_current_language_id()
  {
    if ($this->current_language)
      return $this->current_language->id;
    else
      return 0;
  }

  public function get_default_language_id()
  {
    if ($language = $this->i18n_languages->get_by_code('default'))
      return $language->id;
    else
      return 0;
  }

  public function set_language_by_id($language_id = 0)
  {
    if (!$language_id)
      return false;

    $this->current_language = $this->i18n_languages->get_by_id($language_id);
    if ($this->current_language)
      return true;
    else
    {
      $this->set_language();
    }
  }

  public function set_language($language_code = 'default')
  {
    $this->current_language = $this->i18n_languages->get_by_code($language_code);
    if ($this->current_language)
      return true;
    else
    {
      if ($language_code == 'default')
      {
        $this->current_language = null;
        return false;
      }
      else {
        throw new InvalidArgumentException("Invalid language code. Input: ".$language_code);
        return false; 
      } 
    }
  }

  public function detect_language()
  {
    $langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
    $langcode = (!empty($langcode)) ? explode(";", $langcode) : $langcode;
    $langcode = (!empty($langcode['0'])) ? explode(",", $langcode['0']) : $langcode;
    $langcode = (!empty($langcode['0'])) ? explode("-", $langcode['0']) : $langcode;
    if ($langcode && isset($langcode[0]))
    {
      try {
        $this->set_language($langcode[0]);
      } catch (Exception $e)
      {
        $this->set_language();
      }
    }
  }

  public function translate($string)
  {
    if ($this->gather_strings)
      $this->add_string_to_translation_list($string);

    if ($this->current_language)
      return $this->current_language->translate($string);
    else
      return $string;
  }

  public function add_string_to_translation_list($string)
  {
    if ($this->current_language)
    {
      $tranlated = $this->current_language->get_translations();
      if (isset($tranlated[$string]))
        return false;
      $not_translated = $this->current_language->get_not_translated_strings();
      if (!in_array($string, $not_translated))
      {
        // add 
        $this->current_language->add_string_to_translate($string);
      } else
        return false;
    }

    return false;
  }
}




