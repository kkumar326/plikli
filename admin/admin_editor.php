<?php

include_once('../internal/Smarty.class.php');
$main_smarty = new Smarty;

include('../config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'smartyvariables.php');
include(mnminclude.'csrf.php');

check_referrer();

// require user to log in
force_authentication();

// restrict access to admins
$canIhaveAccess = 0;
$canIhaveAccess = $canIhaveAccess + checklevel('admin');

if($canIhaveAccess == 0){	
//	$main_smarty->assign('tpl_center', '/admin/access_denied');
//	$main_smarty->display('/admin/admin.tpl');			
	header("Location: " . getmyurl('admin_login', $_SERVER['REQUEST_URI']));
	die();
}

// breadcrumbs and page title
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel');
$navwhere['link1'] = getmyurl('admin', '');
$navwhere['text2'] = $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel_Editor');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel'));

// pagename
define('pagename', 'admin_editor'); 
$main_smarty->assign('pagename', pagename);

// read the mysql database to get the plikli version
/* Redwine: plikli version query removed and added to /libs/smartyvriables.php */

$filedir = "../templates/".The_Template;
#echo $filedir;

$valid_ext[1] = "css";
$valid_ext[2] = "tpl";

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    $files = array();
    if (is_readable($filedir)) {
	$filelist = directoryToArray($filedir, true);
	foreach ($filelist as $file) {
	    $ext = substr(strrchr($file, '.'), 1);
		if (in_array($ext,$valid_ext) && is_writable($file)) {
			$files[] = $file;
		}
	}
    }
    $main_smarty->assign('files', $files);
}
elseif ($_POST["the_file"])
{
	/* Redwine: added sanitization and removing ".." from the name of the file and making sure that nothing can be loaded to edit other than the allowed file extensions TPL and CSS. */
	$_POST["the_file"] = sanitize ($_POST["the_file"],3);
	$dir_name = dirname($_POST["the_file"]);
	$base_name = basename($_POST["the_file"]);
	$base_name = str_replace('..', '', $base_name);
	$_POST["the_file"] = $dir_name . "/" . $base_name;
	$ext = substr(strrchr($_POST["the_file"], '.'), 1);
	if (!empty($ext)) {
		if (in_array($ext,$valid_ext)) {
    $file2open = fopen($_POST["the_file"], "r");
    if ($file2open) {
	    $current_data = @fread($file2open, filesize($_POST["the_file"]));
	    $current_data = str_ireplace("</textarea>", "<END-TA-DO-NOT-EDIT>", $current_data);
	    $main_smarty->assign('filedata', htmlspecialchars($current_data));
	    fclose($file2open);
    } else 
	    $main_smarty->assign('error', 1);
    $main_smarty->assign('the_file', sanitize($_POST['the_file'],3));
		}else{
			header("Location: admin_editor.php");
			die();
		}
	}else{
		header("Location: admin_editor.php");
		die();
	}
}
elseif ($_POST["save"])
{
	if (!$_POST["updatedfile"] && !$_POST['isempty'])
		$error = "<h3>ERROR!</h3><p>File NOT saved! <br /> You can't save blank file without confirmation. <br />  <a href=\"\">Click here to go back to the editor.</a></p>";
	elseif ($file2ed = fopen($_POST["the_file2"], "w+")) {
		$data_to_save = $_POST["updatedfile"];
		$data_to_save = str_ireplace("<END-TA-DO-NOT-EDIT>", "</textarea>", $data_to_save);
		$data_to_save = stripslashes($data_to_save);
		if (fwrite($file2ed,$data_to_save)!==FALSE) { 
			$error = "<h3>File Saved</h3><p><a href=\"\">Click here to go back to the editor.</a></p>";	
			fclose($file2ed);
		}
		else {	
			$error = "<h3>ERROR!</h3><p>cant File NOT saved! <br /> Check your CHMOD settings in case it is a file/folder permissions problem. <br />  <a href=\"\">Click here to go back to the editor.</a></p>";
			fclose($file2ed);
		}
	}
	else 
		$error = "<h3>ERROR!</h3><p>writable File NOT saved! <br />Check your CHMOD settings in case it is a file/folder permissions problem.</p>";
     	$main_smarty->assign('error', $error);
}

// show the template
$main_smarty->assign('tpl_center', '/admin/template_editor');
$main_smarty->display('/admin/admin.tpl');	

	
function directoryToArray($directory, $recursive) {
$me = basename($_SERVER['PHP_SELF']);	
$array_items = array();
	if ($handle = opendir($directory)) {
  	while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != $me && substr($file,0,1) != '.') {
        if (is_dir($directory. "/" . $file)) {
					if($recursive) {
						$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
          }						 
				}
				else {
            $file = $directory . "/" . $file;
            $array_items[] = preg_replace("/\/\//si", "/", $file);
				}
      }
    }
    closedir($handle);
		asort($array_items);
  }
  return $array_items;
}

?>