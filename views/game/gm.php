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
    <?=CHtml::hiddenField("character_id")?>
    <div class="b_new_char_set">
    </div>
    <label>Игрок:</label>
    <p id="character_player"></p>
    <label for="character_name">Имя:</label>
    <?=CHtml::textField("character_name")?>
    <br/>
    <label for="class_id">Класс:</label>
    <?=CHtml::dropDownList("class_id", 0, $classesList)?>
    <br/>
    <label for="trait_id">Черта:</label>
    <?=CHtml::dropDownList("trait_id", 0, [])?>
    <br/>
    <label for="ambition_id">Амбиция:</label>
    <?=CHtml::dropDownList("ambition_id", 0, [])?>
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