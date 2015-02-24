/**
 * Created by onag on 07.01.15.
 */

function getObjectsList(modelName, data)
{
    var list = [];

    $.ajax({
        type: "POST",
        async: false,
        context: this,
        url: window.url_root + "ajax/get"+modelName+"List",
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data != null) {
                for(var i = 0; i < data.length; i++)
                    list[data[i].id] = data[i].name;
            }
        }
    });

    return list;
}

/**
 *
 * @param {string} className - i.e. "Character"
 * @param {number} id - Object ID
 * @returns {Object}
 */
function getObjectData(className, id) {
    var data = null;
    if (id) {
        $.ajax({
            type: "POST",
            async: false,
            context: this,
            url: window.url_root + "ajax/get"+className+"Data",
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
        url: window.url_root+"ajax/save"+className,
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
        url: window.url_root + "ajax/getCharacterDataByPlayerId",
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

function getTraitsByCharacterId(id)
{
    var data = {"id":id};
    return getObjectsList("Traits", data);
}
function getTraitsByClassId(id)
{
    var data = {"classId":id};
    return getObjectsList("Traits", data);
}

function getAmbitionsByCharacterId(id)
{
    var data = {"id":id};
    return getObjectsList("Ambitions", data);
}
function getAmbitionsByClassId(id)
{
    var data = {"classId":id};
    return getObjectsList("Ambitions", data);
}