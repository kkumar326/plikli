{config_load file=admin_snippet_lang_conf}

<legend>{#PLIKLI_Admin_Snippet#}</legend>
<p>{#PLIKLI_Admin_Snippet_Instructions_1#}</p>
<legend>{#PLIKLI_Admin_Snippet_Instructions#}</legend>
{#PLIKLI_Admin_Snippet_Instructions_Example#}
<br />

{if $snippet_error}
	<div class="alert fade in">
		<a data-dismiss="alert" class="close">&times;</a>
		{$snippet_error}
	</div>
{/if}

<form name="snippet" method="post" enctype='multipart/form-data'>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>{#PLIKLI_Admin_Snippet_Name#}</th>
				<th>{#PLIKLI_Admin_Snippet_Location#}</th>
				<th>{#PLIKLI_Admin_Snippet_Updated#}</th>
				<th style="width:75px;">{#PLIKLI_Admin_Snippet_Order#}</th>
				<th style="width:75px;">{#PLIKLI_Admin_Snippet_Status#}</th>
				<th style="width:50px;">{#PLIKLI_Admin_Snippet_Delete#}</th>	
			</tr>
		<tbody>
		{section name=id loop=$template_snippets}
			<tr>
				<td><a href="?module=admin_snippet&mode=edit&id={$template_snippets[id].snippet_id}">{$template_snippets[id].snippet_name}</a></td>
				<td>{$template_snippets[id].snippet_location}</td>
				<td>{$template_snippets[id].snippet_updated}</td>
				<td><center><input type="text" name="snippet_order[{$template_snippets[id].snippet_id}]" id="order-{$template_snippets[id].snippet_id}" value="{$template_snippets[id].snippet_order}" class="form-control"></center></td>
				<td><center><input type="text" name="snippet_status[{$template_snippets[id].snippet_id}]" id="status-{$template_snippets[id].snippet_id}" value="{$template_snippets[id].snippet_status}" class="form-control"></center></td>
				<td><center><input type="checkbox" name="snippet_delete[{$template_snippets[id].snippet_id}]" id="delete-{$template_snippets[id].snippet_id}" value="1"></center></td>
			</tr>	
		{/section}
		</tbody>
	</table>

	<p style="text-align:right"><a href="javascript:check_all()">{#PLIKLI_Admin_Snippet_Check_All#}</a>&nbsp;&nbsp;&nbsp;<a href="javascript:uncheck_all()">{#PLIKLI_Admin_Snippet_Uncheck_All#}</a></p><br />

	<div style="text-align:right">
		<input type="submit" value="{#PLIKLI_Admin_Snippet_Add_New#}" onclick="document.location='?module=admin_snippet&mode=new'; return false;" class="btn btn-success" /> 
		<input type="submit" name="update" value="{#PLIKLI_Admin_Snippet_Update#}" class="btn btn-default" />
		<input type="submit" name="export" value="{#PLIKLI_Admin_Snippet_Export_Selected#}" class="btn btn-default" />
		<input type="submit" name="delete" value="{#PLIKLI_Admin_Snippet_Delete_Selected#}" class="btn btn-danger" />
	</div>
	
	{literal}
	<script type="text/javascript">

	</script>
	<script type="text/css">

	</script>
	{/literal}

	<input type="file" name="file" id="file" >
	<input type="submit" name="import" value="{#PLIKLI_Admin_Snippet_Import#}" class="btn btn-primary" />

</form>

{literal}
<SCRIPT>
function check_all() {
	for (var i=0; i< document.snippet.length; i++) {
		if (document.snippet[i].type == "checkbox") {
			document.snippet[i].checked = true;
		}
	}
}
function uncheck_all() {
	for (var i=0; i< document.snippet.length; i++) {
		if (document.snippet[i].type == "checkbox") {
			document.snippet[i].checked = false;
		}
	}
}
</SCRIPT>
{/literal}

{config_load file=admin_snippet_plikli_lang_conf}