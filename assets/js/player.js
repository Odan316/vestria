$(function() {
    refreshCharacterLists($("#Character_classId").val());
    $(document).on('click', '.but_character_save_new', function () {
        var characterData = readCharacterForm();
        saveCharacter(characterData);
    });
    $(document).on('change', '#Character_classId', function(){
        var classId = $(this).val();
        refreshCharacterLists(classId);
    });
    $(document).on('click', '#map_province_fill_4', function(){
        console.log("FINE");
    })
});

function readCharacterForm()
{
    return {
        'Character': {
            'playerId': $("#Character_playerId").val(),
            'name': $("#Character_name").val(),
            'classId': $("#Character_classId").val(),
            'traitId': $("#Character_traitId").val(),
            'ambitionId': $("#Character_ambitionId").val(),
            'provinceId': $("#Character_provinceId").val()
        }
    }
}

function refreshCharacterLists(classId, traitId, ambitionId)
{
    var traits = getTraitsByClassId(classId);
    createList("Character_traitId", traitId, traits);
    var ambitions = getAmbitionsByClassId(classId);
    createList("Character_ambitionId", ambitionId, ambitions);
}