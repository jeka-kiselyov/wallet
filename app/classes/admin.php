<?php

class admin extends singleton_base  {

    protected $password_salt = "some salt";

 	function is_admin()
 	{
        $user = $this->sessions->get_user();
        if ($user && $user->is_admin == "1")
        	return true;
        else
        	return false;
 	}
}

