{if count($pages.pages) > 1}
<div class="center-block">
	<ul class="pagination pagination-sm">
		{foreach from=$pages.pages item=p}
			{if $p.selected}
				<li class="active" title="{t page=$p.text}Current page #%1{/t}"><span>{$p.text}</span></li>
			{else}
				{if $p.text == "..."}
					<li><span>{$p.text}</span></li>
				{else}
					<li title="{t page=$p.text}Go to page #%1{/t}"><a href="?page_n={$p.page}">{$p.text}</a></li>
				{/if}
			{/if}
		{/foreach}
	</ul>
</div>
{/if}