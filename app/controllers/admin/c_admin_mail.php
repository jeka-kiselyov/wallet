<?php

class controller_admin_mail extends admin_controller
{ 
  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('mail');
  }
  function index()
  {
     $this->redirect("admin_mail", "templates");
  }

  function test()
  {
    $this->ta('admin_email', $this->registry->settings->mail_default_from_email);
    $form_checker = new checker;
    if (isset($_POST['send']) && $form_checker->check_security_token())
    {
      if ($form_checker->post('cancel'))
        $this->redirect("admin_mail", "index");

      $form_checker->check_post('to', checker_rules::EMAIL(), $this->_("Invalid to email format"));
      $form_checker->check_post('subject', checker_rules::MIN_LENGTH(3), $this->_("Subject is too short"));
      $form_checker->check_post('body', checker_rules::MIN_LENGTH(3), $this->_("Body is too short"));

      if ($form_checker->is_good())
      {
        $result = '';
        $mailer = $this->mailer;
        $sr = $mailer->send($form_checker->post('to'), $form_checker->post('subject'), $form_checker->post('body'));
        if ($sr)
          $result = 'True ';
        else
          $result = 'False ';

        $result.=$mailer->mailer->ErrorInfo;

        $this->ta('result', $result);
      }
    }
    $this->ta('form_checker', $form_checker);
  }

  function settings()
  {
    $form_checker = new checker;
    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      if ($form_checker->post('cancel'))
        $this->redirect("admin_index", "index");
      
      $form_checker->check_post('mail_method', checker_rules::ONE_OF('smtp', 'mail', 'sendmail'));
      $form_checker->check_post('mail_default_from_email', checker_rules::EMAIL(), $this->_("Invalid email format"));
      $form_checker->check_post('mail_default_from_name', checker_rules::MIN_LENGTH(3), $this->_("Name is too short"));
      $form_checker->check_post('mail_default_from_name', checker_rules::MAX_LENGTH(100), $this->_("Name is too long"));

      if ($form_checker->post('mail_method') == 'sendmail')
      {
        $form_checker->check_post('sendmail_path', checker_rules::MIN_LENGTH(3), $this->_("Sendmail path is too short"));
        $form_checker->check_post('sendmail_path', checker_rules::MAX_LENGTH(255), $this->_("Sendmail path is too long"));
      } else
      if ($form_checker->post('mail_method') == 'smtp')
      {
        $form_checker->check_post('smtp_secure', checker_rules::ONE_OF('', 'ssl', 'tls'));
        $form_checker->check_post('smtp_auth', checker_rules::ONE_OF('1', '0'));
        $form_checker->check_post('smtp_host', checker_rules::MIN_LENGTH(3));
        $form_checker->check_post('smtp_host', checker_rules::MAX_LENGTH(255), $this->_("SMTP host is too long"));
        $form_checker->check_post('smtp_port', checker_rules::IS_INTEGER(), $this->_("SMTP port is invalid"));

        if ((bool)$form_checker->post('smtp_auth'))
        {
          $form_checker->check_post('smtp_username', checker_rules::MIN_LENGTH(3), $this->_("SMTP username is too short"));
          $form_checker->check_post('smtp_username', checker_rules::MAX_LENGTH(255), $this->_("SMTP username is too long"));
          $form_checker->check_post('smtp_password', checker_rules::MIN_LENGTH(3), $this->_("SMTP password is too short"));
          $form_checker->check_post('smtp_password', checker_rules::MAX_LENGTH(255), $this->_("SMTP password is too long"));
        }
      }

      if ($form_checker->is_good())
      {
        $this->registry->settings->mail_method = $form_checker->post('mail_method');
        $this->registry->settings->mail_default_from_email = $form_checker->post('mail_default_from_email');
        $this->registry->settings->mail_default_from_name = $form_checker->post('mail_default_from_name');

        if ($form_checker->post('mail_method') == 'sendmail')
        {
          $this->registry->settings->sendmail_path = $form_checker->post('sendmail_path');
        } else
        if ($form_checker->post('mail_method') == 'smtp')
        {
          $this->registry->settings->smtp_host = $form_checker->post('smtp_host');
          $this->registry->settings->smtp_port = $form_checker->post('smtp_port');
          $this->registry->settings->smtp_auth = (bool)$form_checker->post('smtp_auth');
          $this->registry->settings->smtp_secure = $form_checker->post('smtp_secure');

          if ((bool)$form_checker->post('smtp_auth'))
          {
            $this->registry->settings->smtp_username = $form_checker->post('smtp_username');
            $this->registry->settings->smtp_password = $form_checker->post('smtp_password');
          }
        }

        $this->ta("saved","saved");

      }

    }

    $this->ta("form_checker", $form_checker);

  }

  function templates()
  {
    $search = $this->table_helper->proccess_search_parameters("admin_mail_templates_");
    $order = $this->table_helper->proccess_order_parameters("admin_mail_templates_");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);

    if (isset($_POST['delete']))
    {
      $item_id = false; if (isset($_POST['item_id'])) $item_id = (int)$_POST['item_id'];
      $mailtemplate = $this->mailtemplates->get_by_id($item_id);
      if ($mailtemplate)
      {
        $mailtemplate->delete();
      }
    }

    if (!empty($_POST))
     $this->refresh();
    
    $search_fields = array("name", "content", "subject");
    $joins = array(array('table'=>'i18n_languages', 'field'=>'language_id'));

    $pagination = $this->table_helper->proccess_paging_parameters($this->table_helper->get_count("mailtemplates", $search, $search_fields, $joins), 20);

    $this->ta("pages", $pagination);
    $items = $this->table_helper->get_items("mailtemplates", $order, $pagination['cur_offset'], 20, $search, $search_fields, $joins);

    $this->ta("order", $order);
    $this->ta("search", $search);
    $this->ta("items", $items);
  }

  function edittemplate()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_mail", "templates");

    $item_id = (int)$this->gp(0,0);
    $mailtemplate = $this->mailtemplates->get_by_id($item_id);

    if (!$mailtemplate)
      $this->redirect("admin_mail", "templates");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $form_checker = new checker;

    if (isset($_POST['save']))
    {
      $name = ""; if (isset($_POST['name'])) $name = $_POST['name'];
      $subject = ""; if (isset($_POST['subject'])) $subject = $_POST['subject'];
      $body = ""; if (isset($_POST['body'])) $body = $_POST['body'];

      $subject = strip_tags($subject);

      $name = strtolower(trim($name));
      $name = preg_replace('/[^a-z0-9_]/', '_', $name);
      $name = preg_replace('/_+/', "_", $name);
      $name = trim($name, "_");

      $form_checker->check($name, checker_rules::MIN_LENGTH(2), $this->_("Template identificator is too short"));
      $form_checker->check($name, checker_rules::MAX_LENGTH(255), $this->_("Template identificator is too long"));

      $form_checker->check($subject, checker_rules::MIN_LENGTH(2), $this->_("Subject is too short"));
      $form_checker->check($subject, checker_rules::MAX_LENGTH(999), $this->_("Subject is too long"));

      $form_checker->check($body, checker_rules::MIN_LENGTH(2), $this->_("Body content is too short"));
      $form_checker->check($body, checker_rules::MAX_LENGTH(30000), $this->_("Body content is too long"));

      $language_id = 0; 
      if ($is_multilingual && $form_checker->post('language_id')) 
        $language_id = (int)$form_checker->post('language_id');
      else
        $language_id = $mailtemplate->language_id;

      $with_same_name_s = $this->mailtemplates->find_by_name($name);
      foreach ($with_same_name_s as $with_same_name)
      if ($with_same_name && $with_same_name->id != $item_id && $with_same_name->language_id == $language_id)
        $form_checker->add_error($this->_("This template indenficator is already taken for this language"));


      if ($form_checker->is_good())
      {
        $mailtemplate->name = $name;
        $mailtemplate->subject = $subject;
        $mailtemplate->content = $body;
        $mailtemplate->language_id = $language_id;

        $mailtemplate->save();
        $this->redirect("admin_mail", "templates");
      }
    }

    $this->ta("mailtemplate", $mailtemplate);
    $this->ta('form_checker', $form_checker);  
  }

  function newtemplate()
  {
    if (isset($_POST['cancel']))
      $this->redirect("admin_mail", "templates");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $form_checker = new checker;

    if (isset($_POST['save']))
    {

      $name = ""; if (isset($_POST['name'])) $name = $_POST['name'];
      $subject = ""; if (isset($_POST['subject'])) $subject = $_POST['subject'];
      $body = ""; if (isset($_POST['body'])) $body = $_POST['body'];

      $subject = strip_tags($subject);

      $name = strtolower(trim($name));
      $name = preg_replace('/[^a-z0-9_]/', '_', $name);
      $name = preg_replace('/_+/', "_", $name);
      $name = trim($name, "_");

      $form_checker->check($name, checker_rules::MIN_LENGTH(2), $this->_("Template identificator is too short"));
      $form_checker->check($name, checker_rules::MAX_LENGTH(255), $this->_("Template identificator is too long"));

      $form_checker->check($subject, checker_rules::MIN_LENGTH(2), $this->_("Subject is too short"));
      $form_checker->check($subject, checker_rules::MAX_LENGTH(999), $this->_("Subject is too long"));

      $form_checker->check($body, checker_rules::MIN_LENGTH(2), $this->_("Body content is too short"));
      $form_checker->check($body, checker_rules::MAX_LENGTH(30000), $this->_("Body content is too long"));

      $language_id = 0; 
      if ($is_multilingual && $form_checker->post('language_id')) 
        $language_id = (int)$form_checker->post('language_id');
      else
      {
        $default_language = $this->i18n_languages->get_by_is_default('1');
        if ($default_language)
          $language_id = $default_language->id;
        else
          $language_id = 0;
      }

      $with_same_name_s = $this->mailtemplates->find_by_name($name);
      foreach ($with_same_name_s as $with_same_name)
      if ($with_same_name && $with_same_name->language_id == $language_id)
        $form_checker->add_error($this->_("This template indenficator is already taken for this language"));

      if ($form_checker->is_good())
      {
        $mailtemplate = new mailtemplate;
        $mailtemplate->name = $name;
        $mailtemplate->subject = $subject;
        $mailtemplate->content = $body;
        $mailtemplate->language_id = $language_id;

        $mailtemplate->save();
        $this->redirect("admin_mail", "templates");
      }
    }
    $this->ta('form_checker', $form_checker);
  }

}