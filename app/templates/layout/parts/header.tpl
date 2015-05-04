
	<nav class="navbar navbar-default disable-selection" id="header" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">{t}Toggle navigation{/t}</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{$settings->site_path}/">{t}DimeShift{/t}</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">

						<li class="active header_is_not_signed_in">
							<a href="{$settings->site_path}/" class="menu_category menu_category_home" data-i18n="Home">{t}Home{/t}</a>
						</li>
						<li class="header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/wallets" class="menu_category menu_category_wallets" data-i18n="Wallets">{t}Wallets{/t}</a>
						</li>
						<li>
							<a href="{$settings->site_path}/news/recent" class="menu_category menu_category_news" data-i18n="News">{t}News{/t}</a>
						</li>
						<li>
							<a href="mailto:jeka911@gmail.com?subject=Wallet" target="_blank" class="menu_category" data-i18n="Contact">{t}Contact{/t}</a>
						</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
						<li class="header_is_not_signed_in navbar-right" {if $user && $user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/registration" data-i18n="Register">{t}Register{/t}</a>
						</li>
						<li class="header_is_not_signed_in navbar-right" {if $user && $user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/signin" class="signin_caller" onclick=" " data-i18n="Sign In">{t}Sign In{/t}</a>
						</li>
						<li class="header_is_signed_in navbar-right" {if !$user || !$user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/user/logout" class="signout_caller" data-i18n="Log Out">{t}Log Out{/t}</a>
						</li>
						<li class="header_is_signed_in navbar-right" {if !$user || !$user.id}style="display: none;"{/if}>
							<a href="{$settings->site_path}/profile" data-i18n="Settings">{t}Settings{/t}</a>
						</li>

			    </ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>
