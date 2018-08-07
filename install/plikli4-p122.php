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
				$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_location` = REPLACE(`snippet_location`, 'pligg', 'plikli');";
				$sql_update_snippet_location = $handle->query($sql);
				if (!$sql_update_snippet_location) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Updated snippets location <img src="'.$marks.'" class="iconalign" /></li>';
				
				$sql = "UPDATE `" . table_prefix."snippets` SET `snippet_content` = REPLACE(`snippet_content`, 'PLIGG', 'PLIKLI'),`snippet_content` = REPLACE(`snippet_content`, 'Pligg', 'Plikli') ;";
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
CHANGE  `link_url`  `link_url` VARCHAR( $urllength ) NOT NULL DEFAULT '',
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
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED name from queued to new): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';

	//inserting new name and total for draft and scheduled articles
	$sql = "INSERT INTO `" . table_prefix."totals` (`name`, `total`) VALUES ('draft', 0), ('scheduled', 0);";
	$sql_insert_totals = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (INSERTED 2 new link statuses in the Totals table): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
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
	$sql = "UPDATE `" . table_prefix."users` SET `user_url` = REPLACE(`user_url`, 'http://pligg.com', 'https://www.plikli.com');";
	$sql_update_user_url = $handle->query($sql);
	if (!$sql_update_user_url) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated user_url for admin user <img src="'.$marks.'" class="iconalign" /></li>';
	/* Redwine: now we Alter the enum to the correct one that is compliant with plikli in the following ALTER statement. */

$sql = "ALTER TABLE  `" . table_prefix."users`  
CHANGE  `user_login` `user_login` varchar(32) NOT NULL DEFAULT '',
CHANGE  `user_level`  `user_level` ENUM(  'normal',  'moderator',  'admin', 'Spammer' ) NOT NULL DEFAULT 'normal',
CHANGE  `user_modification` `user_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
CHANGE  `user_pass` `user_pass` varchar(80) NOT NULL DEFAULT '',
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
//End general alters and insert.

