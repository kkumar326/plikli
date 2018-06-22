<?php

if(!defined('mnminclude')){header('Location: ../error_404.php');die();}

class CSRF {

	// options
	/**********************************
	Redwine: the $do_log option, when set to true, logs to a file in the /logs/ folder. It is for your information on how and what TOKEN is created on each page that requires a CSRF TOKEN!
	IMPORTANT: ONLY USE ON THE DEVELOPMENT LOCALHOST AND KEEP IN MIND THAT THIS FILE GET LARGE IN SIZE VERY QUICKLY!!!
	**********************************/
	var $do_log = false;  // log to file
	
	/**********************************
	Redwine: the $do_debug option, when set to true, logs ON THE SCREEN. It is for your information on how and what TOKEN is created on each page that requires a CSRF TOKEN!
	IMPORTANT: ONLY USE ON THE DEVELOPMENT LOCALHOST BECAUSE IT OUTPUTS ALL THE INFORMATION ON THE SCREEN!!!
	**********************************/
	var $do_debug = false;  // output debug info
	
	/**********************************
	Redwine: the $do_debug_only_if_user option, when a value is entered (make sure to only enter your admin user_login), ONLY this user can log to a file or output on the screen.
	IN OTHER WORDS:
	IF $do_debug_only_if_user IS EMPTY, NO LOGGING OCCURS.
	IF user_login ENTERED IS NOT AN ADMIN, NO LOGGING OCCURS!
	**********************************/
	var $do_debug_only_if_user = '';

	// define other variables *** NOT IN USE CURRENTLY ***
	//var $datalog = ''; // an array of all the log items

	function __construct(){
		$this->start();
	}

	function start(){
		//session_start();
		$this->log('Starting CSRF');
	}

	function create($name, $assign = false, $assign_extra = false){
		// token_admin_users_resetpass -- token_time_admin_users_resetpass
		//mcrypt_create_iv (PHP 4, PHP 5, PHP 7 < 7.2.0, PECL mcrypt >= 1.0.0)
		//bin2hex (PHP 4, PHP 5, PHP 7)
		//openssl_random_pseudo_bytes (PHP 5 >= 5.3.0, PHP 7)
		if (function_exists('mcrypt_create_iv')) {
			$_SESSION['token_' . $name] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		} else {
			$_SESSION['token_' . $name] = bin2hex(openssl_random_pseudo_bytes(32));
		}
		$_SESSION['token_time_' . $name] = time();
		$this->log('Creating tokens for: ' . $name . ' -- Creation time: ' . $_SESSION['token_time_' . $name]);

		if($assign == true){
			$this->smarty($name);
		}

		if($assign_extra == true){
			$this->create_hidden_field($name, true);
			$this->create_uri($name, true);
		}
	}

	function get_value($name){
		$value = $_SESSION['token_' . $name];
		$this->log('Getting token value of: ' . $name . ' value: ' . $value);
		return $value;
	}

	function get_time($name){
		if (isset($_SESSION['token_time_' . $name])) {
			$value = $_SESSION['token_time_' . $name];
			$this->log('Getting token time of: ' . $name . ' value: ' . $value);
			return $value;
		}
	}

