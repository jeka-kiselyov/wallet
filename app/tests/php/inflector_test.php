<?php
 
 class inflector_test extends PHPUnit_Framework_0F
 {
    public function test_inflector()
    {
    	// Check out CakePHP's complex Inflector.
    	$this->assertEquals('users', Inflector::pluralize('user'));
    	$this->assertEquals('entities', Inflector::pluralize('entity'));
    	$this->assertEquals('mice', Inflector::pluralize('mouse'));

    	$this->assertEquals('user', Inflector::singularize('users'));
    	$this->assertEquals('entity', Inflector::singularize('entities'));
    	$this->assertEquals('mouse', Inflector::singularize('mice'));
    }

 }
