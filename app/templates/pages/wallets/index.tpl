<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li class="active">Your Wallets</li>
</ul>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-9">

	{if $items|count == 0}
		<div class="alert alert-warning" role="alert">You have no wallets <a href="{$settings->site_path}/wallets/add" class="btn btn-primary btn-xs">Add</a></div>
	{else}
		<div class="list-group">
		{foreach from=$items item=i}
			<a href="{$settings->site_path}/wallets/{$i->id}" class="list-group-item item" data-id="{$i->id}">
			    <span class="badge">${$i->total}</span>
				<h4 class="list-group-item-heading">
				<span class="item_buttons hideme">
					<button class="btn btn-default btn-sm item_button_remove"><span class="glyphicon glyphicon-trash"></span></button>
				</span>{$i->name|escape:'html'}
				</h4>
				<p class="list-group-item-text">...</p>

			</a>
		{/foreach}

		<div class="list-group-item list-group-item-info"><a href="{$settings->site_path}/wallets/add" class="btn btn-primary">Add</a></div>
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
				<li class="active"><a href="#"><span class="glyphicon glyphicon-ok"></span> Active</a></li>
				<li><a href="#"><span class="glyphicon glyphicon-trash"></span> Trash</a></li>
			</ul>
		</div>
	</div>
</div>
</div>