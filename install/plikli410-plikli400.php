<?php
session_start();
//include('../config.php');
include('db-mysqli.php');
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
li{margin-left:30px;}
a:link, a:hover, a:visited, a:active{color:#000000}
.btn-primary, btn {margin-left:10px}
.warn-delete{color:#ffe000;font-weight:bold}
</style>';

//get the name of the directory from where the upgrade is running.
$arr_script = explode("/", $_SERVER['SCRIPT_NAME']);
$upgrade_folder = $arr_script[1];

// ********************************
/**********************************
Redwine: checking for the MySQL Server version. If it is older than 5.0.3, then `link_url` varchar will be the maximum of 255; otherwise, we set it to 512 to accommodate long urlencoded.
**********************************/
$pattern = '/[^0-9-.]/i';
$replacement = '';
$mysqlServerVersion = $handle->server_info;
$mysqlServerVersion = preg_replace($pattern, $replacement, $mysqlServerVersion);
if (strpos($mysqlServerVersion, '-') > 0){ 
$mysqlServerVersion = strstr($mysqlServerVersion, '-', true);
}else{
	$mysqlServerVersion = $mysqlServerVersion;
}

if ($mysqlServerVersion < '5.0.3') {
	$urllength = '255';
}else{
	$urllength = '512';
}


$notok = 'notok.png';
$ok = 'ok.png';
$warnings = array();
$warnings_rename = array();

echo '<fieldset><legend>modifying link_url in Links table.</legend><ul>';
	$sql = "ALTER TABLE  `" . table_prefix."links`  
	CHANGE  `link_url`  `link_url` VARCHAR( $urllength ) NOT NULL DEFAULT '';";
	$sql_alter_links - $handle->query($sql);
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated links Table link_url to VARCHAR '.$urllength.'</li>';
	
echo '</ul></fieldset><br />';

echo '<fieldset><legend>MODIFICATIONS TO THE CONFIG Table.</legend><ul>';
	//Inserting new rows 
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`)VALUES
			(NULL, 'Misc', 'validate_password', 'true', 'true', 'true / false', 'Validate user password', 'Validate user password, when registering/password reset, to check if it is safe and not pwned?<br />If you set to true, then a check is done using HIBP API. If the provided password has been pwned, the registration is not submitted until they provide a different password!.<br /><a href=\"https://haveibeenpwned.com/\" target=\"_blank\" rel=\"noopener noreferrer\">Have I Been Pwned?</a>', 'define', '');";
	$sql_new_config = $handle->query($sql);
	if (!$sql_new_config) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	$warnings[] = "Added new settings to the CONFIG Table:<ol><strong>Under Miscellenaeous<li>validate_password with HIBP API</li></ol>";
	printf("Affected rows (INSERT): %d\n", $handle->affected_rows);
	echo '<li>INSERTED new settings in the CONFIG Table (read the notes at the end of the upgrade process) <img src="'.$marks.'" class="iconalign" /></li>';	
	
	// Update dblang description
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Database language.<br /><strong style=\"color:#ff0000;\">DO NOT CHANGE THIS VALUE \"en\" IT WILL MESS UP THE URLS OF THE CATEGORIES!</STRONG>' WHERE `var_name` =  '\$dblang';";
	$sql_dbland = $handle->query($sql);
	if (!$sql_dbland) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated dblang description <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';
//end work on CONFIG Table

echo '<fieldset><legend>modifying user_password column in Users table.</legend><ul>';
	$sql = "ALTER TABLE  `" . table_prefix."users`  
	CHANGE COLUMN `user_pass` `user_pass` VARCHAR(80) NOT NULL DEFAULT '';";
	$sql_alter_user_password - $handle->query($sql);
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated user_password column in Users table to VARCHAR(80)</li>';
echo '</ul></fieldset><br />';

//start work on misc_data table, setting all captcha and solvemedia entries
echo '<fieldset><legend>Updating the misc_data table. If an entry needs updating it is marked <img src="ok.png" class="iconalign" />; else, it is marked <img src="notok.png" class="iconalign" /></legend><ul>';
	$sql = "select * from `" . table_prefix."misc_data` where name like '%adcopy%'";
	$sql_adcopy = $handle->query($sql);
	if ($sql_adcopy) {
		$row_cnt = $sql_adcopy->num_rows;
		if ($row_cnt) {
			while ($adcopy = $sql_adcopy->fetch_assoc()) {
				if (in_array('adcopy_lang',$adcopy)) {
					$sql_adcopy_lang = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'en' WHERE `name` = 'adcopy_lang';");
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED 'adcopy_lang'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('adcopy_theme',$adcopy)) {
					$sql_adcopy_theme = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'white' WHERE `name` = 'adcopy_theme';");
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED 'adcopy_theme'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('adcopy_pubkey',$adcopy)) {
					$sql_adcopy_pubkey = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'Rp827COlEH2Zcc2ZHrXdPloU6iApn89K' WHERE `name` = 'adcopy_pubkey';");
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED 'adcopy_pubkey'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('adcopy_privkey',$adcopy)) {
					$sql_adcopy_privkey = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = '7lH2UFtscdc2Rb7z3NrT8HlDIzcWD.N1' WHERE `name` = 'adcopy_privkey';");
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED 'adcopy_privkey'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('adcopy_hashkey',$adcopy)) {
					$sql_adcopy_hashkey = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'rWwIbi8Nd6rX-NYvuB6sQUJV6ihYHa74' WHERE `name` = 'adcopy_hashkey';");
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED 'adcopy_hashkey'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}
			}
		}else{
			$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
					VALUES ('adcopy_lang', 'en'),
					('adcopy_theme', 'white'),
					('adcopy_pubkey', 'Rp827COlEH2Zcc2ZHrXdPloU6iApn89K'),
					('adcopy_privkey', '7lH2UFtscdc2Rb7z3NrT8HlDIzcWD.N1'),
					('adcopy_hashkey', 'rWwIbi8Nd6rX-NYvuB6sQUJV6ihYHa74');";
			$sql_captcha_data = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (INSERTED adcopy data values): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';		
		}
	}
	$sql = "select * from `" . table_prefix."misc_data` where name like 'captcha%'";
	$sql_captcha = $handle->query($sql);
	$toCheck = array('captcha_method','captcha_reg_en','captcha_comment_en','captcha_story_en');
	if ($sql_captcha) {
		$row_cnt_captcha = $sql_captcha->num_rows;
		if ($row_cnt_captcha) {
			while ($captcha = $sql_captcha->fetch_assoc()) {
				$all_captcha[] = $captcha['name'];
			}

			$cap_imploded = implode(",",$all_captcha);
			// find the matches in the arrays
			$matches = array_intersect($toCheck, $all_captcha);
			// find the differences in the arrays
			$diff = array_diff($toCheck, $all_captcha);
			if ($matches) {
				foreach($matches as $method) {
					if ($method == 'captcha_method') {
						$sql_captcha_method = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'solvemedia' where `name` = 'captcha_method';");
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (UPDATED 'captcha_method'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';	
					}elseif ($method == 'captcha_reg_en') {
						$sql_captcha_reg_en = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'true' where `name` = 'captcha_reg_en';");
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (UPDATED 'captcha_reg_en'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}elseif ($method == 'captcha_comment_en') {
						$sql_captcha_comment_en = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'true' where `name` = 'captcha_comment_en';");
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (UPDATED 'captcha_comment_en'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}elseif ($method == 'captcha_story_en') {
						$sql_captcha_story_en = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'true' where `name` = 'captcha_story_en';");
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (UPDATED 'captcha_story_en'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}
				}
			}
			if ($diff) {
				foreach($diff as $difference) {
					if ($difference == 'captcha_method') {
						$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
								VALUES ('captcha_method', 'solvemedia');";
						$sql_captcha_method = $handle->query($sql);
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (INSERTED 'solvemedia'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}elseif ($difference == 'captcha_reg_en') {
						$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
								VALUES ('captcha_reg_en', 'true');";
						$sql_captcha_reg_en = $handle->query($sql);
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (INSERTED 'captcha_reg_en'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}elseif ($difference == 'captcha_comment_en') {
						$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
								VALUES ('captcha_comment_en', 'true');";
						$sql_captcha_comment_en = $handle->query($sql);
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (INSERTED 'captcha_comment_en'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}elseif ($difference == 'captcha_story_en') {
						$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
								VALUES ('captcha_story_en', 'true');";
						$sql_captcha_story_en = $handle->query($sql);
						if ($handle->affected_rows < 1) {
							$marks = $notok;
						}else{
							$marks = $ok;
						}
						echo '<li>'.printf("Affected rows (INSERTED 'captcha_story_en'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					}
				}
			}
		}else{
			$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
					VALUES 
						('captcha_method', 'solvemedia'),
						('captcha_comment_en', 'true'),
						('captcha_reg_en', 'true'),
						('captcha_story_en', 'true');";
			$sql_captcha_data = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (INSERTED data values): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';		
		}
	}
	
	// Delete all reCaptcha entries
	$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'reCaptcha_%';";
	$sql_delete_recaptcha_entries = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (DELETED reCaptcha data): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';	
//end work on misc_data table, setting all captcha and solvemedia entries

//start work on misc_data table
echo '<fieldset><legend>Renaming some values in the misc_data table to work with Plikli.</legend><ul>';	
		// Update CMS version.
			$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = '". $lang['plikli_version'] ."'  WHERE `name` = 'plikli_version';";
			$sql_CMS_name = $handle->query($sql);
			if (!$sql_CMS_name) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Updated name to plikli_version and version to '. $lang['plikli_version'] .' <img src="'.$marks.'" class="iconalign" /></li>';
			
	// updating and installing Plikli and module updates
	$sql = "select * from `" . table_prefix."misc_data` where `name` like 'modules_%';";
	$sql_modules_update = $handle->query($sql);
	if ($sql_modules_update) {
		$row_cnt = $sql_modules_update->num_rows;
		if ($row_cnt) {
			while ($cms_modules_update = $sql_modules_update->fetch_assoc()) {
				if (in_array('modules_update_date',$cms_modules_update)) {
					$sql_modules_update_date = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` =  DATE_FORMAT(NOW(),'%Y/%m/%d')  WHERE `name` = 'modules_update_date';");
					if (!$sql_modules_update_date) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
					echo '<li>updated modules_update_date <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('modules_update_url',$cms_modules_update)) {
					$sql_modules_update_url = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'https://www.plikli.com/mods/version-update.txt' WHERE `name` = 'modules_update_url';");
					if (!$sql_modules_update_url) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
					echo '<li>updated modules_update_url <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('modules_update_unins',$cms_modules_update)) {
					$sql_modules_update_unins = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = '' WHERE `name` = 'modules_update_unins';");
					if (!$sql_modules_update_unins) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
					echo '<li>updated modules_update_unins <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('modules_upd_versions',$cms_modules_update)) {
					$sql_modules_upd_versions = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = '' WHERE `name` = 'modules_upd_versions';");
					if (!$sql_modules_upd_versions) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
					echo '<li>updated modules_upd_versions <img src="'.$marks.'" class="iconalign" /></li>';
				}
			}
		}
	}

	//inserting the update url for PLIKLI
	$sql = "INSERT INTO `" . table_prefix."misc_data` ( `name` , `data` ) VALUES 
	('plikli_update_url','https://www.plikli.com/download_plikli/');";
	$sql_insert_updateUrl = $handle->query($sql);
	if (!$sql_insert_updateUrl) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (INSERT): %d\n", $handle->affected_rows);
	echo '<li>Inserted the update URL for Plikli CMS <img src="'.$marks.'" class="iconalign" /></li>';
		
