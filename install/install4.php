<?php
if (!$step) { 
	header('Location: ./install.php'); die(); 
} else if(@$_SESSION['checked_step'] != 3){
	header('Location: ./install.php'); die(); 
}
if ($_POST['language'] == 'arabic') {
	$site_direction = "rtl";
}else{
	$site_direction = "ltr";
}
if ($_POST['language'])
    $language = addslashes(strip_tags($_POST['language']));
if($language == 'arabic'){
	include_once('./languages/lang_arabic.php');
}elseif($language == 'catalan'){
	include_once('./languages/lang_catalan.php');
}elseif($language == 'chinese_simplified'){
	include_once('./languages/lang_chinese_simplified.php');
}elseif($language == 'french'){
	include_once('./languages/lang_french.php');
}elseif($language == 'german'){
	include_once('./languages/lang_german.php');
}elseif($language == 'italian'){
	include_once('./languages/lang_italian.php');
}elseif($language == 'russian'){
	include_once('./languages/lang_russian.php');
}elseif($language == 'thai'){
	include_once('./languages/lang_thai.php');
} elseif($language == 'spanish'){
	include_once('./languages/lang_spanish.php');
} elseif($language == 'italian'){
	include_once('./languages/lang_italian.php');
} elseif($language == 'portuguese'){
	include_once('./languages/lang_portuguese.php');
} elseif($language == 'swedish'){
	include_once('./languages/lang_swedish.php');
} else {
	$language = 'english';
	include_once('./languages/lang_english.php');
}
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
margin-left: 10px;
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
input[type=text] {
    padding:5px; 
    border:2px solid #ccc; 
    -webkit-border-radius: 5px;
    border-radius: 5px;
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

</style>';
echo '<div class="instructions">';
$file='../config.php';
if (!file_exists($file)) { $errors[]="$file " . $lang['NotFound'] ; }
elseif (filesize($file) <= 0) { $errors[]="$file " . $lang['ZeroBytes'] ; }

$file='../settings.php';
if (!file_exists($file)) { $errors[]="$file " . $lang['SettingsNotFound'] ; }
elseif (filesize($file) <= 0) { $errors[]="$file " . $lang['ZeroBytes'] ; }
elseif (!is_writable($file)) { $errors[]="$file " . $lang['NotEditable'] ; }

define("mnminclude", dirname(__FILE__).'/../libs/');
include_once mnminclude.'db.php';
		
if (!$errors) {
	$dbuser = EZSQL_DB_USER;
	$dbpass = EZSQL_DB_PASSWORD;
	$dbname = EZSQL_DB_NAME;
	$dbhost = EZSQL_DB_HOST;

	if($conn = @mysqli_connect($dbhost,$dbuser,$dbpass))
	 {
		$db_selected = mysqli_select_db($conn, $dbname);
		if (!$db_selected) { die ('Error: '.$dbname.' : '.mysqli_error($conn)); }
		define('table_prefix', $_POST['tableprefix']);

		include_once '../libs/define_tables.php';

		//time to create the tables
		echo '<fieldset><legend>' . $lang['CreatingTables'] . '</legend>';
		include_once ('../libs/db.php');
		include_once("installtables.php");
		if (plikli_createtables($conn) == 1) { echo "<li>" . $lang['TablesGood'] . "</li><hr />"; }
		else { $errors[] = $lang['Error3-1']; }
	}
	else { $errors[] = $lang['Error3-2']; }
}

if (!$errors) {
	// refresh / recreate settings
	// this is needed to update it with table_prefix if it has been changed from "plikli_"
	include_once( '../libs/admin_config.php' );
	
	$config = new plikliconfig;
	$config->create_file('../settings.php');

	$gethttphost = $_SERVER["HTTP_HOST"];
	$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://';	
	$port = strpos($gethttphost, ':');
	if ($port !== false){ 
		$httphost = substr($gethttphost, 0, $port);
	}else{
		$httphost = $gethttphost;
	}	
	$standardport = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 443 : 80); 
	$waitTimeoutInSeconds = 1; 
	if($fp = fsockopen($httphost,$standardport,$errCode,$errStr,$waitTimeoutInSeconds)){   
		$my_base_url = $protocol . $httphost;
		$preportnotice = "Standard web server port " . $standardport;
	} else {
		$my_base_url = $protocol . $_SERVER["HTTP_HOST"];
		$preportnotice = "Non standard web server port " . str_replace(":","",substr($gethttphost, $port));
	} 
	fclose($fp);
	echo "<br><strong>" . $preportnotice . " has been detected on your server.</strong> Your Plikli base URL has been set to <strong>" . $my_base_url . "</strong>. This can be changed after install in admin, settings 'Location Installed' or manually by updating both the config table in the database and the settings.php file.";
	
	$my_plikli_base=dirname($_SERVER["PHP_SELF"]); $my_plikli_base=str_replace("/".substr(strrchr($my_plikli_base, '/'), 1),'',$my_plikli_base);

	$sql = "Update " . table_config . " set `var_value` = '" . $my_base_url . "' where `var_name` = '" . '$my_base_url' . "';";
	mysqli_query( $conn, $sql );

	$sql = "Update " . table_config . " set `var_value` = '" . $my_plikli_base . "' where `var_name` = '" . '$my_plikli_base' . "';";
	mysqli_query( $conn, $sql );
	
	// Set the site language to what the user has been using during the installation
	$language = addslashes(strip_tags($_REQUEST['language']));
	$sql = "Update " . table_config . " set `var_value` = '" . $language . "' where `var_name` = '" . '$language' . "';";
	mysqli_query( $conn, $sql );

	$config = new plikliconfig;
	$config->create_file('../settings.php');

	include_once( '../config.php' );
	
	// Remove the cookie setting a template value
	setcookie("template", "", time()-60000,$my_plikli_base,$domain);

	$output='<div class="instructions" style="direction:'.$site_direction.'"><p>' . $lang['EnterAdmin'] . '</p>
	<table>
		<form id="form1" name="form1" action="install.php" method="post" onsubmit="return checkBreachedPassword();">
			<tr>
				<td><label>' . $lang['AdminLogin'] . '</label></td>
				<td><input name="adminlogin" type="text" class="form-control" value="" placeholder="Admin" /></td>
			</tr>
			
			<tr>
				<td><label>' . $lang['AdminPassword'] . '</label></td>
				<td><input id="adminpassword" name="adminpassword" type="password" class="form-control" value="" /><span class="reg_userpasscheckitvalue"></span></td>
			</tr>
			
			<tr>
				<td><label>' . $lang['ConfirmPassword'] . '</label></td>
				<td><input name="adminpassword2" type="password" class="form-control" value="" /></td>
			</tr>
			
			<tr>
				<td><label>' . $lang['AdminEmail'] . '</label></td>
				<td><input name="adminemail" type="text" class="form-control" value="" placeholder="admin@domain.com" /></td>
			</tr>
			
			<tr>
				<td><label>' . $lang['SiteTitleLabel'] . '</label></td>
				<td><input name="sitetitle" type="text" class="form-control" value="" placeholder="My Site" /></td>
			</tr>
			
			<tr>
				<td><label></label></td>
				<td><input type="submit" class="btn btn-primary" name="Submit" value="' . $lang['CreateAdmin'] . '" /></td>
			</tr>
			
			<input type="hidden" name="language" value="' . addslashes(strip_tags($_REQUEST['language'])) . '">
			<input type="hidden" name="step" value="5">
		</form>
    </table>
	</div>
	';
}

	mysqli_query( $conn, $sql );
if (isset($errors)) {
	$output=DisplayErrors($errors);
	$output.='<p>' . $lang['Errors'] . '</p>';
}

if(function_exists("gd_info")) {}
else {
	$config = new plikliconfig;
	$config->var_id = 60;
	$config->var_value = "false";
	$config->store();
	$config->var_id = 69;
	$config->var_value = "false";
	$config->store();
}

echo $output;
echo '</div>';
?>