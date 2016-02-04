/* TAB "MY ACCOUNT" */
saveTimeout = null;
function autoSave()
{
	clearTimeout(saveTimeout);

	saveTimeout = setTimeout(function(){
		fields = {};

		$('.autosave.update').each(function(){
			
			if($(this).attr('type') === 'text' && $(this).val() !== '')
				fields[$(this).attr('id')] = $(this).val();
			else if($(this).attr('type') == 'checkbox')
			{
				if($(this).is(':checked'))
					fields[$(this).attr('id')] = 1;
				else
					fields[$(this).attr('id')] = 0;
			}	
		});

		imgcomments = [];
		$('.imgcomment.update').each(function(){
			comment = {
				id: $(this).attr('data-id'),
				comment: $(this).val()
			};
			imgcomments.push(comment);
		});
		
		data = {
			user: fields, 
			files: imgcomments
		};

		$.postJSONsecure({
            module: "user",
            action: "saveData",
            values: data
        }, 
        function(rdata){
        	if(rdata.status == 1)
        	{
        		notify('Alle Daten gespeichert');
        		$('.update').removeClass('update');
        	}
        });
    
	}, 1000);
}

/* TAB "IMAGES" */
function appendNewImage(file){

	//$('<div class="row imgrow new" style="opacity: 0;"><div class="imgrow_bg"></div><div class="col-sm-3"><div class="img_wrap"><img src="' + file.filename + '" /></div></div><div class="col-sm-9"><form class="form-horizontal"><div class="form-group"><label for="inputEmail3" class="col-sm-3 control-label">Hochgeladen am</label><div class="col-sm-9"><input type="email" class="form-control" id="inputEmail3" placeholder="Email" value="' + file.date + '" disabled></div></div><div class="form-group"><label for="inputPassword3" class="col-sm-3 control-label">Dein Kommentar</label><div class="col-sm-9"><textarea class="form-control" rows="3"></textarea></div></div></form></div></div>').insertAfter('#dropzonerow');

	$('<div class="row imgrow" data-image-id="' + file.id + '"><div class="col-sm-3"><div class="img_wrap"><img src="' + file.filename + '" /></div></div><div class="col-sm-9"><form class="form-horizontal"><div class="row"><div class="col-sm-3"><div class="form-group"><label>ID</label><input type="text" class="form-control" value="' + file.id + '" disabled></div></div><div class="col-sm-3 col-sm-offset-1"><div class="form-group"><label>Hochgeladen am</label><input type="text" class="form-control" value="' + file.date + '" disabled></div></div></div><div class="form-group"><label for="known_from">Dein Kommentar</label><textarea class="form-control imgcomment" data-id="' + file.id + '" rows="3"></textarea></div></form></div></div>').insertAfter('#dropzonerow');

	$('.imgrow.new').animate({opacity: 1}, 500);
	$('.imgrow.new .imgrow_bg').animate({opacity: 0}, 1200, function(){
		$(this).removeClass('new');
	});
}	

var myDropzone = {};

var chatInterval;
function chatinit() 
{

	chatInterval = setInterval(function(){
		$.postJSONsecure({
            module: "user",
            action: "chatinit",
            values: {
            	lastmsgid: function(){
            		if($('.chat_entry').length > 0)
            			return $('.chat_entry:last-child').attr('data-msgid');
            		else
            			return 0;
            	}
            }
        }, 
        function(rdata){
        	
        	if(rdata.count > 0)
        	{

        		if(!$('.nav-tabs li a[href="#messages"]').parents('li').hasClass('active'))
        		{
        			el = $('.msgcounter');
        			if(el.html() === '')
        			{
        				el.html(' (' + rdata.count + ')');
        			}
        			else
        			{
        				str = el.html().replace('(', '');
        				str = str.replace(')', '');
        				str = parseInt(str) + parseInt(rdata.count);

        				el.html(' (' +  str + ')');
        			}
        		}

        		$.each(rdata.messages, function(k, msg)
	    		{
	    			$('.chat').append('<div class="row chat_entry chat_relo" data-msgid="' + msg.id + '"><div class="col-xs-1"><img class="chat_userimg" src="data/img/_users/' + msg.profilepic + '"></div><div class="col-xs-12 col-sm-11"><div class="chat_time">' + msg.time + '</div><div class="chat_message">' + msg.text + '</div></div></div>');
	    		});
	    		$('.chat').scrollTop($('.chat')[0].scrollHeight);	
        	}
        });
	}, 1000);
}

