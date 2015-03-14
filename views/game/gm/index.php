<?php
use diplomacy\modules\vestria\controllers\GameController;

use diplomacy\modules\vestria\models\Game;
/**
 * @var $this GameController
 * @var $game Game
 * @var $players Users[] Список игроков, с племенами
 * @var $classesList [] Список доступных классов для персонажей
 * @var $provincesList [] Список доступных провинций
 */
$this->setPageTitle( $this->getModule()->getTitle() . ' - Кабинет Ведущего' );
?>

<?php
$this->widget(
    'bootstrap.widgets.TbTabs',
    [
        'type' => 'pills',
        'tabs' => [
            [
                'label'   => 'Ход',
                'content' => $this->renderPartial( "gm/_turn",
                    ["game" => $game], 1 ),
                'active'  => true
            ],
            [
                'label'   => 'Игроки и фракции',
                'content' => $this->renderPartial( "gm/_players",
                    [ "players" => $players, "classesList" => $classesList, "provincesList" => $provincesList ], 1 )
            ],
            [
                'label'   => 'Провинции',
                'content' => $this->renderPartial( "gm/_map",
                    [ "provincesList" => $provincesList ], 1 )
            ]
        ],
    ]
);
?>

<div class="clearfix"></div>