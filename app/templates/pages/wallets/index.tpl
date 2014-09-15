<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li class="active">Your Wallets</li>
</ul>

{if $items|count == 0}
	<div class="alert alert-warning" role="alert">You have no wallets <a href="{$settings->site_path}/wallets/add" class="btn btn-primary btn-xs">Add</a></div>
{else}
	<div class="list-group">
	{foreach from=$items item=i}
		<a href="{$settings->site_path}/wallets/{$i->id}" class="list-group-item">
		    <span class="badge">${$i->total}</span>
			<h4 class="list-group-item-heading">{$i->name|escape:'html'}</h4>
			<p class="list-group-item-text">...</p>
		</a>
	{/foreach}

	<div class="list-group-item list-group-item-info"><a href="{$settings->site_path}/wallets/add" class="btn btn-primary">Add</a></div>
	</ul>

	
{/if}

