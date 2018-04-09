- story_view.sql (filled it with data for your testing)

- Added to \libs\define_tables.php the following
	define('table_story_views', table_prefix . "story_views" );

- In story.php, I added the callback to instert into the table, right after check_actions('story_top', $vars); on line 104
	// ************************************************************************
	// The two lines below are a callback to module 'total_Story_views to add
	// story id in the new table "story_views" everytime the story is viewed
	//
	$vars = array('link_id' => $link->id);
	check_actions('add_story_views', $vars);
	// ****************** End callback ****************************************
	
- In \libs\link.php, I added the calback to get the total of each story id, within the function read($usecache = TRUE) { and right after $id = $this->id; on line 248
	// ************************************************************************
		// The two lines below are a callback to module 'total_Story_views to get
		// the total of each story. The total will be showing under the story title
		//
		$vars = array('link_id' => $this->id);
		check_actions('get_story_views', $vars);
		// ****************** End callback ****************************************
		
- In \templates\bootstrap\link_summary.tpl, I added the tpl callback within the <span class="subtext"> right before <i class="fa fa-folder"></i> on line 114
	{checkActionsTpl location="tpl_plikli_story_views_end"}
	


