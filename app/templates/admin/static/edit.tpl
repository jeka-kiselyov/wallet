{include file="admin/breadcrumbs.tpl" title="Edit page"}

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  {if $is_multilingual}
  <div class="form-group">
  <label  for="language_id">{t}Language{/t}</label>  <div class="controls clearfix">  
     <select class="selectpicker" name="language_id" id="language_id">
      {foreach from=$languages item=l}
      <option value="{$l->id}" {if $form_checker->post('language_id')}{if $form_checker->post('language_id') == $l->id}selected="selected"{/if}{else}{if $static_page->language_id == $l->id}selected="selected"{/if}{/if}>{t}{$l->name|escape:'html'}{/t}</option>
      {/foreach}
    </select>
  </div>
  </div>
  {/if}

  <div class="form-group">
  <label  for="title">{t}Title{/t}</label>   <input type="text" name="title" value="{if $form_checker->post('title')}{$form_checker->post('title')|escape:'html'}{else}{$static_page->title|escape:'html'}{/if}"  id="title" class="form-control" placeholder="{t}Page title{/t}">
  </div>

  <div class="form-group">
  <label  for="slug">{t}Slug{/t}</label>   <input type="text" name="slug" value="{if $form_checker->post('slug')}{$form_checker->post('slug')|escape:'html'}{else}{$static_page->slug|escape:'html'}{/if}"  id="slug" class="form-control" placeholder="{t}slug{/t}" >
  </div>

  <div class="form-group">
  <label  for="body">{t}Body{/t}</label>   <textarea id="body" name="body">{if $form_checker->post('body')}{$form_checker->post('body')|escape:'html'}{else}{$static_page->body|escape:'html'}{/if}</textarea>
  </div>

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>

{include file="admin/init_ckeditor.tpl" element="body"}