{include file="admin/breadcrumbs.tpl" title="New page"}

<div class="panel panel-default">
  <div class="panel-body">

<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  {if $is_multilingual}
  <div class="form-group">
    <label for="language_id">{t}Language{/t}</label>
    <div class="clearfix">  
      <select class="selectpicker" name="language_id" id="language_id">
        {foreach from=$languages item=l}
        <option value="{$l->id}" {if $form_checker->post('language_id')}{if $form_checker->post('language_id') == $l->id}selected="selected"{/if}{else}{if $l->is_default == 1}selected="selected"{/if}{/if}>{t}{$l->name|escape:'html'}{/t}</option>
        {/foreach}
      </select>
    </div>
  </div>
  {/if}

  <div class="form-group">
    <label for="title">{t}Title{/t}</label>  
    <input type="text" name="title" onkeyup="try_to_update_slug();" 
      value="{$form_checker->post('title')|escape:'html'}"  id="title" 
      class="form-control" placeholder="{t}Page title{/t}">
  </div>

  <div class="form-group">
  <label  for="slug">{t}Slug{/t}</label>   <input type="text" name="slug" onchange="slug_updated();" value="{$form_checker->post('slug')|escape:'html'}"  id="slug" class="form-control" placeholder="{t}slug{/t}" >
  </div>

  <div class="form-group">
  <label  for="body">{t}Body{/t}</label>   <textarea id="body" name="body">{$form_checker->post('body')|escape:'html'}</textarea>
  </div>

  <div class="form-group">
  <div class="controls">    
  	<input type="submit" name="save" value="{t}Save{/t}" id="save" class="btn btn-primary" data-loading-text="{t}Saving...{/t}" onclick="$(this).button('loading');">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>

</div>
</div>

{include file="admin/init_ckeditor.tpl" element="body"}
<script>
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


</script>
{/literal}