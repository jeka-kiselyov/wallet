{include file="admin/breadcrumbs.tpl" title="Details"}

<div class="panel panel-default"><div class="panel-body">


{if isset($saved) && $saved}
<div class="alert alert-success">
  {t}User info has been saved{/t}. <a href="{$settings->site_path}/admin/users" class="alert-link">{t}Back to the list{/t}</a>
</div>
{/if}

<div class="row">
  <div class="col-xs-5">
<form method="post" onsubmit="">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">


  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

	{input_text name="login" id="login" caption="Login" placeholder="Login" value=$user.login i18n=true getvaluefrompost=true bootstrap_horizontal=true}
	{input_text name="email" id="email" caption="Email" placeholder="Email" value=$user.email i18n=true getvaluefrompost=true bootstrap_horizontal=true}

<div  id="change_password_ask"> 
  <div class="form-group">
  <label for="password">{t}Password{/t}</label>
  <input type="button" class="btn btn-default" value="{t}Change{/t}" onclick=" $('#change_password_ask').hide();  $('#change_password_div').show(); $('#change_password_confirm').val('1'); $('#password').focus(); return false;">
  </div>
</div>
	
<div id="change_password_div" style="display: none;">
  <div class="form-group">
  <label for="password">{t}Password{/t}</label>
  <input type="hidden" name="change_password" value="0" id="change_password_confirm">
		<input type="password" name="password" value=""  id="password"  placeholder="{t}Password{/t}" class="form-control">
  </div>
  <div class="form-group"> 
		<input type="password" name="repeat_password" value=""  id="repeat_password"  placeholder="{t}Confirm password{/t}" class="form-control">
  </div>
</div>

{if $user.confirmation_code}
<div class="radio">
  <label>
    <input type="radio" name="is_banned" id="is_banned_2" value="2" checked>
    Not yet activated
  </label>
</div>
{/if}
<div class="radio">
  <label>
    <input type="radio" name="is_banned" id="is_banned_0" value="0" {if !$user.is_banned && !$user.confirmation_code} checked{/if}>
    Active
  </label>
</div>
<div class="radio">
  <label>
    <input type="radio" name="is_banned" id="is_banned_1" value="1" {if $user.is_banned} checked{/if}>
    Banned
  </label>
</div>

  {if $is_multilingual}
  <div class="form-group">
  <label  for="language_id">{t}Language{/t}</label>  <div class="controls clearfix">  
     <select class="selectpicker" name="language_id" id="language_id">
      {foreach from=$languages item=l}
      <option value="{$l->id}" {if $form_checker->post('language_id')}{if $form_checker->post('language_id') == $l->id}selected="selected"{/if}{else}{if $user->language_id == $l->id}selected="selected"{/if}{/if}>{t}{$l->name|escape:'html'}{/t}</option>
      {/foreach}
    </select>
  </div>
  </div>
  {/if}

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save changes{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>
</form>

</div>
<div class="col-xs-7">

{if $user.registration_date}
  <div class="form-group">
  <label  for="email">{t}Registration date{/t}</label>  
  <div class="controls"><div class="help-block" style="padding-top: 5px;">{$user.registration_date|date_format:"%b %e, %Y %H:%M"}</div>
  </div>
  </div>
{/if}


{if $user.registration_ip}
  <div class="form-group">
  <label  for="email">{t}Registration IP{/t}</label>  
  <div class="controls"><div class="help-block" style="padding-top: 5px;">{$user.registration_ip}</div>
  </div>
  </div>
{/if}



{if $user.activity_date}
  <div class="form-group">
  <label  for="email">{t}Activity date{/t}</label>  
  <div class="controls"><div class="help-block" style="padding-top: 5px;">{$user.activity_date|date_format:"%b %e, %Y %H:%M"}</div>
  </div>
  </div>
{/if}


{if $user.activity_ip}
  <div class="form-group">
  <label  for="email">{t}Last IP{/t}</label>  
  <div class="controls"><div class="help-block" style="padding-top: 5px;">{$user.activity_ip}</div>
  </div>
  </div>
{/if}

</div>
</div>


</div></div><!-- <div class="panel panel-default"><div class="panel-body"> -->