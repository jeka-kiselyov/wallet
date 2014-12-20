
	<nav class="navbar navbar-default disable-selection" id="header" role="navigation">
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

						<li class="active header_is_not_signed_in">
							<a href="{$settings->site_path}/" class="menu_category menu_category_home">Home</a>
						</li>
						<li class="header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/wallets" class="menu_category menu_category_wallets">Wallets</a>
						</li>
						<li>
							<a href="{$settings->site_path}/news/recent" class="menu_category menu_category_news">News</a>
						</li>
						<li>
							<a href="mailto:jeka911@gmail.com?subject=Wallet" target="_blank" class="menu_category">Contact</a>
						</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
						<li class="header_is_not_signed_in navbar-right" {if $user && $user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/registration">Register</a>
						</li>
						<li class="header_is_not_signed_in navbar-right" {if $user && $user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/signin" class="signin_caller" onclick=" ">Sign In</a>
						</li>
						<li class="header_is_signed_in navbar-right" {if !$user || !$user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/logout" class="signout_caller">Log Out</a>
						</li>

						{*
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
						*}
			    </ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>
