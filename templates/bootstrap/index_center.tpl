{************************************
***** Published Pages Template ******
*************************************}
<!-- index_center.tpl -->

{if !$link_summary_output && $pagename == 'index' && count($templatelite.get) == 0}
	{* Welcome message for new installations *}
	<div class="well blank_index">
		{* Redwine: I added the hard coded text to the language files and replaced it with its language constant here, thus making it available and compliant with language changing *}
		<h2>{#KLIQQI_Visual_Welcome_to#} {#KLIQQI_Visual_Name#}</h2>
		<p style="font-size:1.0em;">{#KLIQQI_Visual_Welcome_to_Message#}</p>
		<p><a href="submit.php" class="btn btn-primary">{#KLIQQI_Visual_Welcome_to_Submit_First_Story#}</a></p>
	</div>
{/if}

{$link_summary_output}

{checkActionsTpl location="tpl_kliqqi_pagination_start"}
{$link_pagination}
{checkActionsTpl location="tpl_kliqqi_pagination_end"}
<!--/index_center.tpl -->