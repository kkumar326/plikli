<?php
include_once('ckeditor_settings.php');

$do_not_include_in_pages = array();

$include_in_pages = array('all');
if( do_we_load_module() ) {		
	if(is_object($main_smarty)){
		$main_smarty->plugins_dir[] = ckeditor_plugins_path;
		module_add_action_tpl('tpl_plikli_submit_step2_start', ckeditor_tpl_path . 'ckeditor1.tpl');
		//module_add_action_tpl('tpl_plikli_submit_step2_end', ckeditor_tpl_path . 'ckeditor2.tpl');
		module_add_action_tpl('submit_step_2_pre_extrafields', ckeditor_tpl_path . 'ckeditor2.tpl');
		module_add_action_tpl('tpl_plikli_story_comments_submit_start', ckeditor_tpl_path . 'ckeditor1.tpl');
		module_add_action_tpl('tpl_plikli_story_comments_submit_end', ckeditor_tpl_path . 'ckeditor-comment.tpl');
	}
}

$include_in_pages = array('module');
if( do_we_load_module() ) {		

	$moduleName = $_REQUEST['module'];

	if($moduleName == 'ckeditor'){
		module_add_action('module_page', 'ckeditor_showpage', '');
	
		include_once(mnmmodules . 'ckeditor/ckeditor_main.php');
	}
}
?>