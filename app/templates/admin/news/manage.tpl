{include file="admin/table_helper_header.tpl" title="Manage"}

<div class="panel panel-default panel-table">
<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
<tr>
 <th onclick="sort('id');" class="{if $order.by=='id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Id{/t}</th>
 <th onclick="sort('title');" class="{if $order.by=='title'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Title{/t}</th>
 <th onclick="sort('slug');" class="{if $order.by=='slug'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Slug{/t}</th>
 {if $is_multilingual}
  <th onclick="sort('i18n_language');" class="{if $order.by=='i18n_language'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Language{/t}</th>
 {/if}
 <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{if !isset($items) || !$items}
<tr>
 <td colspan={if $is_multilingual}"5"{else}"4"{/if}>{t}Nothing is found{/t}</td>
</tr>
{else}
{foreach from=$items item=i}
<tr>
 <td>{$i->id}</td>
 <td title="{$i->title|escape:'html'}">{$i->title|truncate:50:"...":true|escape:"html"}</td>
 <td title="{$i->slug|escape:'html'}">{$i->slug|truncate:30:"...":true|escape:"html"}</td>
 {if $is_multilingual}
	 <td>{if $i->i18n_language}{$i->i18n_language->code}{/if}</td>
 {/if}
 <td>
  <a href='{$settings->site_path}/news/view/{$i->slug}.html' target='_blank' class='btn btn-default btn-xs'>{t}Preview{/t}</a>
	<a href='{$settings->site_path}/admin/news/edit/{$i->id}' class='btn btn-default btn-xs'>{t}Edit{/t}</a>
	<a href='#' class='btn btn-default btn-xs' onclick="remove_item('{$i->id}'); return false;">{t}Delete{/t}</a>
</td>
</tr>
{/foreach}
{/if}
</tbody>
</table>
</div>
</div>
