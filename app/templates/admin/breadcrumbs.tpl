<ul class="breadcrumb">
  <li><a href="{$settings->site_path}/admin/">{t}Admin Panel{/t}</a> </li>
  {if !$title}
  <li class="active">{$breadcrumb}</li>
  {else}
  {if isset($breadcrumb)}
  <li><a href="{$settings->site_path}/admin/{$breadcrumb_href}">{$breadcrumb}</a> </li>
  {/if}
  <li class="active">{if $title}{t}{$title}{/t}{/if}</li>
  {/if}
</ul>