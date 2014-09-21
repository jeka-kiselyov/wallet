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
			{if $item->total >= 0}
				<p class="text-center text-success wallet_total"><strong>${$item->total|rational}.<sup>{$item->total|decimal}</sup></strong></p>
			{else}
				<p class="text-center text-danger wallet_total"><strong>-${$item->total|rational}.<sup>{$item->total|decimal}</sup></strong></p>
			{/if}
				
				<button type="button" id="add_profit_button" class="btn btn-success btn-block">Add Profit</button>
				<div class="pull-right">or <a href="#" class="action" id="set_total_to_button">set total to</a></div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-8 col-md-pull-4">

		<div class="list-group">
			<div class="list-group-item ">
				<h3 class="panel-title">Transactions</h3>

				<div class="form-group">
					<form method="post" id="add_transaction_form">
						<input type="text" class="form-control" id="add_transaction_text" placeholder="Describe expense and press Enter to add">
					</form>
				</div>
			</div>

			{foreach from=$transactions item=t}
			<div class="list-group-item item" data-id="{$t->id}">
				{if $t->amount >= 0}
				<div class="pull-right text-success transaction_amount"><strong>${$t->amount|rational}.<sup>{$t->amount|decimal}</sup></strong></div>
				{else}
				<div class="pull-right text-danger transaction_amount"><strong>${$t->amount|rational}.<sup>{$t->amount|decimal}</sup></strong></div>
				{/if}
				<h6 class="list-group-item-heading">{$t->description|escape:'html'|default:'&nbsp;'}</h6>
			</div>
			{/foreach}
		</div>	


	</div>

</div>