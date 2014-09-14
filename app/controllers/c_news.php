<?php

class controller_news extends userside_controller
{
	private $items_per_page = 25;

	function index()
	{
		$this->redirect('news', 'recent');
 	}

 	function recent()
 	{
 		$page = (int)$this->gp(0,1);
 		if ($page < 1) $page = 1;
 		
 		$items = $this->news_items->get_all();

 		$total = $this->news_items->get_count();
 		$total_pages = floor($total / $this->items_per_page);

 		$items->set_order_by('time_created', 'DESC');
 		$items->set_limit(($page-1)*$this->items_per_page, $this->items_per_page); // pagination here

 		$this->ta('items', $items);
 		$this->ta('currentPage', $page);
 		$this->ta('perPage', $this->items_per_page);
 		$this->ta('total_pages', $total_pages);

 		$this->ta('categories', $this->news_categories->get_all());
 	}

 	function category()
 	{
 		$category_id = (int)$this->gp(0,0);

 		$page = (int)$this->gp(1,1);
 		if ($page < 1) $page = 1;
 		
 		$items = $this->news_items->find_by_news_category_id($category_id);

 		$total = $this->news_items->get_count();
 		$total_pages = floor($total / $this->items_per_page);

 		$items->set_order_by('time_created', 'DESC');
 		$items->set_limit(($page-1)*$this->items_per_page, $this->items_per_page); // pagination here

 		$this->ta('items', $items);
 		$this->ta('currentPage', $page);
 		$this->ta('perPage', $this->items_per_page);
 		$this->ta('total_pages', $total_pages);

 		$this->ta('news_category_id', $category_id);
 		$this->ta('categories', $this->news_categories->get_all());
 		$this->ta('page_tpl', 'news/recent');
 	}

	function view()
	{
 		$page_slug = $this->gp(0,'');
		$page_slug = str_replace(".html", "", $page_slug);

		$news_item = $this->news_items->get_by_slug($page_slug);

		if ($news_item)
			$this->ta('item', $news_item);
		else
			$this->not_found();
 	}

 	function rss()
 	{
 		$rss = $this->rss;
 		$rss->set_title($this->settings->site_title." RSS Feed");
 		$rss->set_description($this->settings->site_title." RSS Feed");
 		$rss->set_link($this->settings->site_path);

 		header("Content-Type:text/xml; charset=utf-8");
 		$news = $this->news_items->get_all();
 		$news->set_limit(0, 10);
 		$news->order_by('time_created', 'DESC');

 		foreach ($news as $item)
 			$rss->add_item($item->title, $item->description, $this->settings->site_path.'/news/view/'.$item->slug.'.html', $item->time_updated);

 		echo $rss->get_content();
 		$this->rendered = true;
 	}

 }