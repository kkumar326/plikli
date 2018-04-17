<?php
	include_once('total_story_views_settings.php');

	// tell plikli what pages this modules should be included in
	// pages are <script name> minus .php
	// index.php becomes 'index' and new.php becomes 'new'
	$do_not_include_in_pages = array();
	
	
	$include_in_pages = array('module');
	if( do_we_load_module() ) {		

		$moduleName = $_REQUEST['module'];
		if($moduleName == 'total_story_views'){
			module_add_action('module_page', 'total_story_views_showpage', '');
		
			include_once(mnmmodules . 'total_story_views/total_story_views_main.php');
		}
	}
	
	$include_in_pages = array('all');
	if( do_we_load_module() ) {		
		include_once(mnmmodules . 'total_story_views/total_story_views_main.php');
		module_add_action('lib_link_summary_fill_smarty', 'get_total_views', '');
		//Redwine: this below is needed to show the module's name and link in the left sidebar.
		module_add_action_tpl('tpl_header_admin_main_links', total_story_views_tpl_path . 'total_story_views_admin_link.tpl');
		// show the total views of the story under its title 
		module_add_action_tpl($total_views_settings['place'], total_story_views_tpl_path . 'total_story_views_in_summary.tpl');
		if ($total_views_settings['sidebar'] == 'on') {
		module_add_action_tpl('widget_sidebar', total_story_views_tpl_path . 'side_bar_total_story_views.tpl',array('weight'=>1));
		}
		
	}
	
	$include_in_pages = array('story');
	if( do_we_load_module() ) {		
		include_once(mnmmodules . 'total_story_views/total_story_views_main.php');
		//To add 1 view every time the story page is loaded
		module_add_action_tpl('tpl_plikli_story_tab_end', total_story_views_tpl_path . 'total_story_views_add_views.tpl');
		
		// show the total views of the story under its title 
		module_add_action_tpl($total_views_settings['place'], total_story_views_tpl_path . 'total_story_views_in_summary.tpl');
		if ($total_views_settings['sidebar'] == 'on') {
		module_add_action_tpl('widget_sidebar', total_story_views_tpl_path . 'side_bar_total_story_views.tpl',array('weight'=>1));
		}
	}
?>