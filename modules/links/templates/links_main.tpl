{config_load file=links_lang_conf}

	<script type="text/javascript">
		Event.observe(window, 'load', init, false);
		function init() {ldelim}{foreach from=$editinplace_init item=html}{$html}{/foreach}{rdelim}
	</script>

<legend> {#PLIKLI_links#}</legend>
<p>{#PLIKLI_links_Instructions_1#}</p>
<p>{#PLIKLI_links_Instructions_2#}</p>

<form action="" method="POST" id="thisform">
	<table class="table table-bordered table-striped">
		<tbody>
			<tr>
				<td width="20"><input type="checkbox" name="links_comments" value="1" {if $links_settings.comments}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Comments#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_stories" value="1" {if $links_settings.stories}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Stories#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_nofollow" value="1" {if $links_settings.nofollow}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Nofollow#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_yt_stories" value="1" {if $links_settings.yt_stories}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Youtube_Stories#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_yt_comments" value="1" {if $links_settings.yt_comments}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Youtube_Comments#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_fb_stories" value="1" {if $links_settings.fb_stories}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Facebook_Stories#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_fb_comments" value="1" {if $links_settings.fb_comments}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Facebook_Comments#}</label></td>
			</tr>
			<tr>
				<td colspan="2" class="alert-danger"><p>{#PLIKLI_links_Instructions_3#}</p><p><strong style="text-decoration:underline;">{#PLIKLI_links_Convert_All_Users#}</strong> {#PLIKLI_links_Instructions_all_users#}</p><p><strong style="text-decoration:underline;">{#PLIKLI_links_Convert_Moderators#}</strong> {#PLIKLI_links_Instructions_just_moderators#}</p><p><strong style="text-decoration:underline;">{#PLIKLI_links_Convert_Admins#}</strong> {#PLIKLI_links_Instructions_just_admins#}</p></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_all" value="1" {if $links_settings.all}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_All_Users#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_moderators" value="1" {if $links_settings.moderators}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Moderators#}</label></td>
			</tr>
			<tr>
				<td width="20"><input type="checkbox" name="links_admins" value="1" {if $links_settings.admins}checked{/if}/></td>
				<td><label>{#PLIKLI_links_Convert_Admins#}</label></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" value="{#PLIKLI_links_Submit#}" class="btn btn-primary" />
				</td>
			</tr>
		</tbody>
	</table>
</form>

{config_load file=links_plikli_lang_conf}