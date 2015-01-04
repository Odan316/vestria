<?php
/**
 * @var $this GameController
 * @var $players Users[] Список игроков, с племенами
 * @var $classesList []
 */
$this->setPageTitle( $this->gameTitle . ' - Кабинет Ведущего' );
?>
<div id="gm_players_list">
    <h2>Игроки</h2>
    <?=CHtml::hiddenField("player_id")?>
    <?php foreach ($players as $player) {
        $hasChar = false;
        ?>
        <p data-player-id="<?= $player->id?>">
            <?= $player->person->nickname ?>
            <?php foreach ($this->game->getCharacters() as $character) { ?>
                <?php if ($character->getPlayer()->id == $player->id) {
                    $hasChar = true;
                    ?>
                    <span>(<?= $character->getName() ?>)</span>
                    <button class="edit_character">Изменить персонажа</button>
                <?php } ?>
            <?php } ?>
            <?php if ( ! $hasChar) { ?>
                <button class="add_character">Добавить персонажа</button>
            <?php } ?>
        </p>
    <?php } ?>
</div>

<div id="edit_character" class="modal">
    <div class="modal_close"></div>
    <h2>Персонаж</h2>
    <?=CHtml::hiddenField("Character[id]")?>
    <div class="b_new_char_set">
    </div>
    <label>Игрок:</label>
    <p id="character_player_name"></p>
    <label for="Character_name">Имя:</label>
    <?=CHtml::textField("Character[name]")?>
    <br/>
    <label for="Character_classId">Класс:</label>
    <?=CHtml::dropDownList("Character[classId]", 0, $classesList)?>
    <br/>
    <label for="Character_traitId">Черта:</label>
    <?=CHtml::dropDownList("Character[traitId]", 0, [])?>
    <br/>
    <label for="Character_ambitionId">Амбиция:</label>
    <?=CHtml::dropDownList("Character[ambitionId]", 0, [])?>
    <br/>
    <?
    $this->widget('bootstrap.widgets.TbButton',[
        'label' => 'Сохранить',
        'type' => 'secondary',
        'size' => 'small',
        "htmlOptions" => [
            'class' => "but_gm_character_save"
        ]
    ]);
    ?>
</div>