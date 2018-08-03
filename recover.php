<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'smartyvariables.php');

// breadcrumbs and page title
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Breadcrumb_Recover_Password');
$navwhere['link1'] = getmyurl('loginNoVar', '');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Breadcrumb_Recover_Password'));

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

$id=sanitize($_REQUEST['id'], 3);
if (isset($_REQUEST['n'])) $n=sanitize($_REQUEST['n'], 3);
$idTemp=base64_decode($id);
$username=sanitize($idTemp ,3);

$sql="SELECT * FROM `" . table_users . "` where `user_login` = '".$username."' AND user_level!='Spammer' AND user_enabled=1";
		
$user = $db->get_row($sql);
list($hashed_fields, $secret_code) = explode('/', $user->last_reset_code);

if($user){
	if( (isset($_POST["processrecover"]) && is_numeric($_POST["processrecover"]))){
	
		if($_POST["processrecover"]==1){
			
			$error=false;
			$secret = sanitize($_POST["reg_code"], 3);
			$password = sanitize($_POST["reg_password"], 3);
			$password2 = sanitize($_POST["reg_password2"], 3);
			if ($hashed_fields != $n || $secret != $secret_code) {
				$form_password_error[] = $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_Secret_Code');
				$error = true;
				$main_smarty->assign('wrong_secret_code', true);
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
				    //$main_smarty->assign('user_login', $user->user_login);
				    //$main_smarty->assign('profile_url', getmyurl('profile'));
				    //$main_smarty->assign('login_url', getmyurl('loginNoVar'));

				    $errorMsg = $main_smarty->get_config_vars("PLIKLI_PassEmail_SendSuccess");

				    $db->query("UPDATE `" . table_users . "` SET `last_reset_code` = '". $saltedlogin . "/". $salt ."', `last_reset_request` = FROM_UNIXTIME('".$times."') WHERE `user_login` = '".$username."'");

				    if (!defined ('pagename')) define('pagename', 'recover');
				    $main_smarty->assign('pagename', pagename);
				    //$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Password_Sent');
				}
			}
			if(strlen($password) < 5 ) { // if password is less than 5 characters
				$form_password_error[] = $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_FiveCharPass');
				$error = true;
			}
			if($password !== $password2) { // if both passwords do not match
				$form_password_error[] = $main_smarty->get_config_vars('PLIKLI_Visual_Register_Error_NoPassMatch');
				$error = true;
			}	
			
			if($error==false){
			
				$saltedlogin = generateHash($username);
				$masked_password = substr($password, 0, 1) . str_repeat('x', strlen($password) - 2) . substr($password, -1);
				$to = $user->user_email;
				$subject = $main_smarty->get_config_vars("PLIKLI_Visual_Name").' '.$main_smarty->get_config_vars("PLIKLI_PassEmail_Subject");

				$body = sprintf(
					$main_smarty->get_config_vars("PLIKLI_PassEmail_Reset_Success"),
					$main_smarty->get_config_vars("PLIKLI_Visual_Name"),
					$user->user_login,
					$masked_password
				);
				
				$headers = 'From: ' . $main_smarty->get_config_vars("PLIKLI_PassEmail_From") . "\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				
				if (mail($to, $subject, $body, $headers))
				{
					$saltedPass = generatePassHash($password);
					$db->query("UPDATE `" . table_users . "` SET `user_pass` = '".$saltedPass."', `last_reset_request` = FROM_UNIXTIME('".time()."'), `last_reset_code` = '' WHERE `user_login` = '".$user->user_login."'");
				
					$current_user->Authenticate($user->user_login, $password);
					$return =  my_plikli_base.'/';
					if(strpos($_SERVER['SERVER_SOFTWARE'], "IIS") && strpos(php_sapi_name(), "cgi") >= 0){
						echo '<SCRIPT LANGUAGE="JavaScript">window.location="' . $return . '";</script>';
						echo $main_smarty->get_config_vars('PLIKLI_Visual_IIS_Logged_In') . '<a href = "'.$return.'">' . $main_smarty->get_config_vars('PLIKLI_Visual_IIS_Continue') . '</a>';
					} else {
						header('Location: '.$return);
					}
					
				} else {
				
					$errorMsg = $main_smarty->get_config_vars('PLIKLI_Visual_Login_Delivery_Failed');
					
				}
				
			}
			
			if (!empty($form_password_error)) $main_smarty->assign('form_password_error', $form_password_error);
			
		}
	  
	}

} else {

	$errorMsg = $main_smarty->get_config_vars('PLIKLI_Password_Invalid_URL');
	
}

// pagename
define('pagename', 'recover'); 
$main_smarty->assign('pagename', pagename);
 
// misc smarty 
$main_smarty->assign('errorMsg',$errorMsg); 
$main_smarty->assign('id',$id); 
$main_smarty->assign('n',$n); 


// show the template
$main_smarty->assign('tpl_center', $the_template . '/recover_password_center');
$main_smarty->display($the_template . '/plikli.tpl');

?>