{************************************
*********** Submit Errors ***********
*************************************}
<!-- submit_errors_center.tpl -->
<fieldset>
	{************************************
	******* Submit Step 2 Errors ********
	*************************************}
	{*Redwine: To fix the editlink error message in the case where set time limit to edit the story is set to true. It was displaying as plain html and now the same as all other error messages, See pictures in http://mymonalisasmile.com/upload/editlink-error.html*}
	{if $submit_error eq 'cantedit'}
		<div class="alert alert-danger">
		<p>You cannot edit link after {$edit_time} minutes!</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$toredirect}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'invalidurl'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit2Errors_InvalidURL#}{if $submit_url eq "http://"}. {#PLIKLI_Visual_Submit2Errors_InvalidURL_Specify#}{else}: {$submit_url}{/if}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="javascript:gPageIsOkToExit=true;window.history.go(-1);" value="{#PLIKLI_Visual_Submit2Errors_Back#}" class="btn btn-primary">
			</form>
		</div>
	{/if}
	{if $submit_error eq 'dupeurl'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit2Errors_DupeArticleURL#}: {$submit_url}</p>
			<p>{#PLIKLI_Visual_Submit2Errors_DupeArticleURL_Instruct#}</p>
			<p><a href="{$submit_search}"><strong>{#PLIKLI_Visual_Submit2Errors_DupeArticleURL_Instruct2#}</strong></a></p>
			<br style="clear: both;" /><br style="clear: both;" />
			<form id="thisform">
				<input type="button" onclick="javascript:gPageIsOkToExit=true;window.history.go(-1);" value="{#PLIKLI_Visual_Submit2Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{checkActionsTpl location="tpl_plikli_submit_error_2"}
	{************************************
	******* Submit Step 3 Errors ********
	*************************************}
	{if $submit_error eq 'badkey'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_BadKey#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'hashistory'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_HasHistory#}: {$submit_error_history}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'urlintitle'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_URLInTitle#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'incomplete'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_Incomplete#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'long_title'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_Long_Title#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'long_content'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_Long_Content#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'long_tags'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_Long_Tags#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
    
    {if $submit_error eq 'short_tags'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Submit3Errors_Short_Tags#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
    
	{if $submit_error eq 'long_summary'}
		<div class="alert alert-danger">
			<p>{#PLIKLI_Visual_Submit3Errors_Long_Summary#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{if $submit_error eq 'nocategory'}
		<div class="alert alert-danger">
		<p>{#PLIKLI_Visual_Submit3Errors_NoCategory#}</p>
			<br/>
			<form id="thisform">
				<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-primary" />
			</form>
		</div>
	{/if}
	{checkActionsTpl location="tpl_plikli_submit_error_3"}
</fieldset>
<!--/submit_errors_center.tpl -->