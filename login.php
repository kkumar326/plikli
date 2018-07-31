<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'smartyvariables.php');

$sanitezedPOST = array();
foreach ($_POST as $key => $value) {
	if ($key == 'username') {
		if (filter_var(filter_var($value, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
			$sanitezedPOST[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
		}else{
			$sanitezedPOST[$key] = filter_var($value, FILTER_SANITIZE_STRING); 
		}
	}elseif ($key == 'processlogin') {
		if (filter_var(filter_var($value, FILTER_SANITIZE_NUMBER_INT), FILTER_VALIDATE_INT)) {
			$sanitezedPOST[$key] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
		}
	}else{
		$sanitezedPOST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
	}
}
$_REQUEST = $_POST = $sanitezedPOST;

$sanitezedGET = array();
foreach ($_GET as $key => $value) {
			$sanitezedGET[$key] = filter_var($value, FILTER_SANITIZE_STRING);
}
$_GET = $sanitezedGET;

/*Redwine: preventing the register page from loading if the user is already signed in! */
global $current_user;
if ($current_user->authenticated == 1) {
	header('Location: '.my_base_url.my_plikli_base);
}
/*Redwine: END preventing the register page from loading if the user is already signed in! */

// breadcrumbs and page title
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Breadcrumb_Login');
$navwhere['link1'] = getmyurl('loginNoVar', '');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Breadcrumb_Login'));

// sidebar
$main_smarty = do_sidebar($main_smarty);

// initialize error message variable
$errorMsg="";

// if user requests to logout
if($my_plikli_base){
	if (isset($_GET['return'])) {
		if (strpos($_GET['return'],$my_plikli_base)!==0) $_GET['return']=$my_plikli_base . '/';
	}
	if (isset($_POST['return'])) {
		if (strpos($_POST['return'],$my_plikli_base)!==0) $_POST['return']=$my_plikli_base . '/';
	}
}
if(isset($_GET["op"])){
	if(sanitize($_GET["op"], 3) == 'logout') {
		$current_user->Logout(sanitize($_GET['return'], 3));
	}
}

// if user tries to log in
if( (isset($_POST["processlogin"]) && is_numeric($_POST["processlogin"])) || (isset($_GET["processlogin"]) && is_numeric($_GET["processlogin"])) ){
	if($_POST["processlogin"] == 1) { // users logs in with username and password
		$username = sanitize(trim($_POST['username']), 3);
		$password = sanitize(trim($_POST['password']), 3);
		if(isset($_POST['persistent'])){$persistent = sanitize($_POST['persistent'], 3);}else{$persistent = '';}

		$dbusername=sanitize($db->escape($username),4);
		require_once(mnminclude.'check_behind_proxy.php');
		$lastip=check_ip_behind_proxy();
		$login=$db->get_row("SELECT *, UNIX_TIMESTAMP()-UNIX_TIMESTAMP(login_time) AS time FROM " . table_login_attempts . " WHERE login_ip='$lastip'");
		//if (!empty($login)) {
			if ($login->login_id)
			{
				$login_id = $login->login_id;
				if ($login->time < 3) $errorMsg=sprintf($main_smarty->get_config_vars('PLIKLI_Visual_Login_Error'),3);
				elseif ($login->login_count>=3)
				{
				if ($login->time < min(60*pow(2,$login->login_count-3),3600))
					$errorMsg=sprintf($main_smarty->get_config_vars('PLIKLI_Login_Incorrect_Attempts'),$login->login_count,min(60*pow(2,$login->login_count-3),3600)-$login->time);
				}
			}
			elseif (!is_ip_approved($lastip))
			{
				$db->query("INSERT INTO ".table_login_attempts." SET login_username = '$dbusername', login_time=NOW(), login_ip='$lastip'");
				$login_id = $db->insert_id;
				if (!$login_id) $errorMsg=sprintf($main_smarty->get_config_vars('PLIKLI_Visual_Login_Error'),3);
			}
		//}
		if (!$errorMsg)
		{
		    if($current_user->Authenticate($username, $password, $persistent) == false) {
		    
		    	$db->query("UPDATE ".table_login_attempts." SET login_username='$dbusername', login_count=login_count+1, login_time=NOW() WHERE login_id=".$login_id);

			$user=$db->get_row("SELECT * FROM " . table_users . " WHERE user_login = '$username' or user_email= '$username'");
			if (plikli_validate() && $user->user_lastlogin == "0000-00-00 00:00:00") {
				$errorMsg=$main_smarty->get_config_vars('PLIKLI_Visual_Resend_Email') .
					"<form method='post'>
						<div class='input-append notvalidated'>
							<input type='text' class='col-md-2' name='email'> 
							<input type='submit' class='btn btn-default' value='Send'>
							<input type='hidden' name='processlogin' value='5'/>
						</div>
					</form>";
			} else {
				$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Login_Error');
		    }
		    } else {
			$sql = "DELETE FROM " . table_login_attempts . " WHERE login_ip='$lastip' ";
			$db->query($sql);

			if(strlen(sanitize($_POST['return'], 3)) > 1) {
				$return = sanitize($_POST['return'], 3);
			} else {
				$return =  my_plikli_base.'/';
			}
			
			define('logindetails', $username . ";" . $password . ";" . $return);

			$vars = '';
			check_actions('login_success_pre_redirect', $vars);

			if(strpos($_SERVER['SERVER_SOFTWARE'], "IIS") && strpos(php_sapi_name(), "cgi") >= 0){
				echo '<SCRIPT LANGUAGE="JavaScript">window.location="' . $return . '";</script>';
				echo $main_smarty->get_config_vars('PLIKLI_Visual_IIS_Logged_In') . '<a href = "'.$return.'">' . $main_smarty->get_config_vars('PLIKLI_Visual_IIS_Continue') . '</a>';
			} else {
				header('Location: '.$return);
			}
			die;
		    }
		}
	}

	if($_POST["processlogin"] == 3) { // if user requests forgotten password
	    $email = sanitize($db->escape(trim($_POST['email'])),4);
	    if (check_email($email)){
			$user = $db->get_row("SELECT * FROM `" . table_users . "` where `user_email` = '".$email."' AND user_level!='Spammer' AND user_enabled=1");
			if($user){
				$username = $user->user_login;
				/*Redwine: we want a string of combined fields that are unknow to other than the Admin, which will be used as an identifier when the user tries to validate and reset the password.*/
				$combined = $user->user_modification . $user->user_email . $user->user_lastlogin . $user->user_ip . $user->user_lastip . $user->last_reset_request;
				//Redwine: we want to use the salt with the function generateHash to strengthen it.
				$salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
				//Redwine: saltedlogin is the hashed $combined and that will be saved in the users table and serves as the identifier.
				$saltedlogin = generateHash($combined, $salt);
	
				$to = $user->user_email;
				$subject = $main_smarty->get_config_vars("PLIKLI_Visual_Name").' '.$main_smarty->get_config_vars("PLIKLI_PassEmail_Subject");
			
				$times= time();		
						
				$body = sprintf($main_smarty->get_config_vars("PLIKLI_PassEmail_Body"), $salt, $main_smarty->get_config_vars("PLIKLI_Visual_Name")); 
				$body .="\n \n";
				$body .= $my_base_url . $my_plikli_base . '/recover.php?id=' .base64_encode($username). '&n=' . $saltedlogin;
	
				$headers = 'From: ' . $main_smarty->get_config_vars("PLIKLI_PassEmail_From") . "\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
	
			
				if(phpnum()>=5)
				    require("libs/class.phpmailer5.php");
				
				$mail = new PHPMailer();
				$mail->From = $main_smarty->get_config_vars('PLIKLI_PassEmail_From');
				$mail->FromName = $main_smarty->get_config_vars('PLIKLI_PassEmail_Name');
				$mail->AddAddress($to);
				$mail->AddReplyTo($main_smarty->get_config_vars('PLIKLI_PassEmail_From'));
				$mail->IsHTML(true);
				$mail->Subject = $subject;
				$mail->CharSet = 'utf-8';
				$mail->Body = $body;

				if(!$mail->Send()) {
				    $errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Login_Delivery_Failed');
				} else {
				    $main_smarty->assign('user_login', $user->user_login);
				    $main_smarty->assign('profile_url', getmyurl('profile'));
				    $main_smarty->assign('login_url', getmyurl('loginNoVar'));

				    $errorMsg = $main_smarty->get_config_vars("PLIKLI_PassEmail_SendSuccess");

				    $db->query("UPDATE `" . table_users . "` SET `last_reset_code` = '". $saltedlogin . "/". $salt ."', `last_reset_request` = FROM_UNIXTIME('".$times."') WHERE `user_login` = '".$username."'");

				    if (!defined ('pagename')) define('pagename', 'login');
				    $main_smarty->assign('pagename', pagename);
				    $errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Password_Sent');
				}
				
			}else{
				$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Password_Sent');
			}
		}else{
		$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_BadEmail');
	    }
	}

	if(isset($_GET["processlogin"]) && $_GET["processlogin"] == 4) { // if user clicks on the forgotten password confirmation code
		$username = $db->escape(sanitize(sanitize(trim($_GET['username']), 3), 4));
		if(strlen($username) == 0){
			$errorMsg = $main_smarty->get_config_vars("PLIKLI_Visual_Login_Forgot_Error");
		}
		else {
			$confirmationcode = sanitize($_GET["confirmationcode"], 3);
			$DBconf = $db->get_var("SELECT `last_reset_code` FROM `" . table_users . "` where `user_login` = '".$username."'");
			if($DBconf){
				if($DBconf == $confirmationcode && !empty($confirmationcode)){
					$db->query('UPDATE `' . table_users . '` SET `last_reset_code` = "" WHERE `user_login` = "'.$username.'"');
					$db->query('UPDATE `' . table_users . '` SET `user_pass` = "033700e5a7759d0663e33b18d6ca0dc2b572c20031b575750" WHERE `user_login` = "'.$username.'"');
					$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Login_Forgot_PassReset');
				}	else {
					$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Login_Forgot_ErrorBadCode');
				}
			} else {
				$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Login_Forgot_ErrorBadCode');
			} 
		}
	}

	if($_POST["processlogin"] == 5 && plikli_validate()) { // resend confirmation email
	    $email = sanitize($db->escape(trim($_POST['email'])),4);
	    if (check_email($email)){
			$user = $db->get_row("SELECT * FROM `" . table_users . "` where `user_email` = '".$email."' AND user_level!='Spammer'");
			if($user){
				/* Redwine: Fixed erroneous code ($user->karma and $user->username) that was rendering the validation impossible when users request a validation code upon login. At the same time, added in /validation.php on line 31 a query to delete the entry from the login_attempts table because the user had to wait x number of seconds based on the number of login attempts. Now, upon successful validation, this entry is deleted and users don't have to wait any more!*/
				$encode=md5($_POST['email'] . $user->user_karma .  $user->user_login. plikli_hash().$main_smarty->get_config_vars('PLIKLI_Visual_Name'));

				$domain = $main_smarty->get_config_vars('PLIKLI_Visual_Name');			
				$validation = my_base_url . my_plikli_base . "/validation.php?code=$encode&uid=".urlencode($user->user_login)."&email=".urlencode($_POST['email']);
				$str = $main_smarty->get_config_vars('PLIKLI_PassEmail_verification_message');
				eval('$str = "'.str_replace('"','\"',$str).'";');
				$message = "$str";

				if(phpnum()>=5)
					require("libs/class.phpmailer5.php");
				else
					require("libs/class.phpmailer4.php");
				$mail = new PHPMailer();
				$mail->From = $main_smarty->get_config_vars('PLIKLI_PassEmail_From');
				$mail->FromName = $main_smarty->get_config_vars('PLIKLI_PassEmail_Name');
				$mail->AddAddress($_POST['email']);
				$mail->AddReplyTo($main_smarty->get_config_vars('PLIKLI_PassEmail_From'));
				$mail->IsHTML(false);
				$mail->Subject = $main_smarty->get_config_vars('PLIKLI_PassEmail_Subject_verification');
				$mail->Body = $message;
				$mail->CharSet = 'utf-8';

#print_r($mail);					
				if(!$mail->Send())
					return false;

			}
			$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Email_Sent');
		}else{
			$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_BadEmail');
		}
	}
}   
    
// pagename
define('pagename', 'login'); 
$main_smarty->assign('pagename', pagename);
 
// misc smarty 
$main_smarty->assign('errorMsg',$errorMsg);  
$main_smarty->assign('register_url', getmyurl('register'));

// show the template
$main_smarty->assign('tpl_center', $the_template . '/login_center');
$main_smarty->display($the_template . '/plikli.tpl');

?>