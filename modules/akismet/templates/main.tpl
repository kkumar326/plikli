{config_load file=akismet_lang_conf}
<fieldset>
	<legend>{#KLIQQI_Akismet_BreadCrumb#}</legend>

	<h2>{#KLIQQI_Akismet_settings_title#}</h2>

	<img src="{$akismet_img_path}key.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageKey">{#KLIQQI_Akismet_manage_key#}</a><br />
	{* <img src="{$akismet_img_path}wrench.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageSettings">{#KLIQQI_Akismet_change_settings#}</a><br /> *}
	
	<br />

	{if $spam_links_count eq 0}
		<img src="{$akismet_img_path}tick.png" align="absmiddle"/> {#KLIQQI_Akismet_no_spam_stories#}
	{else}
		<img src="{$akismet_img_path}exclamation.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageSpam">{$spam_links_count} {#KLIQQI_Akismet_stories_need_reviewed#}</a>
	{/if}
	<br />
	{if $spam_comments_count eq 0}
		<img src="{$akismet_img_path}tick.png" align="absmiddle"/> {#KLIQQI_Akismet_no_spam_comments#}
	{else}
		<img src="{$akismet_img_path}exclamation.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageSpamcomments">{$spam_comments_count} {#KLIQQI_Akismet_comments_need_reviewed#}</a>
	{/if}

</fieldset>
{config_load file=akismet_kliqqi_lang_conf}