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
li{marging-left:30px;}
a:link, a:hover, a:visited, a:active{color:#000000}
.btn-primary, btn {margin-left:10px}
.warn-delete{color:#ffe000;font-weight:bold}
</style>';

$notok = 'notok.png';
$ok = 'ok.png';
$warnings = array();

echo '<fieldset><legend>Converting all the Tables to utf8_general_ci and Engine MyISAM</legend><ul>';
  $sql = "SELECT CONCAT( GROUP_CONCAT( table_name ) ,  ';' ) AS statement FROM information_schema.tables WHERE table_schema = '" . EZSQL_DB_NAME. "' AND table_name LIKE  '" .table_prefix."%'";
  $result = $handle->query($sql);
	if ($result) {
		//$marks = $ok;
		$arraytables = $result->fetch_array(MYSQLI_ASSOC);
		$arraytables['statement'] = str_replace(";","",$arraytables['statement']);
		$mytables = explode(",",$arraytables['statement']);
		
		foreach ($mytables as $tname) {
			$sql_alter_tables = $handle->query("ALTER TABLE  `" . $tname . "` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci, ENGINE = MyISAM;");
			if (!$sql_alter_tables) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>Converted Table '. $tname . ' TO CHARACTER SET utf8 COLLATE utf8_general_ci and ENGINE = MyISAM <img src="'.$marks.'" class="iconalign" /></li>';
			if ($tname == table_prefix."files") {
				// Altering and updating "Files" table
				$sql = "ALTER TABLE  `" . $tname . "`  
				CHANGE `file_orig_id`  `file_orig_id` int(11) NOT NULL DEFAULT '0',
				CHANGE `file_number` `file_number` tinyint(4) NOT NULL DEFAULT '0',
				CHANGE `file_ispicture` `file_ispicture` tinyint(4) NOT NULL DEFAULT '0',
				ADD `file_comment_id` int(11) NOT NULL DEFAULT '0';";
				$sql_alter_files = $handle->query($sql);
				if (!$sql_alter_files) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Altered '.$tname . ' table <img src="'.$marks.'" class="iconalign" /></li>';
			}elseif ($tname == table_prefix."spam_comments") {
				// Altering and updating "spam_comments" table
				$sql = "ALTER TABLE  `" . $tname . "`  
				CHANGE `cmt_content`  `cmt_content` text NOT NULL;";
				$sql_alter_spam_comments = $handle->query($sql);
				if (!$sql_alter_spam_comments) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Altered '.$tname . ' table, cmt_content column type <img src="'.$marks.'" class="iconalign" /></li>';
			}elseif ($tname == table_prefix."likes") {
				$sql = "DROP TABLE  `" . $tname . "`;";
				$sql_drop_table_likes = $handle->query($sql);
				if (!$sql_drop_table_likes) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li class="warn-delete">Deleted unsupported '.$tname . ' table <img src="'.$marks.'" class="iconalign" /></li>';
			}elseif ($tname == table_prefix."updates") {
				$sql = "DROP TABLE  `" . $tname . "`;";
				$sql_drop_table_updates = $handle->query($sql);
				if (!$sql_drop_table_updates) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li class="warn-delete">Deleted unsupported '.$tname . ' table <img src="'.$marks.'" class="iconalign" /></li>';
			}elseif ($tname == table_prefix."snippets") {
				$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_location` = REPLACE(`snippet_location`, 'pligg', 'kliqqi');";
				$sql_update_snippet_location = $handle->query($sql);
				if (!$sql_update_snippet_location) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Updated snippets location <img src="'.$marks.'" class="iconalign" /></li>';
				
				$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_content` = REPLACE(`snippet_content`, 'PLIGG', 'KLIQQI'),`snippet_content` = REPLACE(`snippet_content`, 'Pligg', 'Kliqqi') ;";
				$sql_update__snippet_content = $handle->query($sql);
				if (!$sql_update__snippet_content) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Updated snippets content <img src="'.$marks.'" class="iconalign" /></li>';				
			}
		}
	}else{
		$marks = $notok;
		echo 'Could not get the CMS tables from your database!  <img src="'.$marks.'" class="iconalign" />';
		die();
	}
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Categories table.</legend><ul>';
$sql = "ALTER TABLE  `" . table_prefix."categories` 
CHANGE  `category_lang`  `category_lang` VARCHAR( 2 ) NOT NULL DEFAULT  'en',
CHANGE  `category_name` `category_name` VARCHAR( 64 ) NOT NULL DEFAULT '',
CHANGE  `category_safe_name` `category_safe_name` VARCHAR( 64 ) NOT NULL DEFAULT '',
CHANGE  `category_desc` `category_desc` VARCHAR( 255 ) NOT NULL,
CHANGE  `category_keywords` `category_keywords` VARCHAR( 255 ) NOT NULL,
CHANGE  `category_author_level` `category_author_level` ENUM('normal','moderator','admin') NOT NULL DEFAULT 'normal',
CHANGE  `category_author_group` `category_author_group` varchar(255) NOT NULL DEFAULT '';";
$sql_alter_cat = $handle->query($sql);
echo '<li>Updated Categories Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Comments table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."comments` CHANGE  `comment_content`  `comment_content` TEXT NOT NULL;";
$sql_alter_comments = $handle->query($sql);
echo '<li>Updated comments Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Config table.</legend><ul>';

$sql= "ALTER TABLE  `" . table_prefix."config` 
CHANGE  `var_page`  `var_page` VARCHAR( 50 ) NOT NULL,
CHANGE  `var_name`  `var_name` VARCHAR( 100 ) NOT NULL,
CHANGE  `var_value`  `var_value` VARCHAR( 255 ) NOT NULL,
CHANGE  `var_defaultvalue`  `var_defaultvalue` VARCHAR( 50 ) NOT NULL,
CHANGE  `var_optiontext`  `var_optiontext` VARCHAR( 200 ) NOT NULL,
CHANGE  `var_title`  `var_title` VARCHAR( 200 ) NOT NULL,
CHANGE  `var_desc`  `var_desc` TEXT NOT NULL,
CHANGE  `var_method`  `var_method` VARCHAR( 10 ) NOT NULL;";
$sql_alter_config = $handle->query($sql);
echo '<li>Updated Config Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Formulas table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."formulas` 
CHANGE  `type`  `type` VARCHAR( 10 ) NOT NULL,
CHANGE  `title`  `title` VARCHAR( 50 ) NOT NULL,
CHANGE  `formula`  `formula` TEXT NOT NULL;";
$sql_alter_formulas = $handle->query($sql);
echo '<li>Updated formulas Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Groups table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."groups` 
CHANGE  `group_safename`  `group_safename` TEXT NOT NULL,
CHANGE  `group_name`  `group_name` TEXT NOT NULL,
CHANGE  `group_description`  `group_description` TEXT NOT NULL,
CHANGE  `group_privacy`  `group_privacy` ENUM(  'private',  'public',  'restricted' ) NOT NULL ,
CHANGE  `group_avatar`  `group_avatar` VARCHAR( 255 ) NOT NULL,
CHANGE  `group_field1`  `group_field1` VARCHAR( 255 ) NOT NULL,
CHANGE  `group_field2`  `group_field2` VARCHAR( 255 ) NOT NULL,
CHANGE  `group_field3`  `group_field3` VARCHAR( 255 ) NOT NULL,
CHANGE  `group_field4`  `group_field4` VARCHAR( 255 ) NOT NULL,
CHANGE  `group_field5`  `group_field5` VARCHAR( 255 ) NOT NULL,
CHANGE  `group_field6`  `group_field6` VARCHAR( 255 ) NOT NULL;";
$sql_alter_groups = $handle->query($sql);
echo '<li>Updated groups Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Links table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."links`  
CHANGE 	`link_status` `link_status` enum('discard','new','published','abuse','duplicate','page','spam','moderated','draft','scheduled') NOT NULL DEFAULT 'discard',
CHANGE  `link_url`  `link_url` VARCHAR( 512 ) NOT NULL DEFAULT '',
CHANGE  `link_title`  `link_title` TEXT NOT NULL,
CHANGE  `link_content`  `link_content` MEDIUMTEXT NOT NULL,
CHANGE  `link_field1`  `link_field1` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field2`  `link_field2` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field3`  `link_field3` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field4`  `link_field4` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field5`  `link_field5` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field6`  `link_field6` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field7`  `link_field7` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field8`  `link_field8` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field9`  `link_field9` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field10`  `link_field10` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field11`  `link_field11` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field12`  `link_field12` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field13`  `link_field13` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field14`  `link_field14` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `link_field15`  `link_field15` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE 	`link_group_status` `link_group_status` enum('new','published','discard') NOT NULL DEFAULT 'new';";
$sql_alter_links = $handle->query($sql);
echo '<li>Updated links Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Messages table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."messages`  
CHANGE  `title`  `title` VARCHAR( 255 ) NOT NULL DEFAULT '',
CHANGE  `body`  `body` TEXT NOT NULL;";	
$sql_alter_messages = $handle->query($sql);
echo '<li>Updated messages Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Misc_data table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."misc_data`  
CHANGE  `name`  `name` VARCHAR( 20 ) NOT NULL,
CHANGE  `data`  `data` TEXT NOT NULL;";
$sql_alter_misc_data = $handle->query($sql);
echo '<li>Updated misc_data Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Modules table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."modules`  
CHANGE  `name`  `name` varchar(50) NOT NULL,
CHANGE  `folder`  `folder` varchar(50) NOT NULL,
CHANGE  `version` `version` float(10,1) NOT NULL,
CHANGE  `latest_version` `latest_version` float(10,1) NOT NULL,
ADD `weight` int(11) NOT NULL;";
$sql_alter_modules = $handle->query($sql);
echo '<li>Updated modules Table and Added weight column</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Redirects table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."redirects` 
CHANGE  `redirect_old`  `redirect_old` varchar(255) NOT NULL,
CHANGE  `redirect_new`  `redirect_new` varchar(255) NOT NULL;";
$sql_alter_redirects = $handle->query($sql);
echo '<li>Updated redirects Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Tags table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."tags`  
CHANGE  `tag_lang`  `tag_lang` varchar(4) NOT NULL DEFAULT 'en',
CHANGE  `tag_words`  `tag_words` varchar(64) NOT NULL DEFAULT '';";
$sql_alter_tags = $handle->query($sql);
echo '<li>Updated tags Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Tag_cache table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."tag_cache`  
CHANGE  `tag_words`  `tag_words` varchar(64) NOT NULL;";
$sql_alter_tag_cache = $handle->query($sql);
echo '<li>Updated tag_cache Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Totals table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."totals`  
CHANGE  `name`  `name` varchar(10) NOT NULL;";
$sql_alter_totals = $handle->query($sql);
echo '<li>Updated totals Table</li>';
$sql = "UPDATE `" . table_prefix."totals` SET `name` = 'new' WHERE `name` = 'queued';";
	$sql_queued_to_new = $handle->query($sql);
	if (!$sql_queued_to_new) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated name from queued to new in table Totals <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns in Trackbacks table.</legend><ul>';

$sql = "ALTER TABLE  `" . table_prefix."trackbacks`  
CHANGE  `trackback_type`  `trackback_type` ENUM(  'in',  'out' ) NOT NULL DEFAULT 'in',
CHANGE  `trackback_status`  `trackback_status` ENUM(  'ok',  'pendent',  'error' ) NOT NULL DEFAULT  'pendent',
CHANGE  `trackback_url`  `trackback_url` VARCHAR( 200 ) NOT NULL DEFAULT '';";
$sql_alter_trackbacks = $handle->query($sql);
echo '<li>Updated trackbacks Table</li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Adding Columns to Users table.</legend><ul>';

$sql = ("SHOW COLUMNS FROM `" . table_prefix."users` LIKE '%facebook%'");
if ($sql_facebook = $handle->query($sql)) {
	$row_cnt = $sql_facebook->num_rows;
	if (!$row_cnt) {
		$handle->query("ALTER TABLE `" . table_prefix."users` ADD `user_facebook` varchar(64) NOT NULL DEFAULT '';");
	echo '<li>Facebook COLUMN was added to the users table</li>';
}else{
	echo '<li>Facebook COLUMN exists in the users table</li>';
}
}

$sql = ("SHOW COLUMNS FROM `" . table_prefix."users` LIKE '%twitter%'");
if ($sql_twitter = $handle->query($sql)) {
	$row_cnt = $sql_twitter->num_rows;
	if (!$row_cnt) {
		$handle->query("ALTER TABLE `" . table_prefix."users` ADD `user_twitter` varchar(64) NOT NULL DEFAULT '';");
	echo '<li>Twitter COLUMN was added to the users table</li>';
}else{
	echo '<li>Twitter COLUMN exists in the users table</li>';
}
}

$sql = ("SHOW COLUMNS FROM `" . table_prefix."users` LIKE '%linkedin%'");
if ($sql_linkedin = $handle->query($sql)) {
	$row_cnt = $sql_linkedin->num_rows;
	if (!$row_cnt) {
		$handle->query("ALTER TABLE `" . table_prefix."users` ADD `user_linkedin` varchar(64) NOT NULL DEFAULT '';");
	echo '<li>Linkedin COLUMN was added to the users table</li>';
}else{
	echo '<li>Linkedin COLUMN exists in the users table</li>';
}
}

$sql = ("SHOW COLUMNS FROM `" . table_prefix."users` LIKE '%googleplus%'");
if ($sql_googleplus = $handle->query($sql)) {
	$row_cnt = $sql_googleplus->num_rows;
	if (!$row_cnt) {
		$handle->query("ALTER TABLE `" . table_prefix."users` ADD `user_googleplus` varchar(64) NOT NULL DEFAULT '';");
	echo '<li>Googleplus COLUMN was added to the users table</li>';
}else{
	echo '<li>Googleplus COLUMN exists in the users table</li>';
}
}

$sql = ("SHOW COLUMNS FROM `" . table_prefix."users` LIKE '%pinterest%'");
if ($sql_pinterest = $handle->query($sql)) {
	$row_cnt = $sql_pinterest->num_rows;
	if (!$row_cnt) {
		$handle->query("ALTER TABLE `" . table_prefix."users` ADD `user_pinterest` varchar(64) NOT NULL DEFAULT '';");
	echo '<li>Pinterest COLUMN was added to the users table</li>';
}else{
	echo '<li>Pinterest COLUMN exists in the users table</li>';
}
}
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Changing Columns and updating user level in Users table.</legend><ul>';


	/* Redwine: to respectively change the user level 'god' and 'admin' to 'admin' and 'moderator', we have to alter the current enum first and add moderator to it. */
$sql = "ALTER TABLE  `" . table_prefix."users`  
	CHANGE  `user_level`  `user_level` ENUM(  'normal','admin','god','moderator','Spammer' ) NOT NULL DEFAULT 'normal';";
	$handle->query($sql);
	
	/* Redwine: now we select all users with user_level = god and admin and we set the god to admin and admin to moderator.*/
	$sql = "SELECT `user_id`, `user_level` FROM `" . table_prefix."users` WHERE `user_level` = 'admin' or `user_level` = 'god';";
	$sql_get_god_admins = $handle->query($sql);
	while($users = $sql_get_god_admins->fetch_assoc()) {
		if ($users['user_level'] == 'god') {
			$userLevel = 'admin';
		}elseif ($users['user_level'] == 'admin') {
			$userLevel = 'moderator';
		}
		$handle->query("UPDATE `" . table_prefix."users` SET `user_level` = '" . $userLevel . "' WHERE `user_id` = " . $users['user_id']);
	}
	/* Redwine: we also update the user_url where it says http://pligg.com */
	$sql = "UPDATE `" . table_prefix."users` SET `user_url` = REPLACE(`user_url`, 'http://pligg.com', 'http://kliqqi.com');";
	$sql_update_user_url = $handle->query($sql);
	if (!$sql_update_user_url) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated user_url for admin user <img src="'.$marks.'" class="iconalign" /></li>';
	/* Redwine: now we Alter the enum to the correct one that is compliant with kliqqi in the following ALTER statement. */

$sql = "ALTER TABLE  `" . table_prefix."users`  
CHANGE  `user_login` `user_login` varchar(32) NOT NULL DEFAULT '',
CHANGE  `user_level`  `user_level` ENUM(  'normal',  'moderator',  'admin', 'Spammer' ) NOT NULL DEFAULT 'normal',
CHANGE  `user_modification` `user_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
CHANGE  `user_pass` `user_pass` varchar(64) NOT NULL DEFAULT '',
CHANGE  `user_email` `user_email` varchar(128) NOT NULL DEFAULT '',
CHANGE  `user_names` `user_names` varchar(128) NOT NULL DEFAULT '',
CHANGE  `user_url` `user_url` varchar(128) NOT NULL DEFAULT '',
CHANGE  `user_skype` `user_skype` varchar(64) NOT NULL DEFAULT '',
CHANGE  `public_email` `public_email` varchar(64) NOT NULL DEFAULT '',
CHANGE  `user_avatar_source` `user_avatar_source` varchar(255) NOT NULL DEFAULT '',
CHANGE  `user_categories` `user_categories` varchar(255) NOT NULL DEFAULT '',
DROP COLUMN `user_aim`,
DROP COLUMN `user_msn`,
DROP COLUMN `user_yahoo`,
DROP COLUMN `user_gtalk`,
DROP COLUMN `user_irc`,
DROP COLUMN `last_email_friend`;";
$sql_alter_users = $handle->query($sql);
echo '<li>Altered users Table and deleted unused obsolete columns</li>';

echo '</ul></fieldset><br />';
echo '<fieldset><legend>Updating data in Config table.</legend><ul>';

// Update site's language.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='" .$language. "' where `var_name` = '\$language';";
	$sql_site_language = $handle->query($sql);
	if (!$sql_site_language) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated site language entry <img src="'.$marks.'" class="iconalign" /></li>';

// Update urlmethod desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_title` = 'URL Method', `var_desc` ='<strong>1</strong> = Non-SEO Links.<br /> Example: /story.php?title=Example-Title<br /><strong>2</strong> SEO Method. <br />Example: /Category-Title/Story-title/.<br /><strong>Note:</strong> You must rename htaccess.default to .htaccess <strong>AND EDIT IT WHERE THE NOTES ARE!</strong>' where `var_name` = '\$URLMethod';";
	$sql_urlmethod = $handle->query($sql);
	if (!$sql_urlmethod) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title and desc for the "urlmethod" <img src="'.$marks.'" class="iconalign" /></li>';

// Update days_to_publish desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='After this many days posts will not be eligible to move from new to published pages' where `var_name` = 'days_to_publish';";
	$sql_days_to_publish = $handle->query($sql);
	if (!$sql_days_to_publish) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "days_to_publish" <img src="'.$marks.'" class="iconalign" /></li>';

// Update version $trackbackURL
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = 'kliqqi.com', `var_defaultvalue` = 'kliqqi.com', `var_optiontext` = 'kliqqi.com' WHERE `var_name` = '\$trackbackURL';";
	$sql_trackbackURL = $handle->query($sql);
	if (!$sql_trackbackURL) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the trackbackURL value, default value and optiontext <img src="'.$marks.'" class="iconalign" /></li>';

// Update the Location Installed description for my_base_url
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = '<strong>Examples:</strong>\r\n<br />\r\nhttp://demo.kliqqi.com<br />\r\nhttp://localhost<br />\r\nhttp://www.kliqqi.com' WHERE `var_name` =  '\$my_base_url';";
	$sql_my_base_url = $handle->query($sql);
	if (!$sql_my_base_url) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the var_desc where var_name = "my_base_url" <img src="'.$marks.'" class="iconalign" /></li>';

// Update the Location Installed var_name to my_kliqqi_base
	$sql = "UPDATE `" . table_prefix."config` set `var_name` = '\$my_kliqqi_base', `var_desc` = '<strong>Examples:</strong>\r\n<br />\r\n/kliqqi -- if installed in the /kliqqi subfolder<br />\r\nLeave blank if installed in the site root folder.', `var_title` = 'Kliqqi Base Folder' WHERE `var_name` =  '\$my_pligg_base';";
	$sql_my_pligg_base = $handle->query($sql);
	if (!$sql_my_pligg_base) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated name, desc and title for the Location Installed "my_kliqqi_base" <img src="'.$marks.'" class="iconalign" /></li>';

// Update tags_min_pts desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='<strong>Only used if Tags are enabled.</strong> How small should the text for the smallest tags be.' where `var_name` = '\$tags_min_pts';";
	$sql_tags_min_pts = $handle->query($sql);
	if (!$sql_tags_min_pts) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "tags_min_pts" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update tags_max_pts desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='<strong>Only used if Tags are enabled.</strong> How large should the text for the largest tags be.' where `var_name` = '\$tags_max_pts';";
	$sql_tags_max_pts = $handle->query($sql);
	if (!$sql_tags_max_pts) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "tags_max_pts" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update tags_words_limit desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='<strong>Only used if Tags are enabled.</strong> The most tags to show in the cloud.' where `var_name` = '\$tags_words_limit';";
	$sql_tags_words_limit = $handle->query($sql);
	if (!$sql_tags_words_limit) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "tags_words_limit" <img src="'.$marks.'" class="iconalign" /></li>';

// Update CHECK_SPAM title.
	$sql = "UPDATE `" . table_prefix."config` SET `var_title` ='Enable Spam Checking' where `var_name` = 'CHECK_SPAM';";
	$sql_CHECK_SPAM = $handle->query($sql);
	if (!$sql_CHECK_SPAM) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title for the CHECK_SPAM <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update MAIN_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='logs/antispam.log', `var_defaultvalue` = 'logs/antispam.log', `var_optiontext` = 'Text File' where `var_name` = '\$MAIN_SPAM_RULESET';";
	$sql_MAIN_SPAM_RULESET = $handle->query($sql);
	if (!$sql_MAIN_SPAM_RULESET) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "MAIN_SPAM_RULESET" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update USER_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='logs/domain-blacklist.log', `var_defaultvalue` = 'logs/domain-blacklist.log', `var_optiontext` = 'Text File', `var_desc` = 'What file should Kliqqi write to if you mark items as spam?' where `var_name` = '\$USER_SPAM_RULESET';";
	$sql_USER_SPAM_RULESET = $handle->query($sql);
	if (!$sql_USER_SPAM_RULESET) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "USER_SPAM_RULESET" <img src="'.$marks.'" class="iconalign" /></li>';
	
//Inserting a new row FRIENDLY_DOMAINS
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`) VALUES (NULL, 'AntiSpam', '\$FRIENDLY_DOMAINS', 'logs/domain-whitelist.log', 'logs/domain-whitelist.log', 'Text file', 'Local Domain Whitelist File', 'File containing a list of domains that cannot be banned.', 'normal', '\"');";
	$sql_FRIENDLY_DOMAINS = $handle->query($sql);
	if (!$sql_FRIENDLY_DOMAINS) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>INSERTED "FRIENDLY_DOMAINS" Row <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update SPAM_LOG_BOOK value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='logs/spam.log', `var_defaultvalue` = 'logs/spam.log', `var_optiontext` = 'Text File' where `var_name` = '\$SPAM_LOG_BOOK';";
	$sql_SPAM_LOG_BOOK = $handle->query($sql);
	if (!$sql_SPAM_LOG_BOOK) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "SPAM_LOG_BOOK" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Story_Content_Tags_To_Allow_Normal title.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'leave blank to not allow tags. Examples are: &lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;' where `var_name` = 'Story_Content_Tags_To_Allow_Normal';";
	$sql_Story_Content_Tags_To_Allow_Normal = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_Normal) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "Story_Content_Tags_To_Allow_Normal" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update No_URL_Name optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_optiontext` = 'Text' where `var_name` = 'No_URL_Name';";
	$sql_No_URL_Name = $handle->query($sql);
	if (!$sql_No_URL_Name) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "No_URL_Name" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Default_Gravatar_Large optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` = '/avatars/Avatar_100.png', `var_defaultvalue` = '/avatars/Avatar_100.png' where `var_name` = 'Default_Gravatar_Large';";
	$sql_Default_Gravatar_Large = $handle->query($sql);
	if (!$sql_Default_Gravatar_Large) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	$warnings[] = "Avatars large in Kliqqi are 100x100 instead of 30x30. If you have custom avatars for your pligg site, make sure to resize them to 100x100";
	echo '<li>Updated value and defaultvalue for the "Default_Gravatar_Large" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Default_Gravatar_Small optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` = '/avatars/Avatar_32.png', `var_defaultvalue` = '/avatars/Avatar_32.png' where `var_name` = 'Default_Gravatar_Small';";
	$sql_Default_Gravatar_Small = $handle->query($sql);
	if (!$sql_Default_Gravatar_Small) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	$warnings[] = "Avatars small in Kliqqi are 32x32 instead of 15x15. If you have custom avatars for your pligg site, make sure to resize them to 32x32";
	echo '<li>Updated value and defaultvalue for the "Default_Gravatar_Small" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Enable_Extra_Fields optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_title` = 'Enable Extra Fields' where `var_name` = 'Enable_Extra_Fields';";
	$sql_Enable_Extra_Fields = $handle->query($sql);
	if (!$sql_Enable_Extra_Fields) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title for the "Enable_Extra_Fields" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update CommentOrder desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = '<strong>1</strong> = Top rated comments first \r\n<br /><strong>2</strong> = Newest comments first (reverse chronological) \r\n<br /><strong>3</strong> =  Lowest rated comments first \r\n<br /><strong>4</strong> = Oldest comments first (chronological)' where `var_name` = '\$CommentOrder';";
	$sql_CommentOrder = $handle->query($sql);
	if (!$sql_CommentOrder) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the desc for the "CommentOrder" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Voting_Method title and desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_title` = 'Voting Method', `var_desc` = '<strong>1</strong> = Up and Down Voting<br /> <strong>2</strong> = 5 Star Ratings<br /><strong>3</strong> = Karma' where `var_name` = 'Voting_Method';";
	$sql_Voting_Method = $handle->query($sql);
	if (!$sql_Voting_Method) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title and desc for the "Voting_Method" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update SearchMethod title and desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_title` = 'Search Method', `var_desc` = '<strong>1</strong> = uses MySQL MATCH for FULLTEXT indexes (or something). Problems are MySQL STOP words and words less than 4 characters. Note: these limitations do not affect clicking on a TAG to search by it.\r\n<br /><strong>2</strong> = uses MySQL LIKE and is much slower, but returns better results. Also supports \"*\" and \"-\"\r\n<br /><strong>3</strong> = is a hybrid, using method 1 if possible, but method 2 if needed.' where `var_name` = 'SearchMethod';";
	$sql_SearchMethod = $handle->query($sql);
	if (!$sql_SearchMethod) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title and desc for the "SearchMethod" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update dblang optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_optiontext` = 'Text' where `var_name` = '\$dblang';";
	$sql_dblang = $handle->query($sql);
	if (!$sql_dblang) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated optiontext for the "dblang" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update thetemp value, defaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` = 'bootstrap', `var_defaultvalue` = 'bootstrap', `var_optiontext` = 'Text' where `var_name` = '\$thetemp';";
	$sql_thetemp = $handle->query($sql);
	if (!$sql_thetemp) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, defaultvalue and optiontext for the "thetemp" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update track_outgoing_method desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'What identifier should the out.php URL use?' where `var_name` = 'track_outgoing_method';";
	$sql_track_outgoing_method = $handle->query($sql);
	if (!$sql_track_outgoing_method) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "track_outgoing_method" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Avatar_Large value and defaultvalue.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` = '100', `var_defaultvalue` = '100' where `var_name` = 'Avatar_Large';";
	$sql_Avatar_Large = $handle->query($sql);
	if (!$sql_Avatar_Large) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value and defaultvalue for the "Avatar_Large" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update Avatar_Small value and defaultvalue.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` = '32', `var_defaultvalue` = '32' where `var_name` = 'Avatar_Small';";
	$sql_Avatar_Small = $handle->query($sql);
	if (!$sql_Avatar_Small) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value and defaultvalue for the "Avatar_Small" <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the defaultvalue, optiontext and description for table prefix
	$sql = "UPDATE `" . table_prefix."config` set `var_defaultvalue` = 'kliqqi_', `var_optiontext` = 'Text', `var_desc` = 'Table prefix. Ex: kliqqi_ makes the users table become kliqqi_users. Note: changing this will not automatically rename your tables!' WHERE `var_name` =  'table_prefix';";
	$sql_tableprefix = $handle->query($sql);
	if (!$sql_tableprefix) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the defaultvalue, optiontext and description of table prefix. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the description of tags_min_pts_s
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = '<strong>Only used if Tags are enabled.</strong> How small should the text for the smallest tags in the sidebar cloud?' WHERE `var_name` =  'tags_min_pts_s';";
	$sql_tags_min_pts_s = $handle->query($sql);
	if (!$sql_tags_min_pts_s) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the description of tags_min_pts_s. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the description of tags_max_pts_s
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = '<strong>Only used if Tags are enabled.</strong> How big should the text for the largest tags be in the sidebar cloud?' WHERE `var_name` =  'tags_max_pts_s';";
	$sql_tags_max_pts_s = $handle->query($sql);
	if (!$sql_tags_max_pts_s) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value and defaultvalue description of tags_max_pts_s. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the description of tags_words_limit_s
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '30', `var_defaultvalue` = '30', `var_desc` = '<strong>Only used if Tags are enabled.</strong> How many tags to show in the sidebar cloud?' WHERE `var_name` =  'tags_words_limit_s';";
	$sql_tags_words_limit_s = $handle->query($sql);
	if (!$sql_tags_words_limit_s) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, defaultvalue and description of tags_words_limit_s. <img src="'.$marks.'" class="iconalign" /></li>';
		
// Update the description of group_avatar_size_width
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '100', `var_defaultvalue` = '100' WHERE `var_name` =  'group_avatar_size_width';";
	$sql_group_avatar_size_width = $handle->query($sql);
	if (!$sql_group_avatar_size_width) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, defaultvalue and description of group_avatar_size_width. <img src="'.$marks.'" class="iconalign" /></li>';
		
// Update the description of group_avatar_size_height
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '100', `var_defaultvalue` = '100' WHERE `var_name` =  'group_avatar_size_height';";
	$sql_group_avatar_size_height = $handle->query($sql);
	if (!$sql_group_avatar_size_height) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, defaultvalue and description of group_avatar_size_height. <img src="'.$marks.'" class="iconalign" /></li>';
		
// Update the description of votes_per_ip
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'This feature is turned on by default to prevent users from voting from multiple registered accounts from the same computer network. <strong>0</strong> = disable feature.' WHERE `var_name` =  'votes_per_ip';";
	$sql_votes_per_ip = $handle->query($sql);
	if (!$sql_votes_per_ip) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated description of votes_per_ip. <img src="'.$marks.'" class="iconalign" /></li>';
		
// Update the description of limit_time_to_edit
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'This feature allows you to limit the amount of time a user has before they can no longer edit a submitted story.<br /><strong>0</strong> = Unlimited amount of time to edit<br /><strong>1</strong> = specified amount of time' WHERE `var_name` =  'limit_time_to_edit';";
	$sql_limit_time_to_edit = $handle->query($sql);
	if (!$sql_limit_time_to_edit) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated description of limit_time_to_edit. <img src="'.$marks.'" class="iconalign" /></li>';
		
// Update the description of edit_time_limit
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = '<strong>0</strong> = Disable the users ability to ever edit the story. Requires that you enable Limit Time To Edit Stories (set to 1).' WHERE `var_name` =  'edit_time_limit';";
	$sql_edit_time_limit = $handle->query($sql);
	if (!$sql_edit_time_limit) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated description of edit_time_limit. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the optiontext of group_submit_level
	$sql = "UPDATE `" . table_prefix."config` set `var_optiontext` = 'normal,moderator,admin' WHERE `var_name` =  'group_submit_level';";
	$sql_group_submit_level = $handle->query($sql);
	if (!$sql_group_submit_level) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated optiontext of group_submit_level. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the title of Story_Content_Tags_To_Allow_Admin
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'HTML tags to allow for Moderators', `var_desc` = 'leave blank to not allow tags. Examples are: &lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;' WHERE `var_name` =  'Story_Content_Tags_To_Allow_Admin';";
	$sql_Story_Content_Tags_To_Allow_Admin = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_Admin) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title of Story_Content_Tags_To_Allow_Admin. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the title of Story_Content_Tags_To_Allow_God
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'HTML tags to allow for Admins', `var_desc` = 'leave blank to not allow tags. Examples are: &lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;' WHERE `var_name` =  'Story_Content_Tags_To_Allow_God';";
	$sql_Story_Content_Tags_To_Allow_God = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_God) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title of Story_Content_Tags_To_Allow_God. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the description of misc_validate
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Require users to validate their email address?<br />If you set to true, then click on the link below to also set the email to be used for sending the message.<br /><a href=\"../module.php?module=admin_language\">Set the email</a>. Type @ in the filter box and click Filter to get the value to modify. Do not forget to click save.' WHERE `var_name` =  'misc_validate';";
	$sql_misc_validate = $handle->query($sql);
	if (!$sql_misc_validate) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated description of misc_validate. <img src="'.$marks.'" class="iconalign" /></li>';		
		
