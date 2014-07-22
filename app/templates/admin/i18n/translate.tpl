{include file="admin/breadcrumbs.tpl" title="Translate"}

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  <input type="hidden" name="string_id" value="{if $form_checker->post('string_id')}{$form_checker->post('string_id')|escape:'html'}{else}{$item_to_tranlate.id|escape:'html'}{/if}">

  <div class="form-group">
  <label  for="title">{t}Original string{/t}</label>   <input type="text" name="original" value="{if $form_checker->post('original')}{$form_checker->post('original')|escape:'html'}{else}{$item_to_tranlate.string|escape:'html'}{/if}" id="original" class="form-control" disabled>
  </div>

  <div class="form-group">
  <label  for="title">{t language=$language.name}Translation to %1{/t}</label>   <input type="text" name="translation" value="{if $form_checker->post('translation')}{$form_checker->post('translation')|escape:'html'}{/if}" id="translation" class="form-control" placeholder="{t}Translation{/t}">
  </div>

  <div class="form-group">
  <div class="controls">    
    <input type="submit" name="save" value="{t}Save and jump to next string{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
    <input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>


<script>

  $(function(){
    $('#translation').focus();
  });

</script>