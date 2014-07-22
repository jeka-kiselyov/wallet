{include file="admin/table_helper_header.tpl" title="Strings"}


<div class="panel panel-default panel-table">
<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
<tr>
 <th onclick="sort('id');" class="{if $order.by=='id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Id{/t}</th>
 <th onclick="sort('string');" class="{if $order.by=='string'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}String{/t}</th>
 <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{if !isset($items) || !$items}
<tr>
 <td colspan="4">{t}Nothing is found{/t}</td>
</tr>
{else}
{foreach from=$items item=i}
<tr>
 <td>{$i.id}</td>
 <td style="width: 400px; height: 25px; overflow: hidden;">{$i.string|escape:"html"}</td>
 <td>
	<a href='{$settings->site_path}/admin/i18n/editstring/{$i.id}' class='btn btn-default btn-xs'>{t}Edit{/t}</a>
  <a href='#' class='btn btn-default btn-xs' onclick="remove_item('{$i.id}'); return false;">{t}Delete{/t}</a>
</td>
</tr>
{/foreach}
{/if}
</tbody>
</table>
</div>
</div>

