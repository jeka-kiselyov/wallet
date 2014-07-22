{foreach from=$items item=i}

    <div>
        <a href="{$settings->site_path}/news/view/{$i->slug}.html">{$i->title|escape:'html'}</a>
        posted on {$i->time_created|date_format:"%B %e, %Y"}
        {foreach from=$i->get_categories() item=c}
            {*
            <a href="{$settings->site_path}/news/category/{$c->id}">Cat {$c->id}</a> {$c->name|escape:'html'}<br>
            *}
        {/foreach}


        <div class="text_format">
        {if $i->description}
            {$i->description}
        {else}
            {$i->body}
        {/if}
        </div>
    </div>

</div>
{/foreach}
{add_css file="css/text_format"}

{include file="pages/news/pager.tpl"}