<?php

class entityvalidation_test extends PHPUnit_Framework_0F {
	
	public function setUp()
	{
		// Create table in database to check magic - working with entities without defining them in schema file
		//
		@$this->db->query("DROP TABLE `sampleentities`");
		$this->db->query("CREATE TABLE  `sampleentities` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`email` VARCHAR( 64 ) NOT NULL ,
				`login` VARCHAR( 64 ) NOT NULL ,
				`gender` ENUM( 'M','F' ) ,
				`age` INT NOT NULL
		) ENGINE = INNODB;");
	}
	
	public function test_entityvalidation()
    {
    	$entity = new sampleentity();
    	$this->assertInstanceOf('sampleentity', $entity);
    	
    	$entity->email = 'phpunittestuser';
    	$entity->login = 'test';
    	$entity->gender = 'A';
    	$entity->age = 'ts';
    	
    	//Record 1
    	
    	$saved = $this->save_entity($entity);
    	$this->assertCount(4, $saved);  //4 error messages
    	
    	$entity->email = 'phpunittestuser@example.com';
    	$saved = $this->save_entity($entity);
    	$this->assertCount(3, $saved);
    	
    	$entity->login = 'phpunit-testuser22';
    	$saved = $this->save_entity($entity);
    	$this->assertCount(4, $saved);
    	
    	$entity->login = 'phpunittestuser';
    	$saved = $this->save_entity($entity);
    	$this->assertCount(2, $saved);
    	
    	$entity->gender = 'M';
    	$saved = $this->save_entity($entity);
    	$this->assertCount(1, $saved);
    	
    	$entity->age = '23';
    	$saved = $this->save_entity($entity);
    	$this->assertTrue($saved);  //Validated and saved
    	
    	//Record 2
    	
    	$entity->email = 'phpunittestuser2@example.com';
    	$entity->login = 'phpunit-testuser';
    	 
    	$saved = $this->save_entity($entity);
    	$this->assertCount(1, $saved);
    	 
    	$entity->login = 'phpunit_testuser';
    	$saved = $this->save_entity($entity);
    	$this->assertTrue($saved);  //Validated and saved
    }
    
    private function save_entity($entity)
    {
    	try {
    		$saved = $entity->save();
    	}
    	
    	catch (entityvalidation_exception $e) {
    		return $e->get_error_messages();
    	}
    	
    	return $saved;
    }

    public function tearDown()
    {
    	$this->db->query("DROP TABLE `sampleentities`");
    }
}

class sampleentity extends entity_base {

	protected function validation()
	{
		return array(
				array('field'=>'email', 'checker' => checker_rules::EMAIL(), 'error_message' => 'Email address is not valid'),
				array('field'=>'login', 'checker' => checker_rules::MIN_LENGTH(6), 'error_message' => 'Login must be not shorter than 6 characters'),
				array('field'=>'login', 'checker' => checker_rules::MAX_LENGTH(16), 'error_message' => 'Login must be not longer than 16 characters'),
				array('field'=>'login', 'checker' => checker_rules::PREG('/^[a-zA-Z0-9_ ]+$/'), 'error_message' => 'Login must have only letters, digits and underbar character (_)'),
				array('field'=>'gender', 'checker' => checker_rules::ONE_OF('M','F'), 'error_message' => 'Gender must have value M or F'),
				array('field'=>'age', 'checker' => checker_rules::IS_INTEGER(), 'error_message' => 'Age must be integer value'),
		);
	}
}