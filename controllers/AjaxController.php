<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\ModelsFinder;
use diplomacy\modules\vestria\components\VesController;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\CharacterAction;

/**
 * Class AjaxController
 *
 * Контроллер для работы с AJAX-запросами
 */
class AjaxController extends VesController
{
    public function init()
    {
        if (\Yii::app()->request->isAjaxRequest)
        {
            $cs = \Yii::app()->clientScript;
            $cs->scriptMap = [
                'jquery.js' => false,
                'common.js' => false,
            ];
        }
        parent::init();
    }

    public function actionGetCharactersList()
    {
        $criteria = \Yii::app()->request->getPost( "criteria", [] );

        $models = $this->game->getCharacters($criteria, 1);

        echo json_encode( $models );
    }

    public function actionGetCharacterData()
    {
        $id = \Yii::app()->request->getPost( "id", 0 );

        $character = $this->game->getCharacter( $id );

        echo json_encode( $character );
    }

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

    public function actionGetTraitsList()
    {
        $id = \Yii::app()->request->getPost( "id", 0 );
        if(!empty($id)){
            $character = $this->game->getCharacter($id);
        } else {
            $classId = \Yii::app()->request->getPost( "classId", 0 );
            $character = new Character($this->game);
            $character->setClassId($classId);
        }

        $models = (new ModelsFinder($this->game))->findTraits($character);

        $list = $this->game->makeList($models);

        echo json_encode( $list );
    }

    public function actionGetAmbitionsList()
    {
        $id = \Yii::app()->request->getPost( "id", 0 );
        if(!empty($id)){
            $character = $this->game->getCharacter($id);
        } else {
            $classId = \Yii::app()->request->getPost( "classId", 0 );
            $character = new Character($this->game);
            $character->setClassId($classId);
        }

        $models = (new ModelsFinder($this->game))->findAmbitions($character);

        $list = $this->game->makeList($models);

        echo json_encode( $list );
    }

    public function actionSaveCharacter()
    {
        $data = \Yii::app()->request->getPost( "Character", [ ] );
        if ( ! empty( $data['id'] )) {
            echo $this->game->updateCharacter( $data );
        } else {
            $model = $this->game->createCharacter( $data );
            echo (!empty($model));
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
            $model = $this->game->createFaction( $data );
            echo (!empty($model));
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

    public function actionGetRequestPositionCode()
    {
        $character = $this->game->getCharacterByPlayerId(\Yii::app()->user->getState( 'uid' ));
        $actions = (new ModelsFinder($this->game))->findActions($character);
        echo $this->widget( "diplomacy\\modules\\vestria\\widgets\\RequestPositionWidget",
            [ "actions" => $actions, "position" => null, "i" => '' ], 1 );
    }

    public function actionGetActionParametersCode()
    {
        $actionId = \Yii::app()->request->getPost( "actionId", 0 );
        if(!empty($actionId)){
            $character = $this->game->getCharacterByPlayerId(\Yii::app()->user->getState( 'uid' ));
            /** @var CharacterAction $action */
            $action = $this->game->getConfig()->getConfigElementById("character_actions", $actionId);

            echo $this->renderPartial("action_parameters", ["action" => $action, "character" => $character], false, true);
        } else {
            echo "";
        }
    }

    public function actionDeletePosition()
    {
        $id = \Yii::app()->request->getPost( "id", 0 );
        $character = $this->game->getCharacterByPlayerId(\Yii::app()->user->getState( 'uid' ));
        $this->game->getRequestByCharacterId($character->getId())->deletePosition($id);
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
                $model = $this->game->createRequest( $data );
                echo (!empty($model));
            }
        } else {
            echo false;
        }
    }
}