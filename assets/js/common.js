/**
 * Created by onag on 07.01.15.
 */

function getObjectData(className, id) {
    var data = null;
    if (id) {
        $.ajax({
            type: "POST",
            async: false,
            context: this,
            url: window.url_root + "game/get"+className+"Data",
            dataType: 'json',
            data: {
                "id": id
            },
            success: function (response) {
                if (response != null) {
                    data = response;
                }
            }
        });
    }

    return data;
}
function saveObject(className, data)
{
    $.ajax({
        type: "POST",
        async: false,
        url: window.url_root+"game/save"+className,
        dataType: 'json',
        data: data,
        success: function(data){
            if(data == 1){
                location.reload();
            }
            else{
                alert("Не удалось сохранить!");
            }
        }
    });
}

function getCharacterDataByPlayerId(playerId)
{
    var characterData = null;

    $.ajax({
        type: "POST",
        async: false,
        context: this,
        url: window.url_root + "game/getCharacterDataByPlayerId",
        dataType: 'json',
        data: {
            "playerId": playerId
        },
        success: function (data) {
            if (data != null) {
                characterData = data;
            }
        }
    });

    return characterData;
}

function getTraitsByClassId(classId)
{
    var list = [];

    $.ajax({
        type: "POST",
        async: false,
        context: this,
        url: window.url_root + "game/getTraitsByClassId",
        dataType: 'json',
        data: {
            "classId": classId
        },
        success: function (data) {
            if (data != null) {
                for(var i = 0; i < data.length; i++)
                    list[data[i].id] = data[i].name;
            }
        }
    });

    return list;
}
function getAmbitionsByClassId(classId)
{
    var list = [];

    $.ajax({
        type: "POST",
        async: false,
        context: this,
        url: window.url_root + "game/getAmbitionsByClassId",
        dataType: 'json',
        data: {
            "classId": classId
        },
        success: function (data) {
            if (data != null) {
                for(var i = 0; i < data.length; i++)
                    list[data[i].id] = data[i].name;
            }
        }
    });

    return list;
}