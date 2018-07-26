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
	  var user_email= $(this).val()
	  var dataString = 'type='+oparation+'&email='+user_email;
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
	
  

	 $(".reg_password").blur(function() 
    {
		
	  var oparation = 'password';
	  var user_password= $(this).val()
	  var dataString = 'type='+oparation+'&password='+user_password;
	  var parent = $(".reg_userpasscheckshort");
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
  
  
  
//$( document ).ready(function() {
	/*
	I used [`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/] versus [^\w\s-_] because JavaScript does not work well with UTF-8
	and does not recognize the word boundaries in utf8. 
	*/
	$(function(){
		$(".reg_username").keyup(function() {
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/-]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(re, '');
				$(this).val(no_spl_char);
			}
  });
		$(".reg_username").bind("paste", function() {
			setTimeout(function() { 
			  //get the value of the input text
			  var data= $( ".reg_username" ).val() ;
			  //replace the special characters to '' 
			  var dataFull = data.replace(/[`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/-]/gi, '');
			  //set the new value of the input text without special characters
			  $( ".reg_username" ).val(dataFull);
			});
		});
	});
//});
