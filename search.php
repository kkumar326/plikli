<?php

	include_once('internal/Smarty.class.php');
	$main_smarty = new Smarty;
	
	include('config.php');
	include(mnminclude.'html1.php');
	include(mnminclude.'link.php');
	include(mnminclude.'tags.php');
	include(mnminclude.'search.php');
	include(mnminclude.'smartyvariables.php');

//Redwine: FOR SEO URL METHOD 1. creating an array to hold all the sanitezed _GET elements. The we assign it to _GET and _REQUEST 
$sanitezedGET = array();
foreach ($_GET as $key => $value) {
	$sanitezedGET[$key] = sanitize($value, 2);
}
$_GET = $_REQUEST = $sanitezedGET;

//Redwine: sanitize and filter the search GET and then make the seacrh REQUEST equal to it.
$_GET['search'] = htmlentities(sanitize($_GET['search'], 2));
$_GET['search'] = preg_replace('/[^\p{L}\p{N}-_\s\/]/u', ' ', $_GET['search']);
$_REQUEST['search'] = $_GET['search'];

	/*Redwine: function to validate the provided date*/
	function validateDate($date, $format = 'Y-m-d') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	/* Redwine: declaring arrays for each expected form value to check against the submitted values */
	$expected_slink_values = array('1','2','3');
	$expected_status_values = array('all','published','new');
	$expected_scomments_values = array('1','0');
	$expected_stags_values = array('1','0');
	$expected_suser_values = array('1','0');
	

	
	if(isset($_REQUEST['status'])){
		// if "status" is set, filter to that status
		$search->filterToStatus = sanitize($_REQUEST['status'], 3);
	} else {
		// we want to view "all" stories
		$search->filterToStatus = "all";
	}

	//Redwine: now we have to make GET array equal to REQUEST
	$_REQUEST = $_GET;

	/*Redwine: the str_replace was not escaped correctly!*/
	/*********
	Redwine: I added  && strstr($_REQUEST['search'],'/adv/1') because the regular search $_REQUEST['search'] is always equal to the search word and a forward slash (/) at the end, therefore the first condition strstr($_REQUEST['search'],'/') is true and the second one is also true when in SEO URL Method 2. This creates a problem because the data process in this conditional statement should only apply when the advanced search is used and not the regular search. The condition I added will make sure to determine if the regular or advanced search is used.
	*********/
		
	/*****
	Redwine: making sure, when $_REQUEST['search'] contains "/" and $URLMethod == 2, that adv/ is always set to 1
	example: if someone modified the search URL to search/blabla/slink/1/scategory/0/sgroup/3/status/all/stags/1/adv/0/ or search/blabla/slink/1/scategory/0/sgroup/3/status/all/stags/1/adv/<script>alert('123')</script>/
	it will be reset to adv/1/
	*****/
		
	if (strstr($_REQUEST['search'],'/') && $URLMethod == 2 && !strstr($_REQUEST['search'],'/adv/1')) {
		preg_match_all("/([^\/]+)\/([^\/]+)/", $_REQUEST['search'], $p);
		//Redwine: $p matches holds the keys in $p[1] and values in $p[2]
		$search_elements = array_combine($p[1], $p[2]);
		foreach ($search_elements as $key => $value) {
			if ($key == 'adv' && $value != 1) {
				$search_elements['adv'] = 1;
			}
		}
		foreach ($search_elements as $key => $value){
			$joined .= $key . "/" . $value . "/";
		}
		$_GET['search'] = $_REQUEST['search'] = $joined;
	}
		
	if (strstr($_REQUEST['search'],'/') && $URLMethod == 2 && strstr($_REQUEST['search'],'/adv/1')) {
		/************************
		Redwine: in seo url method 2, the search request value is transmitted as a slash delimited string: 
		search/blabla/slink/1/scategory/0/sgroup/3/status/all/stags/1/adv/1/
		we need to convert it to an $_REQUEST ARRAY. Afterwards, we also assign the $_GET array to be equal to $_REQUEST 
		************************/
		preg_match_all("/([^\/]+)\/([^\/]+)/", $_REQUEST['search'], $p);
		//Redwine: $p matches holds the keys in $p[1] and values in $p[2]
		$search_array = array_combine($p[1], $p[2]);
		
		//Redwine: re-assigning the $_GET and $_REQUEST arrays
		$_REQUEST = $_GET = $search_array;
		if (isset($_GET['return'])) $_GET['return'] = addslashes($_GET['return']);
		$main_smarty->assign('get',$_GET);           
	}
	
	//Redwine: became kind of obsolete as LINES 14 and 15 are taking care of all the unwanted characters.
	//To revise later on.
	$_REQUEST['search'] = str_replace('/','',$_REQUEST['search']);
	$_GET['search'] = $_REQUEST['search'];
	
	$main_smarty->assign('get',$_GET);
	/*Redwine: the str_replace was not escaped correctly and if the search term contains a colon, the search result is not accurate. So I replaced the colon with empty!*/
	$_REQUEST['search'] = str_replace(array(':\\\\',':\\\\','|',':'),array(':\/\/',':\/','\/',''),$_REQUEST['search']);
	//END To revise later on.
	if ($_REQUEST['search'] == '-')
		$_GET['search'] = $_REQUEST['search'] = '';
	
	// module system hook
	$vars = '';
	check_actions('search_top', $vars);
	
	$search = new Search();

	if(isset($_REQUEST['from'])){
		$search->newerthan = sanitize($_REQUEST['from'], 3);
	}
	
	if (preg_match('/^\s*((http[s]?:\/+)?(www\.)?([\w_\-\d]+\.)+\w{2,4}(\/[\w_\-\d\.]+)*\/?(\?[^\s]*)?)\s*$/i',$_REQUEST['search'],$m))
	    $_REQUEST['url'] = $m[1];
	else
	    $search->searchTerm = $db->escape(sanitize($_REQUEST['search']), 3);
	
	if(!isset($_REQUEST['search'])){$search->orderBy = "link_modified DESC";}
	if(isset($_REQUEST['tag'])){$search->searchTerm = sanitize($_REQUEST['search'], 3); $search->isTag = true;}
	if(isset($_REQUEST['url'])){$search->url = sanitize(preg_replace('/^(http[s]?:\/+)?(www\.)?/i','',$_REQUEST['url']), 3); }

	// figure out what "page" of the results we're on
	$search->offset = (get_current_page()-1)*$page_size;

	if(isset($_REQUEST['pagesize']))
		{$search->pagesize = sanitize($_REQUEST['pagesize'], 3);}
	else
		// $page_size is set in the admin panel
		{$search->pagesize = $page_size;}

	if(isset($_REQUEST['category'])){
		// filter to just the category we're looking at
		$search->category = sanitize($_REQUEST['category'], 1);
	} 