$(document).ready(function(){
	//$('.chat').outerHeight(($(window).height() - ($('.chat').offset().top + $('.chatinput_wrap').outerHeight())) - 55);
	$('.chat').scrollTop($('.chat')[0].scrollHeight);

	$('.nav-tabs li:not(#logout) a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');

	  if($(this).attr('href') == '#messages')
	  {
	  	$('.msgcounter').html('');
	  	$('.chat').scrollTop($('.chat')[0].scrollHeight);
	  }
	});
	
	chatinit();

	//Dropzone for userimages (#gallery)
	myDropzone = $('#dropzoneform').dropzone({ 
		url: "upload",
		uploadMultiple: true,
		sending: function(file, xhr, formData) {
		    formData.append("action", "userimage");
		},
		init: function() {
	    	this.on("success", function(file, response) 
	    		{ 
	    			var rdata = jQuery.parseJSON(response);
	    			if(rdata.status == 1)
	    			{
	    				appendNewImage(rdata.file);
		    			setTimeout(function(){
		    				$('.dz-preview').fadeOut(500, function(){
		    					$(this).remove();
		    					$('.dz-message').show();
		    				});
		    				
		    			}, 1000);	
	    			}
	    			else
	    			{
	    				$('.dz-error-message').html('<span data-dz-errormessage>' + rdata.error + '</span>').css({opacity: 1, display: "block"});
	    			}
	    		});
	  	},
	  	error: function(file, response) {
	        var message;
	        if($.type(response) === "string")
	            message = response; //dropzone sends it's own error messages in string
	        else
	            message = response.message;
	        file.previewElement.classList.add("dz-error");
	        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
	        _results = [];
	        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	            node = _ref[_i];
	            _results.push(node.textContent = message);
	        }
	        return _results;
	    } 
	});

	/* PROFILE PIC */
	myDropzone = $('#dropzoneform_profilepic').dropzone({ 
		url: "upload",
		uploadMultiple: false,
		dictDefaultMessage: 'Profilbild hier ablegen',
		sending: function(file, xhr, formData) {
		    formData.append("action", "profilepic");
		},
		init: function() {
	    	this.on("success", function(file, response) 
	    		{ 
	    			var rdata = jQuery.parseJSON(response);
	    			if(rdata.status == 1)
	    			{
	    				$('.profilpic_container').html('<img class="profilepic" src="' + rdata.file.filename + '" />');

		    			setTimeout(function(){
		    				$('.dz-preview').fadeOut(500, function(){
		    					$(this).remove();
		    					$('.dz-message').show();
		    				});
		    				
		    			}, 1000);	
	    			}
	    			else
	    			{
	    				$('.dz-error-message').html('<span data-dz-errormessage>' + rdata.error + '</span>').css({opacity: 1, display: "block"});
	    			}
	    		});
	  	},
	  	error: function(file, response) {
	        var message;

	        if($.type(response) === "string")
	        	message = response; //dropzone sends it's own error messages in string
	        else
	            message = response.message;
	        file.previewElement.classList.add("dz-error");
	        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
	        _results = [];
	        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	            node = _ref[_i];
	            _results.push(node.textContent = message);
	        }
	        return _results;
	    }   
	});

	/* AUTOSAVE FIELDS */
	$('body').on('change textInput input', '.autosave, .imgcomment', function(){
		if ($(this).hasClass('update') === false && $(this).val() != $(this).attr('value')) {
			$(this).addClass('update');
			$(this).attr('value', $(this).val());
			autoSave({
				field:$(this).attr('id'),
				value: $(this).val()
			});	
		}
		
	});

	$('input[type="checkbox"].autosave').on('click', function(){
		if($(this).is(':checked'))
			val = 1;
		else
			val = 0;

		autoSave({
			field:$(this).attr('id'),
			value: val
		});
	});
	/* ----------- */

	$('#saveNewPassword').on('click', function(e){
		e.preventDefault();
		$.postJSONsecure({
            module: "user",
            action: "setNewPassword",
            values: {
            	password_new1: $('#password_new').val(),
				password_new2: $('#password_new2').val()
            }
        }, 
        function(rdata){
        	if(rdata.success == 1)
        	{
        		$('#password_new').val('');
        		$('#password_new2').val('');
        		notify('Passwort gespeichert');
        	}
        	else
        		notify(rdata.errmsg);
        });
		return false;
	});

	/* TAB IMAGES */

	$('#gallery').on('click', '.img-toolbar .remove', function(){
		$(this).parents('.imgrow').addClass('deleteThisImage');
		bootbox.confirm("Dieses Bild löschen?", function(result) {
			if(result === true) {

				$.postJSONsecure({
		            module: "user",
		            action: "deleteImage",
		            values: {
		            	dataId: $('.imgrow.deleteThisImage').attr('data-image-id')
		            }
		        }, 
		        function(rdata){
		        	if(rdata.success == 1) {
		        		$('.imgrow[data-image-id="' + rdata.dataId + '"]').remove();
		        	} else
		        		notify(rdata.errmsg);
		        });
			}
		}); 
	});

	/* ---------- */

	/* TAB MESSAGES */
	$('.chatinput_wrap form').on('submit', function(e){
		e.preventDefault();
		clearTimeout(chatInterval);

		data = {
			text: $(this).find('input').val(),
			recipientId: 0
		};

		$.postJSONsecure({
            module: "user",
            action: "sendMessage",
            values: data
        }, 
        function(rdata){
        	if(rdata.status == 1)
        	{
        		$('.chat').append('	<div class="row chat_entry chat_client" data-msgid="' + rdata.msgid + '">' +
				                        '<div class="col-xs-6 col-xs-offset-5 text-right chat_message">' +
				                        	'<div class="chat_time text-right">' +
					                        	rdata.username + ' | ' + rdata.time + '' +
					                        '</div>' +
				                        	data.text +
				                        '</div>' +
				                        '<div class="col-xs-1">' +
				                        	'<img class="chat_userimg" src="data/img/_users/' + rdata.profilepic + '">' +
				                        '</div>' +
				                    '</div>');
        		$('#chatinput').val('');
        		$('.chat').scrollTop($('.chat')[0].scrollHeight);
        		chatinit();
        	}
        });

        return false;
	});
});