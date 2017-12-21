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

	/* Redwine: creating a mysqli connection */
	//$handle = new mysqli(EZSQL_DB_HOST,EZSQL_DB_USER,EZSQL_DB_PASSWORD,EZSQL_DB_NAME);
	/* check connection */
	/*if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}*/

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


// Alter comment_content in comments table
echo '<fieldset><legend>Changing Columns in Comments table.</legend><ul>';
	$sql = "ALTER TABLE  `" . table_prefix."comments` CHANGE  `comment_content`  `comment_content` TEXT NOT NULL;";
	$sql_alter_comments = $handle->query($sql);
	echo '<li>Updated comments Table</li>';
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Updating data in Config table.</legend><ul>';
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

	// Update MAIN_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_defaultvalue` = 'logs/antispam.log' where `var_name` = '\$MAIN_SPAM_RULESET';";
	$sql_MAIN_SPAM_RULESET = $handle->query($sql);
	if (!$sql_MAIN_SPAM_RULESET) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated value, deffaultvalue and optiontext for the "MAIN_SPAM_RULESET" <img src="'.$marks.'" class="iconalign" /></li>';

	// Update USER_SPAM_RULESET value, deffaultvalue and optiontext.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'What file should Kliqqi write to if you mark items as spam?' where `var_name` = '\$USER_SPAM_RULESET';";
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
	
	//Inserting a new LOGO feature
	$sql = "INSERT INTO `" . table_prefix."config` (`var_id`, `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`) VALUES (NULL, 'Logo', 'Default_Site_Logo', '', '', 'Path to the Site Logo', 'Site Logo', 'Default location of the Site Logo. Just make sure the maximum height of the logo is 40 or 41 px.<BR /> You can have any image extension: PNG, JPG, GIF.<br />Example:<br />/logo.png<br />/templates/bootstrap/img/logo.png', 'define', '''');";
	$sql_Logo = $handle->query($sql);
	if (!$sql_Logo) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>INSERTED "LOGO" settings <img src="'.$marks.'" class="iconalign" /></li>';	

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
	
	//deleting obsolete User_Upload_Avatar_Folder
	$sql = "DELETE FROM `" . table_prefix."config` Where `var_name` = 'User_Upload_Avatar_Folder';";
	$sql_delete_User_Upload_Avatar_Folder = $handle->query($sql);
	if (!$sql_delete_User_Upload_Avatar_Folder) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted obsolete User_Upload_Avatar_Folder <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the defaultvalue, optiontext and description for table prefix
	$sql = "UPDATE `" . table_prefix."config` set `var_defaultvalue` = 'kliqqi_', `var_desc` = 'Table prefix. Ex: kliqqi_ makes the users table become kliqqi_users. Note: changing this will not automatically rename your tables!' WHERE `var_name` =  'table_prefix';";
	$sql_tableprefix = $handle->query($sql);
	if (!$sql_tableprefix) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated the defaultvalue, optiontext and description of table prefix. <img src="'.$marks.'" class="iconalign" /></li>';

	// Update the description of misc_validate
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Require users to validate their email address?<br />If you set to true, then click on the link below to also set the email to be used for sending the message.<br /><a href=\"../module.php?module=admin_language\">Set the email</a>. Type @ in the filter box and click Filter to get the value to modify. Do not forget to click save.' WHERE `var_name` =  'misc_validate';";
	$sql_misc_validate = $handle->query($sql);
	if (!$sql_misc_validate) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated description of misc_validate. <img src="'.$marks.'" class="iconalign" /></li>';

	// Update the optiontext, title and desc of buries_to_spam
	$sql = "UPDATE `" . table_prefix."config` set `var_optiontext` = '1 = on / 0 = off', `var_title` = 'Negative Votes Story Discard', `var_desc` = 'If set to 1, stories with enough down votes will be discarded. The formula for determining what gets buried is stored in the database table_formulas. It defaults to discarding stories with 3 times more downvotes than upvotes.' WHERE `var_name` =  'buries_to_spam';";
	$sql_buries_to_spam = $handle->query($sql);
	if (!$sql_buries_to_spam) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated optiontext, title and desc of buries_to_spam. <img src="'.$marks.'" class="iconalign" /></li>';	

	// Update the Auto Scroll
	$sql = "UPDATE `" . table_prefix."config` set `var_value` = '1', `var_defaultvalue` = '1', `var_optiontext` = '1', `var_title` = 'Pagination Mode', `var_desc` = '<strong>1.</strong> Use normal pagination links.' WHERE `var_name` =  'Auto_scroll';";
	$sql_auto_scroll = $handle->query($sql);
	if (!$sql_auto_scroll) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated auto_scroll <img src="'.$marks.'" class="iconalign" /></li>';

	// Update site's language.
	$sql = "UPDATE `" . table_prefix."config` SET `var_value` ='" .$language. "' where `var_name` = '\$language';";
	$sql_site_language = $handle->query($sql);
	if (!$sql_site_language) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated site language entry <img src="'.$marks.'" class="iconalign" /></li>';

	// Update the desc of user_language
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'Allow users to change Kliqqi language' WHERE `var_name` =  'user_language';";
	$sql_user_language = $handle->query($sql);
	if (!$sql_user_language) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc of user_language. <img src="'.$marks.'" class="iconalign" /></li>';	
	
	// Update urlmethod desc.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` ='<strong>1</strong> = Non-SEO Links.<br /> Example: /story.php?title=Example-Title<br /><strong>2</strong> SEO Method. <br />Example: /Category-Title/Story-title/.<br /><strong>Note:</strong> You must rename htaccess.default to .htaccess <strong>AND EDIT IT WHERE THE NOTES ARE!</strong>' where `var_name` = '\$URLMethod';";
	$sql_urlmethod = $handle->query($sql);
	if (!$sql_urlmethod) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title and desc for the "urlmethod" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update Story_Content_Tags_To_Allow_Normal title.
	$sql = "UPDATE `" . table_prefix."config` SET `var_desc` = 'leave blank to not allow tags. Examples are: &lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;' where `var_name` = 'Story_Content_Tags_To_Allow_Normal';";
	$sql_Story_Content_Tags_To_Allow_Normal = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_Normal) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated desc for the "Story_Content_Tags_To_Allow_Normal" <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Story_Content_Tags_To_Allow_Admin
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'leave blank to not allow tags. Examples are: &lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;' WHERE `var_name` =  'Story_Content_Tags_To_Allow_Admin';";
	$sql_Story_Content_Tags_To_Allow_Admin = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_Admin) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title of Story_Content_Tags_To_Allow_Admin. <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Update the title of Story_Content_Tags_To_Allow_God
	$sql = "UPDATE `" . table_prefix."config` set `var_desc` = 'leave blank to not allow tags. Examples are: &lt;p&gt;&lt;strong&gt;&lt;em&gt;&lt;u&gt;&lt;s&gt;&lt;sub&gt;&lt;sup&gt;&lt;ol&gt;&lt;ul&gt;&lt;li&gt;&lt;blockquote&gt;&lt;span&gt;&lt;div&gt;&lt;big&gt;&lt;small&gt;&lt;tt&gt;&lt;code&gt;&lt;kbd&gt;&lt;samp&gt;&lt;var&gt;&lt;del&gt;&lt;ins&gt;&lt;hr&gt;&lt;pre&gt;' WHERE `var_name` =  'Story_Content_Tags_To_Allow_God';";
	$sql_Story_Content_Tags_To_Allow_God = $handle->query($sql);
	if (!$sql_Story_Content_Tags_To_Allow_God) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated title of Story_Content_Tags_To_Allow_God. <img src="'.$marks.'" class="iconalign" /></li>';
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Updating the Groups member role</legend><ul>';
	// First ALtering the member role enum in group_member table
	$sql = "ALTER TABLE  `" . table_prefix."group_member` 
	CHANGE `member_role` `member_role` enum('admin','normal','moderator','flagged','banned') NOT NULL;";
	$sql_alter_role = $handle->query($sql);
	if ($sql_alter_role) {
		// Now we update empty role to normal (in all the column) 
		$sql = "Update `" . table_prefix . "group_member` SET `member_role` = 'normal' WHERE `member_role` = '';";
		$sql_update_role_normal = $handle->query($sql);
		
		// Now we update the group creator's role from moderator to admin
		$sql = "SELECT `group_id`, `group_creator` FROM `" . table_prefix. "groups`;";
		$sql_get_group_id_creator = $handle->query($sql);
		if ($sql_get_group_id_creator) {
			while($group = $sql_get_group_id_creator->fetch_assoc()) {
				$sql = "Update `" . table_prefix . "group_member` SET `member_role` = 'admin' WHERE `member_role` = 'moderator' && `member_user_id` = " .$group['group_creator'] . " && `member_group_id` = " . $group['group_id'];
				$sql_members = $handle->query($sql);
				if (!$sql_members) {
					$marks = $notok;
				}else{
					$marks = $ok;
				}
				echo '<li>Updated member role in group ID ' . $group['group_id'] .  ' <img src="'.$marks.'" class="iconalign" /></li>';
			}
		}
	}
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Changing Columns in Links table.</legend><ul>';
	$sql = "ALTER TABLE  `" . table_prefix."links`  
	CHANGE 	`link_status` `link_status` enum('discard','new','published','abuse','duplicate','page','spam','moderated','draft','scheduled') NOT NULL DEFAULT 'discard',
	CHANGE  `link_url`  `link_url` VARCHAR( 512 ) NOT NULL DEFAULT '';";
	$sql_alter_links - $handle->query($sql);
	echo '<li>Updated links Table link_status enum and link_url to VARCHAR 512</li>';
	
	$sql = "INSERT INTO `".table_prefix."links` (`link_id`, `link_author`, `link_status`, `link_randkey`, `link_votes`, `link_reports`, `link_comments`, `link_karma`, `link_modified`, `link_date`, `link_published_date`, `link_category`, `link_lang`, `link_url`, `link_url_title`, `link_title`, `link_title_url`, `link_content`, `link_summary`, `link_tags`, `link_field1`, `link_field2`, `link_field3`, `link_field4`, `link_field5`, `link_field6`, `link_field7`, `link_field8`, `link_field9`, `link_field10`, `link_field11`, `link_field12`, `link_field13`, `link_field14`, `link_field15`, `link_group_id`, `link_group_status`, `link_out`) VALUES
	(NULL, 1, 'page', 0, 0, 0, 0, '0.00', '2016-10-06 17:19:46', '2016-10-06 17:19:46', '0000-00-00 00:00:00', 0, 1, '', NULL, 'About', 'about', '<legend><strong>About Us</strong></legend>\r\n<p>Our site allows you to submit an article that will be voted on by other members. The most popular posts will be published to the front page, while the less popular articles are left in an ''New'' page until they acquire the set number of votes to move to the published page. This site is dependent on user contributed content and votes to determine the direction of the site.</p>\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'new', 0);";
	$sql_insert_page_links = $handle->query($sql);
	if (!$sql_insert_page_links) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserted sample Page in links table <img src="'.$marks.'" class="iconalign" /></li>';	
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Updating the misc_data table</legend><ul>';
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
	$sql = "INSERT INTO  `" . table_prefix."misc_data` ( `name` , `data` )
			VALUES ('captcha_comment_en', 'true'),
			('captcha_reg_en', 'true'),
			('captcha_story_en', 'true')";
		$sql_captcha_data = $handle->query($sql);
	if (!$sql_captcha_data) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Inserted captcha enabling comment, registration and story <img src="'.$marks.'" class="iconalign" /></li>';
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
	
	// Delete all the status module entries
	$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'status_%';";
	$sql_delete_status_entries = $handle->query($sql);
	if (!$sql_delete_status_entries) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted all the status module entries <img src="'.$marks.'" class="iconalign" /></li>';
	
	// Delete all reCaptcha entries
	$sql = "DELETE FROM `" . table_prefix."misc_data` WHERE `name` like 'reCaptcha_%';";
	$sql_delete_recaptcha_entries = $handle->query($sql);
	if (!$sql_delete_recaptcha_entries) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li class="warn-delete">Deleted all reCaptcha entries <img src="'.$marks.'" class="iconalign" /></li>';
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
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Updating data in Modules table.</legend><ul>';
	$sql = "select `name`,`folder` from `" . table_prefix."modules`";
	$sql_modules = $handle->query($sql);
	$to_delete = array('Human Check','Google Adsense Revenue Sharing','Status', 'Status Update Module');
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
		if ($module['folder'] == 'xml_sitemaps') {
			$sql = "DELETE FROM `" . table_prefix."config` WHERE (`var_page`,`var_name`) IN (('XmlSitemaps','XmlSitemaps_ping_google'),('XmlSitemaps','XmlSitemaps_ping_ask'),('XmlSitemaps','XmlSitemaps_ping_yahoo'),('XmlSitemaps','XmlSitemaps_yahoo_key'),('XmlSitemaps','XmlSitemaps_use_cache'),('XmlSitemaps','XmlSitemaps_cache_ttl'));";
			$sql_delete_xml_entries = $handle->query($sql);
			if (!$sql_delete_xml_entries) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li class="warn-delete">Deleted obsolete XmlSitemaps entries from config table <img src="'.$marks.'" class="iconalign" /></li>';
	
			$sql = "INSERT INTO `".table_prefix."config` (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','','','','link','Sitemap links page','<a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\">View the Sitemapindex</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=0\" target=\"_blank\">View the Links Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=pages0\" target=\"_blank\">View the Pages Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=users0\" target=\"_blank\">View the Users Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=groups0\" target=\"_blank\">View the Groups Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\">View the Navigations Sitemap</a>','define');";
			$sql_insert_xml_navigation_links = $handle->query($sql);
			if (!$sql_insert_xml_navigation_links) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>Inserted XmlSitemaps module navigation entry in the config table <img src="'.$marks.'" class="iconalign" /></li>';
			
			$sql = "Update `" . table_prefix . "config` SET `var_desc` = 'This makes friendly sitemap urls. SET to TRUE, only if you have set URL method to 2 in Dashboard -> Settings -> SEO -> URL Method.' WHERE `var_name` = 'XmlSitemaps_friendly_url';";
			$sql_XmlSitemaps_friendly_url = $handle->query($sql);
			if (!$sql_XmlSitemaps_friendly_url) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>Updated XmlSitemaps XmlSitemaps_friendly_url description <img src="'.$marks.'" class="iconalign" /></li>';	

			$sql = "Update `" . table_prefix . "config` SET `var_desc` = 'This module generates an index of sitemaps, here you can set the number of links you want to include in one sitemap from that index. <STRONG>NOTE THAT GOOGLE RECOMMENDS A MAXIMUM OF 1000 PER SITEMAP</STRONG>' WHERE `var_name` = 'XmlSitemaps_Links_per_sitemap';";
			$sql_XmlSitemaps_Links_per_sitemap = $handle->query($sql);
			if (!$sql_XmlSitemaps_Links_per_sitemap) {
				$marks = $notok;
			}else{
				$marks = $ok;
			}
			echo '<li>Updated XmlSitemaps XmlSitemaps_Links_per_sitemap description <img src="'.$marks.'" class="iconalign" /></li>';
		}
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
echo '</ul></fieldset><br />';

echo '<fieldset><legend>Update Admin user_url in users table</legend><ul>';
	$sql = "UPDATE `" . table_prefix."users` SET `user_url` = REPLACE(`user_url`, 'http://pligg.com', 'http://kliqqi.com');";
	$sql_update_user_url = $handle->query($sql);
	if (!$sql_update_user_url) {
		$marks = $notok;
	}else{
		$marks = $ok;
	}
	echo '<li>Updated user_url for admin user <img src="'.$marks.'" class="iconalign" /></li>';
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