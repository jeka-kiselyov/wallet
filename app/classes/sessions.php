<?php


  class sessions extends singleton_base 
  {
    /**
     * [set_user description]
     * @param user $user [description]
     */
    function set_user(user $user)
    {
      if (!$user)
        return false;

      $auth_code = md5($user->email.$user->id.$user->login)."_".md5($user->email.$user->id.time().$user->login);

      $authentication = new Authentication();
      $authentication->auth_code = $auth_code;
      $authentication->user_id = $user->id;
      $authentication->user_ip = $_SERVER['REMOTE_ADDR'];
      $authentication->save();

      setcookie("is_logged_in_user", "1", time()+365*24*3600, "/"); // @todo add cookie lifetime to settings
      setcookie("logged_in_user", $auth_code, time()+365*24*3600, "/");

      return $auth_code;
    }

    function get_user()
    {
      if (!isset($_COOKIE['is_logged_in_user']) || !$_COOKIE['is_logged_in_user'] || !isset($_COOKIE['logged_in_user']) || !$_COOKIE['logged_in_user'])
        return false;

      $authentication = $this->authentications->get_by_auth_code($_COOKIE['logged_in_user']);

      if (!$authentication || $authentication->user_ip != $_SERVER['REMOTE_ADDR']) // @todo make ip checking optional
        return false;

      $user = $this->users->get_by_id($authentication->user_id);

      if (!$user)
        return false;

      $user->activity_date = time();
      $user->activity_ip = $_SERVER['REMOTE_ADDR'];
      $is_admin = $user->is_admin;
      $user->save();
      $user->is_admin = $is_admin;
      return $user;
    }

    function clear()
    {
      setcookie("is_logged_in_user", "", time() - 3600, "/");
      setcookie("logged_in_user", "", time() - 3600, "/");

      return true;
    }

  }




