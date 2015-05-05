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

	public function hasAccessToWallet($wallet_id)
	{
		$wallet = $this->wallets->get_by_id($wallet_id);
		if ($wallet && $wallet->hasAccess($this->id))
			return true;
		else
			return false;
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
		} else {
			/// update item
			$sameEmail = $this->users->find_by_email($this->email);
			foreach ($sameEmail as $u)
				if ($u->id != $this->id)
					$this->throwValidationException('This email is already registered in the system');

			$sameLogin = $this->users->find_by_login($this->login);
			foreach ($sameLogin as $u)
				if ($u->id != $this->id)
					$this->throwValidationException('This username is already taken');

		}

		$is_admin = $this->fields['is_admin'];
		unset($this->fields['is_admin']);
		unset($this->_changed_fields['is_admin']);
		
		$success = parent::save();
		$this->fields['is_admin'] = $is_admin;
		if ($success)
		{
			//// update wallets accesses if there re any
			$accesses = $this->wallets_accesses->find_by_email($this->email);
			if ($accesses)
				foreach ($accesses as $access) {
					$access->to_user_id = $this->id;
					$access->save();
				}
		}
		return $success;
	}

	public function delete()
	{
		$accesses = $this->wallets_accesses->find_by_email($this->email);
		if ($accesses)
			foreach ($accesses as $access) {
				$access->to_user_id = 0;
				$access->save();
			}	

		return parent::delete();
	}

	public function createWallet($name, $currency = 'USD')
	{
	    $wallet = new Wallet();

	    $wallet->name = $name;
	    $wallet->user_id = $this->id;
	    $wallet->total = 0;
	    $wallet->type = 'default';
	    $wallet->status = 'active';
	    $wallet->currency = $currency;

	    $wallet->save();

	    return $wallet;
	}

	public function to_array()
	{
		$array = $this->fields;
		unset($array['password'], $array['confirmation_code'], $array['password_restore_code']);
		unset($array['registration_ip'], $array['activity_ip']);
		$array['id'] = $this->id;
		$array['is_demo'] = (bool)$this->is_demo;
		return $array;
	}

 }

?>