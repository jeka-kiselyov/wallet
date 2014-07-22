<?php

class controller_admin_api extends admin_controller
{
  public function __construct($registry)
  {
    parent::pre();
    $this->select_menu('api');
  }
  
  function index()
  {
     $this->redirect("admin_index", "index");
  }

  function recaptcha()
  {
    $form_checker = new checker;
    if ($this->is_post() && $form_checker->check_security_token())
    {
      if ($form_checker->post('cancel'))
        $this->redirect("admin_index", "index");
      
      $form_checker->check_post('recaptcha_public_key', checker_rules::MIN_LENGTH(3), $this->_("reCaptcha public key is too short"));
      $form_checker->check_post('recaptcha_public_key', checker_rules::MAX_LENGTH(100), $this->_("reCaptcha public keyis too long"));
      $form_checker->check_post('recaptcha_private_key', checker_rules::MIN_LENGTH(3), $this->_("reCaptcha private key is too short"));
      $form_checker->check_post('recaptcha_private_key', checker_rules::MAX_LENGTH(100), $this->_("reCaptcha private key is too long"));
      
      if ($form_checker->is_good())
      {
        $this->registry->settings->recaptcha_public_key = $form_checker->post('recaptcha_public_key');
        $this->registry->settings->recaptcha_private_key = $form_checker->post('recaptcha_private_key');
        $this->ta("saved","saved");
      }
    }

    $this->ta("form_checker", $form_checker);
  }


  function vk()
  {
    $form_checker = new checker;
    if ($this->is_post() && $form_checker->check_security_token())
    {
      if ($form_checker->post('cancel'))
        $this->redirect("admin_index", "index");
      
      $form_checker->check_post('vk_app_id', checker_rules::MIN_LENGTH(3), $this->_("Application ID is too short"));
      $form_checker->check_post('vk_app_id', checker_rules::MAX_LENGTH(100), $this->_("Application ID is too long"));
      $form_checker->check_post('vk_app_secret', checker_rules::MIN_LENGTH(3), $this->_("Application secret key is too short"));
      $form_checker->check_post('vk_app_secret', checker_rules::MAX_LENGTH(100), $this->_("Application secret key is too long"));
      
      if ($form_checker->is_good())
      {
        $this->registry->settings->vk_app_id = $form_checker->post('vk_app_id');
        $this->registry->settings->vk_app_secret = $form_checker->post('vk_app_secret');
        $this->registry->settings->user_allow_vk_registration = (bool)$form_checker->post('user_allow_vk_registration');

        $this->ta("saved","saved");
      }
    }

    $this->ta("form_checker", $form_checker);
  }


  function facebook()
  {
    $form_checker = new checker;
    if ($this->is_post() && $form_checker->check_security_token())
    {
      if ($form_checker->post('cancel'))
        $this->redirect("admin_index", "index");
      
      $form_checker->check_post('facebook_app_id', checker_rules::MIN_LENGTH(3), $this->_("Application ID is too short"));
      $form_checker->check_post('facebook_app_id', checker_rules::MAX_LENGTH(100), $this->_("Application ID is too long"));
      $form_checker->check_post('facebook_app_secret', checker_rules::MIN_LENGTH(3), $this->_("Application secret key is too short"));
      $form_checker->check_post('facebook_app_secret', checker_rules::MAX_LENGTH(100), $this->_("Application secret key is too long"));
      
      if ($form_checker->is_good())
      {
        $this->registry->settings->facebook_app_id = $form_checker->post('facebook_app_id');
        $this->registry->settings->facebook_app_secret = $form_checker->post('facebook_app_secret');
        $this->registry->settings->user_allow_facebook_registration = (bool)$form_checker->post('user_allow_facebook_registration');

        $this->ta("saved","saved");
      }
    }

    $this->ta("form_checker", $form_checker);
  }



}