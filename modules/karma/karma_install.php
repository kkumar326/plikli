<?php
	$module_info['name'] = 'Karma module';
/*Small type on Karma module install description. https://github.com/Pligg/pligg-cms/commit/1c825d0defd18707a8722dddeddfab7b2557bfc0*/
	$module_info['desc'] = 'Configure the karma algorithms used to generate karma scores';
	$module_info['version'] = 1.0;
	$module_info['update_url'] = '';
	$module_info['homepage_url'] = 'http://www.kliqqi.com/mods/karma.zip';
	$module_info['settings_url'] = '../module.php?module=karma';
	// this is where you set the modules "name" and "version" that is required
	// if more that one module is required then just make a copy of that line

	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_submit_story','+15')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_submit_comment','+10')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_story_publish','+50')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_story_vote','+1')";
/* Redwine: fix to some bugs in the Karma system. https://github.com/Pligg/pligg-cms/commit/737770202d22ec938465fe66e52f2ae7cdcf5240 */
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_story_unvote','-1')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_comment_vote','0')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_story_discard','-250')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_story_spam','-10000')";
	$module_info['db_sql'][] =  "INSERT  into " . table_misc_data . " (name,data) VALUES ('karma_comment_delete','-50')";

?>