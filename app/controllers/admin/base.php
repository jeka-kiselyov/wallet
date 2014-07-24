<?php

 abstract class admin_controller  extends controller_base
 {
 	private $menu_items = array();

 	public function __construct($registry)
	{
 		parent::__construct($registry);
 		
	    $this->layout = "admin";
		if (!$this->admin->is_admin() && ($this->registry->controller != 'admin_login'))
		{
			$this->redirect("admin_login");
		}
		else 
		{
			$user = $this->sessions->get_user();
			$this->ta("user", $user);

			if ($user && $user->language_id)
				$this->i18n->set_language_by_id($user->language_id);
			else
				$this->i18n->detect_language();
		}


	    $menu_items = array();

	    if ($this->admin->is_admin())
	    {

		    $this->menu_items['users'] = 
		    array(
		    	"name"=>$this->_("Users"),
		    	"icon"=>"user",
		    	"items"=>array(
		    		array("name"=>$this->_("Manage"), "href"=>"users/manage"),
		    		array("name"=>$this->_("My profile"), "href"=>"users/details/".$user->id)
		    	)
		    );

		    $this->menu_items['media'] = 
		    array(
		    	"name"=>$this->_("Media"),
		    	"icon"=>"picture",
		    	"items"=>array(
		    		array("name"=>$this->_("Manage"), "href"=>"media/manage")
		    	)
		    );

		    $this->menu_items['pages'] = 
		    array(
		    	"name"=>$this->_("Static Pages"),
		    	"icon"=>"pushpin",
		    	"items"=>array(
		    		array("name"=>$this->_("Manage"), "href"=>"static/manage", "additional"=>array("icon"=>"plus", "href"=>"static/add") ),
		    		array("name"=>$this->_("Add"), "href"=>"static/add")

		    	)
		    );

		    $this->menu_items['news'] = 
		    array(
		    	"name"=>$this->_("News"),
		    	"icon"=>"time",
		    	"items"=>array(
		    		array("name"=>$this->_("Manage"), "href"=>"news/manage", "additional"=>array("icon"=>"plus", "href"=>"news/add") ),
		    		array("name"=>$this->_("Categories"), "href"=>"news/categories", "additional"=>array("icon"=>"plus", "href"=>"news/addcategory") )

		    	)
		    );

		    $this->menu_items['mail'] = 
		    array(
		    	"name"=>$this->_("Mail"),
		    	"icon"=>"envelope",
		    	"items"=>array(
		    		array("name"=>$this->_("Manage templates"), "href"=>"mail/templates", "additional"=>array("icon"=>"plus", "href"=>"mail/newtemplate")),
		    		array("name"=>$this->_("Settings"), "href"=>"mail/settings"),
		    		array("name"=>$this->_("Send test email"), "href"=>"mail/test")
		    	)
		    );


		    $this->menu_items['i18n'] = 
		    array(
		    	"name"=>$this->_("Languages"),
		    	"icon"=>"globe",
		    	"items"=>array(
		    		array("name"=>$this->_("Languages"), "href"=>"i18n/languages", "additional"=>array("icon"=>"plus", "href"=>"i18n/addlanguage")),
		    		array("name"=>$this->_("Strings"), "href"=>"i18n/strings")
		    	)
		    );

		    $this->menu_items['api'] = 
		    array(
		    	"name"=>$this->_("API"),
		    	"icon"=>"cog",
		    	"items"=>array(
		    		array("name"=>$this->_("Openexchangerates"), "href"=>"api/openexchangerates"),
		    		array("name"=>$this->_("VK"), "href"=>"api/vk"),
		    		array("name"=>$this->_("Facebook"), "href"=>"api/facebook"),
		    		array("name"=>$this->_("reCaptcha"), "href"=>"api/recaptcha")
		    	)
		    );

		    $this->menu_items['logout'] = 
		    array(
		    	"name"=>$this->_("Log Out"),
		    	"icon"=>"log-out",
		    	"items"=>array(
		    		array("name"=>$this->_("Log Out"), "href"=>"login/logout")
		    	)
		    );

		}

		foreach ($menu_items as $menu_item) 
			$menu_item['selected'] = false;

	    $this->ta("menu_items", $this->menu_items);
	}

	public function select_menu($menu_item_id)
	{
		if (isset($this->menu_items[$menu_item_id]))
		{
			$this->menu_items[$menu_item_id]['selected'] = true;
			$this->ta('breadcrumb', $this->menu_items[$menu_item_id]['name']);
			if (isset($this->menu_items[$menu_item_id]['items'][0]))
				$this->ta('breadcrumb_href', $this->menu_items[$menu_item_id]['items'][0]['href']);				
		}
	    $this->ta("menu_items", $this->menu_items);
	}

 }