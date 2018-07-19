<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'group.php');
include(mnminclude.'link.php');
include(mnminclude.'tags.php');
include(mnminclude.'smartyvariables.php');

check_referrer();

//to join and unjoin the group
settype($_REQUEST['id'], "integer");

if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
	$gid = sanitize($_REQUEST['id'], 3);
	$privacy = sanitize($_REQUEST['privacy'],3);
	/* Redwine: added a query to get the group's title to fix the redirect and properly redirect to the same group after joining and unjoining. */
	$requestTitle = $db->get_var("SELECT group_safename FROM " . table_groups . " WHERE group_id = $gid");
	if(sanitize($_REQUEST['join'],3) == "true"){
		joinGroup($gid,$privacy);
	}
	if(sanitize($_REQUEST['join'],3) == "false"){
		unjoinGroup($gid,$privacy);
	}
	//page redirect
	$redirect = '';
	/* Redwine: changed the erroneous getmyurl("group_story", $gid) to the accurate one. */
	$redirect = getmyurl("group_story_title", $requestTitle);
	header("Location: $redirect");
	die;
}else{
	$redirect = getmyurl("groups");
	header("Location: $redirect");
}
/* Redwine: Roles and permissions and Groups fixes. To activate/dectivate a group member. Added  && $_REQUEST['activate'] != "withdraw" because when a user request to witdraw the request to join a private or restricted group, and error from line 86 was generated as the if( isset($_REQUEST['activate'])) { with checking for a parameter was processed first and before the if(isset($_REQUEST['activate'])) && $_REQUEST['activate'] == 'withdraw')*/
if( isset($_REQUEST['activate']) && $_REQUEST['activate'] != "withdraw") {
	$group_id = $_REQUEST['group_id'];
	$user_id = $_REQUEST['user_id'];
	if (!is_numeric($user_id) || !is_numeric($group_id)) die();
	/* Redwine: Roles and permissions and Groups fixes. We need to get the group role of the current logged in user to determine the permissions */
	global $db, $current_user;
	$role = "";
	$role = get_group_roles($group_id);
	$is_gr_Admin = 0;
	$is_gr_Moderator = 0;
	$owner = 0;
	$gr_creator = 0;
	$is_active;
	if ($role == "admin") {
		$is_gr_Admin = 1;
	}elseif ($role == "moderator") {
		$is_gr_Moderator = 1;
	}
	/* Redwine: Roles and permissions and Groups fixes. We also need to check if the logged in user is the group creator */
	$owner = get_group_creator($group_id);
	if ($owner == $current_user->user_id) {
		$gr_creator = 1;
	}
	/* Redwine: Roles and permissions and Groups fixes. We also need to check if the logged in user is an active member in the group */
	$is_active = isMemberActive($group_id);
	/* Redwine: Roles and permissions and Groups fixes. Assigning member_status depending on the passed query parameter */
	if ($_REQUEST['activate'] == 'true') {
		$member_status = 'active';
	}elseif ($_REQUEST['activate'] == 'false') {
		$member_status = 'inactive';
	}
	/* Redwine: Roles and permissions and Groups fixes. If the logged in user has the required permissions then we proceed to change the member status */	
	if (($gr_creator == 1 || $is_gr_Admin == 1 || $is_gr_Moderator == 1) && $is_active == 'active') {
		//check for request
		$request_exists = $db->get_row("SELECT * FROM " . table_group_member . " WHERE member_user_id = $user_id");
		if($request_exists) {
			global $db, $current_user;
			$sql = "update ". table_group_member ."  set member_status = '".$member_status."' where member_user_id = ".$user_id." and member_group_id = ".$group_id." ";
			$result1 = $db->query($sql);
			//update count
			$member_count = get_group_members($group_id);
			$member_update = "update ". table_groups ." set group_members = '".$member_count."' where group_id = '".$group_id."'";
			$results1 = $db->query($member_update);
		}
		//page redirect
		$requestTitle = $db->get_var("SELECT group_safename FROM " . table_groups . " WHERE group_id = $group_id");
		$redirect = str_replace("&amp;","&",getmyurl('group_story2', $requestTitle, 'members'));
		header("Location: $redirect");
		die;
	}else{
		die("You don't have enough rights");
	}
}
//to withdraw join group request
if(isset($_REQUEST['activate']) && $_REQUEST['activate'] == 'withdraw')
{
	$group_id = $_REQUEST['group_id'];
	$user_id = $_REQUEST['user_id'];
	/* Redwine: added a query to get the group's title to fix the redirect and properly redirect to the same group after on line 109. */
	$requestTitle = $db->get_var("SELECT group_safename FROM " . table_groups . " WHERE group_id = $group_id");
	if (!is_numeric($user_id) || !is_numeric($group_id)) die();
	//check for request
	$request_exists = $db->get_row("SELECT * FROM " . table_group_member . " WHERE member_user_id = $user_id");
	
	if($request_exists)
	{
		global $db, $current_user;
		$sql2 = "delete from ". table_group_member ." where member_user_id = ".$user_id." and member_group_id = ".$group_id." ";
		//echo $sql;
		$result1 = $db->query($sql2);
	}	
	//page redirect
	$redirect = '';
	/* Redwine: changed the erroneous getmyurl("group_story", $group_id) to the accurate one. */
	$redirect = getmyurl("group_story_title", $requestTitle);
	header("Location: $redirect");
	die;
}
/* Redwine: Roles and permissions and Groups fixes. To change the link_group_status*/
if(in_array($_REQUEST['action'],array('published','new','discard')))
{
	$linkid = $_REQUEST['link'];
	if (!is_numeric($linkid)) die();
/* Redwine: Roles and permissions and Groups fixes */
	$get_groupID = $db->get_var("SELECT link_group_id FROM " . table_links . " WHERE link_id = $linkid");
	$requestTitle = $db->get_var("SELECT group_safename FROM " . table_groups . " WHERE group_id = $get_groupID");
	$action = $_REQUEST['action'];
	
	//update the field group_vote_to_publish
	$sql = "UPDATE " . table_links . " set link_group_status='{$_REQUEST['action']}' WHERE link_id=$linkid";
	
	//update the link status
	//$sql = "UPDATE " . table_links . " set link_status='published' WHERE link_id=$linkid";
	$db->query($sql);
	//page redirect
/* Redwine: Roles and permissions and Groups fixes */
	$redirect = '';
	$redirect = str_replace("&amp;","&",getmyurl('group_story2', $requestTitle, $action));
	header("Location: $redirect");
	die;
}
?>