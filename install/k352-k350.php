<?php
session_start();
//include('../config.php');

echo '<style type="text/css">
h2 {
margin:0 0 5px 0;
line-height:30px;
}
.language_list li {
display:inline-block;
clear:both;
margin:0 0 8px 0;
text-align:left;
padding:3px 3px 2px 10px;
}
.language_list {
margin:0;
padding:0;
}
.well {
background-color: #0073AA;
border:none;
}
fieldset {
width:100%;
-webkit-border-radius: 8px;
-moz-border-radius: 8px;
border-radius: 8px;
background-color: #0073AA;
color:#ffffff;
-webkit-box-shadow: 7px 7px 5px 0px rgba(50, 50, 50, 0.75);
-moz-box-shadow:    7px 7px 5px 0px rgba(50, 50, 50, 0.75);
box-shadow:         7px 7px 5px 0px rgba(50, 50, 50, 0.75);
padding-bottom: 10px;
}
legend {
width: auto;
background: #FF9;
color:#000000;
font-weight:bold;
border: solid 1px black;
-webkit-border-radius: 8px;
-moz-border-radius: 8px;
border-radius: 8px;
padding: 6px;
font-size: 0.9em;
}
.iconalign {vertical-align: bottom;}
.alert-danger, .alert-error {
background-color: #FF0000;
border-color: #F4A2AD;
color: #ffffff;
margin: 0 10px 0 10px;
padding:5px;
}
li{marging-left:30px;}
a:link, a:hover, a:visited, a:active{color:#000000}
.btn-primary, btn {margin-left:10px}
.warn-delete{color:#ffe000;font-weight:bold}
</style>';

$notok = 'notok.png';
$ok = 'ok.png';
$warnings = array();

	/* Redwine: creating a mysqli connection */
	$handle = new mysqli(EZSQL_DB_HOST,EZSQL_DB_USER,EZSQL_DB_PASSWORD,EZSQL_DB_NAME);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

echo '<fieldset><legend>Converting all the Tables to utf8_general_ci and Engine MyISAM</legend><ul>';
	// Update CMS version.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = '" . $lang['kliqqi_version'] . "' where `name` = 'kliqqi_version';";
	$sql_CMS_version = $handle->query($sql);
	if (!$sql_CMS_version) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated CMS_version to '. $lang['kliqqi_version'] .' <img src="'.$marks.'" class="iconalign" /></li>';	
echo '</ul></fieldset><br />';

/* Redwine: checking if we have to detect certain settings and modules or not, to give further instructions. */
if ($_SESSION['upgrade_dir'] != $_SESSION['initial_dir']) {
	echo '<fieldset><legend>Checking the installed modules and certain config settings!</legend><ul>';
		$sql = "select `name` from `" . table_prefix."modules`";
		$sql_modules = $handle->query($sql);
		/*$to_delete = array('Human Check','Google Adsense Revenue Sharing','Status', 'Status Update Module');
		while ($module = $sql_modules->fetch_assoc()) {
			if (in_array($module['name'],$to_delete)) {
				$warnings[] = "We detected that " . $module['name'] . " is installed. This module is not supported by Kliqqi and we cannot support it or support any problem resulting from its usage";
			}
		}
		$sql_modules->data_seek(0);*/
		while ($module = $sql_modules->fetch_assoc()) {
			if ($module['name'] == 'Upload') {
				$warnings[] = "We noticed you have the UPLOAD module installed. You have to copy the files from the old Pligg folder, in /modules/upload/attachments to the same folder in the new Kliqqi.";
			}elseif ($module['name'] == 'Links') {
				$warnings[] = "Check the Links module because we added few settings to it <strong style=\"text-decoration:underline;background-color:#0100ff\">YOU HAVE TO GO TO ITS SETTINGS AND SELECT THE NEW OPTIONS THAT YOU WANT; OTHERWISE IT WILL NOT WORK UNTIL YOU DO SO!</strong>!";			
			}
		}
	echo '</ul></fieldset><br />';	

		// Checking some settings to determine if further manual action is required.
	echo '<fieldset><legend>Checking if Allow users to change language is set to 1 and if validate user email is set to true in your config table</legend><ul>';
		$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'user_language';";
		$sql_get_user_language = $handle->query($sql);
		$result = $sql_get_user_language->fetch_array(MYSQLI_ASSOC);
		if ($result['var_value'] == '1') {
			echo 'Allow users to change language is set to "'.$result['var_value']. '" in your config table. See Warnings at the end of the upgrade!';
			$warnings[] = 'Allow users to change language is set to "'.$result['var_value']. '" in your config table. You must rename the allowed language file in /languages/ from .default to .conf';
		}else{
			echo 'Allow users to change language is set to default 0 no action is required';
		}
		
			// if validate user email is false or true.
		$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'misc_validate';";
		$sql_get_misc_validate = $handle->query($sql);
		$result = $sql_get_misc_validate->fetch_array(MYSQLI_ASSOC);
		echo "<br />email validate = " . $result['var_value'];
		if (trim($result['var_value']) == 'true') {
			echo 'Require users to validate their email address is set to default "'. $result['var_value']. '" in your config table. See Warnings at the end of the upgrade!';
			$warnings[] = 'Require users to validate their email address is set to "' .trim($result['var_value']). '" in your config table. You must enter the email you are using for your site in the language file in /languages/ and enter it as the value for KLIQQI_PassEmail_From';
		}else{
			echo '<br />Require users to validate their email address is set to default "'. $result['var_value']. '" No action is required';
		}
	echo '</ul></fieldset><br />';

	echo '<br /><fieldset><legend>Updating the Site Title in all the language files to "'. $_SESSION['sitetitle'] . '"</legend><ul>';
		$replacement = 'KLIQQI_Visual_Name = "'.strip_tags($_SESSION['sitetitle']).'"';
		if (strip_tags($_SESSION['sitetitle']) != '') {
			foreach (glob("../languages/*.{conf,default}", GLOB_BRACE) as $filename) {
				$filedata = file_get_contents($filename);
				$filedata = preg_replace('/KLIQQI_Visual_Name = \"(.*)\"/iu',$replacement,$filedata);
				// print $filedata;
				
				// Write the changes to the language files
				$lang_file = fopen($filename, "w");
				fwrite($lang_file, $filedata);
				fclose($lang_file);
				echo '<li>' . $filename . '</li>';
			}
		}else{
			echo 'You did not enter a new Visual Name for the site, so the current one will remain unchanged!';
		}
	echo '</ul></fieldset><br />';
		
	echo '<fieldset><legend>Checking SEO Method and links extra fields</legend><ul>';
		// Checking if SEO method 2 is used.
		$sql = "SELECT `var_name`,`var_value` FROM `". table_prefix . "config` WHERE `var_name` = '\$URLMethod' or `var_name` = 'Enable_Extra_Fields';";
		$sql_check_seo = $handle->query($sql);
		if ($sql_check_seo) {
			$row_cnt = $sql_check_seo->num_rows;
			if ($row_cnt > 0) {
				while ($seoMethod = $sql_check_seo->fetch_assoc()) {
					if ($seoMethod['var_name'] == '$URLMethod' && $seoMethod['var_value'] == 2) {
						echo 'We detected that SEO Method 2 is used. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
						$warnings[] = 'We detected that SEO Method 2 is used. You must rename <strong>htaccess.default to .htaccess</strong><br />if you are using Windows, you can rename the file by opening the command line to the root of this folder and type the following and press enter:<br /><strong>rename htaccess.default .htaccess</strong>';
					}elseif ($seoMethod['var_name'] == '$URLMethod' && $seoMethod['var_value'] == 1) {
						echo 'You are using SEO method '.$seoMethod['var_value'].'. No need to rename the htaccess default and edit it!<br />';
						$warnings[] = 'you are using SEO method '.$seoMethod['var_value'].'. No need to rename the htaccess default and edit it!'; 
					}
					// Checking if extra fields are used in links table
						$sql = "SELECT `link_id` FROM `" . table_prefix . "links` WHERE 
						`link_field1` != '' OR 
						`link_field2` != '' OR 
						`link_field3` != '' OR 
						`link_field4` != '' OR 
						`link_field5` != '' OR 
						`link_field6` != '' OR 
						`link_field7` != '' OR 
						`link_field8` != '' OR 
						`link_field9` != '' OR 
						`link_field10` != '' OR
						`link_field11` != '' OR
						`link_field12` != '' OR
						`link_field13` != '' OR
						`link_field14` != '' OR
						`link_field15` != '';";

						$sql_check_extra_fields = $handle->query($sql);
						if ($sql_check_extra_fields) {
							$row_cnt_extra_fields = $sql_check_extra_fields->num_rows;
						}
					if ($seoMethod['var_name'] == 'Enable_Extra_Fields' && $seoMethod['var_value'] == 'true') {
						if ($row_cnt_extra_fields > 0) {
							echo 'We detected that Enable Extra Fields is set to true and one or more extra fields in links table are used. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
							$warnings[] = 'We detected that Enable Extra Fields is set to true and one or more extra fields in links table are used. You must edit the following files to match them with your old files:<br /><strong>/libs/extra_fields.php</strong><br /><strong>/templates/bootstrap/link_summary.tpl (The block of code that starts with {if $Enable_Extra_Field_1 eq 1})</strong>';
						}else{
							echo 'We detected that Enable Extra Fields is set to true but Extra fields in links table are not used. No action is required! SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
							$warnings[] = 'We detected that Enable Extra Fields is set to true but Extra fields in links table are not used. No action is required!<br />Should you decide to use the extra fields, you must edit the following files:<br /><strong>/libs/extra_fields.php</strong><br /><strong>/templates/bootstrap/link_summary.tpl (The block of code that starts with {if $Enable_Extra_Field_1 eq 1})</strong>';
						}

					}elseif ($seoMethod['var_name'] == 'Enable_Extra_Fields' && $seoMethod['var_value'] == 'false') {
						if ($row_cnt_extra_fields > 0) {
							echo 'We detected that Enable Extra Fields is set to false and one or more extra fields in links table are used. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
							$warnings[] = 'We detected that Enable Extra Fields is set to false and one or more extra fields in links table are used. Should you decide to use the extra fields, you must set Enable Extra Fields to true and edit the following files:<br /><strong>/libs/extra_fields.php</strong><br /><strong>/templates/bootstrap/link_summary.tpl (The block of code that starts with {if $Enable_Extra_Field_1 eq 1})</strong>';
						}else{
							echo 'Extra fields in links table are not used. No action is required!<br />';
							$warnings[] = 'Extra fields in links table are not used. No action is required!'; 
						}
					}
				}
			}
		}
	echo '</ul></fieldset><br />';

	echo '<fieldset><legend>Renaming the orginal folder containing the old Kliqqi files</legend><ul>';
		$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = '\$my_kliqqi_base';";
		$sql_get_base_folder = $handle->query($sql);
		$result = $sql_get_base_folder->fetch_array(MYSQLI_ASSOC);
		$result['var_value'] = substr($result['var_value'], 1, strlen($result['var_value']));
		$success = rename($_SERVER['DOCUMENT_ROOT'].$result['var_value'],$_SERVER['DOCUMENT_ROOT'].$result['var_value'] . "-original");
		if (!$success) {
			$marks = $notok;
			echo '<li class="alert-danger">FAILED to rename the folder ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' To ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS! <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = 'FAILED to rename the folder ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' To ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original <br />The browser or any other application is using one of its files!<br />You have to manually rename it as indicated in the beginning of the warning!';
		}else{
			$marks = $ok;
			echo '<li>RENAMED ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' to ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = '<span class="warn-delete">RENAMED ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' to ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original</span>';
		}
		// getting the root folder of the current CMS (the new kliqqi) to rename it as it is in the config table under $my_kliqqi_base
		$arr = explode("/", $_SERVER['SCRIPT_NAME']);
		$first = $arr[1];
		$path = $_SERVER["DOCUMENT_ROOT"] . $first;
		echo "<br />";
		$warnings[] = "you have to manually rename the current folder from:<br />". $path . " to " . $_SERVER["DOCUMENT_ROOT"] . $result['var_value'];
	echo '</ul></fieldset><br />';
}else{
	$warnings[] = "It seems that you copied the new kliqqi ". $lang['kliqqi_version'] ." files and pasted them over in your initial directory " . $_SESSION['initial_dir'] . " So we just updated the version number in the misc_data table. No further instructions is required!";
}	

	
echo '<fieldset><legend>Additional Instructions to follow!</legend><div class="alert alert-danger"><ul>';
	echo '<li><span style="background-color:#ffffff;color:#000000;font-weight:bold;">The upgrade process was successful. PLEASE PAY SPECIAL ATTENTION THE ADDITIONAL INSTRUCTIONS BELOW!</span></li>';
	$output = '';
	if ($warnings) {
		foreach ($warnings as $warning) {
			$output.="<li>$warning</li><br />";
		}
		echo $output;
	}
echo '</ul></div></fieldset><br />';
?>