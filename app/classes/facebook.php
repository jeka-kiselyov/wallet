<?php


  class facebook extends singleton_base 
  {
    function require_auth()
    {
      if (!isset($_GET['code']))
      {
        // 1st step - redirect user to facebook grant access page
        header("location: ".$this->get_site_auth_url($_SERVER["REQUEST_URI"]));
        exit();

      } else {
        // 2nd page - redirected from facebook with ?code=... parameter
        $facebook_user = $this->get_facebook_user_by_auth_code($_GET['code'], $_SERVER["REQUEST_URI"]);

        if ($facebook_user)
        {
          $facebook_user_id = $facebook_user->id;
          $facebook_user_token = $facebook_user->token;
          $facebook_user_email = $facebook_user->email;
          $facebook_user_login = $facebook_user->first_name." ".$facebook_user->last_name;

          // Already connected, return user entity
          $authes = $this->authentications->find_by_third_party_id($facebook_user_id);
          if ($authes)
            foreach ($authes as $auth) 
            {
              if ($auth && $auth->third_party_name == 'facebook')
              {
                $auth->third_party_token = $facebook_user_token;
                $auth->save();

                $user = $this->users->get_by_id($auth->user_id);
                return $user;
              }
            }

          // Not connected with facebook, but has account with same email
          if ($user = $this->users->get_by_email($facebook_user_email))
          {
            $facebook_auth = new authentication;
            $facebook_auth->user_id = $user->id;
            $facebook_auth->third_party_name = 'facebook';
            $facebook_auth->third_party_id = $facebook_user_id;
            $facebook_auth->third_party_token = $facebook_user_token;
            $facebook_auth->save();

            return $user;
          }

          // Not connected yet - create new user:
          $i = 1;
          while ($this->users->get_by_login($facebook_user_login))
          {
            $facebook_user_login = $facebook_user->first_name." ".$facebook_user->last_name.$i;
            $i++;
          }

          $facebook_user_password = md5(time().$facebook_user_login).md5($facebook_user_email.time().rand(0,1000000));
          $user = $this->users->register('facebook', $facebook_user_login, $facebook_user_password, $facebook_user_email);

          if ($user)
          {
            $facebook_auth = new authentication;
            $facebook_auth->user_id = $user->id;
            $facebook_auth->third_party_name = 'facebook';
            $facebook_auth->third_party_id = $facebook_user_id;
            $facebook_auth->third_party_token = $facebook_user_token;
            $facebook_auth->save();

            return $user;
          }
        }
        

        return false;
      }
    }

    function get_site_auth_url($url_path, $scope = 'email')
    {
      $c_url = $this->registry->settings->site_path.$url_path;
      $auth_url = "http://www.facebook.com/dialog/oauth?client_id=".$this->registry->settings->facebook_app_id."&redirect_uri=".urlencode($c_url)."&scope=".$scope;
      return $auth_url;
    }

    function get_facebook_user_by_auth_code($auth_code, $url_path)
    {
     $c_url = $this->registry->settings->site_path.$url_path;
     $token_url = "https://graph.facebook.com/oauth/access_token?client_id=".$this->registry->settings->facebook_app_id."&redirect_uri=".urlencode($c_url)."&client_secret=".$this->registry->settings->facebook_app_secret."&code=".urlencode($auth_code);

     $access_token = @file_get_contents($token_url);

     $graph_url = "https://graph.facebook.com/me?".$access_token;

     $facebook_user = @json_decode(@file_get_contents($graph_url));
     $facebook_user->token = $access_token;

     return $facebook_user;
    }
  }




