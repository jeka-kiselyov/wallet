<?php

class controller_admin_static extends admin_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('pages');
  }
  
  function index()
  {
     $this->redirect("admin_static", "manage");
  }

  function manage()
  {
    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);

    $search = $this->table_helper->proccess_search_parameters("admin_static_pages_");
    $order = $this->table_helper->proccess_order_parameters("admin_static_pages_");

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $page = $this->static_pages->get_by_id($item_id);
      if ($page)
      {
        $page->delete();
      }
    }

    if (!empty($_POST))
     $this->refresh();

    $search_fields = array("title", "body", "slug");
    $joins = array(array('table'=>'i18n_languages', 'field'=>'language_id'));

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count("static_pages", $search, $search_fields, $joins), 20);

    $this->ta("pages", $pagination);
    $items = $this->table_helper->get_items("static_pages", $order, $pagination['cur_offset'], 20, $search, $search_fields, $joins);

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }

  function add()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_static", "manage");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('title', checker_rules::MIN_LENGTH(3), $this->_("Title is too short"));
      $form_checker->check_post('title', checker_rules::MAX_LENGTH(255), $this->_("Title is too long"));

      $form_checker->check_post('slug', checker_rules::MIN_LENGTH(3), $this->_("Slug is too short"));
      $form_checker->check_post('slug', checker_rules::MAX_LENGTH(255), $this->_("Slug is too long"));

      $language_id = 0; 
      if ($is_multilingual && $form_checker->post('language_id')) 
        $language_id = (int)$form_checker->post('language_id');
      else
      {
        $default_language = $this->i18n_languages->get_by_is_default('1');
        if ($default_language)
          $language_id = $default_language->id;
        else
          $language_id = 0;
      }

      if ($form_checker->is_good())
      {
        $with_same_slug_s = $this->static_pages->find_by_slug($form_checker->post('slug'));
        foreach ($with_same_slug_s as $with_same_slug)
        if ($with_same_slug && $with_same_slug->language_id == $language_id)
          $form_checker->add_error($this->_("This slug is already taken by other static page in this language"));
      }

      $form_checker->check_post('body', checker_rules::MIN_LENGTH(3), $this->_("Body is too short"));
      $form_checker->check_post('body', checker_rules::MAX_LENGTH(50000), $this->_("Body is too long. 50kb max"));


      if ($form_checker->is_good())
      {
        $static_page = new static_page();
        $static_page->title = $form_checker->post('title');
        $static_page->slug = $form_checker->post('slug');
        $static_page->body = $form_checker->post('body');
        $static_page->language_id = $language_id;
        $static_page->save();

        $this->redirect("admin_static", "manage");
      }
    }

    $this->ta('form_checker', $form_checker);
  }

  function edit()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_static", "manage");

    $item_id = (int)$this->gp(0,0);
    $static_page = $this->static_pages->get_by_id($item_id);

    if (!$static_page)
      $this->redirect("admin_static", "manage");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('title', checker_rules::MIN_LENGTH(3), $this->_("Title is too short"));
      $form_checker->check_post('title', checker_rules::MAX_LENGTH(255), $this->_("Title is too long"));

      $form_checker->check_post('slug', checker_rules::MIN_LENGTH(3), $this->_("Slug is too short"));
      $form_checker->check_post('slug', checker_rules::MAX_LENGTH(255), $this->_("Slug is too long"));

      $language_id = 0; 
      if ($is_multilingual && $form_checker->post('language_id')) 
        $language_id = (int)$form_checker->post('language_id');
      else
        $language_id = $static_page->language_id;

      if ($form_checker->is_good())
      {
        $with_same_slug_s = $this->static_pages->find_by_slug($form_checker->post('slug'));
        foreach ($with_same_slug_s as $with_same_slug)
        if ($with_same_slug && $with_same_slug->id != $item_id && $with_same_slug->language_id == $language_id)
          $form_checker->add_error($this->_("This slug is already taken by other static page in this language"));
      }

      $form_checker->check_post('body', checker_rules::MIN_LENGTH(3), $this->_("Body is too short"));
      $form_checker->check_post('body', checker_rules::MAX_LENGTH(50000), $this->_("Body is too long. 50kb max"));

      if ($form_checker->is_good())
      {
        $static_page->title = $form_checker->post('title');
        $static_page->slug = $form_checker->post('slug');
        $static_page->body = $form_checker->post('body');
        $static_page->language_id = $language_id;
        $static_page->save();

        $this->redirect("admin_static", "manage");
      }
    }

    $this->ta('static_page', $static_page);
    $this->ta('form_checker', $form_checker);    
  }

}