
<div class="list-group">
<a href="{$settings->site_path}" class="list-group-item">{t}View site{/t}</a>

{if isset($menu_items)}
	{foreach from=$menu_items item=menu key=menu_name}
	<div class="menu_element_holder{if isset($menu.selected) && $menu.selected}_active{/if}">
		<a href="{if isset($menu.items[0])}{$settings->site_path}/admin/{$menu.items[0].href}{/if}"  class="list-group-item list-group-item-no-radius {if isset($menu.selected) && $menu.selected}active{/if}">{if isset($menu.icon) && $menu.icon}<span class="glyphicon glyphicon-{$menu.icon}"></span>&nbsp;{else}&nbsp;&nbsp;&nbsp;&nbsp;{/if}{$menu.name}</a>
		<div style="margin-bottom: -1px; {if isset($menu.selected) && $menu.selected}{else}display: none;{/if}" class="menu_childs {if isset($menu.selected) && $menu.selected}{else}menu_childs_hidable{/if} menu_childs_{$menu_name}">
		{foreach from=$menu.items item=child}
		<div  class="list-group-item list-group-item-child">
			<a href="#">
				{if isset($child.additional)}
					<a href="{$settings->site_path}/admin/{$child.additional.href}" class="pull-right additional_menu_item"><span class="glyphicon glyphicon-{$child.additional.icon}"></span></a>
				{/if}
				<a href="{$settings->site_path}/admin/{$child.href}" class="list-group-item-child-a">{$child.name}</a>
			</a>
		</div>
		{/foreach}
		</div>
	</div>
	{/foreach}
{/if}

</div>

<script>
$(function(){
	$(".menu_element_holder").each(function() {
		if ($(this).hasClass('active'))
			return;

		$(this).hover(function() {
			$(".menu_childs", this).stop().slideDown('slow');
		}, function() {
			$(".menu_childs", this).stop().slideUp('slow');
		});

		//$(this).mouseenter(function() { $(".menu_childs", this).stop(true, true).slideDown('slow'); });
		//$(this).mouseleave(function() { $(".menu_childs", this).stop(true, true).slideUp('slow'); });
	});

	$(".additional_menu_item").each(function() {
		$(this).hide();
		var that = this;
		$(this).parent().hover(function() {
			$(that).show();
		}, function() {
			$(that).hide();
		});
	});
});

</script>