<?php

class controller_admin_i18n extends admin_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('i18n');
  }

  function index()
  {
      $this->redirect("admin_i18n", "languages");
  }

  function editstring()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_i18n", "strings");

    $item_id = (int)$this->gp(0,0);
    $i18n_string = $this->i18n_strings->get_by_id($item_id);

    if (!$i18n_string)
      $this->redirect("admin_i18n", "strings");

    $languages = $this->i18n_languages->get_all();
    $form_translations = array();
    foreach ($languages as $language) 
    if ($language->is_default == 0)
    {
      $translation = $i18n_string->translate_to($language->id);
      $form_translations[] = array('language_id'=>$language->id, 'translation'=>$translation, 'language_name'=>$language->name);
    }

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('string', checker_rules::MIN_LENGTH(1), $this->_("Original string is too short"));
      $form_checker->check_post('string', checker_rules::MAX_LENGTH(500), $this->_("Original string is too long"));

      foreach ($form_translations as $key=>$t)
      {
        $form_checker->check_post('translation_'.$t['language_id'], checker_rules::MAX_LENGTH(500), $this->_("Translated string is too long"));
        $form_translations[$key]['translation'] = $form_checker->post('translation_'.$t['language_id']);
      }

      if ($form_checker->is_good())
      {
        $with_same_string = $this->i18n_strings->get_by_string($form_checker->post('string'));
        if ($with_same_string && $with_same_string->id != $item_id && $with_same_string->string == $form_checker->post('string'))
          $form_checker->add_error($this->_("This original string is already in database"));
      }

      if ($form_checker->is_good())
      {
        $i18n_string->string = $form_checker->post('string');
        foreach ($form_translations as $t)
        {
          $i18n_string->update_translation($t['language_id'], $t['translation']);
        }
        $i18n_string->save();
        $this->redirect("admin_i18n", "strings");
      }
    }

    $this->ta('item', $i18n_string);
    $this->ta('form_translations', $form_translations);
    $this->ta('form_checker', $form_checker); 
  }

  function strings()
  {
    $search = $this->table_helper->proccess_search_parameters("admin_i18n_strings_");
    $order = $this->table_helper->proccess_order_parameters("admin_i18n_strings_");

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $i18n_string = $this->i18n_strings->get_by_id($item_id);
      if ($i18n_string)
      {
        $i18n_string->delete();
        $this->cache->clean_matching_tags(array('i18n'));
      }
    }

    if (!empty($_POST))
     $this->refresh();
    
    $search_fields = array("string");
    $joins = array();

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count("i18n_strings", $search, $search_fields, $joins), 100);

    $this->ta("pages", $pagination);
    $items = $this->table_helper->get_items("i18n_strings", $order, $pagination['cur_offset'], 100, $search, $search_fields);

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }

  function translate()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_i18n", "languages");

    $language_id = (int)$this->gp(0,0);
    $i18n_language = $this->i18n_languages->get_by_id($language_id);

    if (!$i18n_language)
      $this->redirect("admin_i18n", "languages");

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('string_id', checker_rules::IS_INTEGER(), $this->_("Translation is empty"));
      $form_checker->check_post('translation', checker_rules::MIN_LENGTH(1), $this->_("Translation is empty"));
      $form_checker->check_post('translation', checker_rules::MAX_LENGTH(500), $this->_("Translation is too long for database"));

      if ($form_checker->is_good())
      {
        $i18n_language->add_translation($form_checker->post('string_id'), $form_checker->post('translation'));
        $this->redirect("admin_i18n", "translate", false, $i18n_language->id);
      }
    }

    $item_to_tranlate = $i18n_language->get_one_string_to_translate();

    if (!$item_to_tranlate && !$this->is_post())
      $this->redirect("admin_i18n", "languages");

    $this->ta('language', $i18n_language);
    $this->ta('item_to_tranlate', $item_to_tranlate);
    $this->ta('form_checker', $form_checker); 
  }

  function editlanguage()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_i18n", "languages");

    $item_id = (int)$this->gp(0,0);
    $i18n_language = $this->i18n_languages->get_by_id($item_id);

    if (!$i18n_language)
      $this->redirect("admin_i18n", "languages");

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('name', checker_rules::MIN_LENGTH(3), $this->_("Name is too short"));
      $form_checker->check_post('name', checker_rules::MAX_LENGTH(255), $this->_("Name is too long"));

      if ($i18n_language->is_default == 0)
      {
	      $form_checker->check_post('code', checker_rules::MIN_LENGTH(1), $this->_("Code is too short"));
	      $form_checker->check_post('code', checker_rules::MAX_LENGTH(20), $this->_("Code is too long"));
      }

      if ($form_checker->is_good())
      {
        $with_same_code = $this->i18n_languages->get_by_code($form_checker->post('code'));
        if ($with_same_code && $with_same_code->id != $item_id)
          $form_checker->add_error($this->_("This code is already taken by other language"));

        $with_same_name = $this->i18n_languages->get_by_name($form_checker->post('name'));
        if ($with_same_name && $with_same_name->id != $item_id)
          $form_checker->add_error($this->_("This name is already taken by other language"));
      }

      if ($form_checker->is_good())
      {
        $i18n_language->name = $form_checker->post('name');
        if ($i18n_language->is_default == 0)
	        $i18n_language->code = $form_checker->post('code');
        $i18n_language->save();

        $this->redirect("admin_i18n", "languages");
      }
    }

    $this->ta('item', $i18n_language);
    $this->ta('form_checker', $form_checker); 
  }

  function addlanguage()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_i18n", "languages");

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('name', checker_rules::MIN_LENGTH(3), $this->_("Name is too short"));
      $form_checker->check_post('name', checker_rules::MAX_LENGTH(255), $this->_("Name is too long"));

      $form_checker->check_post('code', checker_rules::MIN_LENGTH(1), $this->_("Code is too short"));
      $form_checker->check_post('code', checker_rules::MAX_LENGTH(20), $this->_("Code is too long"));

      if ($form_checker->is_good())
      {
        $with_same_code = $this->i18n_languages->get_by_code($form_checker->post('code'));
        if ($with_same_code)
          $form_checker->add_error($this->_("This code is already taken by other language"));

        $with_same_name = $this->i18n_languages->get_by_name($form_checker->post('name'));
        if ($with_same_name)
          $form_checker->add_error($this->_("This name is already taken by other language"));
      }

      if ($form_checker->is_good())
      {
        $i18n_language = new i18n_language();
        $i18n_language->name = $form_checker->post('name');
        $i18n_language->code = $form_checker->post('code');
        $i18n_language->is_default = 0;

        $i18n_language->save();

        $this->redirect("admin_i18n", "languages");
      }
    }

    $this->ta('form_checker', $form_checker);

  }

  function languages()
  {
    $total_strings_count = $this->i18n_strings->get_count();
    $this->ta('total_strings_count', $total_strings_count);

    $search = $this->table_helper->proccess_search_parameters("admin_i18n_languages_");
    $order = $this->table_helper->proccess_order_parameters("admin_i18n_languages_");

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $i18n_language = $this->i18n_languages->get_by_id($item_id);
      if ($i18n_language && $i18n_language->is_default == 0)
      {
        $i18n_language->delete();
        $this->cache->clean_matching_tags(array('i18n'));
      }
    }

    if (!empty($_POST))
     $this->refresh();

    
    $search_fields = array("name", "code");
    $joins = array();

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count("i18n_languages", $search, $search_fields, $joins), 20);

    $this->ta("pages", $pagination);
    $items = $this->table_helper->get_items("i18n_languages", $order, $pagination['cur_offset'], 20, $search, $search_fields);

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }
}