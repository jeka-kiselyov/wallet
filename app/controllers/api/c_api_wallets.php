<?php

class controller_api_wallets extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='2';
  }

  protected function crud_read($id)
  {
    $this->require_signed_in();

    $wallet = false;
    if ($id)
    {
      $wallet = $this->wallets->get_by_id($id);
      if ($wallet)
        $this->data($this->entity_to_result($wallet));
      else
        $this->not_found();
    }
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
    $this->require_signed_in();

    $wallets = $this->wallets->find_by_user_id($this->user->id);
    $data = array();
    foreach ($wallets as $wallet) 
      $data[] = $this->entity_to_result($wallet);

    $this->data($data);
  }

  private function entity_to_result($wallet, $merge = false)
  {
    $wallet = $wallet->to_array();

    if ($merge)
      $wallet = array_merge($wallet, $merge);

    return $wallet;
  }
}


















