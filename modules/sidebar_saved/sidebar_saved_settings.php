<?php
define('sidebar_saved_path', my_plikli_base . '/modules/sidebar_saved/');
define('sidebar_saved_tpl_path', '../modules/sidebar_saved/templates/');
define('sidebar_saved_plugins_path', 'modules/sidebar_saved/plugins');
if(is_object($main_smarty)){
	$main_smarty->assign('sidebar_saved_path', sidebar_saved_path);
	/* Redwine: obsolete and generating warnings */
	//$main_smarty->assign('sidebar_saved_lang_conf', sidebar_saved_lang_conf);
	$main_smarty->assign('sidebar_saved_tpl_path', sidebar_saved_tpl_path);
}

?>