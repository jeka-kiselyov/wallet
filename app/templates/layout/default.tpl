<!DOCTYPE HTML>
<html>
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
	</script>
	{add_js file="vendors/jquery/jquery-1.10.2.min" prepend=true} 
	{* Prepend - this scripts will be included first, even if you've added something in controller *}
	{add_js file="vendors/bootstrap/js/bootstrap.min" prepend=true}
	{add_js file="vendors/backbonejs/underscore-min" prepend=true}
	{add_js file="vendors/backbonejs/backbone-min" prepend=true}
	{add_js file="scripts/app"}
	{add_js file="scripts/app/models/user"}
	{add_js file="scripts/app/models/wallet"}
	{add_js file="scripts/app/collections/users"}
	{add_js file="scripts/app/collections/wallets"}
	{add_js file="scripts/app/views/header"}
	{add_js file="scripts/app/views/dialogs/signin"}
	{add_js file="scripts/app/settings"}
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
<body>

<div id="wrap">

	<nav class="navbar navbar-default" id="header" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{$settings->site_path}">{$settings->site_title|escape:'html'}</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">

						<li class="active">
							<a href="{$settings->site_path}">Home</a>
						</li>
						<li class="header_is_not_signed_in" {if $user && $user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/registration">Register</a>
						</li>
						<li class="header_is_not_signed_in" {if $user && $user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/signin" class="signin_caller" onclick=" ">Sign In</a>
						</li>
						<li class="header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/logout" class="signout_caller">Log Out</a>
						</li>

						{if isset($user) && $user && $user->is_admin}
							<li><a href="{$settings->site_path}/admin">Admin Panel</a></li>
						{/if}
						<li class="dropdown header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}><a href="#" class="dropdown-toggle" data-toggle="dropdown">Account 
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="{$settings->site_path}/news">News</a></li>
								<li><a href="#">Item #2</a></li>
								<li><a href="#">Item #3</a></li>
							</ul>
						</li>
			    </ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>


	<div class="container">
		{include file="pages/{$page_tpl}.tpl"}
	</div>


	<div id="push"></div>
</div>


<footer id="footer" role="contentinfo">
	<div class="container">
		<p class="muted credit">{$settings->site_title} &copy; {current_year}</p>
	</div>
</footer>

{include file="dialogs/signin.tpl" caching=true cache_lifetime=60}

{add_js_test name="framework_test"}
{add_js_test name="model_user_test"}
{add_js_test name="model_wallet_test"}

{include_js_files}
{include_css_files}
{* In case you've included some scripts in templates with add_js smarty function *}

{if $settings->tests.jasmine.enabled}
	{run_js_tests}
{/if}

</body>
</html>