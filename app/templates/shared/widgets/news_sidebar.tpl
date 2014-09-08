<h4>Categories</h4>

{if $categories}
	{foreach from=$categories item=c}
		<a href="#">{$c.name|escape:'html'}</a>
	{/foreach}
{/if}
