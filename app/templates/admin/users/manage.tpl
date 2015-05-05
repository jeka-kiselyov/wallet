{include file="admin/table_helper_header.tpl" title="Manage"}

<div class="panel panel-default panel-table">
<div class="table-responsive">
<table class="table table-striped table-hover">
<thead>
<tr>
 <th onclick="sort('id');" class="{if $order.by=='id'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Id{/t}</th>
 <th onclick="sort('login');" class="{if $order.by=='login'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Username{/t}</th>
 <th onclick="sort('email');" class="{if $order.by=='email'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Email{/t}</th>
 <th onclick="sort('type');" class="{if $order.by=='type'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Source{/t}</th>
 <th onclick="sort('registration_date');" class="{if $order.by=='registration_date'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Registration Date{/t}</th>
 <th onclick="sort('activity_date');" class="{if $order.by=='activity_date'}{if $order.dir == 'asc'} th_ordered th_asc{else} th_ordered th_desc{/if}{/if}">{t}Activity Date{/t}</th>
 <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{if !isset($items) || !$items}
<tr>
 <td colspan="7">{t}Nothing is found{/t}</td>
</tr>
{else}
{foreach from=$items item=i}
<tr>
 <td>{$i->id}</td>
 <td>{$i->login|escape:"html"}</td>
 <td>{$i->email|escape:"html"}</td>
 <td>{$i->type|escape:"html"}</td>
 <td>{$i->registration_date|date_format}</td>
 <td>{$i->activity_date|date_format}</td>
 <td>
	<a href='{$settings->site_path}/admin/users/details/{$i->id}' class='btn btn-default btn-xs'>{t}Details{/t}</a>
</td>
</tr>
{/foreach}
{/if}
</tbody>
</table>
</div>
</div>