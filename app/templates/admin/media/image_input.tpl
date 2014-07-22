
{assign var='current_value' value=$current_value|default:''}
{assign var='input_name' value=$input_name|default:'image_filename'}
{assign var='sub_directory' value=$sub_directory|default:''}
{assign var='max_width' value=$max_width|default:'128'}
{assign var='max_height' value=$max_height|default:'128'}

<div class="form-group">
  <label  for="image">{$label|default:'Image'}</label>  
  <div class="controls" id="{$input_name}_container">    
    <input type="hidden" name="{$input_name}" id="{$input_name}" value="{if $form_checker->post('{$input_name}')}{$form_checker->post('{$input_name}')|escape:'html'}{else}{$current_value}{/if}">
    <div style="float: left; width: {$max_width}px; height: {$max_height}px; border: 1px dashed #aaa; background-repeat: no-repeat; margin-bottom: 10px; background-position: center center; " 
    class="preview_area">
      <div style="width: {$max_width}px; height: {$max_height}px; background-position: center center; background-repeat: no-repeat; background-image: url({$settings->site_path}/images/loading.gif); display: none;" class="preview_area_loading"></div>
    </div>
    <input type="button" name="change" value="{$change_text|default:'Change'}" id="change_button" class="btn btn-primary" onclick="$('#{$input_name}_file').click();">
    <input type="file" name="image" id="{$input_name}_file" style="visibility: hidden;">
  </div>
</div>


<script>
{literal}
$(function() 
{ 
  var image_filename = $('#{/literal}{$input_name}{literal}').val();
  if (image_filename)
  {
    $('#{/literal}{$input_name}{literal}_container').find('.preview_area').css('background-image', 'url({/literal}{$settings->site_path}{literal}/uploads/images/{/literal}{literal}'+image_filename+')');
  }

  $('#{/literal}{$input_name}_file{literal}').on('change', function()  
  { 
    var data = new FormData();
    var name = '';
    var max_width = {/literal}{$max_width}{literal};
    var max_height = {/literal}{$max_height}{literal};
    var sub_directory = '{/literal}{$sub_directory}{literal}';

    var count = 0;

    jQuery.each($('#{/literal}{$input_name}_file{literal}')[0].files, function(i, file) {
      data.append('image', file);
      count++;
    });

    if (count < 1)
      return;

    data.append('name', name);
    data.append('max_width', max_width);
    data.append('max_height', max_height);
    data.append('sub_directory', sub_directory);

    $('#{/literal}{$input_name}{literal}_container').find('.preview_area').css('background-image', 'none');
    $('#{/literal}{$input_name}{literal}_container').find('.preview_area_loading').show();

    $.ajax({
    url: '{/literal}{$settings->site_path}{literal}/admin/media/upload',
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
    success: function(data){

        $('#{/literal}{$input_name}{literal}_container').find('.preview_area_loading').hide();
        if (typeof(data.success) != 'undefined' && data.success)
        {
          var filename = '';
          if (typeof(data.filename) != 'undefined' && data.filename)
          {
            filename = data.filename;
            $('#{/literal}{$input_name}{literal}_container').find('.preview_area').css('background-image', 
              'url({/literal}{$settings->site_path}{literal}/uploads/images/'+filename+'?r='+Math.random()+')');
            $('#{/literal}{$input_name}{literal}').val(filename);
          } else {
            $('#{/literal}{$input_name}{literal}_container').find('.preview_area').css('background-image', 'none');
          }
        }
    },
    dataType: 'json'
    });

  });
});
{/literal}
</script>