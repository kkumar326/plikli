<?php

function captcha_showpage(){

	force_authentication();
	$canIhaveAccess = 0;
	$canIhaveAccess = $canIhaveAccess + checklevel('admin');

	if($canIhaveAccess == 1)
	{

		global $db, $main_smarty, $the_template;

		$navwhere['text1'] = 'Captcha';
		$navwhere['link1'] = URL_captcha;

		if (!defined('pagename')) define('pagename', 'captcha'); 
		$main_smarty->assign('pagename', pagename);
		
		// New method for identifying modules rather than pagename
		define('modulename', 'captcha'); 
		$main_smarty->assign('modulename', modulename);

		//$main_smarty = do_sidebar($main_smarty, $navwhere);

		if(isset($_REQUEST['action'])){$action = $_REQUEST['action'];}else{$action = '';}

		if($action == 'enable'){
			if(isset($_REQUEST['captcha'])){$captcha = $_REQUEST['captcha'];}else{$captcha = '';}
			enable_captcha($captcha);
		}

		if($action == 'configure')
		{
			if(isset($_REQUEST['captcha']) && !strstr($_REQUEST['captcha'], '/')) 
			{
				$captcha = $_REQUEST['captcha'];
				include_once(captcha_captchas_path . '/' . $captcha . '/main.php');
				captcha_configure();
				$main_smarty->assign('tpl_center', captcha_tpl_path . '../captchas/' . $captcha . '/captcha_configure');
				$main_smarty->display('/admin/admin.tpl');
			}
			die();
		}


		if($action == 'EnableReg'){

			$value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : '';
			/* Redwine: added proper sanitization and an extra validation check to make sure that the passed values are either true or false only. */
			if($db->escape(sanitize($value,3)) != '' && $db->escape(sanitize($value,3)) != 'false'){
				$value = 'true';				
			}else{
				$value = 'false';
			}
				misc_data_update('captcha_reg_en', $value);

			header('Location: ' . URL_captcha);

		}

		if($action == 'EnableStory'){
			$value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : '';
			/* Redwine: added proper sanitization and an extra validation check to make sure that the passed values are either true or false only. */
			if($db->escape(sanitize($value,3)) != '' && $db->escape(sanitize($value,3)) != 'false'){
				$value = 'true';				
			}else{
				$value = 'false';
			}
				misc_data_update('captcha_story_en', $value);
			header('Location: ' . URL_captcha);
		}

		if($action == 'EnableComment'){
			$value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : '';
			/* Redwine: added proper sanitization and an extra validation check to make sure that the passed values are either true or false only. */
			if($db->escape(sanitize($value,3)) != '' && $db->escape(sanitize($value,3)) != 'false'){
				$value = 'true';				
			}else{
				$value = 'false';
			}
				misc_data_update('captcha_comment_en', $value);
			header('Location: ' . URL_captcha);
		}

		$captcha = get_misc_data('captcha_method');
		if($captcha == ''){$captcha = 'recaptcha';}
		$main_smarty->assign('captcha_method', $captcha);

		$main_smarty->assign('tpl_center', captcha_tpl_path . '/captcha_home');
		$main_smarty->display('/admin/admin.tpl');
	}
	else
		header("Location: " . getmyurl('login', $_SERVER['REQUEST_URI']));
}

function enable_captcha($captcha){
	/* Redwine: because we are only using solvemedia, I added this check to validate and default to solvemedia. */
	global $db;
	if ($db->escape(sanitize($captcha,3)) != 'solvemedia') {
		$captcha = 'solvemedia';
	}
	include_once(captcha_captchas_path . '/' . $captcha . '/main.php');

	if(captcha_can_we_use()){
		
		misc_data_update('captcha_method', $captcha);
	}	

}

function captcha_register(&$vars){
		global $main_smarty, $the_template, $captcha_registered;
		if ($captcha_registered) return;
		$captcha_registered = true;

		$captcha = get_misc_data('captcha_method');
/*Redwine: Default and only method is solvemedia*/
		if($captcha == ''){$captcha = 'solvemedia';}
		include_once(captcha_captchas_path . '/' . $captcha . '/main.php');
		captcha_create('', 0);
}

function captcha_register_check_errors(&$vars){
		global $main_smarty, $the_template, $captcha_checked;
		if ($captcha_checked) return;
		$captcha_checked = true;

		$captcha = get_misc_data('captcha_method');
/*Redwine: Default and only method is solvemedia*/
		if($captcha == ''){$captcha = 'solvemedia';}

		if (isset($vars['username'])) {
			$username = $vars['username'];
		}else{
			$username = '';
		}
		if (isset($vars['email'])) {
			$email = $vars['email'];
		}else{
			$email = '';
		}
		if (isset($vars['password'])) {
			$password = $vars['password'];
		}else{
			$password = '';
		}
		$main_smarty->assign('username', $username);
		$main_smarty->assign('email', $email);
		$main_smarty->assign('password', $password);

		include_once(captcha_captchas_path . '/' . $captcha . '/main.php');
		if(captcha_check($vars)){
		} else {
			/*Redwine: needed to be able to issue a warning when solvemedia is bypassed*/
			$vars['error'] = 'register_captcha_error';
			$vars['error'] = true;
		}
}
?>