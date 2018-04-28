{config_load file=total_story_views_lang_conf}
{php}
global $main_smarty, $db, $the_template;
include_once('config.php');
	include_once(mnminclude.'html1.php');
	include_once(mnminclude.'link.php');
	include_once(mnminclude.'tags.php');
	include_once(mnminclude.'smartyvariables.php');
	
	$link_id = $main_smarty->get_template_vars('link_id'); //$vars['link_id'];
	// add a view to the story
	$sql_view_link_id = "SELECT  `view_link_id` FROM ". table_prefix . "story_views WHERE view_link_id = ".$link_id;
	if ($db->get_var($sql_view_link_id)) {
		$db->query("UPDATE ". table_prefix . "story_views SET `view_link_count` =  `view_link_count` + 1 WHERE view_link_id = ".$link_id.";");
	}else{
		$db->query("INSERT INTO ". table_prefix . "story_views (`view_link_id`, `view_link_count`) Values (".$link_id.", 1)");
	}

{/php}
{config_load file=total_story_views_plikli_lang_conf}