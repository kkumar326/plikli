{************************************
********* Header Template ***********
*************************************}
<!-- header.tpl -->
<header role="banner" class="navbar navbar-inverse navbar-fixed-top custom_header">
	<div class="container">
		<div class="navbar-header">
			<button data-target=".bs-navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle">
				<span class="sr-only">Toggle navigation</span>
				<span class="fa fa-ellipsis-v" style="color:white"></span>
			</button>
			<a class="navbar-brand" href="{$my_base_url}{$my_plikli_base}">{if $Default_Site_Logo neq ""}<img src="{$my_base_url}{$my_plikli_base}{$Default_Site_Logo}"  class="site-logo" alt="{#PLIKLI_Visual_Name#}" title="{#PLIKLI_Visual_Name#}" />{else}{#PLIKLI_Visual_Name#}{/if}</a>			
		</div>
		<nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse">
			<ul class="nav navbar-nav">
				<li {if $pagename eq "published" || $pagename eq "index"}class="active"{/if}><a href="{$my_base_url}{$my_plikli_base}">{#PLIKLI_Visual_Home#}</a></li>
				{checkActionsTpl location="tpl_plikli_navbar_start"}
				<li {if $pagename eq "new"}class="active"{/if}><a href="{$URL_new}">{#PLIKLI_Visual_Plikli_Queued#}</a></li>
				{checkActionsTpl location="tpl_plikli_submit_link_start"}
				<li {if $pagename eq "submit"}class="active"{/if}><a href="{$URL_submit}">{#PLIKLI_Visual_Submit_A_New_Story#}</a></li>
				{checkActionsTpl location="tpl_plikli_submit_link_end"}
				{if $enable_group eq "true"}	
					<li {if $pagename eq "groups" || $pagename eq "submit_groups" || $pagename eq "group_story"}class="active"{/if}><a href="{$URL_groups}"><span>{#PLIKLI_Visual_Groups#}</span></a></li>
				{/if}
				{if $Auto_scroll == '2'}
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">More <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="{$URL_advancedsearch}">{#PLIKLI_Visual_Search_Advanced#}</a> 
							{if $Enable_Live}
								<li><a href="{$URL_live}">{#PLIKLI_Visual_Live#}</a></li>
							{/if}
							{if $Enable_Tags}
								<li><a href="{$URL_tagcloud}">{#PLIKLI_Visual_Tags#}</a></li>
							{/if}
							<li><a href="{$URL_topusers}">{#PLIKLI_Visual_Top_Users#}</a></li>
							{if !empty($URL_rss_page)}
								<li><a href="{$URL_rss_page}" target="_blank" rel="noopener noreferrer">RSS</a></li>
							{/if}
							<li><a href="{$my_base_url}{$my_plikli_base}/rssfeeds.php">{#PLIKLI_Visual_RSS_Feeds#}</a></li>
							{checkActionsTpl location="tpl_plikli_header_more_end"}
						</ul>
					</li>
				{/if}
				{checkActionsTpl location="tpl_plikli_navbar_end"}
				{if $user_authenticated neq true}
					<li {if $pagename eq "register"}class="active"{/if}><a href="{$URL_register}"><span>{#PLIKLI_Visual_Register#}</span></a></li>
					<li {if $pagename eq "login"}class="active"{/if}><a data-toggle="modal" href="#loginModal">{#PLIKLI_Visual_Login_Title#}</a>
				{/if}
				{if isset($isadmin) && $isadmin eq 1}
					<li><a href="{$URL_admin}"><span>{#PLIKLI_Visual_Dashboard#}</span></a></li>
				{/if}
			</ul>
			{*
			<script type="text/javascript">
				{if !isset($searchboxtext)}
					{assign var=searchboxtext value=#PLIKLI_Visual_Search_SearchDefaultText#}			
				{/if}
				var some_search='{$searchboxtext}';
			</script>
			<form action="{$my_plikli_base}/search.php" method="get" name="thisform-search" id="thisform-search" class="navbar-form navbar-left custom_nav_search" role="search" {if $urlmethod==2}onsubmit='document.location.href="{$my_base_url}{$my_plikli_base}/search/"+this.search.value.replace(/\//g,"|").replace(/\?/g,"%3F"); return false;'{/if}>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search" tabindex="20" name="search" value="{$searchboxtext}" onfocus="if(this.value == some_search) {ldelim}this.value = '';{rdelim}" onblur="if (this.value == '') {ldelim}this.value = some_search;{rdelim}"/>
				</div>
				<button type="submit" tabindex="21" class="btn btn-default custom_nav_search_button" />{#PLIKLI_Visual_Search_Go#}</button>
			</form>
			*}
			{if $user_authenticated eq true}
				<div class="btn-group navbar-right">
					<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
						{php}
						global $main_smarty, $current_user;

						if ($current_user->user_id > 0 && $current_user->authenticated) {
								$login=$current_user->user_login;
						}

						// Read the users information from the database
						$user=new User();
						$user->username = $login;
						if(!$user->read()) {
							echo "invalid user";
							die;
						}

						// Assign smarty variables to use in the template.
							$main_smarty->assign('Avatar_ImgLarge', get_avatar('large', $user->avatar_source, $user->username, $user->email));
							$main_smarty->assign('Avatar_ImgSmall', get_avatar('small', $user->avatar_source, $user->username, $user->email));
							$main_smarty->assign('user_names', $user->names);
							$main_smarty->assign('user_id', $user->id);
							$main_smarty->assign('user_username', $user->username);

						{/php}
						{checkActionsTpl location="tpl_plikli_user_button_dropdown_start"}
						<img src="{$Avatar_ImgSmall}" onerror="this.src='{$my_plikli_base}/avatars/Avatar_32.png'; this.title='Loading...';" style="height:16px;width:16px;" /> &nbsp;  {$user_logged_in}
						<span class="caret"></span>{checkActionsTpl location="tpl_plikli_user_button_dropdown_end"}
					</a>
					<ul class="dropdown-menu">
						{checkActionsTpl location="tpl_plikli_profile_sort_start"}
						<li><a href="{$URL_userNoVar}">{#PLIKLI_Visual_Profile#}</a></li>
						<li><a href="{$URL_Profile}"><span>{#PLIKLI_Visual_User_Setting#}</a></li>
						<li><a href="{$user_url_news_sent}">{#PLIKLI_Visual_User_NewsSent#}</a></li>
						<li><a href="{$user_url_draft}">{#PLIKLI_Visual_User_NewsDraft#}</a></li>
						<li><a href="{$user_url_scheduled}">{#PLIKLI_Visual_User_NewsScheduled#}</a></li>
						<li><a href="{$user_url_commented}">{#PLIKLI_Visual_User_NewsCommented#}</a></li>
						<li><a href="{$user_url_news_upvoted}">{#PLIKLI_Visual_UpVoted#}</a></li>
						<li><a href="{$user_url_news_downvoted}">{#PLIKLI_Visual_DownVoted#}</a></li>
						<li><a href="{$user_url_saved}">{#PLIKLI_Visual_User_NewsSaved#}</a></li>
						{checkActionsTpl location="tpl_plikli_profile_sort_end"}
						<li class="divider"></li>
						<li><a href="{$URL_logout}">{#PLIKLI_Visual_Logout#}</a></li>
					</ul>
				</div>
				<!--/$user_authenticated -->
			{/if}
			
		</nav>
	</div>
</header>
<!--/header.tpl -->