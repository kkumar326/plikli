<script type="text/javascript" language="javascript">
	
	var my_plikli_url = "{$my_base_url}{$my_plikli_base}";
	var Pager_setting = "{$Pager_setting}";
	var page_name = "{$pagename}";
	var total_row_for_search = "{$total_row_for_search}";
	var pageSize = "{$scrollpageSize}";
	var sql = "{$sql}";
	
	{literal}
	
		$(document).ready(function(){
			
			var count=parseInt(pageSize);
		
			function pagination_for_search() 
			{ 
				var url = "";
			
			 	url = my_plikli_url+"/load_data_for_search.php";
			
				var dataString = "start_up="+count+"&pagesize="+pageSize+"&sql="+sql;
								
				$.ajax({
						type: "POST",
						url:url,
						data: dataString,
						beforeSend: function() {
							
							$(".stories:last").addClass("loader");
						},
						cache: false,
						success: function(html)	{
							
							if (html != "") {
								
								$(".stories:last").after(html); 
								$(".stories").removeClass("loader");
								count=count+parseInt(pageSize);
							}
						} 
				});
			}; 
		
		 	if(Pager_setting==2){
				$(window).scroll(function(){
					if ($(window).scrollTop() == $(document).height() - $(window).height()){
						if(parseInt(total_row_for_search)>=count)
						pagination_for_search();
					}
				}); 
		   } else if(Pager_setting==3){
			   
				if(parseInt(total_row_for_search)>count)  
					$(".stories:last").after("<div class='btn btn-default contine_read_story'>{/literal}{#PLIKLI_Continue_Reading#}{literal}</div>"); 
				
				$(".contine_read_story").live("click", function(){
					if(parseInt(total_row_for_search) > count){
						pagination_for_search();
					}else{	
						$(this).hide();
						
						$(".stories:last").after("<div class='btn btn-default no_stories_left'>{/literal}{#PLIKLI_No_More_Articles#}{literal}</div>"); 
					}
				});
		   }
		});
		
	{/literal}
	</script>