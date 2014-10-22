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
			
				<p class="text-center {if $item->total >= 0}text-success{else}text-danger{/if} wallet_total"><strong>{if $item->total < 0}-{/if}{if $item->currency == 'USD'}${/if}{$item->total|rational}.<sup>{$item->total|decimal}</sup>{if $item->currency != 'USD'} {$item->currency}{/if}</strong></p>
				
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
						<input type="number" min="0" step="0.01" class="form-control hideme" id="add_transaction_amount" placeholder="Transaction amount">
						<button type="submit" class="hideme">
					</form>
				</div>
			</div>

			<div id="transactions_container">

			</div>

		</div>	


	</div>

</div>