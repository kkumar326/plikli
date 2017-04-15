<?php
session_start();
$language = addslashes(strip_tags($_REQUEST['language']));
include ('header.php');

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
.alert-success {
background-color: ##3c763d;
border: 1px solid #fff;
color: ##3c763d;
margin: 0 10px 0 10px;
padding:5px;
}
li{marging-left:30px;}
a:link, a:hover, a:visited, a:active{color:#000000}
.btn-primary, btn {margin-left:10px}
</style>';
/*if($language == 'arabic'){
	include_once('./languages/lang_arabic.php');
} elseif($language == 'catalan'){
	include_once('./languages/lang_catalan.php');
} elseif($language == 'chinese_simplified'){
	include_once('./languages/lang_chinese_simplified.php');
} elseif($language == 'french'){
	include_once('./languages/lang_french.php');
} elseif($language == 'german'){
	include_once('./languages/lang_german.php');
} elseif($language == 'italian'){
	include_once('./languages/lang_italian.php');
} elseif($language == 'russian'){
	include_once('./languages/lang_russian.php');
} elseif($language == 'thai'){
	include_once('./languages/lang_thai.php');
} else {
	include_once('./languages/lang_english.php');
}*/
include('../config.php');
$include='functions.php'; if (file_exists($include)) { include_once($include); }
$tbl_prefix = $_SESSION['table_prefix'];
// Get Pligg CMS Version as $old_version
	$tableexists = checkfortable($tbl_prefix . 'misc_data');
	if ($tableexists) {
		$sql = "SELECT * FROM " . $tbl_prefix . "misc_data WHERE name like '%_version'";
		$pligg_version = $db->get_results($sql);
		foreach($pligg_version as $plv) {
			if ($plv->name == 'pligg_version' || $plv->name == 'kliqqi_version') {
			$old_version = str_replace('.', '' , $plv->data);
			$_SESSION['cms_name'] = $plv->name;
			if ($old_version == '200') {
				$sql = "SELECT `var_defaultvalue` FROM " . $tbl_prefix . "config WHERE `var_name` = '\$MAIN_SPAM_RULESET'";
				$ruleSet = $db->get_var($sql);
				$sql = "SELECT `var_defaultvalue` FROM " . $tbl_prefix . "config WHERE `var_name` = '\$FRIENDLY_DOMAINS'";
				$friendly_domain = $db->get_var($sql);
				if ($ruleSet == 'antispam.log' && $friendly_domain == '') {
					echo '<fieldset><legend>ATTENTION!</legend><div class="alert-success">Confirmed version 2.0.0rc1</div><br /></fieldset>';
					$_SESSION['old_version'] = $old_version . $_SESSION['version_name'];
				}elseif ($ruleSet == 'logs/antispam.log' && $friendly_domain == 'logs/domain-whitelist.log') {
					echo '<fieldset><legend>ATTENTION!</legend><div class="alert-danger">The ' . $plv->name . ' version you are using is ' . $old_version . ' and not "2.0.0rc1"</div><br /></fieldset>';
			$_SESSION['old_version'] = $old_version;
				}
			}else{
				echo '<br /><fieldset><legend>ATTENTION!</legend><div>The CMS and version you are using is ' . $plv->name . ' ' .$plv->data.'</div><br /></fieldset>';
				$_SESSION['old_version'] = $old_version;
			}

			if ($old_version < '122') {
				echo '<br /><fieldset><legend>ATTENTION!</legend><div class="alert-danger">Sorry we can only upgrade from pligg version 1.2.2 and up!</div><br /></fieldset>';
				die();
			}elseif ($_SESSION['cms_name'] == 'kliqqi_version' && $old_version < '300') {
				echo '<br /><fieldset><legend>ATTENTION!</legend><div class="alert-danger">Sorry, your CMS is ' . $plv->name . ' ' . $plv->data. ' There is no Kliqqi version '. $old_version . '</div><br /></fieldset>';
				die();
			}elseif ($_SESSION['cms_name'] == 'kliqqi_version' && $old_version == '352') {
				echo '<br /><fieldset><legend>ATTENTION!</legend><div class="alert-danger">Your CMS is ' . $plv->name . ' ' . $plv->data. ' You already have the latest Kliqqi version '. $old_version . '</div><br /></fieldset>';
				die();
				}
			}
		}
	}else{
		echo "We could not find the tables that belong to the table prefix you entered!";
}

if($_GET['language'] == '' && $_GET['step'] == ''){
	$data = file_get_contents('./languages/language_list_upgrade.html');
	if(strpos($data, '<!--Kliqqi Language Select-->') > 0){
		echo $data;
	} else {
			echo '<fieldset><legend>ATTENTION!</legend><div class="alert-danger">';
		echo 'We are having issues with displaying the local language file list. You can continue by using the default English installer.';
			echo '</div><br />';
		echo '<a class="btn btn-primary" href = "upgrade.php?step=1&language=english">Click to Continue in English</a>';
			echo '</fieldset>';
	}
	//include ('footer.php');
	die();

} else {

	$step = 1;

}
?>
<meta http-equiv="refresh" content="0;url=upgrade.php?step=1">
