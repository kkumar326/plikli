{************************************
****** Search Results Template ******
*************************************}
<!-- search_center.tpl -->
{$link_summary_output}
<br />
{checkActionsTpl location="tpl_plikli_pagination_start"}
{if $link_summary_output neq ""}{$search_pagination}{/if}
{checkActionsTpl location="tpl_plikli_pagination_end"}
<!--/search_center.tpl -->