{foreach from=$items item=i}

    <div>
        <a href="{$settings->site_path}/news/view/{$i->slug}.html">{$i.title|escape:'html'}</a>
        posted on {$i->time_created}


        <div class="text_format">
        {if $i->description}
            {$i->description}
        {else}
            {$i->body}
        {/if}
        </div>
    </div>

{/foreach}