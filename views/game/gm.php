<?php
/**
 * @var $this GameController
 * @var Users[] $players Список игроков, с племенами
 * @var $game Game
 */
$this->setPageTitle( $this->game_title . ' - Кабинет Ведущего' );
?>

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