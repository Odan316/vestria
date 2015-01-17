$(function() {
    $(document).on('click', '#map_province_fill_4', function(){
        console.log("FINE");
    });
    $(document).on('click', '.reguest_position', function(){
        var actionId = $(this).val();
        paramsCode = getActionParametersCode(actionId);
        $(this).next(".request_params").append($(paramsCode));
    });
});

function getActionParametersCode(actionId)
{
    var code = "";
    if (id) {
        $.ajax({
            type: "POST",
            async: false,
            context: this,
            url: window.url_root + "ajax/getActionParametersCode",
            dataType: 'json',
            data: {
                "id": actionId
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