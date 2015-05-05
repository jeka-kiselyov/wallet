<?php

 abstract class admin_controller  extends controller_base
 {
 	private $menu_items = array();

 	public function __construct($registry)
	{
 		parent::__construct($registry);
 		$user = $this->sessions->get_user();
 		
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

		if (isset($this->db_table_name) && $this->db_table_name)
			$this->db_entity_name = Inflector::singularize($this->db_table_name);

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
		    		array("name"=>$this->_("Strings"), "href"=>"i18n/strings"),
		    		array("name"=>$this->_("Parse templates"), "href"=>"i18n/parse")
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

	    $this->languages = $this->i18n_languages->get_all();
	    
	    $this->is_multilingual = false; 
	    if (count($this->languages) > 1)
	    	$this->is_multilingual = true;

	    $this->ta('is_multilingual', $this->is_multilingual);
	    $this->ta('languages', $this->languages);
	}


	public function add()
	{
		if (isset($_POST['cancel']))
			$this->redirect($this->get_current_class_route(), "manage");

		$form_checker = new checker;
		if (isset($_POST['save']) && $form_checker->check_security_token())
		{
			$item = new $this->db_entity_name;
			$item->fill_from_form_checker($form_checker);

			$form_checker->save_entity($item);

			if ($form_checker->is_entity_saved())
				$this->redirect($this->get_current_class_route(), "manage");        
		}

		$this->ta('form_checker', $form_checker);
	}


	public function edit()
	{
		$item_id = (int)$this->gp(0,0);
		$model = $this->{$this->db_table_name};

		if (isset($_POST['cancel']) || !$item_id || !($item = $model->get_by_id($item_id)))
			$this->redirect($this->get_current_class_route(), "manage");

		$form_checker = new checker;
		if (isset($_POST['save']) && $form_checker->check_security_token())
		{
			$item->fill_from_form_checker($form_checker);

			$form_checker->save_entity($item);
			if ($form_checker->is_entity_saved())
				$this->redirect($this->get_current_class_route(), "manage");        
		}

		$this->ta('item', $item);
		$this->ta('form_checker', $form_checker);
	}

	public function manage()
	{
		$search = $this->table_helper->proccess_search_parameters($this->get_current_class_route());
		$order = $this->table_helper->proccess_order_parameters($this->get_current_class_route());
		$model = $this->{$this->db_table_name};

		if (isset($_POST['delete']))
		{
			$item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
			$item = $model->get_by_id($item_id);
			if ($item)
			{
				$item->delete();
			}
		}

		if (!empty($_POST))
			$this->refresh();

		if (isset($this->search_fields) && is_array($this->search_fields))
			$search_fields = $this->search_fields;

		$joins = array();

		$pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count($this->db_table_name, $search, $search_fields, $joins), 20);

		$this->ta("pages", $pagination);
	    $items = new collection($this->db_entity_name, $this->table_helper->get_items_query($this->db_table_name, $order, $pagination['cur_offset'], 20, $search, $search_fields, $joins));

		$this->ta("order", $order);
		$this->ta("search", $search);
		$this->ta("items", $items);
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

	public function get_current_class_route()
	{
		return str_replace('controller_', '', get_class($this));
	}

 }