{config_load file=total_story_views_lang_conf}
	<div class="headline">
		<div class="sectiontitle">{#PLIKLI_Most_Viewed_Stories#}</div>
	</div>
	<div class="boxcontent" >
		<ul class="sidebar-stories" id="viewed_stories">
			{checkActionsTpl location="tpl_widget_most_viewed_start"}	
			{php}
			include_once('internal/Smarty.class.php');
			$main_smarty = new Smarty;
			
			include_once('config.php');
			include_once(mnminclude.'html1.php');
			include_once(mnminclude.'link.php');
			include_once(mnminclude.'tags.php');
			include_once(mnminclude.'search.php');
			include_once(mnminclude.'smartyvariables.php');

			// -------------------------------------------------------------------------------------

			global $the_template, $main_smarty, $db, $total_views_settings;
			//$user_logged_id = $main_smarty->get_template_vars('user_logged_id');
			$sql = "SELECT t1.view_link_id, t1.view_link_count as Count, t2.link_id, t2.link_title\n"
				. "FROM ".table_prefix ."story_views t1\n"
				. "inner join " .table_prefix ."links t2 on view_link_id = link_id\n"
				. " group by `view_link_id`\n"
				. "order by Count DESC LIMIT 0, " .$total_views_settings['count']. ";";
			$list_mostviewed = $db->get_results($sql);
			if($list_mostviewed)
			{
				foreach($list_mostviewed as $row){            
					$story_url = getmyurl("story", $row->link_id);
					echo "<li><span class='sidebar-vote-number'>".$row->Count."</span><a class='switchurl' href='".$story_url."'>".$row->link_title."</a></li>";                
				}
			}
			{/php}
			{checkActionsTpl location="tpl_widget_most_viewed_end"}
		</ul>
	</div>
{config_load file=total_story_views_plikli_lang_conf}