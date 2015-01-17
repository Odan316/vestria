<?php

/**
 * Class GameController
 *
 * Контроллер для работы в Кабинете (Ведущего или Игрока)
 */
class GameController extends VesController
{
    /**
     * По умолчанию, в зависимости от роли мы грузим
     * - либо страницу ГМа (для ГМа),
     * - либо страницу игрока (для остальных)
     */
    public function actionIndex()
    {
        if (Yii::app()->user->getState( 'game_role' ) == Game_roles::GM_ROLE) {
            $this->actionGM();
        } else {
            $this->actionPlayer();
        }
    }

    /**
     * Страница ГМа (только для ГМа)
     */
    public function actionGM()
    {
        // Сначала проверяем роль
        if (Yii::app()->user->getState( 'game_role' ) == Game_roles::GM_ROLE) {
            /** @var $ClientScript CClientScript */
            $ClientScript = Yii::app()->clientScript;
            $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/gm.js' );

            $this->render( 'gm', [
                'players'     => $this->gameModel->players_users,
                'classesList' => $this->game->getConfig()->getConfigAsList( "character_classes" ),
                'provincesList' => $this->game->getConfig()->getConfigAsList( "provinces" ),
                'mapSVG' => $this->game->getMap()->getSVG()
            ] );
        } else {
            $this->actionNoAccess();
        }
    }

    /**
     * Страница игрока
     */
    public function actionPlayer()
    {
        // Сначала проверяем роль
        if (Yii::app()->user->getState( 'game_role' ) == Game_roles::PLAYER_ROLE) {
            /** @var $ClientScript CClientScript */
            $ClientScript = Yii::app()->clientScript;
            $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/player.js' );

            $character = $this->game->getCharacterByPlayerId(Yii::app()->user->getState( 'uid' ));
            if(!$character){
                $this->render( 'player_setup', [
                    'classesList' => $this->game->getConfig()->getConfigAsList( "character_classes" ),
                    'provincesList' => $this->game->getConfig()->getConfigAsList( "provinces" )
                ] );
            } else {
                $this->render( 'player', [
                    'character' => $character,
                    'actions' => (new PlayerActionFinder($this->game, $character))->getActions()
                ] );
            }
        } else {
            $this->actionNoAccess();
        }
    }

    /**
     * Отображение заглушки, говорящей что страница не доступна этой роли
     */
    public function actionNoAccess()
    {
        $this->render( 'no_access' );
    }

    public function actionGetCharacterDataByPlayerId()
    {
        $playerId = htmlspecialchars( $_POST['playerId'] );

        $character = $this->game->getCharacterByPlayerId( $playerId );

        echo json_encode( $character );
    }

    public function actionGetPlayerData()
    {
        echo json_encode( Users::model()->findByPk( htmlspecialchars( $_POST['id'] ) ) );
    }

    public function actionGetTraitsByClassId()
    {
        $classId = htmlspecialchars( $_POST['classId'] );

        $list         = [ ];
        $traitsConfig = $this->game->getConfig()->getConfigAsArray( 'character_traits' );
        foreach ($traitsConfig['elements'] as $key => $values) {
            if (in_array( $classId, $values['classes'] )) {
                $list[] = [ "id" => $values['id'], "name" => $values['name'] ];
            }
        }

        echo json_encode( $list );
    }

    public function actionGetAmbitionsByClassId()
    {
        $classId = htmlspecialchars( $_POST['classId'] );

        $list            = [ ];
        $ambitionsConfig = $this->game->getConfig()->getConfigAsArray( 'character_ambitions' );
        foreach ($ambitionsConfig['elements'] as $key => $values) {
            if (in_array( $classId, $values['classes'] )) {
                $list[] = [ "id" => $values['id'], "name" => $values['name'] ];
            }
        }

        echo json_encode( $list );
    }

    public function actionSaveCharacter()
    {
        $characterData = $_POST['Character'];
        if ( ! empty( $characterData['id'] )) {
            echo $this->game->updateCharacter( $characterData );
        } else {
            echo $this->game->createCharacter( $characterData );
        }
    }


    public function actionGetFactionData()
    {
        echo json_encode( $this->game->getFaction( htmlspecialchars( $_POST['id'] ) ) );
    }

    public function actionSaveFaction()
    {
        $factionData = $_POST['Faction'];
        if ( ! empty( $factionData['id'] )) {
            echo $this->game->updateFaction( $factionData );
        } else {
            echo $this->game->createFaction( $factionData );
        }
    }

    public function actionGetProvinceData()
    {
        echo json_encode( $this->game->getProvince( htmlspecialchars( $_POST['id'] ) ) );
    }

    public function actionSaveProvince()
    {
        $data = $_POST['Province'];
        if ( ! empty( $data['id'] )) {
            echo $this->game->updateProvince( $data );
        } else {
            echo false;
        }
    }
}