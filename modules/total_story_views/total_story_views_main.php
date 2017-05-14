<?php

global $settings;
$settings = get_total_views_settings();

function total_story_views_showpage(){
	global $db, $main_smarty, $the_template,$settings;
		
	include_once('config.php');
	include_once(mnminclude.'html1.php');
	include_once(mnminclude.'link.php');
	include_once(mnminclude.'tags.php');
	include_once(mnminclude.'smartyvariables.php');
	
	force_authentication();
	$canIhaveAccess = 0;
	$canIhaveAccess = $canIhaveAccess + checklevel('admin');
	
	if($canIhaveAccess == 1)
	{	
		// Save settings
		if ($_POST['submit']) {
			$key_values = ''; //Redwine: the string that will hold the values formatted for mysql.
			$size_request = sizeof($_POST) -1; //Redwine: substracting the $_POST['submit'] of the form 
			$counter = 1; //Redwine: to know how we have to end the Values string; by a comma or semicolon if it is the last $_POST array element.
			foreach($_POST as $key => $value) {
				if ($key != 'submit') {
					if ($counter == $size_request) {
						$endValues = ";";
					}else{
						$endValues = ",";
					}
					if (is_array($value)) {
						$key_values .= "('".$key."', '".implode(",",sanitize($value, 3))."')".$endValues;
					}else{
						$key_values .= "('".$key."', '".sanitize($value,3)."')".$endValues;
					}
					$counter++;
				}
				
			}
			$db->query("REPLACE INTO `".table_prefix . "misc_data` (`name`,`data`) VALUES".$key_values);
			header("Location: ".my_kliqqi_base."/module.php?module=total_story_views");
			die();
		}
		// breadcrumbs
			$navwhere['text1'] = $main_smarty->get_config_vars('KLIQQI_Visual_Header_AdminPanel');
			$navwhere['link1'] = getmyurl('admin', '');
			$navwhere['text2'] = "Modify Total Story Views";
			$navwhere['link2'] = my_kliqqi_base . "/module.php?module=total_story_views";
			$main_smarty->assign('navbar_where', $navwhere);
			$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('KLIQQI_Visual_Header_AdminPanel'));
		// breadcrumbs
		define('modulename', 'total_story_views'); 
		$main_smarty->assign('modulename', modulename);
		
		define('pagename', 'admin_modifytotalviews'); 
		$main_smarty->assign('pagename', pagename);
		$main_smarty->assign('settings', str_replace('"','&#034;',get_total_views_settings()));
		$main_smarty->assign('places',$upload_places);
		$main_smarty->assign('tpl_center', total_story_views_tpl_path . 'total_story_views_main');
		$main_smarty->display($template_dir . '/admin/admin.tpl');
	}
	else
	{
		header("Location: " . getmyurl('login', $_SERVER['REQUEST_URI']));
	}
}
function get_total_views_settings() {
	$settings1 = array();
	global $db;
	$sql = "SELECT * FROM  `".table_prefix."misc_data` WHERE `name` like 'total_views_%'";
	$misc_settings = $db->get_results($sql);
	foreach($misc_settings as $misc_setting) {
		$settings1[str_replace('total_views_', '', $misc_setting->name)] = $misc_setting->data;
	}
	return $settings1;
}
function add_total_views(&$vars){
global $main_smarty, $db, $the_template,$settings;
include_once('config.php');
	include_once(mnminclude.'html1.php');
	include_once(mnminclude.'link.php');
	include_once(mnminclude.'tags.php');
	include_once(mnminclude.'smartyvariables.php');
	
	$link_id = $vars['link_id'];
	// add a view to the story
	$db->query("INSERT INTO ". table_prefix . "story_views (view_link_id) Values (".$link_id.")");
}

function get_total_views($vars){
	global $db, $main_smarty, $smarty, $dblang, $the_template, $linkres, $current_user,$settings;
	$link_id = $vars['smarty']->_vars['link_id'];
	$viewed = $db->get_var("SELECT count(*) from " . table_prefix . "story_views WHERE view_link_id = '" .$db->escape($link_id). "'");
	$vars['smarty']->_vars['total_link_views'] = number_format($viewed);
}
?>
