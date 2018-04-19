<?php
if(defined('mnminclude')){
	include_once('simple_messaging_settings.php');

	// tell plikli what pages this modules should be included in
	// pages are <script name> minus .php
	// index.php becomes 'index' and new.php becomes 'new'
	$include_in_pages = array('all');
	$do_not_include_in_pages = array();
		
	if( do_we_load_module() ) {		

		module_add_action('all_pages_top', 'get_new_messages', '');

		// show the inbox link in the user dropdown menu
		module_add_action_tpl('tpl_plikli_profile_sort_end', simple_messaging_tpl_path . 'inbox_link_in_menu.tpl');
		// show the new message notification in the user dropdown box
		module_add_action_tpl('tpl_plikli_user_button_dropdown_end', simple_messaging_tpl_path . 'inbox_notification.tpl');

		if(isset($_REQUEST['module'])){$moduleName = $_REQUEST['module'];}else{$moduleName = '';}

		if($moduleName == 'simple_messaging'){
			module_add_action('module_page', 'simple_messaging_showpage', '');
			module_add_action_tpl('tpl_plikli_breadcrumb_end', simple_messaging_tpl_path . 'breadcrumb.tpl');
		}
	
		include_once(mnmmodules . 'simple_messaging/simple_messaging_main.php');
	}
}
?>