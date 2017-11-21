var rfq_url = '../rfq/public/';

function validateLogin()
{
	var username  		= jQuery.trim($("#username").val());
		var password  		= jQuery.trim($("#password").val());	
		
		if (username==""){
			$("#username").css("border","1px solid #e77776"); 
			$("#username").css("background","#f8dbdb"); 
		 	$("#username").focus();  
			return false;
		}
		if (password==""){
			$("#password").css("border","1px solid #e77776"); 
			$("#password").css("background","#f8dbdb");
			$("#password").focus();  
			return false;
		} 
		
	 
		
		var a  = "chk_details";	 
		var param = { user_name:username, password:password,module:'login', action:a};  
			$.ajax({
					type		: 'POST',
					url			:  'process.php',
					dataType	: 'json',
					data		:  param,					   	 
					success	:  function(data){ 
					
					//console.log(data);
						//	 return false;
							 
						var opStatus='false';
						var rfq = 0;
						if(data.status!=undefined) opStatus=data.status;
						
						
						if(opStatus=='success')
						{
							location.href='main.php'; 	
						}
						else
						{
							if(data.message!=undefined)
							{
								alert(data.message); 
							}
							else
							{ 
								alert('Something went wrong!'); 
							}	
						}  
		
						},
						error	:  function(data) { alert('Something went wrong!');    }
					}); 
}

function rfqLogout()
{
	var param = {};
	
	$.ajax({
					type		: 'GET',
					url			:  rfq_url+'admin_logout',//'process.php',
					dataType	: 'text',
					data		:  param,					   	 
					success	:  function(data){ 
					},
						error	:  function(data) { alert('Something went wrong!');    }
					}); 
}