<?php


 class user extends entity_base
 {
	public function save()
	{
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