<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\VesController;
use diplomacy\modules\vestria\components\PlayerActionHandler;
use diplomacy\modules\vestria\models\PlayerAction;
/**
 * Class AjaxController
 *
 * Контроллер для работы с AJAX-запросами
 */
class AjaxController extends VesController
{
    public function actionGetCharacterDataByPlayerId()
    {
        $playerId = \Yii::app()->request->getPost( "playerId", 0 );

        $character = $this->game->getCharacterByPlayerId( $playerId );

        echo json_encode( $character );
    }

    public function actionGetPlayerData()
    {
        echo json_encode( \Users::model()->findByPk( \Yii::app()->request->getPost( "id", 0 ) ) );
    }

    public function actionGetTraitsByClassId()
    {
        $classId = \Yii::app()->request->getPost( "classId", 0 );

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
        $classId = \Yii::app()->request->getPost( "classId", 0 );

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
        $data = \Yii::app()->request->getPost( "Character", [ ] );
        if ( ! empty( $data['id'] )) {
            echo $this->game->updateCharacter( $data );
        } else {
            echo $this->game->createCharacter( $data );
        }
    }


    public function actionGetFactionData()
    {
        echo json_encode( $this->game->getFaction( \Yii::app()->request->getPost( "id", 0 ) ) );
    }

    public function actionSaveFaction()
    {
        $data = \Yii::app()->request->getPost( "Faction", [ ] );
        if ( ! empty( $data['id'] )) {
            echo $this->game->updateFaction( $data );
        } else {
            echo $this->game->createFaction( $data );
        }
    }

    public function actionGetProvinceData()
    {
        echo json_encode( $this->game->getProvince( \Yii::app()->request->getPost( "id", 0 ) ) );
    }

    public function actionSaveProvince()
    {
        $data = \Yii::app()->request->getPost( "Province", [ ] );
        if ( ! empty( $data['id'] )) {
            echo $this->game->updateProvince( $data );
        } else {
            echo false;
        }
    }

    public function actionGetActionParametersCode()
    {
        $actionId = \Yii::app()->request->getPost( "actionId", 0 );

        /** @var PlayerAction $action */
        $action = $this->game->getConfig()->getConfigElementById("player_actions", $actionId);

        $character = $this->game->getCharacterByPlayerId(\Yii::app()->user->getState( 'uid' ));

        echo (new PlayerActionHandler($this->game, $character))->getParametersCode($action);
    }

    public function actionSaveRequest()
    {
        $positions = \Yii::app()->request->getPost( "positions", [ ] );
        $character = $this->game->getCharacterByPlayerId( \Yii::app()->getUser()->getState('uid') );
        if(!empty($character)){
            $data = ["characterId" => $character->getId(), "positions" => $positions];
            $request = $this->game->getRequestByCharacterId($character->getId());
            if(!empty($request)){
                echo $this->game->updateRequest( $data );
            } else {
                echo $this->game->createRequest( $data );
            }
        } else {
            echo false;
        }
    }
}