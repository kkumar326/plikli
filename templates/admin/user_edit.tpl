<!-- user_edit.tpl -->
{section name=nr loop=$userdata}

	<script>
		var message = "{#PLIKLI_Visual_Register_Error_NoPassMatch#}";
		{literal}
		function check(form)
		{
			if (form.password.value != form.password2.value)
			{
				alert(message);
				form.password.focus();
				return false;
			}
			return true;
		}
		{/literal}
	</script>
	
	<legend>{#PLIKLI_Visual_Breadcrumb_Edit_User#}: <a href="{$my_base_url}{$my_plikli_base}/user.php?login={$userdata[nr].user_login}">{$userdata[nr].user_login}</a></legend>
	<div class="alert alert-warning expires-warning">{#PLIKLI_Visual_Page_Expires#}</div>
	<form id="form1" name="form1" method="post" action="" onsubmit="return check(this);">
    
    <input type="hidden" name="token" value="{$uri_token_admin_users_edit}" />
    
		{if isset($username_error)}
			<div class="alert alert-warning">
				<button class="close" data-dismiss="alert">&times;</button>
				{foreach value=error from=$username_error }
					<p class="error">{$error}</p>
				{/foreach}
			</div>
		{/if}
        
        {if isset($email_error)}
			<div class="alert alert-warning">
				<button class="close" data-dismiss="alert">&times;</button>
				{foreach value=error from=$email_error }
					<p class="error">{$error}</p>
				{/foreach}
			</div>
		{/if}
		
        {if isset($password_error)}
			<div class="alert alert-warning">
				<button class="close" data-dismiss="alert">&times;</button>
				{foreach value=error from=$password_error }
					<p class="error">{$error}</p>
				{/foreach }
			</div>
		{/if}
		
		<table class="table table-bordered table-striped">
			<tr>
				<td style="width:215px;">
					<label>{#PLIKLI_Visual_View_User_Login#}:</label>
				</td>
				<td><input name="login" class="form-control" value="{$userdata[nr].user_login}" ></td>
			</tr>
			{if $userdata[nr].user_id neq 1 && $isadmin}
				<tr>
					<td><label>{#PLIKLI_Visual_View_User_Level#}:</label></td>
					<td><select name="level" class="form-control">{html_options values=$levels output=$levels selected=$userdata[nr].user_level}</select></td>
				</tr>
			{else}
				<tr>
					<td colspan="2"><input name="level" type="hidden" value="{$userdata[nr].user_level}" /></td>
				</tr>
			{/if}
			<tr>
				<td><label>{#PLIKLI_Visual_View_User_Email#}:</label></td>
				<td><input name="email" class="form-control" value="{$userdata[nr].user_email}"></td>
			</tr>
			<tr>
				<td><label>{#PLIKLI_Visual_Profile_NewPass#}:</label></td>
				<td><input name="password" class="form-control" type="password"></td>
			</tr>
			<tr>
				<td><label>{#PLIKLI_Visual_Profile_VerifyNewPass#}:</label></td>
				<td><input name="password2" class="form-control" type="password"></td>
			</tr>
			{checkActionsTpl location="tpl_admin_user_edit_center_fields"}
			<tr>
				<td>
					<a class="btn btn-default" href="{$my_base_url}{$my_plikli_base}/profile.php?login={$userdata[nr].user_login}">{#PLIKLI_Visual_Submit3_Modify#} {#PLIKLI_Visual_Breadcrumb_Profile#} {#PLIKLI_Visual_Profile#}</a>
				</td>
				<td>
					<a class="btn btn-default" href="?mode=resetpass&user={$userdata[nr].user_login}{$uri_token_admin_users_edit}" onclick="return confirm('{#PLIKLI_Visual_View_User_Reset_Pass_Confirm#}')">{#PLIKLI_Visual_View_User_Reset_Pass#}</a>
				</td>
			</tr>
			<tr>
				<td>
					<a class="btn btn-default"  href="?mode=view&user={$userdata[nr].user_id}"><i class="fa fa-chevron-left"></i> {#PLIKLI_Visual_View_User_Edit_Cancel#}</a>
				</td>
				<td>
					{$hidden_token_admin_users_edit}
					<input type="submit" name="mode" value="{#PLIKLI_Visual_Profile_Save#}" class="btn btn-primary">
				</td>
			</tr>
		</table>
	</form>	
{sectionelse}
	{include file="{$my_base_url}{$my_plikli_base}/templates/admin/user_does_not_exist.tpl"}
{/section}
<!--/user_edit.tpl -->