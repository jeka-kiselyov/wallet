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


	<script type="text/javascript">var site_path = '{$settings->site_path}';</script>
	<script src="{$settings->site_path}/vendors/headjs/head.min.js" type="text/javascript"></script>
	<script>
		head.js(site_path+"/vendors/jquery/jquery-1.9.0.min.js");
		head.js(site_path+"/vendors/bootstrap/js/bootstrap.min.js");
		{foreach from=$head_js item=js}
			head.js(site_path+"/scripts/{$js}.js");
		{/foreach}
	</script>

	{if $settings->minify_css_merge}
	<link href="{$settings->site_path}/min/?v=1.0&f=/vendors/bootstrap/css/bootstrap.css,/css/main.css,{foreach from=$head_css item=css}/css/{$css}.css,{/foreach}" media="screen" rel="stylesheet" type="text/css" />	
	{else}
	<link href="{$settings->site_path}/vendors/bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="{$settings->site_path}/css/main.css" media="screen" rel="stylesheet" type="text/css" />
	{foreach from=$head_css item=css}
	<link href="{$settings->site_path}/css/{$css}.css" media="screen" rel="stylesheet" type="text/css" />
	{/foreach}
	{/if}

</head>
<body>

<div id="wrap">

	<div class="navbar">
	  <div class="navbar-inner">
	    <div class="container">
	 
	      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
	      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </a>
	 
	      <!-- Be sure to leave the brand out there if you want it shown -->
	      <a class="brand" href="{$settings->site_path}">{$settings->site_title}</a>
	 
	      <!-- Everything you want hidden at 940px or less, place within here -->
	      <div class="nav-collapse collapse">
	        <!-- .nav, .navbar-search, .navbar-form, etc -->
				<ul class="nav">
					<li class="active"><a href="{$settings->site_path}">Home</a></li>

					{if isset($user)}
						{if $user->is_admin}
						<li><a href="{$settings->site_path}/admin">Admin Panel</a></li>
						{/if}
						{if !$user}
							<li><a href="{$settings->site_path}/user/registration">Register</a></li>
							<li><a href="{$settings->site_path}/user/signin" class="signin_caller" onclick=" ">Sign In</a></li>
						{else}
							<li><a href="{$settings->site_path}/user/logout">Log Out</a></li>
						{/if}
					{/if}
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#">Item #1</a></li>
							<li><a href="#">Item #2</a></li>
							<li><a href="#">Item #3</a></li>
						</ul>
					</li>
				</ul>
	      </div>
	 
	    </div>
	  </div>
	</div>


	<div class="container-fluid">
		{include file="{$page_tpl}.tpl"}
	</div>


	<div id="push"></div>
</div>


<div id="footer">
	<div class="container-fluid">
		<p class="muted credit">{$settings->site_title} &copy; {current_year}</p>
	</div>
</div>

{include file="dialogs/signin.tpl" caching=true cache_lifetime=60}
	
</body>
</html>