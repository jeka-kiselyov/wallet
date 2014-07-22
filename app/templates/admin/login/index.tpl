{include file="admin/breadcrumbs.tpl" title="Sign In"}

<div class="row">
  <div class="col-sm-5">
<form method="post">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

	{input_text name="username" id="username" caption="Username" placeholder="Username" getvaluefrompost=true bootstrap_horizontal=true i18n=true}
	{input_password name="password" id="password" caption="Password" placeholder="Password" getvaluefrompost=true bootstrap_horizontal=true i18n=true}


  <div class="form-group">
  <div class="controls">    
    <input type="submit" name="signin" value="{t}Sign In{/t}" id="signin" class="btn btn-primary" data-loading-text="{t}Signing in...{/t}" onclick="$(this).button('loading');">
    <a href="{$settings->site_path}" class="btn btn-info">{t}Back to{/t} {$settings->site_title|escape:'html'}</a>
  </div>
  </div>


</form>

  {literal}
  <script>
    $(function(){$('#username').focus()});
  </script>
  {/literal}
  </div>
</div>