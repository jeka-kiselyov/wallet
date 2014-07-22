<?php

class rss extends singleton_base 
{
  private $items;

  private $title;
  private $link;
  private $description;

  function __construct() 
  {
    parent::__construct();
    $this->title = $this->registry->settings->site_title;
    $this->description = $this->registry->settings->site_title;
    $this->link = $this->registry->settings->site_path;
  }

  public function add_items($items)
  {
    foreach ($items as $item) 
      if (isset($item['title'], $item['link'], $item['description'], $item['date']))
        $this->add_item($item['title'], $item['description'], $item['link'], $item['date']);
  }

  public function add_item($title, $description, $link, $date = false)
  {
    if (!$date)
      $date = time();

    $this->items[] = array('title'=>$title, 'description'=>$description, 'link'=>$link, 'date'=>$date);
  }

  public function set_title($title)
  {
    $this->title = $title;
  }

  public function set_description($description)
  {
    $this->description = $description;
  }

  public function set_link($link)
  {
    $this->link = $link;
  }

  public function get_content()
  {
    $most_recent_date = 0;
    foreach ($this->items as $item) 
      if (isset($item['date']) && $item['date'] > $most_recent_date)
        $most_recent_date = $item['date'];

    if (!$most_recent_date)
      $most_recent_date = time();

    $content = '<?xml version="1.0" encoding="UTF-8" ?>
                  <rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">
                  <channel>';
    if ($this->title)
      $content.= '<title><![CDATA['.$this->title.']]></title>';
    if ($this->link)
      $content.= '<link>'.htmlentities($this->link, ENT_IGNORE, 'UTF-8').'</link>';
    if ($this->description)
      $content.= '<description><![CDATA['.$this->description.']]></description>';

    $content.="<pubDate>".date('r', $most_recent_date)."</pubDate>\n";
    $content.="<lastBuildDate>".date('r', $most_recent_date)."</lastBuildDate>\n";

    foreach ($this->items as $item) 
    if (isset($item['title'], $item['link'], $item['description']))
    {
      $content.= "<item>\n";
      $content.= "<title><![CDATA[".$item['title']."]]></title>\n";
      $content.= "<link>".htmlentities($item['link'], ENT_IGNORE, 'UTF-8')."</link>\n";
      $content.= "<guid>".htmlentities($item['link'], ENT_IGNORE, 'UTF-8')."</guid>\n";
      $content.= "<desciption><![CDATA[".$item['description']."]]></desciption>\n";
      if (isset($item['date']) && $item['date'])
        $content.= "<pubDate>".date('r', $item['date'])."</pubDate>\n";
      $content.= "</item>\n";
    }

    $content.= '</channel></rss>';

    return $content;
  }

}