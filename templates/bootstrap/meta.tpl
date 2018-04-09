{************************************
***** Meta Properties Template ******
*************************************}
<!-- meta.tpl -->
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
{if $meta_description neq ""}
	<meta name="description" content="{$meta_description|truncate:"300"}" />
{elseif $pagename eq "search"}
	<meta name="description" content="{#PLIKLI_Visual_Search_SearchResults#} {$templatelite.get.search|sanitize:2|stripslashes}" />
{else}
	{*Redwine: this condition applies when on the main page. The PLIKLI_Visual_What_Is_Plikli_Text contains html tag and htmelentities only converts the tags. We need to remove the tags from the description, so we apply sanitize:1 instead,*}
	<meta name="description" content="{#PLIKLI_Visual_What_Is_Plikli_Text#|sanitize:1}" />
{/if}
{if $meta_keywords neq ""}
	<meta name="keywords" content="{$meta_keywords}" />
{elseif $pagename eq "search"}
	<meta name="keywords" content="{$templatelite.get.search|sanitize:2|stripslashes}" />
{else}
	<meta name="keywords" content="{#PLIKLI_Visual_Meta_Keywords#}" />
{/if}
<meta name="Language" content="{#PLIKLI_Visual_Meta_Language#}" />
<meta name="Robots" content="All" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="generator" content="Plikli" />

<!-- Open Graph Protocol  & Twitter Cards -->
<meta property="og:title" content="{$posttitle}" />
	{*Redwine: we might encounter a situation where there is no description content, like in the case of using the rss_importer module. So we provision for this case by assigning the PLIKLI_Visual_What_Is_Plikli_Text as description*}
<meta property="og:description" content="{if $meta_description neq ""}{$meta_description|truncate:300}{else}{#PLIKLI_Visual_What_Is_Plikli_Text#|sanitize:1}{/if}" />
{if $pagename eq "story"}
	<meta property="og:url" content="{$my_base_url}{$story_url}" />
	<meta name="twitter:url" content="{$my_base_url}{$story_url}" />
{else}
	<meta property="og:url" content="{$my_base_url}{$navbar_where.link1}" />
	<meta name="twitter:url" content="{$my_base_url}{$navbar_where.link1}" />
{/if}
	{******* One method *******}
<meta property="og:image" content="{php} if ($_SESSION['uploaded_image'] != '') {echo $_SESSION['uploaded_image'];}else{echo my_base_url.my_plikli_base.'/'.str_replace('/','',my_plikli_base).'-og.jpg';}{/php}" />	
	{******* END One method *******}
	{******* Another method *******}
<!--<meta property="og:image" content="{php}global $current_user; if ($_SESSION['uploaded_image'] != '') {echo $_SESSION['uploaded_image'];}elseif ($current_user->authenticated) {{/php}{$Current_User_Avatar.large}{php}}else{{/php}{$my_base_url}{$my_plikli_base}{$Default_Gravatar_Large}{php}}{/php}" /> -->
	{******* END Another method *******}
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta name="twitter:image" content="{php} if ($_SESSION['uploaded_image'] != '') {echo $_SESSION['uploaded_image'];}else{echo my_base_url.my_plikli_base.'/'.str_replace('/','',my_plikli_base).'-og.jpg';}{/php}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{$posttitle}" />
<meta name="twitter:description" content="{if $meta_description neq ""}{$meta_description|truncate:300}{else}{#PLIKLI_Visual_What_Is_Plikli_Text#|sanitize:1}{/if}" />
<!--/meta.tpl -->