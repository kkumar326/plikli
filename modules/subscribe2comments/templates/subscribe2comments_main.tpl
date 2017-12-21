﻿{config_load file=subscribe2comments_lang_conf}

{if $templatelite.post.submit}
	<div class="alert alert-success">
		<button class="close" data-dismiss="alert">&times;</button>
		{#KLIQQI_Subscribe_2_Comments_Saved#}
    </div>
{/if}

<legend> {#KLIQQI_Subscribe_2_Comments#}</legend>
<p>{#KLIQQI_Subscribe_2_Comments_Instructions#}</p>

<form action="" method="POST" id="thisform">
	<table class="table table-bordered table-striped">
		<tr>
			<td width="180"><label>{#KLIQQI_Subscribe_2_Comments_From#}: </label></td>
			<td><input type="text" class="form-control" name="subscribe2comments_from" value="{$settings.from}" size="40"/></td>
		</tr>
		<tr>
			<td width="180"><label>{#KLIQQI_Subscribe_2_Comments_From_Email#}: </label></td>
			<td><input type="text" class="form-control" name="subscribe2comments_from_email" value="{$settings.from_email}" size="40"/></td>
		</tr>
		<tr>
			<td width="180"><label>{#KLIQQI_Subscribe_2_Comments_Subject#}: </label></td>
			<td><input type="text" class="form-control" name="subscribe2comments_subject" value="{$settings.subject}" size="40"/></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="submit" value="{#KLIQQI_Subscribe_2_Comments_Submit#}" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>

{config_load file="/languages/lang_".$kliqqi_language.".conf"}