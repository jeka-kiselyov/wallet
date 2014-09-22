<?php
 
 class relations_test extends PHPUnit_Framework_0F
 {
 	private $test_entities_count = 10;

 	public function setUp()
    {
    	// Create tables
    	// 
        @$this->db->query("DROP TABLE `testrelationentities`");
        @$this->db->query("DROP TABLE `testrelationjoinedentities`");
        $this->db->query("CREATE TABLE  `testrelationentities` (
                            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `name` VARCHAR( 255 ) NOT NULL ,
                            `value` INT NOT NULL,
                            `testrelationjoinedentity_id` INT NOT NULL
                            ) ENGINE = INNODB;");
        $this->db->query("CREATE TABLE  `testrelationjoinedentities` (
                            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                            `name` VARCHAR( 255 ) NOT NULL
                            ) ENGINE = INNODB;");

        schema::add_to_schema('testrelationentities', array(
            // ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
            'name'                     => array('type'=>"STRING"),
            'value'                    => array('type'=>"INTEGER"),
            'testrelationjoinedentity_id'                    => array('type'=>"INTEGER")
        ));
        schema::add_to_schema('testrelationjoinedentities', array(
            // ['id'] is implicit. It's always there, BIGINT, primaryKey, autoIncrement,
            'name'                     => array('type'=>"STRING"),
            'value'                    => array('type'=>"INTEGER")
        ));


    	for ($i = 0; $i < $this->test_entities_count; $i++)
    	{
            $joined_entity = new Testrelationjoinedentity();  // note the singular form.
            $joined_entity->name = "Joined name #".$i;
            $joined_entity->save();

    		$entity = new Testrelationentity(); 
			$entity->name = 'Entity to test one to many #'.$i;
			$entity->value = 31337 + $i;
            $entity->testrelationjoinedentity_id = $joined_entity->id; // add relation
			$saved = $entity->save();

            $entity2 = new Testrelationentity(); 
            $entity2->name = 'Entity2 to test one to many #'.$i;
            $entity2->value = 50000 + 31337 + $i;
            $entity2->testrelationjoinedentity_id = $joined_entity->id; // add relation
            $saved = $entity2->save();
    	}
    }

    public function test_onetoone()
    {
        $entity = $this->testrelationentities->get_by_name('Entity to test one to many #1');
        $this->assertInstanceOf('testrelationentity', $entity); // loaded entity

        $joined = $entity->testrelationjoinedentity;
        $this->assertInstanceOf('testrelationjoinedentity', $joined); // get joined entity

        $this->assertEquals('Joined name #1', $joined->name); // assert that this is the one we need

        /// try to change the name and reload
        $joined->name = 'Joined changed name #1';
        $joined->save();

        $this->assertEquals('Joined changed name #1', $entity->testrelationjoinedentity->name); // assert that this is the one we need

        $entity2 = $this->testrelationentities->get_by_name('Entity to test one to many #1'); // get from db now
        $this->assertEquals('Joined changed name #1', $entity2->testrelationjoinedentity->name); // assert that this is the one we need
    }

    public function test_onetomany()
    {
        $entity = $this->testrelationjoinedentities->get_by_name('Joined name #2');
        $this->assertInstanceOf('testrelationjoinedentity', $entity); // loaded entity

        /// Get collection of joined entities (2 in this case)
        $related = $entity->testrelationjoinedentity_testrelationentities; // looks complex in this case. $user->user_documents is an another example

        $this->assertInstanceOf('collection', $related); // is an instance of collection class
        $this->assertEquals(2, count($related));

    }

	public function tearDown()
	{
        $this->db->query("DROP TABLE `testrelationentities`");
        $this->db->query("DROP TABLE `testrelationjoinedentities`");
	}

 }
