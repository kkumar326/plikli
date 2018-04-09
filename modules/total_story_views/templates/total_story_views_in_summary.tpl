{config_load file=total_story_views_lang_conf}
{literal}
<style type="text/css">
.total-view-vote{text-align:center;font-size:10px;line-height:10px;color:#aaa;margin-top:5px;}
.total-view{font-size:10px;line-height:10px;color:#aaa;}
</style>
{/literal}
		{if $location neq "tpl_plikli_story_votebox_start" && $location neq "tpl_plikli_story_votebox_end"}
			<span class="total-view">
				<i class="fa fa-film fa-lg"></i>
				{if $plikli_language eq "arabic"}
				<span id="ttl_views">{#PLIKLI_Story_Total_Views#} : </span><span>{$total_link_views}</span> 
				{else}
				<span id="ttl_views">{$total_link_views}</span> <span>{if $total_link_views gt "1"}{#PLIKLI_Story_Total_Views#}{else}{#PLIKLI_Story_Total_View#}{/if}</span>
				{/if}
			</span>
		{else}
			<div class="total-view-vote">
				{if $plikli_language eq "arabic"}
				<span id="ttl_views">{#PLIKLI_Story_Total_Views#} : </span><span>{$total_link_views}</span> 
				{else}
				<span id="ttl_views">{$total_link_views}</span> <span>{if $total_link_views gt "1"}{#PLIKLI_Story_Total_Views#}{else}{#PLIKLI_Story_Total_View#}{/if}</span>
				{/if}
			</div>
		{/if}
{config_load file=total_story_views_plikli_lang_conf}