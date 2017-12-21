{config_load file=subscribe2comments_lang_conf}
<div class="masonry_wrapper">
	<table class="table table-bordered table-striped">
		<thead class="table_title">
			<tr>
				<td colspan="2"><strong>{#KLIQQI_Subscribe_2_Comments_Title#}</strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><input type="checkbox" name="auto_comment_alert" id="auto_comment_alert" value="1" {if $subscribe2comments eq '1'}checked{/if}/></td>
				<td>{#KLIQQI_Subscribe_2_Comments_Switch#}</td>
			</tr>
		</tbody>
	</table>
</div>

{config_load file="/languages/lang_".$kliqqi_language.".conf"}
