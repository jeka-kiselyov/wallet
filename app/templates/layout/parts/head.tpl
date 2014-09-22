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
	{* Prepend - this scripts will be included first, even if you've added something in controller *}
	{add_js file="vendors/bootstrap/js/bootstrap.min" prepend=true}
	{add_js file="vendors/backbonejs/underscore-min" prepend=true}
	{add_js file="vendors/backbonejs/backbone-min" prepend=true}
	{add_js file="vendors/backbonejs/backbone.paginator" prepend=true}
	{add_js file="vendors/j911helpers/j911helpers" prepend=true} 

	{add_js file="vendors/jsmarty/jsmart"} 

	{add_js file="scripts/app"}
	{add_js file="scripts/app/view_stack"}
	{add_js file="scripts/app/settings"}
	{add_js file="scripts/app/local_storage"}
	{add_js file="scripts/app/template_manager"}

	{add_js_folder path="scripts/app/abstract/"}
	{add_js_folder path="scripts/app/models/"}
	{add_js_folder path="scripts/app/collections/"}
	{add_js_folder path="scripts/app/views/dialogs/"}
	{add_js_folder path="scripts/app/views/widgets/"}
	{add_js_folder path="scripts/app/views/pages/"}

	{add_js file="scripts/app/views/header"}

	{add_js file="scripts/app/router"}
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