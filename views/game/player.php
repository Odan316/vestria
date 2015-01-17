<?php
/**
 * @var $this GameController
 * @var $character Character
 * @var $actions PlayerAction[]
 */
$this->setPageTitle($this->getModule()->getTitle().' - Панель игрока');
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
<div id="right_panel" class="requests_list">
    <h2>Заявка</h2>
    <div class="request_block">
        <label for="requests[0]">1: </label>
        <select id="requests[0]" class="reguest_position" name="requests[0]">
            <option value="" selected="selected">Выбрать</option>
            <?php
            foreach($actions as $action){ ?>
                <option value="<?= $action->getId();?>"><?= $action->getName(); ?></option>
            <?php }?>
        </select>
        <div class="request_params"></div>
    </div>
</div>
<div class="clearfix"></div>