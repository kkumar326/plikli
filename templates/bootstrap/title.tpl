<!-- title.tpl -->
{if preg_match('/index.php$/',$templatelite.server.SCRIPT_NAME)}
	{if !empty($get.category)}
		{if !empty($get.page) && $get.page > 1}
			<title>{$navbar_where.text2} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Breadcrumb_Published_Tab#} | {#PLIKLI_Visual_Name#}</title>
		{else}
			<title>{$navbar_where.text2} | {#PLIKLI_Visual_Breadcrumb_Published_Tab#} | {#PLIKLI_Visual_Name#}</title>
		{/if}
	{elseif !empty($get.page) && $get.page > 1}
		<title>{#PLIKLI_Visual_Breadcrumb_Published_Tab#} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Name#}</title>
	{else}
		<title>{#PLIKLI_Visual_Name#} - {#PLIKLI_Visual_RSS_Description#}</title>
	{/if}
{elseif preg_match('/new.php$/',$templatelite.server.SCRIPT_NAME)}
	{if !empty($get.category)}
		{if !empty($get.page) && $get.page > 1}
			<title>{$navbar_where.text2} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Breadcrumb_Unpublished_Tab#} | {#PLIKLI_Visual_Name#}</title>
		{else}
			<title>{$navbar_where.text2} | {#PLIKLI_Visual_Breadcrumb_Unpublished_Tab#} | {#PLIKLI_Visual_Name#}</title>
		{/if}
	{elseif !empty($get.page) && $get.page > 1}
		<title>{#PLIKLI_Visual_Breadcrumb_Unpublished_Tab#} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Name#}</title>
	{else}
		<title>{#PLIKLI_Visual_Breadcrumb_Unpublished_Tab#} | {#PLIKLI_Visual_Name#}</title>
	{/if}
{elseif preg_match('/submit.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Submit#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/live.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Live#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/live_unpublished.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Live#} {#PLIKLI_Visual_Breadcrumb_Unpublished#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/live_published.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Live#} {#PLIKLI_Visual_Breadcrumb_Published#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/live_comments.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Live#} {#PLIKLI_Visual_Comments#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/editlink.php$/',$templatelite.server.SCRIPT_NAME)}	
	<title>{#PLIKLI_Visual_EditStory_Header#}: {$submit_title} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/advancedsearch.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Search_Advanced#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/rssfeeds.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_RSS_Feeds#} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/search.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#PLIKLI_Visual_Search_SearchResults#} &quot;{if $get.search}{$get.search}{else}{$get.date}{/if}&quot; | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/groups.php$/',$templatelite.server.SCRIPT_NAME)}
	{if !empty($get.page) && $get.page > 1}
		<title>{#PLIKLI_Visual_Groups#} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Name#}</title>
	{else}
		<title>{#PLIKLI_Visual_Groups#} | {#PLIKLI_Visual_Name#}</title>
	{/if}
{elseif preg_match('/editgroup.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{$group_name} | {#PLIKLI_Visual_Name#}</title>
{elseif preg_match('/group_story.php$/',$templatelite.server.SCRIPT_NAME)}
	{if $groupview!='published'}
		{if $groupview eq "new"}
			{assign var='tview' value=#PLIKLI_Visual_Group_New#}
		{elseif $groupview eq "shared"}
			{assign var='tview' value=#PLIKLI_Visual_Group_Shared#}
		{elseif $groupview eq "members"}
			{assign var='tview' value=#PLIKLI_Visual_Group_Member#}
		{/if}

		{if !empty($get.page) && $get.page > 1}
			<title>{$group_name} | {if !empty($get.category)}{$navbar_where.text2} | {/if}{$tview} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Name#}</title>
		{else}
			<title>{$group_name} | {if !empty($get.category)}{$navbar_where.text2} | {/if}{$tview} | {#PLIKLI_Visual_Name#}</title>
		{/if}
	{elseif !empty($get.page) && $get.page > 1}
		<title>{$group_name} | {#PLIKLI_Page_Title#} {$get.page} | {#PLIKLI_Visual_Name#}</title>
	{else}
		<title>{$group_name} - {$group_description} | {#PLIKLI_Visual_Name#}</title>
	{/if}
{elseif $pagename eq "register_complete"}
	<title>{#PLIKLI_Validate_user_email_Title#} | {#PLIKLI_Visual_Name#}</title>
{elseif $pagename eq "404"}
	<title>{#PLIKLI_Visual_404_Error#} | {#PLIKLI_Visual_Name#}</title>
{else}	
	<title>{if !empty($posttitle)}{$posttitle}{/if} {if !empty($pretitle)} | {$pretitle}{/if} {#PLIKLI_Visual_Name#}</title>
{/if}
<!-- /title.tpl -->