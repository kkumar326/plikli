{************************************
******* Comment Form Template *******
*************************************}
<!-- comment_form.tpl -->
{if $Enable_Comments neq '1'}
<div class="alert alert-danger">{$disable_Comments_message}</div>
{else}
<a name="discuss"></a>
{checkActionsTpl location="tpl_plikli_story_comments_start"}
<form action="" method="post" id="thisform" name="mycomment_form">
<div class="form-horizontal">
	<fieldset>
		{checkActionsTpl location="tpl_plikli_story_comments_submit_start"}
		<div class="control-group">
			<label for="fileInput" class="control-label">{#PLIKLI_Visual_Comment_Send#}</label>
			<div class="controls">
{* Redwine: to display the allowed HTML tags. *}
				{ if $Story_Content_Tags_To_Allow eq ""}
						<p class="help-inline"><strong>{#PLIKLI_Visual_Submit2_No_HTMLTagsAllowed#} </strong>{*#PLIKLI_Visual_Submit2_HTMLTagsAllowed#*}</p>
					{else}
							<p class="help-inline"><strong>{#PLIKLI_Visual_Submit2_HTMLTagsAllowed#}:</strong> {$Story_Content_Tags_To_Allow}</p>
					{/if }
				<textarea autofocus="autofocus" name="comment_content" id="comment_content" class="form-control comment-form" rows="6" />{if isset($TheComment)}{$TheComment}{/if}</textarea>
				<p class="help-inline">&nbsp;<hr /></p>
			</div>
		</div>
        
		{if isset($register_step_1_extra)}
			{$register_step_1_extra}
		{/if}
		{checkActionsTpl location="tpl_plikli_story_comments_submit_end"}
		<div class="form-actions">
			<input type="hidden" name="process" value="newcomment" />
			<input type="hidden" name="randkey" value="{$randkey}" />
			<input type="hidden" name="link_id" value="{$link_id}" />
			<input type="hidden" name="user_id" value="{$user_id}" />
            <input type="hidden" name="parrent_comment_id" value="{$parrent_comment_id}" />
			<input type="submit" name="submit" value="{#PLIKLI_Visual_Comment_Submit#}" class="btn btn-primary" />
		</div>
	</fieldset>
</div><!--/.form-horizontal -->
</form>
{checkActionsTpl location="tpl_plikli_story_comments_form_end"}
{/if}
<!--/comment_form.tpl -->