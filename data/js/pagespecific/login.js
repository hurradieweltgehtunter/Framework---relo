function getSearchParameters() {
      var prmstr = window.location.search.substr(1);
      return prmstr !== null && prmstr !== "" ? transformToAssocArray(prmstr) : {};
}

function transformToAssocArray( prmstr ) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
        var tmparr = prmarr[i].split("=");
        params[tmparr[0]] = tmparr[1];
    }
    return params;
}

$(document).ready(function(){

	$.backstretch("data/img/bg.jpg");

	getSearchParameters();

	$('#form_login').on('submit', function(){

		$('#errorbox_login').hide();
		$(this).find('.ajaxloader').addClass('AJAXrotate').show();

		$.postJSONsecure(
			{
				module: "login",
		        action: "verifyLogin",
		        values: 
		        {
		        	mail: $('#form_login #mail').val(),
		        	password: $('#form_login #password').val(),
		        	storeLogin: function(){
		        		if( $('#form_login #storelogin').is(':checked') ) 
		        			return 1;
		        		else
		        			return 0;
		        	}
		        }
		    }, 
			function(rdata){
				
				if(rdata.status == 'correct')
					window.location = "start";
				else
				{
					$('#form_login').find('.ajaxloader').hide().removeClass('AJAXrotate');
					$('#errorbox_login').html(rdata.status).css({display: "inline-block"});
				}	
			}
		);

		return false;
	});

	$('#form_register').on('submit', function(e){
		e.preventDefault();

		$('#errorbox_register').hide();
		$(this).find('.ajaxloader').addClass('AJAXrotate').show();

		data = {
			data: {
				mail: $('#form_register #mail').val()
			},
			password: $('form_register #password').val(),
			password2: $('form_register #password2').val(),
			storeLogin: 0
		};

		$(this).find('input:not([type="submit"])').each(function(){
			data[$(this).attr('id')] = $(this).val();
		});

		$.postJSONsecure(
			{
				module: "login",
		        action: "register",
		        values: data
		    }, 
			function(rdata){
				
				if(rdata.status == 'correct')
				{
					$('#form_register').find('.ajaxloader').hide().removeClass('AJAXrotate');
					$('#form_register .input_container').slideUp(300);
					$('#errorbox_register').html(rdata.msg).css({display: "inline-block"});
				}
				else
				{
					$('#form_register').find('.ajaxloader').hide().removeClass('AJAXrotate');
					$('#errorbox_register').html(rdata.status).css({display: "inline-block"});
				}
			}
		);
		return false;
	});
});