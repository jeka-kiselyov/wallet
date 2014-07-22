{include file="admin/breadcrumbs.tpl" title="Settings"}

<div class="row">
  <div class="col-xs-5">
<form method="post" onsubmit="">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">
  <input type="hidden" name="save" value="save">

	{if isset($saved) && $saved}
	<div class="alert alert-success">
		{t}Settings have been saved{/t}
	</div>
	{/if}

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  <div class="form-group">
    <label  for="mail_default_from_email">{t}From email{/t}</label>  
     <input type="text" name="mail_default_from_email" value="{if isset($smarty.post.mail_default_from_email)}{$smarty.post.mail_default_from_email|escape:'html'}{else}{$settings.mail_default_from_email|escape:'html'}{/if}"  id="mail_default_from_email"  placeholder="example@example.com" class="form-control">
  </div>

  <div class="form-group">
    <label  for="mail_default_from_name">{t}From name{/t}</label>  
     <input type="text" name="mail_default_from_name" value="{if isset($smarty.post.mail_default_from_name)}{$smarty.post.mail_default_from_name|escape:'html'}{else}{$settings.mail_default_from_name|escape:'html'}{/if}"  id="mail_default_from_name"  placeholder="{t}Name{/t}" class="form-control">
  </div>

  <div class="form-group">
  <label>{t}Send method{/t}</label>  
    <div class="radio">
      <label>
        <input type="radio" name="mail_method" id="mail_method_mail" value="mail" {if $settings.mail_method == 'mail'} checked{/if}
        onchange="if (this.checked) { $('#div_smtp_settings').hide(); $('#div_sendmail_settings').hide(); };" 
        >
        Send with mail() function
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="mail_method" id="mail_method_smtp" value="smtp" {if $settings.mail_method == 'smtp'} checked{/if}
        onchange="if (this.checked) { $('#div_smtp_settings').show(); $('#div_sendmail_settings').hide(); };" 
        >
        Send via SMTP server
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="mail_method" id="mail_method_sendmail" value="sendmail" {if $settings.mail_method == 'sendmail'} checked{/if}
        onchange="if (this.checked) { $('#div_smtp_settings').hide(); $('#div_sendmail_settings').show(); };" 
        >
        Send with sendmail
      </label>
    </div>
  </div>


  <div id="div_sendmail_settings" {if $settings.mail_method != 'sendmail' || (isset($smarty.post.mail_method)  && $smarty.post.mail_method != 'sendmail')} style="display: none;"{/if}>

    <div class="form-group">
      <label  for="sendmail_path">{t}Sendmail path{/t}</label>  
       <input type="text" name="sendmail_path" value="{if isset($smarty.post.sendmail_path)}{$smarty.post.sendmail_path|escape:'html'}{else}{$settings.sendmail_path|escape:'html'}{/if}"  id="sendmail_path"  placeholder="/usr/sbin/sendmail"  class="form-control">
    </div>

  </div>

  <div id="div_smtp_settings" {if $settings.mail_method != 'smtp' || (isset($smarty.post.mail_method)  && $smarty.post.mail_method != 'smtp')} style="display: none;"{/if}>


    <div class="form-group">
      <label  for="smtp_host">{t}SMTP host{/t}</label>  
       <input type="text" name="smtp_host" value="{if isset($smarty.post.smtp_host)}{$smarty.post.smtp_host|escape:'html'}{else}{$settings.smtp_host|escape:'html'}{/if}"  id="smtp_host"  placeholder="hostname"  class="form-control">
    </div>

    <div class="form-group">
      <label  for="smtp_host">{t}SMTP port{/t}</label>  
       <input type="text" name="smtp_port" value="{if isset($smarty.post.smtp_port)}{$smarty.post.smtp_port|escape:'html'}{else}{$settings.smtp_port|escape:'html'}{/if}"  id="smtp_port"  placeholder="25"  class="form-control">
    </div>

    <div class="form-group">
    <label class="control-label">{t}SMTP secure{/t}</label>  
    <div class="controls">
      <div class="radio">
        <label>
          <input type="radio" name="smtp_secure" id="mail_method_sendmail" value="" {if $settings.smtp_secure == ''} checked{/if}>
          {t}No{/t}
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="smtp_secure" id="mail_method_sendmail" value="ssl" {if $settings.smtp_secure == 'ssl'} checked{/if}>
          SSL
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="smtp_secure" id="mail_method_sendmail" value="tls" {if $settings.smtp_secure == 'tls'} checked{/if}>
          TLS
        </label>
      </div>
    </div>
    </div>

    <div class="form-group">
    <label class="control-label">{t}Require auth{/t}</label>  
    <div class="controls">
      <div class="radio">
        <label>
          <input type="radio" name="smtp_auth" id="mail_method_sendmail" value="1" {if $settings.smtp_auth} checked{/if}
            onchange="if (this.checked) { $('#div_smtp_auth').show(); };" 
            >
          {t}Yes{/t}
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="smtp_auth" id="mail_method_sendmail" value="0" {if !$settings.smtp_auth} checked{/if}
            onchange="if (this.checked) { $('#div_smtp_auth').hide(); };" 
            >
          {t}No{/t}
        </label>
      </div>
    </div>
    </div>

    <div  id="div_smtp_auth" {if !$settings.smtp_auth || (isset($smarty.post.smtp_auth)  && !$smarty.post.smtp_auth)} style="display: none;"{/if}>

      <div class="form-group">
        <label  for="smtp_username">{t}SMTP username{/t}</label>  
         <input type="text" name="smtp_username" value="{if isset($smarty.post.smtp_username)}{$smarty.post.smtp_username|escape:'html'}{else}{$settings.smtp_username|escape:'html'}{/if}"  id="smtp_username"  placeholder="{t}username{/t}"  class="form-control">
      </div>

      <div class="form-group">
        <label  for="smtp_password">{t}SMTP password{/t}</label>  
         <input type="password" name="smtp_password" value="{if isset($smarty.post.smtp_password)}{$smarty.post.smtp_password|escape:'html'}{else}{$settings.smtp_password|escape:'html'}{/if}"  id="smtp_password"  placeholder="{t}password{/t}"  class="form-control">
      </div>

    </div>



  </div>

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save changes{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>
</form>

</div>
</div>