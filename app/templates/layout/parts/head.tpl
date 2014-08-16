<head>
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
	<link rel='icon' href='{$settings->site_path}/images/favicon.ico' type='image/x-icon' />

	<script type="text/javascript">
		var site_path = '{$settings->site_path}';
		var app_version = '{$settings->version}';
	</script>
	{add_js file="vendors/jquery/jquery-1.10.2.min" prepend=true} 
	{add_js file="vendors/jsmarty/smart-2.10.min" prepend=true} 
	{* Prepend - this scripts will be included first, even if you've added something in controller *}
	{add_js file="vendors/bootstrap/js/bootstrap.min" prepend=true}
	{add_js file="vendors/backbonejs/underscore-min" prepend=true}
	{add_js file="vendors/backbonejs/backbone-min" prepend=true}
	{add_js file="scripts/app"}
	{add_js file="scripts/app/models/user"}
	{add_js file="scripts/app/models/wallet"}
	{add_js file="scripts/app/models/transaction"}
	{add_js file="scripts/app/models/static_page"}
	{add_js file="scripts/app/collections/users"}
	{add_js file="scripts/app/collections/wallets"}
	{add_js file="scripts/app/collections/transactions"}
	{add_js file="scripts/app/views/header"}
	{add_js file="scripts/app/views/dialogs/abstract"}
	{add_js file="scripts/app/views/dialogs/signin"}
	{add_js file="scripts/app/views/pages/abstract"}
	{add_js file="scripts/app/views/pages/wallets"}
	{add_js file="scripts/app/views/pages/index"}
	{add_js file="scripts/app/views/pages/static"}
	{add_js file="scripts/app/views/pages/404"}
	{add_js file="scripts/app/settings"}
	{add_js file="scripts/app/router"}
	{add_js file="scripts/app/local_storage"}
	{add_js file="scripts/app/template_manager"}
	{add_js file="scripts/setup"}

	{include_js_files}
	<script>
	{if isset($user) && $user}
		window.App.setUser({$user->to_array()|@json_encode});
	{/if}
	</script>

	{add_css file="vendors/bootstrap/css/bootstrap.min" prepend=true}
	{add_css file="css/main" prepend=true}

	{include_css_files}
</head>