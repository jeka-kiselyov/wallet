<?php

class controller_admin_news extends admin_controller
{
  protected $db_table_name = 'news_items';
  protected $search_fields = array('title', 'body', 'description', 'slug');

  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('news');
  }

  function index()
  {
     $this->redirect("admin_news", "manage");
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
    $search = $this->table_helper->proccess_search_parameters($this->get_current_class_route()."categories");
    $order = $this->table_helper->proccess_order_parameters($this->get_current_class_route()."categories");
    $model = $this->{'news_categories'};

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $item = $model->get_by_id($item_id);
      if ($item)
      {
        $item->delete();
      }
    }

    if (!empty($_POST))
      $this->refresh();

    $search_fields = array('name');

    $joins = array();

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count('news_categories', $search, $search_fields, $joins), 20);

    $this->ta("pages", $pagination);
      $items = new collection($this->db_entity_name, $this->table_helper->get_items_query('news_categories', $order, $pagination['cur_offset'], 20, $search, $search_fields, $joins));

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }

  function addcategory()
  {
    if (isset($_POST['cancel']))
      $this->redirect($this->get_current_class_route(), "categories");

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $item = new news_category;
      $item->fill_from_form_checker($form_checker);

      $form_checker->save_entity($item);

      if ($form_checker->is_entity_saved())
        $this->redirect($this->get_current_class_route(), "categories");        
    }

    $this->ta('form_checker', $form_checker);
  }

  function editcategory()
  {
    $item_id = (int)$this->gp(0,0);
    $model = $this->{'news_categories'};

    if (isset($_POST['cancel']) || !$item_id || !($item = $model->get_by_id($item_id)))
      $this->redirect($this->get_current_class_route(), "categories");

    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $item->fill_from_form_checker($form_checker);

      $form_checker->save_entity($item);
      if ($form_checker->is_entity_saved())
        $this->redirect($this->get_current_class_route(), "categories");        
    }

    $this->ta('item', $item);
    $this->ta('form_checker', $form_checker);
  }


}