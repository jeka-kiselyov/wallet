<?php

class controller_admin_login extends admin_controller
{

  function index()
  {
  	if (isset($_POST['username']) && isset($_POST['password']))
  	{
      $user = $this->users->signin($_POST['username'], $_POST['password']);
  		if ($user && $user->is_admin)
      {
        $this->sessions->set_user($user);
        $this->redirect("admin_index");
      }
      else 
      {
        $form_checker = new checker;
        $form_checker->add_error("Invalid username or password", "username");
        $this->ta("form_checker", $form_checker);
      }
  	}
  }

  function logout()
  {
  	$this->sessions->clear();
    $this->redirect("admin_index");
  }

}