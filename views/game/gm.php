<?php
/**
 * @var $this GameController
 * @var $players Users[] Список игроков, с племенами
 * @var $game Game
 * @var $classes_list []
 */
$this->setPageTitle( $this->game_title . ' - Кабинет Ведущего' );
?>
<div id="gm_players_list">
    <h2>Игроки</h2>
    <?php foreach ($players as $player) {
        $has_char = false;
        ?>
        <p>
            <?= $player->person->nickname ?>
            <?php foreach ($game->getCharacters() as $character) { ?>
                <?php if ($character->getPlayer()->id == $player->id) {
                    $has_char = true;
                    ?>
                    <?= $character->getName() ?>
                    <button id="edit_character">Изменить персонажа</button>
                <?php } ?>
            <?php } ?>
            <?php if ( ! $has_char) { ?>
                <button id="add_character">Добавить персонажа</button>
            <?php } ?>
        </p>
    <?php } ?>
</div>

<div class="edit_char">
    <?=CHtml::hiddenField("player_id")?>
    <?=CHtml::hiddenField("character_id")?>
    <div class="b_new_char_set">
    </div>
    <p>Имя:</p>
    <?=CHtml::textField("character_name")?>
    <p>Класс:</p>
    <?=CHtml::dropDownList("character_class", 0, $classes_list)?>
    <p>Черта:</p>
    <?=CHtml::dropDownList("character_trait", 0, [])?>
    <p>Амбиция:</p>
    <?=CHtml::dropDownList("character_ambition", 0, [])?>
    <div class="b_new_tribe_set">
        <p>Координаты первой общины:</p>
        x:<?=CHtml::textField("tribe_start_x", "", array("style" => "width:60px;"))?>
        y:<?=CHtml::textField("tribe_start_y", "", array("style" => "width:60px;"))?>
    </div>
    <?
    $this->widget('bootstrap.widgets.TbButton',array(
        'label' => 'Сохранить',
        'type' => 'secondary',
        'size' => 'small',
        "htmlOptions" => array(
            'class' => "but_gm_tribe_save"
        )
    ));
    ?>
</div>