{foreach from=$items item=i}
<div class="media">
  <a class="pull-left" href="#">
    <img class="media-object" src="{$settings->site_path}/uploads/images/{$i->preview_image}" alt="...">
  </a>
  <div class="media-body">
    <h4 class="media-heading"><a href="{$settings->site_path}/news/view/{$i->slug}.html">{$i.title|escape:'html'}</a></h4>
    {$i->description}

    posted on {$i->time_created|date_format:'%b %d, %Y'}
  </div>
</div>
{/foreach}

<ul class="pager">
  <li class="previous {if $items|count < $perPage}disabled{/if}"><a href="#" id="go_to_next">&larr; Older</a></li>
  <li class="next {if $page <= 1}disabled{/if}"><a href="#" id="go_to_prev">Newer &rarr;</a></li>
</ul>