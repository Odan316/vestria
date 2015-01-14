<?php
/**
 * @var $this GameController
 */
$this->setPageTitle($this->gameTitle.' - Панель игрока');
$character = $this->game->getCharacterByPlayerId(Yii::app()->user->getState('uid'));
?>

<div id="left_panel">
    <h2>Баланс</h2>
    <p>Ход: <span><?= $this->game->getTurn(); ?></span></p>
    <h4><?= $character->getName(); ?> (<?= $character->getClass()->getName(); ?>)</h4>
    <p>Фракция: <span><?= ( $character->getFaction() ? $character->getFaction()->getName() : "Нет" ) ?></span></p>
    <p>Черта: <span><?= $character->getTrait()->getName() ?></span></p>
    <p>Амбиция: <span><?= $character->getAmbition()->getName() ?></span></p>
    <p>Популярность: <span><?= $character->getPopularity() ?></span></p>
    <p>Состояние: <span><?= $character->getCash() ?> Д</span></p>
</div>
<div id="central_panel">
    <div id="players_map">
        <?= $this->game->getMap()->getSVG(550);?>
    </div>
    <div id="players_map_legend">

    </div>
</div>
<div id="right_panel">
    <h2>Заявка</h2>

</div>
<div class="clearfix"></div>