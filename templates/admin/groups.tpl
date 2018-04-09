<!-- groups.tpl -->
<legend>{#PLIKLI_Visual_AdminPanel_Manage_Groups#}</legend>
<br />
<table class="table table-bordered table-condensed">
	<thead>
		<tr>
			{checkActionsTpl location="tpl_plikli_admin_groups_th_start"}
			<th>{#PLIKLI_Visual_AdminPanel_Group_Name#}</th>
			<th>{#PLIKLI_Visual_AdminPanel_Group_Author#}</th>
			<th>{#PLIKLI_Visual_AdminPanel_Group_Privacy#}</th>
			<th>{#PLIKLI_Visual_AdminPanel_Group_Date#}</th>
			<th style="text-align:center;">{#PLIKLI_Visual_AdminPanel_Group_Edit#}</th>
			<th style="text-align:center;">{#PLIKLI_Visual_AdminPanel_Group_Delete#}</th>
			{checkActionsTpl location="tpl_plikli_admin_groups_th_end"}
		</tr>
	</thead>
	<tbody>
		{foreach from=$groups item=group}
			<tr {if $group.group_status!='Enable'}class="tr_moderated"{/if}>
				{checkActionsTpl location="tpl_plikli_admin_groups_td_start"}
				<td>
					{if $group.group_status!='Enable'}
						<a href='?mode=approve&group_id={$group.group_id}'><i class="fa fa-warning-sign" title="{#PLIKLI_Visual_AdminPanel_Group_Approve#}" alt="{#PLIKLI_Visual_AdminPanel_Group_Approve#}"></i></a>
					{else}
						<i class="fa fa-check" title="{#PLIKLI_Visual_AdminPanel_Group_Approve#}d" alt="{#PLIKLI_Visual_AdminPanel_Group_Approve#}d"></i>
					{/if}
					<a href="{$my_base_url}{$my_plikli_base}/group_story.php?id={$group.group_id}">{$group.group_name}</a>
				</td>
				<td><a href="{$my_base_url}{$my_plikli_base}/admin/admin_users.php?mode=view&user={$group.user_login}">{$group.user_login}</a></td>
				<td>{$group.group_privacy}</td>
				<td>{$group.group_date}</td>
				<td style="text-align:center;"><a class="btn btn-default" href='../editgroup.php?id={$group.group_id}' rel="width:800,height:700"><i class="fa fa-edit" alt="{#PLIKLI_Visual_AdminPanel_Group_Edit#}" title="{#PLIKLI_Visual_AdminPanel_Group_Edit#}"></i></a></td>
{* Redwine: Roles and permissions and Groups fixes *}
				<td style="text-align:center;">{if $amIadmin eq '1'}<a class="btn btn-danger" onclick='return confirm("{#PLIKLI_Visual_Group_Delete_Confirm#}");' href='?mode=delete&group_id={$group.group_id}'><i class="fa fa-trash-o" alt="{#PLIKLI_Visual_AdminPanel_Group_Delete#}" title="{#PLIKLI_Visual_AdminPanel_Group_Delete#}"></i></a>{/if}</td>
				{checkActionsTpl location="tpl_plikli_admin_groups_td_end?"}
			</tr>
		{/foreach}
	</tbody>
</table>
{* Redwine: Roles and permissions and Groups fixes. Fix the button when user met group creation quota *}
<a class="{if !$error_max}btn btn-success{else}btn btn-danger disabled strike-through" title="{#PLIKLI_Visual_Submit_A_New_Group_Error#}"{/if} href="{$my_base_url}{$my_plikli_base}/submit_groups.php" onclick="window.open('{$my_base_url}{$my_plikli_base}/submit_groups.php','popup','width=900,height=900,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false">{#PLIKLI_Visual_AdminPanel_New_Group#}</a>

<!--/groups.tpl -->