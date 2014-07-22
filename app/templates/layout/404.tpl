<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if isset($title) && $title}{$title|escape:"html"} | {/if}{$settings->site_title}</title>
	<link rel='icon' href='{$settings->site_path}/images/favicon.ico' type='image/x-icon' />
	{include file="parts/jquery.tpl"}
	{include file="parts/bootstrap.tpl"}
	{foreach from=$head_js item=js}
	<script src="{$settings->site_path}/scripts/{$js}.js" type="text/javascript"></script>
	{/foreach}
	{foreach from=$head_css item=css}
	<link href="{$settings->site_path}/css/{$css}.css" media="screen" rel="stylesheet" type="text/css" />
	{/foreach}
</head>
<body>

<div class="container-fluid">


    <div class="row-fluid">
		<div class="span3">
			<hr>
		</div>
		<div class="span9">

			<h3>Error 404. Nothing is found</h3>

		</div>
    </div>


</div> <!-- /container -->


<div class="navbar navbar-fixed-bottom">
	<div class="container-fluid">
		<hr>
		<footer class="">
			<p>{$settings->site_title} &copy; {current_year}</p>
		</footer>
	</div>
</div>

</body>
</html>