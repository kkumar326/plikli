{************************************
******* Registration Complete *******
*************************************}
<!-- register_complete_center.tpl -->
{checkActionsTpl location="tpl_plikli_register_complete_start"}
<p>
	{#PLIKLI_Visual_Register_Thankyou#|sprintf:$get.user}
	{#PLIKLI_Visual_Register_Noemail#}
	{assign var="email" value=#PLIKLI_PassEmail_From#}
	{#PLIKLI_Visual_Register_ToDo#|sprintf:$email}
</p>
{checkActionsTpl location="tpl_plikli_register_complete_end"}
<!--/register_complete_center.tpl -->