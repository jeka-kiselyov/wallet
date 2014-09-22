<?php
 
 class entity_test extends PHPUnit_Framework_0F
 {
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
    }

    public function test_magic_entity()
    {
    	$entity = new Testentity(); // note the singular form. 
		$this->assertInstanceOf('testentity', $entity); // created entity is instance of testentity class
		$entity->name = 'Some name';
		$entity->value = 31337;
		$saved = $entity->save();

		$this->assertTrue($saved);        // save() method should return true

		$entity_id = $entity->id;
		$this->assertGreaterThan(0, $entity_id); // saved to database. Id is integer
		$this->assertEquals('Some name', $entity->name);
		$this->assertEquals(31337, $entity->value);

		// Now try to load this entity from db
		unset($entity);

		$entity = $this->testentities->get_by_id($entity_id);
		$this->assertInstanceOf('testentity', $entity); // entity is instance of testentity class
		$this->assertEquals($entity_id, $entity->id);
		$this->assertEquals('Some name', $entity->name);
		$this->assertEquals(31337, $entity->value);

		// Now try to update it with different fields
		$entity->name = 'New name';
		$entity->value = 1337;
		$saved = $entity->save();

		$this->assertTrue($saved);        // save() method should return true
		$this->assertEquals($entity_id, $entity->id);
		$this->assertEquals('New name', $entity->name);
		$this->assertEquals(1337, $entity->value);

		// Now try to load updated entity from db
		unset($entity);

		$entity = $this->testentities->get_by_id($entity_id);
		$this->assertInstanceOf('testentity', $entity); // entity is instance of testentity class
		$this->assertEquals($entity_id, $entity->id);
		$this->assertEquals('New name', $entity->name);
		$this->assertEquals(1337, $entity->value);

		// Now remove it from database
		$removed = $entity->delete();
		$this->assertTrue($removed);        // delete() method should return true
		$this->assertFalse($entity->id);        // id is false now

		// Check that it is removed from database
		$entity = $this->testentities->get_by_id($entity_id);
		$this->assertNull($entity);        // nothing should be returned
    }

    public function test_entity()
    {
    	// Now lets check defined in schema entity - user for example. Should work very same
    	
    	$user = new User(); // note the singular form. 
		$this->assertInstanceOf('user', $user); // created entity is instance of user class
		$user->email = 'phpunittestuser@example.com';
		$user->login = 'phpunittestuser';
		$saved = $user->save();

		$this->assertTrue($saved);        // save() method should return true

		$user_id = $user->id;
		$this->assertGreaterThan(0, $user_id); // saved to database. Id is integer
		$this->assertEquals('phpunittestuser@example.com', $user->email);
		$this->assertEquals('phpunittestuser', $user->login);

		// Now try to load this entity from db
		unset($user);

		$user = $this->users->get_by_id($user_id);
		$this->assertInstanceOf('user', $user); //  entity is an instance of user class
		$this->assertEquals($user_id, $user->id);
		$this->assertEquals('phpunittestuser@example.com', $user->email);
		$this->assertEquals('phpunittestuser', $user->login);

		// Now try to update it with different fields
		$user->email = 'phpunittestuser2@example.com';
		$user->login = 'phpunittestuser2';
		$saved = $user->save();

		$this->assertTrue($saved);        // save() method should return true
		$this->assertEquals($user_id, $user->id);
		$this->assertEquals('phpunittestuser2@example.com', $user->email);
		$this->assertEquals('phpunittestuser2', $user->login);

		// Now try to load updated entity from db
		unset($user);

		$user = $this->users->get_by_id($user_id);
		$this->assertInstanceOf('user', $user); //  entity is an instance of user class
		$this->assertEquals($user_id, $user->id);
		$this->assertEquals('phpunittestuser2@example.com', $user->email);
		$this->assertEquals('phpunittestuser2', $user->login);

		// Now remove it from database
		$removed = $user->delete();
		$this->assertTrue($removed);        // delete() method should return true
		$this->assertFalse($user->id);        // id is false now

		// Check that it is removed from database
		$user = $this->users->get_by_id($user_id);
		$this->assertNull($user);        // nothing should be returned
    }

	public function tearDown()
	{
    	$this->db->query("DROP TABLE `testentities`");
	    $this->db->query("DELETE FROM `users` WHERE 
	    	email = 'phpunittestuser2@example.com' OR email = 'phpunittestuser2@example.com' ");
	}

 }