// Update the value and defaultvalue of maxStoryLength
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '3000', `var_defaultvalue` = '3000' WHERE `var_name` =  'maxStoryLength';";
	$sql_maxStoryLength = $handle->query($sql);
	if (!$sql_maxStoryLength) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value and defaultvalue of maxStoryLength. <img src="'.$marks.'" class="iconalign" /></li>';	
		
// Update the value and defaultvalue of maxSummaryLength
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '600', `var_defaultvalue` = '600' WHERE `var_name` =  'maxSummaryLength';";
	$sql_maxSummaryLength = $handle->query($sql);
	if (!$sql_maxSummaryLength) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value and defaultvalue of maxSummaryLength. <img src="'.$marks.'" class="iconalign" /></li>';	
		
// Update the optiontext, title and desc of buries_to_spam
	$sql = "UPDATE `" . table_prefix."config` set `var_optiontext` = '1 = on / 0 = off', `var_title` = 'Negative Votes Story Discard', `var_desc` = 'If set to 1, stories with enough down votes will be discarded. The formula for determining what gets buried is stored in the database table_formulas. It defaults to discarding stories with 3 times more downvotes than upvotes.' WHERE `var_name` =  'buries_to_spam';";
	$sql_buries_to_spam = $handle->query($sql);
	if (!$sql_buries_to_spam) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated optiontext, title and desc of buries_to_spam. <img src="'.$marks.'" class="iconalign" /></li>';			
	
