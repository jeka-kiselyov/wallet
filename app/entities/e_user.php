<?php


 class user extends entity_base
 {
	protected function validation()
	{
		return array(
				array('field'=>'email', 'checker' => checker_rules::MAX_LENGTH(255), 'error_message' => 'Email is too long'),
				array('field'=>'email', 'checker' => checker_rules::MIN_LENGTH(2), 'error_message' => 'Email is too short'),
				array('field'=>'email', 'checker' => checker_rules::EMAIL(), 'error_message' => 'Invalid email format'),
				array('field'=>'login', 'checker' => checker_rules::MAX_LENGTH(255), 'error_message' => 'Login is too long'),
				array('field'=>'login', 'checker' => checker_rules::MIN_LENGTH(2), 'error_message' => 'Login is too short'),
		);
	}

	public function save()
	{
		if (!$this->id)
		{
			/// new item
			if ($this->users->get_by_email($this->email))
			{
				$this->throwValidationException('This email is already registered in the system');
			}
			if ($this->users->get_by_login($this->login))
			{
				$this->throwValidationException('This username is already taken');
			}
		}

		unset($this->fields['is_admin']);
		return parent::save();
	}

	public function createWallet($name)
	{
	    $wallet = new Wallet();

	    $wallet->name = $name;
	    $wallet->user_id = $this->id;
	    $wallet->total = 0;
	    $wallet->type = 'default';
	    $wallet->status = 'active';

	    $wallet->save();

	    return $wallet;
	}

	public function to_array()
	{
		$array = $this->fields;
		unset($array['password'], $array['confirmation_code'], $array['password_restore_code']);
		unset($array['registration_ip'], $array['activity_ip']);
		$array['id'] = $this->id;
		return $array;
	}

 }

?>