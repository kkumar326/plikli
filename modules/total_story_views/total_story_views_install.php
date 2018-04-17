<?php
	$module_info['name'] = 'Total Story Views';
	$module_info['desc'] = 'Displays the total page views of each story in the link_summary and the sidebar.';
	$module_info['version'] = 1.0;
	$module_info['settings_url'] = '../module.php?module=total_story_views';
	$module_info['update_url'] = '';
	$module_info['homepage_url'] = 'https://www.plikli.com/mods/total_story_views.zip';
	$module_info['creator'] = 'redwine';
	
	$module_info['db_add_table'][]=array(
	'name' => table_prefix . "story_views",
	'sql' => "CREATE TABLE IF NOT EXISTS `".table_prefix . "story_views` (
			  `view_link_id` int(11) NOT NULL DEFAULT '0',
			  KEY `view_link_id` (`view_link_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	if (get_misc_data('total_views_place')=='')
	{
		global $db;
		$db->query("REPLACE INTO `".table_prefix . "misc_data` (`name`,`data`) 
			VALUES 
            ('total_views_place', 'story_total_views_custom'),
			('total_views_sidebar', 'off'),
			('total_views_count', '5');");
	}

?>