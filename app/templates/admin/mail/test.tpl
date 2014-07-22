{include file="admin/breadcrumbs.tpl" title="Send test email"}

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}
  {if isset($result)}
    <div class="alert alert-info">
      {$result}
    </div>
  {/if}

  <div class="form-group">
  <label  for="subject">{t}To email{/t}</label>   
    <div class="input-group">
      <input type="text"  name="to" value="{if isset($smarty.post.to)}{$smarty.post.to|escape:'html'}{else}{/if}" id="to"  class="form-control" placeholder="{t}example@example.com{/t}">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button" onclick="$('#to').val('{$admin_email|escape:'html'}'); return false;">Default 'From' Email</button>
      </span>
    </div><!-- /input-group -->
  </div>


  <div class="form-group">
  <label  for="subject">{t}Subject{/t}</label>   <input type="text" name="subject" value="{if isset($smarty.post.subject)}{$smarty.post.subject|escape:'html'}{else}Test{/if}"  id="subject" class="form-control" placeholder="{t}Subject{/t}" >
  </div>

  <div class="form-group">
  <label  for="subject">{t}Body{/t}</label>   <textarea id="body" name="body">{if isset($smarty.post.body)}{$smarty.post.body|escape:'html'}{else}Test<br>And <b>some simple html</b>{/if}</textarea>
  </div>

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="send" value="{t}Send{/t}" id="send" class="btn btn-primary" data-loading-text="{t}Sending...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>

{include file="admin/init_ckeditor.tpl" element="body"}