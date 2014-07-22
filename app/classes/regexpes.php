<?php

class regexpes {

	protected $registry;

	function set_registry($registry)
	{
		$this->registry = $registry;
	}

	function is_email($email)
	{
    return preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $email);
	}

  function is_domain($domain)
  {
    $pieces = explode(".",$domain);
    foreach($pieces as $piece)
    {
        if (!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $piece)
            || preg_match('/-$/', $piece) )
        {
            return false;
        }
    }
    if (count($pieces) < 2)
     return false;

    return true;
  }

  function is_url($url) {
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
  }

  function filter_non_adup($str)
  {
  	return preg_replace("/[^A-Za-z0-9_\-]/","",$str);
  }


}