{************************************
***** Recover Password Template *****
*************************************}
<!-- recover_password_center.tpl -->
<div class="leftwrapper">
	{if $errorMsg ne ""}
		<div class="alert alert-block alert-danger">
			<a class="close" data-dismiss="alert" href="#">&times;</a>
			{$errorMsg}
		</div>
	{/if}
	{if $errorMsg eq ""}
	<div class="col-md-4 left">
		<form action="recover.php" id="thisform2" method="post">
			<div class="control-group">	
				{if isset($form_password_error)}
					{ foreach value=error from=$form_password_error }
						<div class="alert alert-danger">
							<button class="close" data-dismiss="alert">&times;</button>
							{$error}
						</div>
					{ /foreach }
				{/if}
				{if $wrong_secret_code neq true}
				<label class="control-label">{#KLIQQI_Visual_New_Code#}:</label>
				<div class="controls">
					<input type="text" id="reg_code" class="form-control reg_password" name="reg_code" value="" size="25" tabindex="14"/>
					<p class="help-inline">{#KLIQQI_Visual_Register_Validation_Code#}</p>
				</div>
				<label class="control-label">{#KLIQQI_Visual_New_Password#}:</label>
				<div class="controls">
					<input type="password" id="reg_password" class="form-control reg_password" name="reg_password" value="" size="25" tabindex="14"/>
					<p class="help-inline">{#KLIQQI_Visual_Register_FiveChar#}</p>
				</div>
				{/if}
			</div>
			{if $wrong_secret_code neq true}
			<div class="control-group">	
				<label class="control-label">{#KLIQQI_Visual_New_Verify_Password#}: </label>
				<div class="controls">
					<input type="password" id="reg_verify" class="form-control reg_password" name="reg_password2" value="" size="25" tabindex="15" />
				</div>
			</div>
			<div>
				<br />
				<input type="submit" value="Submit" class="btn btn-primary" tabindex="15" />
			</div>
			<input type="hidden" name="processrecover" value="1"/>
			<input type="hidden" name="id" value="{$id}"/>
			<input type="hidden" name="n" value="{$n}"/>
				{/if}
		</form>
	</div>
	{/if}
</div>
<!--/recover_password_center.tpl -->