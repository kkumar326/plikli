<div style="margin:10px 10px 0 10px;">
	<form method="get">
		<input type="hidden" name="widget" value="kliqqi_news">
		<p>
			{#KLIQQI_News_Widget_Select_Show#}		
			<select name="stories" value="kliqqi_news" style="width:75px;">
				<option value="1" {if $news_count eq "1"}selected="selected"{/if}>1</option>
				<option value="2" {if $news_count eq "2"}selected="selected"{/if}>2</option>
				<option value="3" {if $news_count eq "3"}selected="selected"{/if}>3</option>
				<option value="4" {if $news_count eq "4"}selected="selected"{/if}>4</option>
				<option value="5" {if $news_count eq "5"}selected="selected"{/if}>5</option>
			</select>
			{#KLIQQI_News_Widget_Select_Items#}
			
			{* <input type="text" name="stories" value="{$news_count}"> *}
		</p>
		<p>
			<input type="submit" class="btn btn-primary" value="{#KLIQQI_News_Widget_Save#}">
		</p>
	</form>
</div>