// Update the title and desc of comment_buries_spam
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'Negative votes to remove comment', `var_desc` = 'Number of negative votes before comment is sent to discard state. <strong>0</strong> = disable feature.' WHERE `var_name` =  'comment_buries_spam';";
	$sql_comment_buries_spam = $handle->query($sql);
	if (!$sql_comment_buries_spam) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title and desc of comment_buries_spam. <img src="'.$marks.'" class="iconalign" /></li>';			
	
// Update the desc of Submit_Complete_Step2
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'If set to false, the user will be presented with a third step where they can preview and submit the story.' WHERE `var_name` =  'Submit_Complete_Step2';";
	$sql_Submit_Complete_Step2 = $handle->query($sql);
	if (!$sql_Submit_Complete_Step2) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc of Submit_Complete_Step2. <img src="'.$marks.'" class="iconalign" /></li>';				
				
// Update the desc of Multiple_Categories
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Setting this to true will allow users to select multiple categories on the submit page.' WHERE `var_name` =  'Multiple_Categories';";
	$sql_Multiple_Categories = $handle->query($sql);
	if (!$sql_Multiple_Categories) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc of Multiple_Categories. <img src="'.$marks.'" class="iconalign" /></li>';				
				
// Inserting Auto_scroll row
	$sql = "INSERT INTO `" . table_prefix."config` VALUES (NULL, 'Misc', 'Auto_scroll', '1', '1', '1-3', 'Pagination Mode', '<strong>1.</strong> Use normal pagination links <br /><strong>2.</strong> JavaScript that automatically adds more articles to the bottom of the page<br /><strong>3</strong> JavaScript button to manually load more articles', 'define', NULL);";
	$sql_Auto_scroll = $handle->query($sql);
	if (!$sql_Auto_scroll) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserting Auto_scroll row <img src="'.$marks.'" class="iconalign" /></li>';				
				
