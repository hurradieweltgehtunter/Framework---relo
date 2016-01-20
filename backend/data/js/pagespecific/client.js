function resizeChat() 
{
    $('.chat').height($(window).height() - $('.chat').offset().top - $('.chatinput_wrap').outerHeight() - 66);
    $('.chat').scrollTop($('.chat')[0].scrollHeight);
}

/* refreshes the chat, fetching new messages */
var chatinitInterval;
function chatinit() 
{
    chatinitInterval = setInterval(function(){
        $.postJSONsecure({
            module: "beuser",
            action: "chatinit",
            values: {
                lastmsgid: function(){
                    if($('.chat_entry').length > 0)
                        return $('.chat_entry:last-child').attr('data-msgid');
                    else
                        return 0;
                },
                clientid: $('.tab-pane#home').attr('data-clientid')
            }
        }, 
        function(rdata){
            
            if(rdata.count > 0)
            {
                $.each(rdata.messages, function(k, msg)
                {
                    $('.chat').append('<div class="row chat_entry chat_client" data-msgid="' + msg.id + '"><div class="col-xs-1"><img class="chat_userimg" src="../data/img/_users/' + msg.profilepic + '"></div><div class="col-xs-12 col-sm-11"><div class="chat_time">' + msg.time + '</div><div class="chat_message">' + msg.text + '</div></div></div>');
                });
                $('.chat').scrollTop($('.chat')[0].scrollHeight);   
            }
        });
    }, 1000);
}

$(document).ready(function(){
    $(window).resize(function(){
        resizeChat();
    });

    chatinit();

	$('.nav-tabs li[role="presentation"] a').click(function (e) {
	  	e.preventDefault();
	  	$(this).tab('show');

        if($(this).attr('href') == '#messages')
        {
            resizeChat();
        }
        return false;
	});


	/* TAB MESSAGES */
    $('.chatinput_wrap form').on('submit', function(e){
        e.preventDefault();
        if ($(this).find('input').val() === '')
            return false;
        
        data = {
            text: $(this).find('input').val(),
            recipientId: clientId
        };

        $.postJSONsecure({
            module: "beuser",
            action: "sendMessage",
            values: data
        }, 
        function(rdata){
            if(rdata.status == 1)
            {
                $('.chat').append(' <div class="row chat_entry chat_relo" data-msgid="'  + rdata.msgid + '">'+
                                        
                                        '<div class="col-xs-12 col-sm-11">'+
                                            '<div class="chat_time">'+
                                                rdata.time +
                                            '</div>'+
                                            '<div class="chat_message">'+
                                                data.text +
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-xs-1">'+
                                            '<img class="chat_userimg" src="../data/img/_user/' + rdata.profilepic + '">'+
                                        '</div>'+

                                    '</div>');
                $('#chatinput').val('');
                $('.chat').scrollTop($('.chat')[0].scrollHeight);
            }
        });

        return false;
    });

    $('.erplink').on('click', function(){
        if($('#erp_link').val() !== '')
        {
            $(this).attr('href', $('#erp_link').val());
        }
        else
            return false;
    });

    $('#makeAdmin').on('click', function(){
        $.postJSONsecure({
            module: "beuser",
            action: "MakeAdmin",
            values: 
            {
                clientId: clientId
            }
        }, 
        function(rdata){
            $('#makeAdmin').html(rdata.txt);
            notify();
        });
        return false;
    });

    $('#sendNewPassword').on('click', function(){
        $.postJSONsecure({
            module: "user",
            action: "sendNewPassword",
            values: 
            {
                clientId: clientId
            }
        }, 
        function(rdata){

        });
        return false;
    });



    /* AUTOSAVE FIELDS */
    $('#account .autosave').bind('change textInput input', function(){
        if(!$(this).hasClass('update'));
        {
            $(this).addClass('update');
            autoSave();
        }
    });

    $('#home .autosave').bind('change textInput input', function(){
        if(!$(this).hasClass('update'));
        {
            $(this).addClass('update');
            autoSave($('#home').attr('data-clientid'));
        }
    });
});
