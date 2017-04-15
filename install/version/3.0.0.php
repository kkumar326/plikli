<?php
// This file is for performing an upgrade from Pligg 2.0.3 to kliqqi 3.0.0

// Report all PHP errors
// error_reporting(E_ALL);

// Check for the current version within each upgrade file
$sql = "SELECT data FROM " . table_misc_data . " WHERE name = 'pligg_version'";
$pligg_version = $db->get_var($sql);
// Check if you need to run the one time upgrade to pligg 2.0.1
if (version_compare($pligg_version, '3.0.0') <= 0) {

	echo '<li>Performing one-time Kliqqi 3.0.0 Upgrade</li>';
	
	$sql = "UPDATE ".table_config." 
			SET `var_title` = 'Negative Votes Story Discard' 
			WHERE `var_name` = 'buries_to_spam';";
    $db->query($sql);	
	$sql = "UPDATE `" . table_config . "` 
			SET `var_desc` = 'If set to 1, stories with enough down votes will be discarded. The formula for determining what gets buried is stored in the database table table_formulas. It defaults to discarding stories with 3 times more downvotes than upvotes.'
			WHERE `var_name` = 'buries_to_spam';";
	$db->query($sql);
	$sql = "UPDATE ".table_config." 
			SET `var_optiontext` = '1 = on / 0 = off' 
			WHERE `var_name` = 'buries_to_spam';";
    $db->query($sql);	
	echo '<li>Updated the title and description for the "Negative Votes Story Discard" feature</li>';	
	
	// Update version number and CMS name
	$sql = "UPDATE `" . table_misc_data . "` SET `data` = '3.0.0', `name` = 'kliqqi_version' WHERE `name` = 'pligg_version';";
	$db->query($sql);
	echo '<li>Updated version number to 3.0.0 and name to kliqqi_version</li>';
	
	// Update version misc_validate
	$sql = "UPDATE `" . table_config . "` SET `var_desc` ='Require users to validate their email address?<br />If you set to true, then click on the link below to also set the email to be used for sending the message.<br /><a href=\"../module.php?module=admin_language\">Set the email</a>. Type @ in the filter box and click Filter to get the value to modify. Do not forget to click save.' where `var_name` = 'misc_validate';";
	$db->query($sql);
	echo '<li>Updated the misc_validate field</li>';
	
	// Update version $trackbackURL
	$sql = "UPDATE `" . table_config . "` set `var_value` = 'kliqqi.com', `var_defaultvalue` = 'kliqqi.com', `var_optiontext` = 'kliqqi.com' WHERE `var_name` = '\$trackbackURL';";
	$db->query($sql);
	echo '<li>Updated the trackbackURL field</li>';
	
	// Update the Location Installed var_name to $my_kliqqi_base
	$sql = "UPDATE `" . table_config . "` set `var_name` = '\$my_kliqqi_base', `var_desc` = '<strong>Examples</strong>\r\n<br />\r\nhttp://demo.kliqqi.com<br />\r\nhttp://localhost<br />\r\nhttp://www.kliqqi.com', `var_title` = 'Kliqqi Base Folder' WHERE `var_name` =  '\$my_pligg_base';";
	$db->query($sql);
	echo '<li>Updated the Location Installed var_name to $my_kliqqi_base, var_desc and var_title where var_name = "my_kliqqi_base"</li>';
	
	// Update the Location Installed description for $my_base_url
	$sql = "UPDATE `" . table_config . "` set `var_desc` = '<strong>Examples</strong>\r\n<br />\r\n/kliqqi -- if installed in the /kliqqi subfolder<br />\r\nLeave blank if installed in the site root folder.' WHERE `var_name` =  '\$my_base_url';";
	$db->query($sql);
	echo '<li>Updated the var_desc where var_name = "my_base_url"</li>';
	
	// Update the description for $USER_SPAM_RULESET
	$sql = "UPDATE `" . table_config . "` set `var_desc` = 'What file should Kliqqi write to if you mark items as spam?' WHERE `var_name` =  '\$USER_SPAM_RULESET';";
	$db->query($sql);
	echo '<li>Updated the description for "USER_SPAM_RULESET"</li>';
	
	// Update the description for $USER_SPAM_RULESET
	$sql = "UPDATE `" . table_config . "` set `var_defaultvalue` = 'kliqqi_', `var_desc` = 'Table prefix. Ex: kliqqi_ makes the users table become kliqqi_users. Note: changing this will not automatically rename your tables!' WHERE `var_name` =  'table_prefix';";
	$db->query($sql);
	echo '<li>Updated the default value of table prefix and its description.</li>';
	
	// Update the description for misc_validate
	$sql = "UPDATE `" . table_config . "` set `var_desc` = 'Require users to validate their email address?<br />If you set to true, then click on the link below to also set the email to be used for sending the message.<br /><a href='../module.php?module=admin_language'>Set the email</a>. Type @ in the filter box and click Filter to get the value to modify. Do not forget to click save.' WHERE `var_name` =  'misc_validate';";
	$db->query($sql);
	echo '<li>Updated the description of misc validate</li>';
	
	// Update the description for user language
	$sql = "UPDATE `" . table_config . "` set `var_desc` = 'Allow users to change Kliqqi language' WHERE `var_name` =  'user_language';";
	echo '<li>Updated the description of misc validate</li>';
	$db->query($sql);
	
	// Update captcha adcopy public key
	$sql = "UPDATE `" . table_misc_data . "` SET `data` = '1G9ho6tcbpytfUxJ0SlrSNt0MjjOB0l2' WHERE `name` = 'adcopy_pubkey';";
	$db->query($sql);
	echo '<li>Updated captcha adcopy public key</li>';
	
	// Update captcha adcopy_privkey key
	$sql = "UPDATE `" . table_misc_data . "` SET `data` = 'PjH8h3gpjQrBKihJ8dlLN8sbcmvW1nv-' WHERE `name` = 'adcopy_privkey';";
	$db->query($sql);
	echo '<li>Updated captcha adcopy private key</li>';
	
	// Update captcha adcopy_hashkey key
	$sql = "UPDATE `" . table_misc_data . "` SET `data` = 'eq1xxSfyG55k4ll7CxPCO6XP9-cIWnTf' WHERE `name` = 'adcopy_hashkey';";
	$db->query($sql);
	echo '<li>Updated captcha adcopy private key</li>';
	
	// Update captcha version in table modules
	$sql = "UPDATE `" . table_modules . "` SET `version` = '2.5' WHERE `name` = 'Captcha';";
	$db->query($sql);
	echo '<li>Updated captcha module version</li>';
	
	// Update Simple Private Messaging version in table modules
	$sql = "UPDATE `" . table_modules . "` SET `version` = '2.4' WHERE `name` = 'Simple Private Messaging';";
	$db->query($sql);
	echo '<li>Updated Simple Private Messaging version in table modules</li>';
	
	// Update karma version in table modules
	$sql = "UPDATE `" . table_modules . "` SET `version` = '1.0' WHERE `name` = 'Karma module';";
	$db->query($sql);
	echo '<li>Update karma version in table modules</li>';
	
	// Update table widgets; changing the name of Admin Panel Tools to Dashboard Tools
	$sql = "UPDATE `" . table_widgets . "` SET `name` = 'Dashboard Tools', `folder` = 'dashboard_tools' WHERE `name` = 'Admin Panel Tools';";
	$db->query($sql);
	echo '<li>Updated table widgets; changing the name of Admin Panel Tools to Dashboard Tools</li>';
	
	// Renaming the panel_tools folder to dashboard_tools and the dashboard_tools/panel_tools_readme.htm 
	rename('../widgets/panel_tools','../widgets/dashboard_tools');
	rename('../widgets/dashboard_tools/panel_tools_readme.htm','../widgets/dashboard_tools/dashboard_tools_readme.htm');
	echo '<li>Renaming the panel_tools folder to dashboard_tools and the dashboard_tools/panel_tools_readme.htm</li>';
	
	// Update table widgets; changing the version of statistics widget
	$sql = "UPDATE `" . table_widgets . "` SET `version` = '3.0' WHERE `name` = 'Statistics';";
	$db->query($sql);
	echo '<li>Updated table widgets; changing the version of statistics widget</li>';
	
	// Update table widgets; changing the name of Pligg CMS to Kliqqi CMS
	$sql = "UPDATE `" . table_widgets . "` SET `name` = 'Kliqqi CMS', `folder` = 'kliqqi_cms' WHERE `name` = 'Pligg CMS';";
	$db->query($sql);
	echo '<li>Update table widgets; changing the name of Pligg CMS to Kliqqi CMS</li>';
	
	// Renaming the pligg_cms folder to kliqqi_cms 
	rename('../widgets/pligg_cms','../widgets/kliqqi_cms');
	rename('../widgets/kliqqi_cms/pligg_cms_readme.htm','../widgets/kliqqi_cms/kliqqi_cms_readme.htm');
	echo '<li>Renaming the pligg_cms folder to kliqqi_cms</li>';
	
	// Update table widgets; changing the name of Pligg News to Kliqqi News
	$sql = "UPDATE `" . table_widgets . "` SET `name` = 'Kliqqi News', `folder` = 'kliqqi_news' WHERE `name` = 'Pligg News';";
	$db->query($sql);
	echo '<li>Update table widgets; changing the name of Pligg News to Kliqqi News</li>';
	
	// Renaming the pligg_news folder to kliqqi_news 
	rename('../widgets/pligg_news','../widgets/kliqqi_news');
	rename('../widgets/kliqqi_news/pligg_news_readme.htm','../widgets/kliqqi_news/kliqqi_news_readme.htm');
	echo '<li>Renaming the pligg_cms folder to kliqqi_cms</li>';
	
	
	
	
	// Finished 3.0.0 upgrade
	echo'</ul>';
}

	
?>