// Inserting Search_Comments row
	$sql = "INSERT INTO `" . table_prefix."config` VALUES (NULL, 'Comments', 'Search_Comments', 'false', 'false', 'true / false', 'Search Comments', 'Use comment data when providing search results', 'define', NULL);";
	$sql_Search_Comments = $handle->query($sql);
	if (!$sql_Search_Comments) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserting Search_Comments row <img src="'.$marks.'" class="iconalign" /></li>';	
		
// Update the Auto Scroll
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '1', `var_defaultvalue` = '1', `var_optiontext` = '1', `var_title` = 'Pagination Mode', `var_desc` = '<strong>1.</strong> Use normal pagination links' WHERE `var_name` =  'Auto_scroll';";
	$sql_auto_scroll = $handle->query($sql);
	if (!$sql_auto_scroll) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated auto_scroll <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the desc of user_language
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Allow users to change Kliqqi language' WHERE `var_name` =  'user_language';";
	$sql_user_language = $handle->query($sql);
	if (!$sql_user_language) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc of user_language. <img src="'.$marks.'" class="iconalign" /></li>';		
	
//Inserting a new LOGO feature
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`) VALUES (NULL, 'Logo', 'Default_Site_Logo', '', '', 'Path to the Site Logo', 'Site Logo', 'Default location of the Site Logo. Just make sure the maximum height of the logo is 40 or 41 px.<BR /> You can have any image extension: PNG, JPG, GIF.<br />Example:<br />/logo.png<br />/templates/bootstrap/img/logo.png', 'define', '''');";
	$sql_Logo = $handle->query($sql);
	if (!$sql_Logo) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>INSERTED "LOGO" settings <img src="'.$marks.'" class="iconalign" /></li>';	
	
//Deleting all Tell-a Friend entries
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_page` = 'Tell-a-Friend';";
	$sql_delete_tell_friend = $handle->query($sql);
	if (!$sql_delete_tell_friend) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted all Tell-a Friend entries <img src="'.$marks.'" class="iconalign" /></li>';
	
