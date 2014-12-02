<?php

class controller_api_wallets extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='2';
  }

  protected function crud_accesses_create($wallet_id)
  {
    $this->require_signed_in();

    $wallet = false;
    $data = $this->payload();
    if ($wallet_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      if ($wallet && $wallet->user_id == $this->user->id)
      {
        if (isset($data->to_email, $data->wallet_id) && $data->wallet_id == $wallet_id)
        {
          /// set up
          $wallet->giveAccess($data->to_email);
        }
        
        $accesses = $wallet->getAccesses();
        $ret = array();
        foreach ($accesses as $a) {
          $ret[] = array("id"=>$a->id, "to_email"=>$a->to_email, "to_user_id"=>$a->to_user_id, "wallet_id"=>$a->wallet_id);
        }
        $this->data($ret);
      }
      else
        $this->not_found();
    }    

  }

  protected function crud_accesses_delete($wallet_id, $access_id)
  {
    $this->require_signed_in();

    $wallet = false;
    if ($wallet_id && $access_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      if ($wallet && $wallet->user_id == $this->user->id)
      {
        $a = $this->wallets_accesses->get_by_id($access_id);
        if ($a && $a->wallet_id == $wallet->id)
        {
          $wallet->removeAccess($a->to_email);
          $this->data(null);
        }
        else
          $this->not_found();
      }
      else
        $this->not_found();
    }        
  }

  protected function crud_accesses_list($wallet_id)
  {
    $this->require_signed_in();

    $wallet = false;
    if ($wallet_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      if ($wallet && $wallet->user_id == $this->user->id)
      {
        $accesses = $wallet->getAccesses();
        $ret = array();
        foreach ($accesses as $a) {
          $ret[] = $a->to_array();
        }
        $this->data($ret);
      }
      else
        $this->not_found();
    }    
  }

  protected function crud_transactions_list($id)
  {
    $this->require_signed_in();
    // @todo add caching

    $to = time();
    $from = time() - date('t')*24*60*60;

    if (isset($_GET['to']) && isset($_GET['from']))
    {
      $to = (int)$_GET['to'];
      $from = (int)$_GET['from'];

      if ($from > $to || max($to, $from) - min($to, $from) > 32*24*60*60)
        $from = $to - 31*60*60;
    }

    $wallet = false;
    if ($id)
    {
      $wallet = $this->wallets->get_by_id($id);
      if ($wallet && ($wallet->user_id == $this->user->id || $wallet->hasAccess($this->user->id)))
      {
        $transactions = $wallet->getTransactions($from, $to);
        $ret = array();
        foreach ($transactions as $transaction) {
          $ret[] = $transaction->to_array();
        }
        
        header("Link: <".'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL']."?to=".$from.">; rel=\"next\", <".'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL']."?from=".$to.">; rel=\"prev\"");
        $this->data($ret);
      }
      else
        $this->not_found();
    }    
  }

  protected function crud_transactions_create($id)
  {
    $this->require_signed_in();

    $wallet = false;
    $data = $this->payload();
    if ($id)
    {
      $wallet = $this->wallets->get_by_id($id);
      if ($wallet && ($wallet->user_id == $this->user->id || $wallet->hasAccess($this->user->id)))
      {
        if (isset($data->subtype) && $data->subtype == 'setup')
        {
          /// set up
          $transaction = $wallet->setTotalTo($data->amount);
        } else {
          $transaction = $wallet->addTransaction($data->amount, $data->description);
        }
        
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
    if ($wallet_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      if ($wallet && ($wallet->user_id == $this->user->id || $wallet->hasAccess($this->user->id)))
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
    if ($wallet_id)
    {
      $wallet = $this->wallets->get_by_id($wallet_id);
      $transaction = $this->transactions->get_by_id($transaction_id);
      if (!$wallet || !$transaction || $transaction->wallet_id != $wallet->id)
        $this->not_found();

      if ($wallet->user_id != $this->user->id && ($transaction->user_id != $this->user->id || !$wallet->hasAccess($this->user->id)))
        $this->has_no_rights();

      $transaction->delete();
      $this->data(null);
    }    
    else
      $this->not_found();
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

    $wallet = $this->user->createWallet($data->name, $data->currency);
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
      $currency = $this->payload('currency','');

      if ($name) $wallet->name = $name;
      if ($status) $wallet->status = $status;
      if ($type) $wallet->type = $type;
      if ($currency) $wallet->currency = $currency;
      
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
    $shared_with = $this->wallets->find_shared_with_user_id($this->user->id);
    $data = array();
    foreach ($wallets as $wallet) 
      $data[] = $this->entity_to_result($wallet, array('origin'=>'mine'));
    foreach ($shared_with as $wallet) 
      $data[] = $this->entity_to_result($wallet, array('origin'=>'shared'));

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


















