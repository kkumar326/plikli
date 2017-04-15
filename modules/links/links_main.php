<?php

$the_settings = links_settings();

function embed_videos($text, $type) {
	global $the_settings;
	
	// wrap the image to make it responsive
	if (preg_match('/(<img[^>]+\s{0,}\/?\s{0,}>)/si', $text, $regs)) {
		if (strpos($text, '<div class="videoWrapper"><img src=') === false) {
			$text = str_replace($regs[0], "<div class=\"videoWrapper\">".$regs[0]."</div>", $text);
		}
	}
//match facebook video urldecode
// The Regular Expression filter
//$reg_facebook = "/(https?:\/\/www\.facebook\.com\/(?:video\.php\?v=\d+|.*?\/videos\/\d+))/";
	$reg_facebook = "~https?://www.facebook.com/.*/videos/(?:t\.\d+/)?(\d+/)(\?type=\d+)?~i";
// Check if there is a url in the text
	if ($type == "articles") {
		$correct_settings = $the_settings['fb_stories'];
	}elseif ($type == 'comms') {
		$correct_settings = $the_settings['fb_comments'];
	}
	if ($correct_settings) {
		if(preg_match($reg_facebook, $text, $facebookurl)) {
			$text = preg_replace($reg_facebook, "<iframe style=\"border: none; overflow: hidden;\" src=\"https://www.facebook.com/plugins/video.php?href=$facebookurl[0]&show_text=0\" frameborder=\"0\" scrolling=\"no\" width=\"560\" height=\"375\"></iframe>",$text);
		}
	}
	if ($type == "articles") {
		$correct_settings = $the_settings['yt_stories'];
	}elseif ($type == 'comms') {
		$correct_settings = $the_settings['yt_comments'];
	}
// Youtube
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
		if(preg_match($reg_youtube, $text, $youtubeurl)) {
			$text = preg_replace($reg_youtube, '<br /><div class="videoWrapper"><iframe width="560" height="315" src="http://www.youtube.com/embed/'.$youtubeurl[1].'" frameborder="0" allowfullscreen></iframe></div><br />',
				$text);
		}
	}
	return $text;
}

