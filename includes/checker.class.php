<?php
	

	class checker {

		private $id;
		private $errors;

		private $values;

		private $entity_saved = false;

		function __construct($id = 'checker')
		{
			$this->id = $id;
			$this->errors = array();
			$this->values = array();
		}

		function save_entity($entity)
		{
			$this->entity_saved = false;
			try {
				$entity->save();
				$this->entity_saved = true;
			} catch (entityvalidation_exception $e)
			{
				$this->add_errors($e->get_error_messages());
			}
			return $entity;
		}

		function is_entity_saved()
		{
			return $this->entity_saved;
		}

		function get_errors()
		{
			return $this->errors;
		}

		function get_errors_as_html($separator = "<br>")
		{
			$ret = array();
			foreach ($this->errors as $e)
				$ret[] = $e['message'];

			return implode($separator, $ret);
		}

		function clear()
		{
			$this->errors = array();
			$this->values = array();
		}

		function add_error($message, $input_id = false)
		{
			if ($input_id === false)
				$input_id = count($this->values);
			
			$this->errors[] = array("message"=>$message, "input_id"=>$input_id);
		} 

		function add_errors($messages)
		{
			if (is_array($messages))
			{
				foreach ($messages as $m)
					$this->add_error($m);
			}
			else
				$this->add_error($messages);
		}

		function generate_security_token()
		{
			if (!session_id()) session_start();  // Initialize session' array of security tokens
			if (!isset($_SESSION['security_tokens']) || !is_array($_SESSION['security_tokens']))
				$_SESSION['security_tokens'] = array();

			if (count($_SESSION['security_tokens']) > 50)		// Keep most recent 50 tokens. Enough to keep 50 forms open.
				$_SESSION['security_tokens'] = array_slice($_SESSION['security_tokens'], -50);

			$random_token = md5(time().__FILE__ .mt_rand());
			$_SESSION['security_tokens'][] = md5($random_token.__FILE__); // Save md5 of random token to session. 

			return $random_token;
		}

		function is_good_security_token($token, $do_not_unset = false)
		{
			if (!session_id()) session_start();  // Initialize session' array of security tokens
			if (!isset($_SESSION['security_tokens']) || !is_array($_SESSION['security_tokens']))
			{
				$_SESSION['security_tokens'] = array();
				return false; // Tokens list is empty - token is not good.
			}

			foreach ($_SESSION['security_tokens'] as &$md5_token) 
			{
				if ($md5_token == md5($token.__FILE__))
				{
					if (!$do_not_unset)
						unset($md5_token);
					return true;
				}
			}

			return false;
		}

		function post($input_name = false)
		{
			if ($input_name === false)
				return $_POST;

			if (isset($this->values[$input_name]))
				return $this->values[$input_name]['value'];
			elseif (isset($_POST[$input_name]))
				return $_POST[$input_name];
			else
				return false;
		}

		function check_security_token($input_name = 'security_token', $error_message = 'Invalid or expired security token. Try to submit form again.')
		{
			$input_value = null; 

			if (isset($_POST[$input_name])) 
				$input_value = $_POST[$input_name];

			$has_error = false;
			if (!$this->is_good_security_token($input_value))
				$has_error = true;

			if ($has_error)
			{
				$this->add_error($error_message, $input_name);
				return false;
			}

			return true;
		}

		function check_post($input_name, $rule_type, $error_message = false)
		{
			$rule = $rule_type;
			$input_value = null; 

			if (isset($_POST[$input_name])) 
				$input_value = $_POST[$input_name];
			
			$has_error = !$rule->check($input_value);

			$this->values[$input_name] = array("input_id"=>$input_name, "value"=>$input_value, "rule"=>$rule, "has_error"=>$has_error);

			if ($has_error)
			{
				$this->add_error($error_message, $input_name);
				return false;
			}

			return $input_value;
		}

		function check($input_value, $rule_type, $error_message = "", $input_id = false)
		{
			$rule = $rule_type;

			if ($input_id === false)
				$input_id = "".count($this->values);

			$has_error = !$rule->check($input_value);

			if ($has_error)
			{
				$this->add_error($error_message, $input_id);
			}

			$this->values[$input_id] = array("input_id"=>$input_id, "value"=>$input_value, "rule"=>$rule, "has_error"=>$has_error);
		}

		function has_errors()
		{
			if ($this->errors)
				return true;
			return false;
		}

		function is_passed()
		{
			if (!$this->errors)
				return true;
			return false;
		}

		function is_good()
		{
			if (!$this->errors)
				return true;
			return false;
		}

	}

	final class checker_rules {

		private static $EMAIL;
		private static $IS_INTEGER;
		private static $IS_ARRAY;

		public static function EMAIL()
		{
			return self::$EMAIL ? self::$EMAIL : self::$EMAIL = new self("EMAIL");
		} 
		public static function MIN_LENGTH()
		{
			if (func_num_args() != 1)
				throw new Exception("Invalid parameters count. MIN_LENGTH(length).");
				
			$obj = new self("MIN_LENGTH");
			$obj->add_parameter(func_get_arg(0));

			return $obj;
		} 
		public static function MAX_LENGTH()
		{
			if (func_num_args() != 1)
				throw new Exception("Invalid parameters count. MAX_LENGTH(length).");
			$obj = new self("MAX_LENGTH");
			$obj->add_parameter(func_get_arg(0));

			return $obj;
		} 
		public static function EQUAL()
		{
			if (func_num_args() != 1)
				throw new Exception("Invalid parameters count. EQUAL(to_value).");
			$obj = new self("EQUAL");
			$obj->add_parameter(func_get_arg(0));

			return $obj;
		} 
		public static function IS_INTEGER()
		{
			return self::$IS_INTEGER ? self::$IS_INTEGER : self::$IS_INTEGER = new self("IS_INTEGER");
		} 
		public static function IS_ARRAY()
		{
			return self::$IS_ARRAY ? self::$IS_ARRAY : self::$IS_ARRAY = new self("IS_ARRAY");
		}
		public static function ONE_OF()
		{
			if (func_num_args() < 1)
				throw new Exception("At lease one parameter required. ONE_OF(value1, value2...).");
			$obj = new self("ONE_OF");
			$num_args = func_num_args();
			for ($i=0; $i<$num_args; $i++)
				$obj->add_parameter(func_get_arg($i));

			return $obj;
		}
		public static function PREG()
		{
			if (func_num_args() != 1)
				throw new Exception("Invalid parameters count. PREG(pattern).");
			$obj = new self("PREG");
			$obj->add_parameter(func_get_arg(0));

			return $obj;
		} 
		public static function UNIQUE_IN_DB()
		{
			if (func_num_args() != 2)
				throw new Exception("Invalid parameters count. UNIQUE_IN_DB(table, column).");
			$obj = new self("UNIQUE_IN_DB");
			$obj->add_parameter(func_get_arg(0));
			$obj->add_parameter(func_get_arg(1));

			return $obj;
		} 

		private $name;
		private $parameters;

		public function check($value)
		{ 
			if ($this->name == 'EMAIL')
			{
				return $this->is_email($value);
			}
			elseif ($this->name == 'MIN_LENGTH')
			{
				return ( mb_strlen($value, "UTF-8") >= $this->get_parameter(0) ) ? true : false;
			}
			elseif ($this->name == 'MAX_LENGTH')
			{
				return ( mb_strlen($value, "UTF-8") <= $this->get_parameter(0) ) ? true : false;
			}
			elseif ($this->name == 'IS_INTEGER')
			{
				if (strlen($value) != strspn($value, '0123456789'))
					return false;
				else
					return true;
			}
			elseif ($this->name == 'IS_ARRAY')
			{
				if (is_array($value))
					return true;
				else
					return false;
			}
			elseif ($this->name == 'PREG')
			{
				return preg_match($this->get_parameter(0), $value) ? true : false;
			}
			elseif ($this->name == 'UNIQUE_IN_DB')
			{
				$db = db::getInstance();
				$found = $db->getone("SELECT `".$this->get_parameter(1)."` FROM `".$this->get_parameter(0)."` WHERE `".$this->get_parameter(1)."` = ".$db->qstr($value)." ");

				return !$found;
			}
			elseif ($this->name == 'EQUAL')
			{
				return ( $value == $this->get_parameter(0) ) ? true : false;
			}
			elseif ($this->name = 'ONE_OF') 
			{
				if (in_array($value, $this->parameters))
					return true;
				else
					return false;
			}

			return false;
		}

		private function get_parameter($n)
		{
			return isset($this->parameters[$n]) ? $this->parameters[$n] : false;
		}

		public function add_parameter($param)
		{
			$this->parameters[] = $param;
		}

		public function get_name()
		{
			return $this->name;
		}

		private function __construct($name)
		{
			$this->name = $name;
			$this->parameters = array();
		}

		private function is_email($email) 
		{
			return (bool)preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $email);
		}
	}



?>