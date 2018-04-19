<?php
// the path to the module. the probably shouldn't be changed unless you rename the subscribe2comments folder(s)
define('subscribe2comments_path', my_plikli_base . '/modules/subscribe2comments/');

// the path to the module. the probably shouldn't be changed unless you rename the subscribe2comments folder(s)
	if(!defined('lang_loc')){
		// determine if we're in root or another folder like admin
			$pos = strrpos($_SERVER["SCRIPT_NAME"], "/");
			$path = substr($_SERVER["SCRIPT_NAME"], 0, $pos);
			if ($path == "/"){$path = "";}
			
			if($path != my_plikli_base){
				define('lang_loc', '..');
			} else {
				define('lang_loc', '.');
			}
	}

define('subscribe2comments_lang_conf', lang_loc .'/modules/subscribe2comments/lang.conf');
define('subscribe2comments_plikli_lang_conf', lang_loc . "/languages/lang_" . plikli_language . ".conf");

// the path to the modules templates. the probably shouldn't be changed unless you rename the subscribe2comments folder(s)
define('subscribe2comments_tpl_path', '../modules/subscribe2comments/templates/');

// don't touch anything past this line.
if(isset($main_smarty) && is_object($main_smarty)){
	$main_smarty->assign('subscribe2comments_path', subscribe2comments_path);
	$main_smarty->assign('subscribe2comments_plikli_lang_conf', subscribe2comments_plikli_lang_conf);
	$main_smarty->assign('subscribe2comments_lang_conf', subscribe2comments_lang_conf);
	$main_smarty->assign('subscribe2comments_places', $subscribe2comments_places);
	$main_smarty->assign('subscribe2comments_tpl_path', subscribe2comments_tpl_path);
}

?>