<ul class="breadcrumb">
  <li><a href="{$settings->site_path}">Home</a></li>
  <li class="active">Your Wallets</li>
</ul>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-9">

	{if $items|count == 0}
		{if $status|default:'active' == 'active'}
			<div class="alert alert-warning" role="alert">You have no wallets <a href="{$settings->site_path}/wallets/add" class="btn btn-primary btn-xs">Add</a></div>
		{else}
			<div class="alert alert-warning" role="alert">You have no hidden wallets</div>			
		{/if}
	{else}
		<div class="list-group">
		{foreach from=$items item=i}
			<a href="{$settings->site_path}/wallets/{$i->id}" class="list-group-item item" data-id="{$i->id}">
			    <span class="badge">${$i->total}</span>
				<h4 class="list-group-item-heading">{$i->name|escape:'html'}</h4>
				<div class="item_information">
					<p class="list-group-item-text">...</p>
				</div>
				<div class="item_buttons hideme">
					<button class="btn btn-default btn-xs item_button_remove"><span class="glyphicon glyphicon-trash"></span> {if $i->status|default:'active' == 'active'}Hide{else}Remove{/if}</button>
					{if $i->status|default:'active' == 'active'}
					<button class="btn btn-default btn-xs item_button_edit"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
					<button class="btn btn-default btn-xs item_button_accesses"><span class="glyphicon glyphicon-user"></span> Manage Accesses</button>
					{/if}
					{if $i->status|default:'active' == 'hidden'}
					<button class="btn btn-default btn-xs item_button_restore"><span class="glyphicon glyphicon-repeat"></span> Restore</button>
					{/if}
				</div>

			</a>
		{/foreach}

		{if $status|default:'active' == 'active'}
			<div class="list-group-item list-group-item-info"><a href="{$settings->site_path}/wallets/add" class="btn btn-primary">Add</a></div>
		{/if}
		</div>	
	{/if}

</div>
<div class="col-xs-12 col-sm-12 col-md-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Filter</h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
				<li {if $status|default:'active' == 'active'}class="active"{/if}><a href="#" class="filter_menu" data-status="active"><span class="glyphicon glyphicon-ok"></span> Active</a></li>
				<li {if $status|default:'active' == 'hidden'}class="active"{/if}><a href="#" class="filter_menu" data-status="hidden"><span class="glyphicon glyphicon-trash"></span> Trash</a></li>
			</ul>
		</div>
	</div>
</div>
</div>