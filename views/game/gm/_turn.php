<?php
use diplomacy\modules\vestria\controllers\GameController;

/**
 * @var $this GameController
 */
?>
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