echo '</ul></fieldset><br />';

/* Redwine: checking if we have to detect certain settings and modules or not, to give further instructions. */
//get the CMS folder name
$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = '\$my_plikli_base';";
$sql_get_base_folder = $handle->query($sql);
$fetched = $sql_get_base_folder->fetch_array(MYSQLI_ASSOC);	
$folder_path = $fetched['var_value'];

	echo '<fieldset><legend>Checking the installed modules and certain config settings!</legend><ul>';
		$sql = "select `name`,`folder` from `" . table_prefix."modules`";
		$sql_modules = $handle->query($sql);

	$filename = 'version-update.txt';
	$lines = file($filename, FILE_IGNORE_NEW_LINES);
	$modules_array = array();
	foreach($lines as $line) {
		$modules_array[] = explode(',', $line);
	}
	
	$sql_modules->data_seek(0);
	while ($module = $sql_modules->fetch_assoc()) {
		foreach($modules_array as $modules) {
			if ($module['folder'] == $modules[0]) {
				$sql = "UPDATE `" . table_prefix."modules` SET `version` = '". $modules[1] ."', `latest_version` = '". $modules[1] ."' WHERE `folder` = '" .$module['folder'] ."';";
				$sql_update = $handle->query($sql);
				if (!$sql_update) {
					$marks = $notok;
				}else{
					$marks = $ok;
					printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
					echo '<li>Updated '.$module['name'] . ' module Version <img src="'.$marks.'" class="iconalign" /></li>';
				}
			}	
		}	

		if ($module['folder'] == "links") {
			$warnings[] = "Check the Links module because we added few settings to it <strong style=\"text-decoration:underline;background-color:#0100ff\">YOU HAVE TO GO TO ITS SETTINGS AND SELECT THE NEW OPTIONS THAT YOU WANT; OTHERWISE IT WILL NOT WORK UNTIL YOU DO SO!</strong>!";
		}		
		if ($module['folder'] == "upload") {
			$warnings[] = "We noticed you have the UPLOAD module installed. You have to copy the files from the old Plikli folder, in ".$folder_path."/modules/upload/attachments/ TO the same folder in the new Plikli /".$upgrade_folder."/modules/upload/attachments/.";
			/*Redwine: correcting the default upload fileplace!*/
			$sql_upload_fileplace = "select `data` from `" . table_prefix."misc_data` WHERE `name` = 'upload_fileplace'";
			$sql_fileplace = mysqli_fetch_assoc($handle->query($sql_upload_fileplace));
			if ($sql_fileplace['data'] == 'tpl_plikli_story_who_voted_start') {
				$sql_upload_fileplace_correct = $handle->query("UPDATE `" . table_prefix."misc_data` set `data` = 'upload_story_list_custom' WHERE `name` = 'upload_fileplace'");
				printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
				echo "<li>We corrected the upload_fileplace default to 'upload_story_list_custom'.</li>";
				$warnings[] = "We corrected the upload_fileplace default to 'upload_story_list_custom'.";
			}
		}
		if ($module['folder'] == "admin_snippet") {
			$sql = "ALTER TABLE `" . table_prefix."snippets` ADD `snippet_status` int(1) NOT NULL DEFAULT '1';";
			$sql_add_status = $handle->query($sql);
			if (!$sql_add_status) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Altered `'.table_prefix.'snippets` table to add a status column <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = "Added a Status column to allow Admins to activate/deactivate snippets!</strong>!";
			$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_location` = REPLACE(`snippet_location`, 'kliqqi', 'plikli');";
			$sql_update_snippet_location = $handle->query($sql);
			if (!$sql_update_snippet_location) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Altered `'.table_prefix.'snippets` replaced "kliqqi" with "plikli"in the snippet location <img src="'.$marks.'" class="iconalign" /></li>';
			$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_content` = REPLACE(`snippet_content`, 'KLIQQI', 'PLIKLI');";
			$sql_update_snippet_content = $handle->query($sql);
			if (!$sql_update_snippet_content) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Altered `'.table_prefix.'snippets` replaced "KLIQQI" with "PLIKLI" in the snippet content <img src="'.$marks.'" class="iconalign" /></li>';
			$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_content` = REPLACE(`snippet_content`, 'Kliqqi', 'Plikli');";
			$sql_update_snippet_content = $handle->query($sql);
			if (!$sql_update_snippet_content) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Altered `'.table_prefix.'snippets` replaced "Kliqqi" with "Plikli" in the snippet content <img src="'.$marks.'" class="iconalign" /></li>';
			$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_content` = REPLACE(`snippet_content`, 'kliqqi', 'plikli');";
			$sql_update_snippet_content = $handle->query($sql);
			if (!$sql_update_snippet_content) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Altered `'.table_prefix.'snippets` replaced "kliqqi" with "plikli" in the snippet content <img src="'.$marks.'" class="iconalign" /></li>';
		}
		if ($module['folder'] == 'anonymous') {
			$sql = "UPDATE `" . table_prefix."users` SET `user_email` = 'anonymous@plikli.com' WHERE `user_login` = 'anonymous';";
			$sql_update_email = $handle->query($sql);
			if (!$sql_update_email) {
				$marks = $notok;
			}else{
				$marks = $ok;
				echo '<li>Updated '.$module['folder'] . ' module Version <img src="'.$marks.'" class="iconalign" /></li>';
			}
			printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
			echo '<li>Updated email in the Users table for '.$module['folder'] . ' user <img src="'.$marks.'" class="iconalign" /></li>';
		}
		if ($module['folder'] == 'rss_import') {
			$sql = "ALTER TABLE `" . table_prefix."feed_link` CHANGE `kliqqi_field` `plikli_field` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
			$sql_alter_feed_link = $handle->query($sql);
			if (!$sql_alter_feed_link) {
				$marks = $notok;
			}else{
				$marks = $ok;
				echo '<li>Altered '.$module['folder'] . ' feed_link Table <img src="'.$marks.'" class="iconalign" /></li>';
			}
			printf("Affected rows (ALTERED): %d\n", $handle->affected_rows);
		}
	}
	echo '</ul></fieldset><br />';	

		// Checking some settings to determine if further manual action is required.
	echo '<fieldset><legend>Checking if Allow users to change language is set to 1 and if validate user email is set to true in your config table</legend><ul>';
		$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'user_language';";
		$sql_get_user_language = $handle->query($sql);
		$result = $sql_get_user_language->fetch_array(MYSQLI_ASSOC);
		$allowed_user_languages = '';
		$renamed_allowed_user_languages_files = '';
		if ($result['var_value'] == '1') {
			$sql = "SELECT `user_language` FROM `" . table_prefix."users` WHERE `user_language` != '';";
			$sql_used_user_language = $handle->query($sql);
			if ($sql_used_user_language) {
				$row_used_user_language = $sql_used_user_language->num_rows;
				if ($row_used_user_language > 0) {
				while ($used_language = $sql_used_user_language->fetch_assoc()) {
					$allowed_user_languages .= $used_language['user_language'] . "<br />";
					$file_rename = str_replace(".default", "", "lang_".$used_language['user_language'].".conf.default");
					rename("../languages/lang_".$used_language['user_language'].".conf.default", "../languages/$file_rename");
					chmod("../languages/$file_rename", 0777);
					echo "../languages/$file_rename<br />";
					$renamed_allowed_user_languages_files .= "../languages/$file_rename<br />";
				}
				echo $allowed_user_languages;
					echo '<li>Allow users to change language is set to "'.$result['var_value']. '" in your config table, and detected that the following languages are allowed for users:<br />'.$allowed_user_languages.' We renamed them for you! See Warnings at the end of the upgrade!</li>';
				$warnings[] = 'Allow users to change language is set to "'.$result['var_value']. '" in your config table, and detected that the following languages are allowed for users:<br />'.$allowed_user_languages.' We renamed the language files for you from .default to .conf <br />'.$renamed_allowed_user_languages_files;
				}else{
					echo '<li>Allow users to change language is set to "'.$result['var_value']. '" in your config table, and no renamed language files were detected!</li>';
					$warnings[] = 'Allow users to change language is set to "'.$result['var_value']. '" in your config table, and no renamed language files were detected!';
				}
			}
		}else{
			echo '<li>Allow users to change language is set to default 0. No action is required</li>';
			$warnings[] = 'Allow users to change language is set to "'.$result['var_value']. '". No action is required';
		}
		
			// if validate user email is false or true.
		$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'misc_validate';";
		$sql_get_misc_validate = $handle->query($sql);
		$result = $sql_get_misc_validate->fetch_array(MYSQLI_ASSOC);
		if (trim($result['var_value']) == 'true') {
			echo '<li>Require users to validate their email address is set to default "'. $result['var_value']. '" in your config table. See Warnings at the end of the upgrade!</li>';
			$warnings[] = 'Require users to validate their email address is set to "' .trim($result['var_value']). '" in your config table. You must enter the email you are using for your site in the language file in /languages/ and enter it as the value for PLIKLI_PassEmail_From';
		}else{
			echo '<li>Require users to validate their email address is set to default "'. $result['var_value']. '" No action is required</li>';
		}
	echo '</ul></fieldset><br />';

	echo '<br /><fieldset><legend>Updating the Site Title in all the language files to "'. $_SESSION['sitetitle'] . '"</legend><ul>';
		$replacement = 'PLIKLI_Visual_Name = "'.strip_tags($_SESSION['sitetitle']).'"';
		if (strip_tags($_SESSION['sitetitle']) != '') {
			foreach (glob("../languages/*.{conf,default}", GLOB_BRACE) as $filename) {
				$filedata = file_get_contents($filename);
				$filedata = preg_replace('/PLIKLI_Visual_Name = \"(.*)\"/iu',$replacement,$filedata);
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
						$warnings[] = 'We detected that SEO Method 2 is used. You must EDIT (DO NOT COPY OVER THE OLD ONE) AND RENAME <strong>htaccess.default to .htaccess</strong><br />if you are using Windows, you can rename the file by opening the command line to the root of this folder and type the following and press enter:<br /><strong>rename htaccess.default .htaccess</strong>';
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
					if ($seoMethod['var_name'] == 'Enable_Extra_Fields' && trim($seoMethod['var_value']) == 'true') {
						if ($row_cnt_extra_fields > 0) {
							echo 'We detected that Enable Extra Fields is set to true and one or more extra fields in links table are used. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
							$warnings[] = 'We detected that Enable Extra Fields is set to true and one or more extra fields in links table are used. YOU MUST COPY THE <strong><em>'.$folder_path.'/LIBS/EXTRA_FIELDS.PHP</em></strong> FILE FROM YOUR OLD CMS TO <strong><em>/'.$upgrade_folder.'/LIBS/ FOLDER</em></strong>.';
						}else{
							echo 'We detected that Enable Extra Fields is set to true but Extra fields in links table are not used. No action is required! SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
							$warnings[] = 'We detected that Enable Extra Fields is set to true but Extra fields in links table are not used. No action is required!<br />Should you decide to use the extra fields, you must edit the following files:<br /><strong><em>/libs/extra_fields.php <u>using the Extra Fields Editor in the Dashboard</u></em></strong>';
						}

					}elseif ($seoMethod['var_name'] == 'Enable_Extra_Fields' && trim($seoMethod['var_value']) == 'false') {
						if ($row_cnt_extra_fields > 0) {
							echo 'We detected that Enable Extra Fields is set to false and one or more extra fields in links table are used. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS!<br />';
							$warnings[] = 'We detected that Enable Extra Fields is set to false and one or more extra fields in links table are used. Should you decide to use the extra fields, you must set Enable Extra Fields to true and use the Extra Fields Editor in the Dashboard to edit extra_fields.php if needed!';
						}else{
							echo 'Extra fields in links table are not used. No action is required!<br />';
							$warnings[] = 'Extra fields in links table are not used. No action is required!';
						}
					}
				}
			}
		}
	echo '</ul></fieldset><br />';
	
	echo '<fieldset><legend>Renaming the original folder containing the old Kliqqi files</legend><ul>';
			$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = '\$my_plikli_base';";
			$sql_get_base_folder = $handle->query($sql);
			$result = $sql_get_base_folder->fetch_array(MYSQLI_ASSOC);
			$result['var_value'] = substr($result['var_value'], 1, strlen($result['var_value']));
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
			$success = rename($_SERVER['DOCUMENT_ROOT'].$result['var_value'],$_SERVER['DOCUMENT_ROOT'].$result['var_value'] . "-original");
			if (!$success) {
				$marks = $notok;
				echo '<li class="alert-danger">FAILED to rename the folder ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' To ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original. SEE ADDITIONAL INSTRUCTIONS AT THE END OF THE UPGRADE PROCESS! <img src="'.$marks.'" class="iconalign" /></li>';
				$warnings_rename[] = 'FAILED to rename the folder ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' To ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original <br />The browser or any other application is using one of its files!<br />You have to manually rename it as indicated in the beginning of the warning!';
			}else{
				$marks = $ok;
				echo '<li>RENAMED ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' to ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original <img src="'.$marks.'" class="iconalign" /></li>';
				$warnings_rename[] = '<span class="warn-delete">RENAMED ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . ' to ' . $_SERVER['DOCUMENT_ROOT'].$result['var_value'] . '-original</span>';
			}
		// getting the root folder of the current CMS (the new Plikli) to rename it as it is in the config table under $my_plikli_base
		$arr = explode("/", $_SERVER['SCRIPT_NAME']);
		$first = $arr[1];
		$path = $_SERVER["DOCUMENT_ROOT"] . $first;
		echo "<br />";
		$warnings_rename[] = "you have to manually rename the current folder from:<br />". $path . " to " . $_SERVER["DOCUMENT_ROOT"] . $result['var_value'];
	}else{
		$warnings_rename[] = 'YOU HAVE to rename the folder ' . $result['var_value'] . ' To ' . $result['var_value'] . '-original!';
		// getting the root folder of the current CMS (the new Plikli) to rename it as it is in the config table under $my_plikli_base
		$arr = explode("/", $_SERVER['SCRIPT_NAME']);
		$first = $arr[1];
		$path = $_SERVER["DOCUMENT_ROOT"] . "/$first";
		echo "<br />";
		$warnings_rename[] = "you have to manually rename the current folder from:<br />". $path . " to " . $_SERVER["DOCUMENT_ROOT"] . "/".$result['var_value'];
	}
	
	echo '</ul></fieldset><br />';
	
	//check the CMS version & name 
	echo '<fieldset><legend>Checking and re-ordering the misc_data table!</legend><ul>';
	$sql = "select * from `" . table_prefix."misc_data` where `name` = 'plikli_version';";
	$sql_CMS_version = $handle->query($sql);
	if ($sql_CMS_version) {
		$row_cnt = $sql_CMS_version->num_rows;
		if ($row_cnt) {
			while ($cms_name_version = $sql_CMS_version->fetch_assoc()) {
				//var_dump($cms_name_version);
				echo '<li>CMS name "'. $cms_name_version['name'] .'" and CMS version is "'. $cms_name_version['data'] .'"</li>'; 
			}
		}else{
		$sql_cms_insert = $handle->query("INSERT INTO `" . table_prefix."misc_data` SET `name` = 'plikli_version', `data` = '". $lang['plikli_version'] ."';");
		printf("Affected rows (INSERT): %d\n", $handle->affected_rows);
		echo '<li>inserted CMS_version to "'. $lang['plikli_version'] .'"</li>';
		}
	}	
	
	//empty the data of plikli_update if it contains any version from the old kliqqi
	$sql = "select * from `" . table_prefix."misc_data` where `name` = 'plikli_update';";
	$sql_plikli_update = $handle->query($sql);
	if ($sql_plikli_update) {
		$row_cnt = $sql_plikli_update->num_rows;
		if ($row_cnt) {
			while ($cms_plikli_update = $sql_plikli_update->fetch_assoc()) {
				echo '<li>Plikli Update "'. $cms_plikli_update['name'] .'" and Data "'. $cms_plikli_update['data'] .'"</li>'; 
				$sql_plikli_update_insert = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = '' WHERE `name` = 'plikli_update';");
				printf("Affected rows (UPDATE PLIKLI UPDATE DATA): %d\n", $handle->affected_rows);
			}
		}else{
		$sql_plikli_update_insert = $handle->query("INSERT INTO `" . table_prefix."misc_data` SET `name` = 'plikli_update', `data` = '';");
		printf("Affected rows (UPDATE PLIKLI UPDATE DATA): %d\n", $handle->affected_rows);
		echo '<li>Updated PLIKLI UPDATE DATA</li>';
		}
	}
	
	//reorder the misc_data table to its original sort order
	include('reorder-misc-table.php');
	//END reorder the misc_data table to its original sort order
	
	//reorder config table
	include('reorder-config-table.php');
	//END reorder config table
	
	// if check_spam is true.
	$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'CHECK_SPAM';";
	$sql_get_check_spam = $handle->query($sql);
	$result = $sql_get_check_spam->fetch_array(MYSQLI_ASSOC);
	if (trim($result['var_value']) == 'true') {
		echo '. You are using CHECK_SPAM feature. Files must be copied from your old CMS '.$folder_path.'/logs/  TO /'.$upgrade_folder.'/logs/. See Warnings at the end of the upgrade!';
		$warnings[] = 'CHECK_SPAM is set to "' .trim($result['var_value']). '" in your config table. You must copy the files from your old CMS '.$folder_path.'/logs/ TO the /logs/ directory of the new Plikli, from where you are running the upgrade:<br />'.$folder_path.'/logs/antispam.log TO /'.$upgrade_folder.'/logs/antispam.log<br />'.$folder_path.'/logs/approvedips.log TO /'.$upgrade_folder.'/logs/approvedips.log<br />'.$folder_path.'/logs/bannedips.log TO /'.$upgrade_folder.'/logs/bannedips.log<br />'.$folder_path.'/logs/domain-blacklist.log TO /'.$upgrade_folder.'/logs/domain-blacklist.log<br />'.$folder_path.'/logs/domain-whitelist.log TO /'.$upgrade_folder.'/logs/domain-whitelist.log';
	}else{
		echo '<br />Check Spam is set to default "'. $result['var_value']. '" No action is required';
	}
	
	//check for avatars uploads
	$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'allow_groups_avatar';";
	$sql_get_allow_groups_avatars = $handle->query($sql);
	$result = $sql_get_allow_groups_avatars->fetch_array(MYSQLI_ASSOC);
	$sql = mysqli_query($handle, "SELECT COUNT(`group_avatar`) as UPLOADED FROM `" . table_prefix."groups` where `group_avatar` != '';");
	$uploaded_groups = 0;
		while ($row = $sql->fetch_assoc()) {
			$uploaded_groups = $row['UPLOADED'];
		}
	if ($result) {
		if (trim($result['var_value']) == 'true' && $uploaded_groups > 0) {
			$warnings[] = 'Allow Groups to upload own avatar is set to "'.$result['var_value']. '" in your config table, and some groups have uploaded their own avatar. You must copy the avatars from your old CMS '.$folder_path.'/avatars/groups_uploaded TO /'.$upgrade_folder.'/avatars/groups_uploaded';
		}elseif (trim($result['var_value']) == 'true' && $uploaded_groups == 0) {
			$warnings[] = 'Allow Groups to upload own avatar is set to "'.$result['var_value']. '" in your config table, but no groups have already uploaded their own avatars. NO action is required!';
		}elseif (trim($result['var_value']) == 'false' && $uploaded_groups > 0) {
			$warnings[] = 'Allow Groups to upload own avatar is set to "'.$result['var_value']. '" in your config table, but some groups have already uploaded their own avatars. Just in case you allow Groups to upload own avatar in the future, you must copy the avatars from your old CMS /avatars/groups_uploaded to the the same location in this CMS!';
		}
	}

	$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'Enable_User_Upload_Avatar';";
	$sql_get_allow_users_avatars = $handle->query($sql);
	$result = $sql_get_allow_users_avatars->fetch_array(MYSQLI_ASSOC);
	$sql = mysqli_query($handle, "SELECT COUNT(`user_avatar_source`) as UPLOADED FROM `" . table_prefix."users` where `user_avatar_source` != '';");
	$uploaded_users = 0;
		while ($row = $sql->fetch_assoc()) {
			$uploaded_users = $row['UPLOADED'];
		}
	if ($result) {
		if (trim($result['var_value']) == 'true' && $uploaded_users > 0) {
			$warnings[] = 'Allow User to Upload Avatars is set to "'.$result['var_value']. '" in your config table, and some users have uploaded their own avatar. You must copy the avatars from your old CMS '.$folder_path.'/avatars/user_uploaded TO /'.$upgrade_folder.'/avatars/user_uploaded';
		}elseif (trim($result['var_value']) == 'true' && $uploaded_users = 0) {
			$warnings[] = 'Allow User to Upload Avatars is set to "'.$result['var_value']. '" in your config table, but no users have already uploaded their own avatars. NO action is required!';
		}elseif (trim($result['var_value']) == 'false' && $uploaded_users > 0) {
			$warnings[] = 'Allow User to Upload Avatars is set to "'.$result['var_value']. '" in your config table, but some userss have already uploaded their own avatars. Just in case you allow Users to upload own avatar in the future, you must copy the avatars from your old CMS '.$folder_path.'/avatars/user_uploaded TO /'.$upgrade_folder.'/avatars/user_uploaded';
		}
	}
	//End check for avatars uploads
	echo '</ul></div></fieldset><br />';
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
echo '<fieldset><legend>Renaming Directories Instructions!</legend><div class="alert alert-danger"><ul>';
	echo '<li><span style="background-color:#ffffff;color:#000000;font-weight:bold;">The upgrade process was successful. PLEASE PAY SPECIAL ATTENTION THE ADDITIONAL INSTRUCTIONS BELOW!</span></li>';
	$output = '';
	if ($warnings) {
		foreach ($warnings_rename as $warning_rename) {
			$output.="<li>$warning_rename</li><br />";
		}
		echo $output;
	}
//end of no errors
if ($_SERVER['SERVER_NAME'] == 'localhost') {
	echo file_get_contents("https://www.plikli.com/upgrade/congrats-upgrade-done.html");
}else{
	$url = "https://www.plikli.com/upgrade/congrats-upgrade-done.html";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	echo $data;
}
echo '</ul></div></fieldset><br />';
?>