<?php
include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'group.php');
include(mnminclude.'smartyvariables.php');
include mnminclude.'extra_fields_smarty.php';

$sanitezedREQUEST = array();
foreach ($_REQUEST as $key => $value) {
	$sanitezedREQUEST[$key] = preg_replace('/[^\p{L}\p{N}-_\s\/]/u', '', $value);
}
$_GET = $_REQUEST = $sanitezedREQUEST;

$from_where = "1";
if (!checklevel('admin'))
    $from_where .= " AND group_status = 'Enable' ";
elseif (isset($_REQUEST["approve"]) && is_numeric($_REQUEST["approve"]))
    $db->query("UPDATE ".table_groups." SET group_status='Enable' WHERE group_id=".$db->escape(sanitize($_REQUEST["approve"],3)));

	if (isset($_REQUEST['keyword'])) {
		$_REQUEST['keyword']= preg_replace('/[^\p{L}\p{N}-_\s]/u', ' ', $_REQUEST['keyword']);
		$keyword = $db->escape(sanitize(trim($_REQUEST['keyword']), 3));
		if (!empty($keyword))
		{
			$from_where .= " AND (group_name LIKE '%$keyword%' OR group_description LIKE '%$keyword%')";
			$main_smarty->assign('search', $keyword);
			$main_smarty->assign('get', $_GET);
		}
	}
$order_by = "";
if(isset($_REQUEST["sortby"]))
{
	$sortby  = $_REQUEST["sortby"];
	if($sortby == 'newest')
		$order_by = "group_date DESC";
	if($sortby == 'oldest')
		$order_by = "group_date ASC";
	if($sortby == 'members')
		$order_by = "group_members DESC";
	if($sortby == 'name')
		$order_by = "group_name Asc";
	$main_smarty->assign('sortby', $sortby);
}

$rows = $db->get_var("SELECT count(*) FROM " . table_groups . " WHERE group_status='Enable'");
$main_smarty->assign('total_row_for_group', $rows);

/* Redwine: Roles and permissions and Groups fixes. Fix to Admin -> Settings -> Groups -> Max Groups User Create. Check if the user didn't exceed the max number of creating groups.*/
$numGr = $db->get_var("SELECT count(*) FROM " .table_groups . " WHERE `group_creator` = " . $current_user->user_id);
$max_user_groups_allowed = $main_smarty->get_template_vars('max_user_groups_allowed');
if ($numGr >= $max_user_groups_allowed) {
	$error_max = $main_smarty->get_config_vars('PLIKLI_Visual_Submit_A_New_Group_Error');
	$main_smarty->assign('error_max', $error_max);
}


// pagename
define('pagename', 'groups');
$main_smarty->assign('pagename', pagename);
$main_smarty = do_sidebar($main_smarty);

group_read($from_where, $order_by);

function group_read($from_where,$order_by)
{
	Global $db, $main_smarty, $view, $user, $rows, $page_size, $offset;
	// figure out what "page" of the results we're on
	$offset=(get_current_page()-1)*$page_size;

	// pagesize set in the admin panel
	/* Redwine: $search->pagesize is useless and generating Warning:  Creating default object from empty value. $pagesize is already in the globals. */
	//$search->pagesize = $page_size;

	if ($order_by == "")
		$order_by = "group_date DESC";
	include_once(mnminclude.'smartyvariables.php');
	global $db,$main_smarty;
	$rows = $db->get_var("SELECT count(*) FROM " . table_groups . " WHERE ".$from_where." ");
	$group = $db->get_results("SELECT distinct(group_id) as group_id FROM " . table_groups . " WHERE ".$from_where." ORDER BY group_status DESC, ".$order_by." LIMIT $offset,$page_size ");
	
	if ($group)
	{
		/* Redwine: initializing $group_display to eliminate the Notice:  Undefined variable: group_display. */
		$group_display = "";
		foreach($group as $groupid)
		{
			$group_display .= group_print_summary($groupid->group_id);
		}
		$main_smarty->assign('group_display', $group_display);
	}

	if(Auto_scroll==2 || Auto_scroll==3){
	   $main_smarty->assign("scrollpageSize", $page_size);
	 
	}else
		$main_smarty->assign('group_pagination', do_pages($rows, $page_size, "groups", true));
			return true;
}


//$main_smarty->assign("scrollpageSize", "1");
//For Infinit scrolling and continue reading option 

   $main_smarty->assign('link_pagination', do_pages($rows, $page_size, "published", true));


// show the template
$main_smarty->assign('tpl_center', $the_template . '/group_center');
$main_smarty->display($the_template . '/plikli.tpl');
?>