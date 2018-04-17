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
define('pagename', 'extra_fields_editor'); 
$main_smarty->assign('pagename', pagename);

$file = "../libs/extra_fields.php";

$entry = '';
if (isset($_POST['save'])) {
	$generic = "<?php\nif(!defined('mnminclude')){header('Location: ../error_404.php');die();}\n\n";
	$generic_end = "?>";
	foreach($_POST['link_field'] as $field) {
		foreach($field as $key => $value) {
			if ($value == 'true' || $value == 'false') {
				$entry .= "define('".$key."', ". $value.");\n";
			}else{
				$value = htmlentities($value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$entry .= "define('".$key."', '". $value."');\n";
			}
		}
	}
	$to_write = $generic.$entry.$generic_end;
	$handle = fopen($file, 'w');
	if (!fwrite($handle, $to_write)) {
		$error = "Could not write to '$file' file";
	}else{
		$error = "file updated!";
	}
	fclose($handle);
	$main_smarty->assign('error', $error);
}

$pattern = "@define\(\'(.*)\',\s?\'?(.*?)\'?\)@i";
$lines = file($file);
$index = '';
$thefield = '';
$style = '';

foreach ($lines as $line) {	
	if (preg_match("/(Enable_Extra_Field_)(\d+)/", $line, $n)) {
		$index = $n[2];
		$thearray = $n[1];
		$style = ' style="background-color:#dde9f7;font-weight:bold;"'; 
	}else{
		$style = '';
	}
	if (preg_match("/(Enable_Extra_Field_\d+|Field_\d+_(Title|Instructions|Searchable|Required|Validation_Method|Validation_Error_Message))/", $line, $f)) {
		$thefield = $f[1];
	}
	if (preg_match($pattern, $line, $m)) {
		$extra_content = '<tr>'."\n".'<td' .$style.'>'.$m[1].'</td>'."\n".'<td><input type="text" name="link_field['.$thearray.$index.']['.$thefield.']" class="form-control admin_config_input emptytext" value="'.$m[2].'" size="60"' .$style.' /></td>'."\n".'</tr>'."\n";
		$xtra_content .= $extra_content;
	}
}
$main_smarty->assign('xtra_content', $xtra_content);

// show the template
$main_smarty->assign('tpl_center', '/admin/extra_fields_editor');
$main_smarty->display('/admin/admin.tpl');	


?>