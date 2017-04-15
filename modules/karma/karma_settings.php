<?php
// the path to the module. the probably shouldn't be changed unless you rename the karma folder(s)
define('karma_path', my_kliqqi_base . '/modules/karma/');

// the path to the module. the probably shouldn't be changed unless you rename the module_store folder(s)
if(!defined('lang_loc')){
	// determine if we're in root or another folder like admin
	$pos = strrpos($_SERVER["SCRIPT_NAME"], "/");
	$path = substr($_SERVER["SCRIPT_NAME"], 0, $pos);
	if ($path == "/"){$path = "";}
	if($path != my_kliqqi_base){
		define('lang_loc', '..');
	} else {
		define('lang_loc', '.');
	}
}
	
define('karma_lang_conf', lang_loc . '/modules/karma/lang.conf');
define('karma_kliqqi_lang_conf', lang_loc . "/languages/lang_" . kliqqi_language . ".conf");

define('karma_tpl_path', '../modules/karma/templates/');

// don't touch anything past this line.
if(isset($main_smarty) && is_object($main_smarty)){
	$main_smarty->assign('karma_path', karma_path);
	$main_smarty->assign('karma_kliqqi_lang_conf', karma_kliqqi_lang_conf);
	$main_smarty->assign('karma_lang_conf', karma_lang_conf);
	$main_smarty->assign('karma_tpl_path', karma_tpl_path);
	/* Redwine: obsolete and generating warnings */
	//$main_smarty->assign('karma_places', $karma_places);
}

?>
