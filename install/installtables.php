<?php
include_once (dirname(__FILE__) . '/../libs/db.php');

if (!isset($dblang)) { $dblang='en'; }

echo '<style>
.create-success{border: 2px solid #ffffff;padding: 10px;}
.alert-danger, .alert-error-tbl {
background-color: #FF0000;
border: 2px solid #ffffff;
padding: 10px;
color: #ffffff;
}
.info-alert{color:#31708f;background-color:#d9edf7;border: 2px solid #ffffff;padding: 10px;}
li{list-style: none;margin-top:10px;}
li .create-success, li .alert-error-tbl{margin-top:10px;}
</style>';

function checktableexists($table)
{
	global $conn;
	$result = $conn->query("SHOW TABLES LIKE '".$table."';");
	$numRows = $result->num_rows;
	if ($numRows < 1) {
		return false;
	}else{
	return true;
	}
}

function plikli_createtables($conn) {
	global $dblang;
	$notok = 'notok.png';
	$ok = 'ok.png';
	$warnings = array();
	$sql = 'DROP TABLE IF EXISTS `' . table_additional_categories . '`;';
	mysqli_query( $conn, $sql );
	$sql = "CREATE TABLE `" . table_additional_categories . "` (
	  `ac_link_id` int(11) NOT NULL,
	  `ac_cat_id` int(11) NOT NULL,
	  UNIQUE KEY `ac_link_id` (`ac_link_id`,`ac_cat_id`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_additional_categories);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_additional_categories.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_additional_categories.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_additional_categories.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	// ********************************	
	$sql = 'DROP TABLE IF EXISTS `' . table_categories . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_categories . "` (
	  `category__auto_id` int(11) NOT NULL auto_increment,
	  `category_lang` varchar(" . strlen($dblang) . ") collate utf8_general_ci NOT NULL default " . "'" . $dblang . "',
	  `category_id` int(11) NOT NULL default '0',
	  `category_parent` int(11) NOT NULL default '0',
	  `category_name` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `category_safe_name` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `rgt` int(11) NOT NULL default '0',
	  `lft` int(11) NOT NULL default '0',
	  `category_enabled` int(11) NOT NULL default '1',
	  `category_order` int(11) NOT NULL default '0',
	  `category_desc` varchar(255) collate utf8_general_ci NOT NULL,
	  `category_keywords` varchar(255) collate utf8_general_ci NOT NULL,
	  `category_author_level` enum('normal','moderator','admin') collate utf8_general_ci NOT NULL default 'normal',
	  `category_author_group` varchar(255) NOT NULL default '',
	  `category_votes` varchar(4) NOT NULL default '',
	  `category_karma` varchar(4) NOT NULL default '',
	  PRIMARY KEY  (`category__auto_id`),
	  KEY `category_id` (`category_id`),
	  KEY `category_parent` (`category_parent`),
	  KEY `category_safe_name` (`category_safe_name`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_categories);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_categories.' ... <img src="'.$ok.'" class="iconalign" />';

	$sql = "INSERT INTO `" . table_categories . "` (`category__auto_id`, `category_lang`, `category_id`, `category_parent`, `category_name`, `category_safe_name`, `rgt`, `lft`, `category_enabled`, `category_order`, `category_desc`, `category_keywords`, `category_author_level`, `category_author_group`, `category_votes`) VALUES (0, '" . $dblang . "', 0, 0, 'all', 'all', 3, 0, 2, 0, '', '', 'normal', '', '');";
	mysqli_query( $conn, $sql );
		echo '<ul>';
		echo '<li>INSERTING default into '. table_categories . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li>';
		
	$sql = "UPDATE `" . table_categories . "` SET `category__auto_id` = '0' WHERE `category_name` = 'all' LIMIT 1;";
	mysqli_query( $conn, $sql );
		echo '<li>UPDATING '. table_categories . '...<br />';
		printf("Affected rows (UPDATE): %d\n", $conn->affected_rows);
		echo '</li>';
		
	$sql = "INSERT INTO `" . table_categories . "` (`category__auto_id`, `category_lang`, `category_id`, `category_parent`, `category_name`, `category_safe_name`, `rgt`, `lft`, `category_enabled`, `category_order`, `category_desc`, `category_keywords`, `category_author_level`, `category_author_group`, `category_votes`) VALUES (1, '" . $dblang . "', 1, 0, 'News', 'News', 2, 1, 1, 0, '', '', 'normal', '', '');";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTING default "All" and "News" categories...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';	
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_categories.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_categories.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************	
	$sql = 'DROP TABLE IF EXISTS `' . table_comments . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_comments . "` (
	  `comment_id` int(20) NOT NULL auto_increment,
	  `comment_randkey` int(11) NOT NULL default '0',
	  `comment_parent` int(20) default '0',
	  `comment_link_id` int(20) NOT NULL default '0',
	  `comment_user_id` int(20) NOT NULL default '0',
	  `comment_date` datetime NOT NULL,
	  `comment_karma` smallint(6) NOT NULL default '0',
	  `comment_content` text collate utf8_general_ci NOT NULL,
	  `comment_votes` int(20) NOT NULL default '0',
	  `comment_status` enum('discard','moderated','published','spam') collate utf8_general_ci NOT NULL default 'published',
	  PRIMARY KEY  (`comment_id`),
	  UNIQUE KEY `comments_randkey` (`comment_randkey`,`comment_link_id`,`comment_user_id`,`comment_parent`),
	  KEY `comment_link_id` (`comment_link_id`, `comment_parent`, `comment_date`),
	  KEY `comment_link_id_2` (`comment_link_id`,`comment_date`),
	  KEY `comment_date` (`comment_date`),
	  KEY `comment_parent` (`comment_parent`,`comment_date`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_comments);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_comments.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_comments.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_comments.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************	
	$sql = 'DROP TABLE IF EXISTS `' . table_config . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_config . "` (
	  `var_id` int(11) NOT NULL auto_increment,
	  `var_page` varchar(50) collate utf8_general_ci NOT NULL,
	  `var_name` varchar(100) collate utf8_general_ci NOT NULL,
	  `var_value` varchar(255) collate utf8_general_ci NOT NULL,
	  `var_defaultvalue` varchar(50) collate utf8_general_ci NOT NULL,
	  `var_optiontext` varchar(200) collate utf8_general_ci NOT NULL,
	  `var_title` varchar(200) collate utf8_general_ci NOT NULL,
	  `var_desc` text collate utf8_general_ci NOT NULL,
	  `var_method` varchar(10) collate utf8_general_ci NOT NULL,
	  `var_enclosein` varchar(5) collate utf8_general_ci default NULL,
	  PRIMARY KEY  (`var_id`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_config);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_config.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_config.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_config.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************	
	$sql = 'DROP TABLE IF EXISTS `' . table_formulas . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_formulas . "` (
	  `id` int(11) NOT NULL auto_increment,
	  `type` varchar(10) collate utf8_general_ci NOT NULL,
	  `enabled` tinyint(1) NOT NULL,
	  `title` varchar(50) collate utf8_general_ci NOT NULL,
	  `formula` text collate utf8_general_ci NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_formulas);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_formulas.' ... <img src="'.$ok.'" class="iconalign" />';
		echo '<ul>';
	$sql = 'INSERT INTO `' . table_formulas . '` (`id`, `type`, `enabled`, `title`, `formula`) VALUES (1, \'report\', 1, \'Simple Story Reporting\', \'$reports > $votes * 3\');';
	mysqli_query( $conn, $sql );
		
		echo '<li>INSERTING formula into '. table_formulas . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_formulas.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_formulas.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************	
	$sql = 'DROP TABLE IF EXISTS `' . table_friends . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_friends . "` (
	  `friend_id` int(11) NOT NULL auto_increment,
	  `friend_from` bigint(20) NOT NULL default '0',
	  `friend_to` bigint(20) NOT NULL default '0',
	  PRIMARY KEY  (`friend_id`),
	  UNIQUE KEY `friends_from_to` (`friend_from`,`friend_to`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_friends);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_friends.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_friends.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_friends.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************	
	$sql = 'DROP TABLE IF EXISTS `' . table_groups . '`;';
	mysqli_query( $conn, $sql );
	$sql = "CREATE TABLE `".table_groups."` (
	  `group_id` int(20) NOT NULL auto_increment,
	  `group_creator` int(20) NOT NULL,
	  `group_status` enum('Enable','disable') collate utf8_general_ci NOT NULL,
	  `group_members` int(20) NOT NULL,
	  `group_date` datetime NOT NULL,
	  `group_safename` text collate utf8_general_ci NOT NULL,
	  `group_name` text collate utf8_general_ci NOT NULL,
	  `group_description` text collate utf8_general_ci NOT NULL,
	  `group_privacy` enum('private','public','restricted') collate utf8_general_ci NOT NULL,
	  `group_avatar` varchar(255) collate utf8_general_ci NOT NULL,
	  `group_vote_to_publish` int(20) NOT NULL,
	  `group_field1` varchar(255) collate utf8_general_ci NOT NULL,
	  `group_field2` varchar(255) collate utf8_general_ci NOT NULL,
	  `group_field3` varchar(255) collate utf8_general_ci NOT NULL,
	  `group_field4` varchar(255) collate utf8_general_ci NOT NULL,
	  `group_field5` varchar(255) collate utf8_general_ci NOT NULL,
	  `group_field6` varchar(255) collate utf8_general_ci NOT NULL,
	`group_notify_email` tinyint(1) NOT NULL,
		PRIMARY KEY  (`group_id`),
		KEY `group_name` (`group_name`(100)),
		KEY `group_creator` (`group_creator`, `group_status`)
		) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_groups);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_groups.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_groups.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_groups.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_group_member . '`;';
	mysqli_query( $conn, $sql );
	$sql = "CREATE TABLE `".table_group_member."` (
		`member_id` INT( 20 ) NOT NULL auto_increment,
		`member_user_id` INT( 20 ) NOT NULL ,
		`member_group_id` INT( 20 ) NOT NULL ,
		`member_role` ENUM( 'admin', 'normal', 'moderator', 'flagged', 'banned' ) collate utf8_general_ci NOT NULL,
		`member_status` ENUM( 'active', 'inactive') collate utf8_general_ci NOT NULL,
		PRIMARY KEY  (`member_id`),
		KEY `user_group` (`member_group_id`, `member_user_id`)
		) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_group_member);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_group_member.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_group_member.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_group_member.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_group_shared . '`;';
	mysqli_query( $conn, $sql );
	$sql = "CREATE TABLE `".table_group_shared."` (
		`share_id` INT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`share_link_id` INT( 20 ) NOT NULL ,
		`share_group_id` INT( 20 ) NOT NULL ,
		`share_user_id` INT( 20 ) NOT NULL,
		UNIQUE KEY `share_group_id` (`share_group_id`,`share_link_id`)
		) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_group_shared);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_group_shared.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_group_shared.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_group_shared.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_login_attempts . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `". table_login_attempts ."` (
		  `login_id` int(11) NOT NULL auto_increment,
		  `login_username` varchar(100) collate utf8_general_ci default NULL,
		  `login_time` datetime NOT NULL,
		  `login_ip` varchar(100) collate utf8_general_ci default NULL,
		  `login_count` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`login_id`),
		  UNIQUE KEY `login_username` (`login_ip`,`login_username`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_login_attempts);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_login_attempts.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_login_attempts.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_login_attempts.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	/**********************************
	Redwine: checking for the MySQL Server version. If it is older than 5.0.3, then `link_url` varchar will be the maximum of 255; otherwise, we set it to 512 to accommodate long urlencoded.
	**********************************/
	$pattern = '/[^0-9-.]/i';
	$replacement = '';
	$mysqlServerVersion = $conn->server_info;
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

	echo '<li class="info-alert">';
	printf("Your MySQL Server version is: %s\n", $mysqlServerVersion);
	echo "<br />";
	printf("The varchar maximum character length compatible with your $mysqlServerVersion version to be set for `link_url`, in the links table, is: %s\n", $urllength);
	echo '</li>';
	
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_links . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_links . "` (
	  `link_id` int(20) NOT NULL auto_increment,
	  `link_author` int(20) NOT NULL default '0',
	  `link_status` enum('discard','new','published','abuse','duplicate','page','spam','moderated','draft','scheduled') collate utf8_general_ci NOT NULL default 'discard',
	  `link_randkey` int(20) NOT NULL default '0',
	  `link_votes` int(20) NOT NULL default '0',
	  `link_reports` int(20) NOT NULL default '0',
	  `link_comments` int(20) NOT NULL default '0',
	  `link_karma` decimal(10,2) NOT NULL default '0.00',
	  `link_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `link_date` timestamp NOT NULL,
	  `link_published_date` timestamp NOT NULL,
	  `link_category` int(11) NOT NULL default '0',
	  `link_lang` int(11) NOT NULL default '1',
	  `link_url` varchar($urllength) collate utf8_general_ci NOT NULL default '',
	  `link_url_title` text collate utf8_general_ci,
	  `link_title` text collate utf8_general_ci NOT NULL,
	  `link_title_url` varchar(255) collate utf8_general_ci default NULL,
	  `link_content` mediumtext collate utf8_general_ci NOT NULL,
	  `link_summary` text collate utf8_general_ci,
	  `link_tags` text collate utf8_general_ci,
	  `link_field1` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field2` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field3` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field4` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field5` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field6` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field7` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field8` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field9` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field10` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field11` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field12` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field13` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field14` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_field15` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `link_group_id` int(20) NOT NULL default '0',
	  `link_group_status` enum(  'new',  'published',  'discard' ) DEFAULT 'new' NOT NULL,
	  `link_out` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`link_id`),
	  KEY `link_author` (`link_author`),
	  KEY `link_url` (`link_url`),
	  KEY `link_status` (`link_status`),
	  KEY `link_title_url` (`link_title_url`),
	  KEY `link_date` (`link_date`),
	  KEY `link_published_date` (`link_published_date`),
	  FULLTEXT KEY `link_url_2` (`link_url`,`link_url_title`,`link_title`,`link_content`,`link_tags`),
	  FULLTEXT KEY `link_tags` (`link_tags`),
	  FULLTEXT KEY `link_search` (`link_title`,`link_content`,`link_tags`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_links);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_links.' ... <img src="'.$ok.'" class="iconalign" />';
		echo '<ul>';
	$sql = "INSERT INTO `" . table_links . "` (`link_id`, `link_author`, `link_status`, `link_randkey`, `link_votes`, `link_reports`, `link_comments`, `link_karma`, `link_modified`, `link_date`, `link_published_date`, `link_category`, `link_lang`, `link_url`, `link_url_title`, `link_title`, `link_title_url`, `link_content`, `link_summary`, `link_tags`, `link_field1`, `link_field2`, `link_field3`, `link_field4`, `link_field5`, `link_field6`, `link_field7`, `link_field8`, `link_field9`, `link_field10`, `link_field11`, `link_field12`, `link_field13`, `link_field14`, `link_field15`, `link_group_id`, `link_out`) VALUES (1, 1, 'page', 0, 0, 0, 0, '0.00', NOW(), NOW(), '0000-00-00 00:00:00', 0, 1, '', NULL, 'About', 'about', '<legend><strong>About Us</strong></legend>\r\n<p>Our site allows you to submit an article that will be voted on by other members. The most popular posts will be published to the front page, while the less popular articles are left in an \'New\' page until they acquire the set number of votes to move to the published page. This site is dependent on user contributed content and votes to determine the direction of the site.</p>\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0);";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTED sample page into  '. table_links . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_links.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_links.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_messages . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" .table_messages. "` (
	  `idMsg` int(11) NOT NULL auto_increment,
	  `title` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `body` text NOT NULL,
	  `sender` int(11) NOT NULL default '0',
	  `receiver` int(11) NOT NULL default '0',
	  `senderLevel` int(11) NOT NULL default '0',
	  `readed` int(11) NOT NULL default '0',
	  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`idMsg`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_messages);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_messages.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_messages.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_messages.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_misc_data . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_misc_data . "` (
		`name` VARCHAR( 20 ) collate utf8_general_ci NOT NULL ,
		`data` TEXT collate utf8_general_ci NOT NULL ,
		PRIMARY KEY ( `name` )
		) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
		mysqli_query( $conn, $sql );
	$successful = checktableexists(table_misc_data);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_misc_data.' ... <img src="'.$ok.'" class="iconalign" />';
	
		echo '<ul>';
	$sql = "INSERT INTO `" . table_misc_data . "` ( `name` , `data` ) VALUES
	('plikli_version', '4.1.0'),
	('adcopy_lang', 'en'),
	('adcopy_theme', 'white'),
	('adcopy_pubkey', 'Rp827COlEH2Zcc2ZHrXdPloU6iApn89K'),
	('adcopy_privkey', '7lH2UFtscdc2Rb7z3NrT8HlDIzcWD.N1'),
	('adcopy_hashkey', 'rWwIbi8Nd6rX-NYvuB6sQUJV6ihYHa74'),
	('captcha_method', 'solvemedia'),
	('validate', 0),
	('captcha_comment_en', 'true'),
	('captcha_reg_en', 'true'),
	('captcha_story_en', 'true'),
	('karma_submit_story','+15'),
	('karma_submit_comment','+10'),
	('karma_story_publish','+50'),
	('karma_story_vote','+1'),
	('karma_story_unvote','-1'),
	('karma_comment_vote','0'),
	('karma_story_discard','-250'),
	('karma_story_spam','-10000'),
	('karma_comment_delete','-50'),
	('modules_update_date',DATE_FORMAT(NOW(),'%Y/%m/%d')),
	('modules_update_url','https://www.plikli.com/mods/version-update.txt'),
	('plikli_update',''),
	('plikli_update_url','https://www.plikli.com/download_plikli/'),
	('modules_update_unins',''),
	('modules_upd_versions','');";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTED data into  '. table_misc_data . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li>';
		
	//register validation//
	$randkey = '';
	for ($i=0; $i<32; $i++)
		$randkey .= chr(rand(48,200));

	$sql = "INSERT INTO `" . table_misc_data . "` ( `name` , `data` ) VALUES ('hash', '$randkey');";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTED hash key into  '. table_misc_data . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_misc_data.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_misc_data.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_modules . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_modules . "` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(50) collate utf8_general_ci NOT NULL,
	  `version` float(10,1) NOT NULL,
	  `latest_version` float(10,1) NOT NULL,
	  `folder` varchar(50) collate utf8_general_ci NOT NULL,
	  `enabled` tinyint(1) NOT NULL,
	  `weight` INT NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_modules);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_config.' ... <img src="'.$ok.'" class="iconalign" />';
		echo '<ul>';
	// Adding default modules.
	$sql = "INSERT INTO `" . table_modules . "` (`name`, `version`, `latest_version`, `folder`, `enabled`, `weight`) VALUES 
	('Admin Modify Language', 2.1, 0, 'admin_language', 1,0),
	('Captcha', 2.5, 0, 'captcha', 1,0),
	('Simple Private Messaging', 3.0, 0, 'simple_messaging', 1,0),
	('Sidebar Stories', 2.2, 0, 'sidebar_stories', 1,0),
	('Sidebar Comments', 2.1, 0, 'sidebar_comments', 1,0),
	('Karma module', 1.0, 0, 'karma', 1,0);";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTED default Modules into '. table_modules . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_modules.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_modules.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_old_urls . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_old_urls ."` (
	  `old_id` int(11) NOT NULL auto_increment,
	  `old_link_id` int(11) NOT NULL,
	  `old_title_url` varchar(255) collate utf8_general_ci NOT NULL,
	  PRIMARY KEY  (`old_id`),
	  KEY `old_title_url` (  `old_title_url` )
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_old_urls);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_old_urls.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_old_urls.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_old_urls.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_redirects . '`;';
	mysqli_query( $conn, $sql );
	$sql = "CREATE TABLE `" . table_redirects . "` (
	  `redirect_id` int(11) NOT NULL auto_increment,
	  `redirect_old` varchar(255) NOT NULL,
	  `redirect_new` varchar(255) NOT NULL,
	  PRIMARY KEY  (`redirect_id`),
	  KEY `redirect_old` (`redirect_old`)
		) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_redirects);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_redirects.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_redirects.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_redirects.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_saved_links . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_saved_links ."` (
	  `saved_id` int(11) NOT NULL auto_increment,
	  `saved_user_id` int(11) NOT NULL,
	  `saved_link_id` int(11) NOT NULL,
	  `saved_privacy` ENUM( 'private', 'public' ) collate utf8_general_ci NOT NULL default 'public',
	  PRIMARY KEY  (`saved_id`),
	  KEY `saved_user_id` (  `saved_user_id` )
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_saved_links);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_saved_links.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_saved_links.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_saved_links.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_tags . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_tags . "` (
	  `tag_link_id` int(11) NOT NULL default '0',
	  `tag_lang` varchar(4) collate utf8_general_ci NOT NULL default 'en',
	  `tag_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `tag_words` varchar(64) collate utf8_general_ci NOT NULL default '',
	  UNIQUE KEY `tag_link_id` (`tag_link_id`,`tag_lang`,`tag_words`),
	  KEY `tag_lang` (`tag_lang`,`tag_date`),
	  KEY `tag_words` (`tag_words`,`tag_link_id`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_tags);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_tags.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_tags.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_tags.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_tag_cache . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_tag_cache . "` (
		  `tag_words` varchar(64) NOT NULL,
		  `count` int(11) NOT NULL
		) ENGINE =MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_tag_cache);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_tag_cache.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_tag_cache.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_tag_cache.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);
	
	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_totals . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_totals . "` (
		`name` varchar(10) NOT NULL,
		`total` int(11) NOT NULL,
		PRIMARY KEY  (`name`)
		) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_totals);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_totals.' ... <img src="'.$ok.'" class="iconalign" />';
	
		echo '<ul>';
	$sql = "insert into `" . table_totals . "` (`name`, `total`) values
	('published', 0),
	('new', 0),
	('discard', 0),
	('draft', 0),
	('scheduled', 0);";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTED default totals into '. table_totals . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_totals.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_totals.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_trackbacks . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_trackbacks . "` (
	  `trackback_id` int(10) unsigned NOT NULL auto_increment,
	  `trackback_link_id` int(11) NOT NULL default '0',
	  `trackback_user_id` int(11) NOT NULL default '0',
	  `trackback_type` enum('in','out') NOT NULL default 'in',
	  `trackback_status` enum('ok','pendent','error') NOT NULL default 'pendent',
	  `trackback_modified` timestamp NOT NULL,
	  `trackback_date` timestamp NULL default NULL,
	  `trackback_url` varchar(200) collate utf8_general_ci NOT NULL default '',
	  `trackback_title` text collate utf8_general_ci DEFAULT NULL,
	  `trackback_content` text collate utf8_general_ci DEFAULT NULL,
	  PRIMARY KEY  (`trackback_id`),
	  UNIQUE KEY `trackback_link_id_2` (`trackback_link_id`,`trackback_type`,`trackback_url`),
	  KEY `trackback_link_id` (`trackback_link_id`),
	  KEY `trackback_url` (`trackback_url`),
	  KEY `trackback_date` (`trackback_date`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_trackbacks);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_trackbacks.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_trackbacks.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_trackbacks.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_users . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_users . "` (
	  `user_id` int(20) NOT NULL auto_increment,
	  `user_login` varchar(32) collate utf8_general_ci NOT NULL default '',
	  `user_level` enum('normal','moderator','admin','Spammer') collate utf8_general_ci NOT NULL default 'normal',
	  `user_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `user_date` timestamp NOT NULL,
	  `user_pass` varchar(80) collate utf8_general_ci NOT NULL default '',
	  `user_email` varchar(128) collate utf8_general_ci NOT NULL default '',
	  `user_names` varchar(128) collate utf8_general_ci NOT NULL default '',
	  `user_karma` decimal(10,2) default '0.00',
	  `user_url` varchar(128) collate utf8_general_ci NOT NULL default '',
	  `user_lastlogin` timestamp NOT NULL,
	  `user_facebook` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `user_twitter` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `user_linkedin` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `user_googleplus` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `user_skype` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `user_pinterest` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `public_email` varchar(64) collate utf8_general_ci NOT NULL default '',
	  `user_avatar_source` varchar(255) collate utf8_general_ci NOT NULL default '',
	  `user_ip` varchar(20) collate utf8_general_ci default '0',
	  `user_lastip` varchar(20) collate utf8_general_ci default '0',
	  `last_reset_request` timestamp NOT NULL,
	  `last_reset_code` varchar(255) collate utf8_general_ci default NULL,
	  `user_location` varchar(255) collate utf8_general_ci default NULL,
	  `user_occupation` varchar(255) collate utf8_general_ci default NULL,
	  `user_categories` VARCHAR(255) collate utf8_general_ci NOT NULL default '',
	  `user_enabled` tinyint(1) NOT NULL default '1',
	  `user_language` varchar(32) collate utf8_general_ci default NULL,
	  PRIMARY KEY  (`user_id`),
	  UNIQUE KEY `user_login` (`user_login`),
	  KEY `user_email` (`user_email`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_users);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_users.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_users.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_users.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_votes . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `" . table_votes . "` (
	  `vote_id` int(20) NOT NULL auto_increment,
	  `vote_type` enum('links','comments') NOT NULL default 'links',
	  `vote_date` timestamp NOT NULL,
	  `vote_link_id` int(20) NOT NULL default '0',
	  `vote_user_id` int(20) NOT NULL default '0',
	  `vote_value` smallint(11) NOT NULL default '1',
	  `vote_karma` int(11) NULL default '0',
	  `vote_ip` varchar(64) default NULL,
	  PRIMARY KEY  (`vote_id`),
	  KEY `user_id` (`vote_user_id`),
	  KEY `link_id` (`vote_link_id`),
	  KEY `vote_type` (`vote_type`,`vote_link_id`,`vote_user_id`,`vote_ip`)
	) ENGINE = MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_votes);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_votes.' ... <img src="'.$ok.'" class="iconalign" /></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_votes.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_votes.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	$sql = 'DROP TABLE IF EXISTS `' . table_widgets . '`;';
	mysqli_query( $conn, $sql );

	$sql = "CREATE TABLE `".table_widgets."` (
		  `id` int(11) NOT NULL auto_increment,
		  `name` varchar(50) collate utf8_general_ci default NULL,
		  `version` float NOT NULL,
		  `latest_version` float NOT NULL,
		  `folder` varchar(50) collate utf8_general_ci default NULL,
		  `enabled` tinyint(1) NOT NULL,
		  `column` enum('left','right') collate utf8_general_ci NOT NULL,
		  `position` int(11) NOT NULL,
		  `display` char(5) collate utf8_general_ci NOT NULL,
		  PRIMARY KEY  (`id`),
		  UNIQUE KEY `folder` (`folder`)
	) ENGINE =MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	mysqli_query( $conn, $sql );
	$successful = checktableexists(table_widgets);
	if($successful == true) {
		echo '<li class="create-success">Successfully Created Table: '.table_widgets.' ... <img src="'.$ok.'" class="iconalign" />';

		echo '<ul>';
	$sql = "INSERT INTO `".table_widgets."` (`id`, `name`, `version`, `latest_version`, `folder`, `enabled`, `column`, `position`, `display`) VALUES 
	(NULL, 'Dashboard Tools', 0.1, 0, 'dashboard_tools', 1, 'left', 4, ''),
	(NULL, 'Statistics', 3.0, 0, 'statistics', 1, 'left', 1, ''),
	(NULL, 'Plikli CMS', 1.0, 0, 'plikli_cms', 1, 'right', 5, ''),
	(NULL, 'Plikli News', 0.1, 0, 'plikli_news', 1, 'right', 6, '');";
	mysqli_query( $conn, $sql );
		echo '<li>INSERTED default Widgets into '. table_widgets . '...<br />';
		printf("Affected rows (INSERT): %d\n", $conn->affected_rows);
		echo '</li></ul></li>';
	}else{
		echo '<li class="alert-error-tbl">Table: '.table_widgets.' was not successfully created! <img src="'.$notok.'" class="iconalign" /></li>';
		$warnings[] = 'Table: '.table_widgets.' was not successfully created!';
	}
	flush();
    ob_flush();
    time_nanosleep(0, 500000000);

	// ********************************
	/* Inserting data in the config table*/
	$stmt = file_get_contents(dirname(__FILE__) . '/install_config_table.sql');
	$stmt = str_replace("INSERT INTO `table_config`", "INSERT INTO `".table_prefix."config`", $stmt);
			$stmt = str_replace("'table_prefix', 'plikli_'", "'table_prefix', '" . table_prefix . "'", $stmt);
	$stmt = str_replace("{\$_SESSION[\'language\']}", "{$_SESSION['language']}", $stmt);
			mysqli_query($conn, $stmt);
		if (mysqli_error($conn)) {
				print htmlentities($stmt);
				print mysqli_error($conn);
				exit;
		}else{
			echo '<li class="create-success">INSERTED data in the Config Table...</li><br />';
			}
	if (empty($warnings)) {
	return 1;
	}else{
		echo '<fieldset><legend>Additional Instructions to follow!</legend><div class="alert alert-danger"><ul>';
		echo '<li><span style="background-color:#ffffff;color:#000000;font-weight:bold;">There was a problem creating all the tables!</span></li>';
		$output = '';
		if ($warnings) {
			foreach ($warnings as $warning) {
				$output.="<li>$warning</li><br />";
			}
			echo $output;
		}
		echo '</ul></div></fieldset><br />';
		return 0;
	}
}

?>