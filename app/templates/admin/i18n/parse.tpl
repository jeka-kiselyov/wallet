{include file="admin/breadcrumbs.tpl" title="Parse templates"}


<div class="alert alert-info">
  {t}Get missed strings to translate from templates{/t}
</div>
<form method="post">
  <input type="hidden" name="security_token" value="{$form_checker->generate_security_token()}">


<div class="alert alert-success" id="success" style="display: none;">
  {t}Done!{/t}
</div>

<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
    0%
  </div>
</div>

  <div class="form-group" style="clear: both;">
  <div class="controls">      
  	<input type="submit" name="save" id="parse_templates_submit" value="{t}Parse{/t}" class="btn btn-primary" data-loading-text="{t}Parsing...{/t}" onclick="$(this).button('loading'); parse_templates_run(); return false;">
  	<input type="submit" name="cancel" value="{t}Cancel{/t}" id="cancel" class="btn" data-loading-text="{t}Canceling...{/t}" onclick="$(this).button('loading');">
  </div>
  </div>

</form>


<script>
  var parse_templates_files = [];
  var parsed_count = 0;

  function parse_templates_run()
  {
    parse_templates_set_percentage(0);
    $('.progress').show();
    $('#success').slideUp();

    parse_templates_files = [];
    parsed_count = 0;

    parse_templates_get_files(function(){
      parse_templates_set_percentage(5);
      parse_next_file();
    });
  }

  function parse_next_file()
  {
    if (typeof(parse_templates_files[parsed_count]) !== 'undefined')
      parse_templates_parse_file(parse_templates_files[parsed_count], function(){
        parsed_count++;
        var percent = (5 + (parsed_count / parse_templates_files.length) * 95);
        parse_templates_set_percentage(percent);
        parse_next_file();
      });
    else {
      $('#success').slideDown();
      $('#parse_templates_submit').button('reset');
    }
  }

  function parse_templates_set_percentage(percent)
  {
    var p = Math.round(percent);

    if (p < 0) p = 0;
    if (p > 100) p = 100;

    $('.progress-bar').css('width', p+'%').text(p+'%');
  }

  function parse_templates_get_files(callback)
  {
    var cb = callback;
    var process = function(data) {
      for (var k in data)
        parse_templates_files.push(data[k]);
      if (typeof(cb) === 'function')
        cb();
    }

    $.ajax({
      url: '//'+document.domain+'/admin/i18n/ajax_get_files',
      data: {},
      success: process,
      dataType: 'json',
      mimeType: 'application/json',
      cache: true
    });
  }

  function parse_templates_parse_file(fileName, callback)
  {
    var cb = callback;
    var process = function(data) {
      console.log(data);
      if (typeof(cb) === 'function')
        cb();
    }

    $.ajax({
      url: '//'+document.domain+'/admin/i18n/ajax_parse_file',
      method: 'post',
      data: { file: fileName},
      success: process,
      error: process,
      dataType: 'json',
      mimeType: 'application/json',
      cache: true
    });

  }

</script>
