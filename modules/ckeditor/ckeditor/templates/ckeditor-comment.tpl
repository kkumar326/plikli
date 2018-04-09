{config_load file=ckeditor_lang_conf}
{if $Story_Content_Tags_To_Allow neq ''}
{literal}<script>CKEDITOR.replace( 'comment_content' );</script>{/literal}
{/if}
{config_load file=ckeditor_plikli_lang_conf}