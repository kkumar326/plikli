<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'group.php');
include(mnminclude.'user.php');
include(mnminclude.'friend.php');
include(mnminclude.'smartyvariables.php');
include(mnminclude.'csrf.php');

check_referrer();
$CSRF = new csrf();


$offset=(get_current_page()-1)*$page_size;
//$main_smarty = do_sidebar($main_smarty);

define('pagename', 'user'); 
$main_smarty->assign('pagename', pagename);


// if not logged in, redirect to the index page
	$login = isset($_COOKIE['mnm_user'] ) ? sanitize($_COOKIE['mnm_user'] , 3) : '';
	//$login = isset($_GET['login']) ? sanitize($_GET['login'], 3) : '';
	if($login === ''){
		if ($current_user->user_id > 0) {
			$login = $current_user->user_login;
		} else {
			header('Location: ./');
			die;
		}
	}

if (Allow_User_Change_Templates && file_exists("./templates/".$_POST['template']."/header.tpl"))
{
	$domain = preg_replace('/^www/','',$_SERVER['HTTP_HOST']);
	// Remove port information.
    $port = strpos($domain, ':');
    if ($port !== false)  $domain = substr($domain, 0, $port);			
	if (!strstr($domain,'.') || strpos($domain,'localhost:')===0) $domain='';
	setcookie("template", $_POST['template'], time()+60*60*24*30,'/',$domain);
}
// Redwine: if TOKEN is empty, no need to continue, just display the invalid token error.
if (empty($_POST['token'])) {
	$CSRF->show_invalid_error(1);
	exit;
}
// Redwine: if valid TOKEN, proceed. A valid integer must be equal to 2.
if ($CSRF->check_valid(sanitize($_POST['token'], 3), 'user_settings') != 2){
    	$CSRF->show_invalid_error(1);
	exit;
}

$login_user = $db->escape($login);
//$login_user = $_GET['login'];
$sqlGetiUserId = $db->get_var("SELECT user_id from " . table_users . " where user_login = '" . $login_user. "';");
$select_check = $_POST['chack'];
		/* $geturl = $_SERVER['HTTP_REFERER'];
		$url = strtolower(end(explode('/', $geturl)));
		$vowels = array($url);
		$Get_URL = str_replace($vowels, "", $geturl); */
if ($_SERVER['HTTP_REFERER'] && strpos($_SERVER['HTTP_REFERER'],$my_base_url.$my_plikli_base)===0)
    $geturl = $_SERVER['HTTP_REFERER'];
else
    $geturl = sanitize($_SERVER['HTTP_REFERER'],3);
$url = strtolower(end(explode('/', $geturl)));
	/* Redwine: this query is the same as in profile.php line 333. So i am modifying it the same. changed the query above to get_col and therefore we do not need to create $arr anymore. */
	$sqlGetiCategory = "SELECT category__auto_id from " . table_categories . " where category__auto_id!= 0;";
	$sqlGetiCategoryQ = $db->get_col($sqlGetiCategory);
	/*$sqlGetiCategoryQ = mysql_query($sqlGetiCategory);
	$arr = array();
	while ($row = mysql_fetch_array($sqlGetiCategoryQ, MYSQL_NUM)) 
		$arr[] = $row[0];*/

	if (!$select_check) $select_check = array();
	$diff = array_diff($sqlGetiCategoryQ,$select_check);

	$select_checked = $db->escape(implode(",",$diff));

	$sql = "UPDATE " . table_users . " set user_categories='$select_checked' WHERE user_id = '$sqlGetiUserId'";	
	$query = $db->query($sql);
	$to_page = preg_replace("/&err=.+$/","",$geturl);
	header("location:".$to_page."");

?>