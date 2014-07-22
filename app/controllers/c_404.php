<?php

class controller_404 extends userside_controller
{
  function index()
  {
  	$this->layout = "404";
  	header("HTTP/1.0 404 Not Found");
  }

}