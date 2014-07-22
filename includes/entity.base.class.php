<?php


abstract class entity_base implements ArrayAccess
{
	protected $fields;
	protected $joined_entities;
	protected $models_and_classes;

	protected $id;

	protected $table_name;
	protected $entity_name;

	protected $collection_entity_is_part_of;
	
	protected $_changed_fields;
	
	private $_validation_errors;

	private $registry;
	private $db;

	public function __construct($fields_array = false, &$collection = false)
	{
		$this->registry = registry::getInstance();
		$this->db = $this->registry->db;
		$this->fields = array();
		$this->id = false;
		$this->joined_entities = array();
		$this->models_and_classes = array();

		$this->collection_entity_is_part_of = &$collection;

		$this->entity_name = strtolower(get_class($this));
	    $this->table_name = Inflector::pluralize( $this->entity_name );
	    
	    $this->_changed_fields = array();
	    
	    $this->_validation_errors = array();

		if (isset($fields_array) && is_array($fields_array))
		{
			$this->fields = $fields_array;

			if (isset($fields_array['id']))
			{
				$this->id = $fields_array['id'];
				unset($this->fields['id']);
			}
		} else {
			$table_name = $this->table_name;
			$this->fields = $this->$table_name->get_columns();
			unset($this->fields['id']);	
		}

	    if (is_callable(array($this, 'after_construct')))
	    	$this->after_construct();
	} 
	
	public function to_array()
	{
		return array_merge(array('id'=>$this->id), $this->fields);
	}

	public function fill($array)
	{
		foreach ($array as $key => $value)
			if (array_key_exists($key, $this->fields))
			{
				$this->set($key, $value);
			}
	}
	
	protected function validation()
	{
	    return array();
	}
	
	protected function validate()
	{
		$result = true;
		$this->_validation_errors = array();
		
		$validation = $this->validation();
		if (empty($validation)) return true;
		
		foreach ($validation as $val) {
			
			if (array_key_exists($val['field'], $this->_changed_fields)) {
				$_field = $val['field'];
				
				if (!$val['checker']->check($this->_changed_fields[$_field])) {
					$this->_validation_errors[] = $val['error_message'];
					$result = false;
				}
			}
		}
		
		return $result;
	}

	public function save()
	{
		if (!$this->_changed_fields) return false;
		
		if (!$this->validate())
		{
			$e = new entityvalidation_exception('Entity has not been validated');
			$e->set_error_messages($this->_validation_errors);
			throw $e;
		}
		
		if ($this->id)
		{
			// update
			try {
				return (bool)$this->db->update($this->table_name, $this->_changed_fields, "id=?", $this->id);
			} catch (Exception $e) {
				return false;
			}
		} else 
		{
			// add
			try {
				$this->db->insert($this->table_name, $this->fields);
			} catch (Exception $e) {
				return false;
			}
			$this->id = $this->db->insert_id();
			return true;
		}
	}

	public function delete()
	{
		if ($this->collection_entity_is_part_of && $this->id)
			$this->collection_entity_is_part_of->remove_entity_with_id($this->id);

		if ($this->id)
		{
			try {
				$success = (bool)$this->db->delete($this->table_name, "id=?", $this->id);
				$this->fields = array();
				$this->id = false;
				return $success;
			} catch (Exception $e) {
				return false;
			}
		} else 
		{
			$this->fields = array();
			$this->id = false;
			return true;
		}
	}

	public function set($key, $var)
	{
		return $this->__set($key, $var);
	}

	public function __set($key, $var)
	{
		if (array_key_exists($key, $this->fields))
		{
			$this->fields[$key] = $var;
			$this->_changed_fields[$key] = $var;
			return true;
		}
		
		$this->$key = $var;
		return true;
	}

	public function __get($key)
	{
		if (isset($this->$key))
			return $this->$key;

		if (isset($this->models_and_classes[$key]))
		{
			return $this->models_and_classes[$key];
		} 
		elseif (array_key_exists($key, $this->fields))
		{
			return $this->fields[$key];
		} 
		elseif (isset($this->joined_entities[$key]))
		{
			return $this->joined_entities[$key];
		} 
		elseif (isset($this->fields[$key."_id"])) 
		{
			$model_name = Inflector::pluralize($key);
			if ($this->$model_name)
			{
				$this->joined_entities[$key] = $this->$model_name->get_by_id($this->fields[$key."_id"]);
				return $this->joined_entities[$key];
			}
		}
		
		if ($this->id && strpos($key, $this->entity_name."_") === 0 && substr($key, strlen($key)-1, 1) == 's')
		{
			$model_name = substr($key, strlen($this->entity_name)+1); //$model_name = str_replace($this->entity_name."_", "", $key, 1); 
			if ($this->$model_name)
			{
				$this->joined_entities[$key] = $this->models_and_classes[$model_name]->find_by_field($this->entity_name."_id", $this->id);
				return $this->joined_entities[$key];
			}
		}

	    $this->models_and_classes[$key] = autoloader_get_model_or_class($key);
	    return $this->models_and_classes[$key];
	}

	function get($key)
	{
		if (isset($this->fields[$key]) == false)
			return null;
		return $this->fields[$key];
	}

	function remove($var)
	{
		unset($this->fields[$key]);
	}

	function offsetExists($offset)
	{
		return isset($this->fields[$offset]);
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
		unset($this->fields[$offset]);
	}

}
 

?>