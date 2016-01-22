/**
AUTOSAVE MODULE
*/
saveTimeout = null;
function autoSave(clientid)
{
    if($('.update').length > 0)
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
                    value: $(this).val()
                };
                imgcomments.push(comment);
            });
            
            data = {
                user: fields, 
                files: imgcomments
            };

            $.postJSONsecure({
                module: "beuser",
                action: "saveData",
                clientid: function(){
                    if(clientid !== undefined)
                        return clientid;
                    else
                        return undefined;
                },
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
}

searchTimeout = null;
function clientsearch(needle)
{
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(function(){
        $('.nav_clientsearch .ajax-loader').show();
        $.postJSONsecure({
            module: "search",
            action: "search",
            values: {
                needle: needle
            }
        }, 
        function(rdata){
            $('.nav-tabs a[href="#overview"]').tab('show');
            table = $('table#clients');

            table.find('tbody tr').remove();

            $.each(rdata.result, function(k, client){
                row = '<tr onclick="document.location=\'client/' + client.id + '\'"><td>' + client.firstname + '</td><td>' + client.lastname + '</td><td>' + client.city + '</td><td>' + client.phone + '</td><td>' + client.mail + '</td><td class="notifier"></td>';
                
                table.find('tbody').append(row);
            });

            $("table#clients").trigger("updateAll", [ true ]);

            $('.nav_clientsearch .ajax-loader').hide();
        });
    }, 500);
}

notifyTimeout = null;
function notify(msg)
{
    if(msg === 'undefined')
        msg = 'Änderungen gespeichert';

    if($('#notifier').hasClass('notifying'))
        clearTimeout(notifyTimeout);

    $('#notifier').html(msg).addClass('notifying').animate({bottom: 0}, 400, function(){
        notifyTimeout = setTimeout(function(){
            $('#notifier').animate({bottom: '-40px'}, 400, function(){
                $('#notifier').removeClass('notifying');
            });
        }, 3000);
    });
}

$(document).ready(function(){

/**
GENERAL SPECIFICATIONS, DO NOT EDIT
*/

    // performs all AJAX-Requests
    /*
    var data = {
        module: <<Classname>>,
        action: <<action name>>,
        values: {}
    }

    */
    $.postJSONsecure = function(data, callback, options) {
                        
        var defaultoptions = {
            type:       'POST',
            url:        'ajax/',
            data:       data,
            async:      true,
            dataType:   'json',
            success:    function(rdata) 
                        {
                            if(rdata.forward !== '' && rdata.forward !== undefined)
                                window.location = rdata.forward;

                            if(rdata.reload !== undefined)
                                location.reload();

                            if (typeof callback == 'function') 
                                callback(rdata);
                            
                        },
            error:      function(XMLHttpRequest, statusText, errorThrown) {
                            
                            console.log(statusText);
                            console.log(" - ");
                            console.log(XMLHttpRequest.statusText);
                            console.log(" - ");
                            console.log(errorThrown);
                        }
        };

        if(typeof options != 'undefined')
            $.extend(true, defaultoptions, options);

        var ajax = $.ajax(defaultoptions);
    };


    /**
    AUTOFILL MODULE
    add class .autofill to inputs/textareas to search in the DB (table 'text_blocks') for text proposals. 
    add "data-autofill" and insert the id of the textblock given in the DB
    Text proposals will only be inserted if the field is empty (to avoid overriding)
    */

    var autofills =  [];
    $('.autofill').each(function(){
        if($.inArray($(this).attr('data-autofill'), autofills) == -1)
            autofills.push($(this).attr('data-autofill'));            
    });

    if($('.autofill').length)
    {
        $.postJSONsecure({
            module: 'autofill',
            action: 'getAll',
            fields: autofills
        }, function(rdata){
            $('.autofill').each(function(){
                if($(this).val() === '')
                    $(this).val(rdata[$(this).attr('data-autofill')].text);
            });
        });    
    }
    

    /**
    END OF AUTOFILL MODULE
    */

    /* AUTOSAVE FIELDS */
    $('.autosave, .imgcomment').bind('change textInput input', function(){
        $(this).addClass('update');
        autoSave();
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

    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });


    /* CLIENTSEARCH */
    $('.nav_clientsearch form').on('submit', function(e){
        e.preventDefault();
        return false;
    });

    $('#clientsearch').bind('change textInput input', function(){

        clientsearch($('#clientsearch').val());
    });

});

