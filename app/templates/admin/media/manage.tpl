{include file="admin/breadcrumbs.tpl"}

<script type="text/javascript" src="{$settings->site_path}/vendors/ckfinder/ckfinder.js"></script>

<script type="text/javascript">
var finder = new CKFinder();
finder.basePath = '{$settings->site_path}/vendors/ckfinder/';
finder.height = 500;
finder.create();
</script>