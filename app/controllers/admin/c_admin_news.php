<?php

class controller_admin_news extends admin_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('news');
  }

  function index()
  {
     $this->redirect("admin_news", "manage");
  }

  function add()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_news", "manage");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $news_categories = $this->news_categories->get_all();

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('title', checker_rules::MIN_LENGTH(3), $this->_("Title is too short"));
      $form_checker->check_post('title', checker_rules::MAX_LENGTH(1000), $this->_("Title is too long"));

      $form_checker->check_post('slug', checker_rules::MIN_LENGTH(3), $this->_("Slug is too short"));
      $form_checker->check_post('slug', checker_rules::MAX_LENGTH(1000), $this->_("Slug is too long"));

      $form_checker->check_post('description', checker_rules::MIN_LENGTH(3), $this->_("Description is too short"));
      $form_checker->check_post('description', checker_rules::MAX_LENGTH(1000), $this->_("Description is too long"));

      $form_checker->check_post('preview_image', checker_rules::MAX_LENGTH(255), $this->_("Preview image filename is too long"));

      $form_checker->check_post('slug', checker_rules::MAX_LENGTH(1000), $this->_("Slug is too long"));

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
        $with_same_slug_s = $this->news_items->find_by_slug($form_checker->post('slug'));
        foreach ($with_same_slug_s as $with_same_slug)
        if ($with_same_slug && $with_same_slug->language_id != $language_id)
          $form_checker->add_error($this->_("This slug is already taken by other news item in this language"));
      }

      $form_checker->check_post('body', checker_rules::MIN_LENGTH(3), $this->_("Body is too short"));
      $form_checker->check_post('body', checker_rules::MAX_LENGTH(50000), $this->_("Body is too long. 50kb max"));

      if ($form_checker->is_good())
      {
        $news_item = new news_item();
        $news_item->title = $form_checker->post('title');
        $news_item->slug = $form_checker->post('slug');
        $news_item->body = $form_checker->post('body');
        $news_item->description = $form_checker->post('description');
        $news_item->language_id = $language_id;
        $news_item->set_categories($form_checker->post('categories'));

        if ($form_checker->post('preview_image'))
          $news_item->preview_image = $form_checker->post('preview_image');

        $news_item->save();

        $this->redirect("admin_news", "manage");
      }
    }

    $this->ta('news_categories', $news_categories);
    $this->ta('form_checker', $form_checker);
  }

  function edit()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_news", "manage");

    $news_categories = $this->news_categories->get_all();

    $item_id = (int)$this->gp(0,0);
    $news_item = $this->news_items->get_by_id($item_id);

    if (!$news_item)
      $this->redirect("admin_news", "manage");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('title', checker_rules::MIN_LENGTH(3), $this->_("Title is too short"));
      $form_checker->check_post('title', checker_rules::MAX_LENGTH(1000), $this->_("Title is too long"));

      $form_checker->check_post('slug', checker_rules::MIN_LENGTH(3), $this->_("Slug is too short"));
      $form_checker->check_post('slug', checker_rules::MAX_LENGTH(1000), $this->_("Slug is too long"));

      $form_checker->check_post('description', checker_rules::MIN_LENGTH(3), $this->_("Description is too short"));
      $form_checker->check_post('description', checker_rules::MAX_LENGTH(1000), $this->_("Description is too long"));

      $form_checker->check_post('preview_image', checker_rules::MAX_LENGTH(255), $this->_("Preview image filename is too long"));

      //$form_checker->check_post('categories', checker_rules::IS_ARRAY(), $this->_("Please select at least one category"));

      $language_id = 0; 
      if ($is_multilingual && $form_checker->post('language_id')) 
        $language_id = (int)$form_checker->post('language_id');
      else
        $language_id = $news_item->language_id;

      if ($form_checker->is_good())
      {
        $with_same_slug_s = $this->news_items->find_by_slug($form_checker->post('slug'));
        foreach ($with_same_slug_s as $with_same_slug)
        if ($with_same_slug && $with_same_slug->id != $item_id && $with_same_slug->language_id != $language_id)
          $form_checker->add_error($this->_("This slug is already taken by other news item"));
      }

      $form_checker->check_post('body', checker_rules::MIN_LENGTH(3), $this->_("Body is too short"));
      $form_checker->check_post('body', checker_rules::MAX_LENGTH(50000), $this->_("Body is too long. 50kb max"));

      if ($form_checker->is_good())
      {
        $news_item->title = $form_checker->post('title');
        $news_item->slug = $form_checker->post('slug');
        $news_item->body = $form_checker->post('body');
        $news_item->description = $form_checker->post('description');
        $news_item->language_id = $language_id;

        if ($form_checker->post('preview_image'))
          $news_item->preview_image = $form_checker->post('preview_image');
        
        $news_item->save();

        $news_item->set_categories($form_checker->post('categories'));

        $this->redirect("admin_news", "manage");
      }
    }

    $this->ta('news_item', $news_item);
    $this->ta('news_categories', $news_categories);
    $this->ta('form_checker', $form_checker);
  }

  function manage()
  {
    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);

    $search = $this->table_helper->proccess_search_parameters("admin_news_items_");
    $order = $this->table_helper->proccess_order_parameters("admin_news_items_");

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $news_item = $this->news_items->get_by_id($item_id);
      if ($news_item)
      {
        $news_item->delete();
      }
    }

    if (!empty($_POST))
     $this->refresh();

    $search_fields = array("title", "body", "slug", "description");
    $joins = array(array('table'=>'i18n_languages', 'field'=>'language_id'));

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count("news_items", $search, $search_fields, $joins), 20);

    $this->ta("pages", $pagination);
    $items = $this->table_helper->get_items("news_items", $order, $pagination['cur_offset'], 20, $search, $search_fields, $joins);

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }

  function categorytranslations()
  {
    $item_id = (int)$this->gp(0,0);
    $news_category = $this->news_categories->get_by_id($item_id);

    if (!$news_category)
      $this->redirect("admin_news", "categories");
    
    $i18n_string = $this->i18n_strings->get_by_string($news_category->name);
    if ($i18n_string)
    {
      $this->redirect('admin_i18n', 'editstring', false, $i18n_string->id);
    } else
      $this->redirect("admin_news", "categories");    
  }

  function categories()
  {
    $search = $this->table_helper->proccess_search_parameters("admin_news_categories_");
    $order = $this->table_helper->proccess_order_parameters("admin_news_categories_");
    
    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $news_category = $this->news_categories->get_by_id($item_id);
      if ($news_category)
      {
        $news_category->delete();
      }
    }

    if (!empty($_POST))
     $this->refresh();

    $search_fields = array("name");
    $joins = array();

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count("news_categories", $search, $search_fields, $joins), 20);

    $this->ta("pages", $pagination);
    $items = $this->table_helper->get_items("news_categories", $order, $pagination['cur_offset'], 20, $search, $search_fields);

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }

  function addcategory()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_news", "categories");  

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('name', checker_rules::MIN_LENGTH(2), $this->_("Name is too short"));
      $form_checker->check_post('name', checker_rules::MAX_LENGTH(255), $this->_("Name is too long"));

      if ($form_checker->is_good())
      {
        $news_category = new news_category();
        $news_category->name = $form_checker->post('name');

        $news_category->save();

        $this->redirect("admin_news", "categories");
      }
    }

    $this->ta("form_checker", $form_checker);
  }

  function editcategory()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_news", "categories");

    $item_id = (int)$this->gp(0,0);
    $news_category = $this->news_categories->get_by_id($item_id);

    if (!$news_category)
      $this->redirect("admin_news", "categories");

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      if ($form_checker->post('cancel'))
        $this->redirect("admin_news", "categories");
      
      $form_checker->check_post('name', checker_rules::MIN_LENGTH(2), $this->_("Name is too short"));
      $form_checker->check_post('name', checker_rules::MAX_LENGTH(255), $this->_("Name is too long"));

      if ($form_checker->is_good())
      {
        $news_category->name = $form_checker->post('name');
        $news_category->save();

        $this->redirect("admin_news", "categories");
      }
    }

    $this->ta('news_category', $news_category);
    $this->ta("form_checker", $form_checker);
  }


}