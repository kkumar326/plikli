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

//Converting all the Tables to utf8_general_ci and Engine MyISAM
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
				CHANGE `file_ispicture` `file_ispicture` tinyint(4) NOT NULL DEFAULT '0';";
				$sql_alter_files = $handle->query($sql);
				if (!$sql_alter_files) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Altered '.$tname . ' table <img src="'.$marks.'" class="iconalign" /></li>';
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
			}
		}
	}else{
		$marks = $notok;
		echo 'Could not get the CMS tables from your database!  <img src="'.$marks.'" class="iconalign" />';
		die();
	}
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Changing Columns in Modules table.</legend><ul>';
$sql = "ALTER TABLE  `" . table_prefix."modules`  
CHANGE  `version` `version` float(10,1) NOT NULL,
CHANGE  `latest_version` `latest_version` float(10,1) NOT NULL;";
$sql_alter_modules = $handle->query($sql);
echo '<li>Updated modules Table changed float to float(10,1)</li>';
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
	echo '<li>Altered the name field in Totals table.</li>';
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

// Alter comment_content in comments table
echo '<fieldset><legend>Changing Columns in Comments table.</legend><ul>';
	$sql = "ALTER TABLE  `" . table_prefix."comments` CHANGE  `comment_content`  `comment_content` TEXT NOT NULL;";
	$sql_alter_comments = $handle->query($sql);
	echo '<li>Updated comments Table</li>';
echo '</ul></fieldset><br />';

