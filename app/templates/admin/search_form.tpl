<form method="post" onsubmit="do_search(); return false;">
    <div class="row">
    	<div class="col-xs-3 pull-right">
			<div class="input-group input-group-sm">
				{if $search}
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="remove_search(); return false;"><span class="glyphicon glyphicon-remove"></span></button>
				</span>
			  	{/if}
				<input class="form-control" id="search_q" size="25" name="q" value="{$search|escape:'html'}" type="text">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="do_search(); return false;"><span class="glyphicon glyphicon-search"></span></button>
				</span>
	    	</div>
		</div>
    </div>
</form>