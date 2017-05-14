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
	$db->query("INSERT INTO `". table_prefix . "story_views` (`view_link_id`) Values (".$link_id.")");

{/php}
{config_load file=total_story_views_kliqqi_lang_conf}