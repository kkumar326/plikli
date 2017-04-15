<?php
function links_show_comment_content(&$vars) {
global $smarty, $current_user, $to_convert, $converted, $converted_nofollow;
	if (get_misc_data('links_comments')) {
		$to_convert = $vars['comment_text'];
		$converted = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank">$1</a> ',$to_convert);
		$converted_nofollow = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank" rel="nofollow">$1</a> ',$to_convert);

		/* Redwine: The below code is an extra layer of security to prevent any XSS by not converting any link when a javascript: or window.open or alert() are detected in the text. */
		if (strpos($to_convert, "javascript:") !== false || strpos($to_convert, "window.") !== false || strpos($to_convert, "alert(") !== false) {
				$converted = $to_convert;
				$converted_nofollow = $to_convert;
		}
		/* Redwine: checking if the links module is granted to all user levels. */
		if (get_misc_data('links_all')) {
		/* Redwine: the line of code below must be deleted because it conflicts with urls that are inside an html tag like iframe and converts them to links, which breaks the iframe */
	    //$vars['comment_text'] = text_to_html($vars['comment_text']);
	if (get_misc_data('links_nofollow')) {
		/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Als added open the link in a new window */
				$vars['comment_text'] = $converted_nofollow;
	}else{
				$vars['comment_text'] = $converted;
			}
		}else{
			$vars['comment_text'] = $to_convert;
		}
		
		if (get_misc_data('links_all') == "") {
			if (get_misc_data('links_moderators') && $current_user->user_level == "moderator") {
				if (get_misc_data('links_nofollow')) {
					/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Als added open the link in a new window */
					$vars['comment_text'] = $converted_nofollow;
				}else{
					$vars['comment_text'] = $converted;
				}
			}elseif (get_misc_data('links_admins') && $current_user->user_level == "admin") {
				if (get_misc_data('links_nofollow')) {
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
global $smarty, $current_user, $to_convert, $converted, $converted_nofollow;
	if (get_misc_data('links_stories')) {
		$to_convert = $vars['smarty']->_vars['story_content'];
		if (preg_match('/(<img[^>]+\s{0,}\/?\s{0,}>)/si', $to_convert, $regs)) {
			if (strpos($to_convert, '<div class="videoWrapper"><img src=') === false) {
				$to_convert = str_replace($regs[0], "<div class=\"videoWrapper\">".$regs[0]."</div>", $to_convert);
			}
		}
		$converted = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank">$1</a> ',$to_convert);
		$converted_nofollow = preg_replace('$(https?://[a-zA-Z0-9_.%#/?=&-]+)(?![^<>]*>)$i', ' <a href="$1" target="_blank" rel="nofollow">$1</a> ',$to_convert);

		/* Redwine: The below code is an extra layerr of security to prevent any XSS by not converting any link when a javascript: or window.open or alert() are detected in the text. */
		if (strpos($to_convert, "javascript:") !== false || strpos($to_convert, "window.") !== false || strpos($to_convert, "alert(") !== false) {
				$converted = $to_convert;
				$converted_nofollow = $to_convert;
		}
		/* Redwine: checking if the links module is granted to all user levels. */
		if (get_misc_data('links_all')) {
	/* Redwine: the line of code below must be deleted because it conflicts with urls that are inside an html tag like iframe and converts them to links, which breaks the iframe */
	    //$vars['smarty']->_vars['story_content'] = text_to_html($vars['smarty']->_vars['story_content']);
	if (get_misc_data('links_nofollow')) {
				/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Also added open the link in a new window */
				$vars['smarty']->_vars['story_content'] = $converted_nofollow;
	}else{	
				$vars['smarty']->_vars['story_content'] = $converted;
			}
		}else{
			$vars['smarty']->_vars['story_content'] = $to_convert;
		}
		
		if (get_misc_data('links_all') == "") {
			if (get_misc_data('links_moderators') && $current_user->user_level == "moderator") {
				if (get_misc_data('links_nofollow')) {
					/* Redwine: Changed the regex to skip urls that inside an html tag, like iframe. Also added open the link in a new window */
					$vars['smarty']->_vars['story_content'] = $converted_nofollow;
				}else{	
					$vars['smarty']->_vars['story_content'] = $converted;
				}
			}elseif (get_misc_data('links_admins') && $current_user->user_level == "admin") {
				if (get_misc_data('links_nofollow')) {
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
    return array(
		'comments' => get_misc_data('links_comments'),
		'stories' => get_misc_data('links_stories'),
		'nofollow' => get_misc_data('links_nofollow'),
		'all' => get_misc_data('links_all'),
		'moderators' => get_misc_data('links_moderators'),
		'admins' => get_misc_data('links_admins')
		);
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
			misc_data_update('links_host', sanitize($_REQUEST['links_host'], 3));
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
