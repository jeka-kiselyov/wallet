<!DOCTYPE HTML>
<html>
{include file="layout/parts/head.tpl"}
<body>
<div id="wrap">
	{include file="layout/parts/header.tpl"}

	<div class="container" id="container">
		<div class="page_holder" id="page_holder_1">
			{include file="shared/404.tpl"}
		</div>
		<div class="page_holder" id="page_holder_2"></div>
	</div>

	<div id="push"></div>
</div>

{include file="layout/parts/footer.tpl"}

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