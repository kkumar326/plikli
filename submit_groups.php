<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'tags.php');
include(mnminclude.'smartyvariables.php');
include(mnminclude.'csrf.php');

check_referrer();
$CSRF = new csrf();

// html tags allowed during submit
//$main_smarty->assign('Story_Content_Tags_To_Allow', htmlspecialchars(Story_Content_Tags_To_Allow));

// breadcrumbs and page titles
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Submit_A_New_Group');
$navwhere['link1'] = getmyurl('submit', '');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Submit_A_New_Group'));
$main_smarty = do_sidebar($main_smarty);
$main_smarty->assign('auto_approve_group', auto_approve_group);

// make sure user is logged in
force_authentication();
$current_user_level = $current_user->user_level;
/* Redwine: Roles and permissions and Groups fixes. Check if the user didn't exceed the max number of creating groups.*/
$numGr = $db->get_var("SELECT count(*) FROM " .table_groups . " WHERE `group_creator` = " . $current_user->user_id);
$max_user_groups_allowed = $main_smarty->get_template_vars('max_user_groups_allowed');
if ($numGr >= $max_user_groups_allowed) {
	$errors = $main_smarty->get_config_vars('PLIKLI_Visual_Submit_A_New_Group_Error');
	$main_smarty->assign('error', $errors);
// pagename
define('pagename', 'submit_groups');
$main_smarty->assign('pagename', pagename);

// show the template
$main_smarty->assign('tpl_center', $the_template . '/submit_groups_center');
$main_smarty->display($the_template . '/plikli.tpl');
exit;
}
if(enable_group == "true" && (group_submit_level == $current_user_level || group_submit_level == 'normal' || $current_user_level == 'admin'))
//if(enable_group == "true" && group_allow == 1)
{
	if(isset($_POST['group_title'])){
		$group_title = $db->escape(stripslashes(strip_tags(trim($_POST['group_title']))));
		$group_title = preg_replace('/[^\p{L}\p{N}-_\s]/u', ' ', $group_title);
	}else{
		$group_title = '';
	}
	if(isset($_POST['group_description'])){
		$group_description = $db->escape(stripslashes(strip_tags(trim($_POST['group_description']))));
	}else{
		$group_description = "";
	}
	if(isset($_POST['group_vote_to_publish'])){
		$group_vote_to_publish = $db->escape(stripslashes(strip_tags(trim($_POST['group_vote_to_publish']))));
	}else{
		$group_vote_to_publish = "";
	}
	if(isset($_POST['group_notify_email']) && $_POST['group_notify_email']>0) $group_notify_email = 1;
	else $group_notify_email = 0;
	$group_author = $current_user->user_id;
	$group_members = 1;
	$g_date=time();
	$group_date = $g_date;
	$group_published_date = 943941600;
	$group_name = $group_title;
	$group_description = $group_description;
	
//	$group_safename = str_replace(' ', '-', $group_title);
	$group_safename = makeUrlFriendly($group_title, true);
	
	if(isset($_POST['group_privacy']))
		$group_privacy = $db->escape(sanitize($_POST['group_privacy'],3));
	
	if(auto_approve_group == 'true')
		$group_status = 'enable';
	else
		$group_status = 'disable';
	if(isset($_POST['group_title']))
	{
	    // Redwine: if TOKEN is empty, no need to continue, just display the invalid token error.
		if (empty($_POST['token'])) {
			$CSRF->show_invalid_error(1);
			exit;
		}
		// Redwine: if valid TOKEN, proceed. A valid integer must be equal to 2.
	    if ($CSRF->check_valid(sanitize($_POST['token'], 3), 'submit_group') != 2){
	    	$CSRF->show_invalid_error(1);
		exit;
	    }

	    $errors = '';
	    if (!$group_name || $group_name=='') $errors = $main_smarty->get_config_vars('PLIKLI_Visual_Group_Empty_Title');
	    elseif ($group_vote_to_publish<=0) $errors = $main_smarty->get_config_vars('PLIKLI_Visual_Group_Empty_Votes');
	    else
	    {
		$exists = $db->get_var("select COUNT(*) from ".table_groups." WHERE group_name='$group_name'");
	 	if ($exists) $errors = $main_smarty->get_config_vars('PLIKLI_Visual_Group_Title_Exists');
	    }

	    if (!$errors)
	    {
		//to insert a group
		$insert_group = "INSERT IGNORE INTO " . table_groups . " (group_creator, group_status, group_members, group_date, group_safename, group_name, group_description, group_privacy, group_vote_to_publish, group_notify_email) VALUES ($group_author, '$group_status', $group_members,FROM_UNIXTIME($group_date),'$group_safename','$group_name', '$group_description', '$group_privacy', '$group_vote_to_publish', '$group_notify_email')";
		$result = $db->query($insert_group);
		
		//get linkid inserted above
		$in_id = $db->get_var("select max(group_id) as group_id from ".table_groups." ");
		//echo 'sdgfdsgds'.$in_id;
		
		//to make group creator a member
/* Redwine: Roles and permissions and Groups fixes */
		$insert_member = "INSERT IGNORE INTO ". table_group_member ." (`member_user_id` , `member_group_id`, `member_role`) VALUES (".$group_author.", ".$in_id.",'admin' )";
		$db->query($insert_member);
		
		if(isset($_POST['group_mailer']))
		{
			if(phpnum() == 4) {
				require_once(mnminclude.'class.phpmailer4.php');
			} else {
				require_once(mnminclude.'class.phpmailer5.php');
			}
			if(isset($_POST['group_mailer']))
			{
				Global $db,$current_user;
				$names = $_POST['group_mailer'];
				$v1 = explode (",", $names);
				$name = "";
				
				$user = new User;
				$user->id = $current_user->user_id;
				$user->read();
				
				$author_email = $user->email;
				$username = $user->username;
				
				
				foreach ($v1 as $t)
				{
					/* Redwine: the two lines below are useless. */
					//$str='';
					//$from = "email@example.com";
					$subject = $main_smarty->get_config_vars('PLIKLI_InvitationEmail_Subject');
					$to = $t;
					
					$message = sprintf($main_smarty->get_config_vars('PLIKLI_InvitationEmail_Message'),"<a href='".my_base_url.my_plikli_base."/group_story.php?id=".$in_id."'>".$group_name."</a>","<a href='".my_base_url.my_plikli_base."/user.php?login=".$username."'>".$username."</a>");
					
					//echo $to.":".$site_mail.":".$subject."$message<br/>";
					/* Redwine: the $mail->From = $site_mail is wrong, because $site_mail is defined nowhere! We must set it to the value defined in the language file. The same applies to $mail->AddReplyTo */
					$mail = new PHPMailer();
					$mail->From = $main_smarty->get_config_vars('PLIKLI_PassEmail_From');
					$mail->FromName = $main_smarty->get_config_vars('PLIKLI_PassEmail_Name');
					$mail->AddAddress($to);
					$mail->AddReplyTo($main_smarty->get_config_vars('PLIKLI_PassEmail_From'));
					$mail->IsHTML(true);
					$mail->Subject = $subject;
					$mail->Body = $message;
					$mail->CharSet = 'utf-8';
					$mail->Send();
				}
			}
		}
		if($result)
		{
			//redirect
			$redirect = '';
			$redirect = getmyurl("group_story", $in_id);
			header("Location: $redirect");
			die;
		}
	    }
	}
    	$CSRF->create('submit_group', true, true);
	
	//echo $sql;
}

// pagename
define('pagename', 'submit_groups');
if (!empty($errors)) {
	$main_smarty->assign('error', $errors);
}
$main_smarty->assign('pagename', pagename);

// show the template
$main_smarty->assign('tpl_center', $the_template . '/submit_groups_center');
$main_smarty->display($the_template . '/plikli.tpl');
?>