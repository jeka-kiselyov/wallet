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
	<meta name="detectify-verification" content="77671959b106d29e9175d552421d4800" /> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if isset($title) && $title}{$title|escape:"html"} | {/if}{$settings->site_title}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel='icon' href='{$settings->site_path}/images/favicon.ico' type='image/x-icon' />

	<script type="text/javascript">
		var site_path = '{$settings->site_path}';
		var app_version = '{$settings->version}';
	</script>

	{add_js file="vendors/jquery/jquery.min" prepend=true}
	{* Prepend - this scripts will be included first, even if you've added something in controller *}

	{add_js file="vendors/bootstrap/js/modal" prepend=true}
	{add_js file="vendors/bootstrap/js/button" prepend=true}

	{add_js file="vendors/underscore/underscore-min" prepend=true}
	{add_js file="vendors/backbone/backbone" prepend=true}
	{add_js file="vendors/backbone.paginator/lib/backbone.paginator.min" prepend=true}
	{add_js file="vendors/bootstrap-clickonmouseover/bootstrap.clickonmouseover" prepend=true}
	{add_js file="vendors/magnific-popup/dist/jquery.magnific-popup.min" prepend=true}
	{add_js file="vendors/mprogress/build/js/mprogress.min" prepend=true}
	{add_css file="vendors/mprogress/build/css/mprogress" prepend=true}
	{add_css file="vendors/magnific-popup/dist/magnific-popup" prepend=true}
	{add_js file="vendors/chartist/dist/chartist" prepend=true}
	{add_css file="vendors/chartist/dist/chartist.min" prepend=true}
	{add_js file="vendors/jsmart/jsmart"}
	{add_js file="scripts/functions"}
	{add_js file="scripts/app"}
	{add_js file="scripts/app/view_stack"}
	{add_js file="scripts/app/settings"}
	{add_js file="scripts/app/local_storage"}
	{add_js file="scripts/app/template_manager"}
	{add_js file="scripts/app/i18n"}
	{add_js_folder path="scripts/app/abstract/"}
	{add_js_folder path="scripts/app/models/"}
	{add_js_folder path="scripts/app/collections/"}
	{add_js_folder path="scripts/app/views/dialogs/"}
	{add_js_folder path="scripts/app/views/widgets/"}
	{add_js_folder path="scripts/app/views/parts/"}
	{add_js_folder path="scripts/app/views/pages/"}
	{add_js_folder path="scripts/app/views/charts/"}
	{add_js file="scripts/app/views/header"}
	{add_js file="scripts/app/log"}
	{add_js file="scripts/app/router"}
	{add_js file="scripts/setup"}

	{include_js_files}
	<script>
	{if isset($user) && $user}
	window.App.setUser({$user->to_array()|@json_encode});
	{/if}
	</script>
	
	{add_css file="vendors/bootstrap/dist/css/bootstrap.min" prepend=true}

	{include_css_files}

	{add_css file="css/main.css" prepend=true}

	{include_css_files}
	
	{include_js_files} {* In case there's .less files and //cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js script was included by last include_css_files *}

</head>