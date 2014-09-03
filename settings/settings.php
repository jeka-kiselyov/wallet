<?php

 $settings = array();

 // Site path
 if (ENVIRONMENT_SERVER == 'localhost')
  $settings['site_path'] = "http://wallet.com";
   else
    $settings['site_path'] = "";

 // General settings
 $settings['site_title'] = "Wallet";
 $settings['timezone'] = "UTC";
 $settings['version'] = '1.12335';

 // MVC settings
 $settings['mvc']['enable_magic_entities_and_models'] = true;

 // Registration settings
 $settings['require_email_confirmation'] = false;

 // i18n settings
 $settings['i18n_gather_strings'] = true;

 $settings['openexchangerates_api_key'] = '';

 // FaceBook settings
 $settings['user_allow_facebook_registration'] = false;
 $settings['facebook_app_id'] = "*****";
 $settings['facebook_app_secret'] = "*****";

 // VK settings
 $settings['user_allow_vk_registration'] = false;
 $settings['vk_app_id'] = "*****";
 $settings['vk_app_secret'] = "*****";

 // Recaptcha settings
 $settings['recaptcha_public_key'] = '6LeT-NsSAAAAAHh2ko-GUahJc3noqniuqcfJE72-';
 $settings['recaptcha_private_key'] = '6LeT-NsSAAAAAGlHEDdXvRWjOJla6XbbA0_IZCFp';

 // Yacaptcha settings
 $settings['yandex_cleanweb_api_key'] = 'cw.1.1.20130821T162324Z.01068edc7a9b1c8c.298142bd1b31d2d0724bb166bd87711e04a3de6e';

 // MailChimp settings
 $settings['mailchimp_api_key'] = 'XXX-us6';
 $settings['mailchimp_main_list'] = 'Main User List';

 // CKFinder settings
 $settings['ckfinder_enabled'] = true;
 $settings['ckfinder_separate_folder_for_each_user'] = true;
 $settings['ckfinder_allow_users_to_create_subfolders'] = true;


 // Mail settings. Edit this in admin panel (Mail->Settings)
 $settings['contact_email'] = "jeka911@gmail.com";
 $settings['mail_method'] = 'mail';  /// smtp, mail, sendmail
 $settings['mail_default_from_email'] = 'example@example.com';  
 $settings['mail_default_from_name'] = 'Example Administrator';  
 $settings['sendmail_path'] = '/var/qmail/bin/sendmail';  
 $settings['smtp_auth'] = true;
 $settings['smtp_secure'] = "ssl";
 $settings['smtp_host'] = "email-smtp.us-east-1.amazonaws.com";
 $settings['smtp_port'] = 465;
 $settings['smtp_username'] = "************";
 $settings['smtp_password'] = "************";

 // Minify settings
 $settings['minify_css_merge'] = false;
 $settings['minify_css_minify'] = false;
 $settings['minify_css_cache'] = false;

 $settings['minify_js_merge'] = false;

 // Testing settings
 $settings['tests']['jasmine']['enabled'] = false;

 // Cache settings
 $settings['cache']['enable_smarty_cache'] = false;
 $settings['cache']['enable_system_cache'] = true;

 $settings['cache']['smarty']['cache_lifetime'] = 300;

 $settings['cache']['method'] = 'file';  // 'file' or 'memory'
 $settings['cache']['file']['cache_dir'] = SITE_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."system_cache";
 $settings['cache']['file']['cache_prefix'] = "cache_";
 $settings['cache']['file']['directory_level'] = 1;

 $settings['cache']['memory']['compression'] = true;
 $settings['cache']['memory']['host'] = 'localhost';
 $settings['cache']['memory']['port'] = '11211';
 $settings['cache']['memory']['persistent'] = true;


 // List of settings can be saved in db and edited from admin panel
 $settings['rewritable_settings'] = array('site_title', 'contact_email', 'require_email_confirmation', 'user_allow_facebook_registration'
 	,'facebook_app_id','facebook_app_secret','user_allow_vk_registration','vk_app_id','vk_app_secret','mail_method','smtp_auth','smtp_host'
 	,'smtp_secure','smtp_port','smtp_username','smtp_password','mail_default_from_email','mail_default_from_name','sendmail_path'
 	,'recaptcha_public_key', 'recaptcha_private_key', 'openexchangerates_api_key');

 // Allow to create custom settings, but not defined in this file
 $settings['rewritable_creation_enabled'] = true;
 return $settings;

?>