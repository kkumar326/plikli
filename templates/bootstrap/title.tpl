<!-- title.tpl -->
{if preg_match('/index.php$/',$templatelite.server.SCRIPT_NAME)}
	{if !empty($get.category)}
		{if !empty($get.page) && $get.page > 1}
			<title>{$navbar_where.text2} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Breadcrumb_Published_Tab#} | {#KLIQQI_Visual_Name#}</title>
		{else}
			<title>{$navbar_where.text2} | {#KLIQQI_Visual_Breadcrumb_Published_Tab#} | {#KLIQQI_Visual_Name#}</title>
		{/if}
	{elseif !empty($get.page) && $get.page > 1}
		<title>{#KLIQQI_Visual_Breadcrumb_Published_Tab#} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Name#}</title>
	{else}
		<title>{#KLIQQI_Visual_Name#} - {#KLIQQI_Visual_RSS_Description#}</title>
	{/if}
{elseif preg_match('/new.php$/',$templatelite.server.SCRIPT_NAME)}
	{if !empty($get.category)}
		{if !empty($get.page) && $get.page > 1}
			<title>{$navbar_where.text2} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Breadcrumb_Unpublished_Tab#} | {#KLIQQI_Visual_Name#}</title>
		{else}
			<title>{$navbar_where.text2} | {#KLIQQI_Visual_Breadcrumb_Unpublished_Tab#} | {#KLIQQI_Visual_Name#}</title>
		{/if}
	{elseif !empty($get.page) && $get.page > 1}
		<title>{#KLIQQI_Visual_Breadcrumb_Unpublished_Tab#} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Name#}</title>
	{else}
		<title>{#KLIQQI_Visual_Breadcrumb_Unpublished_Tab#} | {#KLIQQI_Visual_Name#}</title>
	{/if}
{elseif preg_match('/submit.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Submit#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/live.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Live#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/live_unpublished.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Live#} {#KLIQQI_Visual_Breadcrumb_Unpublished#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/live_published.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Live#} {#KLIQQI_Visual_Breadcrumb_Published#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/live_comments.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Live#} {#KLIQQI_Visual_Comments#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/editlink.php$/',$templatelite.server.SCRIPT_NAME)}	
	<title>{#KLIQQI_Visual_EditStory_Header#}: {$submit_title} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/advancedsearch.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Search_Advanced#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/rssfeeds.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_RSS_Feeds#} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/search.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{#KLIQQI_Visual_Search_SearchResults#} &quot;{if $get.search}{$get.search}{else}{$get.date}{/if}&quot; | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/groups.php$/',$templatelite.server.SCRIPT_NAME)}
	{if !empty($get.page) && $get.page > 1}
		<title>{#KLIQQI_Visual_Groups#} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Name#}</title>
	{else}
		<title>{#KLIQQI_Visual_Groups#} | {#KLIQQI_Visual_Name#}</title>
	{/if}
{elseif preg_match('/editgroup.php$/',$templatelite.server.SCRIPT_NAME)}
	<title>{$group_name} | {#KLIQQI_Visual_Name#}</title>
{elseif preg_match('/group_story.php$/',$templatelite.server.SCRIPT_NAME)}
	{if $groupview!='published'}
		{if $groupview eq "new"}
			{assign var='tview' value=#KLIQQI_Visual_Group_New#}
		{elseif $groupview eq "shared"}
			{assign var='tview' value=#KLIQQI_Visual_Group_Shared#}
		{elseif $groupview eq "members"}
			{assign var='tview' value=#KLIQQI_Visual_Group_Member#}
		{/if}

		{if !empty($get.page) && $get.page > 1}
			<title>{$group_name} | {if !empty($get.category)}{$navbar_where.text2} | {/if}{$tview} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Name#}</title>
		{else}
			<title>{$group_name} | {if !empty($get.category)}{$navbar_where.text2} | {/if}{$tview} | {#KLIQQI_Visual_Name#}</title>
		{/if}
	{elseif !empty($get.page) && $get.page > 1}
		<title>{$group_name} | {#KLIQQI_Page_Title#} {$get.page} | {#KLIQQI_Visual_Name#}</title>
	{else}
		<title>{$group_name} - {$group_description} | {#KLIQQI_Visual_Name#}</title>
	{/if}
{elseif $pagename eq "register_complete"}
	<title>{#KLIQQI_Validate_user_email_Title#} | {#KLIQQI_Visual_Name#}</title>
{elseif $pagename eq "404"}
	<title>{#KLIQQI_Visual_404_Error#} | {#KLIQQI_Visual_Name#}</title>
{else}	
	<title>{if !empty($posttitle)}{$posttitle}{/if} {if !empty($pretitle)} | {$pretitle}{/if} {#KLIQQI_Visual_Name#}</title>
{/if}
<!-- /title.tpl -->