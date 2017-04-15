<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
$page = 'upgrade';
define("mnmpath", dirname(__FILE__).'/../');
define("mnminclude", dirname(__FILE__).'/../libs/');
define("mnmmodules", dirname(__FILE__).'/../modules/');
include_once '../settings.php';
include ('header.php');
$tbl_prefix = '';
$sitetitle = '';
/* Redwine: Applies only to kliqqi 3.5.0. We want to find out if they copied 3.5.2 files and replaced the 3.5.0 ones or not. Because if they did, then we notify them that because they did so, only the kliqqi version was updated in the misc_data table and n further actions is required. However, if they are upgrading from a different directory, then we have to detect certain settings to notify them that they must copy over some files from the old directory (UPLOAD module attachements) and, for instance, rename a certain language file if they have Allow users to change language set to 1, etc. */

$parse = parse_url($my_base_url.$_SERVER['REQUEST_URI']);
$path_array = explode("/", $parse['path']);
$the_upgrade_directory = $path_array[1];
$_SESSION['upgrade_dir'] =  $the_upgrade_directory;
$_SESSION['initial_dir'] =  str_replace("/", "",$my_kliqqi_base);
/* END */
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
margin: 0 auto;
width:80%;
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
margin-left: 10px;
}
.iconalign {vertical-align: bottom;}
.alert-danger, .alert-error {
background-color: #FF0000;
border: 1px solid #fff;
color: #ffffff;
margin: 0 10px 0 10px;
padding:5px;
font-size: 12px;
}
.alert-success {
background-color: ##3c763d;
border: 1px solid #fff;
color: #ffffff;
margin: 0 10px 0 10px;
padding:5px;
}
li{marging-left:30px;}
a:link, a:hover, a:visited, a:active{color:#000000}
.btn-primary, btn {margin-left:10px}
input[type=text] {
    padding:5px; 
    border:2px solid #ccc; 
    -webkit-border-radius: 5px;
    border-radius: 5px;
	color:#000000;
}

input[type=text]:focus {
    border-color:#333;
}

input[type=submit] {
    padding:5px 15px; 
    background:#2D6CE0; 
    border:2px solid #fff;
    cursor:pointer;
    -webkit-border-radius: 5px;
    border-radius: 5px;
	color: #ffffff !important;
}
label{font-weight:normal;font-size: 12px;}
</style>';

$thetemp = 'bootstrap';
if (isset($_POST['prefix'])) {
	$tbl_prefix = $_POST['prefix'];
	$_SESSION['table_prefix'] = $tbl_prefix;
	$sql = "UPDATE `" . $tbl_prefix."config` SET `var_value` = 'bootstrap', `var_defaultvalue` = 'bootstrap', `var_optiontext` = 'Text' where `var_name` = '\$thetemp';";
	$sql_thetemp = mysql_query($sql);
}else{
	$tbl_prefix = '';
}
if (isset($_POST['sitetitle'])) {
	$sitetitle = $_POST['sitetitle'];
	$_SESSION['sitetitle'] = $sitetitle;
}else{
	$sitetitle = '';
}
if (isset($_POST['p2rc1'])) {
	$version_name = $_POST['p2rc1'];
	$_SESSION['version_name'] = $version_name;
}else{
	$version_name = '';
}
// Set $step
if (isset($_REQUEST['step'])) { $step=addslashes(strip_tags($_REQUEST['step'])); }

//check for no steps, start on step1
if ((!isset($step)) || ($step == "")) { $step = 0; }
include_once('./languages/lang_english.php');
if ($step == 0 && $tbl_prefix == '') {
	echo '<fieldset><legend>ATTENTION!</legend>
	<div  class="alert-danger">' . $lang['UpgradeHome'] . '</div>
	<div  class="alert-danger">Before you start, make sure you have copied the following files from your Pligg site that you want to upgrade:
		<ul>
			<li>/libs/dbconnect.php<br />COPY it to the libs folder of the package you just downloaded and unzipped and are using now to upgrade.</li>
			<li>/settings.php<br />COPY to the root folder of the package you just downloaded and unzipped and are using now to upgrade.</li>
		</ul>
	</div><br />
	<form id="tbl_prefix" name="tbl_prefix" action="upgrade.php" method="post"><label for="prefix">Enter the table prefix of your current Pligg OR Kliqqi tables.</label><br /><input type="text" id="prefix" name="prefix"><br /><br /><label for="p2rc1">Because both Pligg 2.0.0rc1 and 2.0.0 have the same verion "2.0.0", if you are using Pligg version 2.0.0 rc1, enter rc1 in the box; otherwise, leave it empty</label><br /><input type="text" id="p2rc1" name="p2rc1"><br /><br /><label for="sitetitle">Enter the Visual Name you used for your pligg site (you can find it in the language file of your site under the general section PLIGG_Visual_Name)</label><br /><input type="text" id="sitetitle" name="sitetitle"><br /><br /><input type="submit" value="Submit" class="btn btn-primary"></form></div></fieldset>'; 
	die();
}

// If they haven't selected a step yet, start them off at the language selection screen
if ($step == 0) { 
	include('upgrade_language.php');
}

$include='header.php'; 

// Sanitize and set $language
if ($_GET['language'])
    $language = addslashes(strip_tags($_GET['language']));
	
// Set connect $language to install language file


if ($step == 1) { 
	include('upgrade1.php');
}



$include='footer.php'; if (file_exists($include)) { include_once($include); }
?>