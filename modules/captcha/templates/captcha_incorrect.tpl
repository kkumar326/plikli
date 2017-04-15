{config_load file=captcha_lang_conf}
{if $submit_error eq 'register_captcha_error'}

	<div class="alert alert-error">
		{#KLIQQI_Captcha_Incorrect#}
	</div>
	
	<br/>
	{config_load file=captcha_kliqqi_lang_conf}
	<form id="thisform">
		<input type="button" onclick="gPageIsOkToExit=true; document.location.href='{$my_base_url}{$my_kliqqi_base}/{$pagename}.php?id={$link_id}';" value="{#KLIQQI_Visual_Submit3Errors_Back#}" class="btn btn-default" />
	</form>
	
{/if}

