<?php


 class user extends entity_base
 {
	public function save()
	{
		unset($this->fields['is_admin']);
		return parent::save();
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