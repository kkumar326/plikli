{config_load file=subscribe2comments_lang_conf}
 {if $user_authenticated eq true}
	{php}
		global $current_user, $db;
		$subs = $db->get_row("SELECT * FROM `".table_prefix . "subscribe2comments` WHERE notify_user_id={$current_user->user_id} AND notify_link_id='{$this->_vars['link_id']}'",ARRAY_A);
		$this->assign('notify_id',$subs['notify_link_id']);
	{/php}

	<span class="submit_cs" id="{$link_id}">
		<span style="margin:15px 0 2px 0;" class="btn btn-primary" href="javascript://" onclick="subscribe_2_comments({$link_id},{$link_id}{if $notify_id},1{/if});" ><i class="fa fa-envelope"></i> 
			{if $notify_id}
				{#PLIKLI_Subscribe_2_Comments_Unsubscribe#}
			{else}
				{#PLIKLI_Subscribe_2_Comments_Subscribe#}
			{/if}
		</span>
	</span>
 {/if}
{config_load file=subscribe2comments_plikli_lang_conf}