<?php

class controller_api_news_categories extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='5';
  }

  protected function crud_news_items_list($news_category_id)
  {
    $news_category_id = (int)$news_category_id;
    $page = 1; if (isset($_GET['page'])) $page = (int)$_GET['page']; if ($page < 1) $page = 1;
    $per_page = 25; if (isset($_GET['per_page'])) $per_page = (int)$_GET['per_page']; if ($per_page < 1) $per_page = 1;

    $items = $this->news_items->find_by_news_category_id($news_category_id);
    $total = $items->get_total_count();
    $items->set_order_by('time_created', 'DESC');
    $items->set_limit($per_page*($page - 1), $per_page);

    $data = array();
    foreach ($items as $item) 
      $data[] = $this->entity_to_result($item);

    $this->data(array(array('total_entries'=>$total), $data));    
  }

  protected function crud_read($id)
  {
    $item = false;
    if ($id)
    {
      $item = $this->news_categories->get_by_id($id);
    }

    if ($item)
      $this->data($this->entity_to_result($item));
    else
      $this->not_found();
  }

  protected function crud_list()
  {
    $items = $this->news_categories->get_all();

    $data = array();
    foreach ($items as $item) 
      $data[] = $this->entity_to_result($item);

    $this->data($data);
  }

  private function entity_to_result($item, $merge = false)
  {
    $item = $item->to_array();

    if ($merge)
      $item = array_merge($item, $merge);

    return $item;
  }
}


















