{literal}
<script>
var my_plikli_base='{$my_plikli_base}';

function subscribe_2_comments(htmlid, linkid, unsubscribe)
{
	var url = my_plikli_base + "/modules/subscribe2comments/subscribe2comments.php";
	mycontent = "htmlid=" + htmlid + "&linkid=" + linkid;
	if (unsubscribe) mycontent += "&uns=1";
	 $.ajax({
	  type: "post",
	  url: url,
	  data: mycontent,
	  success: function( data ) {
		  //alert(data);
		$('#'+htmlid).html(data);
	  }
	 });
}
</script>
{/literal}