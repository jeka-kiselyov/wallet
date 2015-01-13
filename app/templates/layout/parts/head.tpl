<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="{$settings->site_path}/vendors/jquery/placeholder/jquery.placeholder.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function() {
				$('input, textarea').placeholder();
			});
		</script>
	<![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if isset($title) && $title}{$title|escape:"html"} | {/if}{$settings->site_title}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel='icon' href='{$settings->site_path}/images/favicon.ico' type='image/x-icon' />

	<script type="text/javascript">
		var site_path = '{$settings->site_path}';
		var app_version = '{$settings->version}';
	</script>
	
	<link href="{$settings->site_path}/css/dist/app.min.css" media="screen" rel="stylesheet" type="text/css" />

	{if $settings->environment == 'localhost'}
	<script src="{$settings->site_path}/scripts/dist/app.js" type="text/javascript"></script>
	<script>document.write('<script src="http://localhost:35729/livereload.js?snipver=1"></' + 'script>')</script>
	{else}
	<script src="{$settings->site_path}/scripts/dist/app.min.js" type="text/javascript"></script>
	{/if}

	<script>
	{if isset($user) && $user}
		window.App.setUser({$user->to_array()|@json_encode});
	{/if}
	</script>


</head>