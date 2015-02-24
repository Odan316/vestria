$(function(){
    $(document).on('click', '.modal_close', function(){
        $(this).parents('.modal').hide();
    });
    $(document).on('click', '.add_character_gm, .edit_character_gm', function(){
        var playerId = $(this).parents("p").data("player-id");
        var playerData = getObjectData("Player", playerId);
        var characterData = getCharacterDataByPlayerId(playerId);
        if(characterData == null){
            refreshCharacterLists(0, 1);
        }
        fillCharacterForm(playerData, characterData);
        $('#edit_character_gm').show();
    });
    $(document).on('change', '#Character_classId', function(){
        var classId = $(this).val();
        refreshCharacterLists(0, classId);
    });
    $(document).on('click', '.but_character_save_gm', function(){
        var data = readCharacterForm();
        saveObject("Character", data);
    });
    $(document).on('click', '.add_faction_gm, .edit_faction_gm', function(){
        var factionId = $(this).parents("p").data("faction-id");
        var data = getObjectData("Faction", factionId);
        if(data == null){
            refreshFactionLists(0, null);
        }
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

/**
 * Обновление списка персонажей
 *
 * @param {number} characterId
 * @param {number} classId
 * @param {number} traitId
 * @param {number} ambitionId
 */
function refreshCharacterLists(characterId, classId, traitId, ambitionId)
{
    var ambitions;
    var traits;
    if(!isEmpty(characterId)){
        ambitions = getAmbitionsByCharacterId(characterId);
        traits = getTraitsByCharacterId(characterId);
    } else {
        ambitions = getAmbitionsByClassId(classId);
        traits = getTraitsByClassId(classId);
    }
    createList("Character_ambitionId", ambitionId, ambitions);
    createList("Character_traitId", traitId, traits);
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
            refreshCharacterLists(characterData.id, characterData.classId, characterData.traitId, characterData.ambitionId);
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
    characterData = characterData.concat(playerData);
    return characterData;
}


/**
 * Обновление списка персонажей
 *
 * @param {number} factionId
 * @param {number} leaderId
 */
function refreshFactionLists(factionId, leaderId)
{
    var characters;
    characters = getObjectsList("Characters", {"criteria": {"factionId": null}});
    if(!isEmpty(factionId)){
        var leader = getObjectData("Character", leaderId);
        characters[leader.id] = leader.name;
    }
    createList("Faction_leaderId", leaderId, characters);
}
/**
 * @param {Object} factionData Faction info
 * @param {Number} factionData.id Faction ID
 * @param {String} factionData.name Faction name
 * @param {Number} factionData.leaderId ID of leader character
 * @param {String} factionData.color Faction color
 */
function fillFactionForm(factionData)
{
    if(factionData != null){
        $("#Faction_id").val(factionData.id);
        $("#Faction_name").val(factionData.name);
        $("#Faction_leaderId").val(factionData.leaderId);
        $("#Faction_color").val(factionData.color)
            .next(".colorPicker-picker").css({"background-color": factionData.color});
        refreshFactionLists(factionData.id, factionData.leaderId);
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