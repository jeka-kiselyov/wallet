{include file="admin/breadcrumbs.tpl" title="Edit language"}

<div class="row">
  <div class="col-xs-5">

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}


  <div class="form-group">
  <label for="title">{t}Name{/t}</label>
  <input type="text" name="name" 
    value="{if $form_checker->post('name')}{$form_checker->post('name')|escape:'html'}{else}{$item->name|escape:'html'}{/if}"  
    id="name" class="form-control form-control" placeholder="{t}Language name{/t}">
  </div>

  {if $item->is_default == 0}
  <div class="form-group">
  <label  for="slug">{t}Code{/t}</label>    <div class="controls clearfix">  
     <select class="selectpicker" name="code" id="code">
      <option value="">{t}Select{/t}</option>
      {if $form_checker->post('code')}
        {include file='admin/i18n/language_codes_options.tpl' selected=$form_checker->post('code')}
      {else}
        {include file='admin/i18n/language_codes_options.tpl' selected=$item->code}
      {/if}
     </select>
  </div>
  </div>
  {/if}

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>

</div>
</div>