<?php
if (!$step) {
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
} else {
	$language = 'english';
	include_once('./languages/lang_english.php');
}

$file=dirname(__FILE__) . '/../libs/dbconnect.php';

if(!isset($dbuser)){
  $dbuser = $_POST['dbuser'];
  $dbpass = $_POST['dbpass'];
  $dbname = $_POST['dbname'];
  $dbhost = $_POST['dbhost'];
}

if($conn = @mysqli_connect($dbhost,$dbuser,$dbpass)) {
	
	@$_SESSION['checked_step'] = 3;
	
	$output.= "<div style=\"direction:$site_direction\"><fieldset><p>" . $lang['ConnectionEstab'] . "</p>\n";
	if(mysqli_select_db($conn, $dbname)) {
	$output.= "<p><strong>" . $lang['FoundDb'] . "</strong></p>\n";
		if($handle = fopen($file, 'w')) {
			$str  = "<?php\n";
			$str .= "define(\"EZSQL_DB_USER\", '".$dbuser."');"."\n";
			$str .= "define(\"EZSQL_DB_PASSWORD\", '".$dbpass."');"."\n";
			$str .= "define(\"EZSQL_DB_NAME\", '".$dbname."');"."\n";
			$str .= "define(\"EZSQL_DB_HOST\", '".$dbhost."');"."\n";
			$str .= "if (!function_exists('gettext')) {"."\n";
			$str .= '	function _($s) {return $s;}'."\n";
			$str .= '}'."\n";
			$str .= "?>";

			if(fwrite($handle, $str)) {
				$output.= "<p>" . $lang['dbconnect'] . "</p>\n";
				fclose($handle);
			} 
			else {
				$_SESSION['checked_step'] = 0;
				$errors[] = $lang['Error2-1'];
			}
		} 
		else { 
			$_SESSION['checked_step'] = 0;
			$errors[] = $lang['Error2-2'];
		}
	}
	else {	
		$_SESSION['checked_step'] = 0; 
		$errors[] = $lang['Error2-3'];  
	}
}
else { 
	$_SESSION['checked_step'] = 0;
	$errors[] = $lang['Error2-4']; 
}

if($check_errors !== false){
  if (!$errors) {
  	$output.='<div class="instructions"><p>' . $lang['NoErrors'] . '</p>
  	<form id="form2" name="form2" method="post">
  	  <input type="hidden" name="dbuser" value="'.addslashes(strip_tags($_POST['dbuser'])).'" />
  	  <input type="hidden" name="dbpass" value="'.addslashes(strip_tags($_POST['dbpass'])).'" />
  	  <input type="hidden" name="dbname" value="'.addslashes(strip_tags($_POST['dbname'])).'" />
  	  <input type="hidden" name="dbhost" value="'.addslashes(strip_tags($_POST['dbhost'])).'" />
  	  <input type="hidden" name="tableprefix" value="'.addslashes(strip_tags($_POST['tableprefix'])).'" />
	  <input type="hidden" name="language" value="' . addslashes(strip_tags($_REQUEST['language'])) . '">
  	  <input type="hidden" name="step" value="4">
  	  <input type="submit" class="btn btn-primary" name="Submit" value="' . $lang['Next'] . '" />
  	  </form></div>';
  }
  else {
    $output=DisplayErrors($errors);
    $output.='<div class="instructions"><form id="thisform">
    <a class="btn btn-primary" href="install.php?step=2&language='.$language.'">' . $lang['GoBack'] . '</a>
    </form></div></fieldset>';
  }
  echo $output;echo "</div>";
} else {
  header("Location: $url_install3"); 
}
?>