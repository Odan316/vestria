<?php
use diplomacy\modules\vestria\controllers\GameController;

use diplomacy\modules\vestria\models\Game;

/**
 * @var $this GameController
 * @var $game Game
 */
?>
<div>Ход: <?= $game->getTurn(); ?></div> <br/>
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