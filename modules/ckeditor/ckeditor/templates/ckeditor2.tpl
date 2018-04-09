{config_load file=ckeditor_lang_conf}
{if $Story_Content_Tags_To_Allow neq ''}
	{php}echo "tags are " .$main_smarty->get_template_vars('Story_Content_Tags_To_Allow');{/php}
	{literal}<script>CKEDITOR.replace( 'bodytext' );</script>{/literal}
	{if $SubmitSummary_Allow_Edit neq ''}
		{literal}<script>CKEDITOR.replace( 'summarytext' );</script>{/literal}
	{/if}
{/if}
{config_load file=ckeditor_plikli_lang_conf}