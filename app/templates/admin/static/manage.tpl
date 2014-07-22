{include file="admin/table_helper_header.tpl" title="Manage"}

<div class="panel panel-default panel-table">
<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
<tr>
 <th onclick="sort('id');" class="{if $order.by=='id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Id{/t}</th>
 <th onclick="sort('name');" class="{if $order.by=='name'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Title{/t}</th>
 <th onclick="sort('subject');" class="{if $order.by=='subject'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Slug{/t}</th>
 {if $is_multilingual}
  <th onclick="sort('language_id');" class="{if $order.by=='language_id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Language{/t}</th>
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
 <td>{$i.id}</td>
 <td>{$i.title|escape:"html"}</td>
 <td>{$i.slug|escape:"html"}</td> 
 {if $is_multilingual}
 <td>{t}{$i.language_id_i18n_languages_name|escape:"html"}{/t}</td>
 {/if}
 <td>
  <a href='{$settings->site_path}/static/view/{$i.slug}.html' target='_blank' class='btn btn-default btn-xs'>{t}Preview{/t}</a>
	<a href='{$settings->site_path}/admin/static/edit/{$i.id}' class='btn btn-default btn-xs'>{t}Edit{/t}</a>
	<a href='#' class='btn btn-default btn-xs' onclick="remove_item('{$i.id}'); return false;">{t}Delete{/t}</a>
</td>
</tr>
{/foreach}
{/if}
</tbody>
</table>
</div>
</div>