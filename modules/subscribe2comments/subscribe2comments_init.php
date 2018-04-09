<?php
if(defined('mnminclude')){
	include_once('subscribe2comments_settings.php');

	/* enter the name of the pages, you want the to include, in array(). Ex: array('published', 'index', 'story')
		Leave it empty if it should include all pages.
	*/
	$do_not_include_in_pages = array();

	$include_in_pages = array('all');
	if( do_we_load_module() ) {	
	
		/* tpl_header_admin_main_links hook is in admin.tpl. It checks if modules should have a link in the dashboard under the Modules heading in the left sidebar. */
		module_add_action_tpl('tpl_header_admin_main_links', subscribe2comments_tpl_path . 'subscribe2comments_admin_main_link.tpl');
		module_add_action('after_comment_submit', 'subscribe2comments_comment_submit', '' ) ;
		module_add_action('do_submit3', 'subscribe2comments_story_submit', '' ) ;
//module_add_action_tpl('tpl_plikli_content_start', subscribe2comments_tpl_path . 'subscribe2comments_unsubscribe.tpl');
		module_add_action_tpl('tpl_plikli_head_end', subscribe2comments_tpl_path . 'subscribe2comments_js.tpl');
		module_add_action_tpl('tpl_plikli_story_comments_individual_start', subscribe2comments_tpl_path . 'subscribe2comments_story_tools_end.tpl');


		module_add_action('profile_save', 'subscribe2comments_profile_save', '');
		module_add_action('profile_show', 'subscribe2comments_profile_show', '');
		module_add_action_tpl('tpl_user_edit_fields', subscribe2comments_tpl_path . 'subscribe2comments_center_fields.tpl');

		include_once(mnmmodules . 'subscribe2comments/subscribe2comments_main.php');
	}

	$include_in_pages = array('module');
	if( do_we_load_module() ) {		

		$moduleName = $_REQUEST['module'];

		if($moduleName == 'subscribe2comments'){
			module_add_action('module_page', 'subscribe2comments_showpage', '');
		
			include_once(mnmmodules . 'subscribe2comments/subscribe2comments_main.php');
		}
	}
}
?>