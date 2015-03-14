$(function() {
    jQuery.extend(jQuery.validator.messages, {
        required: jQuery.validator.format(""),
        max: jQuery.validator.format(""),
        min: jQuery.validator.format("")
    });
    jQuery.validator.addClassRules("request_parameter", {
        required: true
    });
    $(document).on("keyup", "input", function(){
        $("#requests_form").validate().element(this);
    });
    $("#requests_form").submit(function(){
        return false;
    });
    $(document).on('click', '#map_province_fill_4', function(){
        //console.log("FINE");
    });
    $(document).on('click', ".add_request_position", function(){
        var positionCode = getRequestPositionCode();
        positionCode = $(positionCode);
        var positionsCounter = $(".request_block").length + 1;
        positionCode.find(".position_num").text(positionsCounter);
        $(this).before(positionCode);
    });
    $(document).on('click', ".delete_request_position", function(){
        var positionId = $(this).parent().find(".positionId").val();
        if(positionId != ''){
            deletePosition(positionId);
        }
        $(this).parent().detach();
    });
    $(document).on('change', '.reguest_position', function(){
        var actionId = $(this).val();
        paramsCode = getActionParametersCode(actionId);
        $(this).parents(".request_block").find(".request_params").html(paramsCode);
    });
    $(document).on('click', '.but_request_save', function(){
        saveRequest();
    });
});

function getActionParametersCode(actionId)
{
    var code = "";
    if (actionId != "" && actionId != 0) {
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

function getRequestPositionCode()
{
    var code = "";
    $.ajax({
        type: "POST",
        async: false,
        context: this,
        url: window.url_root + "ajax/getRequestPositionCode",
        dataType: 'html',
        data: {},
        success: function (response) {
            if (response != null) {
                code = response;
            }
        }
    });
    return code;
}

function deletePosition(id)
{
    if (id) {
        $.ajax({
            type: "POST",
            async: false,
            context: this,
            url: window.url_root + "ajax/deletePosition",
            dataType: 'html',
            data: {
                "id": id
            },
            success: function (response) { }
        });
    }
}

function saveRequest()
{
    var validator = $("#requests_form").validate();
    $(".request_parameter").each(function() {
        validator.element(this);
    });
    if(isEmpty(validator.numberOfInvalids())){
        var data = getRequestData();
        saveObject("Request", data);
    }
}

function getRequestData()
{
    var data = {
        'positions': []
    };
    $('.request_block').each(function(){
        var position = {
            'id': $(this).find('.positionId').val(),
            'actionId': $(this).find('.reguest_position').val(),
            'parameters': {}
        };
        $(this).find('.request_parameter').each(function(){
            position.parameters[$(this).attr('name')] = $(this).val();
        });
        data.positions[data.positions.length] = position;
    });

    return data;
}