<?php

  class yacaptcha extends singleton_base 
  {
    function get_captcha()
    {
      $yandex_cleanweb_api_key = $this->settings->yandex_cleanweb_api_key;
      $str = @file_get_contents("http://cleanweb-api.yandex.ru/1.0/get-captcha?key=".$yandex_cleanweb_api_key."&type=std");
      $data = @simplexml_load_string($str);

      if ($data && isset($data->captcha) && isset($data->url))
        return array('captcha'=>''.$data->captcha, 'image_url'=>''.$data->url);
    }

    function check($captcha, $value)
    {
      $yandex_cleanweb_api_key = $this->settings->yandex_cleanweb_api_key;
      $str = @file_get_contents("http://cleanweb-api.yandex.ru/1.0/check-captcha?key=".$yandex_cleanweb_api_key."&captcha=".urlencode($captcha)."&value=".urlencode($value));
      $data = @simplexml_load_string($str);

      if ($data && isset($data->ok))
        return true;
      else
        return false;
    }

  }




