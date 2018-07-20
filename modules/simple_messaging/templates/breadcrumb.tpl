{config_load file=simple_messaging_lang_conf}
<li>{if $modulename_sm neq "simple_messaging_inbox"}<a href="{$URL_simple_messaging_inbox}">{/if}{#PLIKLI_MESSAGING_Inbox#}{if $modulename_sm neq "simple_messaging_inbox"}</a>{/if}</li>
{if $modulename_sm eq "simple_messaging_viewmsg"}<li>{#PLIKLI_MESSAGING_Message#}</li>{/if}
{if $modulename_sm eq "simple_messaging_sent"}<li>{#PLIKLI_MESSAGING_Sent#}</li>{/if}
{if $modulename_sm eq "simple_messaging_viewsentmsg"}<li><a href="{$my_plikli_base}/module.php?module=simple_messaging&view=sent">{#PLIKLI_MESSAGING_Sent#}</a></li><li>{#PLIKLI_MESSAGING_Message#}</li>{/if}
{if $modulename_sm eq "simple_messaging_reply"}<li>{#PLIKLI_MESSAGING_Reply#}</li>{/if}
{if $modulename_sm eq "simple_messaging_compose"}<li>{#PLIKLI_MESSAGING_Send#}</li>{/if}
{config_load file=simple_messaging_plikli_lang_conf}