function links_show_comment_content(&$vars) {
	global $smarty, $current_user, $to_convert, $converted, $converted_nofollow,$the_settings;
	if ($the_settings['comments']) {
		$to_convert = $vars['comment_text'];
		$check_embed = embed_videos($to_convert, 'comms');
			$converted = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank">$1</a> ',$check_embed);
			$converted_nofollow = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank" rel="nofollow">$1</a> ',$check_embed);

		/* Redwine: The below code is an extra layer of security to prevent any XSS by not converting any link when a javascript: or window.open or alert() are detected in the text. */
		if (strpos($to_convert, "javascript:") !== false || strpos($to_convert, "window.") !== false || strpos($to_convert, "alert(") !== false) {
				$converted = $to_convert;
				$converted_nofollow = $to_convert;
		}
		/* Redwine: checking if the links module is granted to all user levels. */
		if ($the_settings['all']) {
		/* Redwine: the line of code below must be deleted because it conflicts with urls that are inside an html tag like iframe and converts them to links, which breaks the iframe */
	    //$vars['comment_text'] = text_to_html($vars['comment_text']);
			if ($the_settings['nofollow']) {
		/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Als added open the link in a new window */
				$vars['comment_text'] = $converted_nofollow;
	}else{
				$vars['comment_text'] = $converted;
			}
		}else{
			$vars['comment_text'] = $to_convert;
		}
		
		if ($the_settings['all'] == "") {
			if ($the_settings['moderators'] && $current_user->user_level == "moderator") {
				if ($the_settings['nofollow']) {
					/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Als added open the link in a new window */
					$vars['comment_text'] = $converted_nofollow;
				}else{
					$vars['comment_text'] = $converted;
				}
			}elseif ($the_settings['admins'] && $current_user->user_level == "admin") {
				if ($the_settings['nofollow']) {
					/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Als added open the link in a new window */
					$vars['comment_text'] = $converted_nofollow;
				}else{
					$vars['comment_text'] = $converted;
				}
			}
	}
}
}
function links_summary_fill_smarty(&$vars) {
	global $smarty, $current_user, $to_convert, $converted, $converted_nofollow,$the_settings;
	if ($the_settings['stories']) {
		$to_convert = $vars['smarty']->_vars['story_content'];


			$check_embed = embed_videos($to_convert, 'articles');
			$converted = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank">$1</a> ',$check_embed);
			$converted_nofollow = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank" rel="nofollow">$1</a> ',$check_embed);

		/* Redwine: The below code is an extra layerr of security to prevent any XSS by not converting any link when a javascript: or window.open or alert() are detected in the text. */
		if (strpos($to_convert, "javascript:") !== false || strpos($to_convert, "window.") !== false || strpos($to_convert, "alert(") !== false) {
				$converted = $to_convert;
				$converted_nofollow = $to_convert;
		}
		/* Redwine: checking if the links module is granted to all user levels. */
		if ($the_settings['all']) {
	/* Redwine: the line of code below must be deleted because it conflicts with urls that are inside an html tag like iframe and converts them to links, which breaks the iframe */
	    //$vars['smarty']->_vars['story_content'] = text_to_html($vars['smarty']->_vars['story_content']);
			if ($the_settings['nofollow']) {
				/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Also added open the link in a new window */
				$vars['smarty']->_vars['story_content'] = $converted_nofollow;
	}else{	
				$vars['smarty']->_vars['story_content'] = $converted;
			}
		}else{
			$vars['smarty']->_vars['story_content'] = $to_convert;
		}
		
		if ($the_settings['all'] == "") {
			if ($the_settings['moderators'] && $current_user->user_level == "moderator") {
				if ($the_settings['nofollow']) {
					/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Also added open the link in a new window */
					$vars['smarty']->_vars['story_content'] = $converted_nofollow;
				}else{	
					$vars['smarty']->_vars['story_content'] = $converted;
				}
			}elseif ($the_settings['admins'] && $current_user->user_level == "admin") {
				if ($the_settings['nofollow']) {
					/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Also added open the link in a new window */
					$vars['smarty']->_vars['story_content'] = $converted_nofollow;
	}else{	
					$vars['smarty']->_vars['story_content'] = $converted;
				}
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
	
	
    /*return array(
		'comments' => get_misc_data('links_comments'),
		'stories' => get_misc_data('links_stories'),
		'yt_stories' => get_misc_data('links_yt_stories'),
		'yt_comments' => get_misc_data('links_yt_comments'),
		'fb_stories' => get_misc_data('links_fb_stories'),
		'fb_comments' => get_misc_data('links_fb_comments'),
		'nofollow' => get_misc_data('links_nofollow'),
		'all' => get_misc_data('links_all'),
		'moderators' => get_misc_data('links_moderators'),
		'admins' => get_misc_data('links_admins')
		);*/
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
			header("Location: ".my_kliqqi_base."/module.php?module=links");
			die();
		}
		// breadcrumbs
		//$main_smarty->assign('navbar_where', $navwhere);
		$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('KLIQQI_Visual_Header_AdminPanel'));
		// breadcrumbs
		define('modulename', 'links'); 
		$main_smarty->assign('modulename', modulename);
		
		if (!defined('pagename')) define('pagename', 'admin_modifylinks'); 
		$main_smarty->assign('pagename', pagename);
		$main_smarty->assign('settings', links_settings());
		$main_smarty->assign('tpl_center', links_tpl_path . 'links_main');
		$main_smarty->display('/admin/admin.tpl');
	}else{
		header("Location: " . getmyurl('login', $_SERVER['REQUEST_URI']));
	}
}	

?>
