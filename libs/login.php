<?php

if(!defined('mnminclude')){header('Location: ../error_404.php');die();}
include_once mnminclude.'/check_behind_proxy.php';

class UserAuth {
	var $user_id  = 0;
	var $user_login = "";
	var $md5_pass = "";
	var $authenticated = FALSE;

	// Additional parameters for cookie security
	protected $ip, $user_agent;

	// To be implemented for proper security
	private $token, $validator;

	function __construct() {
		global $db, $cached_users, $language;
		$this->ip = check_ip_behind_proxy();
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];

		if(isset($_COOKIE['mnm_user'], $_COOKIE['mnm_key'], $_COOKIE['mnm_data']) && $_COOKIE['mnm_user'] !== '' &&
            $_COOKIE['mnm_data'] == md5(sha1($this->ip.':'.$this->user_agent)) ) {
			$userInfo=explode(":", base64_decode($db->escape($_COOKIE['mnm_key'])));
			if(crypt($userInfo[0], 22)===$userInfo[1] 
				&& $db->escape($_COOKIE['mnm_user']) === $userInfo[0]) {
				$dbusername = $db->escape($_COOKIE['mnm_user']);

				$dbuser = $db->get_row("SELECT * FROM " . table_users . " WHERE user_login = '$dbusername'");
				$cached_users[$dbuser->user_id] = $dbuser;

				if($dbuser->user_id > 0 && md5($dbuser->user_pass)==$userInfo[2] && $dbuser->user_enabled) {
					$this->user_id = $dbuser->user_id;
					$this->user_level = $dbuser->user_level;
					$this->user_login  = $userInfo[0];
					$this->md5_pass = $userInfo[2];
					$this->authenticated = TRUE;
					if ($dbuser->user_language)
						$language = $dbuser->user_language;
				}
			}
		}
	}


	function SetIDCookie($what, $remember) {
		$domain = preg_replace('/^www/','',$_SERVER['HTTP_HOST']);
		// Remove port information.
        $port = strpos($domain, ':');
        if ($port !== false)  $domain = substr($domain, 0, $port);			
		if (!strstr($domain,'.') || strpos($domain,'localhost:')===0) $domain='';
		switch ($what) {
			case 0:	// Borra cookie, logout
				setcookie ("mnm_user", "", time()-3600, "/",$domain); // Expiring cookie
				setcookie ("mnm_key", "", time()-3600, "/",$domain); // Expiring cookie
                setcookie ("mnm_data", "", time()-3600, "/",$domain); // Expiring cookie
				setcookie ("mnm_user", "", time()-3600, "/"); // Expiring cookie
				setcookie ("mnm_key", "", time()-3600, "/"); // Expiring cookie
                setcookie ("mnm_data", "", time()-3600, "/"); // Expiring cookie
				break;
			case 1: //Usuario logeado, actualiza el cookie
				// Atencion, cambiar aqu�cuando se cambie el password de base de datos a MD5
				$strCookie=base64_encode(join(':',
					array(
						$this->user_login,
						crypt($this->user_login, 22),
						$this->md5_pass)
					)
				);
				if($remember){
					$time = time() + (86400 * 30); //Cookie will expire in 30 days if remmber option is selected at login. 86400 = 1 day
				} else {
					$time = 0; //Cookie will expire when browser session ends. Note: This may depend on your browser settings. Example: in Chrome if 'Continue where you left off' option is checked the cookie won't expire.
				}

				// Setting httponly and secure true to add additional security for cookies
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                    setcookie('mnm_user', $this->user_login, $time, '/',$domain, TRUE, TRUE);
                    setcookie('mnm_key', $strCookie, $time, '/',$domain, TRUE, TRUE);
                    setcookie('mnm_data', md5(sha1($this->ip.':'.$this->user_agent)), $time, '/', $domain, TRUE, TRUE);
                } else {
                    setcookie('mnm_user', $this->user_login, $time, '/',$domain, FALSE, TRUE);
                    setcookie('mnm_key', $strCookie, $time, '/',$domain, FALSE, TRUE);
                    setcookie('mnm_data', md5(sha1($this->ip.':'.$this->user_agent)), $time, '/', $domain, FALSE, TRUE);
                }

				break;
		}
	}

	function Authenticate($username, $pass, $remember=false) {
		global $db;
		$dbusername=sanitize($db->escape($username),4);
		
		check_actions('login_start', $vars);
		$user=$db->get_row("SELECT * FROM " . table_users . " WHERE user_login = '$dbusername' or user_email= '$dbusername' ");
		/***
		Redwine: the code below is to check for the hashed password of the user logging in. If it contains the string 'bcrypt:' it means that the new password hashing has been applied to it, otherwise, it rehash the password based on the new password hashing!
		***/
		if ($user->user_id > 0 && $user->user_lastlogin != "0000-00-00 00:00:00"  && $user->user_enabled) {
			if(substr($user->user_pass, 0, 7) !== 'bcrypt:'){
				$salt = substr($user->user_pass, 0, SALT_LENGTH);
				$sha_hash = substr($user->user_pass, SALT_LENGTH);
				if (!function_exists('password_hash')) {
					require(mnminclude."password.php");
					$new_pass = 'bcrypt:' . $salt . password_hash ($sha_hash, PASSWORD_BCRYPT);
		} else {
					$new_pass = 'bcrypt:' . $salt . password_hash ($sha_hash, PASSWORD_BCRYPT);
		}
				$db->query("UPDATE ".table_users." SET user_pass = '$new_pass' WHERE user_id ='$user->user_id' LIMIT 1");
			}
		}
		/* Redwine: End checking/applying the new password hashing */
		if ($user->user_id > 0 && verifyPassHash($pass, $user->user_pass) && $user->user_lastlogin != "0000-00-00 00:00:00"  && $user->user_enabled) {
			$this->user_login = $user->user_login;  
			$this->user_id = $user->user_id;

			$vars = array('user' => serialize($this), 'can_login' => true);
			check_actions('login_pass_match', $vars);

			if($vars['can_login'] != true){return false;}

			$this->authenticated = TRUE;
			$this->md5_pass = md5($user->user_pass);
			$this->SetIDCookie(1, $remember);
			require_once(mnminclude.'check_behind_proxy.php');
			$lastip=check_ip_behind_proxy();
			$sql = "UPDATE " . table_users . " SET user_lastip = '$lastip', user_lastlogin = now() WHERE user_id = {$user->user_id} LIMIT 1";
			$db->query($sql);
			return true;
		}
		return false;
	}

	function Logout($url='./') {
		global $main_smarty;
		
		$this->user_login = "";
		$this->authenticated = FALSE;
		$this->SetIDCookie (0, '');

		define('wheretoreturn', $url);
		$vars = '';
		check_actions('logout_success', $vars);

		if (preg_match('/user\.php\?login=(.+)$/', $url, $m)) {
			$user=new User();
			$user->username = $m[1];
			if(!$user->all_stats() || $user->total_links+$user->total_comments==0) 
				$url = my_plikli_base.'/';
		}
			

		header("Cache-Control: no-cache, must-revalidate");
		if(!strpos($_SERVER['SERVER_SOFTWARE'], "IIS") && !strpos(php_sapi_name(), "cgi") >= 0){
			if(strlen(sanitize($url, 3)) > 1) {
				$url = sanitize($url, 3);
			} else {
				$url =  my_plikli_base.'/';
			}
			header("Location: $url");
		}
		header("Expires: " . gmdate("r", time()-3600));
		header("ETag: \"logingout" . time(). "\"");
		if(strpos($_SERVER['SERVER_SOFTWARE'], "IIS") && strpos(php_sapi_name(), "cgi") >= 0){
			echo '<SCRIPT LANGUAGE="JavaScript">window.location="' . $url . '";</script>';
			echo $main_smarty->get_config_vars('PLIKLI_Visual_IIS_Logged_Out') . '<a href = "'.$url.'">' . $main_smarty->get_config_vars('PLIKLI_Visual_IIS_Continue') . '</a>';
		}
		die;
	}

}

$current_user = new UserAuth();
?>