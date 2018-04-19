<?php
// the path to the module. the probably shouldn't be changed unless you rename the scheduled_posts folder(s)
define('scheduled_posts_path', my_plikli_base . '/modules/scheduled_posts/');

// the path to the module. the probably shouldn't be changed unless you rename the scheduled_posts folder(s)
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

//define('scheduled_posts_lang_conf', lang_loc .'/modules/scheduled_posts/lang.conf');
//define('scheduled_posts_plikli_lang_conf', lang_loc . "/languages/lang_" . plikli_language . ".conf");

// the path to the modules templates. the probably shouldn't be changed unless you rename the scheduled_posts folder(s)
//define('scheduled_posts_tpl_path', '../modules/scheduled_posts/templates/');

// don't touch anything past this line.
if(isset($main_smarty) && is_object($main_smarty)){
	$main_smarty->assign('scheduled_posts_path', scheduled_posts_path);
	//$main_smarty->assign('scheduled_posts_plikli_lang_conf', scheduled_posts_plikli_lang_conf);
	//$main_smarty->assign('scheduled_posts_lang_conf', scheduled_posts_lang_conf);
	//$main_smarty->assign('scheduled_posts_places', $scheduled_posts_places);
	//$main_smarty->assign('scheduled_posts_tpl_path', scheduled_posts_tpl_path);
}

?>