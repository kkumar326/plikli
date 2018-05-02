{************************************
*********** Live Main Page **********
*************************************}
<!-- live_center.tpl -->

<legend>{#PLIKLI_Visual_Live#}</legend>
<table class="table table-bordered table-striped" id="liveTble">
	<thead>
		<tr class="live2-item">
			<th><span class="live2-ts"><strong>{#PLIKLI_Visual_All_Hour#}</strong></span></th>
			<th><span class="live2-type"><strong>{#PLIKLI_Visual_All_Action#}</strong></span></th>
			<th><span class="live2-votes"><strong><center>{#PLIKLI_Visual_All_Votes#}</center></strong></span></th>
			<th><span class="live2-story"><span align="center"><strong>{#PLIKLI_Visual_All_Story#}</strong></span></span></th>
			<th><span class="live2-who"><strong>{#PLIKLI_Visual_All_User#}</strong></span></th>
			<th><span class="live2-status"><strong>{#PLIKLI_Visual_All_State#}</strong></span></th>
		</tr>
	</thead>
	<tbody>
		{section name=foo start=0 loop=$items_to_show step=1}
			<tr id="live2-{$templatelite.section.foo.index}" class="live2-item"></tr>
		{/section}
	</tbody>
</table>

<!--/live_center.tpl -->