<?php
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
// the path to the module. the probably shouldn't be changed unless you rename the total_story_views folder(s)
define('total_story_views_path', my_plikli_base . '/modules/total_story_views/');
// the path to the module's used language file. the probably shouldn't be changed unless you rename the total_story_views folder(s)
define('total_story_views_lang_conf', lang_loc . "/modules/total_story_views/lang_" .plikli_language. ".conf");

//The Pligg used language file
define('total_story_views_plikli_lang_conf', lang_loc . "/languages/lang_" . plikli_language . ".conf");

// the path to the modules templates. the probably shouldn't be changed unless you rename the total_story_views folder(s)
define('total_story_views_tpl_path', '../modules/total_story_views/templates/');

// the path to the modules libraries. the probably shouldn't be changed unless you rename the total_story_views folder(s)
define('total_story_views_lib_path', './modules/total_story_views/libs/');

$story_view_places = array(
"tpl_plikli_story_start",
"tpl_plikli_story_end",
"tpl_plikli_story_votebox_start",
"tpl_plikli_story_votebox_end",
"tpl_plikli_story_title_start",
"tpl_plikli_story_title_end",
"tpl_link_summary_pre_story_content",
"tpl_plikli_story_body_start",
"tpl_plikli_story_body_end",
"tpl_plikli_story_body_start_full",
"tpl_plikli_story_body_end_full",
"tpl_plikli_story_tools_start",
"tpl_plikli_story_tools_end"
);
$main_smarty->assign('story_view_places', $story_view_places);
// don't touch anything past this line.

if(is_object($main_smarty)){
	$main_smarty->assign('total_story_views_path', total_story_views_path);
	$main_smarty->assign('total_story_views_lang_conf', total_story_views_lang_conf);
	$main_smarty->assign('total_story_views_plikli_lang_conf', total_story_views_plikli_lang_conf);
	$main_smarty->assign('total_story_views_tpl_path', total_story_views_tpl_path);

}

?>