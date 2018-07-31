{************************************
*********** Story Content ***********
*************************************}
<!-- link_summary.tpl -->
<!-- Microdata markup added by ChuckRoast -->
{checkActionsTpl location="tpl_link_summary_start"}
<div itemscope itemtype="http://schema.org/Article" class="stories" id="xnews-{$link_shakebox_index}" {* if $link_shakebox_currentuser_reports gt 0} style="opacity:0.5;filter:alpha(opacity = 50)"{/if *}>
{* Redwine: Roles and permissions and Groups fixes *}
	{if $isAdmin eq '1' || $isModerator eq '1' || $user_logged_in eq $link_submitter || $is_gr_Creator eq '1' || $is_gr_Admin eq '1' || $is_gr_Moderator eq '1'}
		<div class="btn-group pull-right admin-links">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
			  <i class="fa fa-cog"></i>
			  <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
{* Redwine: Roles and permissions and Groups fixes *}
				<li><a href="{$story_edit_url}"><i class="fa fa-pencil"></i> {#PLIKLI_Visual_LS_Admin_Edit#}</a></li>
{* Redwine: Roles and permissions and Groups fixes *}
				{if $isAdmin eq '1' || $isModerator eq '1'}
					<li><a href="{$story_admin_url}"><i class="fa fa-arrows-v"></i> {#PLIKLI_Visual_LS_Admin_Status#}</a></li>
{* Redwine: Roles and permissions and Groups fixes *}
					{if $isAdmin eq '1'}
					<li><a href="{$my_base_url}{$my_plikli_base}/admin/admin_users.php?mode=view&user={$link_submitter}"><i class="fa fa-user"></i> {#PLIKLI_Visual_Comment_Manage_User#} {$link_submitter}</a></li>
{* Redwine: Roles and permissions and Groups fixes *}
					{/if}
				{/if}
				{checkActionsTpl location="tpl_link_summary_admin_links"}
{* Redwine: Roles and permissions and Groups fixes *}
				{if $isAdmin eq '1' || $isModerator eq '1' || $is_gr_Creator eq '1' || $is_gr_Admin eq '1' || $is_gr_Moderator eq '1'}
				{if $link_group_id neq 0}<li><a target="story_status" href="javascript://" onclick="show_hide_user_links(document.getElementById('stories_status-{$link_shakebox_index}'));"><i class="fa fa-group"></i> {#PLIKLI_Visual_Group_Story_Status#}</a></li>{/if}
{* Redwine: Roles and permissions and Groups fixes *}
				{/if}
				{if $isAdmin eq '1'}
					<li><a href="{$my_plikli_base}/admin/admin_users.php?mode=killspam&user={$link_submitter}"><i class="fa fa-ban"></i> {#PLIKLI_Visual_View_User_Killspam#}</a></li>
					<li><a href="{$my_plikli_base}/delete.php?link_id={$link_id}" title="This function deletes the story,&#10;all its votes and comments&#10;and removes it from&#10;the groups and saved stories!"><i class="fa fa-trash-o"></i> {#PLIKLI_Visual_AdminPanel_Discard#}</a></li>
				{/if}
			</ul>
			
		</div>		
	{/if}
	{checkActionsTpl location="tpl_plikli_story_start"}
	<div class="story_data">
		{if $Voting_Method eq 2}
			<h4 id="ls_title-{$link_shakebox_index}">
				<ul class='star-rating{$star_class}' id="xvotes-{$link_shakebox_index}">
					<li class="current-rating" style="width: {$link_rating_width}px;" id="xvote-{$link_shakebox_index}"></li>
					<span id="mnmc-{$link_shakebox_index}" {if $link_shakebox_currentuser_votes ne 0}style="display: none;"{/if}>
						<li><a href="javascript:{$link_shakebox_javascript_vote_1star}" class='one-star'>1</a></li>
						<li><a href="javascript:{$link_shakebox_javascript_vote_2star}" class='two-stars'>2</a></li>
						<li><a href="javascript:{$link_shakebox_javascript_vote_3star}" class='three-stars'>3</a></li>
						<li><a href="javascript:{$link_shakebox_javascript_vote_4star}" class='four-stars'>4</a></li>
						<li><a href="javascript:{$link_shakebox_javascript_vote_5star}" class='five-stars'>5</a></li>
					</span>
					<span id="mnmd-{$link_shakebox_index}" {if $link_shakebox_currentuser_votes eq 0}style="display: none;"{/if}>
						<li class='one-star-noh'>1</li>
						<li class='two-stars-noh'>2</li>
						<li class='three-stars-noh'>3</li>
						<li class='four-stars-noh'>4</li>
						<li class='five-stars-noh'>5</li>
					</span>
				</ul>
			</h4>
		{else}
		  {if $story_status eq "published"}
			<div class="votebox votebox-published">
		  {else}
			<div class="votebox votebox-new">
		  {/if}			
				<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="vote">
					{checkActionsTpl location="tpl_plikli_story_votebox_start"}
					<div itemprop="ratingCount" class="votenumber">
						{$link_shakebox_votes}
					</div>
					<div id="xvote-{$link_shakebox_index}" class="votebutton">
						{if $anonymous_vote eq "false" and $user_logged_in eq ""}
							<a data-toggle="modal" href="#loginModal" class="btn {if $link_shakebox_currentuser_votes eq 1}btn-success{else}btn-default{/if}"><i class="fa {if $link_shakebox_currentuser_votes eq 1}fa-white {/if}fa-thumbs-up"></i></a>
							<a data-toggle="modal" href="#loginModal" class="btn {if $link_shakebox_currentuser_reports eq 1}btn-danger{else}btn-default{/if}"><i class="fa {if $link_shakebox_currentuser_reports eq 1}fa-white {/if}fa-thumbs-down"></i></a>
                        
                        {else}
							{if $link_shakebox_currentuser_votes eq 0}
								<!-- Vote For It -->
								<a class="btn btn-default linkVote_{$link_id}" {if $vote_from_this_ip neq 0 and $user_logged_in eq ""} data-toggle="modal" href="#LoginModal" {else} href="javascript:{$link_shakebox_javascript_vote}" {/if} title="{$title_short}" ><i class="fa fa-thumbs-up"></i></a>
							{elseif $link_shakebox_currentuser_votes eq 1}
								<!-- Already Voted -->
								<a class="btn btn-default btn-success linkVote_{$link_id}" href="javascript:{$link_shakebox_javascript_unvote}" title="{$title_short}"><i class="fa fa-white fa-thumbs-up"></i></a>
							{/if}
							{if $link_shakebox_currentuser_reports eq 0}
								<!-- Bury It -->
								<a class="btn btn-default linkVote_{$link_id}" {if $report_from_this_ip neq 0 and $user_logged_in eq ""} data-toggle="modal" href="#LoginModal" {else} href="javascript:{$link_shakebox_javascript_report}" {/if} title="{$title_short}" ><i class="fa fa-thumbs-down"></i></a>
							{elseif $link_shakebox_currentuser_reports eq 1}
								<!-- Already Buried -->
								<a class="btn btn-default btn-danger linkVote_{$link_id}"   href="javascript:{$link_shakebox_javascript_unbury}" title="{$title_short}" }><i class="fa fa-white fa-thumbs-down"></i></a>
							{/if}
						{/if}
						<!-- Votes: {$link_shakebox_currentuser_votes} Buries: {$link_shakebox_currentuser_reports} -->
					</div><!-- /.votebutton -->
				{checkActionsTpl location="tpl_plikli_story_votebox_end"}
				</div><!-- /.vote -->
			</div><!-- /.votebox -->
		{/if}
		<div class="title" id="title-{$link_shakebox_index}">
		<span itemprop="name">
		{* Redwine: the $is_rtl is passed by /libs/link.php and holds the value of 0 or 1; i being for the rtl languages. *}
			<h2 {if $is_rtl eq 1}dir="rtl"{else}dir="ltr"{/if}>
				{checkActionsTpl location="tpl_plikli_story_title_start"}
				{if $use_title_as_link eq true}
					{if $url_short neq "http://" && $url_short neq "://"}
						<a href="{$url}" {if $open_in_new_window eq true} target="_blank" rel="noopener noreferrer"{/if} {if $link_nofollow eq "1"}rel="nofollow"{/if}>{$title_short}</a>
					{else}
						<a href="{$story_url}" {if $open_in_new_window eq true} target="_blank" rel="noopener noreferrer"{/if}>{$title_short}</a>
					{/if}
				 {else}
					{if $pagename eq "story" && $url_short neq "http://" && $url_short neq "://"}
						<a href="{$url}" {if $open_in_new_window eq true} target="_blank" rel="noopener noreferrer"{/if} {if $link_nofollow eq "1"}rel="nofollow"{/if}>{$title_short}</a>
{*Redwine: to only link the title when the story content is not empty*}
					{elseif $pagename neq "story" && $story_content eq ''} 
					  {$title_short}
					{else} 
					  <a href="{$story_url}">{$title_short}</a>
					{/if}
				{/if}
				{checkActionsTpl location="tpl_plikli_story_title_end"}
			</h2>
		</span>	
			<span class="subtext">
				{if $UseAvatars neq "0"}<span id="ls_avatar-{$link_shakebox_index}"><img itemprop="image" src="{$Avatar_ImgSrcs}" width="32px" height="32px" alt="{$link_submitter}" title="{$link_submitter}" /></span>{else}<i class="fa fa-user"></i>{/if}
				<span itemprop="author" itemscope itemtype="http://schema.org/Person">
				<span itemprop="name">
				<a href="{$submitter_profile_url}">{$link_submitter}{if $submitter_rank neq ''} (#{$submitter_rank}){/if}</a> 
				</span></span>
				<i class="fa fa-clock-o"></i>
				<span itemprop="datePublished">{$link_submit_timeago} {#PLIKLI_Visual_Comment_Ago#}</span>
				
				<i class="fa fa-folder"></i> 
				<a href="{$category_url}">{$link_category}</a>
				{if $link_additional_cats}
					{foreach from=$link_additional_cats item=catname key=caturl}
						<a href="{$caturl}">{$catname}</a>
					{/foreach}
				{/if}
				
				{if $enable_tags}
					{if $tags}
						<i class="fa fa-tag"></i>
						{section name=thistag loop=$tag_array}
							{if $tag_array[thistag] neq ''}
								<a href="{$tags_url_array[thistag]}">{$tag_array[thistag]}</a>
							{/if}
						{/section}
					{/if}
				{/if}
				
				{if $url_short neq "http://" && $url_short neq "://"}
					<i class="fa fa-globe"></i>
					<a href="{$url}" {if $open_in_new_window eq true} target="_blank" rel="noopener noreferrer"{/if}  {if $link_nofollow eq "1"}rel="nofollow"{/if}>{$url_short}</a>
{* Redwine: this is only applied when a URL is not needed to submit a story. It loads the Editorial from the config table*}
				{else}
					<i class="fa fa-globe"></i>
					{$No_URL_Name}
				{/if}
				{checkActionsTpl location="tpl_plikli_story_tools_start"}
				&nbsp;
				<span id="ls_comments_url-{$link_shakebox_index}">
					{if $story_comment_count eq 0}
						<i class="fa fa-comment"></i> <span id="linksummaryDiscuss"><a href="{$story_url}#discuss" class="comments">{#PLIKLI_MiscWords_Discuss#}</a>&nbsp;</span>
					{/if}
					{if $story_comment_count eq 1}
						<i class="fa fa-comment"></i> <span id="linksummaryHasComment"><a href="{$story_url}#comments" class="comments2">{$story_comment_count} {#PLIKLI_MiscWords_Comment#}</a>&nbsp;</span>
					{/if}
					{if $story_comment_count gt 1}
						<i class="fa fa-comment"></i> <span id="linksummaryHasComment"><a href="{$story_url}#comments" class="comments2">{$story_comment_count} {#PLIKLI_MiscWords_Comments#}</a>&nbsp;</span>
					{/if}
				</span> 
				{if $user_logged_in}  
					<iframe height="0px;" width="0px;" frameborder="0" name="add_stories"></iframe>
					{if $link_mine eq 0}
						<i class="fa fa-star"></i> <span id="linksummarySaveLink">
						<a id="add" linkid="{$link_id}" title="{$title_short}" class="favorite" >{#PLIKLI_MiscWords_Save_Links_Save#}</a>
					{else}
						<i class="fa fa-star-o"></i> <span id="linksummaryRemoveLink">
						<a id="remove" linkid="{$link_id}" title="{$title_short}" class="favorite" >{#PLIKLI_MiscWords_Save_Links_Remove#}</a>
					{/if}
					</span>&nbsp;
					<span id="stories-{$link_shakebox_index}" class="label label-success" style="display:none;line-height:1em;">{#PLIKLI_MiscWords_Save_Links_Success#}</span>
				{/if}
				{if $link_shakebox_currentuser_votes eq 1 && $link_shakebox_currentuser_reports eq 1}
					<i class="fa fa-minus-sign"></i> 
					<span id="linksummaryUnvote"><a href="javascript:{$link_shakebox_javascript_unvote}">{#PLIKLI_Visual_Unvote_For_It#}</a></span>&nbsp; 
				{/if}
				{if $enable_group eq "true" && $user_logged_in}
					<span class="group-tool-wrapper">
						{if $get_group_membered neq ''}
						<i class="fa fa-group"></i> 
						<span class="group_sharing"><a href="javascript://" onclick="{if $get_group_membered}var replydisplay=document.getElementById('group_share-{$link_shakebox_index}').style.display ? '' : 'none';document.getElementById('group_share-{$link_shakebox_index}').style.display = replydisplay;{else}alert('{#PLIKLI_Visual_No_Groups#}');{/if}">{#PLIKLI_Visual_Group_Share#}</a></span>
						<span id = "group_share-{$link_shakebox_index}" style="display:none;">
							<div class="group-share-popup">{$get_group_membered}</div>
						</span>
					</span>
				{/if}
					{if $get_group_shared_membered neq ''}
					{if $is_link_sharer eq 1 || $is_gr_Admin eq 1}
					<span class="group-tool-wrapper">
					<i class="fa fa-group"></i>
					<span class="group_sharing"><a class="group-unshare" href="javascript://" onclick="{if $get_group_shared_membered}var replydisplay=document.getElementById('group_unshare-{$link_shakebox_index}').style.display ? '' : 'none';document.getElementById('group_unshare-{$link_shakebox_index}').style.display = replydisplay;{else}alert('{#PLIKLI_Visual_No_Groups#}');{/if}">Group Unshare</a></span>
					<span id = "group_unshare-{$link_shakebox_index}" style="display:none;">
						<div class="group-unshare-popup">{$get_group_shared_membered}</div>
					</span>
					{/if}
					</span>
					{/if}
				{/if}
				{checkActionsTpl location="tpl_plikli_story_tools_end"}
				<iframe height="0" width="0" frameborder="0" name="story_status" class="invisible"></iframe>
				<span id="stories_status-{$link_shakebox_index}" style="display:none;">
{*Redwine: Roles and permissions and Groups fixes. I created a script in /templates/bootstrap/functions/common.tpl to change the display to the corresponding status tab when a group story status is changed. It works well, however, the page is cached and needs refreshing to load the new version. window.location.reload(true) is not working. function switch_group_links_tabs(status)*}
					<a target="story_status" href="{$group_story_links_publish}" onclick="switch_group_links_tabs('published')">{#PLIKLI_Visual_AdminPanel_Published#}</a>
					<a target="story_status" href="{$group_story_links_new}" onclick="switch_group_links_tabs('new')">{#PLIKLI_Visual_AdminPanel_New#}</a>
					<a target="story_status" href="{$group_story_links_discard}" onclick="switch_group_links_tabs('discard')">{#PLIKLI_Visual_AdminPanel_Discard#}</a> {$groupview_published}
				</span>
				<span id="story_status_success-{$link_shakebox_index}" class="label label-success" style="display:none;">
					{#PLIKLI_MiscWords_Save_Links_Success#}
				</span>
			</span>
		</div><!-- /.title -->
	</div> <!-- /.story_data -->
	<span itemprop="articleBody">
	<div class="storycontent">
		{checkActionsTpl location="tpl_link_summary_pre_story_content"}
		{if $pagename eq "story"}{checkActionsTpl location="tpl_plikli_story_body_start_full"}{else}{checkActionsTpl location="tpl_plikli_story_body_start"}{/if}
		{if $viewtype neq "short"}
			{* Redwine: the $is_rtl is passed by /libs/link.php and holds the value of 0 or 1; i being for the rtl languages. *}
			<div class="news-body-text" id="ls_contents-{$link_shakebox_index}" {if $is_rtl eq 1}dir="rtl"{else}dir="ltr"{/if}>
				{if $show_content neq 'FALSE'}
					{if $pagename eq "story"}
						{* The nl2br modifier will convert line breaks to <br> tags. http://www.smarty.net/docsv2/en/language.modifier.nl2br.tpl*}
						{$story_content|nl2br}
					{else}
						{* The truncate modifier will cut off content after X characters. http://www.smarty.net/docsv2/en/language.modifier.truncate *}
						{$story_content|nl2br}
					{/if}
				{/if}
				 
				{if $Enable_Extra_Field_1 eq 1}{if $link_field1 neq ""}<br/><b>{$Field_1_Title}:</b> {$link_field1}{/if}{/if}
				{if $Enable_Extra_Field_2 eq 1}{if $link_field2 neq ""}<br/><b>{$Field_2_Title}:</b> {$link_field2}{/if}{/if}
				{if $Enable_Extra_Field_3 eq 1}{if $link_field3 neq ""}<br/><b>{$Field_3_Title}:</b> {$link_field3}{/if}{/if}
				{if $Enable_Extra_Field_4 eq 1}{if $link_field4 neq ""}<br/><b>{$Field_4_Title}:</b> {$link_field4}{/if}{/if}
				{if $Enable_Extra_Field_5 eq 1}{if $link_field5 neq ""}<br/><b>{$Field_5_Title}:</b> {$link_field5}{/if}{/if}
				{if $Enable_Extra_Field_6 eq 1}{if $link_field6 neq ""}<br/><b>{$Field_6_Title}:</b> {$link_field6}{/if}{/if}
				{if $Enable_Extra_Field_7 eq 1}{if $link_field7 neq ""}<br/><b>{$Field_7_Title}:</b> {$link_field7}{/if}{/if}
				{if $Enable_Extra_Field_8 eq 1}{if $link_field8 neq ""}<br/><b>{$Field_8_Title}:</b> {$link_field8}{/if}{/if}
				{if $Enable_Extra_Field_9 eq 1}{if $link_field9 neq ""}<br/><b>{$Field_9_Title}:</b> {$link_field9}{/if}{/if}
				{if $Enable_Extra_Field_10 eq 1}{if $link_field10 neq ""}<br/><b>{$Field_10_Title}:</b> {$link_field10}{/if}{/if}
				{if $Enable_Extra_Field_11 eq 1}{if $link_field11 neq ""}<br/><b>{$Field_11_Title}:</b> {$link_field11}{/if}{/if}
				{if $Enable_Extra_Field_12 eq 1}{if $link_field12 neq ""}<br/><b>{$Field_12_Title}:</b> {$link_field12}{/if}{/if}
				{if $Enable_Extra_Field_13 eq 1}{if $link_field13 neq ""}<br/><b>{$Field_13_Title}:</b> {$link_field13}{/if}{/if}
				{if $Enable_Extra_Field_14 eq 1}{if $link_field14 neq ""}<br/><b>{$Field_14_Title}:</b> {$link_field14}{/if}{/if}
				{if $Enable_Extra_Field_15 eq 1}{if $link_field15 neq ""}<br/><b>{$Field_15_Title}:</b> {$link_field15}{/if}{/if}
				
				{* 
				  {if $pagename neq "story" && $pagename neq "submit"} <div class="floatright"><a class="btn btn-default" href="{$story_url}">{#PLIKLI_Visual_Read_More#}</a></div>{/if}
				*}
				<div class="clearboth"></div> 
			</div>
			{if $pagename eq "story"}{checkActionsTpl location="tpl_plikli_story_body_end_full"}{else}{checkActionsTpl location="tpl_plikli_story_body_end"}{/if}
		{/if}
	</div><!-- /.storycontent -->
	</span>
	{checkActionsTpl location="tpl_link_summary_end"}
</div><!-- /.stories -->
{checkActionsTpl location="tpl_plikli_story_end"}
<!--/link_summary.tpl -->