//Deleting spell checker entries
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_name` = 'Spell_Checker';";
	$sql_delete_spell_checker = $handle->query($sql);
	if (!$sql_delete_spell_checker) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted Spell Checker entries <img src="'.$marks.'" class="iconalign" /></li>';
	
//Deleting User_Upload_Avatar_Folder entries
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_name` = 'User_Upload_Avatar_Folder';";
	$sql_delete_User_Upload_Avatar_Folder = $handle->query($sql);
	if (!$sql_delete_User_Upload_Avatar_Folder) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted User_Upload_Avatar_Folder entries <img src="'.$marks.'" class="iconalign" /></li>';
	
//Deleting 'enable_gzip_files' entries
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_name` = 'enable_gzip_files';";
	$sql_delete_enable_gzip_files = $handle->query($sql);
	if (!$sql_delete_enable_gzip_files) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted enable_gzip_files entries <img src="'.$marks.'" class="iconalign" /></li>';
	
//Deleting 'Recommend_Time_Limit' entries
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_name` = 'Recommend_Time_Limit';";
	$sql_delete_Recommend_Time_Limit = $handle->query($sql);
	if (!$sql_delete_Recommend_Time_Limit) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted Recommend_Time_Limit entries <img src="'.$marks.'" class="iconalign" /></li>';
