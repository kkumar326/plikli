<?php
session_start();
include_once('./languages/lang_english.php');
//include('db-mysqli.php');
/* Redwine: creating a mysqli connection */
/*$handle = new mysqli(EZSQL_DB_HOST,EZSQL_DB_USER,EZSQL_DB_PASSWORD,EZSQL_DB_NAME);*/
	/* check connection */
	/*if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$handle->set_charset("utf8");*/
	
$LocationInstalled = array();
$Logo = array();
$Hidden = array();
$SEO = array();
$AntiSpam = array();
$Submit = array();
$Story = array();
$Comments = array();
$Voting = array();
$Tags = array();
$Anonymous = array();
$OutGoing = array();
$Groups = array();
$Avatars = array();
$Live = array();
$Template = array();
$Misc = array();
$XmlSitemaps = array();
$other = array();

$config_sections = array("LocationInstalled","Logo","Hidden","SEO","AntiSpam","Submit","Story","Comments","Voting","Tags","Anonymous","OutGoing","Groups","Avatars","Live","Template","Misc");

$maketemp = "
	CREATE TEMPORARY TABLE `temp_config` (
	`var_id` int(11) NOT NULL AUTO_INCREMENT,
	`var_page` varchar(50) NOT NULL,
	`var_name` varchar(100) NOT NULL,
	`var_value` varchar(255) NOT NULL,
	`var_defaultvalue` varchar(50) NOT NULL,
	`var_optiontext` varchar(200) NOT NULL,
	`var_title` varchar(200) NOT NULL,
	`var_desc` text NOT NULL,
	`var_method` varchar(10) NOT NULL,
	`var_enclosein` varchar(5) DEFAULT NULL,
	PRIMARY KEY (`var_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;"; 
	$handle->query($maketemp);
	
$stmt = file_get_contents(dirname(__FILE__) . '/install_config_table.sql');
	$stmt = str_replace("INSERT INTO `table_config`", "INSERT INTO `temp_config`", $stmt);
$handle->query($stmt);

echo '<fieldset><legend>Reordering the config Table for better organization and presentation in Plikli Dashboard Settings.</legend><ul>';	
	$sql = "select * from `" . table_prefix."config`";
	$sql_config = $handle->query($sql);
	if ($sql_config) {
		$row_cnt = $sql_config->num_rows;
		if ($row_cnt) {
			$count = 0;
			while ($config_rows = $sql_config->fetch_assoc()) {
				if (strpos($config_rows['var_page'], 'Location Installed') !== false) {
					$LocationInstalled[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Logo') !== false) {
					$Logo[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Hidden') !== false) {
					$Hidden[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'SEO') !== false) {
					$SEO[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'AntiSpam') !== false) {
					$AntiSpam[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Submit') !== false) {
					$Submit[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Story') !== false) {
					$Story[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Comments') !== false) {
					$Comments[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Voting') !== false) {
					$Voting[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Tags') !== false) {
					$Tags[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Anonymous') !== false) {
					$Anonymous[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'OutGoing') !== false) {
					$OutGoing[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Groups') !== false) {
					$Groups[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Avatars') !== false) {
					$Avatars[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Live') !== false) {
					$Live[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Template') !== false) {
					$Template[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'Misc') !== false) {
					$Misc[] = $config_rows;
				}elseif (strpos($config_rows['var_page'], 'XmlSitemaps') !== false) {
					$XmlSitemaps[] = $config_rows;
				}else{
					$other[] = $config_rows;
				}
			}
			echo "$count<br /><br />";
		}
	}	
/*echo "LocationInstalled " . sizeof($LocationInstalled)."<br />";
echo "Logo " . sizeof($Logo)."<br />";
echo "Hidden " . sizeof($Hidden)."<br />";
echo "SEO " . sizeof($SEO)."<br />";
echo "AntiSpam " . sizeof($AntiSpam)."<br />";
echo "Submit " . sizeof($Submit)."<br />";
echo "Story " . sizeof($Story)."<br />";
echo "Comments " . sizeof($Comments)."<br />";
echo "Voting " . sizeof($Voting)."<br />";
echo "Tags " . sizeof($Tags)."<br />";
echo "Anonymous " . sizeof($Anonymous)."<br />";
echo "OutGoing " . sizeof($OutGoing)."<br />";
echo "Groups " . sizeof($Groups)."<br />";
echo "Avatars " . sizeof($Avatars)."<br />";
echo "Live " . sizeof($Live)."<br />";
echo "Template " . sizeof($Template)."<br />";
echo "Misc " . sizeof($Misc)."<br />";
echo "XmlSitemaps " . sizeof($XmlSitemaps)."<br />";
echo "other " . sizeof($other)."<br />";*/

	//inserting into the temp table
	if (sizeof($LocationInstalled) > 0) {
		if (in_array('LocationInstalled',$config_sections)) {
		foreach($LocationInstalled as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED LocationInstalled): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Logo) > 0) {
		if (in_array('Logo',$config_sections)) {
		foreach($Logo as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED LOGO): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Hidden) > 0) {
		if (in_array('Hidden',$config_sections)) {
		foreach($Hidden as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Hidden): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($SEO) > 0) {
		if (in_array('SEO',$config_sections)) {
		foreach($SEO as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED SEO): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($AntiSpam) > 0) {
		if (in_array('AntiSpam',$config_sections)) {
		foreach($AntiSpam as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED AntiSpam): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Submit) > 0) {
		if (in_array('Submit',$config_sections)) {
		foreach($Submit as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Submit): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Story) > 0) {
		if (in_array('Story',$config_sections)) {
		foreach($Story as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Story): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Comments) > 0) {
		if (in_array('Comments',$config_sections)) {
		foreach($Comments as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Comments): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Voting) > 0) {
		if (in_array('Voting',$config_sections)) {
		foreach($Voting as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			echo "<br />";
		}
		}
	}
	if (sizeof($Tags) > 0) {
		if (in_array('Tags',$config_sections)) {
		foreach($Tags as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Tags): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Anonymous) > 0) {
		if (in_array('Anonymous',$config_sections)) {
		foreach($Anonymous as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Anonymous): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($OutGoing) > 0) {
		if (in_array('OutGoing',$config_sections)) {
		foreach($OutGoing as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED OutGoing): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Groups) > 0) {
		if (in_array('Groups',$config_sections)) {
		foreach($Groups as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Groups): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Avatars) > 0) {
		if (in_array('Avatars',$config_sections)) {
		foreach($Avatars as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Avatars): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Live) > 0) {
		if (in_array('Live',$config_sections)) {
		foreach($Live as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Live): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Template) > 0) {
		if (in_array('Template',$config_sections)) {
		foreach($Template as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Template): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($Misc) > 0) {
		if (in_array('Misc',$config_sections)) {
		foreach($Misc as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED Misc): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	
	if (sizeof($other) > 0) {
		if (in_array('other',$config_sections)) {
		foreach($other as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (UPDATED other): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}else{
			foreach($other as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("INSERT INTO `temp_config` set `var_page` = '".$var_page."', `var_name` = '".$var_name."', `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."';");
			printf("Affected rows (UPDATED other): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}
	if (sizeof($XmlSitemaps) > 0) {
		if (in_array('XmlSitemaps',$config_sections)) {
		foreach($XmlSitemaps as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("UPDATE `temp_config` set `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."' WHERE `var_name` = '".$var_name."';");
			printf("Affected rows (INSERT XmlSitemaps): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}else{
			foreach($XmlSitemaps as $item) {
			$var_page = $handle->real_escape_string($item['var_page']);
			$var_name = $handle->real_escape_string($item['var_name']);
			$var_value = $handle->real_escape_string($item['var_value']);
			$var_defaultvalue = $handle->real_escape_string($item['var_defaultvalue']);
			$var_optiontext = $handle->real_escape_string($item['var_optiontext']);
			$var_title = $handle->real_escape_string($item['var_title']);
			$var_desc = $handle->real_escape_string($item['var_desc']);
			$var_method = $handle->real_escape_string($item['var_method']);
			$var_enclosein = $handle->real_escape_string($item['var_enclosein']);
			
			$sql_config_insert = $handle->query("INSERT INTO `temp_config` set `var_page` = '".$var_page."', `var_name` = '".$var_name."', `var_value` = '".$var_value."', `var_defaultvalue` = '".$var_defaultvalue."', `var_optiontext` = '".$var_optiontext."', `var_title` = '".$var_title."', `var_desc` = '".$var_desc."', `var_method` = '".$var_method."', `var_enclosein` = '".$var_enclosein."';");
			printf("Affected rows (INSERT XmlSitemaps): %d\n", $handle->affected_rows);
			echo "<br />";
		}
		}
	}

$handle->query("TRUNCATE  TABLE `" . table_prefix."config`;");
	$sql = "INSERT INTO `" . table_prefix."config` (`var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein`) select `var_page`, `var_name`, `var_value`, `var_defaultvalue`, `var_optiontext`, `var_title`, `var_desc`, `var_method`, `var_enclosein` from `temp_config`;";
	$sql_temp_config = $handle->query($sql);
	printf("Affected rows (INSERT FROM TEMP): %d\n", $handle->affected_rows);
	
$handle->query("DROP TEMPORARY TABLE `temp_config`;");
?>