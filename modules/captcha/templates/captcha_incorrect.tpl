{config_load file=captcha_lang_conf}
{if $submit_error eq 'register_captcha_error'}

	<div class="alert alert-error">
		{#PLIKLI_Captcha_Incorrect#}
	</div>
	
	<br/>
	
	<form id="thisform">
		<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_plikli_base}/{$pagename}.php?id={$link_id}';" value="{#PLIKLI_Visual_Submit3Errors_Back#}" class="btn btn-default" />
	</form>
	
{/if}
{config_load file=captcha_plikli_lang_conf}
