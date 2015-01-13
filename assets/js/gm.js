$(function(){
    $(document).on('click', '.modal_close', function(){
        $(this).parents('.modal').hide();
    });
    $(document).on('click', '.add_character_gm, .edit_character_gm', function(){
        var playerId = $(this).parents("p").data("player-id");
        var playerData = getObjectData("Player", playerId);
        var characterData = getCharacterDataByPlayerId(playerId);
        var classId = $("#Character_classId").val();
        refreshCharacterLists(classId);
        fillCharacterForm(playerData, characterData);
        $('#edit_character_gm').show();
    });
    $(document).on('change', '#Character_classId', function(){
        var classId = $(this).val();
        refreshCharacterLists(classId);
    });
    $(document).on('click', '.but_character_save_gm', function(){
        var data = readCharacterForm();
        saveObject("Character", data);
    });
    $(document).on('click', '.add_faction_gm, .edit_faction_gm', function(){
        var factionId = $(this).parents("p").data("faction-id");
        var data = getObjectData("Faction", factionId);
        fillFactionForm(data);
        $('#edit_faction_gm').show();
    });
    $(document).on('click', '.but_faction_save_gm', function(){
        var data = readFactionForm();
        saveObject("Faction", data);
    });
    $(document).on('click', '.edit_province_gm', function(){
        var provinceId = $(this).parents("p").data("province-id");
        var data = getObjectData("Province", provinceId);
        fillProvinceForm(data);
        $('#edit_province_gm').show();
    });
    $(document).on('click', '.but_province_save_gm', function(){
        var data = readProvinceForm();
        saveObject("Province", data);
    });
});

function refreshCharacterLists(classId, traitId, ambitionId)
{
    var traits = getTraitsByClassId(classId);
    createList("Character_traitId", traitId, traits);
    var ambitions = getAmbitionsByClassId(classId);
    createList("Character_ambitionId", ambitionId, ambitions);
}
function fillCharacterForm(playerData, characterData)
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
    var playerData = new Array({'name': 'Character[playerId]', 'value': $("#player_id").val()});
    var characterData = $("#Character_form").serializeArray();
    $.extend(characterData, playerData);
    return characterData;
}

function fillFactionForm(factionData)
{
    if(factionData != null){
        $("#Faction_id").val(factionData.id);
        $("#Faction_name").val(factionData.name);
        $("#Faction_leaderId").val(factionData.leaderId);
        $("#Faction_color").val(factionData.color);
    }
}
function readFactionForm()
{
    return $("#Faction_form").serializeArray();
}

function fillProvinceForm(data)
{
    if(data != null){
        $("#Province_id").val(data.id);
        $("#Province_name").val(data.name);
        $("#Province_ownerId").val(data.ownerId);
    }
}
function readProvinceForm()
{
    return $("#Province_form").serializeArray();
}