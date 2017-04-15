{************************************
********** Footer Template **********
*************************************}
<!-- footer.tpl -->
<div id="footer">
	{checkActionsTpl location="tpl_kliqqi_footer_start"}
	<span class="subtext"> 
		Copyright &copy; {php} echo date('Y'); {/php} {#KLIQQI_Visual_Name#}
		| <a href="{$URL_advancedsearch}">{#KLIQQI_Visual_Search_Advanced#}</a> 
		{if $Enable_Live}
			| <a href="{$URL_live}">{#KLIQQI_Visual_Live#}</a>
		{/if}
		{if $Enable_Tags}
			| <a href="{$URL_tagcloud}">{#KLIQQI_Visual_Tags#}</a>
		{/if}
		| <a href="{$URL_topusers}">{#KLIQQI_Visual_Top_Users#}</a>
		| Made with <a href="https://www.kliqqi.com/" target="_blank">Kliqqi CMS</a> 
		{if !empty($URL_rss_page)}
			| <a href="{$URL_rss_page}" target="_blank">{$pagename|capitalize} RSS Feed</a>
		{/if}
		| <a href="{$my_base_url}{$my_kliqqi_base}/rssfeeds.php">{#KLIQQI_Visual_RSS_Feeds#}</a> 
	</span>
	{checkActionsTpl location="tpl_kliqqi_footer_end"}
</div>
<!--/footer.tpl -->