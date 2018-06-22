$(function() {
    $(".reg_username").blur(function() 
    {
		
	  var oparation = 'username';
	  var user_name= $(this).val()
	  var dataString = 'type='+oparation+'&name='+user_name;
	  var parent = $(".reg_usernamecheckitvalue");
	  $.ajax({
	  type: "POST",
	  async: false,
	  url:my_base_url+my_plikli_base+"/checkfield.php",
	  data: dataString,
	  beforeSend: function() {
      	parent.addClass("loader");
        },
	  cache: false,
	  success: function(html)
		{
		  if(html!='OK')
		  parent.html('<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close">&times;</button>'+html+'<div>');
		  else
		  parent.html('');
		  parent.removeClass("loader");
		} 
	  });
	});
	
	
	 $(".reg_email").blur(function() 
    {
		
	  var oparation = 'email';
	  var user_name= $(this).val()
	  var dataString = 'type='+oparation+'&name='+user_name;
	  var parent = $(".reg_emailcheckitvalue");
	  $.ajax({
	  type: "POST",
	  async: false,
	  url:my_base_url+my_plikli_base+"/checkfield.php",
	  data: dataString,
	  beforeSend: function() {
      	parent.addClass("loader");
        },
	  cache: false,
	  success: function(html)
		{
		  if(html!='OK')
		  parent.html('<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close">&times;</button>'+html+'<div>');
		  else
		  parent.html('');
		  parent.removeClass("loader");
		} 
	  });
	});
	
	 return false;
  
  });