echo '<fieldset><legend>MODIFICATIONS TO THE CONFIG Table.</legend><ul>';
	//Inserting a new row FRIENDLY_DOMAINS
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`) VALUES (NULL, 'AntiSpam', '\$FRIENDLY_DOMAINS', 'logs/domain-whitelist.log', 'logs/domain-whitelist.log', 'Text file', 'Local Domain Whitelist File', 'File containing a list of domains that cannot be banned.', 'normal', '\"');";
	$sql_FRIENDLY_DOMAINS = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (INSERTED 'FRIENDLY_DOMAINS'): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';

	// Update MAIN_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_defaultvalue` = 'logs/antispam.log' where `var_name` = '\$MAIN_SPAM_RULESET';";
	$sql_MAIN_SPAM_RULESET = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'MAIN_SPAM_RULESET'): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';

	// Update USER_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'What file should Plikli write to if you mark items as spam?' where `var_name` = '\$USER_SPAM_RULESET';";
	$sql_USER_SPAM_RULESET = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'USER_SPAM_RULESET'): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Use Allow User to Upload Avatars
	$sql = "UPDATE `" . table_prefix."config` set `var_title` = 'Allow User to Upload Avatars' WHERE `var_name` =  'Enable_User_Upload_Avatar';";
	$sql_Allow_User_Upload_Avatars = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Enable_User_Upload_Avatar' Title): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	//deleting obsolete User_Upload_Avatar_Folder
	$sql = "DELETE FROM `" . table_prefix."config` Where `var_name` = 'User_Upload_Avatar_Folder';";
	$sql_delete_User_Upload_Avatar_Folder = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (DELETED 'obsolete User_Upload_Avatar_Folder' entry): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	//Update my_base_url description.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = REPLACE(`var_desc`, 'pligg', 'plikli') WHERE `var_name` = '\$my_base_url';";
	$sql_mybaseurl = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'my_base_url' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	$sql = "UPDATE `" . table_prefix."config` SET `var_name` = '\$my_plikli_base', `var_title` = REPLACE(`var_title`, 'Pligg', 'Plikli'), `var_desc` = REPLACE(`var_desc`, 'pligg', 'plikli') WHERE `var_name` = '\$my_pligg_base';";
	$sql_mypliklibase = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'my_pligg_base' title & description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	//replacing pligg instances with plikli in trackback
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = 'plikli.com', `var_defaultvalue` = 'plikli.com', `var_optiontext` = 'plikli.com' WHERE `var_name` = '\$trackbackURL';";
	$sql_trackbackURL = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'trackbackURL' value & default value & option text): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';	
	
	// Update the Auto Scroll
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '1', `var_defaultvalue` = '1', `var_optiontext` = '1', `var_title` = 'Pagination Mode', `var_desc` = '<strong>1.</strong> Use normal pagination links.' WHERE `var_name` =  'Auto_scroll';";
	$sql_auto_scroll = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Auto Scroll' fields): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update allow extra fields desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='Enable extra fields when submitting stories?<br /><strong>When SET to TRUE, you have to edit the /libs/extra_fields.php file, using the NEW <a href=\"../admin/admin_xtra_fields_editor.php\" target=\"_blank\" rel=\"noopener noreferrer\">Extra Fields Editor</a> in the Dashboard!</strong>' where `var_name` = 'Enable_Extra_Fields';";
	$sql_extra_fields = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Enable_Extra_Fields' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the description of misc_validate
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Require users to validate their email address?<br />If you set to true, then click on the link below to also set the email to be used for sending the message.<br /><a href=\"../module.php?module=admin_language\">Set the email</a>. Type @ in the filter box and click Filter to get the value to modify. Do not forget to click save.' WHERE `var_name` =  'misc_validate';";
	$sql_misc_validate = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'misc_validate' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	//Update table_prefix data
	$sql = "UPDATE `" . table_prefix."config` SET `var_defaultvalue` = REPLACE(`var_defaultvalue`, 'pligg', 'plikli'), `var_desc` = REPLACE(`var_desc`, 'pligg', 'plikli') WHERE `var_name` = 'table_prefix';";
	$sql_table_prefix = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'table_prefix' description & default value): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	//Update user_language
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'Allow users to change Plikli language<br /><strong>When SET to 1, you have to rename the language file that you want to allow in /languages/ folder.</strong> Ex: <span style=\"font-style:italic;color:#004dff\">RENAME lang_italian.conf.default</span> to <span style=\"font-style:italic;color:#004dff\">lang_italian.conf</span>' WHERE `var_name` = 'user_language';";
	$sql_user_language = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'user_language' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update urlmethod desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='<strong>1</strong> = Non-SEO Links.<br /> Example: /story.php?title=Example-Title<br /><strong>2</strong> SEO Method. <br />Example: /Category-Title/Story-title/.<br /><strong>Note:</strong> You must rename htaccess.default to .htaccess <strong>AND EDIT IT WHERE MODIFICATIONS ARE NOTED!</strong>' where `var_name` = '\$URLMethod';";
	$sql_urlmethod = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'URLMethod' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the description of Use Story Title as External Link
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Use the story title as link to story\'s website. <strong>NOTE that if you set it to true, the title will link directly to the original story link even when the story is displayed in summary mode!</strong>' WHERE `var_name` =  'use_title_as_link';";
	$sql_Story_Title_as_External_Link = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'use_title_as_link' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Story_Content_Tags_To_Allow_Admin
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'leave blank to not allow tags. Examples are: &lt;br&gt;&lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;<br /><strong style=\"color:#ff0000;\">NEVER ALLOW OTHER THAN THESE TAGS, ESPECIALLY FORM, SCRIPT, IMG, SVG AND IFRAME TAGS!</strong>' WHERE `var_name` =  'Story_Content_Tags_To_Allow_Admin';";
	$sql_Story_Content_Tags_To_Allow_Admin = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Story_Content_Tags_To_Allow_Admin' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Story_Content_Tags_To_Allow_God
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'leave blank to not allow tags. Examples are: &lt;br&gt;&lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;<br /><strong style=\"color:#ff0000;\">NEVER ALLOW OTHER THAN THESE TAGS, ESPECIALLY FORM, SCRIPT, IMG, SVG AND IFRAME TAGS!</strong>' WHERE `var_name` =  'Story_Content_Tags_To_Allow_God';";
	$sql_Story_Content_Tags_To_Allow_God = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Story_Content_Tags_To_Allow_God' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update Story_Content_Tags_To_Allow_Normal title.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'leave blank to not allow tags. Examples are: &lt;br&gt;&lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;<br /><strong style=\"color:#ff0000;\">NEVER ALLOW OTHER THAN THESE TAGS, ESPECIALLY FORM, SCRIPT, IMG, SVG AND IFRAME TAGS!</strong>' where `var_name` = 'Story_Content_Tags_To_Allow_Normal';";
	$sql_Story_Content_Tags_To_Allow_Normal = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Story_Content_Tags_To_Allow_Normal' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the description of Show the URL Input Box
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Show the URL input box in submit step 1.<br /><strong>It is by default set to true. If you plan on allowing both URL and Editorial story submission, then you keep it set to true. However, if you only want to allow Editorial story submission, then set it to false!</strong>' WHERE `var_name` =  'Submit_Show_URL_Input';";
	$sql_Submit_Show_URL_Input = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'Submit_Show_URL_Input' description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	//Delete SubmitSummary_Allow_Edit settings
	$sql = "DELETE FROM `" . table_prefix."config` WHERE `var_name` = 'SubmitSummary_Allow_Edit';";
	$sql_del_allow_summary = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (DELETED 'SubmitSummary_Allow_Edit' settings): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the optiontext, title and desc of buries_to_spam
	$sql = "UPDATE `" . table_prefix."config` set `var_optiontext` = '1 = on / 0 = off', `var_title` = 'Negative Votes Story Discard', `var_desc` = 'If set to 1, stories with enough down votes will be discarded. The formula for determining what gets buried is stored in the database table_formulas. It defaults to discarding stories with 3 times more downvotes than upvotes.' WHERE `var_name` =  'buries_to_spam';";
	$sql_buries_to_spam = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED 'buries_to_spam' option text & title & description): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';	
	
