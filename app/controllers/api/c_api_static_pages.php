<?php

class controller_api_static_pages extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='3';
  }

  protected function crud_read($id)
  {
    $item = false;
    if ($id)
    {
      $item = $this->static_pages->get_by_id($id);
    }

    if ($item)
      $this->data($this->entity_to_result($item));
    else
      $this->not_found();
  }

  public function by_slug()
  {
    $slug = $this->gp(0,'');
    $item = $this->static_pages->get_by_slug($slug);

    if ($item)
      $this->data($this->entity_to_result($item));
    else
      $this->not_found();
  }

  protected function crud_create()
  {
    $this->require_signed_in();

    $data = $this->payload();

    $wallet = $this->user->createWallet($data->name);
    $wallet = $this->wallets->get_by_id($wallet->id);
    $this->data($this->entity_to_result($wallet));
  }

  protected function crud_update($id)
  {
    $this->require_signed_in();

    $data = $this->payload();
    if ($id == $data->id)
    {
      $wallet = $this->wallets->get_by_id($id);
      if ($wallet->user_id != $this->user->id)
        $this->has_no_rights();
      //@todo: apply changes
      
      $wallet->save();
    }
    $this->data($this->entity_to_result($wallet));
  }

  protected function crud_delete($id)
  {
    $this->require_signed_in();

    $data = $this->payload();
    $wallet = $this->wallets->get_by_id($id);
    if ($wallet->user_id != $this->user->id)
      $this->has_no_rights();
      
    $wallet->delete();
    $this->data(null);
  }

  protected function crud_list()
  {
    $items = $this->static_pages->get_all();
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


















