<?php

class recaptcha extends singleton_base 
{
  private $public_key = null;
  private $private_key = null;

  function __construct() 
  {
    parent::__construct();
    $this->init_recaptcha();
  }

  private function init_recaptcha()
  {
    require_once(SITE_PATH_LIBS."recaptcha/recaptchalib.php");

    $this->public_key = $this->registry->settings->recaptcha_public_key;
    $this->private_key = $this->registry->settings->recaptcha_private_key;

    return true;
  }

  public function get_html()
  {
    return recaptcha_get_html($this->public_key);
  }

  public function is_valid()
  {
    if (!isset($_POST['recaptcha_challenge_field']) || !isset($_POST['recaptcha_response_field']))
      return false;

    $resp = recaptcha_check_answer ($this->private_key,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

    return $resp->is_valid;
  }

}




