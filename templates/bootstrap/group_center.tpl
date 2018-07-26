{************************************
******* Groups Main Template ********
*************************************}
<!-- group_center.tpl -->
{if $enable_group eq "true"}
	{if $pagename eq "groups"}
		<div class="well group_explain">
			<div class="group_explain_inner">
				<h2>{#PLIKLI_Visual_Group_Explain_title#}</h2>
				<div class="group_explain_description">
					{#PLIKLI_Visual_Group_Explain#}
				</div>
				{if $group_allow eq "1"}
					<div class="create_group">
						<form>
{* Redwine: Roles and permissions and Groups fixes. Fix the button when user met group creation quota *}
							<input type="button" value="{#PLIKLI_Visual_Submit_A_New_Group#}" onclick="window.location.href='{$URL_submit_groups}'" class="{if empty($error_max)}btn btn-success{else}btn btn-danger disabled strike-through" title="{#PLIKLI_Visual_Submit_A_New_Group_Error#}{/if}">
						</form>
					</div>
				{/if}
				<div class="search_groups">
					<div class="input-append">
						<form action="{$my_plikli_base}/groups.php" method="get"	{if $urlmethod eq 2}onsubmit="document.location.href = '{$my_base_url}{$my_plikli_base}/groups/search/' + encodeURIComponent(this.keyword.value); return false;"{/if}>
							<input type="hidden" name="view" value="search">
							{if !empty($get.keyword)}
								{assign var=searchboxtext value=$get.keyword}
							{else}
								{assign var=searchboxtext value=''}
							{/if}
							<div class="col-md-8">
								<input type="text" class="form-control" id="keyword" name="keyword" value="{$searchboxtext}" placeholder="{#PLIKLI_Visual_Search_SearchDefaultText#}" onfocus="if(this.value == 'encodeURIComponent({$searchboxtext})') {ldelim}this.value = '';{rdelim}" onblur="if (this.value == '') {ldelim}this.value = 'encodeURIComponent({$searchboxtext})'{rdelim}">
							</div>
							<div class="col-md-4">
								<button class="btn btn-primary" type="submit">{#PLIKLI_Visual_Group_Search_Groups#}</button>
							</div>
						</form>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
	{/if}
	{if !empty($get.keyword)}
		{if !empty($group_display)}
			<legend>{#PLIKLI_Visual_Search_SearchResults#} &quot;{$search}&quot;</legend>
		{else}
			<legend>{#PLIKLI_Visual_Search_NoResults#} &quot;{$search}&quot;</legend>
		{/if}
	{/if}
	{if !empty($group_display)}{$group_display}{/if}
	<div style="clear:both;"></div>
	{if !empty($group_pagination)}{$group_pagination}{/if}
{/if}
   {if $enable_group neq "true"}
       {literal}
			<script type="text/javascript">
			   <!--
			   window.location="{/literal}{$my_base_url}{$my_plikli_base}/error_404.php{literal}";
			   //-->
			</script>
      {/literal}
   {/if}
 
 {literal}
<script type="text/javascript">
$( document ).ready(function() {
	/*
	I used [`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/] versus [^\w\s-_] because JavaScript does not work well with UTF-8
	and does not recognize the word boundaries in utf8. 
	*/
	$(function(){
		$('#keyword').keyup(function() {
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(re, '');
				$(this).val(no_spl_char);
			}
		});
		$('#keyword').bind("paste", function() {
			setTimeout(function() { 
			  //get the value of the input text
			  var data= $( '#keyword' ).val() ;
			  //replace the special characters to '' 
			  var dataFull = data.replace(/[`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/]/gi, '');
			  //set the new value of the input text without special characters
			  $( '#keyword' ).val(dataFull);
			});
		});
	});
});
</script>
{/literal}
<!-- group_center.tpl -->