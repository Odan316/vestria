$(function(){
    refreshLists();
    $(document).on('click', '.modal_close', function(){
        $(this).parents('.modal').hide();
    });
    $(document).on('click', '.add_character', function(){
        var playerId = $(this).parents("p").data("player-id");
        var playerData = getPlayerData(playerId);
        var characterData = getCharacterDataByPlayerId(playerId);
        fillCharacterFrom(playerData, characterData);
        $('#edit_character').show();
    });
    $(document).on('change', '#class_id', function(){
        refreshLists();
    });
    $(document).on('click', '.but_gm_character_save', function(){
        var characterData = readCharacterForm();
        saveCharacter(characterData);
        $(this).parents('.b_gm_tribe_info').find('.but_gm_tribe_edit').show();
    })
});

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
function refreshLists()
{
    var classId = $("#class_id").val();
    console.log(classId);
    var traits = getTraitsByClassId(classId);
    createList("trait_id", 0, traits);
    var ambitions = getAmbitionsByClassId(classId);
    createList("ambition_id", 0, ambitions);
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
function fillCharacterFrom(playerData, characterData)
{
    if(playerData != null){
        $("#character_player").text(playerData.nickname);
        $("#player_id").val(playerData.id);
        if(characterData != null){

        }
    } else {
        $("#player_id").val("");
        $("#character_player").html('<span class="alert">Игрок не найден!</span>');
    }
}
function readCharacterForm()
{
    return {
        'playerId': $("#player_id").val(),
        'name': $("#character_name").val(),
        'class_id': $("#class_id").val(),
        'traitIdd': $("#trait_id").val(),
        'ambitionId': $("#ambition_id").val()
    }
}

function saveCharacter(characterData)
{
    $.ajax({
        type: "POST",
        async: false,
        url: window.url_root+"game/GMSaveCharacter",
        dataType: 'json',
        data: characterData,
        success: function(data){
            if(data.result == true){
                location.reload();
            }
            else{
                alert("Не удалось сохранить персонажа");
            }
        }
    });
}
