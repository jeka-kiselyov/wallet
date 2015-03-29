<?php

class controller_api_users extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='1';
  }

  protected function crud_wallets_list($user_id)
  {
    $this->require_signed_in();

    if ($user_id != $this->user->id)
      $this->has_no_rights();

    $wallets = $this->wallets->find_by_user_id($this->user->id);
    $shared_with = $this->wallets->find_shared_with_user_id($this->user->id);
    $data = array();
    foreach ($wallets as $wallet) 
      $data[] = array_merge($wallet->to_array(), array('origin'=>'mine'));
    foreach ($shared_with as $wallet) 
      $data[] = array_merge($wallet->to_array(), array('origin'=>'shared'));

    $this->data($data);
  }

  protected function crud_read($id)
  {
    $user = false;
    if ($id)
    {
      $user = $this->users->get_by_id($id);
      if ($user)
        $this->data($this->entity_to_result($user));
      else
        $this->not_found();
    }
  }

  protected function crud_create()
  {
    $login = $this->payload('login','');
    $type = $this->payload('type', 'default');
    $password = $this->payload('password', '');
    $email = $this->payload('email', '');

    $demo = false;
    if ($email == 'demo@demo.com')
    {
      //// demo user
      $login = time()."_".rand(0,time());
      $email = $login."@example.com";
      $password = md5($password);
      $demo = true;
    }

    try {
      $user = $this->users->register($type, $login, $password, $email);
      
      if ($demo)
      {
        $user->is_demo = 1;
        $user->confirmation_code = '';
        $user->save();

        $this->demo->fill_demo_account($user->id);
      }

      if (!$user->confirmation_code)
      {
        /// can sign in now
        $auth_code = $this->sessions->set_user($user);
        $this->data($this->entity_to_result($user, array('auth_code'=>$auth_code)));
      } else {
        $this->data($this->entity_to_result($user));        
      }
    } catch (entityvalidation_exception $e) {
      $this->error(1, $e->get_error_messages());
    }
  }

  protected function crud_update($id)
  {
    $login = $this->payload('login','');
    $password = $this->payload('password', '');
    $email = $this->payload('email', '');
    $is_demo = $this->payload('is_demo', false);
    $current_password = $this->payload('current_password', '');

    $data = $this->payload();
    if ($id == $data->id)
    {
      if (!$this->admin->is_admin() && $data->id != $this->user->id)
        $this->has_no_rights();

      $user = $this->users->get_by_id($id);
      if (!$user->is_demo && $user->password != md5($current_password.$this->users->password_salt))
        $this->has_no_rights();
        
      //@todo: apply changes

      if ($login)
        $user->login = $login;

      if ($email)
        $user->email = $email;

      if ($password)
        $user->password = md5($password.$this->users->password_salt);

      $user->is_demo = $is_demo;
      
      try {
        $user->save();
      } 
      catch (entityvalidation_exception $e) {
        $this->error(1, $e->get_error_messages());
      }
    }

    $user = $this->users->get_by_id($id);
    $this->data($this->entity_to_result($user));
  }

  protected function crud_delete($id)
  {
    $data = $this->payload();
    if ($id == $data->id)
    {
      if (!$this->admin->is_admin() && $data->id != $this->user->id)
        $this->has_no_rights();

      $user = $this->users->get_by_id($id);
      //@todo: apply changes
      //
      //$user->delete();
    }
    $this->data(null);
  }

  protected function crud_list()
  {
    $users = $this->users->get_all();
    $data = array();
    foreach ($users as $user) 
      $data[] = $this->entity_to_result($user);

    $this->data($data);
  }

  public function newpassword()
  {
    $password = $this->param('password');
    $code = $this->param('code');
    $hash = $this->param('hash');

    if (!$this->users->is_good_restore_hash($code, $hash))
      $this->error(4, "Invalid password restore code");
    else
    {
      $this->users->create_new_password($code, $hash, $password);
      $this->data(array('success'=>'success'));      
    }
  }

  public function restore()
  {
    $email = $this->param('email');
    $user = $this->users->get_by_email($email);
    if (!$user)
    {
      $this->error(2, "Can't find user with this email");
    } else
    {
      $success = $this->users->restore_password($user->email);
      if ($success)
        $this->data(array('success'=>'success'));
      else
        $this->error(3, "Something is wrong");
    }
  }

  public function signin()
  {
    $username = $this->param('username');
    $password = $this->param('password');

    if (!($user = $this->users->signin($username, $password)) || !$user->id)
    {
      $this->error(1, "Invalid credentials");
    } else
    {
      $auth_code = $this->sessions->set_user($user);
      $this->data($this->entity_to_result($user, array('auth_code'=>$auth_code)));
    }
  }

  public function signout()
  {
    $this->sessions->clear();
    $this->data(null);
  }

  private function entity_to_result($user, $merge = false)
  {
    $user = $user->to_array();

    if (!$this->user || ($this->user->id != $user['id']) ) /// Don't show special fields for other users
    {
      $user = array('id'=>$user['id'], 'login'=>$user['login'], 'is_demo'=>(bool)$user['is_demo']);
    } else {
      $user['password'] = '';
      unset($user['password_restore_code']);
      unset($user['confirmation_code']);
      unset($user['auth_code']);
    }

    $user['is_demo'] = (bool)$user['is_demo'];

    if ($merge)
      $user = array_merge($user, $merge);

    return $user;
  }
}


















