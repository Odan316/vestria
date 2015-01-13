<?php
/**
 * @var $this GameController
 * @var $players Users[] Список игроков, с племенами
 * @var $classesList [] Список доступных классов для персонажей
 * @var $provincesList [] Список доступных провинций
 */
$this->setPageTitle( $this->gameTitle . ' - Кабинет Ведущего' );
?>

<?php
$this->widget(
    'bootstrap.widgets.TbTabs',
    [
        'type' => 'pills',
        'tabs' => [
            [
                'label' => 'Игроки и фракции',
                'content' => $this->renderPartial("gm/players", ["players" => $players], 1),
                'active' => true
            ],
            ['label' => 'Провинции', 'content' => $this->renderPartial("gm/map", [], 1)],
        ],
    ]
);
?>

<div class="clearfix"></div>

<div id="edit_character_gm" class="modal">
    <div class="modal_close"></div>
    <h2>Персонаж</h2>
    <?= CHtml::hiddenField( "Character[id]" ) ?>
    <label>Игрок:</label>
    <p id="character_player_name"></p>
    <label for="Character_name">Имя:</label>
    <?= CHtml::textField( "Character[name]" ) ?>
    <br/>
    <label for="Character_classId">Класс:</label>
    <?= CHtml::dropDownList( "Character[classId]", 0, $classesList ) ?>
    <br/>
    <label for="Character_traitId">Черта:</label>
    <?= CHtml::dropDownList( "Character[traitId]", 0, [ ] ) ?>
    <br/>
    <label for="Character_ambitionId">Амбиция:</label>
    <?= CHtml::dropDownList( "Character[ambitionId]", 0, [ ] ) ?>
    <br/>
    <label for="Character_provinceId">Провинция:</label>
    <?= CHtml::dropDownList( "Character[provinceId]", 0, $provincesList ) ?>
    <br/>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Сохранить',
        'type'        => 'secondary',
        'size'        => 'small',
        "htmlOptions" => [
            'class' => "but_character_save_gm"
        ]
    ] );
    ?>
</div>
<div id="edit_faction_gm" class="modal ff">
    <?= CHtml::beginForm("/", "POST", ["id" => "Faction_form"]); ?>
    <div class="modal_close"></div>
    <h2>Фракция</h2>
    <?= CHtml::hiddenField( "Faction[id]" ) ?>
    <label for="Faction_name">Название:</label>
    <?= CHtml::textField( "Faction[name]" ) ?>
    <br/>
    <label for="Faction_leaderId">Лидер:</label>
    <?= CHtml::dropDownList( "Faction[leaderId]", 0, CHtml::listData($this->game->getCharacters(true), "id", "name") ) ?>
    <br/>
    <label for="Faction_leaderId">Цвет:</label>
    <?= $this->widget('ext.yii-colorpicker.ColorPicker', [
        'name' => 'Faction[color]'
    ], 1);
    ?>
    <?= CHtml::endForm(); ?>
    <br/>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Сохранить',
        'type'        => 'secondary',
        'size'        => 'small',
        "htmlOptions" => [
            'class' => "but_faction_save_gm"
        ]
    ] );
    ?>
</div>