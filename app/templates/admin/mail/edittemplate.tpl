{include file="admin/breadcrumbs.tpl" title="Edit template"}

<form method="post">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  {if $is_multilingual}
  <div class="form-group">
  <label  for="portfolio_item_id">{t}Language{/t}</label>  <div class="controls clearfix">  
     <select class="selectpicker" name="language_id" id="language_id">
      {foreach from=$languages item=l}
      <option value="{$l->id}" {if $form_checker->post('language_id')}{if $form_checker->post('language_id') == $l->id}selected="selected"{/if}{else}{if $mailtemplate->language_id == $l->id}selected="selected"{/if}{/if}>{t}{$l->name|escape:'html'}{/t}</option>
      {/foreach}
    </select>
  </div>
  </div>
  {/if}

  <div class="form-group">
  <label  for="subject">{t}Template identificator{/t}</label>   <input type="text" name="name" value="{if isset($smarty.post.name)}{$smarty.post.name|escape:'html'}{else}{$mailtemplate.name|escape:'html'}{/if}"  id="name" class="form-control" placeholder="{t}Template identificator{/t}">
  </div>

  <div class="form-group">
  <label  for="subject">{t}Subject{/t}</label>   <input type="text" name="subject" value="{if isset($smarty.post.subject)}{$smarty.post.subject|escape:'html'}{else}{$mailtemplate.subject|escape:'html'}{/if}"  id="subject" class="form-control" placeholder="{t}Subject{/t}" >
  </div>

  <div class="form-group">
  <label  for="subject">{t}Body{/t}</label>   <textarea id="body" name="body">{if isset($smarty.post.body)}{$smarty.post.body|escape:'html'}{else}{$mailtemplate.content|escape:'html'}{/if}</textarea>
  </div>

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>

{include file="admin/init_ckeditor.tpl" element="body"}