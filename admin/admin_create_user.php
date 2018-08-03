<?php

include_once('../internal/Smarty.class.php');
$main_smarty = new Smarty;

include('../config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'votes.php');
include(mnminclude.'tags.php');
include(mnminclude.'user.php');
include(mnminclude.'smartyvariables.php');
include(mnminclude.'csrf.php');
include(mnminclude.'document_class.php');

check_referrer();

// require user to log in
force_authentication();

// restrict access to admins and moderators
$amIadmin = 0;
$amIadmin = $amIadmin + checklevel('admin');
$main_smarty->assign('amIadmin', $amIadmin);

$canIhaveAccess = 0;
$canIhaveAccess = $canIhaveAccess + checklevel('admin');
$canIhaveAccess = $canIhaveAccess + checklevel('moderator');


if($canIhaveAccess == 0){	
//	$main_smarty->assign('tpl_center', '/admin/access_denied');
//	$main_smarty->display('/admin/admin.tpl');		
	header("Location: " . getmyurl('admin_login', $_SERVER['REQUEST_URI']));
	die();
}

if (isset($_REQUEST["mode"]) && sanitize($_REQUEST["mode"], 3) == "newuser"){
	    // Redwine: if TOKEN is empty, no need to continue, just display the invalid token error.
		if (empty($_POST['token'])) {
			$CSRF->show_invalid_error(1);
			exit;
		}
		// Redwine: if valid TOKEN, proceed. A valid integer must be equal to 2.
	    if ($CSRF->check_valid(sanitize($_POST['token'], 3), 'admin_users_create') == 2){
		$username=trim($db->escape($_POST['username']));
		$password=trim($db->escape($_POST['password']));
		$email=trim($db->escape($_POST['email']));
		$level=trim($db->escape($_POST['level']));
		$saltedpass=generatePassHash($password);
			if (!isset($username) || strlen($username) < 3) {
				$main_smarty->assign(username_error, $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_UserTooShort'));			
			}
			elseif (!preg_match('/^[a-zA-Z0-9\-]+$/', $username)) {
				$main_smarty->assign(username_error, $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_UserInvalid'));
			}
			elseif (user_exists(trim($username)) ) {
				$main_smarty->assign(username_error, $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_UserExists'));
			}
			elseif (!check_email(trim($email))) {
				$main_smarty->assign(email_error, $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_BadEmail'));
			}
			elseif (email_exists(trim($email))) {
				$main_smarty->assign(email_error, $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_EmailExists'));			
			}
			elseif (strlen($password) < 5 ) {
				$main_smarty->assign(password_error, $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_FiveCharPass'));			
			}
			else {
				$db->query("INSERT IGNORE INTO " . table_users . " (user_login, user_level, user_email, user_pass, user_date) VALUES ('$username', '$level', '$email', '$saltedpass', now())");
				echo "User Added successfully";
			}
	    // Redwine: if invalid TOKEN, display TOKEN invalid error.	
	    } else {
		$CSRF->show_invalid_error(1);
		exit;
	    }
	}
	
	?>