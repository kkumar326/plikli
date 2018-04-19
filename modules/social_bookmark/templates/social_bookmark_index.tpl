{checkActionsTpl location="tpl_plikli_module_social_bookmark_start"}
{config_load file=social_bookmark_lang_conf}
<span id="linksummaryAddLink">
	&nbsp;<i class="fa fa-share-alt"></i> <a href="javascript://" onclick="var replydisplay=document.getElementById('addto-{$link_shakebox_index}').style.display ? '' : 'none';document.getElementById('addto-{$link_shakebox_index}').style.display = replydisplay;"> {#Social_Bookmark_AddTo#}</a>
</span>
<span id="addto-{$link_shakebox_index}" style="display:none">
<div style="position:absolute;display:block;background:#fff;padding:10px;margin:10px 0 0 100px;font-size:12px;border:2px solid #bbb;z-index:999;">
<a title="submit '{$title_short}' to Twitter" href="http://twitter.com/home?status" onclick="window.open('http://twitter.com/home?status={$enc_title_short}%20{$my_base_url}{$story_url}', 'Twitter','toolbar=no,width=700,height=400'); return false;"><img src="{$my_base_url}{$my_plikli_base}/modules/social_bookmark/images/twitter.png" border="0" alt="submit '{$title_short}' to Twitter" /></a>
<br /><br /><a title="submit '{$title_short}' to facebook" href="http://www.facebook.com/sharer.php?u={$my_base_url}{$story_url}&t={$title_short}"><img src="{$my_base_url}{$my_plikli_base}/modules/social_bookmark/images/facebook.png" border="0" alt="submit '{$title_short}' to facebook" /></a>
<br /><br /><a title="submit '{$title_short}' to google" href="https://plus.google.com/share?url={$my_base_url}{$story_url}&title={$title_short}"><img src="{$my_base_url}{$my_plikli_base}/modules/social_bookmark/images/google.png" border="0" alt="submit '{$title_short}' to google" /></a>		
<br /><br /><a title="eMail '{$title_short}' to" href="mailto:?subject={$title_short}&body={$my_base_url}{$story_url}"><img src="{$my_base_url}{$my_plikli_base}/modules/social_bookmark/images/email.png"/></a>
</div>
</span>

{checkActionsTpl location="tpl_plikli_module_social_bookmark_end"}
{config_load file=social_bookmark_plikli_lang_conf}