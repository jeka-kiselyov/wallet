{include file="admin/breadcrumbs.tpl" title="Facebook"}

  {if isset($saved) && $saved}
  <div class="alert alert-success">
    {t}Settings have been saved{/t}
  </div>
  {/if}

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}
<div class="row">
  <div class="col-xs-5">
<form method="post" onsubmit="">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">
  <input type="hidden" name="save" value="save">


  <div class="form-group">
    <label  for="facebook_app_id">{t}Application ID{/t}</label>   
    <input type="text" name="facebook_app_id"  class="form-control"
    value="{if $form_checker->post('facebook_app_id')}{$form_checker->post('facebook_app_id')|escape:'html'}{else}{$settings->facebook_app_id|escape:'html'}{/if}"  
    id="facebook_app_id" placeholder="{t}Facebook application ID{/t}">
  </div>

  <div class="form-group">
    <label  for="title">{t}Facebook secret key{/t}</label>   
    <input type="text" name="facebook_app_secret" class="form-control" 
    value="{if $form_checker->post('facebook_app_secret')}{$form_checker->post('facebook_app_secret')|escape:'html'}{else}{$settings->facebook_app_secret|escape:'html'}{/if}"  
    id="facebook_app_secret" placeholder="{t}Facebook application secret key{/t}">
  </div>

  <div class="form-group">
  <label>{t}Allow FB registration{/t}</label>  
  <div class="radio">
    <label>
      <input type="radio" name="user_allow_facebook_registration" id="user_allow_facebook_registration_1" value="1" 
      {if $settings.user_allow_facebook_registration} checked{/if}>
      {t}Yes{/t}
    </label>
  </div>
  <div class="radio">
    <label>
      <input type="radio" name="user_allow_facebook_registration" id="user_allow_facebook_registration_0" value="0" 
      {if !$settings.user_allow_facebook_registration} checked{/if}>
      {t}No{/t}
    </label>
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