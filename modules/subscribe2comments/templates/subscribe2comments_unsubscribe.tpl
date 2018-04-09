{config_load file=subscribe2comments_lang_conf}
{if !empty($message_uns)}
	<div class="alert alert-success">{$message_uns}{if !empty($message_uns) && !empty($story_url)}<br /><a href="{$story_url}">{$story_url}</a>{/if}</div>
{elseif !empty($message_error)}
	<div class="alert alert-danger">{$message_error}</div>
{/if}

{config_load file=subscribe2comments_plikli_lang_conf}