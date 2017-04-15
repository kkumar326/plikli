{config_load file=links_lang_conf}

	<script type="text/javascript">
		Event.observe(window, 'load', init, false);
		function init() {ldelim}{foreach from=$editinplace_init item=html}{$html}{/foreach}{rdelim}
	</script>

<legend> {#KLIQQI_links#}</legend>
<p>{#KLIQQI_links_Instructions_1#}</p>
<p>{#KLIQQI_links_Instructions_2#}</p>

<form action="" method="POST" id="thisform">
	<table class="table table-bordered table-striped">
		<tbody>
			<tr>
				<td width="20"><input type="checkbox" name="links_comments" value="1" {if $settings.comments}checked{/if}/></td>
				<td><label>{#KLIQQI_links_Convert_Comments#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_stories" value="1" {if $settings.stories}checked{/if}/></td>
				<td><label>{#KLIQQI_links_Convert_Stories#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_nofollow" value="1" {if $settings.nofollow}checked{/if}/></td>
				<td><label>{#KLIQQI_links_Convert_Nofollow#}</label></td>
			</tr>
			<tr>
				<td colspan="2" class="alert-danger"><p>{#KLIQQI_links_Instructions_3#}</p><p><strong style="text-decoration:underline;">{#KLIQQI_links_Convert_All_Users#}</strong> {#KLIQQI_links_Instructions_all_users#}</p><p><strong style="text-decoration:underline;">{#KLIQQI_links_Convert_Moderators#}</strong> {#KLIQQI_links_Instructions_just_moderators#}</p><p><strong style="text-decoration:underline;">{#KLIQQI_links_Convert_Admins#}</strong> {#KLIQQI_links_Instructions_just_admins#}</p></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_all" value="1" {if $settings.all}checked{/if}/></td>
				<td><label>{#KLIQQI_links_Convert_All_Users#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_moderators" value="1" {if $settings.moderators}checked{/if}/></td>
				<td><label>{#KLIQQI_links_Convert_Moderators#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_admins" value="1" {if $settings.admins}checked{/if}/></td>
				<td><label>{#KLIQQI_links_Convert_Admins#}</label></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" value="{#KLIQQI_links_Submit#}" class="btn btn-primary" />
				</td>
			</tr>
		</tbody>
	</table>
</form>



{config_load file=links_kliqqi_lang_conf}