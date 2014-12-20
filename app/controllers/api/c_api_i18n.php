<?php

class controller_api_i18n extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='7';
  }

  protected function crud_list()
  {
    $items = $this->i18n_languages->get_all();

    $data = array();
    foreach ($items as $item) 
    {
      $d = $item->to_array();
      $data[] = array('name'=>$d['name'], 'code'=>$d['code']);
    }

    $this->data($data);
  }

  public function bycode()
  {
    $code = $this->gp(0,'');
    $ret = false;
    if ($code)
    {
      $language = $this->i18n_languages->get_by_code($code);
      if ($language)
        $ret = $language->get_strings();
    }

    $this->data($ret);
  }

}


















