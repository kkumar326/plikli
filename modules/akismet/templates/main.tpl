{config_load file=akismet_lang_conf}
<fieldset>
	<legend>{#PLIKLI_Akismet_BreadCrumb#}</legend>

	<h2>{#PLIKLI_Akismet_settings_title#}</h2>

	<img src="{$akismet_img_path}key.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageKey">{#PLIKLI_Akismet_manage_key#}</a><br />
	{* <img src="{$akismet_img_path}wrench.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageSettings">{#PLIKLI_Akismet_change_settings#}</a><br /> *}
	
	<br />

	{if $spam_links_count eq 0}
		<img src="{$akismet_img_path}tick.png" align="absmiddle"/> {#PLIKLI_Akismet_no_spam_stories#}
	{else}
		<img src="{$akismet_img_path}exclamation.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageSpam">{$spam_links_count} {#PLIKLI_Akismet_stories_need_reviewed#}</a>
	{/if}
	<br />
	{if $spam_comments_count eq 0}
		<img src="{$akismet_img_path}tick.png" align="absmiddle"/> {#PLIKLI_Akismet_no_spam_comments#}
	{else}
		<img src="{$akismet_img_path}exclamation.png" align="absmiddle"/> <a href = "{$URL_akismet}&view=manageSpamcomments">{$spam_comments_count} {#PLIKLI_Akismet_comments_need_reviewed#}</a>
	{/if}

</fieldset>
{config_load file=akismet_plikli_lang_conf}