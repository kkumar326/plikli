<?php
$widget['widget_title'] = "Statistics";
$widget['widget_has_settings'] = 0;
$widget['widget_shrink_icon'] = 1;
$widget['widget_uninstall_icon'] = 1;
$widget['name'] = 'Statistics';
$widget['desc'] = 'This widget inserts common statistics about your website. Examples include the number of members, stories, votes, comments, and your Plikli version.';
$widget['version'] = 3.0;

$sw_version='1';
$sw_members='1';
$sw_groups='1';
$sw_links='1';
$sw_published='1';
$sw_new='1';
$sw_votes='1';
$sw_comments='1';
$sw_newuser='1';
$phpver='1';
$mysqlver='1';
$sw_dbsize='1';


/*if ($_REQUEST['widget']=='statistics'){
    if(isset($_REQUEST['version']))
		$sw_version = sanitize($_REQUEST['version'], 3);
    misc_data_update('sw_version', $sw_version);
    if(isset($_REQUEST['members']))
		$sw_members = sanitize($_REQUEST['members'], 3);
    misc_data_update('sw_members', $sw_members);
    if(isset($_REQUEST['groups']))
		$sw_groups = sanitize($_REQUEST['groups'], 3);
    misc_data_update('sw_groups', $sw_groups);
    if(isset($_REQUEST['links']))
		$sw_links = sanitize($_REQUEST['links'], 3);
    misc_data_update('sw_links', $sw_links);
    if(isset($_REQUEST['published']))
		$sw_published = sanitize($_REQUEST['published'], 3);
    misc_data_update('sw_published', $sw_published);
    if(isset($_REQUEST['new']))
		$sw_new = sanitize($_REQUEST['new'], 3);
    misc_data_update('sw_new', $sw_new);
    if(isset($_REQUEST['votes']))
		$sw_votes = sanitize($_REQUEST['votes'], 3);
    misc_data_update('sw_votes', $sw_votes);
    if(isset($_REQUEST['comments']))
		$sw_comments = sanitize($_REQUEST['comments'], 3);
    misc_data_update('sw_comments', $sw_comments);
    if(isset($_REQUEST['latestuser']))
		$sw_newuser = sanitize($_REQUEST['latestuser'], 3);
    misc_data_update('sw_newuser', $sw_newuser);
	if(isset($_REQUEST['phpver']))
		$phpver = sanitize($_REQUEST['phpver'], 3);
    misc_data_update('phpver', $phpver);
	if(isset($_REQUEST['mysqlver']))
		$mysqlver = sanitize($_REQUEST['mysqlver'], 3);
    misc_data_update('mysqlver', $mysqlver);
    if(isset($_REQUEST['dbsize']))
		$sw_dbsize = sanitize($_REQUEST['dbsize'], 3);
    misc_data_update('sw_dbsize', $sw_dbsize);
}*/

// Smarty Assign
if ($main_smarty){
    $main_smarty->assign('sw_version', $sw_version);
	$main_smarty->assign('sw_members', $sw_members);
	$main_smarty->assign('sw_groups', $sw_groups);
	$main_smarty->assign('sw_links', $sw_links);
	$main_smarty->assign('sw_published', $sw_published);
	$main_smarty->assign('sw_new', $sw_new);
	$main_smarty->assign('sw_votes', $sw_votes);
	$main_smarty->assign('sw_comments', $sw_comments);
	$main_smarty->assign('sw_newuser', $sw_newuser);
	$main_smarty->assign('sw_dbsize', $sw_dbsize);
	$main_smarty->assign('phpver', $phpver);
	$main_smarty->assign('mysqlver', $mysqlver);
	$main_smarty->assign('dbsize', $dbsize);
}
?>