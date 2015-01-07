$(function(){
    $(document).on('click', '.modal_close', function(){
        $(this).parents('.modal').hide();
    });
    $(document).on('click', '.add_character_gm, .edit_character_gm', function(){
        var playerId = $(this).parents("p").data("player-id");
        var playerData = getPlayerData(playerId);
        var characterData = getCharacterDataByPlayerId(playerId);
        fillCharacterFrom(playerData, characterData);
        $('#edit_character_gm').show();
    });
    $(document).on('change', '#Character_classId', function(){
        var classId = $(this).val();
        refreshCharacterLists(classId);
    });
    $(document).on('click', '.but_character_save_gm', function(){
        var characterData = readCharacterForm();
        saveCharacter(characterData);
    });
    $(document).on('click', '.add_faction_gm, .edit_faction_gm', function(){
        var factionId = $(this).parents("p").data("faction-id");
        console.log(factionId);
        var factionData = getFactionData(factionId);
        fillFactionFrom(factionData);
        $('#edit_faction_gm').show();
    });
    $(document).on('click', '.but_faction_save_gm', function(){
        var factionData = readFactionForm();
        saveFaction(factionData);
    });
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
function refreshCharacterLists(classId, traitId, ambitionId)
{
    var traits = getTraitsByClassId(classId);
    createList("Character_traitId", traitId, traits);
    var ambitions = getAmbitionsByClassId(classId);
    createList("Character_ambitionId", ambitionId, ambitions);
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
        $("#character_player_name").text(playerData.nickname);
        $("#player_id").val(playerData.id);
        if(characterData != null){
            $("#Character_id").val(characterData.id);
            $("#Character_name").val(characterData.name);
            $("#Character_classId").val(characterData.classId);
            $("#Character_provinceId").val(characterData.provinceId);
            refreshCharacterLists(characterData.classId, characterData.traitId, characterData.ambitionId);
        }
    } else {
        $("#player_id").val("");
        $("#character_player_name").html('<span class="alert-error">Игрок не найден!</span>');
    }
}
function readCharacterForm()
{
    return {
        'playerId': $("#player_id").val(),
        'Character': {
            'id': $("#Character_id").val(),
            'name': $("#Character_name").val(),
            'classId': $("#Character_classId").val(),
            'traitId': $("#Character_traitId").val(),
            'ambitionId': $("#Character_ambitionId").val(),
            'provinceId': $("#Character_provinceId").val()
        }
    }
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
function fillFactionFrom(factionData)
{
    if(factionData != null){
        $("#Faction_id").val(factionData.id);
        $("#Faction_name").val(factionData.name);
        $("#Faction_leaderId").val(factionData.leaderId);
    }
}
function readFactionForm()
{
    return {
        'Faction': {
            'id': $("#Faction_id").val(),
            'name': $("#Faction_name").val(),
            'leaderId': $("#Faction_leaderId").val()
        }
    }
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