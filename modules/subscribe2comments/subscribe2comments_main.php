<?php

function subscribe2comments_comment_submit(&$vars)
{
	include_once(mnmmodules.'subscribe2comments/subscribe2comments_settings.php');
	global $db, $main_smarty, $current_user;
	
	$subscribe2_settings = subscribe2comments_settings();
	$main_smarty->config_load(subscribe2comments_plikli_lang_conf);
	if ($subscribe2_settings['from_email'] !='') {
		$fromEmail = $subscribe2_settings['from_email'];
	}else{
		$fromEmail = $main_smarty->get_config_vars("PLIKLI_PassEmail_From");
	}
	if ($subscribe2_settings['from'] !='') {
		$fromSite = $subscribe2_settings['from'];
	}else{
		$fromSite = $main_smarty->get_config_vars("PLIKLI_Visual_Name");
	}
	if ($subscribe2_settings['background'] !='') {
		$headingbg = $subscribe2_settings['background'];
	}else{
		$headingbg = '#183a52';
	}
	if ($subscribe2_settings['fontcolor'] !='') {
		$headingfc = $subscribe2_settings['fontcolor'];
	}else{
		$headingbg = '#ffffff';
	}
	$main_smarty->assign('headingbg', $headingbg);
	$main_smarty->assign('headingfc', $headingfc);
	if ($subscribe2_settings['subject'] !='') {
		$fromSubject = $subscribe2_settings['subject'];
	}else{
		$fromSubject = $main_smarty->get_config_vars("PLIKLI_Subscribe_2_Comments_Email_Subject");
	}
	$main_smarty->assign('site_title', $fromSite);
	$main_smarty->config_load(subscribe2comments_lang_conf);

	$comment = new Comment;
	$comment->id = $vars['comment'];
	if (!$comment->read()) return;

	$user=new User();
	$user->id = $comment->author;
	$linkres=new Link;
	$linkres->id = $comment->link;

	if($user->read() && $linkres->read()) {
		$user_profile = my_base_url.getmyurl('user2', $login, 'profile');
		$main_smarty->assign('user_profile', $user_profile);
		$user_all_avatars = $main_smarty->get_template_vars("Current_User_Avatar");
		$user_avatar = $user_all_avatars['large'];
		$main_smarty->assign('user_avatar', $user_avatar);

		$main_smarty->assign('link_title', $linkres->title);
		$main_smarty->assign('story_url', my_base_url.$linkres->get_internal_url());
		$main_smarty->assign('comment_content', $comment->content); 

		if ($user->username=='anonymous' && function_exists('get_comment_username')) {
		    $vars['comment_username']='anonymous';
		    $vars['comment_id'] = $vars['comment'];
			$anonymous_name = $db->get_var("SELECT `comment_anonymous_username` FROM `".table_prefix . "comments` WHERE `comment_id` = {$vars['comment_id']}");
		    $user->username = $vars['comment_username'];
			//echo "user anonymous is " .$user->username;die();
		}
		if (Default_Site_Logo != '') {
			$site_logo = my_base_url.my_plikli_base.Default_Site_Logo;
			$main_smarty->assign('site_logo', $site_logo); 
		}
		
		$main_smarty->assign('comment_author', $user->username);

	    // Select users who subscribed to that story notifications
	    $subs = $db->get_results($sql="SELECT * FROM `".table_prefix . "subscribe2comments` WHERE notify_link_id='{$linkres->id}' AND notify_user_id!={$comment->author}",ARRAY_A);
	
	    // Send notification to author of parent comment (if presented)
	    if ($comment->parent) {
			$comment1 = new Comment;
			$comment1->id = $comment->parent;
			if ($comment1->read()) {
				$user1 = new User();
				$user1->id = $comment1->author;
				if($user1->read() && $user1->extra_field['auto_comment_alert']) 
					$subs[] = array('notify_user_id' => $comment1->author);
			}
   	    }

	    if ($subs && $fromEmail) {
			require_once(mnminclude.'class.phpmailer5.php');
			$mail = new PHPMailer();
			foreach ($subs as $sub) {
				$user->id = $sub['notify_user_id'];
				if ($user->read())
				{
				$main_smarty->assign('unsubscribe_link', my_base_url.my_plikli_base . '/unsubscribe.php?linkid='.$linkres->id.'&unsub=1&uid='.$user->username.'&code='.md5($user->email . $user->date . $user->username . plikli_hash()));
				$text = $main_smarty->fetch('../modules/subscribe2comments/templates/subscribe2comments_email.tpl');

						$mail->From = $fromEmail;
						$mail->FromName = $fromSite;
						$mail->AddAddress($user->email);
						$mail->AddReplyTo($main_smarty->get_config_vars('PLIKLI_PassEmail_From'));
						$mail->IsHTML(true);
						$mail->Subject = $fromSubject;
						$mail->Body = $text;
						$mail->CharSet = 'utf-8';
						$mail->Send();
				}
			}
		}
	}
}

