<div class="simple_messaging_wrapper">

	{checkActionsTpl location="tpl_kliqqi_module_simple_messaging_start"}
	{include file="./modules/simple_messaging/templates/menu.tpl"}
	{config_load file=$simple_messaging_lang_conf}

	<form name="bulk_moderate" action="{$my_kliqqi_base}/module.php?module=simple_messaging&view=inbox&action=bulkmod" method="post">
		<table class="table">
			<thead>
				<tr>
					<th>{#KLIQQI_MESSAGING_From#}</th>
					<th>{#KLIQQI_MESSAGING_Subject#}</th>
					<th style="min-width:150px">{#KLIQQI_MESSAGING_Sent#}</th>
					<th style="width:40px;">{#KLIQQI_MESSAGING_Delete#}</th>
				</tr>
			</thead>
			<tbody>
				{if $msg_array neq ""}
					{section name=themessage loop=$msg_array}
						<tr id="msg_row_{$msg_array[themessage].id}">
							<td><a href="{$my_kliqqi_base}/user.php?login={$msg_array[themessage].sender_name}">{$msg_array[themessage].sender_name}</a></td>
							<td>
								{if isset($msg_array[themessage].read) && $msg_array[themessage].read eq 0}<strong> <img src="{$simple_messaging_path}images/new.png" align="absmiddle" /> </strong>{/if}
								<a href="{$URL_simple_messaging_viewmsg}{$msg_array[themessage].id}">
									{$msg_array[themessage].title}
								</a>
							</td>
							<td>{$msg_array[themessage].date}</td>
							<td style="text-align:center;"><input type="checkbox" name="message[{$msg_array[themessage].id}]" id="message-{$msg_array[themessage].id}" value="delete"></td>
						</tr>
					{/section}
					
				{else}
					<tr>
						<td colspan="4"><div align="center">{#KLIQQI_MESSAGING_No_Messages#}</div></td>
					</tr>
				{/if}
			</tbody>
		</table>

		{if $msg_array neq ""}
			<p align="right"><input type="submit" name="submit" value="{#KLIQQI_MESSAGING_Delete_Selected#}" class="btn btn-danger" /></p>
		{/if}

	</form>
</div>

{config_load file=simple_messaging_kliqqi_lang_conf}
{checkActionsTpl location="tpl_kliqqi_module_simple_messaging_end"}