	function smarty($name){
		// assign the token to a smarty variable
		global $main_smarty;
		$main_smarty->assign('token_' . $name, $this->get_value($name));
		$this->log('Assigning token to smarty: ' . $name);
	}

function check_valid($token, $name) {
	//see if the $token matches what the token was previously set to.
	/***************************************************************
	hash_equals (PHP 5 >= 5.6.0, PHP 7)
	Redwine: for backward compatibility, I am using a function that is equal to hash_equals, I found at https://github.com/indigophp/hash-compat. I tweaked a bit to fit our needs!
	This user function only runs if PHP version is < 5.6
	***************************************************************/
	$score_valid = 0;
	$score_expired = 0;
	if (!function_exists('hash_equals')) {
		defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));
		/**
		 * hash_equals — Timing attack safe string comparison
		 *
		 * Arguments are null by default, so an appropriate warning can be triggered
		 *
		 * @param string $known_string
		 * @param string $user_string
		 *
		 * @link http://php.net/manual/en/function.hash-equals.php
		 *
		 * @return boolean
		 */
		function hash_equals($token = null, $name = null)
		{
			$argc = func_num_args();
			// Check the number of arguments
			if ($argc < 2) {
				//trigger_error(sprintf('hash_equals() expects exactly 2 parameters, %d given', $argc), E_USER_WARNING);
				return null;
			}

			// Check $known_string type
			if (!is_string($token)) {
				//trigger_error(sprintf('hash_equals(): Expected known_string to be a string, %s given', strtolower(gettype($token))), E_USER_WARNING);
				return false;
			}

			// Check $user_string type
			if (!is_string($name)) {
				//trigger_error(sprintf('hash_equals(): Expected user_string to be a string, %s given', strtolower(gettype($name))), E_USER_WARNING);
				return false;
			}

			// Ensures raw binary string length returned
			$strlen = function($string) {
				if (USE_MB_STRING) {
					return mb_strlen($string, '8bit');
				}
				return strlen($string);
			};

			// Compare string lengths
			if (($length = $strlen($token)) !== $strlen($name)) {
				return false;
			}
			$diff = 0;
			// Calculate differences
			for ($i = 0; $i < $length; $i++) {
				$diff |= ord($token[$i]) ^ ord($name[$i]);
			}
			return $diff === 0;
		}
	}
	/***************************************************************
	Redwine: calling hash_equals function, the built-in or the user's created one expects a return of 1 (equal = true) or 0 (equal = false).
	The same for calling check_expired function, returns 1 (expired = false) or 0 (expired = true)
	The check_valid function returns the cumulative returned total of both hash_equals and check_expired functions. The page that called check_valid function receives it and execute accordingly!
	***************************************************************/
	$score_valid = hash_equals($token, $_SESSION['token_' . $name]);
	if ($score_valid == 1) {
		$this->log("\n\t\tFrom check_valid:\n\t\ttoken: " . $name . ' matches'); 
	}else{
		$this->log("\n\t\tFrom check_valid:\n\t\ttoken: " . $name . ' does not match: ' . $token);
	}
	$this->log("\n\t\tFrom check_valid:\n\t\ttoken: ".$token. " ====> ". $_SESSION['token_' . $name]);
	$score_expired = $this->check_expired($name);
	if ($score_expired == 1) {
		$this->log("\n\t\tFrom check_valid:\n\t\ttoken: " . $name . ' has not expired'); 
	}else{
		$this->log("\n\t\tFrom check_valid:\n\t\ttoken: " . $name . ' has expired -- token created: ' . $this->get_time($name) . ' -- token age: ' . $token_age);
	}
	return $score_valid + $score_expired;
}
	
	function check_expired($name, $time = 300){
		// check to see if time (seconds) has passed since the token was created.
		$token_age = time() - $this->get_time($name);
		if ($token_age >= $time) {
			// delete the tokens in session for this person
			unset($_SESSION['token_' . $name]);
			unset($_SESSION['token_time_' . $name]);
			$this->log("\n\t\tFrom check_expired:\n\t\ttoken: " . $name . ' has expired -- token created: ' . $this->get_time($name) . ' -- token age: ' . $token_age); 
			return 0;
		} else {
			$this->log("\n\t\tFrom check_expired:\n\t\ttoken: " . $name . ' has not expired'); 
			return 1;
		}
	}

	function log($action){
		global $current_user;
		if ($this->do_debug_only_if_user != '' && ($this->do_debug_only_if_user == $current_user->user_login && $current_user->user_level == 'admin')) {
		
			if($this->do_log == true){
				$this->datalog[] = $action;
				$fh=fopen(mnmpath.'/logs/log_csrf.log',"a");
				fwrite($fh,$action . "\n");
				fclose($fh);
			}
			if($this->do_debug == true){
				echo $action . '<br />';
			}
		}
	}

	function create_hidden_field($name, $assign = false){
		// creates the HTML for a hidden text field with the token
		// assigns to smarty if $assign == true
		$field = '<input type="hidden" name="token" value="' . $this->get_value($name) . '">';
		$this->log('created hidden field for token: ' . $name); 
		if($assign == true){
			global $main_smarty;
			$main_smarty->assign('hidden_token_' . $name, $field);
			$this->log('Assigning to smarty hidden field for token: ' . $name . ' to ' . $field); 
		}
		return $field;
	}

	function create_uri($name, $assign = false){
		// creates the URL code for a token
		// ex: &token=tokenIDhere
		// and assigns to smarty if $assign == true
		$uri = '&token=' . $this->get_value($name);
		$this->log('created uri for token: ' . $name); 
		if($assign == true){
			global $main_smarty;
			$main_smarty->assign('uri_token_' . $name, $uri);
			$this->log('Assigning to smarty uri for token: ' . $name . ' to ' . $uri); 
		}
		return $uri;
	}

	function show_invalid_error($steps_back){
		echo '<div style="text-align: center;background-color:#5cb85c;border-color:#4cae4c;width: 650px;padding: 20px;border: 1px solid #000000;margin: auto;"><span style="display:inline-block;background-color:#FFC6C6;border:1px solid #000000;color:#C80700;padding:20px;">Do not be alarmed. It could be a blocked invalid token (hack attempt) or just a timeout.<br />YOU ARE SAFE!<br />Go <a href = "javascript:history.go(-' . $steps_back . ')">back</a>, REFRESH that page, and try again.</span></div>';
	}
}
?>