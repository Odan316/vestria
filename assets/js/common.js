/**
 * Created by onag on 07.01.15.
 */

function getPlayerData(playerId)
{
    var playerData = null;

    $.ajax({
        type: "POST",
        async: false,
        context: this,
        url: window.url_root + "game/getPlayerData",
        dataType: 'json',
        data: {
            "playerId": playerId
        },
        success: function (data) {
            if (data != null) {
                playerData = data;
            }
        }
    });

    return playerData;
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
function saveCharacter(characterData)
{
    $.ajax({
        type: "POST",
        async: false,
        url: window.url_root+"game/saveCharacter",
        dataType: 'json',
        data: characterData,
        success: function(data){
            if(data == 1){
                location.reload();
            }
            else{
                alert("Не удалось сохранить персонажа");
            }
        }
    });
}

function getFactionData(factionId) {
    var factionData = null;
    if (factionId) {
        $.ajax({
            type: "POST",
            async: false,
            context: this,
            url: window.url_root + "game/getFactionData",
            dataType: 'json',
            data: {
                "factionId": factionId
            },
            success: function (data) {
                if (data != null) {
                    factionData = data;
                }
            }
        });
    }

    return factionData;
}
function saveFaction(factionData)
{
    $.ajax({
        type: "POST",
        async: false,
        url: window.url_root+"game/saveFaction",
        dataType: 'json',
        data: factionData,
        success: function(data){
            if(data == 1){
                location.reload();
            }
            else{
                alert("Не удалось сохранить фракцию");
            }
        }
    });
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