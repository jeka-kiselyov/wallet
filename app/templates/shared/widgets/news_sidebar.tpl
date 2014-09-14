<h4>Categories</h4>

<ul>
{if $categories}
	{foreach from=$categories item=c}
		<li><a href="{$settings->site_path}/news/category/{$c->id}">{$c->name|escape:'html'}</a></li>
	{/foreach}
{/if}
</ul>
