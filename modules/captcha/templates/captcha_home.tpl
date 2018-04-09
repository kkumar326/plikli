{config_load file=captcha_lang_conf}

<legend>{#Plikli_Captcha_Settings#}</legend>
{if isset($msg)}
	<div class="alert alert-warning fade in">
		<a data-dismiss="alert" class="close">&times;</a>
		{$msg}
	</div>
{/if}
<p>{#Plikli_Captcha_Description#}</p>
<table class="table table-bordered table-striped" cellpadding="0" cellspacing="0">
	<thead>
		<tr><th colspan="2">{#Plikli_Captcha_Captchas#}</th><tr>
	</thead>
	<tbody>
		<tr>
			<td>{#Plikli_Captcha_Solve_Media#}: </td>
			<td> 
				<div id="tab" class="btn-group">
					{if $captcha_method eq "solvemedia"}
						<span class="btn btn-success disabled" disabled>{#PLIKLI_Captcha_Active#}</span>
					{else}
						<a class="btn btn-primary" href="module.php?module=captcha&captcha=solvemedia&action=enable">{#PLIKLI_Captcha_Enable#}</a>
					{/if}
					
				</div>

			</td>
		</tr>
	</tbody>
	<thead>
		<tr><th colspan="2">{#Plikli_Captcha_options#}</th><tr>
	</thead>
	<tbody>
		<tr>
			<td>{#Plikli_Captcha_register#}:  </td>
			<td>
				<div id="tab" class="btn-group">
					<a href="module.php?module=captcha&captcha=default&action=EnableReg&value=true" class="btn btn-default {if $captcha_reg_enabled eq true}active{/if}">{#PLIKLI_Captcha_Enable#}</a>
					<a href="module.php?module=captcha&captcha=default&action=EnableReg&value=false" class="btn btn-default {if $captcha_reg_enabled eq false}active{/if}">{#PLIKLI_Captcha_Disable#}</a>
				</div>
			</td>
		</tr>
		<tr>
			<td>{#Plikli_Captcha_story#}:  </td>
			<td>
				<div id="tab" class="btn-group">
					<a href="module.php?module=captcha&captcha=default&action=EnableStory&value=true" class="btn btn-default {if $captcha_story_enabled eq true}active{/if}">{#PLIKLI_Captcha_Enable#}</a>
					<a href="module.php?module=captcha&captcha=default&action=EnableStory&value=false" class="btn btn-default {if $captcha_story_enabled eq false}active{/if}">{#PLIKLI_Captcha_Disable#}</a>
				</div>
			</td>
		</tr>
		<tr>
			<td>{#Plikli_Captcha_comment#}:  </td>
			<td>
				<div id="tab" class="btn-group">
					<a href="module.php?module=captcha&captcha=default&action=EnableComment&value=true" class="btn btn-default {if $captcha_comment_enabled eq true}active{/if}">{#PLIKLI_Captcha_Enable#}</a>
					<a href="module.php?module=captcha&captcha=default&action=EnableComment&value=false" class="btn btn-default {if $captcha_comment_enabled eq false}active{/if}">{#PLIKLI_Captcha_Disable#}</a>
				</div>
			</td>
		</tr>
	</tbody>
</table>
{config_load file=captcha_plikli_lang_conf}