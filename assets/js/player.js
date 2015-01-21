$(function() {
    $(document).on('click', '#map_province_fill_4', function(){
        console.log("FINE");
    });
    $(document).on('change', '.reguest_position', function(){
        var actionId = $(this).val();
        paramsCode = getActionParametersCode(actionId);
        $(this).parents(".request_block").find(".request_params").html(paramsCode);
    });
    $(document).on('click', '.but_request_save', function(){
        var data = getRequestData();
        saveObject("Request", data);
    });
});

function getActionParametersCode(actionId)
{
    var code = "";
    if (actionId) {
        $.ajax({
            type: "POST",
            async: false,
            context: this,
            url: window.url_root + "ajax/getActionParametersCode",
            dataType: 'html',
            data: {
                "actionId": actionId
            },
            success: function (response) {
                if (response != null) {
                    code = response;
                }
            }
        });
    }
    return code;
}

function getRequestData()
{

    var data = {
        'positions': []
    };
    $('.request_block').each(function(){
        var position = {
            'actionId': $(this).find('.reguest_position').val()
        };
        $(this).find('.request_parameter').each(function(){
            position[$(this).attr('name')] = $(this).val();
        });
        data.positions[data.positions.length] = position;
    });

    return data;
}