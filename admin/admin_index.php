<?php

include_once('../internal/Smarty.class.php');
$main_smarty = new Smarty;

include('../config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'user.php');
include(mnminclude.'smartyvariables.php');
check_referrer();

// require user to log in
force_authentication();

// restrict access to admins and moderators
$amIadmin = 0;
$amIadmin = $amIadmin + checklevel('admin');
$main_smarty->assign('amIadmin', $amIadmin);

$canIhaveAccess = 0;
$canIhaveAccess = $canIhaveAccess + checklevel('admin');
$canIhaveAccess = $canIhaveAccess + checklevel('moderator');

$is_moderator = checklevel('moderator'); // Moderators have a value of '1' for the variable $is_moderator
if ($is_moderator == '1'){
	header("Location: ./admin_links.php"); // Redirect moderators to the submissions page, since they can't use the admin homepage widgets
	die();
}

if($canIhaveAccess == 0){	
	header("Location: " . getmyurl('admin_login', $_SERVER['REQUEST_URI']));
	die();
}

if (isset($_GET['action']) && $_GET['action']=='move')
{
	$column = $_GET['left']<600 ? 'left' : 'right';
	if (!is_numeric($_GET['id'])) die("Wrong parameter 'id'");
	if (!is_numeric($_GET['top'])) die("Wrong parameter 'top'");
/* Redwine: using php explode() instead of the deprecated split() which generates warnings. */
	$list = explode(',',$_GET['list']);
	foreach ($list as $item)
	    if ($item && is_numeric($item))
		$db->query($sql="UPDATE ".table_widgets." SET `position`=".(++$i).", `column`='$column' WHERE id=$item");
	/* Redwine: extra query. add the update column to the query above */
	//$db->query($sql="UPDATE ".table_widgets." SET `column`='$column' WHERE id={$_GET['id']}");
	exit;
}
elseif (isset($_GET['action']) && $_GET['action']=='minimize')
{
	if (!is_numeric($_GET['id'])) die("Wrong parameter 'id'");

	$db->query($sql="UPDATE ".table_widgets." SET `display`='".$db->escape($_GET['display'])."' WHERE id={$_GET['id']}");
	exit;
}

// misc smarty
$main_smarty->assign('isAdmin', $canIhaveAccess);

// sidebar
//$main_smarty = do_sidebar($main_smarty);

// breadcrumbs and page titles
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel');
$navwhere['link1'] = getmyurl('admin', '');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel'));

// Database Size
include_once('../libs/dbconnect.php');
/* Redwine: changed the query to mysqli to be compliant with php 5.5+ and added a filter to just get the size of tables belonging to the current plikli site */
function CalcFullDatabaseSize($database, $db) {
   $result = $db->query("SELECT CONCAT(GROUP_CONCAT(table_name) , ';' ) AS statement FROM information_schema.tables WHERE table_schema = '" . EZSQL_DB_NAME. "' AND table_name LIKE  '" .table_prefix."%'");
	if (!$result) { return -1; }
	$arraytables = $result->fetch_array(MYSQLI_ASSOC);
	$mytables = explode(",",$arraytables['statement']);
    $size = 0;
	
    foreach ($mytables as $tname) {
        $r = $db->query("SHOW TABLE STATUS FROM ".$database." LIKE '".$tname."'");
        $data = $r->fetch_array(MYSQLI_ASSOC);
        $size += ($data['Index_length'] + $data['Data_length']);
    }
 
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
    return round($size, 2).$units[$i];
}
/* Redwine: creating a mysqli connection */
$handle = new mysqli(EZSQL_DB_HOST,EZSQL_DB_USER,EZSQL_DB_PASSWORD);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
// get the size of all tables in this database:
$dbsize = CalcFullDatabaseSize(EZSQL_DB_NAME, $handle);
$handle->close();

/* Redwine: added to /libs/smartyvriables.php */
/*$votes = $db->get_var('SELECT count(*) from ' . table_votes . ' where vote_type="links";');
$main_smarty->assign('votes', $votes);

$comments = $db->get_var('SELECT count(*) from ' . table_comments . ';');
$main_smarty->assign('comments', $comments);*/

/*$sql = mysql_query("SELECT link_id,link_date FROM " . table_links . " ORDER BY link_date DESC LIMIT 1");
    while ($rows = mysql_fetch_array($sql)) {
		$link_date = txt_time_diff(unixtimestamp($rows['link_date']));
		$main_smarty->assign('link_date', $link_date . ' ' . $main_smarty->get_config_vars('PLIKLI_Visual_Comment_Ago'));
		$main_smarty->assign('link_id', $rows['link_id']);
	}
		
$sql = mysql_query("SELECT link_id,comment_id,comment_link_id,comment_date FROM " . table_comments . "," . table_links . " WHERE comment_link_id = link_id ORDER BY comment_date DESC LIMIT 1");
	while ($rows = mysql_fetch_array($sql)) {
		$comment_date = txt_time_diff(unixtimestamp($rows['comment_date']));
		$main_smarty->assign('comment_date', $comment_date . ' ' . $main_smarty->get_config_vars('PLIKLI_Visual_Comment_Ago'));
		$main_smarty->assign('link_id', $rows['link_id']);
		$main_smarty->assign('comment_id', $rows['comment_id']);
	}*/

// read the mysql database to get the plikli version
/* Redwine: plikli version query removed and added to /libs/smartyvriables.php */
/*$sql = "SELECT data FROM " . table_misc_data . " WHERE name = 'plikli_version'";
$plikli_version = $db->get_var($sql);
$main_smarty->assign('version_number', $plikli_version);*/

// pagename
define('pagename', 'admin_index'); 
$main_smarty->assign('pagename', pagename);


$widgets = $db->get_results($sql='SELECT * from ' . table_widgets . ' where enabled=1 ORDER BY position',ARRAY_A);
$main_smarty->assign('plikli_lang_conf',lang_loc . "/languages/lang_" . plikli_language . ".conf");
#$db->cache_queries = false;
if($widgets){
	// for each module...
	for($i=0; $i<sizeof($widgets); $i++) 
	{
		$file = '../widgets/' . $widgets[$i]['folder'] . '/' . 'init.php';
		$widget = array();
		if (file_exists($file)) 
		{
			include_once($file);
			$widgets[$i]['settings'] = '../widgets/'.$widgets[$i]['folder'].'/templates/settings.tpl';
			$widgets[$i]['main'] = '../widgets/'.$widgets[$i]['folder'].'/templates/widget.tpl';
			if (file_exists('../widgets/'.$widgets[$i]['folder'].'/lang_' . plikli_language . '.conf'))
			    $widgets[$i]['lang_conf'] = '../widgets/'.$widgets[$i]['folder'].'/lang_' . plikli_language . '.conf';
			elseif (file_exists('../widgets/'.$widgets[$i]['folder'].'/lang.conf'))
			    $widgets[$i]['lang_conf'] = '../widgets/'.$widgets[$i]['folder'].'/lang.conf';
			$widgets[$i] = array_merge($widgets[$i],$widget);
		}
		else
			array_splice($widgets,$i--,1);
	}
	$main_smarty->assign('widgets',$widgets);
}


// show the template
$main_smarty->assign('tpl_center', '/admin/home');
$main_smarty->display('/admin/admin.tpl');

?>