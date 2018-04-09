<?php
session_start();
include_once('./languages/lang_english.php');
//include('db-mysqli.php');
$plikli = array();
$kliqqi = array();
$modules = array();
$adcopy = array();
$karma = array();
$upload = array();
$links = array();
$misc = array();

	$sql = "select * from `" . table_prefix."misc_data`";
	$sql_misc_data = $handle->query($sql);
	if ($sql_misc_data) {
		$row_cnt = $sql_misc_data->num_rows;
		if ($row_cnt) {
			$count = 0;
			while ($misc_data_rows = $sql_misc_data->fetch_assoc()) {
				if (strpos($misc_data_rows['name'], 'plikli_') !== false) {
					$plikli[] = $misc_data_rows;
				}elseif (strpos($misc_data_rows['name'], 'kliqqi_') !== false) {
					$kliqqi[] = $misc_data_rows;
				}elseif (strpos($misc_data_rows['name'], 'adcopy_') !== false || strpos($misc_data_rows['name'], 'captcha_') !== false) {
					$adcopy[] = $misc_data_rows;
				}elseif (strpos($misc_data_rows['name'], 'modules_') !== false) {
					$modules[] = $misc_data_rows;
				}elseif (strpos($misc_data_rows['name'], 'karma_') !== false) {
					$karma[] = $misc_data_rows;
				}elseif (strpos($misc_data_rows['name'], 'upload_') !== false) {
					$upload[] = $misc_data_rows;
				}elseif (strpos($misc_data_rows['name'], 'links_') !== false) {
					$links[] = $misc_data_rows;
				}else{
					$misc[] = $misc_data_rows;
				}
			}
			echo "$count<br /><br />";
		}
	}
	$maketemp = "
	CREATE TEMPORARY TABLE `temp_misc_data` (
	`order` int(5) not null default '10',
	`name` varchar(20) NOT NULL,
	`data` text NOT NULL,
	PRIMARY KEY (`name`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;"; 

	$handle->query($maketemp);
	
	//inserting into the temp table
	if (sizeof($plikli) > 0) {
		foreach($plikli as $item) {
			$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (1,'".$item['name']."', '".$item['data']."');");
			printf("Affected rows (INSERT PLIKLI): %d\n", $handle->affected_rows);
			echo "<br />";
		}
	}
	foreach($adcopy as $item) {
		$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (2,'".$item['name']."', '".$item['data']."');");
		printf("Affected rows (INSERT ADCOPY): %d\n", $handle->affected_rows);
		echo "<br />";
	}
	foreach($modules as $item) {
		$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (3,'".$item['name']."', '".$item['data']."');");
		printf("Affected rows (INSERT MODULES): %d\n", $handle->affected_rows);
		echo "<br />";
	}
	foreach($karma as $item) {
		$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (4,'".$item['name']."', '".$item['data']."');");
		printf("Affected rows (INSERT KARMA): %d\n", $handle->affected_rows);
		echo "<br />";
	}
	if (sizeof($links) > 0) {
		foreach($links as $item) {
			$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (5,'".$item['name']."', '".$item['data']."');");
			printf("Affected rows (INSERT LINKS): %d\n", $handle->affected_rows);
			echo "<br />";
		}
	}
	if (sizeof($upload) > 0) {
		foreach($upload as $item) {
			$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (6,'".$item['name']."', '".$item['data']."');");
			printf("Affected rows (INSERT UPLOAD): %d\n", $handle->affected_rows);
			echo "<br />";
		}
	}
	foreach($misc as $item) {
		$sql_misc_data_insert = $handle->query("insert into `temp_misc_data` (`order`, `name`, `data`) VALUES (10,'".$item['name']."', '".$item['data']."');");
		printf("Affected rows (INSERT MISC): %d\n", $handle->affected_rows);
		echo "<br />";
	}

	//re-inserting in the misc_data table
	$handle->query("TRUNCATE  TABLE `" . table_prefix."misc_data`;");
	$sql = "INSERT INTO `" . table_prefix."misc_data` (`name`, `data`) select `name`, `data` from `temp_misc_data` where `data` != 'p' AND `data` != '4' order by `order` ASC;";
	$sql_temp_misc_data = $handle->query($sql);
	printf("Affected rows (INSERT FROM TEMP): %d\n", $handle->affected_rows);
	echo "<br />";
	$handle->query("DROP TEMPORARY TABLE `temp_misc_data`;");
	echo '</ul></fieldset><br />';
?>