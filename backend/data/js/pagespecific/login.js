$(document).ready(function(){
	$('form#login').on('submit', function(e){
		e.preventDefault();

		$.postJSONsecure(
			{
				module: "login",
		        action: "verifyLogin",
		        values: 
		        {
		        	mail: $('#login #mail').val(),
		        	password: $('#login #password').val(),
		        	storeLogin: function(){
		        		if( $('#login #storelogin').is(':checked') ) 
		        			return 1;
		        		else
		        			return 0;
		        	}
		        }
		    }, 
			function(rdata){
				if(rdata.status == 'correct')
				{
					window.location = "start";
				}
				else
					$('#errorbox').html(rdata.status);
			}
		);
		return false;
	});
});