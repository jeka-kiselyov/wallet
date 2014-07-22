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
    $data = $this->payload();
    $user = new User();
    $user->login = $data->login;
    $user->save();

    $user = $this->users->get_by_id($user->id);
    $this->data($this->entity_to_result($user));
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


















