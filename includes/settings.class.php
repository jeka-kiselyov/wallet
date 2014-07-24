<?php

 class settings implements ArrayAccess {

      private $vars = array();
      private $vars_from_db = array();

      protected $db;

      static private $_instance = NULL;

      public static function getInstance($registry)
      {
        if (!self::$_instance) {
                self::$_instance = new self($registry);
        }
        return self::$_instance;
      }

      function __construct($registry)
      {       
        try {
        	$this->vars = include(SITE_PATH_SETTINGS."settings.php");
          $this->db = $registry->db;

          $changed_items = false;
          if ($this->vars['cache']['enable_system_cache'])
          {
            require_once(SITE_PATH_APP."classes/cache.php");
            $cache = cache::getInstanceWithParams($this->vars['cache']);
            $changed_items = $cache->get('system_settings');
          }

          if ($changed_items === false)
          {
            $changed_items = $this->db->getall("SELECT `name`, `value` FROM settings");
            if ($this->vars['cache']['enable_system_cache'])
            {
              $cache->set($changed_items, 'system_settings', array('system'), 24*60*60);
            }
          }

          foreach ($changed_items as $item) 
          {
            if ($this->is_setting_rewritable($item['name']))
            {
              $this->vars[$item['name']] = @unserialize($item['value']);
              $this->vars_from_db[$item['name']] = $this->vars[$item['name']];
            }
          }
        } catch (Exception $e)
        {
          // die('Can not initialize settings class. '.$e->getMessage());
        }
      }

      private function is_setting_rewritable($key)
      {
        if (isset($this->vars['rewritable_creation_enabled']) && !isset($this->vars[$key]))
        {
          $this->vars['rewritable_settings'][] = $key;
          return true;
        }

        if (!isset($this->vars['rewritable_settings']))
          return false;
        if (!in_array($key, $this->vars['rewritable_settings']))
          return false;

        return true;
      }

      public function set($key, $var)
      { 
        if ($this->is_setting_rewritable($key))
        { 
          $cache = cache::getInstanceWithParams($this->vars['cache']);
          $cache->delete('system_settings');
          
          $this->vars[$key] = $var;
          $serialized = serialize($var);

          if (isset($this->vars_from_db[$key]))
            $this->db->update("settings", array('value'=>$serialized), "name=?", array($key));
          else
            $this->db->insert("settings", array('value'=>$serialized, 'name'=>$key));
          
          $this->vars_from_db[$key] = $var;

          return true;
        } else
        	return false;
      }

      function __set($key, $var)
      {
       return $this->set($key, $var);
      }

      function __get($key)
      {
       return $this->get($key);
      }


      function get($key)
      {
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