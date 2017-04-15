{************************************
******* Registration Complete *******
*************************************}
<!-- register_complete_center.tpl -->
{checkActionsTpl location="tpl_kliqqi_register_complete_start"}
<p>
	{#KLIQQI_Visual_Register_Thankyou#|sprintf:$get.user}
	{#KLIQQI_Visual_Register_Noemail#}
	{assign var="email" value=#KLIQQI_PassEmail_From#}
	{#KLIQQI_Visual_Register_ToDo#|sprintf:$email}
</p>
{checkActionsTpl location="tpl_kliqqi_register_complete_end"}
<!--/register_complete_center.tpl -->