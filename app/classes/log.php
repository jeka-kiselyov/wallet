<?php

  class log extends singleton_base 
  {
    static private $logger = false;

    function __construct() 
    {
      parent::__construct();
      //require_once SITE_PATH_LIBS.DIRECTORY_SEPARATOR.'KLogger'.DIRECTORY_SEPARATOR.'KLogger.php';
      self :: $logger = new Katzgrau\KLogger\Logger(SITE_PATH.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'logs');
    }

    public function __call($method, $args)//call KLogger methods
    {
      return call_user_func_array(array(self :: $logger, $method),$args);
    }
  }

