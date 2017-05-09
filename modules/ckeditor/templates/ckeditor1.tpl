{config_load file=ckeditor_lang_conf}
{if $Story_Content_Tags_To_Allow neq ''}		
<script type="text/javascript" src="{$my_kliqqi_base}/modules/ckeditor/ckeditor/ckeditor.js"></script>
{/if}
{config_load file=ckeditor_kliqqi_lang_conf}