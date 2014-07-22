{include file="admin/breadcrumbs.tpl" title=$title}

<div class="filter">

<div class="filter_pagination">
	<div class="filter_pagination_container">
			{include file="admin/pager.tpl"}
	</div>
</div>
{include file="admin/search_form.tpl"}

</div>


{include file="admin/table_helper_remove_dialog.tpl"}