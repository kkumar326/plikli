<!--user_killspam.tpl -->
<legend>{#PLIKLI_Visual_Breadcrumb_User_Killspam#}</legend>
<div class="alert alert-warning expires-warning">{#PLIKLI_Visual_Page_Expires#}</div>
<p>{#PLIKLI_Visual_View_Killspam_Step1#}</p>
<p>
	<a class="btn btn-danger" href="{$my_base_url}{$my_plikli_base}/admin/admin_users.php?mode=yeskillspam&user={$user}&id={$id}{$uri_token_admin_users_killspam}">{#PLIKLI_Visual_Ban_Link_Yes#}</a>
	<a class="btn btn-default" href="javascript:history.back()">{#PLIKLI_Visual_Ban_Link_No#}</a>
</p>
<!--/user_killspam.tpl -->