//
// Subscribe author automatically on submit
function subscribe2comments_story_submit($vars)
{
	global $db, $main_smarty;

	$linkres = $vars['linkres'];
	if (!$linkres->id) return;

	$user=new User();
	$user->id = $linkres->author;

	if($user->read() && $user->extra_field['auto_comment_alert']) 
	    $db->query($sql="INSERT INTO ".table_prefix."subscribe2comments SET notify_user_id='{$linkres->author}',
							    		   notify_link_id='{$linkres->id}'");
}

//
// Settings page
//
function subscribe2comments_showpage(){
	global $db, $main_smarty, $the_template;
		
	include_once('config.php');
	include_once(mnminclude.'html1.php');
	include_once(mnminclude.'link.php');
	include_once(mnminclude.'tags.php');
	include_once(mnminclude.'smartyvariables.php');
	
	$main_smarty = do_sidebar($main_smarty);

	force_authentication();
	$canIhaveAccess = 0;
	$canIhaveAccess = $canIhaveAccess + checklevel('admin');
	
	if($canIhaveAccess == 1)
	{	
		if ($_POST['submit'])
		{
			if ($_REQUEST['subscribe2comments_level'])
			    $level = join(',',$_REQUEST['subscribe2comments_level']);
			if ($_REQUEST['subscribe2comments_profile_level'])
			    $level1 = join(',',$_REQUEST['subscribe2comments_profile_level']);

			$_REQUEST = str_replace('"',"'",$_REQUEST);
			misc_data_update('cs_from', $db->escape($_REQUEST['subscribe2comments_from']));
			misc_data_update('cs_from_email', $db->escape($_REQUEST['subscribe2comments_from_email']));
			misc_data_update('cs_subject', $db->escape($_REQUEST['subscribe2comments_subject']));
			misc_data_update('cs_background', $db->escape($_REQUEST['subscribe2comments_backgroundcolor']));
			misc_data_update('cs_fontcolor', $db->escape($_REQUEST['subscribe2comments_fontcolor']));
		}
		// breadcrumbs
		$main_smarty->assign('navbar_where', $navwhere);
		$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel'));
		// breadcrumbs
		define('modulename', 'subscribe2comments'); 
		$main_smarty->assign('modulename', modulename);
		
		define('pagename', 'admin_modifysubscribe2comments'); 
		$main_smarty->assign('pagename', pagename);

		$main_smarty->assign('subscribe2_settings', subscribe2comments_settings());
		$main_smarty->assign('tpl_center', subscribe2comments_tpl_path . 'subscribe2comments_main');
		$main_smarty->display($template_dir . '/admin/admin.tpl');
	}
	else
	{
		header("Location: " . getmyurl('login', $_SERVER['REQUEST_URI']));
	}
}	

// 
// Read module settings
//
function subscribe2comments_settings()
{
    return array(
		'from' => get_misc_data('cs_from'), 
		'from_email' => get_misc_data('cs_from_email'), 
		'subject' => get_misc_data('cs_subject'),
		'background' => get_misc_data('cs_background'),
		'fontcolor' => get_misc_data('cs_fontcolor'),
		);
}

function subscribe2comments_profile_save(){
	global $user, $users_extra_fields_field;
	$user->extra['auto_comment_alert']=sanitize($_POST['auto_comment_alert']);	
}

function subscribe2comments_profile_show(){
	global $main_smarty, $user, $users_extra_fields_field;
	$main_smarty->assign('subscribe2comments', $user->extra_field['auto_comment_alert']);
}
?>