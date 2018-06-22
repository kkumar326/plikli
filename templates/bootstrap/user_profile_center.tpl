{************************************
*********** User Profile ************
 This template controls the user friend/follower pages
*************************************}
{include file=$the_template"/user_navigation.tpl"}

<!-- user_profile_center.tpl -->
{***********************************************************************************}
{if $user_view eq 'profile'}
{if $user_login eq $user_logged_in}
	<div class="alert alert-warning expires-warning">{#PLIKLI_Visual_Page_Expires#}</div>
{/if}
	<div id="profile_container" style="position: relative;">
		<div class="row">
			{checkActionsTpl location="tpl_plikli_profile_info_start"}
			{checkActionsTpl location="tpl_plikli_profile_info_middle"}
			<div id="stats" class="col-md-6">
				<table class="table table-bordered table-striped vertical-align">
					<thead class="table_title">
						<tr>
							<th colspan="2">{#PLIKLI_Visual_User_Profile_User_Stats#}</th>
						</tr>
					</thead>
					<tbody>
						{if $user_karma > "0.00"}
							<tr>
								<td><strong>{#PLIKLI_Visual_Rank#}:</strong></td>
								<td>{$user_rank}</td>
							</tr>
							<tr>
								<td><strong>{#PLIKLI_Visual_User_Profile_KarmaPoints#}:</strong></td>
								<td>{$user_karma|number_format:"0"}</td>
							</tr>
						{/if}
						<tr>
							<td><strong>{#PLIKLI_Visual_User_Profile_Joined#}:</strong></td>
							<td width="120px">
								{*	{$user_joined}	*}
								{php}
									$plikli_date = $this->_vars['user_joined'];
									echo date("F d, Y", strtotime($plikli_date));
								{/php}
							</td>	
						</tr>
						<tr>
							<td><strong>{#PLIKLI_Visual_User_Profile_Total_Links#}:</strong></td>
							<td>{$user_total_links}</td>
						</tr>
						<tr>
							<td><strong>{#PLIKLI_Visual_User_Profile_Published_Links#}:</strong></td>
							<td>{$user_published_links}</td>
						</tr>
						<tr>
							<td><strong>{#PLIKLI_Visual_User_Profile_Total_Comments#}:</strong></td>
							<td>{$user_total_comments}</td>
						</tr>
						<tr>
							<td><strong>{#PLIKLI_Visual_User_Profile_Total_Votes#}:</strong></td>
							<td>{$user_total_votes}</td>
						</tr>
						{*
						<tr>
							<td><strong>{#PLIKLI_Visual_User_Profile_Published_Votes#}:</strong></td>
							<td>{$user_published_votes}</td>
						</tr>
						*}
					</tbody>
				</table>
			</div>
			{if $enable_group eq "true"}
				<div id="groups" class="col-md-6">
					<table class="table table-bordered table-striped vertical-align">
						<thead class="table_title">
							<tr>
								<th>{#PLIKLI_Visual_AdminPanel_Group_Name#}</th>
								{if !empty($group_display)}<th style="width:60px;text-align:center;">{#PLIKLI_Visual_Group_Member#}</th>{/if}
							</tr>
						<tbody>
							{if empty($group_display)}
								<tr>
									<td colspan="2">
										{#Plikli_Profile_No_Membership#}
									</td>
								</tr>
							{else}
								{$group_display}
							{/if}
						</tbody>
					</table>
				</div>
			{/if}
			{if $Allow_Friends neq "0"}
				<div id="following" class="col-md-6">
					<table class="table table-bordered table-striped vertical-align">
						<thead class="table_title">
							<tr>
								<th>{#PLIKLI_Visual_User_Profile_Friends#}</th>
								{checkActionsTpl location="tpl_plikli_profile_friend_th"}
								{if check_for_enabled_module('simple_messaging',2.0) && $user_logged_in && $following}
									<th>{#PLIKLI_Visual_User_Profile_Message#}</th>
								{/if}
								{if $user_authenticated eq true}
									<th>{#PLIKLI_Visual_User_Profile_Add_Friend#} / {#PLIKLI_Visual_User_Profile_Remove_Friend#}</th>
								{/if}
							</tr>
						</thead>
						<tbody>
							{if $following}
								{foreach from=$following item=myfriend}
									{php}
										$this->_vars['friend_avatar'] = get_avatar('small', $this->_vars['myfriend']['user_avatar_source'], $this->_vars['myfriend']['user_login'], $this->_vars['myfriend']['user_email']);
										$this->_vars['profileURL'] = getmyurl('user2', $this->_vars['myfriend']['user_login'], 'profile');
										$this->_vars['removeURL'] = getmyurl('user_add_remove', 'removefriend', $this->_vars['myfriend']['user_login']);
										$this->_vars['addURL'] = getmyurl('user_add_remove', 'addfriend', $this->_vars['myfriend']['user_login']);
									{/php}
									<tr>
										<td>
											<a href="{$profileURL}"><img src="{$friend_avatar}" style="text-decoration:none;border:0;"/></a>
											<a href="{$profileURL}">{$myfriend.user_login}</a>
										</td>
										{if $user_authenticated eq true && $myfriend.is_mutual eq 'mutual'}
											<td style="text-align:center">
												<a href="{$my_plikli_base}/module.php?module=simple_messaging&view=compose&return={$templatelite.server.REQUEST_URI|urlencode}&to={$myfriend.user_login}" class="btn btn-default"><i class="fa fa-envelope"></i></a>
											</td>
										{elseif $user_authenticated eq true}
											<td>&nbsp;</td>
										{else}
											
										{/if}
										{checkActionsTpl location="tpl_plikli_profile_friend_td"}										
										{if $user_authenticated eq true && $myfriend.is_mutual eq 'mutual' || $myfriend.is_mutual eq 'following' || $is_friend eq 'mutual'}
											<td>
												<a class="btn btn-danger" href="{$removeURL}">{#PLIKLI_Visual_User_Profile_Remove_Friend#}</a>
											</td>
										{elseif $user_authenticated neq true}
										
										{else}
											<td>
												<a class="btn btn-success" href="{$addURL}">{#PLIKLI_Visual_User_Profile_Add_Friend#}</a>
											</td>											
										{/if}
									</tr>
								{/foreach}
							{else}
								<tr>
									<td colspan="3">
										{$user_username|capitalize} {#PLIKLI_Visual_User_Profile_No_Friends#}
									</td>
								</tr>
							{/if}
						</tbody>
					</table>
				</div>
			{/if}
			{checkActionsTpl location="tpl_plikli_profile_info_end"}
			<div style="clear:both;"> </div>
			{checkActionsTpl location="tpl_plikli_profile_tab_insert"}
		</div>
	</div>
{/if}
{***********************************************************************************}

{if isset($user_page)}
	{$user_page}
	{if $user_page eq ''}
		<div class="jumbotron" style="padding:15px 25px;"><p style="padding:0;margin:0;font-size:1.1em;">{#PLIKLI_User_Profile_No_Content#}</p></div>
	{/if}
{/if}

{if isset($user_pagination) && $user_page neq ''}
	{checkActionsTpl location="tpl_plikli_pagination_start"}
	{$user_pagination}
	{checkActionsTpl location="tpl_plikli_pagination_end"}
{/if}

{checkActionsTpl location="tpl_plikli_profile_end"}
<!--/user_profile_center.tpl -->