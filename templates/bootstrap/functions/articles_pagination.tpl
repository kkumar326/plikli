{literal}
<script type="text/javascript">

	
	
	$(document).ready(function()
	{
	var my_plikli_url={/literal}"{$my_base_url}{$my_plikli_base}"{literal};
	var total_row={/literal}"{if !empty($total_row)}{$total_row}{/if}"{literal};
	var Pager_setting={/literal}"{$Pager_setting}"{literal};
	var page_name={/literal}"{$pagename}"{literal};
	var pageSize={/literal}"{if !empty($scrollpageSize)}{$scrollpageSize}{/if}"{literal};

		var count;
		count=parseInt(pageSize);
		
		function last_msg_funtion() 
		{ 
			var data="";
			var url = "";
		
			if(page_name=="index" || page_name=="new" || page_name=="published"){
				var catID={/literal}"{if !empty($catID)}{$catID}{/if}"{literal};
				var part={/literal}"{if !empty($part)}{$part}{/if}"{literal};
				var searchorder={/literal}"{if !empty($searchOrder)}{$searchOrder}{/if}"{literal};
			 	data="&catID="+catID+"&part="+part+"&sorder="+searchorder;
			 	url = my_plikli_url+"/load_data.php";
			 }
			else if(page_name=="group_story"){
				var groupID={/literal}"{if !empty($groupID)}{$groupID}{/if}"{literal};
				var viewtype={/literal}"{if !empty($viewtype)}{$viewtype}{/if}"{literal};
				var group_vote={/literal}"{if !empty($group_vote)}{$group_vote}{/if}"{literal};
				var catID={/literal}"{if !empty($catID)}{$catID}{/if}"{literal};
			 	data="&groupid="+groupID+"&view="+viewtype+"&group_vote="+group_vote+"&catID="+catID; 
			 	url = my_plikli_url+"/load_data.php";
			 }
			else if(page_name=="user"){
				var viewtype={/literal}"{if !empty($viewtype)}{$viewtype}{/if}"{literal};
				var userid={/literal}"{if !empty($userid)}{$userid}{/if}"{literal};
				var curuserid={/literal}"{if !empty($curuserid)}{$curuserid}{/if}"{literal};
			 	data="&view="+viewtype+"&userid="+userid+"&curuserid="+curuserid; 
			 	url = my_plikli_url+"/load_data.php";
			 }
			
			var dataString = "pname="+page_name+"&start_up="+count+"&pagesize="+pageSize+data;
								
				$.ajax({
					type: "POST",
					url:url,
					data: dataString,
					beforeSend: function() {
						$(".stories:last").addClass("loader");
					},
					cache: false,
					success: function(html)	{
						
						if ($.trim(html) != "") {
							
							$(".stories:last").after(html); 
							$(".stories").removeClass("loader");
							count=count+parseInt(pageSize);
						} else{
						
							$(".stories").removeClass("loader");
						}
					} 
			});
		}; 
      
	   if(Pager_setting==2){
		$(window).scroll(function(){
			if ($(window).scrollTop() == $(document).height() - $(window).height()){
				if(parseInt(total_row)>=count)
				last_msg_funtion();
			}
		}); 
	   }else if(Pager_setting==3){
		   
			if(parseInt(total_row)>count)  
			$(".stories:last").after("<div class='btn btn-default contine_read_story'>{/literal}{#PLIKLI_Continue_Reading#}{literal}</div>"); 
			
			$(".contine_read_story").live("click", function(){
				if(parseInt(total_row) > count){
					last_msg_funtion();
				}else{	
					$(this).hide();
					$(".stories:last").after("<div class='btn btn-default no_stories_left'>{/literal}{#PLIKLI_No_More_Articles#}{literal}</div>"); 
				}
			});
	   }
	});
	
	</script>
{/literal}