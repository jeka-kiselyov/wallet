<?php

class controller_static extends userside_controller
{
	function index()
	{
		$this->redirect('index', 'index');
 	}

	function view()
	{
		$page_slug = $this->gp(0,'');
		$page_slug = str_replace(".html", "", $page_slug);

		$static_page_item = $this->static_pages->get_by_slug($page_slug);

		if ($static_page_item)
			$this->ta('static_page', $static_page_item);
		else
			$this->not_found();
 	}

 }