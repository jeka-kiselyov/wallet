<?php

 function autoloader_get_model_or_class($name)
 {
 	if (class_exists("model_".$name, false))
 	{
	    $classname = "model_".$name;
	    return $classname::getInstance();
 	}
 	elseif (class_exists($name, false))
 	{
 		if (method_exists($name, 'getInstance'))
	 		return $name::getInstance();
	 	else
	 		return new $name();
 	}
    elseif (is_file(SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR.$name.".php"))
    {
	    require_once SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR.$name.".php";
		return new $name();
    } 
    elseif (is_file(SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."m_".$name.".php"))
    {
	    require_once SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."m_".$name.".php";
	    $classname = "model_".$name;
	    return new $classname();
    }
    else
    {
    	$schema = schema::getInstance();
    	if ($schema->get_fields($name) !== false)
    	{	    
    		$classname = "model_".$name;
	 		eval("class ".$classname." extends model_base {}");
		    return new $classname();
    	}
    }
 }

 function autoloader($class_name) 
 {	
 		$name = strtolower($class_name);
 		if (is_file(SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."entities".DIRECTORY_SEPARATOR."e_".$name.".php"))
 		{
	 		include SITE_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."entities".DIRECTORY_SEPARATOR."e_".$name.".php";
 		} 
 		else 
 		{
	    	$schema = schema::getInstance();
	    	if ($schema->get_fields(Inflector::pluralize($name)) !== false)
	    	{	    
	 			eval("class ".$class_name." extends entity_base {}");
	    	}
 		}
 }

 spl_autoload_register('autoloader');


?>