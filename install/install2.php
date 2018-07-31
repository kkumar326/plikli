<?php
if (!$step) { header('Location: ./install.php'); die(); }
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

$file='../config.php';
if (!file_exists($file)) { $errors[]="$file " . $lang['NotFound'] ; }
elseif (filesize($file) <= 0) { $errors[]="$file " . $lang['ZeroBytes'] ; }

$file='../settings.php';
if (!file_exists($file)) { $errors[]="$file " . $lang['SettingsNotFound'] ; }
elseif (filesize($file) <= 0) { $errors[]="$file " . $lang['ZeroBytes'] ; }
elseif (!is_writable($file)) { $errors[]="$file " . $lang['NotEditable'] ; }

$file='../libs/dbconnect.php';
if (!file_exists($file)) { $errors[]="$file " . $lang['DbconnectNotFound'] ; }
elseif (!is_writable($file)) { $errors[]="$file " . $lang['NotEditable'] ; }

$file='../cache';
if (!file_exists($file)) { $errors[]="$file " . $lang['CacheNotFound'] ; }
elseif (!is_writable($file)) { $errors[]="$file " . $lang['NotEditable'] ; }

$language = addslashes(strip_tags($_REQUEST['language']));
$file="../languages/lang_$language.conf";
if (!file_exists($file)) { 
//$errors[]="$file " . $lang['LangNotFound'] ; 
rename("../languages/lang_$language.conf.default", "../languages/lang_$language.conf");
}
elseif (!is_writable($file)) { $errors[]="$file " . $lang['NotEditable'] ; }
echo '<style type="text/css">
body{direction:'.$site_direction.';}
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
padding: 15px;
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
form{padding:15px}
</style>';
if (!$errors) {

$output='
	<fieldset>
<form class="form-horizontal" id="form1" name="form1" action="install.php" method="post">
	
		<p>' . $lang['EnterMySQL'] . '</p>
		
		<div class="control-group">
			<label for="input01" class="control-label">' . $lang['DatabaseName'] . '</label>
			<div class="controls">
				<input class="form-control" name="dbname" type="text" value="" />
			</div>
		</div>

		<div class="control-group">
			<label for="input01" class="control-label">' . $lang['DatabaseUsername'] . '</label>
			<div class="controls">
				<input class="form-control" name="dbuser" type="text" value="" />
			</div>
		</div>
		  
		<div class="control-group">
			<label for="input01" class="control-label">' . $lang['DatabasePassword'] . '</label>
			<div class="controls">
				<input class="form-control" name="dbpass" type="password" value="" />
			</div>
		</div>
		
		<div class="control-group">
			<label for="input01" class="control-label">' . $lang['DatabaseServer'] . '</label>
			<div class="controls">
				<input class="form-control" name="dbhost" type="text" value="localhost" />
			</div>
		</div>

		<div class="control-group">
			<label for="input01" class="control-label">' . $lang['TablePrefix'] . '</label>
			<div class="controls">
				<input class="form-control" name="tableprefix" type="text" value="plikli_" />
				<p class="help-block">' . $lang['PrefixExample'] . '</p>
			</div>
		</div>
		
		<div class="form-actions">
			<input type="submit" class="btn btn-primary" name="Submit" value="' . $lang['CheckSettings'] . '" />
			<button class="btn btn-default" onclick="history.go(-1)">Back</button>
		</div>
		
		<input type="hidden" name="language" value="' . addslashes(strip_tags($_REQUEST['language'])) . '">
		<input type="hidden" name="step" value="3">
	
</form>
</fieldset>
';


}
else { 
	$output=DisplayErrors($errors);
	$output.='<div class="alert">' . $lang['Errors'] . '</div>';
}

echo $output;

?>