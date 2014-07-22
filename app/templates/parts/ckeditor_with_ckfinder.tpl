
<script src="{$settings->site_path}/vendors/ckfinder/ckfinder.js" type="text/javascript"></script>
<script src="{$settings->site_path}/vendors/ckeditor/ckeditor.js" type="text/javascript"></script>
<script>
 CKFinder.setupCKEditor( null, '{$settings->site_path}/vendors/ckfinder/' );
</script>
<style>
{literal}
.cke_reset_all textarea,.cke_reset_all input[type="text"],.cke_reset_all input[type="password"]{cursor:text; padding: 0; margin:0;font-size: 12px;height: 20px;line-height: 20px;}
{/literal}
</style>