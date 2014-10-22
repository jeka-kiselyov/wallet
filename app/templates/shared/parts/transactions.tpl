{if $state == 'loading'}
	<div class="list-group-item">
		loading
	</div>
{else}

	{foreach from=$transactions item=t}
	<div class="list-group-item item" data-id="{$t->id}">
		<div class="pull-left transaction_time">
			{assign var="current_transaction_time_date" value=$t->datetime|date_format}
			<div class="transaction_time_date">{if $last_time_date|default:'' != $current_transaction_time_date}{$current_transaction_time_date}{else}&nbsp;{/if}</div>
			<div class="transaction_time_time">{$t->datetime|date_format:'g:i a'}</div>
			{assign var="last_time_date" value=$current_transaction_time_date}
		</div>

		<div class="pull-right {if $t->amount >= 0}text-success{else}text-danger{/if} transaction_amount"><strong>{if $item->currency == 'USD'}${/if}{$t->amount|rational}.<sup>{$t->amount|decimal}</sup>{if $item->currency != 'USD'} {$item->currency}{/if}</strong></div>
		
		<h6 class="list-group-item-heading">{$t->description|escape:'html'|default:'&nbsp;'}</h6>
	</div>
	{/foreach}

{/if}