<?php
	$module_info['name'] = 'Total Story Views';
	$module_info['desc'] = 'Displays the total page views of each story in the link_summary and the sidebar.';
	$module_info['version'] = 1.1;
	$module_info['settings_url'] = '../module.php?module=total_story_views';
	$module_info['update_url'] = '';
	$module_info['homepage_url'] = 'https://www.plikli.com/mods/total_story_views.zip';
	$module_info['creator'] = 'redwine';
	
	//Redwine: check if table exists with data
	$table_exists = check_if_table_exists(table_prefix . "story_views");
	if ($table_exists) {
		/* Redwine: creating a mysqli connection */
		$handle = new mysqli(EZSQL_DB_HOST,EZSQL_DB_USER,EZSQL_DB_PASSWORD,EZSQL_DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		$result_column = $handle->query("SHOW COLUMNS FROM `".table_prefix . "story_views` LIKE 'view_link_count';");
		$numRows_column = $result_column->num_rows;
		if ($numRows_column < 1) {
			//create a temp table to hold the view_link_id and the cumulative view_link_count for each 
			$handle->query("CREATE TEMPORARY TABLE `".table_prefix . "story_views_temp` SELECT `view_link_id`, count(`view_link_id`) AS TTL FROM `".table_prefix . "story_views` group by `view_link_id`;");
			//Alter table story_views and add a column ew_link_count
			$handle->query("ALTER TABLE `".table_prefix . "story_views` ADD COLUMN `view_link_count` int(11) NOT NULL DEFAULT '0';");
			//truncate the story_views table to re-insert the correct data from the temp table
			$handle->query("TRUNCATE TABLE `".table_prefix . "story_views`;");
			//insert back into story_views table
			$handle->query("INSERT INTO `".table_prefix . "story_views` (`view_link_id`, `view_link_count`) SELECT `view_link_id`, TTL FROM `".table_prefix . "story_views_temp`;");
			//Drop the temp table
			$handle->query("DROP TABLE `".table_prefix . "story_views_TEMP`;");
		}
	}else{
	$module_info['db_add_table'][]=array(
	'name' => table_prefix . "story_views",
	'sql' => "CREATE TABLE IF NOT EXISTS `".table_prefix . "story_views` (
			  `view_link_id` int(11) NOT NULL DEFAULT '0',
				  `view_link_count` int(11) NOT NULL DEFAULT '0',
			  KEY `view_link_id` (`view_link_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
		if (get_misc_data('total_views_place')=='') {
		global $db;
		$db->query("REPLACE INTO `".table_prefix . "misc_data` (`name`,`data`) 
			VALUES 
            ('total_views_place', 'story_total_views_custom'),
			('total_views_sidebar', 'off'),
			('total_views_count', '5');");
	}
	}
?>