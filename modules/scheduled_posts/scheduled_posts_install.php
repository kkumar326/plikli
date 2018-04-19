<?php
	$module_info['name'] = 'Scheduled Posts';
	$module_info['desc'] = 'This module MUST ONLY be installed when "Allow Scheduled Articles" in Dashboard -> Settings -> Submit -> Allow Scheduled Articles is set to TRUE. The module checks for scheduled posts to be posted at a date you chose and posts them.';
	$module_info['version'] = 1.0;
	//$module_info['settings_url'] = '../module.php?module=scheduled_posts';
	$module_info['homepage_url'] = 'https://www.plikli.com/mods/scheduled_posts.zip';
	$module_info['update_url'] = '';
	$module_info['creator'] = 'redwine';
	
	//Adding an entry in the misc_data table
	$module_info['db_sql'][] =  "INSERT IGNORE  into " . table_misc_data . " (name,data) VALUES ('scheduled_run','')";
?>