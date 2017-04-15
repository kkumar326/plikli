{config_load file=spam_trigger_lang_conf}

<fieldset><legend> {#KLIQQI_Spam_Trigger#}</legend>
<p>{#KLIQQI_Spam_Trigger_Instructions#}</p>

<form action="" method="POST" id="thisform">
	
	<legend>{#KLIQQI_Spam_Trigger_Light_List#}: </legend>
	<p>{#KLIQQI_Spam_Trigger_Light_Description#}</p>
	<textarea name='spam_light' class="form-control" rows="15">{$settings.spam_light}</textarea>
	<br /><input type="submit" name="submit" value="{#KLIQQI_Spam_Trigger_Submit#}" class="btn btn-primary" />
	<br /><br />
	
	<legend>{#KLIQQI_Spam_Trigger_Medium_List#}: </legend>
	<p>{#KLIQQI_Spam_Trigger_Medium_Description#}</p>
	<textarea name='spam_medium' class="form-control" rows="15">{$settings.spam_medium}</textarea>
	<br /><input type="submit" name="submit" value="{#KLIQQI_Spam_Trigger_Submit#}" class="btn btn-primary" />
	<br /><br />
	
	<legend>{#KLIQQI_Spam_Trigger_Hard_List#}: </legend>
	<p>{#KLIQQI_Spam_Trigger_Hard_Description#}</p>
	<textarea name='spam_hard' class="form-control" rows="15">{$settings.spam_hard}</textarea>
	<br /><input type="submit" name="submit" value="{#KLIQQI_Spam_Trigger_Submit#}" class="btn btn-primary" />
	<br />

</form>

{config_load file=spam_trigger_kliqqi_lang_conf}