$(function() {
    refreshCharacterLists($("#Character_classId").val());
    $(document).on('click', '.but_character_save_new', function () {
        var data = readCharacterForm();
        saveObject("Character", data);
    });
    $(document).on('change', '#Character_classId', function(){
        var classId = $(this).val();
        refreshCharacterLists(classId);
    });
});

function readCharacterForm()
{
    return $("#Character_form").serializeArray();
}

function refreshCharacterLists(classId, traitId, ambitionId)
{
    var traits = getTraitsByClassId(classId);
    createList("Character_traitId", traitId, traits);
    var ambitions = getAmbitionsByClassId(classId);
    createList("Character_ambitionId", ambitionId, ambitions);
}