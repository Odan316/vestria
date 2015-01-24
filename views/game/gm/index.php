<?php
use diplomacy\modules\vestria\controllers\GameController;
/**
 * @var $this GameController
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
                'label'   => 'Игроки и фракции',
                'content' => $this->renderPartial( "gm/_players",
                    [ "players" => $players, "classesList" => $classesList, "provincesList" => $provincesList ], 1 ),
                'active'  => true
            ],
            [
                'label'   => 'Провинции',
                'content' => $this->renderPartial( "gm/_map",
                    [ "provincesList" => $provincesList ], 1 )
            ],
        ],
    ]
);
?>

<div class="clearfix"></div>