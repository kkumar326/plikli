<?php

global $scheduled_settings;
$scheduled_settings = get_scheduled_posts_settings();

function check_scheduled() {
	global $db, $scheduled_settings;
	
	/*************
	Redwine: first, we check if the Allow_Scheduled is set to true in the dashboard; if it is true, then we check in the misc_data table for the date the module last ran. If the value is empty or not equal to today's date, then the module will run and updates the date value in the misc_data table to the current date. If no scheduled posts were found, it also updates the date value to prevent it from running again in the same day. If it is false, then we save the queries and the module won't run.
	*************/
	
	if (Allow_Scheduled == 1) {
		if ($scheduled_settings['run'] == '' || $scheduled_settings['run'] != date('Y/m/d')) {
			$scheduled_available = $db->get_results("SELECT * FROM `".table_prefix."links` WHERE `link_status` = 'scheduled' and DATE_FORMAT(`link_date`, '%Y-%m-%d') = CURDATE();");
			if ($scheduled_available) {
				include_once(mnminclude.'link.php');
				foreach($scheduled_available as $sch_available) {
					
					$mylink = new Link;
					$mylink->id = $sch_available->link_id;
					$mylink->read();
					echo $mylink->status;
					totals_adjust_count("$mylink->status", -1);
					totals_adjust_count('new', 1);
					$mylink->status = 'new';
					/*Redwine: we also don't want to penalize the article if the days_to_publish variable is set for a number of days (default is 10 days), so we have to change the link_date and link_published_date to the current date and time. */
					$mylink->date = time();
					$mylink->published_date = time();
					$mylink->check_should_publish();
					$mylink->store();
				}
				misc_data_update('scheduled_run', date('Y/m/d'));
			}else{
				misc_data_update('scheduled_run', date('Y/m/d'));
			}
		}
	}
}


function get_scheduled_posts_settings() {
	$settings_scheduled = array();
	global $db;
	$sql = "SELECT * FROM  `".table_prefix."misc_data` WHERE `name` like 'scheduled_%'";
	$misc_settings = $db->get_results($sql);
	foreach($misc_settings as $misc_setting) {
		$settings_scheduled[str_replace('scheduled_', '', $misc_setting->name)] = $misc_setting->data;
	}
	return $settings_scheduled;
}