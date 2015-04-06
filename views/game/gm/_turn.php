<?php
use diplomacy\modules\vestria\controllers\GameController;

use diplomacy\modules\vestria\models\Game;

/**
 * @var $this GameController
 * @var $game Game
 */
?>
<div id="gm_turn_actions">
    <h3>Ход: <?= $game->getTurn(); ?></h3>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Рассчитать ход',
        'type'        => 'danger',
        'size'        => 'large',
        'url'         => $this->createUrl( 'turn/go' ),
        "htmlOptions" => [
            'class' => ""
        ]
    ] );
    ?>
</div>
<div id="gm_turnLog">
    <h3>Лог прошлого хода</h3>
    <?php foreach($game->getLog($game->getTurn()-1)->getRows() as $row){ ?>
        <p><?= $row->getText() ?></p>
    <?php }?>
</div>