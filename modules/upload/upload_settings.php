<?php
// the path to the module. the probably shouldn't be changed unless you rename the upload folder(s)
define('upload_path', my_kliqqi_base . '/modules/upload/');

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
	
define('upload_lang_conf', lang_loc . '/modules/upload/lang.conf');
define('upload_kliqqi_lang_conf', lang_loc . "/languages/lang_" . kliqqi_language . ".conf");

// the path to the modules templates. the probably shouldn't be changed unless you rename the upload folder(s)
define('upload_tpl_path', '../modules/upload/templates/');

$upload_places = array(
"tpl_kliqqi_story_start",
"tpl_kliqqi_story_votebox_start",
"tpl_kliqqi_story_votebox_end",
"tpl_kliqqi_story_title_start",
"tpl_kliqqi_story_title_end",
"tpl_link_summary_admin_links",
"tpl_link_summary_pre_story_content",
"tpl_kliqqi_story_body_start",
"tpl_kliqqi_story_body_start_full",
"tpl_kliqqi_story_body_end",
"tpl_kliqqi_story_body_end_full",
"tpl_kliqqi_story_tools_start",
"tpl_kliqqi_story_tools_end",
"tpl_link_summary_end",
"tpl_kliqqi_story_end",
"tpl_kliqqi_story_who_voted_start",
"tpl_kliqqi_story_who_voted_end",
"tpl_kliqqi_story_related_start",
"tpl_kliqqi_story_related_end",
"tpl_kliqqi_story_comments_start",
"tpl_kliqqi_story_comments_end"
);


$comment_places = array(
"tpl_kliqqi_story_comments_single_start",
"tpl_kliqqi_story_comments_single_end"
);

// don't touch anything past this line.
if(isset($main_smarty) && is_object($main_smarty)){
	$main_smarty->assign('upload_path', upload_path);
	$main_smarty->assign('upload_kliqqi_lang_conf', upload_kliqqi_lang_conf);
	$main_smarty->assign('upload_lang_conf', upload_lang_conf);
	$main_smarty->assign('upload_tpl_path', upload_tpl_path);
	$main_smarty->assign('upload_places', $upload_places);
	$main_smarty->assign('comment_places', $comment_places);
	$main_smarty->assign('upload_maxnumber', get_misc_data('upload_maxnumber'));
	$main_smarty->assign('upload_filesize', get_misc_data('upload_filesize'));
	$main_smarty->assign('upload_extensions', get_misc_data('upload_extensions'));
	$main_smarty->assign('upload_external', get_misc_data('upload_external'));
}

?>
