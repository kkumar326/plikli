<?php
ob_start();
include_once('../internal/Smarty.class.php');
$main_smarty = new Smarty;

include('../config.php');
include(mnminclude.'html1.php');
include(mnminclude.'smartyvariables.php');

check_referrer();
force_authentication();
$canIhaveAccess = 0;
$canIhaveAccess = $canIhaveAccess + checklevel('admin');
if($canIhaveAccess == 0)
{
	header("Location: " . getmyurl('admin_login', $_SERVER['REQUEST_URI']));
	die();
}

// pagename
define('pagename', 'admin_modules'); 
$main_smarty->assign('pagename', pagename);

// read the mysql database to get the plikli version
/* Redwine: plikli version query removed and added to /libs/smartyvriables.php */

// breadcrumbs and page title
$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel');
$navwhere['link1'] = getmyurl('admin', '');
$navwhere['text2'] = $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel_6');
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', " / " . $main_smarty->get_config_vars('PLIKLI_Visual_Header_AdminPanel_6'));
 
$main_smarty->assign('module_management_name', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Module_Management'));
$main_smarty->assign('module_management_desc', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Module_Description'));

if (!isset($_GET["status"])) {
	$status = "installed";
}else{
	$status = $_GET["status"];
}
$main_smarty->assign('status', $status);

if($status=="uninstalled") {
	$foundfolders = $foundfolders_unins; // we get it from smartyvariables.php
	
	$token=isset($_GET['token']) ? $_GET['token']: '';
	if(!empty($token)) {
		$updatekey=$_GET['updkey'];
		$updkey_array=@explode(",",$updatekey);
		
		// now we filter the $foundfolders array to only keep the ones that are in the $updatekey.
		if(isset($foundfolders) && is_array($foundfolders))
		{
			foreach($foundfolders as $key => $value)
			{
				if (!in_array($value,$updkey_array))
				{
					unset($foundfolders[$key]);
				}
			}
		}
	}
	
	$module_info_data=array();
	if(isset($foundfolders) && is_array($foundfolders)) {
		asort($foundfolders);
		$i=0;
		$update_key = array();
		$updatecount=0;
		foreach($foundfolders as $key => $value) {
			$text = array();
			if($module_info = include_module_settings($value)) {
				$text[] = $module_info['desc'];
				$module_info_data[$i]['version'] = $module_info['version'];
				 
				if(isset($module_info['update_url'])) {
					if (is_array($versionupdate)) {
						foreach($versionupdate as $mod) {
							if (in_array($value, $mod)) {
								if($mod[1]>$module_info['version']) {
									$update_key[$updatecount] = array($value,$mod[1]);
							$updatecount++;
									//we want to pass on the latest version to smarty in the $module_info array
									$module_info_data[$i]['version'] = $mod[1];
								}
						}
					}
				}
				
				$module_info_data[$i]['value'] = $value;
				$module_info_data[$i]['name'] = $module_info['name'];
				
					if(file_exists('../modules/' . $value . '/' . $value . '_readme.htm')) {
					$module_info_data[$i]['dname']="<a href='?action=readme&module=". $value ."'>".$module_info['name']."</a>";
				} else {
					$module_info_data[$i]['dname']="".$module_info['name']."";
				}
				
					if(is_array($text)) {
					$module_info_data[$i]['desc']=@implode("",$text);
				} else {
					$module_info_data[$i]['desc']=$module_info['desc'];
				}
					if(isset($module_info['requires'])) {
					$requires = $module_info['requires'];
						if(is_array($requires)) {
						$req_data='<ul class="unstyled">';
							foreach($requires as $requirement) {
							$req_data.='<li style="line-height:22px;">';
								if(check_for_enabled_module($requirement[0], $requirement[1])) {
								$req_data.= '<span class="label label-success" style="padding:3px 5px;"><i class="fa fa-white fa-check"></i> ';
							} else {
								$req_data.= '<span class="label label-danger" style="padding:3px 5px;"><i class="fa fa-white fa-times"></i> ';
							}
								if ($requirement[3]) {
								$req_data.= '<a href="' . $requirement[3] . '" style="color:#fff;">';
							}
								if ($requirement[2]) {
								$req_data.= $requirement[2];
							} else {
								$req_data.= $requirement[0];
							}
								if ($requirement[3]) {
								$req_data.= '</a>';
							}
							$req_data.= ' ' . $requirement[1] .'</span></li>';
						}
						$req_data.= '</ul>';
					}
					$module_info_data[$i]['requires']=$req_data;	
				} else {
					$module_info_data[$i]['requires']="&nbsp;";
				}
				
					if(isset($module_info['homepage_url'])) {
					$homepage_url = $module_info['homepage_url'];
					$module_info_data[$i]['homepage_url']= " <a class='btn btn-default btn-xs' href='" . $homepage_url . "' target='_blank' rel='noopener noreferrer'>Homepage</a>";
				} else {
					$module_info_data[$i]['homepage_url']="&nbsp;";	
				}
			
				$i++;	
			
			}
		}
	}
		$updatekey = '';
		$modules_name = array();
		if (!empty($update_key)) {
			if (is_array($update_key)) {
	$update_key = array_filter($update_key);
				$update_misc_data=serialize($update_key);
				foreach($update_key as $module_to_update) {
					//$updatekey .= $module_to_update['module'] . ","; 
					$modules_name[] = $module_to_update[0];
				}
				$updatekey = implode(",", $modules_name);
			}
		}else{
			$updatekey = "";
			$update_misc_data = '';
		}
		misc_data_update('modules_update_unins',$update_misc_data);
		$main_smarty->assign('updatekey', $updatekey);
	}
	$main_smarty->assign('no_module_update_require', $updatecount);
	//$expire=time()+60*60*24*60;
    //setcookie("module_update_require_un", $updatecount, $expire);
	//setcookie("module_update_require_un_ex", $updatecount, $expire,"/",$_SERVER["HTTP_HOST"]);
	$main_smarty->assign('module_info', $module_info_data);
	
	$res_for_update=$db->get_var("select var_value from " . table_config . "  where var_name = 'uninstall_module_updates'");
	if(count($res_for_update)<=0){
	 $db->query("INSERT INTO " . table_config . " set var_value =".$updatecount." , var_name = 'uninstall_module_updates'");
	}else{
		/* Redwine: added conditional satement to save a query. */
		if ($res_for_update != $updatecount) {
			$sql = "UPDATE " . table_config . " set var_value =".$updatecount." where `var_name` = 'uninstall_module_updates';";
			//echo $sql;
			$db->query($sql);
		}
	}
	//echo "<pre>";
	//print_r($module_info_data);
	
} elseif($status=='installed') {
	// Installed Modules
	
	$main_smarty->assign('btn_apply_change', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Apply_Changes'));
	$main_smarty->assign('btn_module_remove', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Module_Remove'));

	$token=isset($_GET['token']) ? $_GET['token']: '';
    if($token==1)
	{
		$modules = $res_update_mod; //(we get it from smartyvariables.php) //$db->get_results('SELECT * from ' . table_modules . ' where latest_version>version order by weight asc;');
		
	} else {	
		$modules = $modules; //(we get it from smartyvariables.php) //$db->get_results('SELECT * from ' . table_modules . ' order by weight asc;');
	}
	
	
	
	if($modules) {
		
		$module_info_data=array();
		 $i=0;
		
		foreach($modules as $module) {
					
			if (file_exists(mnmmodules . $module->folder)) {
				
				$module_info_data[$i]['id']=$module->id;
				$module_info_data[$i]['enabled']= $module->enabled;
				$module_info_data[$i]['name']= $module->name;
			   
			    $first_row="<input type=\"hidden\" name=\"enabled[{$module->id}]\" id=\"enabled_{$module->id}\" value=\"{$module->enabled}\">";
				$first_row.= "<input type='checkbox' onclick='document.getElementById(\"enabled_{$module->id}\").value=this.checked ? 1 : 0;' ";
				if($module->enabled) {
					$first_row.= "checked";
				}
				$first_row.= ">";
			   
			    $module_info_data[$i]['first_row']=$first_row;
				$module_info_data[$i]['dname']='<a href="?action=readme&module=' . $module->folder . '">' . $module->name . '</a>';
				
				if($module_info = include_module_settings($module->folder)) {
					$versionupdate = $versionupdate_to_pass_to_installed; //passed on from smartyvariables.php
					if(isset($module_info['update_url'])) {
						//if ($proceed_check_update == 'true') {
							foreach($versionupdate as $mod) {
								if (in_array($module->folder, $mod)) {	
									$module_info_data[$i]['dname']='<a href="?action=readme&module=' . $module->folder . '">' . $mod[0] . '</a>';								
									if ( $mod[1] != $module->latest_version) {
										$versionupdates = $mod[1];
										$db->query($sql="UPDATE `". table_modules . "` SET `latest_version`='$versionupdates' WHERE `id`='".$module->id."'");
									} elseif ($versionupdates=="Invalid Product ID" ) {
						 $db->query("UPDATE `". table_modules . "` SET `latest_version`=0 WHERE `id`='".$module->id."'");
									} else {
										$versionupdates = 'N/A';
									}
								}
							}
						//}else{
							if ($module->latest_version > $module->version) {
								$versionupdates = $module->latest_version;
							}
						//}
				  }else{
					  $db->query("UPDATE `". table_modules . "` SET `latest_version`=0 WHERE `id`='".$module->id."'"); 
				  }
				}
				
				//echo "<br/>".$module->name."update url:".$module_info['update_url']."vn:".$versionupdate;
				// Get module description
				if($module_info['desc']!="")
				{
					$module_info_data[$i]['desc']= "".$module_info['desc'];
				}
				
				// Get module requirements
				if(isset($module_info['requires']))
				{
					$requires = $module_info['requires'];
					if(is_array($requires))
					{
						$require_data='<ul class="unstyled">';
						foreach($requires as $requirement)
						{
							$require_data.='<li style="line-height:22px;">';
							if(check_for_enabled_module($requirement[0], $requirement[1]))
							{
								$require_data.= '<span class="label label-success" style="padding:3px 5px;"><i class="fa fa-white fa-check"></i> ';
							} else {
								$require_data.= '<span class="label label-danger" style="padding:3px 5px;"><i class="fa fa-white fa-times"></i> ';
							}
							if (isset($requirement[3]))
							{
								$require_data.= '<a href="' . $requirement[3] . '" style="color:#fff;">';
							}
							if (isset($requirement[2]))
							{
								$require_data.= $requirement[2];
							} else {
								$require_data.= $requirement[0];
							}
							if (isset($requirement[3]))
							{
								$require_data.= '</a>';
							}
							$require_data.= ' ' . $requirement[1] .'</span></li>';
						}
						$require_data.= '</ul>';
					} else {
						$require_data="&nbsp;";
					}
				} else {
					$require_data="&nbsp;";
				}

				$module_info_data[$i]['requires']=$require_data;
				$homepage_url = '';
				if(isset($module_info['homepage_url']))
				{
					$homepage_url = $module_info['homepage_url'];
					$module_info_data[$i]['homepage_url']= '<a class="btn btn-default btn-xs" href="' . $homepage_url . '">Homepage</a>';
				} else {
					$module_info_data[$i]['homepage_url']="&nbsp;" ;
				}

				if ($versionupdates > 0 && $versionupdates>$module->version)
				{
					// Update available and is greater than installed version
					if ($module_info_data[$i]['homepage_url'] == "&nbsp;"){
						$module_info_data[$i]['version']= "<a class='btn btn-default disabled btn-xs' href='" . $homepage_url . "' title='No Upgrade URL Provided'>Download Update</a></td>";
					} else {
						$module_info_data[$i]['version']= "<a class='btn btn-success btn-xs' href='" . $homepage_url . "' title='Remove module first,&#13;then download to Upgrade to $versionupdates&#13;and then reinstall it'>Download Update</a></td>";
					}
				} else {
					// Version numbers may be funny. Needs further testing.
					if ($module_info_data[$i]['homepage_url'] == "&nbsp;"){
						$module_info_data[$i]['version']= "<a class='btn btn-default disabled btn-xs' href='" . $homepage_url . "' title='No Upgrade URL Provided'>Download Update</a></td>";
					} else {
						$module_info_data[$i]['version']= "<a class='btn btn-success btn-xs' href='" . $homepage_url . "' title='Remove module first,&#13;then download to Upgrade to $versionupdates&#13;and then reinstall it'>Download Update</a></td>";
					}		
				}

				if(isset($module_info['settings_url']))
				{
					$settings_url = $module_info['settings_url'];
					$module_info_data[$i]['settings_url']= '<a class="btn btn-default btn-xs" href="' . $settings_url . '">Settings</a>';
				} else {
					$module_info_data[$i]['settings_url']="&nbsp;" ;
				}
				
			 $i++;	
			}
		}
	}

	$update_require_modules = $db->get_results('SELECT * from ' . table_modules . ' where latest_version>version order by weight asc;');
	//print_r($update_require_modules);
	$num_update_required=0;
	if(count($update_require_modules)){
		
		foreach($update_require_modules as $module) {
				if (file_exists(mnmmodules . $module->folder))
				$num_update_required++;
				
		}
	}

	$main_smarty->assign('no_module_update_require', $num_update_required);
	$main_smarty->assign('module_info', $module_info_data);	
}

//print_r($module_info_data);
$action = '';
if (isset($_GET['action'])) {
$action=$_GET['action'];
}

if($action == 'readme'){
	$main_smarty->assign('action', 'readme');
	$module = sanitize($_REQUEST['module'],3);
	$main_smarty->assign('module_management_name', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Module_Readme'));
	$main_smarty->assign('module_readme_return', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Module_Return'));
	
	
	if(file_exists('../modules/' . $module . '/' . $module . '_readme.htm')){
		$main_smarty->assign('found','yes');
		$myFile = '../modules/' . $module . '/' . $module . '_readme.htm';
        $fh = fopen($myFile, 'r');
		$theData = fread($fh,filesize($myFile));
		fclose($fh);
		$main_smarty->assign('readme_content', $theData);
	} else {
		$main_smarty->assign('found','no');
		$main_smarty->assign('not_found', $main_smarty->get_config_vars('PLIKLI_Visual_AdminPanel_Module_Readme_Not_Found'));
		
	}
}else
$main_smarty->assign('action', '');

if($canIhaveAccess == 1){
	if (isset($_POST["enabled"]))
	{
		foreach($_POST["enabled"] as $id => $value) 
		{
			$sql = "UPDATE " . table_modules . " set enabled =  ".$db->escape(sanitize($value,3)). " where id=". $db->escape(sanitize($id,3));
			$db->query($sql);
		}
		header("Location: admin_modules.php");
		exit;
	}

	if(isset($_GET['action']) && $_GET['action'] == 'disable')
	{
		$module = $db->escape(sanitize($_REQUEST['module'],3));
		$sql = "UPDATE " . table_modules . " set enabled = 0 where `name` = '" . $module . "';";
		//echo $sql;
		$db->query($sql);

		clear_module_cache();

		header('Location: admin_modules.php');
		die();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'enable')
	{
		$module = $db->escape(sanitize($_REQUEST['module'],3));
		$sql = "UPDATE " . table_modules . " set enabled = 1 where `name` = '" . $module . "';";
		//echo $sql;
		$db->query($sql);

		clear_module_cache();

		header('Location: admin_modules.php');
		die();
	}
	
	$main_smarty->assign('tpl_center', '/admin/modules');
	$output = $main_smarty->fetch('/admin/admin.tpl');		

	if (!function_exists('clear_module_cache'))
	{
		echo "Your template is not compatible with this version of Plikli. Missing the 'clear_modules_cache' function in modules.tpl.";
	} else {
		echo $output;
	}
}

if($action == 'install')
{
	$module = $db->escape(sanitize($_REQUEST['module'],3));
	if($module_info = include_module_settings($module))
	{
		$version = $module_info['version'];
		$name = $module_info['name'];
		$requires = $module_info['requires'];
		check_module_requirements($requires);
		process_db_requirements($module_info);
		if ($module_info['install'])
			@eval($module_info['install']);
	} else {
		die('No install file exists.');
	}
	/* Redwine: changing the deprecated mysql_ extension and optimizing the query. */
	//$res_total_module = mysql_query('SELECT id from ' . table_modules );
	$res_total_module = $db->get_var('SELECT count(id) from ' . table_modules );
	//$total_module=mysql_num_rows($res_total_module);
	$db->query("INSERT IGNORE INTO " . table_modules . " (`name`, `version`, `folder`, `enabled`,`weight`) values ('".$name."', '" . $version . "', '".$module."', 1,'".$res_total_module."');");
	clear_module_cache();
	header('Location: admin_modules.php?status=uninstalled');
}

if($action == 'remove')
{
	 $module = $db->escape(sanitize($_REQUEST['module'],3));
	 $sql = "SELECT * FROM " . table_modules . " WHERE `name` = '" . $module . "';";
	
	$row = $db->get_row($sql);
	if ($module_info = include_module_settings($row->folder)) {
	   if ($module_info['uninstall'])
		@eval($module_info['uninstall'].'();');
	   elseif ($module_info['uninstall2'])
		@eval($module_info['uninstall2']);
	}

	$sql = "Delete from " . table_modules . " where `name` = '" . $module . "';";
	//echo $sql;
	$db->query($sql);
	clear_module_cache();
	header('Location: admin_modules.php');
}

function clear_module_cache ()
{
	global $db;
	if(caching == 1)
	{
		// this is to clear the cache and reload it for settings_from_db.php
		$db->cache_dir = mnmpath.'cache';
		$db->use_disk_cache = true;
		$db->cache_queries = true;
		$db->cache_timeout = 0;
		// if this query is changed, be sure to also change it in modules_init.php
		$modules = $db->get_results('SELECT * from ' . table_modules . ' where enabled=1;');
		$db->cache_queries = false;
	}
}

function safe_file_get_contents($url,$redirect=0)
{
	$parts = parse_url($url);
	$site  = $parts['host'];
	$port  = $parts['port'] ? $parts['port'] : 80;
	$path  = $parts['path'] . ($parts['query'] ? "?".$parts['query'] : "") . ($parts['fragment'] ? "#".$parts['fragment'] : "");
	$timeout = 5;
	$sock = @fsockopen( $site, $port, $errnum, $errstr, $timeout);
	if (!$sock)
	{
		return "Cannot connect to $site:$port: $errstr($errno)";
	} else {
		@socket_set_timeout($sock, $timeout);
		$dump = "GET ".$path." HTTP/1.0\r\n";
		$dump .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)\r\n";
		$dump .= "Host: ".$site."\r\n";
		$dump .= "Connection: close\r\n\r\n";
		$res = "";
		// Send HTTP query
			fputs( $sock, $dump );
		// Read all
		$header = true;
		while( $str = fgets( $sock, 1024 ) ) 
		{
			if ($header)
			{
				if (preg_match("/^Location: ([^\\s]+)\\s*$/",$str,$m) && ++$redirect<10)
				{
					return safe_file_get_contents($m[1],$redirect);
				}
				if ($str == "\r\n")
				{
					$header = false;
				}
			} else {
				$res .= $str;
			}
		}
		fclose( $sock );
	}
	return $res;
}

?>