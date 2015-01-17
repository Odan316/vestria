<?php

/**
 * Class AjaxController
 *
 * Контроллер для работы с AJAX-запросами
 */
class AjaxController extends VesController
{
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