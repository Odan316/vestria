<?php
use diplomacy\modules\vestria\controllers\GameController;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\CharacterAction;
use diplomacy\modules\vestria\models\Request;
/**
 * @var $this GameController
 * @var $character Character
 * @var $actions CharacterAction[]
 * @var $request Request
 */
$this->setPageTitle($this->getModule()->getTitle().' - Панель игрока');
?>

<div id="left_panel">
    <h2>Баланс</h2>
    <p>Ход: <span><?= $this->game->getTurn(); ?></span></p>
    <h4><?= $character->getName(); ?> (<?= $character->getClass()->getName(); ?>)</h4>
    <p>Сейчас в: <span><?= $character->getProvince()->getName(); ?></span></p>
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
<?= $this->renderPartial( "player/_requests",
    [ "character" => $character,  "actions" => $actions, "request" => $request ], 1 ) ?>
<div class="clearfix"></div>