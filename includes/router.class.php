<?php


class router {

        private $registry;
        private $path;
        private $args = array();

        function __construct($registry)
        {
            $this->registry = $registry;
        }

        function call404()
        {
            $file = $this->path . "c_404.php";
            if (is_readable($file))
            {
                if (is_file($this->path."base.php"))
                    include_once ($this->path."base.php");

                include ($file);
                
                $class = 'controller_404';
                $this->registry->page = 'controller_404';
                $controller = new $class($this->registry);

                if (is_callable(array($controller, "pre")))
                    $controller->pre();

                $controller->index();
            } else
             die ($file.' , '.$action.' is not callable. 404 Not Found');
        }

        function setPath($path)
        {
            $path = rtrim($path, '/\\');
            $path .= DIRECTORY_SEPARATOR;

            if (is_dir($path) == false)
            {
                throw new Exception ('Invalid controller path: `' . $path . '`');
            }

            $this->path = $path;
        }

        function delegate()
        {
            $this->getController($file, $controller, $action, $args, $base_file);

            $this->registry->args = $args;
            $this->registry->controller = $controller;
            $this->registry->action = $action;

            $this->registry->page = $controller."_".$action;
            $this->registry->page_tpl = str_replace("_", "/", $this->registry->page);
            $this->registry->tpl->assign("page_tpl", $this->registry->page_tpl);

            $class = 'controller_' . $controller;

            if (!is_readable($file))
              return $this->call404();

            if ($base_file)
            {
                include $base_file;
            }

            include_once $file;

            if (!is_callable(array($class, $action)))
            {
                return $this->call404();
            }

            $controller = new $class($this->registry);

            if (is_callable(array($controller, "pre")))
                $controller->pre();

            return $controller->$action($args);
        }

        private function getController(&$file, &$controller, &$action, &$args, &$base_file)
        {
            $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

            if (empty($route))
            {
                $route = 'index';
            }

            $route = trim($route, '/\\');
            $parts = explode('/', $route);
            $cmd_path = $this->path;

            $lower_dirs = array();

            foreach ($parts as $part)
            {
                if (is_dir($cmd_path . $part))
                {
                    $cmd_path .= $part . DIRECTORY_SEPARATOR;
                    if (!$lower_dirs) $lower_dirs[] = "";
                    array_unshift($lower_dirs, $part);
                    array_shift($parts);
                    continue;
                }

                if (is_file($cmd_path . "c_" . implode("_", $lower_dirs). $part . '.php'))
                {
                    $controller = implode("_",$lower_dirs).$part;
                    array_shift($parts);
                    break;
                }

                if (ctype_digit($part))
                {
                    $controller = implode("_",$lower_dirs)."index";
                    break;
                }
            }

            if (empty($controller))
              $controller = implode("_",$lower_dirs).'index';

            foreach ($parts as $part)
            {
                if (ctype_digit($part))
                {
                    $action = "index";
                    break;
                } 
                else 
                {
                    $action = array_shift($parts);
                    break;
                }
            }

            if (empty($action))
                $action = 'index';

            $file = $cmd_path . "c_" . $controller . '.php';
            if (is_file($cmd_path . "base.php"))
                $base_file = $cmd_path . "base.php";
            $args = $parts;
        }



}
