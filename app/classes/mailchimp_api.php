<?php

class mailchimp_api extends singleton_base 
{
  protected $api_key = '';
  protected $mailchimp = false;

  function __construct() 
  {
    parent::__construct(); 
    
    $this->api_key = $this->registry->settings->mailchimp_api_key;
    $this->mailchimp = new Mailchimp/Mailchimp($this->api_key);
  }

  function __get($name)
  {
    if (property_exists($this->mailchimp, $name))
      return $this->mailchimp->$name;
    return null;
  }

}




