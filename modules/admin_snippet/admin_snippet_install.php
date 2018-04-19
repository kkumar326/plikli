<?php
	$module_info['name'] = 'Admin Snippets';
	$module_info['desc'] = 'Easily insert code into your template file through module hooks. A great way to add analytics or advertisements.';
	$module_info['version'] = 1.5;
	$module_info['update_url'] = '';
	$module_info['homepage_url'] = 'https://www.plikli.com/mods/admin_snippet.zip';
	$module_info['settings_url'] = '../module.php?module=admin_snippet';

	
	$module_info['db_add_table'][]=array(
	'name' => table_prefix . "snippets",
	'sql' => "CREATE TABLE `".table_prefix . "snippets` (
	  `snippet_id` int(11) NOT NULL auto_increment,
	  `snippet_name` varchar(255) default NULL,
	  `snippet_location` varchar(255) NOT NULL,
	  `snippet_updated` datetime NOT NULL,
	  `snippet_order` int(11) NOT NULL,
	  `snippet_content` text,
	  `snippet_status` int(1) NOT NULL DEFAULT '1',
	  PRIMARY KEY  (`snippet_id`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
?>