<?php

class controller_api_users extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='1';
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

    try {
      $user = $this->users->register($type, $login, $password, $email);
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
    $data = $this->payload();
    if ($id == $data->id)
    {
      if (!$this->admin->is_admin() && $data->id != $this->user->id)
        $this->has_no_rights();

      $user = $this->users->get_by_id($id);
      //@todo: apply changes
      
      $user->save();
    }
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

    if (!($user = $this->users->signin($username, $password)) )
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
      $user = array('id'=>$user['id'], 'login'=>$user['login']);
    } else {
      $user['password'] = '';
      unset($user['password_restore_code']);
      unset($user['confirmation_code']);
      unset($user['auth_code']);
    }

    if ($merge)
      $user = array_merge($user, $merge);

    return $user;
  }
}


















