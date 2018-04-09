{************************************
******* Comment Error Template ******
*************************************}
<!-- comment_errors.tpl -->
<div class="alert alert-warning">
	{*Redwine: added smarty assign for the length of the comment to be able to provide the relevant warning in the comment_error.tpl*}
	{if $max_Comment_Length neq ""}
	{#PLIKLI_Visual_Submit3Errors_Long_Content#}
	{else}
		The Solve Media puzzle answer provided is not correct. Please try again.
	{/if}
	<form id="thisform">
		<input type="button" onclick="gPageIsOkToExit=true; history.go(-1);" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
	</form>
</div>
<br/ >
<!--/comment_errors.tpl -->