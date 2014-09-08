<?php

class controller_api_news_categories extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='5';
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


















