<?php


class collection implements ArrayAccess, Countable, IteratorAggregate
{
	private $note = "Trying to var_dump it? Don't forget to call fill_entities() method first.";

	private $registry;
	private $db;

	private $entity_name;

	public $base_query;

	private $order_by_field;
	private $order_by_order;

	private $limit_from;
	private $limit_count;

	private $is_queried = false;

	public $entities = array();
	private $entities_ids = array();

	private $collection_itterator;


	function __construct($entity_name, $base_query)
	{
		$this->registry = registry::getInstance();
		$this->db = $this->registry->db;

		$this->entity_name = $entity_name;
		$this->base_query = $base_query;
	} 

	function get_entity_by_index($i)
	{
		if (!$this->is_queried)
			$this->fill_entities();

		if (isset($this->entities_ids[$i]))
			return $this->entities[$this->entities_ids[$i]];
		else
			return null;
	}

	function at($id)
	{
		return $this->get_entity_by_index($id);
	}

	function add_entity($entity)
	{
		$id = "".$entity->id;
		$this->entities[$id] = $entity;
		$this->entities_ids[] = $id;
	}

	function remove_entity_with_id($id)
	{
		$id = "".$id;
		unset($this->entities[$id]);
		$key = array_search($id, $this->entities_ids);
		if ($key !== false)
			array_splice($this->entities_ids, $key, 1);
	}

	function get_entity_by_id($id)
	{
		if (!$this->is_queried)
			$this->fill_entities();

		if (isset($this->entities["".$id]))
			return $this->entities["".$id];
		else
			return null;
	}

	function get_total_count()
	{
        $rs = $this->db->execute($this->base_query);
        if ($rs)
        	return (int)$rs->RecordCount();
        return 0;
	}

	function fill_entities()
	{
		$q = $this->base_query;

		if ($this->order_by_field)
			$q.=" ORDER BY `".$this->order_by_field."` ".$this->order_by_order;

		if ($this->limit_from || $this->limit_count)
			$q.=" LIMIT ".(int)$this->limit_from.", ".(int)$this->limit_count;

		$rs = $this->db->execute($q);
		if ($rs)
		while ($array = $rs->FetchRow())
		{
			$this->entities[$array['id'].""] = new $this->entity_name($array, $this);
		}

		$this->entities_ids = array_keys($this->entities);

		$this->is_queried = true;
	}

	function clear_entities()
	{
		$this->entities = array();
		$this->entities_ids = array();
		$this->is_queried = false;
	}

	function set_base_query($base_query)
	{
		$this->base_query = $base_query;
		$this->clear_entities();
	}

	function set_order_by($field, $order = 'DESC')
	{
		$this->order_by_order = $order;
		$this->order_by_field = $field;
		$this->clear_entities();
	}

	function order_by($field, $order = 'DESC')
	{
		$this->order_by_order = $order;
		$this->order_by_field = $field;
		$this->clear_entities();

		return $this;
	}

	function set_limit($from, $count)
	{
		$this->limit_from = $from;
		$this->limit_count = $count;
		$this->clear_entities();
	}

	function limit($from, $count)
	{
		$this->limit_from = $from;
		$this->limit_count = $count;
		$this->clear_entities();
	}

	function slice($offset, $count)
	{
		$this->limit_from = $offset;
		$this->limit_count = $count;
		$this->clear_entities();
	}



	//// \/ -  fill interfaces methods


	function getIterator() {
		if (!$this->is_queried)
			$this->fill_entities();
		return new CollectionInrerator($this->entities);
	}

	function count() {
		if (!$this->is_queried)
			$this->fill_entities();
		return count($this->entities);
	}

	function offsetExists($offset)
	{
		if (!$this->is_queried)
			$this->fill_entities();
		return isset($this->entities[$offset]);
	}

	function offsetGet($offset)
	{
		if (!$this->is_queried)
			$this->fill_entities();

		if (isset($this->entities[$offset]) == false)
			return null;
		return $this->entities[$offset];
	}

	function offsetSet($offset, $value)
	{
		if (!$this->is_queried)
			$this->fill_entities();
		
		$this->entities[$offset] = $value;
	}

	function offsetUnset($offset)
	{
		if (!$this->is_queried)
			$this->fill_entities();
		unset($this->entities[$offset]);
	}

}
 







class CollectionInrerator implements Iterator {
  private $_list;
  private $_current;
  private $_keys;

  function __construct(&$elements) {
    $this->_list = &$elements;
  }

  function rewind() {
    return reset($this->_list);
  }
  function current() {
    return current($this->_list);
  }
  function key() {
    return key($this->_list);
  }
  function next() {
    return next($this->_list);
  }
  function valid() {
    return key($this->_list) !== null;
  }
} 

?>