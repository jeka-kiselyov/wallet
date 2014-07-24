<?php
 
 class cache_test extends PHPUnit_Framework_0F
 {
 	public function setUp()
    {
    }

    public function test_cache()
    {
        $this->cache->set(911, 'phpunit_test_data');
        $this->assertEquals(911, $this->cache->get('phpunit_test_data'));

        $this->cache->delete('phpunit_test_data');
        $this->assertEquals(false, $this->cache->get('phpunit_test_data'));

    }

	public function tearDown()
	{
	}

 }
