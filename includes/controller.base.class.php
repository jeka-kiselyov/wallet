<?php

 abstract class controller_base
 {
 	protected $registry;
 	protected $tpl;
 	protected $db;

 	protected $layout;
 	protected $page;

 	protected $head_css = array();
  protected $head_js = array();
 	public $rendered;

  public $view_cache_id;

 	public function __construct($registry)
 	{
 		$this->registry = $registry;
 	  $this->tpl = $registry->tpl;
 	  $this->db = $registry->db;
 	  $this->settings = $registry->settings;

 	  $this->layout = "default";
 	  $this->page = $this->registry->page;

 	  $this->rendered = false;
 	}

 	abstract function index();

  public function json_results($value)
  {
    header('Content-Type: application/json');
    echo json_encode($value);
    $this->rendered = true;
    exit();
  }

  /**
   * Set page title
   * @param string $str Title
   */
 	protected function add_title($str)
 	{
 		$this->registry->title = $str;
 	}

 	protected function add_css($name)
 	{
   $this->head_css[] = $name;
 	}

 	protected function add_js($name)
 	{
 		$this->head_js[] = $name;
 	}

  protected function finish()
  {
    $this->rendered = true;
    exit();
  }

  protected function is_post()
  {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
      return true;
    return false;
  }

  protected function _($string)
  {
    return $this->i18n->translate($string);
  }

  protected function not_found()
  { 
    $this->registry->router->call404();
    $this->rendered = true;
  }

 	protected function reroute($controller, $action = false)
 	{
 		if (!$action)
 		 $action = "index";

 		$this->registry->router->delegate($controller, $action);
    $this->rendered = true;
 	}

  protected function redirect($controller, $action = false, $dont_exit = false, $params = "")
  {
  	$controller = str_replace("_", "/", $controller);
  	if ($controller == "index" && !$action)
  	 header("location: ".$this->registry->settings->site_path."/");
  	  else
    if ($action)
     header("location: ".$this->registry->settings->site_path."/".$controller."/".$action."/".$params);
      else
       header("location: ".$this->registry->settings->site_path."/".$controller."/");

    if (!$dont_exit)
      exit();
  }

  protected function refresh($dont_exit = false)
  {
   $pageURL = 'http';
   if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
    $pageURL.= "s";
   $pageURL.="://";
   if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80")
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     else
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

    header("location: ".$pageURL);

    if (!$dont_exit)
      exit();
  }

	protected function get_parameter($n = 0, $default = false)
  {
   if (isset($this->registry->args[$n]))
    return $this->registry->args[$n];
     else
      return $default;
  }

  protected function gp($n, $default = false)
  {
   return $this->get_parameter($n, $default);
  }

  protected function ta($name, $value)
  {
   return $this->tpl->assign($name, $value);
  }

	public function __get($name)
 	{
    if (!empty($this->$name))
     return $this->$name;

    $this->$name = autoloader_get_model_or_class($name);
    return $this->$name;
 	}

  protected function clear_view_cache($cache_id = false)
  {
    if ($cache_id === false)
    {
      // Clear current view cache
      if (is_null($this->view_cache_id))
      {
        $this->set_view_cache_id();
      }
      return $this->tpl->clearCache("layout/".$this->layout.".tpl", $this->view_cache_id);
    } else {
      // Clear view cache by cache id
      return $this->tpl->clearCache("layout/".$this->layout.".tpl", $cache_id);
    }
  }

  protected function is_view_cached()
  {
    if (is_null($this->view_cache_id))
    {
      $this->set_view_cache_id();
    }
    return ($this->tpl->isCached("layout/".$this->layout.".tpl", $this->view_cache_id));
  }

  protected function set_view_cache_id($cache_id = false)
  {
    if ($cache_id === false)
    {
      $this->view_cache_id = $this->registry->controller."|".$this->registry->action."|".md5(json_encode($_GET));
      return $this->view_cache_id;
    } 

    $this->view_cache_id = $cache_id;
    return $cache_id;
  }

 	public function __destruct()
 	{
 		if (!$this->rendered)
 		{
      $this->tpl->assign("controller",$this->registry->controller);
      $this->tpl->assign("action",$this->registry->action);
      $this->tpl->assign("page",$this->page);

      $this->tpl->assign("title",$this->registry->title);
      $this->tpl->assign("head_css",$this->head_css);
      $this->tpl->assign("head_js",$this->head_js);

      $tokens = array();
      $checker = new checker;
      for ($i = 0; $i < 10; $i++)
        $tokens[] = $checker->generate_security_token();

      $this->tpl->assign("tokens", $tokens);

      if ($this->registry->settings['cache']['enable_smarty_cache'])
      {
        if (is_null($this->view_cache_id))
        {
          $this->set_view_cache_id();
        }
        $this->tpl->display("layout/".$this->layout.".tpl", $this->view_cache_id);        
      } else {
        
        try {
          $this->tpl->display("layout/".$this->layout.".tpl");
        } catch (Exception $e) {
          die('Can not display template. Error: '.$e->getMessage());  
        }

      }
 		}
 	}

 }