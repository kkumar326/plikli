<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'smartyvariables.php');

// Get values from the end user
$rcode=$db->escape(trim($_GET['code']));
$username=$db->escape(trim($_GET['uid']));

// Retrieve values from database
$user=("SELECT user_email, user_pass, user_karma, user_lastlogin FROM " . table_users . " WHERE user_login = '$username'");
global $db;
$result = $db->get_row ($user);
if($result)
    if(isset($_GET['email']))
	$decode=md5($_GET['email'] . $result->user_karma .  $username. plikli_hash().$main_smarty->get_config_vars('PLIKLI_Visual_Name'));
    else
	$decode=md5($result->user_email . $result->user_karma .  $username. plikli_hash().$main_smarty->get_config_vars('PLIKLI_Visual_Name'));
else
	$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Validation_No_Results'));

// Compare values
if($rcode==$decode)
{
	/* Redwine: Fixed erroneous code ($user->karma and $user->username) that was rendering the validation impossible when users request a validation code upon login. At the same time, added a query to delete the entry from the login_attempts table because the user had to wait x number of seconds based on the number of login attempts. Now, upon successful validation, this entry is deleted and users don't have to wait any more!*/
	$db->query("DELETE FROM ".table_login_attempts." WHERE login_username='$username'");
	$lastlogin = $db->get_var("SELECT user_lastlogin FROM " . table_users . " WHERE user_login = '$username'");
	if($lastlogin == "0000-00-00 00:00:00"){
		$login_url=getmyurl("loginNoVar");
		$message = sprintf($main_smarty->get_config_vars('PLIKLI_Validation_Message'),$login_url);
		$main_smarty->assign('message', $message);

		$sql="UPDATE " . table_users . " SET user_lastlogin = now() WHERE user_login='$username'";
		if(!$db->query($sql))
			$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Validation_Mysql_Error'));
	}
	elseif($_GET['email'] && $_GET['email']!=$result->user_email)
	{
		$login_url=getmyurl("loginNoVar");
		$message = sprintf($main_smarty->get_config_vars('PLIKLI_Validation_Message'),$login_url);
		$main_smarty->assign('message', $message);

		$sql="UPDATE " . table_users . " SET user_email = '".$db->escape($_GET['email'])."' WHERE user_login='$username'";
		if(!$db->query($sql))
			$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Validation_Mysql_Error'));
	}
	else
		$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Validation_Already_Activated'));
}
else
	$main_smarty->assign('error', $main_smarty->get_config_vars('PLIKLI_Validation_Invalid_Code'));

define('pagename', 'validation'); 
$main_smarty->assign('pagename', pagename);

do_sidebar($main_smarty);

$main_smarty->assign('tpl_center', $the_template . '/user_validation_center');
$main_smarty->display($the_template . '/plikli.tpl');
?>