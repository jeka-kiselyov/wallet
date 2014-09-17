<?php

class controller_api_wallets extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='2';
  }

  protected function crud_transactions_list($id)
  {
    $this->require_signed_in();
    // @todo add caching

    $wallet = false;
    if ($id)
    {
      $wallet = $this->wallets->get_by_id($id);
      if ($wallet && $wallet->user_id = $this->user->id)
      {
        $transactions = $wallet->getTransactions();
        $ret = array();
        foreach ($transactions as $transaction) {
          $ret[] = $transaction->to_array();
        }
        $this->data($ret);
      }
      else
        $this->not_found();
    }    
  }

  protected function crud_transactions_create($id)
  {
    $this->require_signed_in();
    // @todo add caching

    $wallet = false;
    $data = $this->payload();
    if ($id)
    {
      $wallet = $this->wallets->get_by_id($id);
      if ($wallet && $wallet->user_id = $this->user->id)
      {
        $transaction = $wallet->addTransaction($data->amount, $data->description);
        $this->data($transaction->to_array());
      }
      else
        $this->not_found();
    }    
  }


  protected function crud_transactions_read($wallet_id, $transaction_id)
  {
    $this->require_signed_in();

    $wallet = false;
    if ($wallet_id && $transaction_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      if ($wallet && $wallet->user_id = $this->user->id)
      {
        $transaction = $this->transactions->get_by_id($transaction_id);
        if ($transaction && $transaction->wallet_id == $wallet->id)
        {
          $this->data($transaction->to_array());
        } else
          $this->not_found();
      }
      else
        $this->not_found();
    }    
  }

  protected function crud_transactions_delete($wallet_id, $transaction_id)
  {
    $this->require_signed_in();

    $wallet = false;
    if ($wallet_id && $transaction_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      if ($wallet && $wallet->user_id = $this->user->id)
      {
        $transaction = $this->transactions->get_by_id($transaction_id);
        if ($transaction && $transaction->wallet_id == $wallet->id)
        {
          $transaction->delete();
          $this->data(null);
        } else
          $this->not_found();
      }
      else
        $this->not_found();
    }    
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


      $name = $this->payload('name','');
      $status = $this->payload('status','');
      $type = $this->payload('type','');

      if ($name) $wallet->name = $name;
      if ($status) $wallet->status = $status;
      if ($type) $wallet->type = $type;
      
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


















