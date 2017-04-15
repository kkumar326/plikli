<div class="simple_messaging_wrapper">

	{include file="./modules/simple_messaging/templates/menu.tpl"}
	{config_load file=$simple_messaging_lang_conf}

	<h3>{#KLIQQI_MESSAGING_Error#}</h3>

	<div class="alert alert-warning">
		<strong>{$message}</strong>
	</div>
		
	<a class="btn btn-warning" href="#" onclick="history.go(-2);">{#KLIQQI_MESSAGING_Back#}</a>
	
</div>

{config_load file=simple_messaging_kliqqi_lang_conf}