<!-- extra_fields_editor.tpl -->
<legend>{#PLIKLI_Visual_AdminPanel_Extra_Fields_Editor#}</legend>
    {if $error}
		<div class="alert alert-block {if $error eq 'file updated!'}alert-success{else}alert-danger{/if}">
			<h4 class="alert-heading">{$error}</h4>
		</div>
	{/if}
		<h3>{#PLIKLI_Visual_AdminPanel_Extra_Fields_Instructions#}</h3>
		<table class="table table-bordered table-striped">
		<form action="" method="post">	
			{$xtra_content}
			<input type="submit" class="btn btn-primary" name="save" value="Save Changes" class="btn btn-default"/>
		</form>
		</table>
<div>
<h3>Instructions</h3>
<ul>
	<li>"<strong>Enable_Extra_Field_1</strong>" set it to true if you want to enable a field!</li>
	<li>"<strong>Field_1_Title</strong>" Enter the name you want to give to this field. Ex: Author!</li>
	<li>"<strong>Field_1_Instructions</strong>" Enter a Label to explain what the field is. <u>Make sure you provide a space at the beginning of the text to space it from the field's title!</li>
	<li>"<strong>Field_1_Searchable</strong>" set it true if you want this field content to be included in the search!</li>
	<li>"<strong>Field_1_Required</strong>" set it to true if you want this field to be required. When set to true, the browser will issue and notification if the user does not fill the field and halts the execution!<br /><br />The "required" will be added to the input field like so:<br />&lt;input type=&quot;text&quot; name=&quot;link_field1&quot; class=&quot;form-control&quot; id=&quot;link_field1&quot; value=&quot;&quot; size=&quot;60&quot; <span style="color:red">required</span> /&gt;</li>
</ul>
</div> 
<!--/extra_fields_editor.tpl -->