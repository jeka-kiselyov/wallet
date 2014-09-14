<?php

class controller_api_tokens extends api_controller
{
  public function __construct($registry)
  {
    parent::__construct($registry);

    $this->error_prefix.='6';
  }

  protected function crud_list()
  {
    $checker = new checker;
    for ($i = 0; $i < 10; $i++)
      $data[] = $checker->generate_security_token();

    $this->data($data);
  }
}


















