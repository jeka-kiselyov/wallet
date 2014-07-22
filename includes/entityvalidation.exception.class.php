<?php

class entityvalidation_exception extends Exception
{
	private $_error_messages = array();
	
	public function set_error_messages($error_messages)
	{
		$this->_error_messages = $error_messages;
	}
	
	public function get_error_messages()
	{
		return $this->_error_messages;
	}
}