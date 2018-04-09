<?php

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'tags.php');
include(mnminclude.'smartyvariables.php');

check_referrer();

// breadcrumbs and page title
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Change_Template');
$navwhere['link1'] = getmyurl('profile', '');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Change_Template'));

// pagename
define('pagename', 'settemplate'); 
$main_smarty->assign('pagename', pagename);


if(isset($_GET['template'])){
	if(file_exists("./templates/".$_GET['template']."/link_summary.tpl")){
		$domain = preg_replace('/^www/','',$_SERVER['HTTP_HOST']);
		// Remove port information.
		$port = strpos($domain, ':');
		if ($port !== false)  $domain = substr($domain, 0, $port);			
		if (!strstr($domain,'.') || strpos($domain,'localhost:')===0) $domain='';
		setcookie("template", $_GET['template'], time()+60*60*24*30,$my_plikli_base,$domain);
		header('Location: ./index.php');
		die();
	}else{
	    
		$main_smarty->assign('message', '<div class="alert alert-error">Warning: <strong>"' . sanitize($_GET['template'],3) . '"</strong> does not seem to exist!</div>');
	}
}

// show the template	
$main_smarty->assign('tpl_center', $the_template . '/settemplate_center');
$main_smarty->display($the_template . '/plikli.tpl');
?>