<?php

// the path to the module. the probably shouldn't be changed unless you rename the ckeditor folder(s)
define('ckeditor_path', my_plikli_base . '/modules/ckeditor/');

// the path to the module. the probably shouldn't be changed unless you rename the module_store folder(s)
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
define('ckeditor_lang_conf', lang_loc . '/modules/ckeditor/lang.conf');
define('ckeditor_plikli_lang_conf', lang_loc . "/languages/lang_" . plikli_language . ".conf");

// the path to the modules templates. the probably shouldn't be changed unless you rename the ckeditor folder(s)
define('ckeditor_tpl_path', '../modules/ckeditor/templates/');

// don't touch anything past this line.

if(is_object($main_smarty)){
	$main_smarty->assign('ckeditor_path', ckeditor_path);
	$main_smarty->assign('ckeditor_lang_conf', ckeditor_lang_conf);
	$main_smarty->assign('ckeditor_tpl_path', ckeditor_tpl_path);
}

?>