$sort_uri = $URLMethod == 2 ? 'order/' : '&order=';
$request_uri = preg_replace('/' . str_replace('/', '\/', $sort_uri) . '.*$/', '', $_SERVER['REQUEST_URI']) . $sort_uri;
$main_smarty->assign('index_url_recent', $request_uri);
$main_smarty->assign('index_url_upvoted', $request_uri . 'upvoted'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_downvoted', $request_uri . 'downvoted'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_commented', $request_uri . 'commented'  . ($URLMethod == 2 ? '/' : ''));

//Advanced Search
if( isset( $search_array['adv'] ) && $search_array['adv'] == 1 ){
	$search->adv = true;
	if (isset($search_array['date']) && !empty($search_array['date'])) {
		//Redwine: if date is not valid, we set $_GET['date'] and $_GET['date_to'] to empty and we make $_REQUEST['date'] and $_REQUEST['date_to'] equal to them
		
		$isdate_Valid = validateDate($search_array['date']);
		$isdate_to_Valid = validateDate($search_array['date_to']);
		
		if (!$isdate_Valid || !$isdate_to_Valid) {
			$search_array['date'] = '';
			$search_array['date_to'] = '';
			$_REQUEST = $_GET = $search_array;
		}else{
			$todaysdate=date("Y-m-d");
			if (strtotime($search_array['date_to']) < strtotime($search_array['date'])) {
				$search_array['date_to'] = $todaysdate;
			}
			$search->s_date = $search_array['date'];
			$search->s_date_to = $search_array['date_to'];
		}
	}

	$_GET = $_REQUEST = $search_array;

	/* Redwine: setting the type of each expected value */
	settype($_REQUEST['search'], "string");
	settype($_REQUEST['slink'], "integer");
	settype($_REQUEST['scategory'], "integer");
	settype($_REQUEST['sgroup'], "integer");
	settype($_REQUEST['status'], "string");
	settype($_REQUEST['scomments'], "integer");
	settype($_REQUEST['stags'], "integer");
	settype($_REQUEST['suser'], "integer");
	settype($_REQUEST['adv'], "integer");
	
	
	if (isset($_REQUEST['sgroup'])) $search->s_group = $_REQUEST['sgroup'];
	if (isset($_REQUEST['stags'])) $search->s_tags = $_REQUEST['stags'];
	if (isset($_REQUEST['slink'])) $search->s_story = $_REQUEST['slink'];
	if (isset($_REQUEST['status'])) $search->status = $_REQUEST['status'];
	if (isset($_REQUEST['suser'])) $search->s_user = $_REQUEST['suser'];
	if (isset($_REQUEST['scategory'])) $search->s_cat = $_REQUEST['scategory'];
	if (isset($_REQUEST['scomments'])) $search->s_comments = $_REQUEST['scomments'];		

	if( intval( $_REQUEST['sgroup'] ) > 0 )
		$display_grouplinks = true;
}
//Redwine: we need to make sure to re-assign $_GET equal to $_REQUEST to also affect all the changes in the validation checks to be reflected in the breadcrumb.
$_GET = $_REQUEST;
$main_smarty->assign('get',$_GET); 
//end Advanced Search

