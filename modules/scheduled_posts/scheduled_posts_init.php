<?php
if(defined('mnminclude')){
	include_once('scheduled_posts_settings.php');

	// tell plikli what pages this modules should be included in
	// pages are <script name> minus .php
	// index.php becomes 'index' and new.php becomes 'new'
	$include_in_pages = array('index');
	$do_not_include_in_pages = array();
		
	if( do_we_load_module() ) {		
		module_add_action('index_top', 'check_scheduled', '');
		include_once(mnmmodules . 'scheduled_posts/scheduled_posts_main.php');
	}
}
?>