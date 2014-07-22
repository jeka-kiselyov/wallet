{include file="admin/breadcrumbs.tpl" title="Add language"}
<div class="row">
  <div class="col-xs-5">

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}


  <div class="form-group">
  <label for="name">{t}Name{/t}</label>  
  <input type="text" name="name" value="{$form_checker->post('name')|escape:'html'}"  id="name" class="form-control" 
  placeholder="{t}Language name{/t}">
  </div>

  <div class="form-group">
  <label for="code">{t}Code{/t}</label>    
  <div class="clearfix">  
     <select class="selectpicker" name="code" id="code" class="form-control">
      <option value="">{t}Select{/t}</option>
      {include file='admin/i18n/language_codes_options.tpl' selected=$form_checker->post('code')}
     </select>
  </div>
  </div>

  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">

</form>

</div>
</div>