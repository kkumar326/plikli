<?php
	$module_info['name'] = 'Subscribe To Comments';
	$module_info['desc'] = 'The module provides an option to subscribe/unsubscribe to comments notifications!';
	$module_info['version'] = 2.0;
	$module_info['settings_url'] = '../module.php?module=subscribe2comments';
	$module_info['homepage_url'] = 'https://www.plikli.com/mods/subscribe2comments.zip';
	$module_info['update_url'] = '';
	$module_info['creator'] = 'Author Unknown & redwine';
	/****************** Add new columns ******************
		we use db_add_field because the function checks if the column exists; otherwise, if we use an ater add query we get an error when we uninstall and then reinstall the module.
	******************************************************/	
	$module_info['db_add_field'][]=array('users', 'auto_comment_alert', 'TINYINT',  1, '', 0, '0');

	// Add new table
	$module_info['db_add_table'][]=array(
	'name' => table_prefix . "subscribe2comments",
	'sql' => "CREATE TABLE `".table_prefix . "subscribe2comments` (
	  `notify_link_id` int(11) NOT NULL,
	  `notify_user_id` int(11) NOT NULL,
  	  UNIQUE KEY `notify_link_id` (`notify_link_id`,`notify_user_id`)
	  ) ENGINE=MyISAM DEFAULT CHARSET=utf8");	
	
	//Inserting the default background and font colors that match the defaults used for the CMS
	if (get_misc_data('cs_background')=='') {
		global $db;
		$db->query("REPLACE INTO `".table_prefix . "misc_data` (`name`,`data`) 
			VALUES 
			('cs_from', ''),
			('cs_from_email', ''),
			('cs_subject', ''),
            ('cs_background', '#183a52'),
			('cs_fontcolor', '#ffffff');");
	}
	//copying the unsubscribe.php to the root folder because we have to run the file from outside the modules folder otherwise we get multiple errors and the site won't load fully, due to many settings, namely the language files.
	if (!file_exists(mnmpath.'unsubscribe.php')) {
		if (!copy(mnmmodules.'subscribe2comments/unsubscribe.php', mnmpath. 'unsubscribe.php')) {
			echo "failed to copy 'unsubscribe.php'...\n";
		}
	}

?>