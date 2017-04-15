{************************************
****** Submit Step 2 Template *******
*************************************}
<!-- submit_step_2_center.tpl -->
<div class="submit_page">
	<legend>{#KLIQQI_Visual_Submit2_Details#}</legend>
	{checkActionsTpl location="tpl_kliqqi_submit_step2_start"}
	<form class="form-horizontal" action="{$URL_submit}" method="post" name="thisform" id="thisform" enctype="multipart/form-data" onsubmit="return checkForm()">
		<div class="col-md-6 submit_step_2_left">
			<div class="control-group">
				<label for="input01" class="control-label">{#KLIQQI_Visual_Submit2_Title#}</label>
				<div class="controls">
					<input type="text" id="title" class="form-control title col-md-4" tabindex="1" name="title" value="{if $submit_title}{$submit_title}{else}{$submit_url_title}{/if}" size="54" maxlength="{$maxTitleLength}" />
					<p class="help-inline">{#KLIQQI_Visual_Submit2_TitleInstruct#}</p>
				</div>
			</div>
			<div class="control-group">
				<label for="input01" class="control-label">{#KLIQQI_Visual_Submit2_Category#}</label>
				<div class="controls select-category{if $Multiple_Categories} multi-select-category{/if}">
					{if $Multiple_Categories}
						{section name=thecat loop=$submit_cat_array}
							{$submit_cat_array[thecat].spacercount|repeat_count:'&nbsp;&nbsp;&nbsp;&nbsp;'}
								{*Redwine: I added the $submit_cat_array[thecat].name as the title attribute to the input checkbox so that we are able to use it to get the category name to be added in the preview section on the submit step 2*}
								 <input type="checkbox" class="form-control" name="category[]" value="{$submit_cat_array[thecat].auto_id}" title="{$submit_cat_array[thecat].name}" {if $submit_cat_array[thecat].auto_id == $submit_category  || in_array($submit_cat_array[thecat].auto_id,$submit_additional_cats)}checked{/if}> {$submit_cat_array[thecat].name}<br />							
						{/section}
						<br />
					{else}
						<select class="form-control category" tabindex="2" name="category">
							<option value="">{#KLIQQI_Visual_Submit2_CatInstructSelect#}</option>
							{section name=thecat loop=$submit_cat_array}
								<option value = "{$submit_cat_array[thecat].auto_id}" {if $submit_cat_array[thecat].auto_id == $submit_category  || in_array($cat_array[thecat].auto_id,$submit_additional_cats)}selected{/if}>
									{if $submit_cat_array[thecat].spacercount lt $submit_lastspacer}{$submit_cat_array[thecat].spacerdiff|repeat_count:''}{/if}
									{if $submit_cat_array[thecat].spacercount gt $submit_lastspacer}{/if}
									{$submit_cat_array[thecat].spacercount|repeat_count:'&nbsp;&nbsp;&nbsp;'}
									{$submit_cat_array[thecat].name}
									&nbsp;&nbsp;&nbsp;
									{assign var=submit_lastspacer value=$submit_cat_array[thecat].spacercount}
								</option>
							{/section}
						</select>
					{/if}
					<p class="help-inline">{#KLIQQI_Visual_Submit2_CatInstruct#}</p>
				</div>
			</div>
			{if $enable_group && $output neq ''}
				<div class="control-group">
					<label for="input01" class="control-label">{#KLIQQI_Visual_Group_Submit_story#}</label>
					<div class="controls">
						{$output}
					</div>
				</div>
			{/if}
			{checkActionsTpl location="tpl_header_admin_main_comment_subscription"}
			{*{checkActionsTpl location="tpl_timestamp_stories"}*}
			{if $enable_tags}
				<div class="control-group">
					<label for="input01" class="control-label">{#KLIQQI_Visual_Submit2_Tags#}</label>
					<div class="controls">
						<input tabindex="10" type="text" id="tags" class="form-control tags col-md-4" name="tags" data-mode="multiple" value="{$tags_words}" maxlength="{$maxTagsLength}" data-source="[&quot;.net&quot;,&quot;ajax&quot;,&quot;arts&quot;,&quot;apple&quot;,&quot;blog&quot;,&quot;books&quot;,&quot;business&quot;,&quot;celebrity&quot;,&quot;clothing&quot;,&quot;cms&quot;,&quot;coldfusion&quot;,&quot;computer&quot;,&quot;console&quot;,&quot;contest&quot;,&quot;css&quot;,&quot;deal&quot;,&quot;decorating&quot;,&quot;design&quot;,&quot;DIY&quot;,&quot;download&quot;,&quot;education&quot;,&quot;election&quot;,&quot;entertainment&quot;,&quot;enviroment&quot;,&quot;firefox&quot;,&quot;flash&quot;,&quot;food&quot;,&quot;forums&quot;,&quot;free software&quot;,&quot;funny&quot;,&quot;gadget&quot;,&quot;gallery&quot;,&quot;games&quot;,&quot;gifts&quot;,&quot;Google&quot;,&quot;hacking&quot;,&quot;handheld&quot;,&quot;hardware&quot;,&quot;health&quot;,&quot;howto&quot;,&quot;html&quot;,&quot;humor&quot;,&quot;images&quot;,&quot;international&quot;,&quot;internet&quot;,&quot;javascript&quot;,&quot;jobs&quot;,&quot;lifestyle&quot;,&quot;linux&quot;,&quot;mac&quot;,&quot;Microsoft&quot;,&quot;mobile&quot;,&quot;mods&quot;,&quot;money&quot;,&quot;movies&quot;,&quot;music&quot;,&quot;mysql&quot;,&quot;Nintendo&quot;,&quot;open source&quot;,&quot;pc&quot;,&quot;php&quot;,&quot;photoshop&quot;,&quot;Playstation&quot;,&quot;podcast&quot;,&quot;politics&quot;,&quot;portfolio&quot;,&quot;programming&quot;,&quot;rumor&quot;,&quot;science&quot;,&quot;security&quot;,&quot;SEO&quot;,&quot;shopping&quot;,&quot;software&quot;,&quot;space&quot;,&quot;sports&quot;,&quot;technology&quot;,&quot;television&quot;,&quot;templates&quot;,&quot;themes&quot;,&quot;tools&quot;,&quot;toys&quot;,&quot;travel&quot;,&quot;tutorial&quot;,&quot;typography&quot;,&quot;usability&quot;,&quot;video&quot;,&quot;video game&quot;,&quot;web&quot;,&quot;webdesign&quot;,&quot;Wii&quot;,&quot;work&quot;,&quot;Xbox&quot;,&quot;XHTML&quot;,&quot;Yahoo&quot;]" data-items="4" data-delimiter="," data-provide="typeahead">
						<p class="help-inline">{#KLIQQI_Visual_Submit2_Tags_Example#} {#KLIQQI_Visual_Submit2_Tags_Inst2#}</p>
					</div>
				</div>
			{/if}
			{checkActionsTpl location="tpl_kliqqi_submit_step2_middle"}
			<div class="control-group">
				<label for="input01" class="control-label">{#KLIQQI_Visual_Submit2_Description#}</label>
				<div class="controls">
{*Redwine: to display before the the content textarea what are the html tags allowed if set in Admin Panel; otherwise, it displays NO HTML*}
					{ if $Story_Content_Tags_To_Allow eq ""}
						<p class="help-inline"><strong>{#KLIQQI_Visual_Submit2_No_HTMLTagsAllowed#} </strong>{*#KLIQQI_Visual_Submit2_HTMLTagsAllowed#*}</p>
					{else}
							<p class="help-inline"><strong>{#KLIQQI_Visual_Submit2_HTMLTagsAllowed#}:</strong> {$Story_Content_Tags_To_Allow}</p>
					{/if }
					<textarea name="bodytext" tabindex="15" rows="6" class="form-control bodytext col-md-4" id="bodytext" maxlength="{$maxStoryLength}" WRAP="SOFT">{if $submit_url_description}{$submit_url_description}{/if}{$submit_content}</textarea>
					<br />
					<p class="help-inline">{#KLIQQI_Visual_Submit2_DescInstruct#}</p>
				</div>
			</div>
			{if $SubmitSummary_Allow_Edit eq 1}
				<div class="control-group">
					<label for="input01" class="control-label">{#KLIQQI_Visual_Submit2_Summary#}</label>
					<div class="controls">
{*Redwine: to display before the the edit content textarea what are the html tags allowed if set in Admin Panel; otherwise, it displays NO HTML*}
						{ if $Story_Content_Tags_To_Allow eq ""}
							<p class="help-inline"><strong>{#KLIQQI_Visual_Submit2_No_HTMLTagsAllowed#} </strong>{*#KLIQQI_Visual_Submit2_HTMLTagsAllowed#*}</p>
						{else}
							<p class="help-inline"><strong>{#KLIQQI_Visual_Submit2_HTMLTagsAllowed#}:</strong> {$Story_Content_Tags_To_Allow}</p>
						{/if }
						<textarea name="summarytext" rows="5" maxlength="{$maxSummaryLength}" id="summarytext" class="col-md-4" WRAP="SOFT">{$submit_summary}</textarea>
						<br />
						<p class="help-inline">
							{#KLIQQI_Visual_Submit2_SummaryInstruct#}
							{#KLIQQI_Visual_Submit2_SummaryLimit#}
							{$StorySummary_ContentTruncate}
							{#KLIQQI_Visual_Submit2_SummaryLimitCharacters#}
						</p>
					</div>
				</div>
			{/if}
			{*
				{if $Submit_Show_URL_Input eq 1}
					<div class="control-group">
						<label for="input01" class="control-label">{#KLIQQI_Visual_Submit2_Trackback#}</label>
						<div class="controls">
							<input type="text" name="trackback" tabindex="17" id="trackback" class="form-control col-md-5" value="{$submit_trackback}" size="54" />
						</div>
					</div>
				{/if}
			*}
			{checkActionsTpl location="submit_step_2_pre_extrafields"}
			{include file=$tpl_extra_fields.".tpl"}
			{if isset($register_step_1_extra)}
				{$register_step_1_extra}
			{/if}
			<div class="form-actions">            
				<input type="hidden" name="url" id="url" value="{$submit_url}" />
				<input type="hidden" name="phase" value="2" />
				<input type="hidden" name="randkey" value="{$randkey}" />
				<input type="hidden" name="id" value="{$submit_id}" />
				{* Redwine: I added ; return false; to the onlclick event to fix the bug in Firefox that was taking the user back to an error message...when clicking the cancel the submission button. https://github.com/redwinefireplace/kliqqi-cms/commit/bcc2b717bd8196c32126380706bf2f5ae4ff9b90 *}
				<button class="btn btn-default" tabindex="30" onclick="history.go(-1); return false;">Cancel</button>
				{checkActionsTpl location="tpl_kliqqi_submit_step2_end"}
				<input class="btn btn-primary" tabindex="31" type="submit" value="{#KLIQQI_Visual_Submit2_Continue#}" />
			</div>
		</div>
		{* START STORY PREVIEW *}
		<div class="col-md-6 submit_step_2_right" id="dockcontent">
			{checkActionsTpl location="tpl_kliqqi_submit_preview_start"}
				{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_shakebox_index" *}
			<div class="stories" id="xnews-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}">
				{checkActionsTpl location="tpl_kliqqi_story_start"}
				<div class="headline">
					{if $Voting_Method eq 2}
						<h4 id="ls_title-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}">
							<ul class='star-rating{$star_class}' id="xvotes-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}">
								<li class="current-rating" style="width: {$link_rating_width}px;" id="xvote-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}"></li>
								<span id="mnmc-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}" {if $link_shakebox_currentuser_votes ne 0}style="display: none;"{/if}>
									<li><a href="javascript:{$link_shakebox_javascript_vote_1star}" class='one-star'>1</a></li>
									<li><a href="javascript:{$link_shakebox_javascript_vote_2star}" class='two-stars'>2</a></li>
									<li><a href="javascript:{$link_shakebox_javascript_vote_3star}" class='three-stars'>3</a></li>
									<li><a href="javascript:{$link_shakebox_javascript_vote_4star}" class='four-stars'>4</a></li>
									<li><a href="javascript:{$link_shakebox_javascript_vote_5star}" class='five-stars'>5</a></li>
								</span>
								<span id="mnmd-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}" {if $link_shakebox_currentuser_votes eq 0}style="display: none;"{/if}>
									<li class='one-star-noh'>1</li>
									<li class='two-stars-noh'>2</li>
									<li class='three-stars-noh'>3</li>
									<li class='four-stars-noh'>4</li>
									<li class='five-stars-noh'>5</li>
								</span>
							</ul>
						</h4>
					{else}
						<div class="votebox votebox-published">
							<div class="vote">
								{checkActionsTpl location="tpl_kliqqi_story_votebox_start"}
								<div class="votenumber">1</div>
								{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_shakebox_index" *}
								<div id="xvote-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}" class="votebutton">
									<!-- Already Voted -->
									<a class="btn btn-success"><i class="fa fa-white fa-thumbs-up"></i></a>
									<!-- Bury It -->
									{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_id *}
									<a class="btn btn-default linkVote_{if !empty($link_id)}{$link_id}{/if}"><i class="fa fa-thumbs-down"></i></a>
								</div><!-- /.votebutton -->
								{checkActionsTpl location="tpl_kliqqi_story_votebox_end"}
							</div><!-- /.vote -->
						</div><!-- /.votebox -->
					{/if}
					{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_shakebox_index" *}
					<div class="title" id="title-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}">
						<h2>
							{checkActionsTpl location="tpl_kliqqi_story_title_start"}
							<span class="story_title">&nbsp;</span>
							{checkActionsTpl location="tpl_kliqqi_story_title_end"}
						</h2>
						<span class="subtext">
							{php}
								global $main_smarty, $current_user;
								if ($current_user->user_id > 0 && $current_user->authenticated) {
									$login=$current_user->user_login;
								}
								// Read the users information from the database
								$user=new User();
								$user->username = $login;
								// Assign smarty variables to use in the template.
								$main_smarty->assign('link_submitter', $user->username);
								$main_smarty->assign('submitter_profile_url', getmyurl('user2', $login, 'profile'));
								$main_smarty->assign('Avatar_ImgSrc', get_avatar('small', $user->avatar_source, $user->username));
							{/php}
							{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_shakebox_index" *}
							{if $UseAvatars neq "0"}<span id="ls_avatar-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}"><img src="{$Avatar_ImgSrc}" width="16px" height="16px" alt="" title="Avatar" /></span>{else}<i class="fa fa-user"></i>{/if}
							<a href="{$submitter_profile_url}">{$link_submitter}</a> 
							<i class="fa fa-time"></i>
							Being Submitted Now
							{if $url_short neq "http://" && $url_short neq "://"}
								<i class="fa fa-globe"></i>
								{* Redwine: temporary solution to eliminate the warnings "Undefined index: story_status" *}
								<a href="{$url}" {if $open_in_new_window eq true} target="_blank"{/if}  {if !empty($story_status) && $story_status neq "published"}rel="nofollow"{/if}>{$url_short}</a>
							{/if}
						</span>
					</div><!-- /.title -->
				</div> <!-- /.headline -->
				<div class="storycontent">
					{checkActionsTpl location="tpl_link_summary_pre_story_content"}
					{if $pagename eq "story"}{checkActionsTpl location="tpl_kliqqi_story_body_start_full"}{else}{checkActionsTpl location="tpl_kliqqi_story_body_start"}{/if}
					{*if $viewtype neq "short"*}
						<span class="news-body-text" id="ls_contents-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}">
							<span class="bodytext">&nbsp;</span>
							<div class="clearboth"></div> 
						</span>
						{checkActionsTpl location="tpl_kliqqi_story_body_end"}
					{*/if*}
				</div><!-- /.storycontent -->
				<div class="storyfooter">
					<div class="story-tools-left">
						{checkActionsTpl location="tpl_kliqqi_story_tools_start"}
						{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_shakebox_index" *}
						<span id="ls_comments_url-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}">
							<i class="fa fa-comment"></i> <span id="linksummaryDiscuss"><a class="comments">{#KLIQQI_MiscWords_Discuss#}</a>&nbsp;</span>
						</span> 
						<i class="fa fa-star"></i> <span id="linksummarySaveLink"><a id="add" class="favorite" >{#KLIQQI_MiscWords_Save_Links_Save#}</a></span>&nbsp;
						{* Redwine: temporary solution to eliminate the warnings "Undefined index: link_shakebox_index" *}
						<span id="stories-{if !empty($link_shakebox_index)}{$link_shakebox_index}{/if}" class="label label-success" style="display:none;line-height:1em;">{#KLIQQI_MiscWords_Save_Links_Success#}</span>
						{if $enable_group eq "true" && $user_logged_in}
							<i class="fa fa-group"></i> <span class="group_sharing"><a>{#KLIQQI_Visual_Group_Share#}</a></span>
						{/if}
						{checkActionsTpl location="tpl_kliqqi_story_tools_end"}
					</div>
					<div class="story-tools-right">
						{*Redwine: I changed the class below from category to prev_category because when the javascript runs, it was applying the result to the class category thus affecting the select option from which we select the category and render it empty execpt from the selected category and the impossibility to change the category of we choose to!*}
						<i class="fa fa-folder"></i> <a><span class="prev_category">&nbsp;</span></a>
						{if $enable_tags}
							<i class="fa fa-tag"></i> <a><span class="tags"></span></a>
						{/if}	
					 </div><!-- /.story-tools-right -->
					 <div style="clear:both;"></div>
				</div><!-- /.storyfooter -->
				{checkActionsTpl location="tpl_link_summary_end"}
			</div><!-- /.stories -->
			{checkActionsTpl location="tpl_kliqqi_story_end"}
		</div>
		{* END STORY PREVIEW *}
	</form>
	{checkActionsTpl location="tpl_kliqqi_submit_step2_after_form"}
	{if $og_twitter_image neq ""}
		<div class="display_img_to_upload">{#KLIQQI_Visual_Display_IMG_To_Upload#}</div>
		<img src="{$og_twitter_image}" style="max-width:550px"/>
	{/if}
</div>
{literal}
	<script type="text/javascript">
		var dock0=new dockit("dockcontent", 0);
	</script>
	
	
	<script type="text/javascript">
	// Redwine: Added the below to prevent pressing the enter key which was taking the user back to the url submit page.
	$( document ).ready(function() {
		$('.title').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) { 
			e.preventDefault();
			return false;
		  }
		});
		$('.tags').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) { 
			e.preventDefault();
			return false;
		  }
		});
	});
	</script>
{/literal}
<!--/submit_step_2_center.tpl -->