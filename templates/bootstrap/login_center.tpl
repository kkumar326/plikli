{************************************
******** Login Page Template ********
*************************************}
<!-- login_center.tpl -->
<div class="leftwrapper">
	{if $errorMsg ne ""}
		<div class="alert alert-block alert-danger">
			<a class="close" data-dismiss="alert" href="#">&times;</a>
			{$errorMsg}
		</div>
	{/if}
	{checkActionsTpl location="tpl_login_top"}
	<div class="col-md-4 login-left">
		<form id="thisform" method="post">
			<h2>{#PLIKLI_Visual_Login_Login#}</h2>
			<p>{#PLIKLI_Visual_Login_Have_Account#}</p>
			<form action="{$URL_login}" method="post">	
				<strong>{#PLIKLI_Visual_Login_Username#}/{#PLIKLI_Visual_Register_Email#}:</strong><br />
				<input autofocus="autofocus" type="text" id="username" name="username" class="form-control" value="{if isset($login_username)}{$login_username}{/if}" tabindex="1" /><br />
				
				<strong>{#PLIKLI_Visual_Login_Password#}:</strong><br />
				<input type="password" name="password" class="form-control" tabindex="2" /><br />
				<input type="hidden" name="processlogin" value="1"/>
				<input type="hidden" name="return" value="{if isset($get.return)}{$get.return}{/if}"/>
				<div class="login-submit">
					<input type="submit" value="{#PLIKLI_Visual_Login_LoginButton#}" class="btn btn-primary" tabindex="4" />
				</div>
				<div class="login-remember">
					<input type="checkbox" class="col-sm-offset-2" name="persistent" tabindex="3" /> {#PLIKLI_Visual_Login_Remember#}
				</div>
			</form>
		</form>
	</div>
	<div class="col-md-4 login-middle">
		<form action="{$URL_login}" id="thisform2" method="post">
			<h2>{#PLIKLI_Visual_Login_ForgottenPassword#}</h2>
			<p>{#PLIKLI_Visual_Login_EmailChangePass#}</p>
			<strong>{#PLIKLI_Visual_Register_Email#}:</strong><br />
			<input type="text" name="email" class="form-control" size="25" tabindex="5" id="forgot-email" value="" />
			<br />
			<input type="submit" value="Submit" class="btn btn-primary" tabindex="6" />
			<input type="hidden" name="processlogin" value="3"/>
			<input type="hidden" name="return" value="{if isset($get.return)}{$get.return}{/if}"/>
		</form>
	</div>
	<div class="col-md-4 login-right">
		<h2>{#PLIKLI_Visual_Login_NewUsers#}</h2>
		<p>{#PLIKLI_Visual_Login_NewUsersA#}<a href="{$register_url}" class="btn btn-success btn-xs" tabindex="7">{#PLIKLI_Visual_Login_NewUsersB#}</a>{#PLIKLI_Visual_Login_NewUsersC#}</p>
	</div>
	
	<div class="clearfix"></div>
	<br /><br />

	{checkActionsTpl location="tpl_login_bottom"}
</div>

<!--/login_center.tpl -->