//Inserting new rows 
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`)VALUES
	(NULL, 'Location Installed', 'allow_registration', 'true', 'true', 'true / false', 'Allow registration?', 'If for a reason you want to suspend registration, permanently or definitely, set it to false!', 'define', ''),
	(NULL, 'Location Installed', 'disallow_registration_message', 'Registration is temporarily suspended!', '', 'Text', 'Message to display when Registration is suspended', 'Enter the message you want to display.', 'define', ''),
	(NULL, 'Location Installed', '\$maintenance_mode', 'false', 'false', 'true / false', 'Maintenance Mode', 'Set the mode to true when you want to notify the users of the unavailabilty of the site (upgrade, downtime, etc.)<br /><strong>NOTE that only admin can still access the site during maintenance mode!</strong>', 'normal', ''''),
	(NULL, 'Submit', 'Enable_Submit', 'true', 'true', 'true / false', 'Allow Submit', 'Allow users to submit articles?', 'define', NULL),
	(NULL, 'Submit', 'disable_Submit_message', 'Submitting articles is temporarily disabled!', '', 'Text', 'Message to display when Submitting articles is disallowed', 'Enter the message you want to display.', 'define', NULL),
	(NULL, 'Submit', 'Allow_Draft', 'false', 'false', 'true / false', 'Allow Draft Articles?', 'Set it to true to allow users to save draft articles', 'define', ''),
	(NULL, 'Submit', 'Allow_Scheduled', 'false', 'false', 'true / false', 'Allow Scheduled Articles?', 'Set it to true to allow users to save scheduled articles.<br /><strong>If you set to true, then you MUST install the <u>scheduled_posts</u> Module.</strong>', 'define', ''),
	(NULL, 'Story', 'link_nofollow', 'true', 'true', 'true / false', 'Use rel=\"nofollow\"', 'nofollow is a value that can be assigned to the rel attribute of an HTML a element to instruct some search engines that the hyperlink should not influence the ranking of the link''s target in the search engine''s index.<br /><a href=\"https://support.google.com/webmasters/answer/96569?hl=en\" target=\"_blank\">Google: policies</a>', 'define', NULL),
	(NULL, 'Comments', 'Enable_Comments', 'true', 'true', 'true / false', 'Allow Comments', 'Allow users to comment on articles?', 'define', NULL),
	(NULL, 'Comments', 'disable_Comments_message', 'Comments are temporarily disabled!', '', 'Text', 'Message to display when Comments are disallowed', 'Enter the message you want to display.', 'define', NULL),
	(NULL, 'Groups', 'allow_groups_avatar', 'true', 'true', 'true/false', 'Allow Groups to upload own avatar', 'Should groups be allowed to upload own avatar?', 'define', 'NULL'),
	(NULL, 'Groups', 'max_group_avatar_size', '200', '200KB', 'number', 'Maximum image size allowed to upload', 'Set the maximum image size for the group avatar to upload.', 'define', 'NULL'),
	(NULL, 'Avatars', 'max_avatar_size', '200', '200KB', 'number', 'Maximum image size allowed to upload', 'Set the maximum image size a user can upload.', 'define', '''');";
	$sql_new_config = $handle->query($sql);
	if (!$sql_new_config) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	$warnings[] = "Added new settings to the config table:<ol><strong>Under Location Installed Section</strong><li>Allow/Disallow Registration</li><li>Disallow Registration message</li><li>Set the maintenance Mode for the site</li><strong>Under Submit Section</strong><li>Enable/Disable Submit</li><li>Disable Submit message</li><li>Enable/disable Allow Draft; users can save articles as draft for later publishing</li><li>Enable/disable Allow Scheduled; users can save articles as scheduled for later publishing</li><strong>Under Story Section</strong><li>Enable/disable link nofollow for the story URL that is linked in the title on the Story page and the original site that appears in the toolsbar under the title</li><strong>Under Comments Section</strong><li>Enable/Disable Comments</li><li>Disable Comments message</li><strong>Under Groups Section</strong><li>Allow Groups Avatar change</li><li>Set Maximum Group Avatar size to upload</li><strong>Under Avatars Section</strong><li>Set Maximum Avatar size to upload</li></ol>";
	echo '<li>INSERTED many new settings in the config table (read the notes at the end of the upgrade process) <img src="'.$marks.'" class="iconalign" /></li>';	
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Updating data in Links table.</legend><ul>';
	
// Update the content of the about page in links table
	$sql = "UPDATE `" . table_prefix."links` SET `link_content`= '<legend><strong>About Us</strong></legend><p>Our site allows you to submit an article that will be voted on by other members. The most popular posts will be published to the front page, while the less popular articles are left in an \'New\' page until they acquire the set number of votes to move to the published page. This site is dependent on user contributed content and votes to determine the direction of the site.</p>\r\n', `link_group_status` = 'new' where `link_status` = 'page';";
	$sql_about_page = $handle->query($sql);
	if (!$sql_about_page) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the content of the about page in links table. <img src="'.$marks.'" class="iconalign" /></li>';
	
// Update the link status in links table from queued to new.
	$sql = "UPDATE `" . table_prefix."links` SET `link_status`= 'new', `link_group_status` = 'new' where `link_status` = '';";
	$sql_about_page = $handle->query($sql);
	if (!$sql_about_page) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the link status in links table from queued to new. <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Updating data in Misc_data table.</legend><ul>';
	
$sql = "select * from `" . table_prefix."misc_data` where name like '%adcopy%'";
$sql_adcopy = $handle->query($sql);
if ($sql_adcopy) {
	$row_cnt = $sql_adcopy->num_rows;
	if ($row_cnt) {
		while ($adcopy = $sql_adcopy->fetch_assoc()) {
		if (in_array('adcopy_lang',$adcopy)) {
				$sql_adcopy_lang = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'en' WHERE `name` = 'adcopy_lang';");
			$marks = $ok;
			echo '<li>updated adcopy_lang <img src="'.$marks.'" class="iconalign" /></li>';
		}elseif (in_array('adcopy_theme',$adcopy)) {
				$sql_adcopy_theme = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'white' WHERE `name` = 'adcopy_theme';");
			$marks = $ok;
			echo '<li>Updated adcopy_theme <img src="'.$marks.'" class="iconalign" /></li>';
		}elseif (in_array('adcopy_pubkey',$adcopy)) {
				$sql_adcopy_pubkey = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = '1G9ho6tcbpytfUxJ0SlrSNt0MjjOB0l2' WHERE `name` = 'adcopy_pubkey';");
			$marks = $ok;
			echo '<li>Updated adcopy_pubkey <img src="'.$marks.'" class="iconalign" /></li>';
		}elseif (in_array('adcopy_privkey',$adcopy)) {
				$sql_adcopy_privkey = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'PjH8h3gpjQrBKihJ8dlLN8sbcmvW1nv-' WHERE `name` = 'adcopy_privkey';");
			$marks = $ok;
			echo '<li>Updated adcopy_privkey <img src="'.$marks.'" class="iconalign" /></li>';
		}elseif (in_array('adcopy_hashkey',$adcopy)) {
				$sql_adcopy_hashkey = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = 'eq1xxSfyG55k4ll7CxPCO6XP9-cIWnTf' WHERE `name` = 'adcopy_hashkey';");
			$marks = $ok;
			echo '<li>Updated adcopy_hashkey <img src="'.$marks.'" class="iconalign" /></li>';
		}
	}
}else{
	$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
			VALUES ('adcopy_lang', 'en'),
			('adcopy_theme', 'white'),
			('adcopy_pubkey', '1G9ho6tcbpytfUxJ0SlrSNt0MjjOB0l2'),
			('adcopy_privkey', 'PjH8h3gpjQrBKihJ8dlLN8sbcmvW1nv-'),
				('adcopy_hashkey', 'eq1xxSfyG55k4ll7CxPCO6XP9-cIWnTf'),
				('captcha_comment_en', 'true'),
				('captcha_reg_en', 'true'),
				('captcha_story_en', 'true')";
		$sql_captcha_data = $handle->query($sql);
	if (!$sql_captcha_data) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserted captcha lang, theme, public, private and hash keys <img src="'.$marks.'" class="iconalign" /></li>';
}
}
// Update captcha method.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = 'solvemedia' where `name` = 'captcha_method';";
	$sql_captcha_method = $handle->query($sql);
	if (!$sql_captcha_method) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated captcha methode to solvemedia <img src="'.$marks.'" class="iconalign" /></li>';

// Update CMS version.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = '" . $lang['kliqqi_version'] . "' where `name` = 'pligg_version';";
	$sql_CMS_version = $handle->query($sql);
	if (!$sql_CMS_version) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated CMS_version <img src="'.$marks.'" class="iconalign" /></li>';	

// Update CMS name.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `name` = 'kliqqi_version' where `name` = 'pligg_version';";
	$sql_CMS_name = $handle->query($sql);
	if (!$sql_CMS_name) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated CMS Name <img src="'.$marks.'" class="iconalign" /></li>';	

	// Insert karma_story_unvote, and the new fields for kliqqi version and mudules updates.
	$sql = "INSERT INTO `" . table_prefix."misc_data` ( `name` , `data` ) VALUES 
	('karma_story_unvote','-1'),
	('modules_update_date',DATE_FORMAT(NOW(),'%Y/%m/%d')),
	('modules_update_url','http://www.kliqqi.com/mods/version-update.txt'),
	('kliqqi_update',''),
	('kliqqi_update_url','http://www.kliqqi.com/download_kliqqi/'),
	('modules_update_unins',''),
	('modules_upd_versions','');";
	$sql_karma_story_unvote = $handle->query($sql);
	if (!$sql_karma_story_unvote) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserted karma_story_unvote value in "Misc_data" table <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update data column to replace all instances of tpl_pligg with tpl_kliqqi.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = REPLACE(`data`, 'tpl_pligg', 'tpl_kliqqi');";
	$sql_update_data_column = $handle->query($sql);
	if (!$sql_update_data_column) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated data column to replace all instances of tpl_pligg with tpl_kliqqi <img src="'.$marks.'" class="iconalign" /></li>';


	
/*	// Delete all the status module entries
	$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'status_%';";
	$sql_delete_status_entries = $handle->query($sql);
	if (!$sql_delete_status_entries) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted all the status module entries <img src="'.$marks.'" class="iconalign" /></li>';*/
	
	// Delete all reCaptcha entries
	$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'reCaptcha_%';";
	$sql_delete_recaptcha_entries = $handle->query($sql);
	if (!$sql_delete_recaptcha_entries) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted all reCaptcha entries <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';
echo '<fieldset><legend>Updating data in Widgets table.</legend><ul>';
	$sql = "select `name` from `" . table_prefix."widgets`";
	$sql_widgets = $handle->query($sql);
	if ($sql_widgets) {
		$row_cnt = $sql_widgets->num_rows;
		if ($row_cnt) {
			while ($widget = $sql_widgets->fetch_assoc()) {	
				if (in_array('Admin Panel Tools',$widget)) {
					// Update table widgets; changing the name of Admin Panel Tools to Dashboard Tools
									$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Dashboard Tools', `folder` = 'dashboard_tools' WHERE `name` = 'Admin Panel Tools';";
									$sql_widget_dashboard = $handle->query($sql);
					if (!$sql_widget_dashboard) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Updated table widgets; changing the name of Admin Panel Tools to Dashboard Tools <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Module Settings',$widget)) {
					$sql = "DELETE FROM `" . table_prefix."widgets` where `name` = 'Module Settings';";
					$sql_widget_delete_Module_Settings = $handle->query($sql);
					if (!$sql_widget_delete_Module_Settings) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li class="warn-delete">Deleted obsolete "Module Settings" widget from table widgets <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Statistics',$widget)) {
					// Update table widgets; changing the version of statistics widget
					$sql = "UPDATE `" . table_prefix."widgets` SET `version` = '3.0' WHERE `name` = 'Statistics';";
					$sql_widget_statistics = $handle->query($sql);
					if (!$sql_widget_statistics) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Updated table widgets; changing the version of statistics widget <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Pligg CMS',$widget)) {
	// Update table widgets; changing the name of Pligg CMS to Kliqqi CMS
					$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Kliqqi CMS', `folder` = 'kliqqi_cms', `version` = '1.0' WHERE `name` = 'Pligg CMS';";
					$sql_widget_kliqqi_cms = $handle->query($sql);
					if (!$sql_widget_kliqqi_cms) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Update table widgets; changing the name of Pligg CMS to Kliqqi CMS <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Pligg News',$widget)) {
					// Update table widgets; changing the name of Pligg News to Kliqqi News
					$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Kliqqi News', `folder` = 'kliqqi_news' WHERE `name` = 'Pligg News';";
					$sql_widget_kliqqi_news = $handle->query($sql);
					if (!$sql_widget_kliqqi_news) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Update table widgets; changing the name of Pligg News to Kliqqi News <img src="'.$marks.'" class="iconalign" /></li>';
					
					// Update misc_data table; setting the news count in case it does not exist.
					$sql = "UPDATE IGNORE `" . table_prefix."misc_data` SET `name` = 'news_count', `data` = '3';";
					$sql_news_count = $handle->query($sql);
					if (!$sql_news_count) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Update misc_data table setting the new_count to 3 for Kliqqi News widgets <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('New products',$widget) || in_array('New Products',$widget)) {
					// Update table widgets; changing the name of Pligg News to Kliqqi News
					$sql = "DELETE FROM `" . table_prefix."widgets` WHERE `name` = 'New products' OR `name` = 'New Products';";
					$sql_widget_kliqqi_news = $handle->query($sql);
					if (!$sql_widget_kliqqi_news) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li class="warn-delete">Deleted obsolete "New products" widget from table widgets <img src="'.$marks.'" class="iconalign" /></li>';
				}
			}
			
		}
	}
	echo '</ul></fieldset><br />';
	// Updating Modules table
	echo '<fieldset><legend>Updating data in Modules table.</legend><ul>';
	$sql = "select `name`,`folder` from `" . table_prefix."modules`";
	$sql_modules = $handle->query($sql);
	$to_delete = array('Admin Help English','Pligg Auto Update module','CodeMirror Template Editor','Human Check','Hello World','Google Adsense Revenue Sharing','Sidebar Top Today','Status');
	while ($module = $sql_modules->fetch_assoc()) {
		if (in_array($module['name'],$to_delete)) {
			$sql = "DELETE FROM `" . table_prefix."modules` where `name` = '" .$module['name'] ."';";
			$sql_delete = $handle->query($sql);
			if (!$sql_delete) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li class="warn-delete">Deleted obsolete '.$module['name'] . ' module from table modules <img src="'.$marks.'" class="iconalign" /></li>';
		}
			//If module is Google Adsense Revenue Sharing, then we delete the google_adsense columns in users table
		if ($module['name'] == 'Google Adsense Revenue Sharing') {
			$sql = "ALTER TABLE  `" . table_prefix."users` 
			DROP COLUMN `google_adsense_id`,
			DROP COLUMN `google_adsense_channel`,
			DROP COLUMN `google_adsense_percent`;";
			$sql_delete_google_adsense = $handle->query($sql);
			if (!$sql_delete_google_adsense) {
				$marks = $notok;
			}else{
				$marks = $ok;
				$warnings[] = "We detected 'Google Adsense Revenue Sharing' module installed. This module is not supported by Kliqqi, so we removed it from the installed modules and we deleted the related columns from the users table. Just in case you need the id/channel/percent for your own ussage, you can get them from your database backup!";
			}
			echo '<li class="warn-delete">Deleted obsolete columns in users table belonging to '.$module['name'] . ' module <img src="'.$marks.'" class="iconalign" /></li>';
		}elseif ($module['name'] == 'Status' || $module['name'] == 'Status Update Module') {
			$sql = "ALTER TABLE  `" . table_prefix."users`  
			DROP COLUMN `status_switch`,
			DROP COLUMN `status_friends`,
			DROP COLUMN `status_story`,
			DROP COLUMN `status_comment`,
			DROP COLUMN `status_email`,
			DROP COLUMN `status_group`,
			DROP COLUMN `status_all_friends`,
			DROP COLUMN `status_friend_list`,
			DROP COLUMN `status_excludes`;";
			$sql_alter_users_status = $handle->query($sql);
			if (!$sql_alter_users_status) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li class="warn-delete">Deleted obsolete columns in users table belonging to '.$module['name'] . ' module <img src="'.$marks.'" class="iconalign" /></li>';
			
			// Delete all the status module entries
			$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'status_%';";
			$sql_delete_status_entries = $handle->query($sql);
			if (!$sql_delete_status_entries) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li class="warn-delete">Deleted all the status module entries <img src="'.$marks.'" class="iconalign" /></li>';
		}
	}

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
				$sql = "UPDATE `" . table_prefix."modules` SET `version` = '". $modules[1] ."' WHERE `folder` = '" .$module['folder'] ."';";
				$sql_update = $handle->query($sql);
				if (!$sql_update) {
					$marks = $notok;
				}else{
					$marks = $ok;
					echo '<li>Updated '.$module['name'] . ' module Version <img src="'.$marks.'" class="iconalign" /></li>';
				}
			}	
		}		

		if ($module['folder'] == "links") {
			// Insert new Links module settings.
			$sql = "INSERT INTO `" . table_prefix."misc_data` ( `name` , `data` ) VALUES 
			('links_all', '1'),
			('links_moderators', ''),
			('links_admins', '');";
			$sql_new_links_module_settings = $handle->query($sql);
			if (!$sql_new_links_module_settings) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>Inserted new Links module settings in "Misc_data" table. (See instructions at the end of upgrade <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = "Check the Links module because we added few settings to it <strong style=\"text-decoration:underline;background-color:#0100ff\">YOU HAVE TO GO TO ITS SETTINGS AND SELECT THE NEW OPTIONS THAT YOU WANT; OTHERWISE IT WILL NOT WORK UNTIL YOU DO SO!</strong>!";
		}		
		if ($module['folder'] == "upload") {
			$warnings[] = "We noticed you have the UPLOAD module installed. You have to copy the files from the old Pligg folder, in /modules/upload/attachments to the same folder in the new Kliqqi.";
			/*Redwine: correcting the default upload fileplace!*/
			$sql_upload_fileplace = "select `data` from `" . table_prefix."misc_data` WHERE `name` = 'upload_fileplace'";
			$sql_fileplace = mysqli_fetch_assoc($handle->query($sql_upload_fileplace));
			if ($sql_fileplace['data'] == 'tpl_kliqqi_story_who_voted_start') {
				$sql_upload_fileplace_correct = $handle->query("UPDATE `" . table_prefix."misc_data` set `data` = 'upload_story_list_custom' WHERE `name` = 'upload_fileplace'");
				echo "<li>We corrected the upload_fileplace default to 'upload_story_list_custom'.</li>";
				$warnings[] = "We corrected the upload_fileplace default to 'upload_story_list_custom'.";
			}
		}
		if ($module['folder'] == 'anonymous') {
			$sql = "UPDATE `" . table_prefix."users` SET `user_email` = 'anonymous@kliqqi.com' WHERE `user_login` = 'anonymous';";
			$sql_update_email = $handle->query($sql);
			if (!$sql_update_email) {
				$marks = $notok;
			}else{
				$marks = $ok;
				echo '<li>Updated '.$module['folder'] . ' module Version <img src="'.$marks.'" class="iconalign" /></li>';
			}
			echo '<li>Updated email in the Users table for '.$module['folder'] . ' user <img src="'.$marks.'" class="iconalign" /></li>';
		}
		if ($module['folder'] == 'akismet') {
			$sql = "select `data` from `" . table_prefix."misc_data` WHERE `name` = 'wordpress_key'";
			$sql_key = mysqli_fetch_assoc($handle->query($sql));
			if ($sql_key['data'] != '') {
				echo "<li>We detected you are using the Akismet module and found its key in the misc_data table!</li>";
			}else{
				echo '<li class="warn-delete">We detected you are using the Akismet module but did not find its key in the misc_data table!';
				$warnings[] = "You have Akismet module installed but its has no Wordpress key!";
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
			echo '<li>Altered `'.table_prefix.'snippets` table to add a status column <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = "Added a Status column to allow Admins to activate/deactivate snippets!</strong>!";
		}		
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

echo '<fieldset><legend>Renaming the orginal folder containing the old Pligg files</legend><ul>';
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

		// if check_spam is true.
	$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = 'CHECK_SPAM';";
	$sql_get_check_spam = $handle->query($sql);
	$result = $sql_get_check_spam->fetch_array(MYSQLI_ASSOC);
	echo "<br />CHECK_SPAM = " . $result['var_value'];
	if (trim($result['var_value']) == 'true') {
		echo 'You are using CHECK_SPAM feature. Files content must be copied from your old pligg version .'. $lang['kliqqi_version'] . ' to the files in the root directory of the new kliqqi. See Warnings at the end of the upgrade!';
		$warnings[] = 'CHECK_SPAM is set to "' .trim($result['var_value']). '" in your config table. You must copy the content of these files from your old pligg version .'. $lang['kliqqi_version'] . ' to the root directory of the new kliqqi, from where you are running the upgrade:<br />/antispam.txt to /logs/antispam.log<br />/local-antispam.txt to logs/domain-blacklist.log<br />/spamlog.log to logs/spam.log';
	}else{
		echo '<br />Require users to validate their email address is set to default "'. $result['var_value']. '" No action is required';
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