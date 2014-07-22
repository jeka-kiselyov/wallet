{include file="admin/breadcrumbs.tpl" title="Edit strings"}


<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}


  <div class="form-group">
  <label  for="title">{t}Original string{/t}</label>   <input type="text" name="string" value="{if $form_checker->post('string')}{$form_checker->post('string')|escape:'html'}{else}{$item->string|escape:'html'}{/if}"  id="string" class="form-control" placeholder="{t}Original string{/t}">
  </div>

  {foreach from=$form_translations item=t}
  <div class="form-group">
  <label  for="title">{t name=$t.language_name}Translation to %1{/t}</label>   <input type="text" name="translation_{$t.language_id}" value="{$t.translation|escape:'html'}"  id="translation_{$t.language_id}" class="form-control" placeholder="{t}Translation{/t}" onclick="$(this).focus(); $(this).select();">
  </div>
  {/foreach}

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>
