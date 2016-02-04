notifyTimeout = null;
function notify(msg)
{
    if($('.notifier').hasClass('notifying'))
        clearTimeout(notifyTimeout);

    $('.notifier').html(msg).addClass('notifying').animate({bottom: 0}, 400, function(){
        notifyTimeout = setTimeout(function(){
            $('.notifier').animate({bottom: '-40px'}, 400, function(){
                $('.notifier').removeClass('notifying');
            });
        }, 3000);
    });
}


$(document).ready(function(){

	// performs all AJAX-Requests
	/*
	var data = {
        module: <<Classname>>,
        action: <<action name>>,
        values: {}
    }

    $.postJSONsecure(
        {
            module: <<Classname>>,
            action: <<action name>>,
            values: {}
        }, 
        function(rdata){

        }
    );
    );
	*/

    $.postJSONsecure = function(data, callback, options) {
        var defaultoptions = {
            type:       'POST',
            url:        'ajax/',
            data:       data,
            async:      data.async,
            dataType:   'json',
            success:    function(rdata) 
                        {
                            if (typeof callback == 'function') 
                                callback(rdata);
                            
                        },
            error:      function(rdata)
                        {
                            console.log('ERROR');
                            console.log(rdata);
                        }
        };

        if(typeof options != 'undefined')
            $.extend(true, defaultoptions, options);

        var ajax = $.ajax(defaultoptions);
    };

    $(document).keyup(function(e) {
        if(e.which == 27){
            if($('#overlay').hasClass('visible'))
                closeOverlay($('#overlay'));
        }
    });

    //Allow only numeric input
    $(".onlynumeric").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});