{include file="admin/breadcrumbs.tpl" title="Add"}

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}


  {if $is_multilingual}
  <div class="form-group">
  <label  for="portfolio_item_id">{t}Language{/t}</label>  <div class="controls clearfix">  
     <select class="selectpicker" name="language_id" id="language_id">
      {foreach from=$languages item=l}
      <option value="{$l->id}" {if $form_checker->post('language_id')}{if $form_checker->post('language_id') == $l->id}selected="selected"{/if}{else}{if $l->is_default == 1}selected="selected"{/if}{/if}>{t}{$l->name|escape:'html'}{/t}</option>
      {/foreach}
    </select>
  </div>
  </div>
  {/if}

  <div class="form-group">
  <label  for="title">{t}Title{/t}</label>   <input type="text" name="title" onkeyup="try_to_update_slug();" value="{$form_checker->post('title')|escape:'html'}"  id="title" class="form-control" placeholder="{t}Title{/t}">
  </div>

  <div class="form-group">
  <label  for="slug">{t}Slug{/t}</label>   <input type="text" name="slug" onchange="slug_updated();" value="{$form_checker->post('slug')|escape:'html'}"  id="slug" class="form-control" placeholder="{t}slug{/t}" >
  </div>

  <div class="form-group">
  <label  for="body">{t}Body{/t}</label>   <textarea id="body" name="body">{$form_checker->post('body')|escape:'html'}</textarea>
  </div>

  <div class="form-group">
  <label  for="body">{t}Description{/t}</label>   <textarea name="description" class="form-control" onchange="description_updated();" id="description">{$form_checker->post('description')|escape:'html'}</textarea>
  </div>

  {if $news_categories}  
  <div class="form-group" style="clear: both;">
  <label  for="categories">{t}Categories{/t}</label>  <div class="controls clearfix">  
     <select class="multiselect" name="categories[]" id="categories" multiple="multiple" class="btn-primary">
      {foreach from=$news_categories item=i}
      <option value="{$i->id}" {if $form_checker->post('categories') && in_array($i->id, $form_checker->post('categories'))}selected="selected"{/if}>{$i->name|escape:'html'}</option>
      {/foreach}
    </select>
  </div>
  </div>
  {/if}

  {include file="admin/media/image_input.tpl" label="Preview image" max_width="200" max_height="150" sub_directory="news_previews" input_name="preview_image"}
  
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



  String.prototype.translit = (function(){
      var L = {
  'А':'A','а':'a','Б':'B','б':'b','В':'V','в':'v','Г':'G','г':'g',
  'Д':'D','д':'d','Е':'E','е':'e','Ё':'Yo','ё':'yo','Ж':'Zh','ж':'zh',
  'З':'Z','з':'z','И':'I','и':'i','Й':'Y','й':'y','К':'K','к':'k',
  'Л':'L','л':'l','М':'M','м':'m','Н':'N','н':'n','О':'O','о':'o',
  'П':'P','п':'p','Р':'R','р':'r','С':'S','с':'s','Т':'T','т':'t',
  'У':'U','у':'u','Ф':'F','ф':'f','Х':'Kh','х':'kh','Ц':'Ts','ц':'ts',
  'Ч':'Ch','ч':'ch','Ш':'Sh','ш':'sh','Щ':'Sch','щ':'sch','Ъ':'"','ъ':'"',
  'Ы':'Y','ы':'y','Ь':"'",'ь':"'",'Э':'E','э':'e','Ю':'Yu','ю':'yu',
  'Я':'Ya','я':'ya'
          },
          r = '',
          k;
      for (k in L) r += k;
      r = new RegExp('[' + r + ']', 'g');
      k = function(a){
          return a in L ? L[a] : '';
      };
      return function(){
          return this.replace(r, k);
      };
  })();



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
  var title = $('#title').val().translit();
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