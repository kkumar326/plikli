{* Redwine: changed the data to display to also include spam comments *}
<ul>
	{if $moderated_submissions_count gt 0}
	<li><strong><a href='admin_links.php?filter=moderated' style='text-decoration:underline;'>You have {if $moderated_submissions_count gt 1}{$moderated_submissions_count} Stories {elseif $moderated_submissions_count eq 1}{$moderated_submissions_count} Story {elseif $moderated_submissions_count eq ''} 0 Story{/if}  awaiting moderation</a></strong></li>
	{/if}
	
	{if $moderated_comments_count gt 0}
	<li><strong><a href='admin_comments.php?filter=moderated' style='text-decoration:underline;'>You have {if $moderated_comments_count gt 1}{$moderated_comments_count} Comments {elseif $moderated_comments_count eq 1}{$moderated_comments_count} Comment {elseif $moderated_comments_count eq ''} 0 Comment{/if}  awaiting moderation</a></strong></li>
	{/if}
	
	{if $spam_comments_count gt 0}
	<li><strong><a href='admin_comments.php?filter=spam' style='text-decoration:underline;'>You have {if $spam_comments_count gt 1}{$spam_comments_count} SPAM Comments {elseif $spam_comments_count eq 1}{$spam_comments_count} SPAM Comment {elseif $spam_comments_count eq ''} 0 SPAM Comment{/if}  awaiting revision</a></strong></li>
	{/if}
	
</ul>