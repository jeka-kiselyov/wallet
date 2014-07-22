<?php

class controller_news extends userside_controller
{
	private $items_per_page = 10;

	function index()
	{
		$this->redirect('news', 'recent');
 	}

 	function recent()
 	{
 		$page = (int)$this->gp(0,0);
 		if ($page < 0) $page = 0;
 		
 		$items = $this->news_items->get_all();

 		$total = $this->news_items->get_count();
 		$total_pages = floor($total / $this->items_per_page);

 		$items->set_order_by('time_created', 'DESC');
 		$items->set_limit($page*$this->items_per_page, $this->items_per_page); // pagination here

 		$this->ta('items', $items);
 		$this->ta('current_page', $page);
 		$this->ta('total_pages', $total_pages);

 		$this->ta('page_url_format', $this->registry->settings->site_path.'/news/recent/%d');
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