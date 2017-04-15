{************************************
************ Search Box *************
*************************************}
<!-- search_box.tpl -->
{checkActionsTpl location="tpl_kliqqi_search_start"}
<script type="text/javascript">
	{if !isset($searchboxtext)}
		{assign var=searchboxtext value=#KLIQQI_Visual_Search_SearchDefaultText#}			
	{/if}
	var some_search='{$searchboxtext}';
</script>
<div class="search">
	<div class="headline">
		<div class="sectiontitle">{#KLIQQI_Visual_Search_Title#}</div>
	</div>

	<form action="{$my_kliqqi_base}/search.php" method="get" name="thisform-search" class="form-inline search-form" role="form" id="thisform-search" {if $urlmethod eq 2}onsubmit='document.location.href="{$my_base_url}{$my_kliqqi_base}/search/"+this.search.value.replace(/\//g,"").replace(/\?/g,""); return false;'{/if}>
		
			<div class="input-group">
		
		<input type="text" class="form-control" tabindex="20" name="search" id="searchsite" value="{$searchboxtext}" onfocus="if(this.value == some_search) {ldelim}this.value = '';{rdelim}" onblur="if (this.value == '') {ldelim}this.value = some_search;{rdelim}"/>
			
			<span class="input-group-btn">
				<button type="submit" tabindex="21" class="btn btn-primary custom_nav_search_button" />{#KLIQQI_Visual_Search_Go#}</button>
			</span>
		 </div>
	</form>

	<div style="clear:both;"></div>
	<br />
</div>
{checkActionsTpl location="tpl_kliqqi_search_end"}
<!--/search_box.tpl -->