echo '<fieldset><legend>INSERT NEW SETTINGS TO THE CONFIG Table.</legend><ul>';
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
//end work on CONFIG Table	

echo '<fieldset><legend>Updating the Groups member role</legend><ul>';
	// First ALtering the member role enum in group_member table
	$sql = "ALTER TABLE  `" . table_prefix."group_member` 
	CHANGE `member_role` `member_role` enum('admin','normal','flagged','banned') NOT NULL;";
	$sql_alter_role = $handle->query($sql);
	echo '<li>ALTERED the member_role ENUM</li>';
	if ($sql_alter_role) {
		// Now we update the group creator's role from empty to admin
		$sql = "SELECT `group_id`, `group_creator` FROM `" . table_prefix. "groups`;";
		$sql_get_group_id_creator = $handle->query($sql);
		if ($sql_get_group_id_creator) {
			$row_cnt = $sql_get_group_id_creator->num_rows;
			if ($row_cnt > 0) {
				while($group = $sql_get_group_id_creator->fetch_assoc()) {
					$sql = "Update `" . table_prefix . "group_member` SET `member_role` = 'admin' WHERE `member_role` = '' && `member_user_id` = " .$group['group_creator'] . " && `member_group_id` = " . $group['group_id'];
					$sql_members = $handle->query($sql);
					if (!$sql_members) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>Updated member role in group ID ' . $group['group_id'] .  ' <img src="'.$marks.'" class="iconalign" /></li>';
				}
		
				// ALtering the member role enum in group_member table to put it the correct way
				$sql = "ALTER TABLE  `" . table_prefix."group_member` 
				CHANGE `member_role` `member_role` enum('admin','normal','moderator','flagged','banned') NOT NULL;";
				$sql_alter_role = $handle->query($sql);
				// Now we update empty role to normal (in all the column) 
				$sql = "Update `" . table_prefix . "group_member` SET `member_role` = 'normal' WHERE `member_role` = '';";
				$sql_update_role_normal = $handle->query($sql);
				if ($handle->affected_rows < 1) {
				$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>'.printf("Affected rows (UPDATED ENUM): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
			}
		}
	}
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Changing Columns in Links table.</legend><ul>';
	$sql = "ALTER TABLE  `" . table_prefix."links`  
	CHANGE 	`link_status` `link_status`  enum('discard','new','published','abuse','duplicate','page','spam','moderated','draft','scheduled') NOT NULL DEFAULT 'discard',
	CHANGE  `link_url`  `link_url` VARCHAR( $urllength ) NOT NULL DEFAULT '';";
	$sql_alter_links = $handle->query($sql);
	if (!$sql_alter_links) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>ALTERED the ENUM of `link_status` and `link_url` varchar to '.$urllength.' <img src="'.$marks.'" class="iconalign" /></li>';
	
	$sql = "INSERT INTO `".table_prefix."links` (`link_id`, `link_author`, `link_status`, `link_randkey`, `link_votes`, `link_reports`, `link_comments`, `link_karma`, `link_modified`, `link_date`, `link_published_date`, `link_category`, `link_lang`, `link_url`, `link_url_title`, `link_title`, `link_title_url`, `link_content`, `link_summary`, `link_tags`, `link_field1`, `link_field2`, `link_field3`, `link_field4`, `link_field5`, `link_field6`, `link_field7`, `link_field8`, `link_field9`, `link_field10`, `link_field11`, `link_field12`, `link_field13`, `link_field14`, `link_field15`, `link_group_id`, `link_group_status`, `link_out`) VALUES
	(NULL, 1, 'page', 0, 0, 0, 0, '0.00', '2016-10-06 17:19:46', '2016-10-06 17:19:46', '0000-00-00 00:00:00', 0, 1, '', NULL, 'About', 'about', '<legend><strong>About Us</strong></legend>\r\n<p>Our site allows you to submit an article that will be voted on by other members. The most popular posts will be published to the front page, while the less popular articles are left in an ''New'' page until they acquire the set number of votes to move to the published page. This site is dependent on user contributed content and votes to determine the direction of the site.</p>\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'new', 0);";
	$sql_insert_page_links = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (INSERTED a sample page in the links table): %d\n", $handle->affected_rows) . '<img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';

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

//start work on misc_data table, replacing pligg with plikli
echo '<fieldset><legend>Renaming some values in the misc_data table to work with Plikli.</legend><ul>';	
	// Update CMS version.
	$sql = "UPDATE `" . table_prefix."misc_data` SET `data` = '". $lang['plikli_version'] ."'  WHERE `name` = 'pligg_version';";
	$sql_CMS_name = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED the CMS version to '". $lang['plikli_version'] ."' ): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';	

	//replace all instances of pligg with plikli
	$sql = "UPDATE `" . table_prefix."misc_data` SET `name` = REPLACE(`name`, 'pligg', 'plikli') , `data` = REPLACE(`data`, 'pligg', 'plikli');";
	$sql_update_data_column = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED the CMS name version to 'plikli_version'): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';	
	
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
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (INSERTED new data for Plikli CMS): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Deleting obsolete Modules. If an entry needs updating it is marked <img src="ok.png" class="iconalign" />; else, it is marked <img src="notok.png" class="iconalign" /></legend><ul>';
	$sql = "select `name`,`folder` from `" . table_prefix."modules`";
	$sql_modules = $handle->query($sql);
	$to_delete = array('Human Check','Google Adsense Revenue Sharing','Status', 'Status Update Module');
	while ($module = $sql_modules->fetch_assoc()) {
		if (in_array($module['name'],$to_delete)) {
			$sql = "DELETE FROM `" . table_prefix."modules` where `name` = '" .$module['name'] ."';";
			$sql_delete = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (DELETED obsolete ".$module['name'] ."): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = "We detected ".$module['name'] ." module installed. This module is not supported by Plikli, so we removed it from the installed modules and we deleted all related data from any other tables. You can get the data from your database backup!";
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
			
			// Delete all the status module entries from misc_data table
			$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'status_%';";
			$sql_delete_status_entries = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (DELETED obsolete entries for ".$module['name'] ." from the misc_data table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
		}
	}
	
	/* Redwine: checking if we have to detect certain settings and modules or not, to give further instructions. */
	//get the CMS folder name
	$sql = "SELECT `var_value` FROM `" . table_prefix."config` WHERE `var_name` = '\$my_plikli_base';";
	$sql_get_base_folder = $handle->query($sql);
	$fetched = $sql_get_base_folder->fetch_array(MYSQLI_ASSOC);	
	$folder_path = $fetched['var_value'];
	
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
				if ($handle->affected_rows < 1) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>'.printf("Affected rows (UPDATED " .$module['name']." version): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
			}	
		}	

		if ($module['folder'] == "links") {
			// Insert new Links module settings.
			$sql = "INSERT INTO `" . table_prefix."misc_data` ( `name` , `data` ) VALUES 
			('links_all', '1'),
			('links_moderators', ''),
			('links_admins', '');";
			$sql_new_links_module_settings = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (INSERTED new Links module settings in 'Misc_data' table. (See instructions at the end of upgrade): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = "Check the Links module because we added few settings to it <strong style=\"text-decoration:underline;background-color:#0100ff\">YOU HAVE TO GO TO ITS SETTINGS AND SELECT THE NEW OPTIONS THAT YOU WANT; OTHERWISE IT WILL NOT WORK UNTIL YOU DO SO!</strong>!";
			
			// Delete obsolete Links module settings.
			$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` = 'links_host';";
			$sql_new_links_module_settings = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (DELETED obsolete Links module settings in 'Misc_data' table.): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
		}		
		if ($module['folder'] == "upload") {
			$warnings[] = "We noticed you have the UPLOAD module installed. You have to copy the files from the old Pligg folder, in ".$folder_path."/modules/upload/attachments TO the same folder in the new Plikli /".$upgrade_folder."/modules/upload/attachments";
			/*Redwine: correcting the default upload fileplace!*/
			$sql_upload_fileplace = "select `data` from `" . table_prefix."misc_data` WHERE `name` = 'upload_fileplace'";
			$sql_fileplace = mysqli_fetch_assoc($handle->query($sql_upload_fileplace));
			if ($sql_fileplace['data'] == 'tpl_plikli_story_who_voted_start') {
				$sql_upload_fileplace_correct = $handle->query("UPDATE `" . table_prefix."misc_data` set `data` = 'upload_story_list_custom' WHERE `name` = 'upload_fileplace'");
				if ($handle->affected_rows < 1) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>'.printf("Affected rows (UPDATED the upload_fileplace default to 'upload_story_list_custom'.): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';	
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
			echo '<li>Altered "table snippets" added a status column <img src="'.$marks.'" class="iconalign" /></li>';
			$warnings[] = "Added a Status column to allow Admins to activate/deactivate snippets!</strong>!";
		}
		if ($module['folder'] == 'anonymous') {
			$sql = "UPDATE `" . table_prefix."users` SET `user_email` = 'anonymous@plikli.com' WHERE `user_login` = 'anonymous';";
			$sql_update_email = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (UPDATED  user_email for anonymous in users table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
		}
		if ($module['folder'] == 'akismet') {
			$sql = "select `data` from `" . table_prefix."misc_data` WHERE `name` = 'wordpress_key'";
			$sql_key = mysqli_fetch_assoc($handle->query($sql));
			if ($sql_key['data'] != '') {
				echo "<li>We detected you are using the Akismet module and found its key in the misc_data table!</li>";
			}else{
				echo '<li class="warn-delete">We detected you are using the Akismet module but did not find its key in the misc_data table!</li>';
				$warnings[] = "You have Akismet module installed but its has no Wordpress key!";
			}
		}
		if ($module['folder'] == 'xml_sitemaps') {
			$sql = "DELETE FROM `" . table_prefix."config` WHERE (`var_page`,`var_name`) IN (('XmlSitemaps','XmlSitemaps_ping_google'),('XmlSitemaps','XmlSitemaps_ping_ask'),('XmlSitemaps','XmlSitemaps_ping_yahoo'),('XmlSitemaps','XmlSitemaps_yahoo_key'),('XmlSitemaps','XmlSitemaps_use_cache'),('XmlSitemaps','XmlSitemaps_cache_ttl'));";
			$sql_delete_xml_entries = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (DELETED  obsolete XmlSitemaps entries from config table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
	
			$sql = "INSERT INTO `".table_prefix."config` (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','','','','link','Sitemap links page','<a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\" rel=\"noopener noreferrer\">View the Sitemapindex</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Links Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=pages0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Pages Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=users0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Users Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=groups0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Groups Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\" rel=\"noopener noreferrer\">View the Navigations Sitemap</a>','define');";
			$sql_insert_xml_navigation_links = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (INSERTED  XmlSitemaps module navigation entry in the config table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
			
			$sql = "Update `" . table_prefix . "config` SET `var_desc` = 'This makes friendly sitemap urls. SET to TRUE, only if you have set URL method to 2 in Dashboard -> Settings -> SEO -> URL Method.' WHERE `var_name` = 'XmlSitemaps_friendly_url';";
			$sql_XmlSitemaps_friendly_url = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (UPDATED  XmlSitemaps_friendly_url description in the config table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';

			$sql = "Update `" . table_prefix . "config` SET `var_desc` = 'This module generates an index of sitemaps, here you can set the number of links you want to include in one sitemap from that index. <STRONG>NOTE THAT GOOGLE RECOMMENDS A MAXIMUM OF 1000 PER SITEMAP</STRONG>' WHERE `var_name` = 'XmlSitemaps_Links_per_sitemap';";
			$sql_XmlSitemaps_Links_per_sitemap = $handle->query($sql);
			if ($handle->affected_rows < 1) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>'.printf("Affected rows (UPDATED  XmlSitemaps_Links_per_sitemap description in the config table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
		}
	}	
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Update Admin user_url in users table</legend><ul>';
	$sql = "UPDATE `" . table_prefix."users` SET `user_url` = REPLACE(`user_url`, 'http://pligg.com', 'https://www.plikli.com');";
	$sql_update_user_url = $handle->query($sql);
	if ($handle->affected_rows < 1) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>'.printf("Affected rows (UPDATED user_url for admin user in the users table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
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
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED widget name and folder for ".$widget." widget): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Statistics',$widget)) {
				// Update table widgets; changing the version of statistics widget
					$sql = "UPDATE `" . table_prefix."widgets` SET `version` = '3.0' WHERE `name` = 'Statistics';";
					$sql_widget_statistics = $handle->query($sql);
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED widget version for ".$widget." widget): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Pligg CMS',$widget)) {
					// Update table widgets; changing the name of Pligg CMS to Plikli CMS
					$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Plikli CMS', `folder` = 'plikli_cms', `version` = '1.0' WHERE `name` = 'Pligg CMS';";
					$sql_widget_plikli_cms = $handle->query($sql);
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED widget name and folder for ".$widget." widget): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('Pligg News',$widget)) {
					// Update table widgets; changing the name of Pligg News to Plikli News
					$sql = "UPDATE `" . table_prefix."widgets` SET `name` = 'Plikli News', `folder` = 'plikli_news' WHERE `name` = 'Pligg News';";
					$sql_widget_plikli_news = $handle->query($sql);
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED widget name and folder for ".$widget." widget): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
					
					// Update misc_data table; setting the news count in case it does not exist.
					$sql = "UPDATE IGNORE `" . table_prefix."misc_data` SET `name` = 'news_count', `data` = '3';";
					$sql_news_count = $handle->query($sql);
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (UPDATED the news_count for widget for ".$widget." widget in the misc_data table): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
				}elseif (in_array('New products',$widget) || in_array('New Products',$widget)) {
					// Update table widgets; changing the name of Pligg News to Plikli News
					$sql = "DELETE FROM `" . table_prefix."widgets` WHERE `name` = 'New products' OR `name` = 'New Products';";
					$sql_widget_plikli_news = $handle->query($sql);
					if ($handle->affected_rows < 1) {
						$marks = $notok;
					}else{
						$marks = $ok;
					}
					echo '<li>'.printf("Affected rows (DELETED obsolete ".$widget." widget): %d\n", $handle->affected_rows).' <img src="'.$marks.'" class="iconalign" /></li>';
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

echo '<fieldset><legend>Checking and re-ordering the misc_data table!</legend><ul>';
$sql = "select * from `" . table_prefix."misc_data` where `name` = 'plikli_version';";
$sql_CMS_version = $handle->query($sql);
if ($sql_CMS_version) {
	$row_cnt = $sql_CMS_version->num_rows;
	if ($row_cnt) {
		while ($cms_name_version = $sql_CMS_version->fetch_assoc()) {
			echo '<li>CMS name "'. $cms_name_version['name'] .'" and CMS version is "'. $cms_name_version['data'] .'"</li>'; 
		}
	}else{
	$sql_cms_insert = $handle->query("INSERT INTO `" . table_prefix."misc_data` SET `name` = 'plikli_version', `data` = '". $lang['plikli_version'] ."';");
	echo '<li>'.printf("Affected rows (INSERTED CMS name and version ". $lang['plikli_version'] ." in misc_data table): %d\n", $handle->affected_rows).'</li>';
	}
}	

//empty the data of plikli_update if it contains any version from the old pligg
$sql = "select * from `" . table_prefix."misc_data` where `name` = 'plikli_update';";
$sql_plikli_update = $handle->query($sql);
if ($sql_plikli_update) {
	$row_cnt = $sql_plikli_update->num_rows;
	if ($row_cnt) {
		while ($cms_plikli_update = $sql_plikli_update->fetch_assoc()) {
			echo '<li>Plikli Update "'. $cms_plikli_update['name'] .'" and Data "'. $cms_plikli_update['data'] .'"</li>'; 
			$sql_plikli_update_insert = $handle->query("UPDATE `" . table_prefix."misc_data` SET `data` = '' WHERE `name` = 'plikli_update';");
			echo '<li>'.printf("Affected rows (UPDATE PLIKLI UPDATE DATA): %d\n", $handle->affected_rows).'</li>';
		}
	}else{
	$sql_plikli_update_insert = $handle->query("INSERT INTO `" . table_prefix."misc_data` SET `name` = 'plikli_update', `data` = '';");
	echo '<li>'.printf("Affected rows (UPDATE PLIKLI UPDATE DATA): %d\n", $handle->affected_rows).'</li>';
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
echo "<br />CHECK_SPAM = " . $result['var_value'];
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

