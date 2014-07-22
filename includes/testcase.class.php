<?php
 
 abstract class PHPUnit_Framework_0F extends PHPUnit_Framework_TestCase
 {
	public function setUp()
	{
	// your code here
	}

	public function tearDown()
	{
	// your code here
	}

	public function __get($name)
	{
		if (!empty($this->$name))
			return $this->$name;

		$this->$name = autoloader_get_model_or_class($name);
		return $this->$name;
	}

 }

?>