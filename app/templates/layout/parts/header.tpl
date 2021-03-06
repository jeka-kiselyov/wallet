
	<!-- Fixed navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top headroom disable-selection" id="header">
		<div class="container">
			<div class="navbar-header">
				<!-- Button for smallest screens -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				

				<a href="{$settings->site_path}/" class="btn btn-info btn-lg btn-logo">$ dimeshift</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav pull-right">
					<li class="active header_is_not_signed_in">
						<a href="{$settings->site_path}/" class="menu_category menu_category_home" data-i18n="Home">{t}Home{/t}</a>
					</li>
					<li class="header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}>
						<a href="{$settings->site_path}/wallets" class="menu_category menu_category_wallets" data-i18n="Wallets">{t}Wallets{/t}</a>
					</li>
					<li><a href="mailto:jeka911@gmail.com?subject=Wallet" class="menu_category" data-i18n="Contact">{t}Contact{/t}</a></li>
					<li class="header_is_not_signed_in" {if $user && $user.id}style="display: none;"{/if}>
						<a href="{$settings->site_path}/user/registration" data-i18n="Register">{t}Register{/t}</a>
					</li>
					<li class="header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}>
						<a href="{$settings->site_path}/user/logout" class="signout_caller" data-i18n="Log Out">{t}Log Out{/t}</a>
					</li>
					<li class="header_is_not_signed_in" {if $user && $user.id}style="display: none;"{/if}>
						<a href="{$settings->site_path}/user/signin" class="signin_caller" data-i18n="Sign In">{t}Sign In{/t}</a>
					</li>
					<li class="header_is_signed_in" {if !$user || !$user.id}style="display: none;"{/if}>
						<a href="{$settings->site_path}/profile" data-i18n="Settings">{t}Settings{/t}</a>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div> 
	<!-- /.navbar -->

