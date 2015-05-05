<?php

  class model_users extends model_base {

    var $password_salt = "password_salt";

    function get_password_salt()
    {
      return $this->password_salt;
    }

    function register($type, $login, $password, $email)
    {
      if ($type != "facebook" && $type != "ios" && $type != "android" && $type != 'vk') 
      {
        $type = "default";
      }
      
      $confirmation_code = "";

      if ($type == "default" && $this->registry->settings->require_email_confirmation)
        $confirmation_code = md5($password.time().$this->password_salt);

      $user = new user();
      $user->type = $type;
      $user->email = $email;
      $user->login = $login;
      $user->password = md5($password.$this->password_salt); /// @todo add some user specific piece to salt
      $user->registration_date = time();
      $user->registration_ip = $_SERVER['REMOTE_ADDR'];
      $user->confirmation_code = $confirmation_code;
      $user->is_banned = false;

      $user->save();

      if ($type == "default" && $this->registry->settings->require_email_confirmation)
      {
        $user->confirmation_code = md5($password.time().$this->password_salt);
        $user->save();
        $this->mailer->send_to_user_id($user->id, "confirmation_email", array("confirmation_code"=>$confirmation_code, "confirmation_hash"=>md5($user->id)));
      }

      return $user;
    }

    function signin($login, $password)
    {
      $query = "SELECT * FROM users WHERE (login = '".$this->db->escape($login)."' OR email = '".$this->db->escape($login)."') 
                AND password = '".$this->db->escape(md5($password.$this->password_salt))."' AND confirmation_code = ''";
      $user = new user( $this->db->getrow($query) );

      if (!$user || $user->is_banned)
      {
        return false;      
      }
      
      $user->activity_date = time();
      $user->activity_ip = $_SERVER['REMOTE_ADDR'];
      $user->save();
      
      return $user;
    }

    function restore_password($email)
    {
      if (!$email)
        return false;
      $user = $this->get_by_email($email);
      if (!$user)
        return false;

      $restore_code = md5(time().rand(0,100000).$user->id);
      $restore_hash = md5($user->id.$restore_code);

      $user->password_restore_code = $restore_code;
      $user->save();

      $this->mailer->send_to_user_id($user->id, 
                                     "user_restore_password", 
                                     array("restore_code"=>$restore_code, "restore_hash"=>$restore_hash)
                                    );
      return true;
    }


    function is_good_restore_hash($code, $hash)
    {
      $user = $this->get_by_password_restore_code($code);
      if (!$user)
        return false;

      if (md5($user->id.$code) != $hash)
        return false;

      return true;
    }

    function create_new_password($code, $hash, $password)
    {
      $user = $this->get_by_password_restore_code($code);
      $user->password = md5($password.$this->password_salt);
      $user->restore_code = '';
      $user->restore_hash = '';
      $user->save();
      return true;
    }

    function confirm_account($confirmation_code, $confirmation_hash)
    {
      $user = $this->get_by_confirmation_code($confirmation_code);
      if (!$user)
        return false;

      if ($confirmation_hash == md5($user->id))
      {
        $user->confirmation_code = '';
        $user->save();
        return true;
      }
    }

  }


?>