<?php


 class registry implements ArrayAccess {

      private $vars = array();

      static private $_instance = NULL;

      public static function getInstance()
      {
        if (!self::$_instance) {
                self::$_instance = new self;
        }
        return self::$_instance;
      }

      function set($key, $var)
      {
       $this->vars[$key] = $var;

       return true;
      }

      function __set($key, $var)
      {
       $this->vars[$key] = $var;

       return true;
      }

      function __get($key)
      {
       if (isset($this->vars[$key]) == false)
        return null;

       return $this->vars[$key];
      }


      function get($key)
      {
       if (isset($this->vars[$key]) == false)
        return null;

       return $this->vars[$key];
      }


      function remove($var)
      {
       unset($this->vars[$key]);
      }

      function offsetExists($offset)
      {
       return isset($this->vars[$offset]);
      }


      function offsetGet($offset)
      {
       return $this->get($offset);
      }


      function offsetSet($offset, $value)
      {
      	$this->set($offset, $value);
      }


      function offsetUnset($offset)
      {
      	unset($this->vars[$offset]);
      }

 }






?>
