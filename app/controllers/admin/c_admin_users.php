<?php

class controller_admin_users extends admin_controller
{
  protected $db_table_name = 'users';
  protected $search_fields = array("login", "email", "type");

  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('users');
  }
  
  public function index()
  {
     $this->redirect("admin_users", "manage");
  }

  public function edit() { $this->redirect("admin_users", "manage"); }
  public function add() { $this->redirect("admin_users", "manage"); }

  function details()
  {
  	$user_id = (int)$this->gp(0);
    $user = false;
    if (!$user_id || !($user = $this->users->get_by_id($user_id)))
      $this->redirect("admin_users", "manage");

    if (isset($_POST['cancel']))
      $this->redirect("admin_users", "manage");

    $languages = $this->i18n_languages->get_all();
    $is_multilingual = false; if (count($languages) > 1) $is_multilingual = true;

    $this->ta('is_multilingual', $is_multilingual);
    $this->ta('languages', $languages);

    $form_checker = new checker;

    if (isset($_POST['save']) && $form_checker->check_security_token())
    {
      $form_checker->check_post('login', checker_rules::MIN_LENGTH(2), $this->_("Login is too short"));
      $form_checker->check_post('login', checker_rules::MAX_LENGTH(100), $this->_("Login is too long"));
      $form_checker->check_post('email', checker_rules::EMAIL(), $this->_("Invalid email"));
      $form_checker->check_post('email', checker_rules::MAX_LENGTH(100), $this->_("Email is too long"));
      $form_checker->check_post('is_banned', checker_rules::ONE_OF(0,1,2), $this->_("Error in banning user"));

      if ($form_checker->is_good())
      {
        $same_login_users = $this->users->find_by_login($form_checker->post('login'));
        $taken = false;
        if ($same_login_users)
        foreach ($same_login_users as $same) 
          if ($same->id != $user->id)
            $taken = true;

        if ($taken)
          $form_checker->add_error($this->_("Login is taken by other user"), "login");

        $same_email_users = $this->users->find_by_email($form_checker->post('email'));
        $taken = false;
        if ($same_email_users)
        foreach ($same_email_users as $same) 
          if ($same->id != $user->id)
            $taken = true;

        if ($taken)
          $form_checker->add_error($this->_("Email is taken by other user"), "email");        
      }

      $language_id = 0; 
      if ($is_multilingual && $form_checker->post('language_id')) 
        $language_id = (int)$form_checker->post('language_id');
      else
        $language_id = $user->language_id;

      if ($form_checker->is_good())
      {
        if ($_POST['change_password'])
        {
          $repeat_password = ''; if (isset($_POST['repeat_password'])) $repeat_password = $_POST['repeat_password'];
          $form_checker->check_post('password', checker_rules::EQUAL($repeat_password), $this->_("Please check password"));
          $form_checker->check_post('password', checker_rules::MIN_LENGTH(5), $this->_("Password is too short"));

          if ($form_checker->is_good())
          {
            $user->password = md5($form_checker->post('password').$this->users->get_password_salt());
            $user->save();
          }
        }
      }

      if ($form_checker->is_good())
      {
        $user->login = $form_checker->post('login');
        $user->email = $form_checker->post('email');
        if ((int)$form_checker->post('is_banned') != 2)
        {
          $user->is_banned = (int)$form_checker->post('is_banned');
          if ($user->confirmation_code)
            $user->confirmation_code = '';
        }
        $user->language_id = $language_id;

        $user->save();
        $this->ta("saved", "saved");
      }
    }

    $this->ta("user", $user);

    $this->ta("form_checker", $form_checker);
  }



}