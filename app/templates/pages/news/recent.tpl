{if $items|count == 0}
<p class="text-warning">No more items found</p>
{else}
{foreach from=$items item=i}
<div class="media">
  <a class="pull-left" href="{$settings->site_path}/news/view/{$i->slug}.html">
    <img class="media-object img-thumbnail" src="{$settings->site_path}/uploads/images/{$i->preview_image}" style="max-width: 117px;">
  </a>
  <div class="media-body">
    <h4 class="media-heading"><a href="{$settings->site_path}/news/view/{$i->slug}.html">{$i.title|escape:'html'}</a></h4>
    <p>{$i->description}</p>

    <p class="text-muted">posted on {$i->time_created|date_format:'%b %d, %Y'}</p>
    <a href="{$settings->site_path}/news/view/{$i->slug}.html" class="btn btn-default btn-xs" role="button">Read More</a>
  </div>
</div>
{/foreach}
{/if}

<ul class="pager">
  <li class="previous {if $items|count < $perPage}disabled{/if}"><a href="#" id="go_to_next">&larr; Older</a></li>
  <li class="next {if $page <= 1}disabled{/if}"><a href="#" id="go_to_prev">Newer &rarr;</a></li>
</ul>