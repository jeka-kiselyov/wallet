<?php

class controller_admin_static extends admin_controller
{
  protected $db_table_name = 'static_pages';
  protected $search_fields = array("title", "body", "slug");

  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('pages');
  }
  
  function index()
  {
     $this->redirect("admin_static", "manage");
  }

}