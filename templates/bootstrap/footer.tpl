{************************************
********** Footer Template **********
*************************************}
<!-- footer.tpl -->
<div id="footer">
	{checkActionsTpl location="tpl_plikli_footer_start"}
	<span class="subtext"> 
		Copyright &copy; {php} echo date('Y'); {/php} {#PLIKLI_Visual_Name#}
		| <a href="{$URL_advancedsearch}">{#PLIKLI_Visual_Search_Advanced#}</a> 
		{if $Enable_Live}
			| <a href="{$URL_live}">{#PLIKLI_Visual_Live#}</a>
		{/if}
		{if $Enable_Tags}
			| <a href="{$URL_tagcloud}">{#PLIKLI_Visual_Tags#}</a>
		{/if}
		| <a href="{$URL_topusers}">{#PLIKLI_Visual_Top_Users#}</a>
		| Made with <a href="https://www.plikli.com/" target="_blank" rel="noopener noreferrer">Plikli CMS</a> 
		{if !empty($URL_rss_page)}
			| <a href="{$URL_rss_page}" target="_blank" rel="noopener noreferrer">{$pagename|capitalize} RSS Feed</a>
		{/if}
		| <a href="{$my_base_url}{$my_plikli_base}/rssfeeds.php">{#PLIKLI_Visual_RSS_Feeds#}</a> 
	</span>
	{checkActionsTpl location="tpl_plikli_footer_end"}
</div>
<!--/footer.tpl -->