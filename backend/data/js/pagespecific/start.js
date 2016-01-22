var chatInterval;
function checkForMesssages() 
{
	chatInterval = setInterval(function(){
		$.postJSONsecure({
            module: "beuser",
            action: "checkNewMessages"
        }, 
        function(rdata){
        	notify(rdata.msg);
        });
	}, 1000);
}

var dropzoneform_beprofilepic = {};

$(document).ready(function(){
	$.tablesorter.themes.bootstrap = {
    // these classes are added to the table. To see other table classes available,
    // look here: http://getbootstrap.com/css/#tables
    table        : 'table table-striped',
    caption      : 'caption',
    // header class names
    header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
    sortNone     : '',
    sortAsc      : '',
    sortDesc     : '',
    active       : '', // applied when column is sorted
    hover        : '', // custom css required - a defined bootstrap style may not override other classes
    // icon class names
    icons        : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
    iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
    iconSortAsc  : 'glyphicon glyphicon-chevron-up', // class name added to icon when column has ascending sort
    iconSortDesc : 'glyphicon glyphicon-chevron-down', // class name added to icon when column has descending sort
    filterRow    : '', // filter row class; use widgetOptions.filter_cssFilter for the input/select element
    footerRow    : '',
    footerCells  : '',
    even         : '', // even row zebra striping
    odd          : ''  // odd row zebra striping
  };

	$("#clients").tablesorter({
    // this will apply the bootstrap theme if "uitheme" widget is included
    // the widgetOptions.uitheme is no longer required to be set
    theme : "bootstrap",

    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

    // widget code contained in the jquery.tablesorter.widgets.js file
    // use the zebra stripe widget if you plan on hiding any rows (filter widget)
    widgets : [ "uitheme"],
    sortReset: true
  });

	$('.nav-tabs li:not(#logout) a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	//Dropzone for profilepic
	dropzoneform_beprofilepic = $('#dropzoneform_beprofilepic').dropzone({ 
		url: "upload",
		maxFilesize: 1,
		uploadMultiple: true,
		dictDefaultMessage: 'Profilbild hier ablegen',
		sending: function(file, xhr, formData) {
		    formData.append("action", "beprofilepic");
		},
		init: function() {
	    	this.on("success", function(file, response) 
	    		{ 
	    			var rdata = jQuery.parseJSON(response);
	    			if(rdata.status == 1)
	    			{
	    				$('.profilepic_container').html('<img class="profilepic" src="' + rdata.file.filename + '" />');
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
	
	$('form#newPassword').on('submit', function(e){
		e.preventDefault();
		$.postJSONsecure({
            module: "beuser",
            action: "setNewPassword",
            values: {
            	password_new1: $('#password_new').val(),
				password_new2: $('#password_new2').val()
            }
        }, 
        function(rdata){
        	if(rdata.success == 1)
        	{
        		notify('Passwort gespeichert');
        		$('#password_new').val('');
        		$('#password_new2').val('');
        	}
        	else
        		notify(rdata.errmsg);
        });
		return false;
	});
});