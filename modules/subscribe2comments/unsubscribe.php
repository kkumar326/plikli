<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include_once(mnminclude.'utils.php');
include(mnminclude.'user.php');
include(mnminclude.'smartyvariables.php');

$link_id = $db->escape($_REQUEST['linkid']);
$link_shakebox_index = $db->escape($_REQUEST['htmlid']);
if (!$link_id) return;

$uns_code=$db->escape(trim($_GET['code']));
$username=$db->escape(trim($_GET['uid']));

// Load settings
include_once(mnmmodules.'subscribe2comments/subscribe2comments_settings.php');

//$smarty = new Smarty;
$main_smarty->config_load(subscribe2comments_lang_conf);

// Unsubscription link from email
if ($_GET['unsub'] && $_GET['unsub'] == 1) {
	$result = $db->get_row ("SELECT * FROM " . table_users . " WHERE user_login = '$username'");
	if($result) {
		$subscribed = $db->get_row("SELECT * FROM `" . table_prefix . "subscribe2comments` WHERE `notify_user_id` = $result->user_id AND `notify_link_id` = $link_id;");
		if ($subscribed) {
			$decode=md5($result->user_email . strtotime($result->user_date) .  $username. plikli_hash());
			if($uns_code==$decode) {
				$linkres=new Link;
				$linkres->id = $link_id;
				if ($linkres->read()) {
					// Unsibscribe
					$db->query("DELETE FROM `".table_prefix . "subscribe2comments` WHERE notify_user_id={$result->user_id} AND notify_link_id='$link_id'");
			
					// Show the message
					$main_smarty->assign('link', get_object_vars($linkres));
					$main_smarty->assign('story_url', urldecode(my_base_url . $linkres->get_internal_url()));
					//$story_url = $linkres->get_internal_url();
					$main_smarty->assign('message_uns', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Unsubscribed_Message'));
				}else{
					$main_smarty->assign('message_error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Invalid_Code'));
				}
			}else{
				$main_smarty->assign('message_error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Invalid_Code'));
			}
		}else{
			$main_smarty->assign('message_error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Unsubscribe_Wrong'));
		}
	}else{
	    $main_smarty->assign('message_error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_No_User'));
	}

$main_smarty->config_load(subscribe2comments_plikli_lang_conf);
	define('pagename', 'story'); 
	$main_smarty->assign('pagename', pagename);
	$main_smarty = do_sidebar($main_smarty);

	$main_smarty->assign('tpl_center', subscribe2comments_tpl_path . '/subscribe2comments_unsubscribe');    
	$main_smarty->display($the_template . '/plikli.tpl');
}
?>