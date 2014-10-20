<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Categories</h3>
	</div>
	<div class="panel-body">
		{if $categories}
		<ul class="nav nav-pills nav-stacked">
		{foreach from=$categories item=c}
			<li id="news_sidebar_item_{$c->id}"><a href="{$settings->site_path}/news/category/{$c->id}" class="filter_menu">{$c->name|escape:'html'}</a></li>
		{/foreach}
		</ul>
		{else}
			No categories defined
		{/if}
	</div>
</div>