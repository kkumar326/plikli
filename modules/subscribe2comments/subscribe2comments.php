<?php

include_once('../../internal/Smarty.class.php');
$main_smarty = new Smarty;

include('../../config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include_once(mnminclude.'utils.php');
include(mnminclude.'user.php');
include(mnminclude.'smartyvariables.php');

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

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
/*if ($_GET['unsub'] && $_GET['unsub'] == 1)
{
	$main_smarty->config_load(subscribe2comments_lang_conf);
	$result = $db->get_row ("SELECT * FROM " . table_users . " WHERE user_login = '$username'");
	if($result)
	{
	    $decode=md5($result->user_email . $result->user_karma .  $username. plikli_hash());
	    if($uns_code==$decode)
	    {
		$linkres=new Link;
		$linkres->id = $link_id;
		if ($linkres->read())
		{
			// Unsibscribe
			$db->query("DELETE FROM `".table_prefix . "subscribe2comments` WHERE notify_user_id={$result->user_id} AND notify_link_id='$link_id'");
	
			// Show the message
			$main_smarty->assign('link', get_object_vars($linkres));
			$main_smarty->assign('story_url', $linkres->get_internal_url());
			$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Unsubscribed_Message'));
		}		
	        else
			$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Invalid_Code'));
	    }
	    else
		$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Invalid_Code'));
	}
	else
	    $main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_No_User'));

//$main_smarty->config_load(subscribe2comments_plikli_lang_conf);
	//define('pagename', 'unsubscribe'); 
	//$main_smarty->assign('pagename', pagename);
	//$main_smarty = do_sidebar($main_smarty);

	//$main_smarty->assign('tpl_center', subscribe2comments_tpl_path . '/subscribe2comments_unsubscribe.tpl'); 
//echo "tpl is " . $main_smarty->get_config_vars('tpl_center');die();	
	//$main_smarty->display($the_template . '/plikli.tpl');
}*/
// Unsubscribe from story page
if ($_REQUEST['uns'])
{
    $db->query("DELETE FROM `".table_prefix . "subscribe2comments` WHERE `notify_user_id`={$current_user->user_id} AND `notify_link_id`='$link_id'");
    echo "<span style=\"margin:15px 0 2px 0;\" class=\"btn btn-primary\" href=\"javascript://\" onclick=\"subscribe_2_comments({$_REQUEST['linkid']},{$_REQUEST['linkid']});\" ><i class=\"fa fa-envelope\"></i> ".$main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Subscribe')."</span>";

}
// Subscribe from story page
else
{
    $db->query("INSERT IGNORE INTO `".table_prefix . "subscribe2comments` SET `notify_user_id`={$current_user->user_id}, `notify_link_id`='$link_id'");
    echo "<span style=\"margin:15px 0 2px 0;\" class=\"btn btn-primary\" href=\"javascript://\" onclick=\"subscribe_2_comments({$_REQUEST['linkid']},{$_REQUEST['linkid']},1);\" ><i class=\"fa fa-envelope\"></i> ".$main_smarty->get_config_vars('PLIKLI_Subscribe_2_Comments_Unsubscribe')."</span>";

}

?>