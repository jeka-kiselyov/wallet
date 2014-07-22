{include file="admin/breadcrumbs.tpl" title="Edit Category"}


<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  <div class="form-group">
  <label  for="name">{t}Name{/t}</label>   <input type="text" name="name" value="{if $form_checker->post('name')}{$form_checker->post('name')|escape:'html'}{else}{$news_category->name|escape:'html'}{/if}"  id="name" class="form-control" placeholder="{t}Category name{/t}">
  </div>
  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>
