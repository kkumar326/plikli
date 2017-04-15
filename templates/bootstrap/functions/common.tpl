{* Redwine: correcting the <script>literal *}
{literal}<script>
function show_hide_user_links(thediv)
{
	if(window.Effect){
		if(thediv.style.display == 'none')
		{Effect.Appear(thediv); return false;}
		else
		{Effect.Fade(thediv); return false;}
	}else{
		var replydisplay=thediv.style.display ? '' : 'none';
		thediv.style.display = replydisplay;					
	}
}
{* Redwine: correcting the /literal</script> *}
</script>{/literal}

{literal}<script>
// Redwine: Roles and permissions and Groups fixes. This script is needed to change the display to the corresponding status whe a group story status is changed. It works well, however, the page is cached and needs refreshing to load the new version. window.location.reload(true) is not working in Firefox.
function switch_group_links_tabs(status)
{
	var address = window.location.href;
	if (status != 'discard') {
		if (address.indexOf("published") != -1){
			var redirect = address.replace('published', 'new');
		}else if (address.indexOf("new") != -1) {
			var redirect = address.replace('new', 'published');
		}
		window.location.assign(redirect);
	}else{
		window.location.href = address;
	}	
}
</script>{/literal}
