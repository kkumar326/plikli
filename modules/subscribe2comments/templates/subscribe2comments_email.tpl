<html>
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>New Comment: {$link_title}</title>
	{literal}
	<style>
	div.layout{text-align:center;}
	div.centre{text-align:centre;display: block;margin-left: auto;margin-right: auto;}
	
	@media only screen 
	  and (min-device-width: 681px) {
		div.layout{text-align:center;}
		div.wdth{width:60%}
		div.widthfull{width:100%}
	}
	</style>
	{/literal}
	</head>
<body>

	<div class="layout wdth" style="display:block;margin-left: auto;margin-right: auto;">
		<div class="centre" style="background-color:{$headingbg};">
		{if $site_logo}
			<img src="{$site_logo}" style="border:none;" />
		{/if}
			<h1 style="font-size:30px;color:{$headingfc}"><a href="{$my_base_url}{$my_plikli_base}" style="text-decoration:none;color:#ffffff">
				{$site_title}														
			</a></h1>
		</div>


	<div class="centre wdth">
		<div class="widthfull">
			<span style="width:75px;">
				<img src="{$user_avatar}" style="border:none;height:auto;width:48px;" />
			</span>
			<span><strong style="margin-left:15px;color:#0000ff;font-weight:bold;"><a href="{$user_profile}" style="text-decoration:none;">{$comment_author}</a></strong> published a comment:</span>
			<div class="centre wdth" style="">
				<blockquote style="margin:2px 0 0 20px;border-left: 5px solid #eee;padding-left:6px;font-style:italic;text-align:justify;">{$comment_content}</blockquote>
			</div>

		</div>
		<br style="clear:both;" />
		<p>Comment posted on this article:</p>
		<p><a href="{$story_url}" style="color:#0000ff;font-weight:normal;text-decoration:underline;">{$link_title}</a></p>
		
		<hr />
	</div>

		
	<div class="centre" style="text-align:center;">
		<span style="font-size:13px;background-color:#eeeeee;padding: 5px;line-height: 1.5em;">
			To unsubscribe from this article&#39;s comments? <a href="{$unsubscribe_link}" style="color:#0000ff;font-weight:normal;text-decoration:underline;">Unsubscribe</a>
		</span>
	</div>
		</div>
</body>
</html>