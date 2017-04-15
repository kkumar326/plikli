<?php
set_time_limit(0);
header('Content-type: text/html; charset=UTF-8');

if ($_GET['language'])
    $language = addslashes(strip_tags($_GET['language']));
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
} else {
	$language = 'english';
	include_once('./languages/lang_english.php');
}

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Robots" content="none" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link href="../templates/admin/css/bootstrap.no-icons.min.css" rel="stylesheet">
	<link href="../templates/admin/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../templates/admin/css/style.css" media="screen" />
	<style type="text/css">
	body {
		padding-top: 55px;
		background-color: #ffffff;
		background-image: url(../templates/admin/img/grid-18px-masked.png);
		background-repeat: repeat-x;
		background-position: 0 46px;
	}
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
li{marging-left:30px;}
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
.jumbotron{background-color:#ed143d}
	</style>
	
	<title>Kliqqi CMS <?php $lang['installer'] ?></title>
		
</head>
<body>
<?php
include("menu.php");
?>

<div class="container">
	<section id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<script>
				function Set_Cookie( name, value, expires, path, domain, secure )
				{
				var today = new Date();
				today.setTime( today.getTime() );

				if ( expires )
					expires = expires * 1000 * 60 * 60 * 24;
				var expires_date = new Date( today.getTime() + (expires) );

				document.cookie = name + "=" +escape( value ) +
				( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
				( ( path )    ? ";path=" + path : "" ) +
				( ( domain )  ? ";domain=" + domain : "" ) +
				( ( secure )  ? ";secure" : "" );
				}
				</script>
				