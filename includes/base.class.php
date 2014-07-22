<?php

 abstract class base
 {
  protected $registry;
  protected $db;

  function __construct()
  {
    $this->registry = registry::getInstance();
    $this->db = $this->registry->db;
  }

  public static function getInstance() // not singleton
  {
    return $this;
  }

  function __get($name)
  {
    if (!empty($this->$name))
     return $this->$name;

    $this->$name = autoloader_get_model_or_class($name);
    return $this->$name;
  }
 }