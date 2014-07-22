<?php

class mailer extends singleton_base 
{
  public $mailer = null;

  public $language_id = false;
  public $default_language_id = false;

  function __construct() 
  {
    parent::__construct();
    $this->init_phpmailer();

    $this->language_id = $this->i18n->get_current_language_id();
    $this->default_language_id = $this->i18n->get_default_language_id();
  }

  private function init_phpmailer()
  {
    require_once(SITE_PATH_LIBS."phpmailer/class.phpmailer.php");
    $this->mailer = new PHPMailer();

    if ($this->registry->settings->mail_method == 'smtp')
    {
      $this->mailer->IsSMTP(); // telling the class to use SMTP
      $this->mailer->SMTPDebug  = 0;          
      $this->mailer->SMTPAuth   = $this->registry->settings->smtp_auth;     
      $this->mailer->SMTPSecure = $this->registry->settings->smtp_secure;     
      $this->mailer->Host       = $this->registry->settings->smtp_host;
      $this->mailer->Port       = $this->registry->settings->smtp_port; 
      $this->mailer->Username   = $this->registry->settings->smtp_username;
      $this->mailer->Password   = $this->registry->settings->smtp_password;
    }
    elseif ($this->registry->settings->mail_method == 'mail')
    {
      $this->mailer->IsMail();
    }
    elseif ($this->registry->settings->mail_method == 'sendmail')
    {
      $this->mailer->IsSendmail();
      $this->mailer->Sendmail = $this->registry->settings->sendmail_path;
    }

    return true;
  }

  public function get_template($template_name, $language_id = false)
  {
    //@todo: cache templates
    if (!$language_id)
      $language_id = $this->language_id;

    $template = $this->db->getrow("SELECT content, subject FROM mailtemplates WHERE name=? AND language_id=?", array($template_name, $language_id));
    if (!$template)
      $template = $this->db->getrow("SELECT content, subject FROM mailtemplates WHERE name=? AND language_id=?", array($template_name, $this->default_language_id));  

    if ($template)
      return $template;
    else
      return array('content'=>'', 'subject'=>'');
  }

  public function get_template_with_replaces($template_name, $vars, $language_id = false)
  {
    if (!isset($vars['site_path'])) 
      $vars['site_path'] = $this->registry->settings->site_path;
    
    $tpl = $this->get_template($template_name, $language_id);
    foreach ($vars as $key => $value) 
    {
      $tpl['content'] = str_replace("%".$key."%", $value, $tpl['content']);  // template tags replaces
      $tpl['subject'] = str_replace("%".$key."%", $value, $tpl['subject']);  // template tags replaces
    }

    return $tpl;
  }

  function send_to_user_id($user_id, $template_name, $vars = false, $language_id = false)
  {
    if (!$vars) 
      $vars = array();

    $user = $this->users->get_by_id($user_id);
    if (!$language_id && $user->language_id)
      $language_id = $user->language_id;

    if (!isset($vars['user_email'])) $vars['user_email'] = $user->email;
    if (!isset($vars['user_name'])) $vars['user_name'] = $user->login;
    if (!isset($vars['user_registration_ip'])) $vars['user_registration_ip'] = $user->registration_ip;
    
    $message = $this->get_template_with_replaces($template_name, $vars, $language_id);

    return $this->send($user['email'], $message['subject'], $message['content']);
  }

  function send_to_admin($from_email, $template_name, $vars)
  {
    $message = $this->get_template_with_replaces($template_name, $vars); 
    return $this->send($this->registry->settings->contact_email, $message['subject'], $message['content'], $from_email);
  }

  function send($email, $subject, $body, $reply_to = false)
  {
    $from_email = $this->registry->settings->mail_default_from_email;
    $from_name = $this->registry->settings->mail_default_from_name;

    if (!$reply_to)
      $reply_to = $from_email;

    $this->mailer->SetFrom($from_email, $from_name, false);
    $this->mailer->AddReplyTo($reply_to);
    $this->mailer->Subject = $subject;
    $this->mailer->MsgHTML($body);

    $this->mailer->AddAddress($email);

    if(!$this->mailer->Send()) {
      return false;
    } else {
      return true;
    }
  }

}




