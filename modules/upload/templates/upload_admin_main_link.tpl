{config_load file=upload_lang_conf}
<li{if $modulename eq "upload"} class="active"{/if}><a href="{$my_plikli_base}/module.php?module=upload">{* <img src="{$my_plikli_base}/modules/upload/templates/upload.gif" align="absmiddle"/>  *}{#PLIKLI_Upload#}</a></li>
{config_load file=upload_plikli_lang_conf}