<!-- extra_fields_editor.tpl -->
<legend>{#KLIQQI_Visual_AdminPanel_Extra_Fields_Editor#}</legend>
    {if $error}
		<div class="alert alert-block {if $error eq 'file updated!'}alert-success{else}alert-danger{/if}">
			<h4 class="alert-heading">{$error}</h4>
		</div>
	{/if}
		<h3>{#KLIQQI_Visual_AdminPanel_Extra_Fields_Instructions#}</h3>
		<table>
		<form action="" method="post">	
			{$xtra_content}
			<input type="submit" class="btn btn-primary" name="save" value="Save Changes" class="btn btn-default"/>
		</form>
		</table>
    
<!--/extra_fields_editor.tpl -->