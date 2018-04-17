{config_load file=subscribe2comments_lang_conf}

{if $templatelite.post.submit}
	<div class="alert alert-success">
		<button class="close" data-dismiss="alert">&times;</button>
		{#PLIKLI_Subscribe_2_Comments_Saved#}
    </div>
{/if}

<legend> {#PLIKLI_Subscribe_2_Comments#}</legend>
<p>{#PLIKLI_Subscribe_2_Comments_Instructions#}</p>

<form action="" method="POST" id="thisform">
	<table class="table table-bordered table-striped">
		<tr>
			<td width="180"><label>{#PLIKLI_Subscribe_2_Comments_From#}: </label></td>
			<td><input type="text" class="form-control" name="subscribe2comments_from" value="{$subscribe2_settings.from}" size="40"/></td>
		</tr>
		<tr>
			<td width="180"><label>{#PLIKLI_Subscribe_2_Comments_From_Email#}: </label></td>
			<td><input type="text" class="form-control" name="subscribe2comments_from_email" value="{$subscribe2_settings.from_email}" size="40"/></td>
		</tr>
		<tr>
			<td width="180"><label>{#PLIKLI_Subscribe_2_Comments_BG_color#}: </label></td>
			<td><input type="text" class="form-control" id="subscribe2comments_backgroundcolor" name="subscribe2comments_backgroundcolor" value="{$subscribe2_settings.background}" size="40"/><br />
			To customize the heading background color, use this link and follow the instructions!<br />
			<a href="{$my_base_url}{$my_plikli_base}/modules/subscribe2comments/colorpicker/demo/index.html" target="_blank">Heading Customizer</a>
			</td>
		</tr>
		<tr>
			<td width="180"><label>{#PLIKLI_Subscribe_2_Comments_Font_Color#}: </label></td>
			<td><input type="text" class="form-control" id="subscribe2comments_fontcolor" name="subscribe2comments_fontcolor" value="{$subscribe2_settings.fontcolor}" size="40"/><br />
			Enter, here, one of the two colors: Black (#000000) or White (#FFFFFF) depending on what is found to be well contrasted with the header background color you selected!
			</td>
		</tr>
		<tr>
			<td width="180"><label>{#PLIKLI_Subscribe_2_Comments_Subject#}: </label></td>
			<td><input type="text" class="form-control" name="subscribe2comments_subject" value="{$subscribe2_settings.subject}" size="40"/></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="submit" value="{#PLIKLI_Subscribe_2_Comments_Submit#}" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>

{config_load file="/languages/lang_".$plikli_language.".conf"}