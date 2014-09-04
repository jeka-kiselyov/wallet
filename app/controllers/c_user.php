<?php

class controller_user extends userside_controller
{
  function index()
  {
    if (!$this->user)
     $this->redirect("user", "signin");
  }

  function signin()
  {
    $form_checker = new checker;
    
    if ($this->is_post())
    { 
      $user = $this->users->signin($form_checker->post('username'), $form_checker->post('password'));
      
      if ($user)
      {
        $this->sessions->set_user($user);
        $this->redirect("index");
      } else {
        $form_checker->add_error("Invalid username or password", "username");
      }
    }

    $this->ta('form_checker', $form_checker);
  }
  
  function vkconnect()
  {
    $user = $this->vk->require_auth();
    if ($user)
    {
        $this->sessions->set_user($user);
        $this->redirect("index");
    } else {
        $this->redirect("index");
    }
  }

  function fbconnect()
  {
    $user = $this->facebook->require_auth();
    if ($user)
    {
        $this->sessions->set_user($user);
        $this->redirect("index");
    } else {
        $this->redirect("index");
    }
  }

  function registration()
  {
    if (isset($_POST['register']))
    {
      $username = $_POST['username']; 
      $email = $_POST['email'];
      $password = $_POST['password']; 
      $repeat_password = $_POST['repeat_password'];

      $form_checker = new checker();

      $form_checker->check_post('username', checker_rules::MIN_LENGTH(2), "Username is too short");
      $form_checker->check_post('username', checker_rules::MAX_LENGTH(100), "Username is too long");
      $form_checker->check_post('username', checker_rules::UNIQUE_IN_DB('users', 'login'), "Username is already taken");
      $form_checker->check_post('email', checker_rules::EMAIL(), "Invalid email");
      $form_checker->check_post('email', checker_rules::MAX_LENGTH(100), "Invalid email");
      $form_checker->check_post('email', checker_rules::UNIQUE_IN_DB('users', 'email'), "Email is already in the system");
      $form_checker->check_post('password', checker_rules::EQUAL($repeat_password), "Please check password");
      $form_checker->check_post('password', checker_rules::MIN_LENGTH(6), "Password is too short");

      if ($form_checker->is_good())
      {
        $user = $this->users->register("default", $username, $password, $email);
        if ($this->registry->settings->require_email_confirmation)
         $this->ta("registered", true);
        else
        {
          $this->sessions->set_user($user);
          $this->redirect("index");
        }
      }
      else 
      {
        $this->ta("form_checker", $form_checker);
      }
    }

  }

  function confirm()
  {
  	$code = $this->gp(0, ""); 
    $hash = $this->gp(1, "");

  	$confirmed = true; 
    if (!$code || !$hash) 
      $confirmed = false;
    
  	if ($confirmed)
  	 $confirmed = $this->users->confirm_account($code, $hash);
  	$this->ta("confirmed", $confirmed);
  }

  function newpassword()
  {
    $code = $this->gp(0,"");
    $hash = $this->gp(1, "");
    // if (!$this->users->is_good_restore_hash($code, $hash))
    // {

    // } else {
      
    // }
    $this->ta('code', $code);
    $this->ta('hash', $hash);

    if (!$this->users->is_good_restore_hash($code, $hash))
      $this->ta("invalid_code", true);

    // $form_checker = new checker();

    // if (!$this->users->is_good_restore_hash($code, $hash))
    // {
    //   $form_checker->add_error('Invalid restore password link');
    // } else
    // if (isset($_POST['password']) && isset($_POST['repeat_password']))
    // {
    //   $password = $_POST['password']; 
    //   $repeat_password = $_POST['repeat_password'];

    //   if ($password != $repeat_password)
    //     $form_checker->add_error('Please check password');
    // }

    // if (!$form_checker->is_good())
    // {
    //   $this->ta('form_checker', $form_checker);
    // } else 
    // if (isset($_POST['password']) && isset($_POST['repeat_password']))
    // {
    //   $password_changed = $this->users->create_new_password($code, $hash, $password);
    //   $this->ta('password_changed', $password_changed);
    // }
  }

  function restore()
  {
   if (isset($_POST['email']))
   {
      $form_checker = new checker();
      $user = $this->users->get_by_email($_POST['email']);
      if (!$user)
        $form_checker->add_error('Can\'t find user with this email');

      if (!$form_checker->is_good())
      {
        $this->ta('form_checker', $form_checker);
      } else {
        $success = $this->users->restore_password($user->email);
        $this->ta("email_sent", true);
      }
   }
  }


  function logout()
  {
  	$this->rendered = true;
  	$this->sessions->clear();
  	$this->redirect("index");
  }

}


















