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
});