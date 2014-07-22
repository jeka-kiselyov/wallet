{include file="admin/table_helper_header.tpl" title="Languages"}


<div class="panel panel-default panel-table">
<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
<tr>
 <th onclick="sort('id');" class="{if $order.by=='id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Id{/t}</th>
 <th onclick="sort('name');" class="{if $order.by=='code'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Code{/t}</th>
 <th onclick="sort('subject');" class="{if $order.by=='name'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Name{/t}</th>
 <th onclick="sort('translated_strings_count');" class="{if $order.by=='translated_strings_count'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Translated{/t}</th>
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
 <td>{$i.code|escape:"html"}</td>
 <td>{$i.name|escape:"html"}</td>
 <td>{if $i.is_default == 0}{$i.translated_strings_count|escape:"html"}{else}{$total_strings_count}{/if}/{$total_strings_count}</td>
 <td>
  {if $i.is_default == 0 && $i.translated_strings_count < $total_strings_count}
  <a href='{$settings->site_path}/admin/i18n/translate/{$i.id}' class='btn btn-default btn-xs'>{t}Translate{/t}</a>
  {/if}
	<a href='{$settings->site_path}/admin/i18n/editlanguage/{$i.id}' class='btn btn-default btn-xs'>{t}Edit{/t}</a>
  {if $i.is_default == 0}
	<a href='#' class='btn btn-default btn-xs' onclick="remove_item('{$i.id}'); return false;">{t}Delete{/t}</a>
  {/if}
</td>
</tr>
{/foreach}
{/if}
</tbody>
</table>

</div>
</div>
