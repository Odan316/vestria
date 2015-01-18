$(function() {
    $(document).on('click', '#map_province_fill_4', function(){
        console.log("FINE");
    });
    $(document).on('change', '.reguest_position', function(){
        var actionId = $(this).val();
        paramsCode = getActionParametersCode(actionId);
        console.log($(this).next());
        console.log(paramsCode);
        $(this).next(".request_params").html(paramsCode);
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