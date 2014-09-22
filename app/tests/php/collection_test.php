<?php
 
 class collection_test extends PHPUnit_Framework_0F
 {
 	private $test_entities_count = 100;

 	public function setUp()
    {
    	// Create table in database to check magic - working with entities without defining them in schema file
    	// 
    	@$this->db->query("DROP TABLE `testentities`");
    	$this->db->query("CREATE TABLE  `testentities` (
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
							`name` VARCHAR( 255 ) NOT NULL ,
							`value` INT NOT NULL
							) ENGINE = INNODB;");

        schema::add_to_schema('testentities', array(
            // ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
            'name'                     => array('type'=>"STRING"),
            'value'                    => array('type'=>"INTEGER")
        ));

    	for ($i = 0; $i < $this->test_entities_count; $i++)
    	{
    		$entity = new Testentity(); // note the singular form. 
			$entity->name = 'Entity for collection name';
			$entity->value = 31337 + $i;
			$saved = $entity->save();
    	}
    }

    public function test_collection()
    {
    	/// easiest way to create collection - with model class
    	// model->find_by_fieldname(fieldvalue)
    	// or model->get_all()
    	// 
    	$entities = $this->testentities->find_by_name('Entity for collection name');
		$this->assertInstanceOf('collection', $entities); // entities is an instance of collection class
    	$this->assertEquals($this->test_entities_count, count($entities));

    	// Foreach
    	$count_in_foreach = 0;
    	foreach ($entities as $entity)
    	{
			$this->assertInstanceOf('testentity', $entity); // entity is an instance of testentity class
	    	$this->assertEquals('Entity for collection name', $entity->name);
			$this->assertGreaterThan(0, $entity->id); // Id is integer

			$count_in_foreach++;
    	}
    	$this->assertEquals($this->test_entities_count, $count_in_foreach);

    	$total_count = $entities->get_total_count(); // total = count() in this case, as we didn't set up limit();
    	$this->assertEquals($this->test_entities_count, $total_count);
    }

    public function test_limit()
    {
    	$entities = $this->testentities->find_by_name('Entity for collection name'); // Entities are not loaded from db on this step
    	$entities->limit(10,10); // neither on this
    	$this->assertEquals(10, count($entities)); // but are loaded on this. When we get count or try to access some entity in collection

    	$entities->set_limit(5,55); // synonim. Note the SQL thing. Total is 55, not 50
    	$this->assertEquals(55, count($entities));

    	$total_count = $entities->get_total_count(); // but total count is still == 100
    	$this->assertEquals($this->test_entities_count, $total_count);
    }

    public function test_orderby()
    {
	    $entities = $this->testentities->find_by_name('Entity for collection name'); // Entities are not loaded from db on this step
    	$entities->order_by('value', 'DESC'); // neither on this

    	$highest = $entities->get_entity_by_index(0);
    	$highest_value = $highest->value;

    	$found_highest_value = 0;
    	foreach ($entities as $entity) 
    	{
    		if ($entity->value > $found_highest_value)
    			$found_highest_value = $entity->value;
    	}

    	$this->assertEquals($highest_value, $found_highest_value);
    }

    public function test_change_in_entity()
    {
    	$entities = $this->testentities->find_by_name('Entity for collection name');
    	$entity = $entities->get_entity_by_index(2); // get third entity. 2 is not ID here, but index in array of entities
		$this->assertInstanceOf('testentity', $entity); // entity is an instance of testentity class

		$entity_id = $entity->id;
		$temp_value  = $entity->value;

		$entity->value = 666000;
		$entity2 = $entities->get_entity_by_index(2); // get same entity. 
    	$this->assertEquals(666000, $entity2->value);

		$entity3 = $entities->get_entity_by_id($entity_id); // get same entity. 
    	$this->assertEquals(666000, $entity3->value);

    	// Find in by foreach
    	$found = false;
    	foreach ($entities as $fentity) 
    	{
    		if ($fentity->id == $entity_id && $fentity->value == 666000)
    			$found = true;
    	}
	    $this->assertTrue($found);

	    // entity, entity2 and entity3 point to same thing now. Lets check it
	    $entity3->value = 555666;
    	$this->assertEquals(555666, $entity3->value);
    	$this->assertEquals(555666, $entity2->value);
    	$this->assertEquals(555666, $entity->value);

    	// Set back to 666000
	    $entity->value = 666000;

	    // Don't forget to save the entity for future use!
	    $entity->save();
    	$entities = $this->testentities->find_by_name('Entity for collection name');
    	$entities->limit(0,50);
		$entity3 = $entities->get_entity_by_id($entity_id); // get same entity. 
    	$this->assertEquals(666000, $entity3->value);

    	// Restore the value to use in other tests
    	$entity3->value = $temp_value;
    	$entity3->save();
    }

    public function test_removing_entity()
    {
    	$entities = $this->testentities->find_by_name('Entity for collection name');
    	$entity = $entities->get_entity_by_index(2); // get third entity. 2 is not ID here, but index in array of entities
		$this->assertInstanceOf('testentity', $entity); // entity is an instance of testentity class

		$temp_value  = $entity->value;
		// duplicate
		$entity2 = $entities->get_entity_by_id($entity->id);

		// Remove entity
		$entity->delete();
		$this->assertFalse($entity->id);
		$this->assertFalse($entity2->id); // // entity2 points to the same location

		$this->assertEquals($this->test_entities_count - 1, count($entities));

    	// Foreach
    	$count_in_foreach = 0;
    	foreach ($entities as $entity)
    	{
			$this->assertInstanceOf('testentity', $entity); // entity is an instance of testentity class
	    	$this->assertEquals('Entity for collection name', $entity->name);
			$this->assertGreaterThan(0, $entity->id); // Id is integer

			$count_in_foreach++;
    	}
    	$this->assertEquals($this->test_entities_count-1, $count_in_foreach);

    	$total_count = $entities->get_total_count(); // total = count() in this case, as we didn't set up limit();
    	$this->assertEquals($this->test_entities_count-1, $total_count);

    	/// restore
		$entity = new Testentity(); // note the singular form. 
		$entity->name = 'Entity for collection name';
		$entity->value = 31337 + 2;
		$saved = $entity->save();

    	$entities = $this->testentities->find_by_name('Entity for collection name');
		$this->assertEquals($this->test_entities_count, count($entities));
    }

	public function tearDown()
	{
    	$this->db->query("DROP TABLE `testentities`");
	    $this->db->query("DELETE FROM `users` WHERE 
	    	email = 'phpunittestuser2@example.com' OR email = 'phpunittestuser2@example.com' ");
	}

 }
