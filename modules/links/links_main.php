<?php

$links_settings = links_settings();

function embed_videos($text, $type) {
	global $links_settings;
	
	// wrap the image to make it responsive
	if (preg_match('/(<img[^>]+\s{0,}\/?\s{0,}>)/si', $text, $regs)) {
		if (strpos($text, '<div class="videoWrapper"><img src=') === false) {
			$text = str_replace($regs[0], "<div class=\"videoWrapper\">".$regs[0]."</div>", $text);
		}
	}

	$reg_facebook = "~https?://www.facebook.com/.*/videos/(?:t\.\d+/)?(\d+/)(\?type=\d+)?~i";
// Check if there is a url in the text
	if ($type == "articles") {
		$correct_settings = $links_settings['fb_stories'];
	}elseif ($type == 'comms') {
		$correct_settings = $links_settings['fb_comments'];
	}
//match facebook video url
	if ($correct_settings) {
		if(preg_match_all($reg_facebook, $text, $facebookurl)) {
			$numFB = sizeof($facebookurl);
			$numFirstFB = sizeof($facebookurl[0]);
			for ($FB = 0; $FB < $numFirstFB; $FB++) {
				$text = str_replace($facebookurl[0][$FB], "<br /><div class=\"videoWrapper\"><iframe style=\"border: none; overflow: hidden;\" src=\"https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/facebook/videos/".$facebookurl[1][$FB]."&show_text=0\" frameborder=\"0\" scrolling=\"no\" width=\"560\" height=\"375\"></iframe></div><br />",	$text);
			}
		}
	}
	if ($type == "articles") {
		$correct_settings = $links_settings['yt_stories'];
	}elseif ($type == 'comms') {
		$correct_settings = $links_settings['yt_comments'];
	}
// match Youtube video url
	if ($correct_settings) {
		$reg_youtube = '~(?#!js YouTubeId Rev:20160125_1800)
				# Match non-linked youtube URL in the wild. (Rev:20130823)
				https?://          # Required scheme. Either http or https.
				(?:[0-9A-Z-]+\.)?  # Optional subdomain.
				(?:                # Group host alternatives.
				  youtu\.be/       # Either youtu.be,
				| youtube          # or youtube.com or
				  (?:-nocookie)?   # youtube-nocookie.com
				  \.com            # followed by
				  \S*?             # Allow anything up to VIDEO_ID,
				  [^\w\s-]         # but char before ID is non-ID char.
				)                  # End host alternatives.
				([\w-]{11})        # $1: VIDEO_ID is exactly 11 chars.
				(?=[^\w-]|$)       # Assert next char is non-ID or EOS.
				(?!                # Assert URL is not pre-linked.
				  [?=&+%\w.-]*     # Allow URL (query) remainder.
				  (?:              # Group pre-linked alternatives.
					[\'"][^<>]*>   # Either inside a start tag,
				  | </a>           # or inside <a> element text contents.
				  )                # End recognized pre-linked alts.
				)                  # End negative lookahead assertion.
				[?=&+%\w.-]*       # Consume any URL (query) remainder.
				~ix';
		if(preg_match_all($reg_youtube, $text, $youtubeurl)) {
			$numYT = sizeof($youtubeurl);
			$numFirst = sizeof($youtubeurl[0]);
			for ($YT = 0; $YT < $numFirst; $YT++) {
				$text = str_replace($youtubeurl[0][$YT], '<br /><div class="videoWrapper"><iframe width="560" height="315" src="http://www.youtube.com/embed/'.$youtubeurl[1][$YT].'" frameborder="0" allowfullscreen></iframe></div><br />',	$text);
		}
		}

		$findlinks = preg_match_all('$(https?://[\p{L}a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$ui', $text, $matches);
		if ($matches) {
		foreach($matches[1] as $match) {
			// matching any of the audio extensions
			$ogg = stripos($match, '.ogg');
			$mp3 = stripos($match, '.mp3');
			$wav = stripos($match, '.wav');
			$jpg = stripos($match, '.jpg');
				$jpeg = stripos($match, '.jpeg');
			$png = stripos($match, '.png');
			$gif = stripos($match, '.gif');
			if ( $ogg !== false || $mp3 !== false || $wav !== false) {
					$text = str_replace($match, " <!--[if lt IE 9]><script>document.createElement(\"audio\");</script><![endif]-->
		<br /><audio preload=\"none\" style=\"width: 50%;\" controls=\"controls\"><source type=\"audio/mpeg\" src=\"$match\" /></audio><br />",$text);
			}elseif ( $jpg !== false || $png !== false || $gif !== false) {
					$text = preg_replace("#(?<!=\")($match)(?![^<>]*>)#ui", " <div class=\"videoWrapper\"><img src=\"$match\" /></div>", $text);
			}else{
				// matching all other urls that are not within html tags
				if ($links_settings['nofollow'] == "1") {
						$text = preg_replace("#(?<!=\")($match)(?![^<>]*>)#ui", " <a href=\"$match\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">$match</a> ",$text);
				}else{
					$text = preg_replace('$(\s|^)(https?://[\p{L}a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$ui', " <a href=\"$match\" target=\"_blank\" rel=\"noopener noreferrer\">$match</a> ",$text);
				}
			}
		}
	}
	}
	return $text;
}

function links_show_comment_content(&$vars) {
	global $smarty, $current_user, $to_convert, $converted, $converted_nofollow,$links_settings;
	if ($links_settings['comments']) {
		$to_convert = $vars['comment_text'];
		$converted = embed_videos($to_convert, 'comms');
			

		/* Redwine: The below code is an extra layer of security to prevent any XSS by not converting any link when a javascript: or window.open or alert() are detected in the text. */
		if (strpos($to_convert, "javascript:") !== false || strpos($to_convert, "window.") !== false || strpos($to_convert, "alert(") !== false) {
				$converted = $to_convert;
				$converted_nofollow = $to_convert;
		}
		/* Redwine: checking if the links module is granted to all user levels. */
		if ($links_settings['all']) {
					$vars['comment_text'] = $converted;
		}else{
			$vars['comment_text'] = $to_convert;
		}
		
		if ($links_settings['all'] == "") {
			if ($links_settings['moderators'] && $current_user->user_level == "moderator") {
					$vars['comment_text'] = $converted;
			}elseif ($links_settings['admins'] && $current_user->user_level == "admin") {
					$vars['comment_text'] = $converted;
				}
			}
		}
	}
function links_summary_fill_smarty(&$vars) {
	global $smarty, $current_user, $to_convert, $converted, $converted_nofollow,$links_settings;
	if ($links_settings['stories']) {
		$to_convert = $vars['smarty']->_vars['story_content'];
		
			$converted = embed_videos($to_convert, 'articles');

		
		/* Redwine: The below code is an extra layerr of security to prevent any XSS by not converting any link when a javascript: or window.open or alert() are detected in the text. */
		if (strpos($to_convert, "javascript:") !== false || strpos($to_convert, "window.") !== false || strpos($to_convert, "alert(") !== false) {
				$converted = $to_convert;
				$converted_nofollow = $to_convert;
		}
		/* Redwine: checking if the links module is granted to all user levels. */
		if ($links_settings['all']) {
				$vars['smarty']->_vars['story_content'] = $converted;
		}else{
			$vars['smarty']->_vars['story_content'] = $to_convert;
		}
		
		if ($links_settings['all'] == "") {
			if ($links_settings['moderators'] && $current_user->user_level == "moderator") {
					$vars['smarty']->_vars['story_content'] = $converted;
			}elseif ($links_settings['admins'] && $current_user->user_level == "admin") {
					$vars['smarty']->_vars['story_content'] = $converted;
				}
			}
		}
	}

// 
// Read module settings
//
function links_settings()
{
    $settings1 = array();
	global $db;
	$sql = "SELECT * FROM  `".table_prefix."misc_data` WHERE `name` like 'links_%'";
	$misc_settings = $db->get_results($sql);
	foreach($misc_settings as $misc_setting) {
		$settings1[str_replace('links_', '', $misc_setting->name)] = $misc_setting->data;
	}
	return $settings1;
}

//
// Settings page
//
function links_showpage(){
	global $db, $main_smarty, $the_template;
		
	include_once('config.php');
	include_once(mnminclude.'html1.php');
	include_once(mnminclude.'link.php');
	include_once(mnminclude.'tags.php');
	include_once(mnminclude.'smartyvariables.php');
	
	//$main_smarty = do_sidebar($main_smarty);

	force_authentication();
	$canIhaveAccess = 0;
	$canIhaveAccess = $canIhaveAccess + checklevel('admin');
	
	if($canIhaveAccess == 1) {	
		if (isset($_POST['submit'])) {
			misc_data_update('links_comments', sanitize($_REQUEST['links_comments'], 3));
			misc_data_update('links_stories', sanitize($_REQUEST['links_stories'], 3));
			misc_data_update('links_nofollow', sanitize($_REQUEST['links_nofollow'], 3));
			misc_data_update('links_yt_stories', sanitize($_REQUEST['links_yt_stories'], 3));
			misc_data_update('links_yt_comments', sanitize($_REQUEST['links_yt_comments'], 3));
			misc_data_update('links_fb_stories', sanitize($_REQUEST['links_fb_stories'], 3));
			misc_data_update('links_fb_comments', sanitize($_REQUEST['links_fb_comments'], 3));
			//misc_data_update('links_host', sanitize($_REQUEST['links_host'], 3));
			misc_data_update('links_all', sanitize($_REQUEST['links_all'], 3));
			misc_data_update('links_moderators', sanitize($_REQUEST['links_moderators'], 3));
			misc_data_update('links_admins', sanitize($_REQUEST['links_admins'], 3));
			header("Location: ".my_plikli_base."/module.php?module=links");
			die();
		}
		// breadcrumbs
		//$main_smarty->assign('navbar_where', $navwhere);
		$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel'));
		// breadcrumbs
		define('modulename', 'links'); 
		$main_smarty->assign('modulename', modulename);
		
		if (!defined('pagename')) define('pagename', 'admin_modifylinks'); 
		$main_smarty->assign('pagename', pagename);
		$main_smarty->assign('links_settings', links_settings());
		$main_smarty->assign('tpl_center', links_tpl_path . 'links_main');
		$main_smarty->display('/admin/admin.tpl');
	}else{
		header("Location: " . getmyurl('login', $_SERVER['REQUEST_URI']));
	}
}	

?>