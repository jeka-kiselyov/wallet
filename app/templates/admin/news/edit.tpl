{include file="admin/breadcrumbs.tpl" title="Edit"}

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
      <option value="{$l->id}" {if $form_checker->post('language_id')}{if $form_checker->post('language_id') == $l->id}selected="selected"{/if}{else}{if $news_item->language_id == $l->id}selected="selected"{/if}{/if}>{t}{$l->name|escape:'html'}{/t}</option>
      {/foreach}
    </select>
  </div>
  </div>
  {/if}

  <div class="form-group">
  <label  for="title">{t}Title{/t}</label>   <input type="text" name="title" onkeyup="try_to_update_slug();" value="{if $form_checker->post('title')}{$form_checker->post('title')|escape:'html'}{else}{$news_item->title|escape:'html'}{/if}"  id="title" class="form-control" placeholder="{t}Title{/t}">
  </div>

  <div class="form-group">
  <label  for="slug">{t}Slug{/t}</label>   <input type="text" name="slug" onchange="slug_updated();" value="{if $form_checker->post('slug')}{$form_checker->post('slug')|escape:'html'}{else}{$news_item->slug|escape:'html'}{/if}"  id="slug" class="form-control" placeholder="{t}slug{/t}" >
  </div>

  <div class="form-group">
  <label  for="body">{t}Body{/t}</label>   <textarea id="body" name="body">{if $form_checker->post('body')}{$form_checker->post('body')|escape:'html'}{else}{$news_item->body|escape:'html'}{/if}</textarea>
  </div>

  <div class="form-group">
  <label  for="body">{t}Description{/t}</label>   <textarea name="description" class="form-control" onchange="description_updated();" id="description">{if $form_checker->post('description')}{$form_checker->post('description')|escape:'html'}{else}{$news_item->description|escape:'html'}{/if}</textarea>
  </div>

  <div class="form-group" style="clear: both;">
  <label  for="categories">{t}Categories{/t}</label>  <div class="controls clearfix">  
     <select class="multiselect" name="categories[]" id="categories" multiple="multiple">
      {if $form_checker->post('categories')}
      {foreach from=$news_categories item=i}
      <option value="{$i->id}" {if in_array($i->id, $form_checker->post('categories'))}selected="selected"{/if}>{$i->name|escape:'html'}</option>
      {/foreach}
      {else}
      {foreach from=$news_categories item=i}
      <option value="{$i->id}" {if $news_item->is_in_category($i->id)}selected="selected"{/if}>{$i->name|escape:'html'}</option>
      {/foreach}
      {/if}
    </select>
  </div>
  </div>

  {include file="admin/media/image_input.tpl" label="Preview image" max_width="200" max_height="150" sub_directory="news_previews" input_name="preview_image" current_value=$news_item->preview_image}

  <div class="form-group" style="clear: both;">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>

{include file="admin/init_ckeditor.tpl" element="body"}

<script>

{literal}
 $(function(){
  $(".category_checkbox").each(function(){

      var name = $(this).children('span').html();
      var id = $(this).children('input').attr('id').split('_')[2];
      var html = "<button type=\"button\" class=\"btn\" id=\"category_checkbox_button_"+id+"\">"+name+"</button>";

      $(html).insertAfter(this).click(function(){ 
        var id = $(this).attr('id').split('_')[3];
        if ($(this).hasClass('active'))
        {
          $(this).removeClass('active');
          $('#category_checkbox_'+id).prop('checked', false);
        }
        else
        {
          $(this).addClass('active');
          $('#category_checkbox_'+id).prop('checked', true);
        }
      });

      if ($('#category_checkbox_'+id).prop('checked'))
        $('#category_checkbox_button_'+id).addClass('active');

      $(this).hide();
  });
 });
{/literal}










 var is_description_updated_manually = {if !$form_checker->post('description')}false{else}true{/if};
 var is_slug_updated_manually = {if !$form_checker->post('slug')}false{else}true{/if};
 {literal}
 function slug_updated()
 {
  is_slug_updated_manually = true;
  return true;
 }

 function try_to_update_slug()
 {
  var title = $('#title').val();
  if (is_slug_updated_manually) return;
  var slug = title.replace(/^\s+|\s+$/g, ''); // trim
  slug = slug.toLowerCase();

  slug = slug.replace(/[^a-z0-9 _]/g, '') // remove invalid chars
    .replace(/\s+/g, '_') // collapse whitespace and replace by -
    .replace(/_+/g, '_'); // collapse dashes

  $('#slug').val(slug);
 }

 function description_updated()
 {
  is_description_updated_manually = true;
  return true;
 }

 function try_to_update_description()
 {
  if (is_description_updated_manually) return;
  var body = CKEDITOR.instances['body'].getData();
  body = $(body).text();
  body = body.replace(/\n/g," ");
  body = body.replace(/\s+/g, ' ');

  var length = 255; 
  var trimmed_body = body.length > length ? body.substring(0, length - 3) + "..." : body.substring(0, length);

  $('#description').val(trimmed_body);
 }


$(function() {
CKEDITOR.instances['body'].on('blur', function() { try_to_update_description(); });
});

</script>
{/literal}