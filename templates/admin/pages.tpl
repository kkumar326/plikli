<!-- pages.tpl -->
<legend>{#KLIQQI_Visual_AdminPanel_Manage_Pages#}</legend>
<br />
<table class="table table-condensed table-bordered table-striped">
	<thead>
		<tr>
			{checkActionsTpl location="tpl_kliqqi_admin_pages_th_start"}
			<th class="page_th_title">{#KLIQQI_Visual_AdminPanel_Page_Submit_Title#}</th>
			<th class="page_th_edit">{#KLIQQI_Visual_AdminPanel_Page_Edit#}</th>
			<th class="page_th_delete">{#KLIQQI_Visual_AdminPanel_Page_Delete#}</th>
			{checkActionsTpl location="tpl_kliqqi_admin_pages_th_end"}
		</tr>
	</thead>
	<tbody>
		{$page_title}
	</tbody>
</table>
{$page_text}
<a class="btn btn-success" href="{$my_base_url}{$my_kliqqi_base}/admin/submit_page.php" title="{#KLIQQI_Visual_AdminPanel_Page_Submit_New#}">{#KLIQQI_Visual_AdminPanel_Page_Submit_New#}</a>
<!--/pages.tpl -->