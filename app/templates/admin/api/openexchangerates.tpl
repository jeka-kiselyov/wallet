{include file="admin/breadcrumbs.tpl" title="Openexchangerates"}

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
<div class="row">
  <div class="col-xs-5">
<form method="post" onsubmit="">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">
  <input type="hidden" name="save" value="save">


  <div class="form-group">
  <label  for="title">{t}Openexchangerates API key{/t}</label>   
  <input type="text" name="openexchangerates_api_key" class="form-control" value="{if $form_checker->post('openexchangerates_api_key')}{$form_checker->post('openexchangerates_api_key')|escape:'html'}{else}{$settings->openexchangerates_api_key|escape:'html'}{/if}"  id="openexchangerates_api_key" placeholder="{t}Openexchangerates API key{/t}">
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