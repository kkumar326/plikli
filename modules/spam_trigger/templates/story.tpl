{config_load file=spam_trigger_lang_conf}

{if $templatelite.session.spam_trigger_story_error == 'moderated'}
{* Redwine: Spam Trigger Module was not working as intended. Fix provided by modifying 8 files.">Spam Trigger Module was not working as intended. https://github.com/Pligg/pligg-cms/commit/2faf855793814f82d7c61a8745a93998c13967e0 *}
	<div class="alert-danger spam_trigger_moderated spam_trigger">
		{#PLIKLI_Spam_Trigger_Story_Moderated#}
	</div>
{elseif $templatelite.session.spam_trigger_story_error == 'discarded'}
	<div class="alert-danger spam_trigger_discarded spam_trigger">
		{#PLIKLI_Spam_Trigger_Story_Discarded#}
	</div>
{elseif $templatelite.session.spam_trigger_story_error == 'deleted'}
	<div class="alert-danger spam_trigger_deleted spam_trigger">
		{#PLIKLI_Spam_Trigger_Story_Deleted#}
	</div>
{/if}

{config_load file=spam_trigger_plikli_lang_conf}


{php}unset($_SESSION['spam_trigger_story_error']);{/php}