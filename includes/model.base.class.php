<?php


 abstract class model_base extends singleton_base
 {
  protected $table;
  protected $entity_class_name;

  private $columns = array();

  function __construct()
  {
    parent::__construct();

    $this->table = strtolower(str_replace("model_","",get_class($this)));
    $this->entity_class_name = Inflector::singularize($this->table);
  }

  function get_columns()
  {
    if ($this->columns)
      return $this->columns;

    $fields = $this->registry->schema->get_fields($this->table);
    if ($fields)
    {
      /// get columns from schema file
      $this->columns = array_fill_keys(array_keys($fields), '');
    }
    else {
      /// get columns from db
      $columns = $this->db->MetaColumnNames($this->table, true);
      if (!$columns)
        throw new Exception("Invalid model name: ".$this->table, 1);
        
      $this->columns = array_fill_keys($columns, '');
    }
    
    /*
    $columns = $this->db->MetaColumnNames($this->table, true);
    $this->columns = array_fill_keys($columns, '');
    */
    return $this->columns;
  }

  function get_count()
  {
    return (int)$this->db->getone("SELECT COUNT(id) as cnt FROM `".$this->table."`");
  }

  function get_all()
  {
    return new collection($this->entity_class_name, "SELECT * FROM `".$this->table."`" );
  }

  function get_by_id($id)
  {
    $fields = $this->db->GetRow("SELECT * FROM `".$this->table."` WHERE `id` = '".(int)$id."';");
    if ($fields)
    	return new $this->entity_class_name($fields);
    else
      return null;
  }

  function get_by_field($field_name, $value)
  {
    $fields = $this->db->GetRow("SELECT * FROM `".$this->table."` WHERE `".$field_name."` = ?;", array($value));
    if ($fields)
      return new $this->entity_class_name($fields);
    else
      return null;
  }

  function find_by_field($field_name, $value)
  {
    return new collection($this->entity_class_name, $this->db->returnPreparedQuery("SELECT * FROM `".$this->table."` WHERE `".$field_name."` = ?", array($value) ) );
  }

  public function __call($name, $arguments)
  {
    if (strpos($name, "get_by_") === 0)
    {
      $field_name = str_replace("get_by_", "", $name);
      return $this->get_by_field($field_name, $arguments[0]);
    }
    elseif(strpos($name, "find_by_") === 0)
    {
      $field_name = str_replace("find_by_", "", $name);
      return $this->find_by_field($field_name, $arguments[0]);
    }
  }


 }