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

// restrict access to admins
$canIhaveAccess = 0;
$canIhaveAccess = $canIhaveAccess + checklevel('admin');

if($canIhaveAccess == 0){	
	header("Location: " . getmyurl('admin_login', $_SERVER['REQUEST_URI']));
	die();
}

/* Redwine: changed the function parameters according to the query on line 62 to save 3 queries X number of comments to delete, in the delete_comment function */
function delete_comment($key, $linkid) {
    global $db;
    if (!is_numeric($key)) return;
   
	//$link_id = $db->get_var("SELECT comment_link_id FROM `" . table_comments . "` WHERE `comment_id` = ".$key.";");
	
	$vars = array('comment_id' => $key);
	check_actions('comment_deleted', $vars);

	/*$comments = $db->get_results($sql="SELECT comment_id FROM " . table_comments . " WHERE `comment_parent` = '$key'");
	foreach($comments as $comment)
	{
	   	$vars = array('comment_id' => $comment->comment_id);
	   	check_actions('comment_deleted', $vars);
	}
	$db->query('DELETE FROM `' . table_comments . '` WHERE `comment_parent` = "'.$key.'"');*/
	$db->query('DELETE FROM `' . table_comments . '` WHERE `comment_id` = "'.$key.'"');

	$link = new Link;
	$link->id=$linkid;
	$link->read();
	$link->recalc_comments();
	$link->store();
}
/* Redwine: changed the query to save 3 queries in the above delete_comment function */
$sql_query = "SELECT comment_id, comment_link_id, comment_parent FROM " . table_comments . " WHERE comment_status = 'discard'";
$result = $db->get_results($sql_query);
$num_rows = $db->get_var("SELECT FOUND_ROWS()");
foreach($result as $comment)
        delete_comment($comment->comment_id, $comment->comment_link_id);
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?php echo $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Discarded_Comments_Removed') ?></h4>
		</div>
		<div class="modal-body">
			<?php 
			/* Redwine: code to optimize the table is not working. In addition, i changed it from the deprectaed mysql_ extension to mysqli. */
			/*$query = "OPTIMIZE TABLE comments";
			mysql_query($query);
			if (mysql_error()){
				echo '<p>'.mysql_error().'</p>';
			}else{*/
				echo '<p><strong>'.$num_rows.'</strong> '.$main_smarty->get_config_vars("PLIKLI_Visual_AdminPanel_Discarded_Comments_Removed_Message").'</p>';
			//}
			include_once('../libs/dbconnect.php');
			$handle = new mysqli(EZSQL_DB_HOST,EZSQL_DB_USER,EZSQL_DB_PASSWORD,EZSQL_DB_NAME);
			/* check connection */
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			/* Redwine: Modified the code from the deprecated mysql_ to mysqli and also fixed the Optimization code that was not working, and created a new function in /libs/utils.php for this purpose that will also be used from many other places. */
			Repair_Optmize_Table($handle,EZSQL_DB_NAME,table_comments);
			$handle->close();
			?>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		</div>
	</div><!-- /.modal-content -->
</div>