// breadcrumbs and page title
$search->searchTerm = str_replace('/','',$search->searchTerm);

$navwhere['text1'] = $main_smarty->get_config_vars('PLIKLI_Visual_Breadcrumb_Search') . stripslashes(str_replace('/','',$search->searchTerm));
$navwhere['link1'] = getmyurl('search', urlencode($search->searchTerm));
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Breadcrumb_Search') . stripslashes($search->searchTerm));

//sidebar
$main_smarty = do_sidebar($main_smarty);

// misc smarty
$main_smarty->assign('searchboxtext',sanitize($_REQUEST['search'],2));
$main_smarty->assign('cat_url', getmyurl("maincategory"));
$main_smarty->assign('URL_rss_page', getmyurl('rsssearch',sanitize($search->searchTerm,2)));

if((strlen($search->searchTerm) < 3 || strlen(html_entity_decode($search->searchTerm)) < 3) && strlen($search->url) < 3 && !$search->s_date)
{
	$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Search_Too_Short'));
	$main_smarty->assign('pagename', 'noresults');
}
else
{
	if (isset($_GET['order'])) $search->ords = $db->escape($_GET['order']);
	$new_search = $search->new_search();

	$linksum_count = $search->countsql;
	$linksum_sql = $search->sql;
	
	$main_smarty->assign('sql', $linksum_sql);
	// pagename	
	define('pagename', 'search'); 
	$main_smarty->assign('pagename', pagename);

	$fetch_link_summary = true;

	include('./libs/link_summary.php'); // this is the code that show the links / stories
	if($rows == false){
		$main_smarty->assign('posttitle', $main_smarty->get_config_vars('PLIKLI_Visual_Search_NoResults') . ' ' . stripslashes($search->searchTerm) . stripslashes($search->url));
		$main_smarty->assign('pagename', 'noresults');
	}
	
	$pages = do_pages($rows, $page_size, "search", true);

	if(isset($_REQUEST['tag']))
	    $pages = str_replace('/search/','/tag/',$pages);
		
	if(Auto_scroll==2 || Auto_scroll==3){
	   $main_smarty->assign("scrollpageSize", $page_size);
	}else
		$main_smarty->assign('search_pagination', $pages);
		
	$main_smarty->assign('total_row_for_search', $rows);
}

// show the template
$main_smarty->assign('tpl_center', $the_template . '/search_center');
$main_smarty->display($the_template . '/plikli.tpl');
?>