//start work on the config table.
echo '<fieldset><legend>MODIFICATIONS TO THE CONFIG Table.</legend><ul>';
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
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='<strong>1</strong> = Non-SEO Links.<br /> Example: /story.php?title=Example-Title<br /><strong>2</strong> SEO Method. <br />Example: /Category-Title/Story-title/.<br /><strong>Note:</strong> You must rename htaccess.default to .htaccess <strong>AND EDIT IT WHERE MODIFICATIONS ARE NOTED!</strong>' where `var_name` = '\$URLMethod';";
	$sql_urlmethod = $handle->query($sql);
	if (!$sql_urlmethod) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of the "urlmethod" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update days_to_publish desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='After this many days posts will not be eligible to move from new to published pages' where `var_name` = 'days_to_publish';";
	$sql_days_to_publish = $handle->query($sql);
	if (!$sql_days_to_publish) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "days_to_publish" <img src="'.$marks.'" class="iconalign" /></li>';
	
	//replacing pligg instances with plikli in trackback
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = 'plikli.com', `var_defaultvalue` = 'plikli.com', `var_optiontext` = 'plikli.com' WHERE `var_name` = '\$trackbackURL';";
	$sql_trackbackURL = $handle->query($sql);
	if (!$sql_trackbackURL) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated the trackbackURL value, default value and optiontext to plikli.com <img src="'.$marks.'" class="iconalign" /></li>';
	
	//Deleted the obsolete SubmitSummary_Allow_Edit entry
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_name` = 'SubmitSummary_Allow_Edit';";
	$sql_del_allow_summary = $handle->query($sql);
	if (!$sql_del_allow_summary) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (DELETE): %d\n", $handle->affected_rows);
	echo '<li>Deleted the obsolete SubmitSummary_Allow_Edit entry <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update allow extra fields desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='Enable extra fields when submitting stories?<br /><strong>When SET to TRUE, you have to edit the /libs/extra_fields.php file, using the NEW <a href=\"../admin/admin_xtra_fields_editor.php\" target=\"_blank\" rel=\"noopener noreferrer\">Extra Fields Editor</a> in the Dashboard!</strong>' where `var_name` = 'Enable_Extra_Fields';";
	$sql_extra_fields = $handle->query($sql);
	if (!$sql_extra_fields) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of the "Enable_Extra_Fields" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update Story_Content_Tags_To_Allow_Normal title.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'leave blank to not allow tags. Examples are: &lt;br&gt;&lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;<br /><strong style=\"color:#ff0000;\">NEVER ALLOW OTHER THAN THESE TAGS, ESPECIALLY FORM, SCRIPT, IMG, SVG AND IFRAME TAGS!</strong>' where `var_name` = 'Story_Content_Tags_To_Allow_Normal';";
	$sql_Story_Content_Tags_To_Allow_Normal = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_Normal) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of "Story_Content_Tags_To_Allow_Normal" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Story_Content_Tags_To_Allow_Admin
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'HTML tags to allow for Moderators', `var_desc` = 'leave blank to not allow tags. Examples are: &lt;br&gt;&lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;<br /><strong style=\"color:#ff0000;\">NEVER ALLOW OTHER THAN THESE TAGS, ESPECIALLY FORM, SCRIPT, IMG, SVG AND IFRAME TAGS!</strong>' WHERE `var_name` =  'Story_Content_Tags_To_Allow_Admin';";
	$sql_Story_Content_Tags_To_Allow_Admin = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_Admin) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of "Story_Content_Tags_To_Allow_Admin" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Story_Content_Tags_To_Allow_God
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'HTML tags to allow for Admins', `var_desc` = 'leave blank to not allow tags. Examples are: &lt;br&gt;&lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;<br /><strong style=\"color:#ff0000;\">NEVER ALLOW OTHER THAN THESE TAGS, ESPECIALLY FORM, SCRIPT, IMG, SVG AND IFRAME TAGS!</strong>' WHERE `var_name` =  'Story_Content_Tags_To_Allow_God';";
	$sql_Story_Content_Tags_To_Allow_God = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_God) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of "Story_Content_Tags_To_Allow_God" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the description of Show the URL Input Box
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Show the URL input box in submit step 1.<br /><strong>It is by default set to true. If you plan on allowing both URL and Editorial story submission, then you keep it set to true. However, if you only want to allow Editorial story submission, then set it to false!</strong>' WHERE `var_name` =  'Submit_Show_URL_Input';";
	$sql_Submit_Show_URL_Input = $handle->query($sql);
	if (!$sql_Submit_Show_URL_Input) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of "Submit_Show_URL_Input" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the description of Use Story Title as External Link
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Use the story title as link to story\'s website. <strong>NOTE that if you set it to true, the title will link directly to the original story link even when the story is displayed in summary mode!</strong>' WHERE `var_name` =  'use_title_as_link';";
	$sql_Story_Title_as_External_Link = $handle->query($sql);
	if (!$sql_Story_Title_as_External_Link) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated description of Story Title as External Link <img src="'.$marks.'" class="iconalign" /></li>';
	
	//Update the description of my base url
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = REPLACE(`var_desc`, 'pligg', 'plikli') WHERE `var_name` = '\$my_base_url';";
	$sql_mybaseurl = $handle->query($sql);
	if (!$sql_mybaseurl) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated the description of my base url, replacing pligg instances with plikli. <img src="'.$marks.'" class="iconalign" /></li>';
	
	//Update the title and description of my plikli base
	$sql = "UPDATE `" . table_prefix."config` SET `var_name` = '\$my_plikli_base', `var_title` = REPLACE(`var_title`, 'Pligg', 'Plikli'), `var_desc` = REPLACE(`var_desc`, 'pligg', 'plikli') WHERE `var_name` = '\$my_pligg_base';";
	$sql_mypliklibase = $handle->query($sql);
	if (!$sql_mypliklibase) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated the title and description of my plikli base, replacing pligg instances with plikli. <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update USER_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='logs/domain-blacklist.log', `var_defaultvalue` = 'logs/domain-blacklist.log', `var_optiontext` = 'Text File', `var_desc` = 'What file should Plikli write to if you mark items as spam?' where `var_name` = '\$USER_SPAM_RULESET';";
	$sql_USER_SPAM_RULESET = $handle->query($sql);
	if (!$sql_USER_SPAM_RULESET) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "USER_SPAM_RULESET" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update MAIN_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='logs/antispam.log', `var_defaultvalue` = 'logs/antispam.log', `var_optiontext` = 'Text File' where `var_name` = '\$MAIN_SPAM_RULESET';";
	$sql_MAIN_SPAM_RULESET = $handle->query($sql);
	if (!$sql_MAIN_SPAM_RULESET) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "MAIN_SPAM_RULESET" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update CHECK_SPAM title.
	$sql = "UPDATE `" . table_prefix."config` SET `var_title` ='Enable Spam Checking' where `var_name` = 'CHECK_SPAM';";
	$sql_CHECK_SPAM = $handle->query($sql);
	if (!$sql_CHECK_SPAM) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title for the CHECK_SPAM <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update SPAM_LOG_BOOK value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='logs/spam.log', `var_defaultvalue` = 'logs/spam.log', `var_optiontext` = 'Text File' where `var_name` = '\$SPAM_LOG_BOOK';";
	$sql_SPAM_LOG_BOOK = $handle->query($sql);
	if (!$sql_SPAM_LOG_BOOK) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "SPAM_LOG_BOOK" <img src="'.$marks.'" class="iconalign" /></li>';
	
	//Inserting a new row FRIENDLY_DOMAINS
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`) VALUES (NULL, 'AntiSpam', '\$FRIENDLY_DOMAINS', 'logs/domain-whitelist.log', 'logs/domain-whitelist.log', 'Text file', 'Local Domain Whitelist File', 'File containing a list of domains that cannot be banned.', 'normal', '\"');";
	$sql_FRIENDLY_DOMAINS = $handle->query($sql);
	if (!$sql_FRIENDLY_DOMAINS) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>INSERTED "FRIENDLY_DOMAINS" Row <img src="'.$marks.'" class="iconalign" /></li>';
	
	
	//Update the defaultvalue and description of table prefix
	$sql = "UPDATE `" . table_prefix."config` SET `var_defaultvalue` = REPLACE(`var_defaultvalue`, 'pligg', 'plikli'), `var_desc` = REPLACE(`var_desc`, 'pligg', 'plikli') WHERE `var_name` = 'table_prefix';";
	$sql_table_prefix = $handle->query($sql);
	if (!$sql_table_prefix) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated the defaultvalue and description of table prefix, replacing pligg instances with plikli. <img src="'.$marks.'" class="iconalign" /></li>';
	
	//Update the description of user language.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'Allow users to change Plikli language<br /><strong>When SET to 1, you have to rename the language file that you want to allow in /languages/ folder.</strong> Ex: <span style=\"font-style:italic;color:#004dff\">RENAME lang_italian.conf.default</span> to <span style=\"font-style:italic;color:#004dff\">lang_italian.conf</span>' WHERE `var_name` = 'user_language';";
	$sql_user_language = $handle->query($sql);
	if (!$sql_user_language) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated the description of user language. <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Use Allow User to Upload Avatars
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'Allow User to Upload Avatars' WHERE `var_name` =  'Enable_User_Upload_Avatar';";
	$sql_Allow_User_Upload_Avatars = $handle->query($sql);
	if (!sql_Allow_User_Upload_Avatars) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated title of Allow User to Upload Avatars <img src="'.$marks.'" class="iconalign" /></li>';
	
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
	$warnings[] = "Avatars large in Plikli are 100x100 instead of 30x30. If you have custom avatars for your pligg site, make sure to resize them to 100x100";
	echo '<li>Updated value and defaultvalue for the "Default_Gravatar_Large" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update Default_Gravatar_Small optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` = '/avatars/Avatar_32.png', `var_defaultvalue` = '/avatars/Avatar_32.png' where `var_name` = 'Default_Gravatar_Small';";
	$sql_Default_Gravatar_Small = $handle->query($sql);
	if (!$sql_Default_Gravatar_Small) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	$warnings[] = "Avatars small in Plikli are 32x32 instead of 15x15. If you have custom avatars for your pligg site, make sure to resize them to 32x32";
	echo '<li>Updated value and defaultvalue for the "Default_Gravatar_Small" <img src="'.$marks.'" class="iconalign" /></li>';
	
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
	$sql = "INSERT INTO `" . table_prefix."config` VALUES (NULL, 'Misc', 'Auto_scroll', '1', '1', '1', 'Pagination Mode', '<strong>1.</strong> Use normal pagination links.', 'define', NULL);";
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
		
	
	// Update the desc of user_language
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Allow users to change Plikli language<br /><strong>When SET to 1, you have to rename the language file that you want to allow in /languages/ folder.</strong> Ex: <span style=\"font-style:italic;color:#004dff\">RENAME lang_italian.conf.default</span> to <span style=\"font-style:italic;color:#004dff\">lang_italian.conf</span>' WHERE `var_name` =  'user_language';";
	$sql_user_language = $handle->query($sql);
	if (!$sql_user_language) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc of user_language. <img src="'.$marks.'" class="iconalign" /></li>';		
	
	
	
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
	(NULL, 'Logo', 'Default_Site_Logo', '', '', 'Path to the Site Logo', 'Site Logo', 'Default location of the Site Logo. Just make sure the maximum height of the logo is 40 or 41 px.<BR /> You can have any image extension: PNG, JPG, GIF.<br />Example:<br />/logo.png<br />/templates/bootstrap/img/logo.png', 'define', ''''),
	(NULL, 'Location Installed', 'allow_registration', 'true', 'true', 'true / false', 'Allow registration?', 'If for a reason you want to suspend registration, permanently or definitely, set it to false!', 'define', ''),
	(NULL, 'Location Installed', 'disallow_registration_message', 'Registration is temporarily suspended!', '', 'Text', 'Message to display when Registration is suspended', 'Enter the message you want to display.', 'define', ''),
	(NULL, 'Location Installed', '\$maintenance_mode', 'false', 'false', 'true / false', 'Maintenance Mode', 'Set the mode to true when you want to notify the users of the unavailability of the site (upgrade, downtime, etc.)<br /><strong>NOTE that only Admin can still access the site during maintenance mode!</strong>', 'normal', ''''),
	(NULL, 'Submit', 'Enable_Submit', 'true', 'true', 'true / false', 'Allow Submit', 'Allow users to submit articles?', 'define', NULL),
	(NULL, 'Submit', 'disable_Submit_message', 'Submitting articles is temporarily disabled!', '', 'Text', 'Message to display when Submitting articles is disallowed', 'Enter the message you want to display.', 'define', NULL),
	(NULL, 'Submit', 'Allow_Draft', 'false', 'false', 'true / false', 'Allow Draft Articles?', 'Set it to true to allow users to save draft articles', 'define', ''),
	(NULL, 'Submit', 'Allow_Scheduled', 'false', 'false', 'true / false', 'Allow Scheduled Articles?', 'Set it to true to allow users to save scheduled articles.<br /><strong>If you set to true, then you MUST install the <u>scheduled_posts</u> Module.</strong>', 'define', ''),
	(NULL, 'Story', 'link_nofollow', 'true', 'true', 'true / false', 'Use rel=\"nofollow\"', 'nofollow is a value that can be assigned to the rel attribute of an HTML a element to instruct some search engines that the hyperlink should not influence the ranking of the link''s target in the search engine''s index.<br /><a href=\"https://support.google.com/webmasters/answer/96569?hl=en\" target=\"_blank\" rel=\"noopener noreferrer\">Google: policies</a>', 'define', NULL),
	(NULL, 'Comments', 'Enable_Comments', 'true', 'true', 'true / false', 'Allow Comments', 'Allow users to comment on articles?', 'define', NULL),
	(NULL, 'Comments', 'disable_Comments_message', 'Comments are temporarily disabled!', '', 'Text', 'Message to display when Comments are disallowed', 'Enter the message you want to display.', 'define', NULL),
	(NULL, 'Groups', 'allow_groups_avatar', 'true', 'true', 'true/false', 'Allow Groups to upload own avatar', 'Should groups be allowed to upload own avatar?', 'define', 'NULL'),
	(NULL, 'Groups', 'max_group_avatar_size', '200', '200KB', 'number', 'Maximum image size allowed to upload', 'Set the maximum image size for the group avatar to upload.', 'define', 'NULL'),
	(NULL, 'Avatars', 'max_avatar_size', '200', '200KB', 'number', 'Maximum image size allowed to upload', 'Set the maximum image size a user can upload.', 'define', ''''),
	(NULL, 'Misc', 'validate_password', 'true', 'true', 'true / false', 'Validate user password', 'Validate user password, when registering/password reset, to check if it is safe and not pwned?<br />If you set to true, then a check is done using HIBP API. If the provided password has been pwned, the registration is not submitted until they provide a different password!.<br /><a href=\"https://haveibeenpwned.com/\" target=\"_blank\" rel=\"noopener noreferrer\">Have I Been Pwned?</a>', 'define', '');";
	
	$warnings[] = "Added new settings to the CONFIG Table:<ol><strong>Under Logo Section</strong><li>New configuration to set the site logo</li><strong>Under Location Installed Section</strong><li>allow_registration: Allows Admins to enable/disable registration to the site.</li><li>disallow_registration_message: Message to display when allow_registration is set to false.</li><li>maintenance_mode: Admins can set the maintenance mode ON/OFF.</li><strong>Under Submit Section</strong><li>Enable_Submit: Admins can enable/disable the Submit articles feature.</li><li>disable_Submit_message: Message to display when Submit is disabled.</li><li>Allow_Draft: Admins can allow/disallow users to submit Draft (saved) articles for later publishing.</li><li>Allow_Scheduled: Admins can allow/disallow users to submit Scheduled articles to be posted at a set later date.</li><strong>Under Story Section</strong><li>link_nofollow: Enable/disable link nofollow for the story URL that is linked in the title on the Story page and the original site that appears in the toolsbar under the title</li><strong>Under Comments Section</strong><li>Enable_Comments: Admins can enable/disable the Comments feature.</li><li>disable_Comments_message: Message to display when Comments are disabled.</li><strong>Under Groups Section</strong><li>allow_groups_avatar: Admins can allow/disallow groups avatar.</li><li>max_group_avatar_size: Admins can set the maximum group avatar to be uploaded.</li><strong>Under Avatars Section</strong><li>max_avatar_size: Admins can set now the user avatar size to be uploaded.</li><li>validate_password with HIBP API</li></ol>";
	echo '<li>'.printf("Affected rows (INSERT NEW SETTINGS): %d\n", $handle->affected_rows) .'</li>';
	
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
//end work on the config table.

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
	
	// Update the link status in links table from queued to new and from duplicated to duplicate.
	/**********************
	redwine: altering and replacing links status from "queued" to "new" and "duplicated" to "duplicate" must be done in two phases:
	1- we need to alter the enum of link_status and ONLY change the "queued" to "new". After it is altered, all "queued" link_status will be empty, it is only then that we replace the empty status with "new"

	2- the same applies to the "duplicated" link_status to change it to "duplicate". 
	**********************/
	$sql = "ALTER TABLE `" . table_prefix."links` CHANGE `link_status` `link_status`  enum('discard','new','published','abuse','duplicated','page','spam','moderated','draft','scheduled') NOT NULL DEFAULT 'discard';";
	$sql_alter_enum_status_queued_new = $handle->query($sql);
	printf("Affected rows (ALTERED link_status ENUM 'queued' to 'new'): %d\n", $handle->affected_rows);
	
	$sql = "UPDATE `" . table_prefix."links` SET `link_status`= 'new' where `link_status` = '';";
	$sql_update_status_queued_new = $handle->query($sql);
	printf("Affected rows (UPDATE link_status 'empty'  to 'new'): %d\n", $handle->affected_rows);

	$sql = "ALTER TABLE `" . table_prefix."links` CHANGE `link_status` `link_status` enum('discard','new','published','abuse','duplicate','page','spam','moderated','draft','scheduled') NOT NULL DEFAULT 'discard';";
	$sql_alter_status_duplicated_duplicate = $handle->query($sql);
	printf("Affected rows (ALTERED link_status ENUM 'duplicated' to 'duplicate'): %d\n", $handle->affected_rows);
	
	$sql = "UPDATE `" . table_prefix."links` SET `link_status`= 'duplicate' where `link_status` = '';";
	$sql_update_status_duplicated_duplicate = $handle->query($sql);
	printf("Affected rows (UPDATE link_status 'empty'  to 'duplicate'): %d\n", $handle->affected_rows);
	
	//Alter the `link_group_status`
	$sql = "ALTER TABLE `" . table_prefix."links` CHANGE `link_group_status` `link_group_status` enum(  'new',  'published',  'discard' ) DEFAULT 'new' NOT NULL;";
	$sql_alter_enum_status_link_group_status_queued_new = $handle->query($sql);
	printf("Affected rows (ALTERED link_group_status ENUM 'queued' to 'new'): %d\n", $handle->affected_rows);
	
	$sql = "UPDATE `" . table_prefix."links` SET `link_group_status`= 'new' where `link_group_status` = '';";
	$sql_update_status_duplicated_duplicate = $handle->query($sql);
	printf("Affected rows (UPDATE link_group_status 'empty'  to 'new'): %d\n", $handle->affected_rows);
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

//start work on misc_data table, replacing pligg with plikli
echo '<fieldset><legend>Renaming some values in the misc_data table to work with Plikli.</legend><ul>';	
	// Update CMS version.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = '". $lang['plikli_version'] ."'  WHERE `name` = 'pligg_version';";
	$sql_CMS_name = $handle->query($sql);
	if (!$sql_CMS_name) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated name to plikli_version and version to '. $lang['plikli_version'] .' <img src="'.$marks.'" class="iconalign" /></li>';
	
	///replace all instances of pligg with plikli
	$sql = "UPDATE `" . table_prefix."misc_data` SET `name` = REPLACE(`name`, 'pligg', 'plikli') , `data` = REPLACE(`data`, 'pligg', 'plikli');";
	$sql_update_data_column = $handle->query($sql);
	if (!$sql_update_data_column) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	printf("Affected rows (UPDATE): %d\n", $handle->affected_rows);
	echo '<li>Updated data column to replace all instances of tpl_pligg with tpl_plikli <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Insert karma_story_unvote, and the new fields for plikli version and mudules updates.
	$sql = "INSERT INTO `" . table_prefix."misc_data` ( `name` , `data` ) VALUES 
	('karma_story_unvote','-1'),
	('modules_update_date',DATE_FORMAT(NOW(),'%Y/%m/%d')),
	('modules_update_url','https://www.plikli.com/mods/version-update.txt'),
	('plikli_update',''),
	('plikli_update_url','https://www.plikli.com/download_plikli/'),
	('modules_update_unins',''),
	('modules_upd_versions','');";
	$sql_karma_story_unvote = $handle->query($sql);
	if (!$sql_karma_story_unvote) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserted karma_story_unvote value in "Misc_data" table <img src="'.$marks.'" class="iconalign" /></li>';
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
					// Update table widgets; changing the name of Pligg CMS to Plikli CMS
					$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Plikli CMS', `folder` = 'plikli_cms', `version` = '1.0' WHERE `name` = 'Pligg CMS';";
					$sql_widget_plikli_cms = $handle->query($sql);
					if (!$sql_widget_plikli_cms) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Update table widgets; changing the name of Pligg CMS to Plikli CMS <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Pligg News',$widget)) {
					// Update table widgets; changing the name of Pligg News to Plikli News
					$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Plikli News', `folder` = 'plikli_news' WHERE `name` = 'Pligg News';";
					$sql_widget_plikli_news = $handle->query($sql);
					if (!$sql_widget_plikli_news) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Update table widgets; changing the name of Pligg News to Plikli News <img src="'.$marks.'" class="iconalign" /></li>';
					
					// Update misc_data table; setting the news count in case it does not exist.
					$sql = "UPDATE IGNORE `" . table_prefix."misc_data` SET `name` = 'news_count', `data` = '3';";
					$sql_news_count = $handle->query($sql);
					if (!$sql_news_count) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Update misc_data table setting the new_count to 3 for Plikli News widgets <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('New products',$widget) || in_array('New Products',$widget)) {
					// Update table widgets; changing the name of Pligg News to Plikli News
					$sql = "DELETE FROM `" . table_prefix."widgets` WHERE `name` = 'New products' OR `name` = 'New Products';";
					$sql_widget_plikli_news = $handle->query($sql);
					if (!$sql_widget_plikli_news) {
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

/* Redwine: checking if we have to detect certain settings and modules or not, to give further instructions. */
//get the CMS folder name
$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = '\$my_plikli_base';";
$sql_get_base_folder = $handle->query($sql);
$fetched = $sql_get_base_folder->fetch_array(MYSQLI_ASSOC);	
$folder_path = $fetched['var_value'];

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
				$warnings[] = "We detected 'Google Adsense Revenue Sharing' module installed. This module is not supported by Plikli, so we removed it from the installed modules and we deleted the related columns from the users table. Just in case you need the id/channel/percent for your own ussage, you can get them from your database backup!";
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
			$warnings[] = "We noticed you have the UPLOAD module installed. You have to copy the files from the old Pligg folder, in ".$folder_path."/modules/upload/attachments TO the same folder in the new Plikli /".$upgrade_folder."/modules/upload/attachments";
			/*Redwine: correcting the default upload fileplace!*/
			$sql_upload_fileplace = "select `data` from `" . table_prefix."misc_data` WHERE `name` = 'upload_fileplace'";
			$sql_fileplace = mysqli_fetch_assoc($handle->query($sql_upload_fileplace));
			if ($sql_fileplace['data'] == 'tpl_plikli_story_who_voted_start') {
				$sql_upload_fileplace_correct = $handle->query("UPDATE `" . table_prefix."misc_data` set `data` = 'upload_story_list_custom' WHERE `name` = 'upload_fileplace'");
				echo "<li>We corrected the upload_fileplace default to 'upload_story_list_custom'.</li>";
				$warnings[] = "We corrected the upload_fileplace default to 'upload_story_list_custom'.";
			}
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
		if ($module['folder'] == 'rss_import') {
			$sql = "ALTER TABLE `" . table_prefix."feed_link` CHANGE `pligg_field` `plikli_field` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
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
	
echo '<fieldset><legend>Renaming the original folder containing the old Pligg files</legend><ul>';
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
		echo '. You are using CHECK_SPAM feature. <strong>Files content</strong> must be copied from your old CMS '.$folder_path.' to the files in the root directory of the new Plikli. See Warnings at the end of the upgrade!';
		$warnings[] = 'CHECK_SPAM is set to "' .trim($result['var_value']). '" in your config table. You must copy the <strong>Files content</strong> from your old CMS '.$folder_path.'/ TO the /logs/ directory of the new Plikli, from where you are running the upgrade:<br />'.$folder_path.'/antispam.log TO /'.$upgrade_folder.'/logs/antispam.log<br />'.$folder_path.'/approvedips.txt TO /'.$upgrade_folder.'/logs/approvedips.log<br />'.$folder_path.'/bannedips.txt TO /'.$upgrade_folder.'/logs/bannedips.log<br />'.$folder_path.'/antispam.txt TO /'.$upgrade_folder.'/logs/domain-blacklist.log<br />'.$folder_path.'/notspam.txt TO /'.$upgrade_folder.'/logs/domain-whitelist.log';
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