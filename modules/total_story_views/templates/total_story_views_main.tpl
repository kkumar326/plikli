{config_load file=total_story_views_lang_conf}
<legend> {#PLIKLI_Story_Total_Views_Title#}</legend>
{#PLIKLI_Story_Total_Views_Instructions#}
<br />

<form action="" method="POST" id="thisform">

	<legend>{#PLIKLI_Story_Total_Views_General#}</legend>
	<br />

	<table class="table table-bordered table-striped">
		<tbody>
			<tr>
				<td style="min-width:130px;width:200px;"><label><a href="#">{#PLIKLI_Story_Total_Views_Place#}</a>:</label></td>
				<td>
					<select name="total_views_place" class="form-control">
						<option {if $total_views_settings.place == 'story_total_views_custom'}selected{/if}>story_total_views_custom</option>
						{foreach from=$story_view_places item=place}
							<option {if $total_views_settings.place==$place}selected{/if}>{$place}</option>
						{/foreach}
					</select></td>
			</tr>
			<tr>
				<td style="min-width:130px;width:200px;"><label><a href="#">{#PLIKLI_Story_Total_Views_Sidebar#}</a>:</label></td>
				<td>
					<select name="total_views_sidebar" class="form-control">
						
							{if $total_views_settings.sidebar == 'on'}
								<option selected>on</option>
								<option>off</option>
							{else}
								<option selected>off</option>
								<option>on</option>
							{/if}

					</select></td>
			</tr>
			<tr>
				<td style="min-width:130px;width:200px;"><label><a href="#">{#PLIKLI_Story_Total_Views_Sidebar_count#}</a>:</label></td>
				<td>
					<input type="text" class="form-control" style="width: 100px;" name="total_views_count" size="3" value="{$total_views_settings.count}" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="{#PLIKLI_Story_Total_Views_Submit#}" class="btn btn-primary"/></td>
			</tr>
		</tbody>
	</table>
	<div style="clear:both;"></div>
</form>

<br /><br />
<hr class="soften" />

<legend>{#PLIKLI_Story_Total_Views_Field_Definitions#}</legend>
<p>{#PLIKLI_Story_Total_Views_Field_Definitions_Desc#}</p>
{config_load file=total_story_views_plikli_lang_conf}


<h4>The default is "<span style="color:red">tpl_plikli_story_tools_start</span>" and this is how it is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/tools-start.jpg" alt="Option tpl_plikli_story_tools_start" title="Option tpl_plikli_story_tools_start" /></div><br />

<h4>"<span style="color:red">tpl_plikli_story_tools_end</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/tools-end.jpg" alt="Option tpl_plikli_story_tools_end" title="Option tpl_plikli_story_tools_end" /></div><br />
<hr />
<h4>"<span style="color:red">tpl_plikli_story_start</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/story-start.jpg" alt="Option tpl_plikli_story_start" title="Option tpl_plikli_story_start" /></div><br />

<h4>"<span style="color:red">tpl_plikli_story_end</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/story-end.jpg" alt="Option tpl_plikli_story_end" title="Option tpl_plikli_story_end" /></div><br />
<hr />
<h4>"<span style="color:red">tpl_plikli_story_votebox_start</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/votebox-start.jpg" alt="Option tpl_plikli_story_votebox_start" title="Option tpl_plikli_story_votebox_start" /></div><br />

<h4>"<span style="color:red">tpl_plikli_story_votebox_end</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/votebox-end.jpg" alt="Option tpl_plikli_story_votebox_end" title="Option tpl_plikli_story_votebox_end" /></div><br />
<hr />
<h4>"<span style="color:red">tpl_plikli_story_title_start</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/title-start.jpg" alt="Option tpl_plikli_story_title_start" title="Option tpl_plikli_story_title_start" /></div><br />

<h4>"<span style="color:red">tpl_plikli_story_title_end</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/title-end.jpg" alt="Option tpl_plikli_story_title_end" title="Option tpl_plikli_story_title_end" /></div><br />
<hr />
<h4>"<span style="color:red">tpl_link_summary_pre_story_content</span>" and "<span style="color:red">tpl_plikli_story_body_start</span>" are displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/summary-pre-story-content.jpg" alt="Option tpl_link_summary_pre_story_content" title="Option tpl_link_summary_pre_story_content" /></div><br />

<h4>"<span style="color:red">tpl_plikli_story_body_end</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/body-end.jpg" alt="Option tpl_link_summary_pre_story_body_end" title="Option tpl_link_summary_pre_story_body_end" /></div><br />
<hr />
<h4>"<span style="color:red">tpl_plikli_story_body_start_full</span>" is displayed the same as "<span style="color:red">tpl_plikli_story_body_start</span>" BUT ONLY ON THE STORY PAGE.</h4>

<h4>"<span style="color:red">tpl_plikli_story_body_end_full</span>" is displayed the same as "<span style="color:red">tpl_plikli_story_body_end</span>" BUT ONLY ON THE STORY PAGE.</h4>
<hr />

<h4>"<span style="color:red">Sidebar</span>" is displayed:</h4>
<div style="text-align:center;"><img src="{$my_plikli_base}/modules/total_story_views/templates/images/sidebar.jpg" alt="Most Viewed in Sidebar" title="Most Viewed in Sidebar" /></div><br />
<hr />


