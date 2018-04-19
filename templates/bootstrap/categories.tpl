{************************************
****** Category Menu Template *******
*************************************}
<!-- categories.tpl -->
<div class="subnav" id="categories">
	<div class="container">
		<ul class="nav nav-pills">
			{checkActionsTpl location="tpl_widget_categories_start"}
			{assign var=submit_lastspacer value=''}
			{assign var=spacer_diff value=''}
			{section name=thecat loop=$cat_array}
				{if $cat_array[thecat].auto_id neq 0}
					{if $cat_array[thecat].spacercount < $submit_lastspacer}
						{$cat_array[thecat].spacerdiff|repeat_count:'</ul></li>'}
					{/if}
					{if $cat_array[thecat].spacercount > $submit_lastspacer}
						<ul class="dropdown-menu {if $cat_array[thecat].principlecat eq 0}sub-menu{/if} sub-menu-show sub-menu-hide">
					{/if}
					<li class="category_item{if $cat_array[thecat].principlecat neq 0} dropdown{/if}{if !empty($request_category) && $request_category eq $cat_array[thecat].safename} active{/if}">
						<a {if $cat_array[thecat].principlecat neq 0} class="dropdown-toggle active"{/if} href="{if $pagename eq "new" || !empty($groupview) && $groupview eq "new"}{$URL_newcategory, $cat_array[thecat].safename}{else}{$URL_maincategory, $cat_array[thecat].safename}{/if}{if $urlmethod==2}/{/if}">{$cat_array[thecat].name} {if $cat_array[thecat].principlecat neq 0}<b class="caret"></b>{/if}</a>
					{if $cat_array[thecat].principlecat eq 0}
						</li>
					{/if}
					{assign var=submit_lastspacer value=$cat_array[thecat].spacercount}
					{assign var=spacer_diff value=$cat_array[thecat].spacediff}
				{/if}
			{/section}
			{if $submit_lastspacer > $spacer_diff}{$submit_lastspacer|repeat_count:'</ul></li>	'}{/if}
			{checkActionsTpl location="tpl_widget_categories_end"}
		</ul>
	</div>
</div>
<!--/categories.tpl -->