<?php


  class vk extends singleton_base 
  {
    function require_auth()
    {
      if (!isset($_GET['code']))
      {
        // 1st step - redirect user to facebook grant access page
        header("location: ".$this->get_site_auth_url($_SERVER["REQUEST_URI"]));
        exit();

      } else {
        $vk_user = $this->get_vk_user_by_auth_code($_GET['code'], str_replace("?code=".$_GET['code'], "", $_SERVER["REQUEST_URI"]) );

        if ($vk_user)
        {
          $vk_user_id = $vk_user->uid;
          $vk_user_token = $vk_user->token;
          $vk_user_login = $vk_user->first_name." ".$vk_user->last_name;
          $vk_user_email = $vk_user_id."@vk.com";

          // Already connected, return user entity
          $authes = $this->authentications->find_by_third_party_id($vk_user_id);
          if ($authes)
            foreach ($authes as $auth) 
            {
              if ($auth && $auth->third_party_name == 'vk')
              {
                $auth->third_party_token = $vk_user_token;
                $auth->save();

                $user = $this->users->get_by_id($auth->user_id);
                return $user;
              }
            }

          // Not connected yet - create new user:
          $i = 1;
          while ($this->users->get_by_login($vk_user_login))
          {
            $vk_user_login = $vk_user->first_name." ".$vk_user->last_name.$i;
            $i++;
          }

          $vk_user_password = md5(time().$vk_user_login).md5($vk_user_id.time().rand(0,1000000));
          $user = $this->users->register('vk', $vk_user_login, $vk_user_password, $vk_user_email);

          if ($user)
          {
            $vk_auth = new authentication;
            $vk_auth->user_id = $user->id;
            $vk_auth->third_party_name = 'vk';
            $vk_auth->third_party_id = $vk_user_id;
            $vk_auth->third_party_token = $vk_user_token;
            $vk_auth->save();

            return $user;
          }
        }
        

        return false;
      }
    }

    function api($method, $access_token, $params)
    {
      if (!is_array($params))
        $params = array();

      $params['access_token'] = $access_token;

      
      $result = @file_get_contents("https://api.vk.com/method/".$method."?".http_build_query($params));
      $result = @json_decode($result);

      return $result;
    }

    function get_site_auth_url($url_path, $scope = 'offline')
    {
      $c_url = $this->registry->settings->site_path.$url_path;
      $auth_url = "https://oauth.vk.com/authorize?client_id=".$this->registry->settings->vk_app_id."&response_type=code&redirect_uri=".urlencode($c_url)."&scope=".$scope;
      return $auth_url;
    }

    function get_vk_user_by_auth_code($auth_code, $url_path)
    {
     $c_url = $this->registry->settings->site_path.$url_path;
     $token_url = "https://oauth.vk.com/access_token?client_id=".$this->registry->settings->vk_app_id."&redirect_uri=".urlencode($c_url)."&client_secret=".$this->registry->settings->vk_app_secret."&code=".urlencode($auth_code);

     $access_token = @json_decode(@file_get_contents($token_url));
     if (!isset($access_token->access_token) || !isset($access_token->user_id))
      return null;

     $response = $this->api("users.get", $access_token->access_token, array("uids"=>$access_token->user_id, "fields"=>"first_name,last_name,nickname,screen_name,photo"));
     if ($response && isset($response->response) && isset($response->response[0]))
     {
        $vk_user = $response->response[0];
        $vk_user->token = $access_token->access_token;

        return $vk_user;
     } else
      return null;
    }
  }




