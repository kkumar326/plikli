<!-- user_enable.tpl -->
<legend>{#PLIKLI_Visual_View_User_Enable#}</legend>
<div class="alert alert-warning expires-warning">{#PLIKLI_Visual_Page_Expires#}</div>
<p>Are you sure you want to "enable" this user and allow them to logging in?</p>
<p>
	<a class="btn btn-danger" href="{$my_base_url}{$my_plikli_base}/admin/admin_users.php?mode=yesenable&user={$user}{$uri_token_admin_users_enable}">Yes, enable this user.</a>
	<a class="btn btn-default" href="javascript: history.go(-1)">No, cancel</a>
</p>
<p>An enabled user will be able to login."</p>
<!--/user_enable.tpl -->