<?php
//
// Settings page
//
function spam_trigger_showpage(){
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
	
	if($canIhaveAccess == 1)
	{	
		// Save settings
		if (isset($_POST['submit']))
		{
			misc_data_update('spam_trigger_light', sanitize($_REQUEST['spam_light'], 3));
			misc_data_update('spam_trigger_medium', sanitize($_REQUEST['spam_medium'], 3));
			misc_data_update('spam_trigger_hard', sanitize($_REQUEST['spam_hard'], 3));

			header("Location: ".my_plikli_base."/module.php?module=spam_trigger");
			die();
		}
		// breadcrumbs
		$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel');
		$navwhere['link1'] = getmyurl('admin', '');
		$navwhere['text2'] = "Modify spam_trigger";
		$navwhere['link2'] = my_plikli_base . "/module.php?module=spam_trigger";
		$main_smarty->assign('navbar_where', $navwhere);
		$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel'));

		define('modulename', 'spam_trigger'); 
		$main_smarty->assign('modulename', modulename);
		
		if (!defined('pagename')) define('pagename', 'admin_modifyspam_trigger'); 
		$main_smarty->assign('pagename', pagename);
		$main_smarty->assign('spam_settings', str_replace('"','&#034;',get_spam_trigger_settings()));
		//$main_smarty->assign('places',$spam_trigger_places);
		$main_smarty->assign('tpl_center', spam_trigger_tpl_path . 'spam_trigger_main');
		$main_smarty->display('/admin/admin.tpl');
	}
	else
	{
		header("Location: " . getmyurl('login', $_SERVER['REQUEST_URI']));
	}
}	

function spam_trigger_editlink()
{
	global $db, $current_user, $linkres;
/* Redwine: Spam Trigger Module was not working as intended. Fix provided by modifying 8 files.">Spam Trigger Module was not working as intended. https://github.com/Pligg/pligg-cms/commit/2faf855793814f82d7c61a8745a93998c13967e0 */
	//if (checklevel('moderator') || checklevel('admin')) return;
	if (!is_numeric($_POST['id'])) return;

	$spam_settings = get_spam_trigger_settings();
	isset($_POST['summarytext']) ?: $_POST['summarytext'] = '';
	$str = $_POST['title']."\n".$_POST['bodytext']."\n".$_POST['summarytext']."\n".$_POST['tags'];
	// Killspam user
	if ($spam_settings['spam_hard'] && !spam_trigger_check($str, $spam_settings['spam_hard']))
	{
		$_SESSION['spam_trigger_story_error'] = 'deleted';
		spam_trigger_killspam($current_user->user_id);
		$linkres->status = 'spam';
	}
	// discard story
	elseif ($spam_settings['spam_medium'] && !spam_trigger_check($str, $spam_settings['spam_medium']))
	{
		$_SESSION['spam_trigger_story_error'] = 'discarded';
		$linkres->status = 'discard';
	}
	// set status to moderated
	elseif ($spam_settings['spam_light'] && !spam_trigger_check($str, $spam_settings['spam_light']))
	{
		$_SESSION['spam_trigger_story_error'] = 'moderated';
		$linkres->status = 'moderated';
	}
}

function spam_trigger_do_submit3($vars)
{
	global $db, $current_user;
/* Redwine: Spam Trigger Module was not working as intended. Fix provided by modifying 8 files.">Spam Trigger Module was not working as intended. https://github.com/Pligg/pligg-cms/commit/2faf855793814f82d7c61a8745a93998c13967e0 */
	//if (checklevel('moderator') || checklevel('admin')) return;
	$linkres = $vars['linkres'];
	if (!$linkres->id) return;

	$spam_settings = get_spam_trigger_settings();

	$str = $linkres->title."\n".$linkres->content."\n".$linkres->link_summary."\n".$linkres->tags;
	// Killspam user
	if ($spam_settings['spam_hard'] && !spam_trigger_check($str, $spam_settings['spam_hard']))
	{
		$_SESSION['spam_trigger_story_error'] = 'deleted';
		spam_trigger_killspam($current_user->user_id);
		$vars['linkres']->status = 'spam';
	}
	// discard story
	elseif ($spam_settings['spam_medium'] && !spam_trigger_check($str, $spam_settings['spam_medium']))
	{
		$_SESSION['spam_trigger_story_error'] = 'discarded';
		$vars['linkres']->status = 'discard';
	}
	// set status to moderated
	elseif ($spam_settings['spam_light'] && !spam_trigger_check($str, $spam_settings['spam_light']))
	{
		$_SESSION['spam_trigger_story_error'] = 'moderated';
		$vars['linkres']->status = 'moderated';
	}
}

function spam_trigger_comment($vars)
{
	global $db, $current_user;

	$spam_settings = get_spam_trigger_settings();

	$str = $_POST['comment_content'];
	// Killspam user
	if ($spam_settings['spam_hard'] && !spam_trigger_check($str, $spam_settings['spam_hard']))
	{
		$_SESSION['spam_trigger_comment_error'] = 'deleted';
		spam_trigger_killspam($current_user->user_id);
		$vars['error'] = 1;
/* Redwine: updating the comment status to spam*/
		$vars['comment']->status = 'spam';
	}
	// delete comment
	elseif ($spam_settings['spam_medium'] && !spam_trigger_check($str, $spam_settings['spam_medium']))
	{
		$_SESSION['spam_trigger_comment_error'] = 'deleted';
		$vars['error'] = 1;
		$vars['comment']->status = 'moderated';
	}
	// set status to moderated
	elseif ($spam_settings['spam_light'] && !spam_trigger_check($str, $spam_settings['spam_light']))
	{
		$_SESSION['spam_trigger_comment_error'] = 'moderated';
		$vars['comment']->status = 'discard';
	}
}

function spam_trigger_killspam($id)
{
	killspam($id);
}


function spam_trigger_check($text, $wordlist)
{
	$words = explode("\n",$wordlist);
	foreach ($words as $word)
	{
	    $word = trim(str_replace("\r","",$word));
    	    if (strlen($word) && preg_match('/(^|[^\w])('.$word.')($|[^\w])/i',$text))
		return false;
	}
	return true;
}

// 
// Read module settings
//
function get_spam_trigger_settings()
{
    return array(
		'spam_light' => get_misc_data('spam_trigger_light'), 
		'spam_medium' => get_misc_data('spam_trigger_medium'), 
		'spam_hard' => get_misc_data('spam_trigger_hard')
		);
}
?>