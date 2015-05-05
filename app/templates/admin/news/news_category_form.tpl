<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  <div class="form-group clearfix">
    <label for="name">{t}Name{/t}</label>
    <input type="text" class="form-control" 
      name="name" id="name" placeholder="{t}Name{/t}" 
      onkeyup="try_to_update_slug();" 
      value="{$form_checker->post('name')|default:$item->name|default:''|escape:'html'}"/>
  </div>

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>
<script>
  $(function(){
    $('#name').focus();
  });
</script>