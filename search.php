<?php

	include_once('internal/Smarty.class.php');
	$main_smarty = new Smarty;
	
	include('config.php');
	include(mnminclude.'html1.php');
	include(mnminclude.'link.php');
	include(mnminclude.'tags.php');
	include(mnminclude.'search.php');
	include(mnminclude.'smartyvariables.php');

	/*Redwine: the str_replace was not escaped correctly!*/
	/*********
	Redwine: I added  && strstr($_REQUEST['search'],'/adv/1') because the regular search $_REQUEST['search'] is always equal to the search word and a forward slash (/) at the end, therefore the first condition strstr($_REQUEST['search'],'/') is true and the second one is also true when in SEO URL Method 2. This creates a problem because the data process in this conditional statement should only apply when the advanced search is used and not the regular search. The condition I added will make sure to determine if the regular or advanced search is used.
	*********/
	if (strstr($_REQUEST['search'],'/') && $URLMethod == 2 && strstr($_REQUEST['search'],'/adv/1'))
	{
		/*$post = preg_split('/\//',$_REQUEST['search']);
		$_GET['search'] = $_REQUEST['search'] = $post[1];
		for ($i=1; $i+1<sizeof($post); $i+=2)
		$_GET[$post[$i]] = $_REQUEST[$post[$i]] = $post[$i+1];
	
		$get = array();
		foreach ($_GET as $k => $v)
		$get[$k] = stripslashes(htmlentities(strip_tags($v),ENT_QUOTES,'UTF-8'));
		if (isset($get['return'])) $get['return'] = addslashes($get['return']);*/
		
		/************************
		Redwine: the above code was commented because it was too much for a simple task and it was wrong in the for loop $i value should start at 2 because I fixed the seo url method 2 in search_advanced_center.tpl that was not accounting for the "search" key and other fields were not also included. See the tpl file.
		************************/
		
		/************************
		Redwine: in seo url method 2, the search request value is transmitted as a slash delimited string: 
		search/blabla/slink/1/scategory/0/sgroup/3/status/all/stags/1/adv/1/
		we need to convert it to an $_REQUEST ARRAY. Afterwards, we also assign the $_GET array to be equal to $_REQUEST 
		************************/
		preg_match_all("/([^\/]+)\/([^\/]+)/", $_REQUEST['search'], $p);
		$search_array = array_combine($p[1], $p[2]);
		//Redwine: re-assigning the $_GET and $_REQUEST arrays
		$_REQUEST = $_GET = $search_array;
		if (isset($_GET['return'])) $_GET['return'] = addslashes($_GET['return']);
		$main_smarty->assign('get',$_GET);           
	}
	$_REQUEST['search'] = str_replace('/','',$_REQUEST['search']);
	$_GET['search'] = $_REQUEST['search'];
	$main_smarty->assign('get',$_GET);
	/*Redwine: the str_replace was not escaped correctly and if the search term contains a colon, the search result is not accurate. So I replaced the colon with empty!*/
	$_REQUEST['search'] = str_replace(array(':\\\\',':\\\\','|',':'),array(':\/\/',':\/','\/',''),$_REQUEST['search']);

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

	if(isset($_REQUEST['status'])){
		// if "status" is set, filter to that status
		$search->filterToStatus = sanitize($_REQUEST['status'], 3);
	} else {
		// we want to view "all" stories
		$search->filterToStatus = "all";
	}

	if(isset($_REQUEST['category'])){
		// filter to just the category we're looking at
		$search->category = sanitize($_REQUEST['category'], 1);
	} 

$sort_uri = $URLMethod == 2 ? 'order/' : '&order=';
$request_uri = preg_replace('/' . str_replace('/', '\/', $sort_uri) . '.*$/', '', $_SERVER['REQUEST_URI']) . $sort_uri;
$main_smarty->assign('index_url_recent', $request_uri);
/*
$main_smarty->assign('index_url_today', $request_uri . 'today' . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_yesterday', $request_uri . 'yesterday'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_week', $request_uri . 'week'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_month', $request_uri . 'month'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_year', $request_uri . 'year'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_alltime', $request_uri . 'alltime'  . ($URLMethod == 2 ? '/' : ''));
*/
$main_smarty->assign('index_url_upvoted', $request_uri . 'upvoted'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_downvoted', $request_uri . 'downvoted'  . ($URLMethod == 2 ? '/' : ''));
$main_smarty->assign('index_url_commented', $request_uri . 'commented'  . ($URLMethod == 2 ? '/' : ''));

//Advanced Search
if( isset( $_REQUEST['adv'] ) && $_REQUEST['adv'] == 1 ){
	$search->adv = true;
	//Redwine: the field sgroups is wrong; it must be sgroup
	//if (isset($_REQUEST['sgroups'])) $search->s_group = sanitize($_REQUEST['sgroup'],2);
	if (isset($_REQUEST['sgroup'])) $search->s_group = sanitize($_REQUEST['sgroup'],2);
	if (isset($_REQUEST['stags'])) $search->s_tags = sanitize($_REQUEST['stags'],2);
	if (isset($_REQUEST['slink'])) $search->s_story = sanitize($_REQUEST['slink'],2);
	if (isset($_REQUEST['status'])) $search->status = sanitize($_REQUEST['status'],2);
	if (isset($_REQUEST['suser'])) $search->s_user = sanitize($_REQUEST['suser'],2);
	if (isset($_REQUEST['scategory'])) $search->s_cat = sanitize($_REQUEST['scategory'],2);
	if (isset($_REQUEST['scomments'])) $search->s_comments = sanitize($_REQUEST['scomments'],2);		
	if (isset($_REQUEST['date'])) $search->s_date = sanitize($_REQUEST['date'],2);	
	if (isset($_REQUEST['date_to'])) $search->s_date_to = sanitize($_REQUEST['date_to'],2);
	if( intval( $_REQUEST['sgroup'] ) > 0 )
		$display_grouplinks = true;
}
//end Advanced Search

// breadcrumbs and page title
$search->searchTerm = str_replace('/','',$search->searchTerm);

$navwhere['text1'] = $main_smarty->get_config_vars('KLIQQI_Visual_Breadcrumb_Search') . stripslashes(str_replace('/','',$search->searchTerm));
$navwhere['link1'] = getmyurl('search', urlencode($search->searchTerm));
$main_smarty->assign('navbar_where', $navwhere);
$main_smarty->assign('posttitle', $main_smarty->get_config_vars('KLIQQI_Visual_Breadcrumb_Search') . stripslashes($search->searchTerm));

//sidebar
$main_smarty = do_sidebar($main_smarty);

// misc smarty
$main_smarty->assign('searchboxtext',sanitize($_REQUEST['search'],2));
$main_smarty->assign('cat_url', getmyurl("maincategory"));
$main_smarty->assign('URL_rss_page', getmyurl('rsssearch',sanitize($search->searchTerm,2)));

if(strlen($search->searchTerm) < 3 && strlen($search->url) < 3 && !$search->s_date)
{
	$main_smarty->assign('posttitle', $main_smarty->get_config_vars('KLIQQI_Visual_Search_Too_Short'));
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
		$main_smarty->assign('posttitle', $main_smarty->get_config_vars('KLIQQI_Visual_Search_NoResults') . ' ' . stripslashes($search->searchTerm) . stripslashes($search->url));
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
$main_smarty->display($the_template . '/kliqqi.tpl');
?>