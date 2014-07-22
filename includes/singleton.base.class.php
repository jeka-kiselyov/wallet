<?php

 abstract class singleton_base extends base
 {
  private static $instances = array();

  function __construct()
  {
    parent::__construct();
    
    $name = get_called_class();
    self::$instances[$name] = $this;
  }

  public static function getInstance() // singleton
  {
    $name = get_called_class();
    if (!isset(self::$instances[$name]) ) 
    {
      self::$instances[$name] = new $name();
    }
    return self::$instances[$name];
  }

  public static function getInstanceWithParams($param) // singleton
  {
    $name = get_called_class();
    if (!isset(self::$instances[$name]) ) 
    {
      self::$instances[$name] = new $name($param);
    }
    return self::$instances[$name];
  }

  final private function __clone(){}
 }