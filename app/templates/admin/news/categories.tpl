{include file="admin/table_helper_header.tpl" title="Manage categories"}

<div class="panel panel-default panel-table">
<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
<tr>
 <th onclick="sort('id');" class="{if $order.by=='id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Id{/t}</th>
 <th onclick="sort('name');" class="{if $order.by=='name'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Name{/t}</th>
  {if $is_multilingual}
 <th>{t}Current Language Translation{/t}</th>
  {/if}
 <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{if !isset($items) || !$items}
<tr>
 <td colspan={if $is_multilingual}"4"{else}"3"{/if}>{t}Nothing is found{/t}</td>
</tr>
{else}
{foreach from=$items item=i}
<tr>
 <td>{$i.id}</td>
 <td>{$i.name|escape:"html"}</td>
 {if $is_multilingual}
 <td>{t}{$i.name|escape:"html"}{/t}</td>
 {/if}
 <td>
	<a href='{$settings->site_path}/admin/news/editcategory/{$i.id}' class='btn btn-default btn-xs'>{t}Edit{/t}</a>
  {if $is_multilingual}
  <a href='{$settings->site_path}/admin/news/categorytranslations/{$i.id}' class='btn btn-default btn-xs'>{t}Translations{/t}</a>
  {/if}
	<a href='#' class='btn btn-default btn-xs' onclick="remove_item('{$i.id}'); return false;">{t}Delete{/t}</a>
</td>
</tr>
{/foreach}
{/if}
</tbody>
</table>
</div>
</div>
