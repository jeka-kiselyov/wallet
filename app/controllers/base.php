<?php

 abstract class userside_controller  extends controller_base
 {
	public function __construct($registry)
	{
 		parent::__construct($registry);
 		
        $this->user = $this->sessions->get_user();
        $this->ta("user", $this->user);

  //       if ($this->user && $this->user->language_id)
  //       {
  //       	try {
		// 		$this->i18n->set_language_by_id($this->user->language_id);
		// 	} catch (Expcetion $e)
		// 	{
		// 		$this->i18n->detect_language();	
		// 	}
		// }
		// else
		// 	$this->i18n->detect_language();

		// $this->current_language = $this->i18n->get_current_language();
	}

 }