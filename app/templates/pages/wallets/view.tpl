<ul class="breadcrumb">
  <li><a href="{$settings->site_path}">Home</a></li>
  <li><a href="{$settings->site_path}/wallets">Wallets</a></li>
  <li class="active">{$item->name|default:'Wallet'|escape:'html'}</li>
</ul>


<div class="row">

	<div class="col-xs-12 col-sm-12 col-md-4 col-md-push-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">{$item->name|escape:'html'}</h3>
			</div>
			<div class="panel-body">
				fdsfsd
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-8 col-md-pull-4">


		<div class="list-group">
			<div class="list-group-item ">
				<h3 class="panel-title">Transactions</h3>
			</div>
			<a href="{$settings->site_path}/wallets/3" class="list-group-item item" data-id="3">
			    <span class="badge">$33</span>
				<h6 class="list-group-item-heading">333</h6>
			</a>
			<a href="{$settings->site_path}/wallets/3" class="list-group-item item" data-id="3">
			    <span class="badge">$33</span>
				<h6 class="list-group-item-heading">333</h6>
			</a>
			<a href="{$settings->site_path}/wallets/3" class="list-group-item item" data-id="3">
			    <span class="badge">$33</span>
				<h6 class="list-group-item-heading">333</h6>
			</a>
		</div>	


	</div>

</div>