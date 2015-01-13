<?php
/**
 * @var $this GameController
 * @var $players Users[] Список игроков, с племенами
 * @var $classesList [] Список доступных классов для персонажей
 * @var $provincesList [] Список доступных провинций
 */
?>
<div id="gm_players_list">
    <h2>Игроки</h2>
    <?= CHtml::hiddenField( "player_id" ) ?>
    <?php foreach ($players as $player) { ?>
        <p data-player-id="<?= $player->id ?>">
            <?= $player->person->nickname ?>
            <?php $character = $this->game->getCharacterByPlayerId( $player->id ); ?>
            <?php if ( ! empty( $character )) { ?>
                <span>(<?= $character->getName(); ?>
                    - <?= ( $character->getFaction() ? $character->getFaction()->getName() : "Нет фракции" ) ?>)</span>
                <?php
                $this->widget( 'bootstrap.widgets.TbButton', [
                    'label'       => 'Изменить персонажа',
                    'type'        => 'secondary',
                    'size'        => 'small',
                    "htmlOptions" => [
                        'class' => "edit_character_gm"
                    ]
                ] );
                ?>
            <?php } else { ?>
                <?php
                $this->widget( 'bootstrap.widgets.TbButton', [
                    'label'       => 'Добавить персонажа',
                    'type'        => 'secondary',
                    'size'        => 'small',
                    "htmlOptions" => [
                        'class' => "add_character_gm"
                    ]
                ] );
                ?>
            <?php } ?>
        </p>
    <?php } ?>
</div>
<div id="gm_factions_list">
    <h2>Фракции</h2>
    <?= CHtml::hiddenField( "faction_id" ) ?>
    <?php foreach ($this->game->getFactions() as $faction) { ?>
        <p data-faction-id="<?= $faction->getId() ?>">
            <?= $faction->getName() ?>
            <span>(<?=
                ( $faction->getLeader() ? $faction->getLeader()->getName() : '<span class="alert-error">Нет лидера</span>' )
                ?>)</span>
            <?php
            $this->widget( 'bootstrap.widgets.TbButton', [
                'label'       => 'Редактировать фракцию',
                'type'        => 'secondary',
                'size'        => 'small',
                "htmlOptions" => [
                    'class' => "edit_faction_gm"
                ]
            ] );
            ?>
        </p>
    <?php } ?>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Добавить фракцию',
        'type'        => 'secondary',
        'size'        => 'small',
        "htmlOptions" => [
            'class' => "add_faction_gm"
        ]
    ] );
    ?>
</div>

<div id="edit_character_gm" class="modal">
    <div class="modal_close"></div>
    <?= CHtml::beginForm("/", "POST", ["id" => "Character_form"]); ?>
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
    <?= CHtml::endForm(); ?>
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
<div id="edit_faction_gm" class="modal">
    <div class="modal_close"></div>
    <?= CHtml::beginForm("/", "POST", ["id" => "Faction_form"]); ?>
    <h2>Фракция</h2>
    <?= CHtml::hiddenField( "Faction[id]" ) ?>
    <label for="Faction_name">Название:</label>
    <?= CHtml::textField( "Faction[name]" ) ?>
    <br/>
    <label for="Faction_leaderId">Лидер:</label>
    <?= CHtml::dropDownList( "Faction[leaderId]", 0, CHtml::listData($this->game->getCharacters(true), "id", "name") ) ?>
    <br/>
    <label for="Faction_color">Цвет:</label>
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